<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Form;
use App\Models\User;
use App\Models\Device;
use App\Models\Dosage;
use App\Models\Medication;
use App\Models\Designation;
use App\Models\Presentation;
use App\Models\Classification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected function analyseAndTransformData(&$array, $compositionType, Model $model)
    {
        for ($j = 0; $j < count($array[$compositionType]); $j++) {
            $composition = $model::find($array[$compositionType][$j]);
            $array[$compositionType][$j] = [
                'id' => $composition->id??null,
                'value' => $composition->value??null,
            ];
        }
    }
    protected function getMedications()
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
            $medicationsArrays[$i]['forms'] = array_values(array_unique($medicationsArrays[$i]['forms']));
            $medicationsArrays[$i]['presentations'] = array_values(array_unique($medicationsArrays[$i]['presentations']));
            $medicationsArrays[$i]['dosages'] = array_values(array_unique($medicationsArrays[$i]['dosages']));
            $form = new Form();
            $this->analyseAndTransformData($medicationsArrays[$i], 'forms', $form);

            $presentation = new Presentation();
            $this->analyseAndTransformData($medicationsArrays[$i], 'presentations', $presentation);

            $dosage = new Dosage();
            $this->analyseAndTransformData($medicationsArrays[$i], 'dosages', $dosage);
        }
        for ($i = 0; $i < count($medicationsArrays); $i++) {
            $allDcis = [];
            foreach ($medicationsArrays[$i]['codes'] as $code) {
                $dcis = Medication::find($code)->dcis;
                foreach ($dcis as $dci) {
                    array_push(
                        $allDcis,
                        ["id" => $dci->id, "value" => $dci->value]
                    );
                }
                $medicationsArrays[$i]['dcis'] = $allDcis;
            }
            $medicationsArrays[$i]["dcis"] = array_values(array_unique($medicationsArrays[$i]["dcis"], SORT_REGULAR));
        }

        return $medicationsArrays;
    }



    protected function getDevices()
    {
        $devices = Device::select('name', DB::raw('GROUP_CONCAT(code) as codes',), DB::raw('GROUP_CONCAT(designation_id) as designations',), DB::raw('GROUP_CONCAT(classification_id) as classifications'))->groupBy('name')
            ->get();

        $devices->map(function ($column) {
            $column->codes = explode(',', $column->codes);
            $column->designations = explode(',', $column->designations);
            $column->classifications = explode(',', $column->classifications);
        });
        $devicesArray = [];

        for ($i = 0; $i < count($devices); $i++) {
            array_push($devicesArray, $devices[$i]->toArray());
            $devicesArray[$i]['designations'] = array_values(array_unique($devicesArray[$i]['designations']));
            $devicesArray[$i]['classifications'] = array_values(array_unique($devicesArray[$i]['classifications']));
            $designation = new Designation();
            $this->analyseAndTransformData($devicesArray[$i], 'designations', $designation);

            $classification = new Classification();
            $this->analyseAndTransformData($devicesArray[$i], 'classifications', $classification);
        }


        return $devicesArray;
    }


    public function getUserData()
    {
        try{
            $directionName=Auth::user()->direction->name;

        }catch(Exception $e){
            $directionName="Unknown";
        }
        
        return [
            "id"=>Auth::user()->id,
            "first_name" => Auth::user()->first_name,
            "last_name" => Auth::user()->last_name,
            "email" => Auth::user()->email,
            "role" => Auth::user()->role,
            "path_image" =>Auth::user()->path_image==null?null:asset(Storage::url(Auth::user()->path_image)),
            "direction"=>$directionName,
        ];
    }
}
