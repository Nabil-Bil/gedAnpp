<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Dosage;
use App\Models\Form;
use App\Models\Medication;
use App\Models\Presentation;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\TechnicalFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class TechnicalFileController extends Controller
{   
    private function analyseAndTransformData(&$array,$compositionType,Model $model)
    {
        for($j=0;$j<count($array[$compositionType]);$j++){
            $composition=$model::find($array[$compositionType][$j]);
            $array[$compositionType][$j]=[
                'id'=> $composition->id,
                'value'=>$composition->value
            ];

        }
    }
    private function getMedications()
    {
        
        $medications = Medication::select('name', DB::raw('GROUP_CONCAT(code) as codes',), DB::raw('GROUP_CONCAT(form_id) as forms',), DB::raw('GROUP_CONCAT(presentation_id) as presentations'), DB::raw('GROUP_CONCAT(dosage_id) as dosages'))->groupBy('name')
            ->get();
        $medications->map(function ($column) {
            $column->codes = explode(',', $column->codes);
            $column->forms = explode(',', $column->forms);
            $column->presentations = explode(',', $column->presentations);
            $column->dosages = explode(',', $column->dosages);
        });
        
        $medicationsArrays = [];
        for ($i = 0; $i < count($medications); $i++) {
            array_push($medicationsArrays, $medications[$i]->toArray());
            $medicationsArrays[$i]['forms']=array_unique($medicationsArrays[$i]['forms']);
            $medicationsArrays[$i]['presentations']=array_unique($medicationsArrays[$i]['presentations']);
            $medicationsArrays[$i]['dosages']=array_unique($medicationsArrays[$i]['dosages']);
            $form=new Form();
            $this->analyseAndTransformData($medicationsArrays[$i],'forms',$form);

            $presentation=new Presentation();
            $this->analyseAndTransformData($medicationsArrays[$i],'presentations',$presentation);

            $dosage=new Dosage();
            $this->analyseAndTransformData($medicationsArrays[$i],'dosages',$dosage);
        }
        for($i=0;$i<count($medicationsArrays);$i++){
            $allDcis = [];
            foreach ($medicationsArrays[$i]['codes'] as $code) {
                $dcis = Medication::find($code)->dcis;
                foreach ($dcis as $dci) {
                    array_push(
                        $allDcis,
                        ["id" => $dci->id, "value" => $dci->value]
                    );
                }
                 $medicationsArrays[$i]['dcis']=$allDcis;
            }
            $medicationsArrays[$i]["dcis"]= array_unique( $medicationsArrays[$i]["dcis"],SORT_REGULAR);
        }

        return $medicationsArrays;
    }
    public function index()
    {
        $allDocuments = [];
        foreach (TechnicalFile::all() as $file) {
            array_push($allDocuments, [
                'code' => $file->code,
                'status' => $file->status,
                'product_type' => $file->medication_code == null ? 'Device' : 'Medication',
                'product' => $file->medication_code == null ? $file->device_code : $file->medication_code,
                'module_number' => count($file->documents),
                'created_at' => $file->created_at,
            ]);
        }
        return Inertia::render('Contents/Admin/TechnicalFile', [
            'user_data' => $this->getUserData(),
            'technical_files' => $allDocuments
        ]);
    }

    public function create()
    {
        $medications=$this->getMedications();
        return Inertia::render(
            'Contents/Admin/CreateTechnicalFile',
            [
                'user_data' => $this->getUserData(),
                'medications' => $medications,
                'devices' => DB::table('devices')->select('name')->groupBy('name')->orderBy('name')->get(),
            ]
        );
    }



    public function destroy(Request $request)
    {
        foreach ($request->ids as $id) {
            $directory = 'technicalFiles/' . $id;
            Storage::disk('public')->deleteDirectory($directory);
            TechnicalFile::find($id)->delete();
        }
        return Redirect::route('technicalfile.index');
    }
}
