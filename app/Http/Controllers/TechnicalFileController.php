<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Device;
use App\Models\Document;
use App\Models\Medication;
use Illuminate\Http\Request;
use App\Models\TechnicalFile;
use App\Models\PharmaceuticalEstablishment;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class TechnicalFileController extends Controller
{



    public function index()
    {
        $allDocuments = [];
        foreach (TechnicalFile::all() as $file) {
            array_push($allDocuments, [
                'code' => $file->code,
                'status' => $file->status,
                'product_type' => $file->dci_medication_id == null ? 'Device' : 'Medication',
                'product' => $file->dci_medication_id == null ? $file->device_code : $file->dci_medication_id,
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
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        $data = $request->query();
        if (array_key_exists('product_type', $data) && array_key_exists('technicalFileData', $data)) {
            $inputData = [];
            $inputData['product_type'] = $data['product_type'];

            //technical Files
            $technicalFilesData = [];
            foreach ($data["technicalFileData"] as $k => $v) {
                if ($v != null) {
                    $technicalFilesData[$k] = $v;
                }
            }
            if (!empty($technicalFilesData)) {
                $inputData["technicalFileData"] = $technicalFilesData;
            }

            $technicalFiles = TechnicalFile::all();
            if (array_key_exists('technicalFileData', $inputData)) {
                foreach ($inputData['technicalFileData'] as $key => $value) {
                    $technicalFiles = $technicalFiles->where($key, $value);
                }
            }

            if ($inputData['product_type'] == 'medication') {
                $technicalFiles = $technicalFiles->whereNotNull('dci_medication_id');

                $medicationData = [];
                foreach ($data['medicationData'] as $key => $value) {
                    if ($value != null) {
                        $medicationData[$key] = $value;
                    }
                }
                if (!empty($medicationData)) {
                    $inputData["medicationData"] = $medicationData;
                }

                if (array_key_exists('medicationData', $inputData)) {
                    $medications = Medication::all();
                    foreach ($inputData['medicationData'] as $key => $value) {
                        if ($key == 'dci_id') {
                            continue;
                        }
                        $medications = $medications->where($key, $value);
                    }
                    $medicationsCode = [];
                    foreach ($medications as $medication) {
                        array_push($medicationsCode, $medication->code);
                    }

                    $dci_medication_ids = [];
                    if (!array_key_exists('dci_id', $inputData['medicationData'])) {
                        foreach ($medicationsCode as $medication) {
                            $m = DB::table('dci_medication')->select('id')->where('medication_code', $medication)->get()->toArray();

                            foreach ($m as $v) {

                                array_push($dci_medication_ids, $v->id);
                            }
                        }
                    } else {
                        foreach ($medicationsCode as $medication) {
                            $m = DB::table('dci_medication')->select('id')->where('medication_code', $medication)->where('dci_id', $inputData['medicationData']['dci_id'])->get()->toArray();

                            foreach ($m as $v) {
                                array_push($dci_medication_ids, $v->id);
                            }
                        }
                    }
                    $technicalFiles = $technicalFiles->whereIn("dci_medication_id", $dci_medication_ids);
                }
            } else if ($inputData['product_type'] == 'device') {
                $technicalFiles = $technicalFiles->whereNotNull('device_code');

                $deviceData = [];
                foreach ($data["deviceData"] as $k => $v) {
                    if ($v != null) {
                        $deviceData[$k] = $v;
                    }
                }
                if (!empty($deviceData)) {
                    $inputData["deviceData"] = $deviceData;
                }
                if (array_key_exists('deviceData', $inputData)) {
                    $devices = Device::all();
                    foreach ($inputData['deviceData'] as $key => $value) {
                        $devices = $devices->where($key, $value);
                    }
                    $devicesCodes = [];
                    foreach ($devices as $device) {
                        array_push($devicesCodes, $device->code);
                    }
                    $technicalFiles = $technicalFiles->whereIn("device_code", $devicesCodes);
                }
            } else {
                return abort(500);
            }
            $technicalFilesWithDocuments = [];

            if (!empty($technicalFiles->toArray())) {
                foreach ($technicalFiles as $tf) {
                    array_push($technicalFilesWithDocuments, [
                        'code' => $tf->code,
                        'status' => $tf->status,
                        'documents' => $tf->documentsWithFilters->toArray()
                    ]);
                }
            }
            return Inertia::render('Contents/TechnicalFilesResult', [
                'userData' => $this->getUserData(),
                'technicalFiles' => $technicalFilesWithDocuments,
                'product_type'=>$inputData['product_type']
            ]);
        } else {
            return abort(500);
        }
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
                "dosage" => $request->dosage,
                "pharmaceutical_establishment" => $request->pharmaceutical_establishment
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
                'pharmaceutical_establishment' => ['required', Rule::exists('medications', 'pharmaceutical_establishment_id')],
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
                ['pharmaceutical_establishment_id', $newRequest->pharmaceutical_establishment],
                ['name', $newRequest->medication_name],
                ['form_id', $newRequest->form],
                ['dosage_id', $newRequest->dosage],
                ['presentation_id', $newRequest->presentation],
            ])->get();
            if ($medication->isEmpty()) {
                return Redirect::back(303)->withErrors(['medication_name' => 'Medication not found for the selected data']);
            }
            if (!$medication[0]->dcis()->where('dci_id', $newRequest->dcis)->exists()) {
                return Redirect::back()->withErrors(['code' => 'Dcis not found for the selected Medication']);
            }



            //Storing Data
            $pathDirectory = 'technicalFiles/' . $newRequest->code;
            Storage::disk('public')->makeDirectory($pathDirectory);
            $dci_medication = DB::table('dci_medication')->where('medication_code', $medication[0]->code)->where('dci_id', $newRequest->dcis)->get()[0]->id;

            $technicalFile = new TechnicalFile([
                'code' => $newRequest->code,
                'status' => $newRequest->status,
                'dci_medication_id' => $dci_medication
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
                "pharmaceutical_establishment" => $request->pharmaceutical_establishment
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
                'pharmaceutical_establishment' => ['required', Rule::exists('devices', 'pharmaceutical_establishment_id')],
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
                ['pharmaceutical_establishment_id', $newRequest->pharmaceutical_establishment],
                ['name', $newRequest->device_name],
                ['designation_id', $newRequest->designation],
                ['classification_id', $newRequest->classification],
            ])->get();
            if ($device->isEmpty()) {
                return Redirect::back()->withErrors(['code' => 'Device not found for the selected data']);
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
        $pharmaceuticalEstablishments = PharmaceuticalEstablishment::all(['id', 'name']);
        return Inertia::render(
            'Contents/Admin/CreateTechnicalFile',
            [
                'user_data' => $this->getUserData(),
                'medications' => $medications,
                'devices' => $devices,
                'pharmaceuticalEstablishments' => $pharmaceuticalEstablishments
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

    public function changeStatus($code,Request $request)
    {
        $technicalFile=TechnicalFile::find($code);
        if($technicalFile==null){
            return abort(404);
        }

        $technicalFile->update([
            'status'=>$request->status
        ]);
        return Redirect::back();
    }
}
