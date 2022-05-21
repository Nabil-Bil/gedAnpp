<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Form;
use Inertia\Inertia;
use App\Models\Device;
use App\Models\Dosage;
use App\Models\Document;
use App\Models\Medication;
use App\Models\Designation;
use App\Models\Presentation;
use Illuminate\Http\Request;
use App\Models\TechnicalFile;
use App\Models\Classification;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class TechnicalFileController extends Controller
{
    private function analyseAndTransformData(&$array, $compositionType, Model $model)
    {
        for ($j = 0; $j < count($array[$compositionType]); $j++) {
            $composition = $model::find($array[$compositionType][$j]);
            $array[$compositionType][$j] = [
                'id' => $composition->id??null,
                'value' => $composition->value??null,
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



    private function getDevices()
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request->product_type == 'medication') {

            $data = [
                "code" => $request->code,
                "status" => $request->status,
                "medication_name" => "$request->medication",
                "dcis" => $request->dci,
                "presentation" => $request->presentation,
                "form" => $request->form,
                "dosage" => $request->dosage
            ];

            if (!empty($request->file())) {

                foreach ($request->file('files') as $key => $file) {
                    $data['files'][$key] = ["module" => $request->input('files')[$key]['module'], "file" => $file["value"]];
                }
            } else {
                $data['files'] = null;
            }

            $newRequest = new Request($data);

            //Validation
            $newRequest->validate([
                'code' => ['required', Rule::Unique('technical_files', 'code')],
                'status' => ['required', Rule::in([
                    "Réception",
                    "Recevable",
                    "Etude technico règlementaire",
                    "Evaluation technique",
                    "Contrôle Qualité",
                    "Elaboration décision d’enregistrement",
                    "DE remise",
                ])],
                'medication_name' => ['required', Rule::exists('medications', 'name')],
                'dcis' => ['required', Rule::exists('dci_medication', 'dci_id')],
                'form' => ['required', Rule::exists('medications', 'form_id')],
                'presentation' => ['required', Rule::exists('medications', 'presentation_id')],
                'dosage' => ['required', Rule::exists('medications', 'dosage_id')],
                'files' => ['required', 'array', 'min:1', 'max:5'],
                'files.*.file' => ['required', 'file', 'mimes:pdf'],
                'files.*.module' => ['required', 'distinct'],
            ], [   
                'files.*.module.distinct' => "The files must not have same module number"
            ]);


            $medication = Medication::where([
                ['name', $newRequest->medication_name],
                ['form_id', $newRequest->form],
                ['dosage_id', $newRequest->dosage],
                ['presentation_id', $newRequest->presentation],
            ])->get();
            if ($medication->isEmpty()) {
                return Redirect::back(303)->withErrors(['code' => 'Medication not found']);
            }

            if (!$medication[0]->dcis()->where('dci_id', $newRequest->dcis)->exists()) {
                return Redirect::back()->withErrors(['code' => 'Dcis not found for the selected Medication']);
            }



            //Storing Data
            $pathDirectory = 'technicalFiles/' . $newRequest->code;
            Storage::disk('public')->makeDirectory($pathDirectory);
            $technicalFile = new TechnicalFile([
                'code' => $newRequest->code,
                'status' => $newRequest->status,
                'medication_code' => $medication[0]->code,
            ]);

            $technicalFile->save();
            foreach ($newRequest->input('files') as $file) {
                $fileName = Carbon::now()->timestamp . $file['file']->getClientOriginalName();

                Storage::disk('public')->putFileAs($pathDirectory, $file['file'], $fileName);
                $document = Document::create([
                    'name' => pathinfo($file['file']->getClientOriginalName(), PATHINFO_FILENAME),
                    'path' => $pathDirectory . '/' . $fileName,
                    'module_number' => $file['module'],
                    'technical_file_code' => $newRequest->code
                ]);
                $document->save();
            }
        } else if ($request->product_type == 'device') {
            $data = [
                "code" => $request->code,
                "status" => $request->status,
                "device_name" => $request->device,
                "designation" => $request->designation,
                "classification" => $request->classification,
            ];
            if (!empty($request->file())) {

                foreach ($request->file('files') as $key => $file) {
                    $data['files'][$key] = ["module" => $request->input('files')[$key]['module'], "file" => $file["value"]];
                }
            } else {
                $data['files'] = null;
            }

            $newRequest = new Request($data);
            $newRequest->validate([
                'code' => ['required', Rule::Unique('technical_files', 'code')],
                'status' => ['required', Rule::in([
                    "Réception",
                    "Recevable",
                    "Etude technico règlementaire",
                    "Evaluation technique",
                    "Contrôle Qualité",
                    "Elaboration décision d’homologation",
                    "DH remise",
                ])],
                'device_name' => ['required', Rule::exists('devices', 'name')],
                'designation' => ['required', Rule::exists('devices', 'designation_id')],
                'classification' => ['required', Rule::exists('devices', 'classification_id')],
                'files' => ['required', 'array', 'min:1', 'max:5'],
                'files.*.file' => ['required', 'file', 'mimes:pdf'],
                'files.*.module' => ['required', 'distinct'],
            ], [
                'files.*.module.distinct' => "The files must not have same module number"
            ]);
            $device = Device::where([
                ['name', $newRequest->device_name],
                ['designation_id', $newRequest->designation],
                ['classification_id', $newRequest->classification],
            ])->get();
            if ($device->isEmpty()) {
                return Redirect::back()->withErrors(['code' => 'Device not found']);
            }



            //Storing Data
            $pathDirectory = 'technicalFiles/' . $newRequest->code;
            Storage::disk('public')->makeDirectory($pathDirectory);
            $technicalFile = new TechnicalFile([
                'code' => $newRequest->code,
                'status' => $newRequest->status,
                'device_code' => $device[0]->code,
            ]);
            $technicalFile->save();
            foreach ($newRequest->input('files') as $file) {
                $fileName = Carbon::now()->timestamp . $file['file']->getClientOriginalName();

                Storage::disk('public')->putFileAs($pathDirectory, $file['file'], $fileName);
                $document = Document::create([
                    'name' => pathinfo($file['file']->getClientOriginalName(), PATHINFO_FILENAME),
                    'path' => $pathDirectory . '/' . $fileName,
                    'module_number' => $file['module'],
                    'technical_file_code' => $newRequest->code
                ]);
                $document->save();
            }
        } else {
            return abort(409);
        }


        return Redirect::route('technicalfile.index');
    }
    public function create()
    {
        $medications = $this->getMedications();
        $devices = $this->getDevices();
        return Inertia::render(
            'Contents/Admin/CreateTechnicalFile',
            [
                'user_data' => $this->getUserData(),
                'medications' => $medications,
                'devices' => $devices,
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
