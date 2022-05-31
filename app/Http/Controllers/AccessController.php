<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Medication;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TechnicalFile;
use Carbon\Carbon;

class AccessController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'administrateur') {

            $files = TechnicalFile::select('code', 'created_at')
            ->whereYear('created_at',date('Y'))
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('m'); // grouping by months
            });
            $medications=Medication::select('code', 'created_at')
            ->whereYear('created_at',date('Y'))
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('m'); // grouping by months
            });

            $devices=Device::select('code', 'created_at')
            ->whereYear('created_at',date('Y'))
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('m'); // grouping by months
            });
            
            $filesmcount = [];
            $medicationCount=[];
            $deviceCount=[];

            $filesArr = [];
            $medicationArr = [];
            $deviceArr = [];
            
            foreach ($files as $key => $value) {
                $filesmcount[(int)$key] = count($value);
            }
            foreach ($medications as $key => $value) {
                $medicationCount[(int)$key] = count($value);
            }
            foreach ($devices as $key => $value) {
                $deviceCount[(int)$key] = count($value);
            }
            
            for($i = 1; $i <= 12; $i++){
                if(!empty($filesmcount[$i])){
                    array_push($filesArr,$filesmcount[$i]);
                }else{
                    array_push($filesArr,0);  
                }
                if(!empty($medicationCount[$i])){
                    array_push($medicationArr,$medicationCount[$i]);
                }else{
                    array_push($medicationArr,0);  
                }
                if(!empty($deviceCount[$i])){
                    array_push($deviceArr,$deviceCount[$i]);
                }else{
                    array_push($deviceArr,0);  
                }
            }

            
            return Inertia::render('Contents/Admin/Home', [
                'files'=>$filesArr,
                'user_data' => $this->getUserData(),
                'users_number' => DB::select('select role,count(*) as number from users group by role'),
                'medications'=>$medicationArr,
                'devices'=>$deviceArr,
            ]);
        } else {
            $devices = DB::table('devices')->groupBy('name')->orderBy('name')->get(['name']);
            $classifications = DB::table('classifications')->get(['id','value']);
            $designations = DB::table('designations')->get(['id','value']);


            $medications = DB::table('medications')->groupBy('name')->orderBy('name')->get(['name']);
            $dosages = DB::table('dosages')->get(['id','value']);
            $forms = DB::table('forms')->get(['id','value']);
            $presentations = DB::table('presentations')->get(['id','value']);
            $dcis = DB::table('dcis')->get(['id','value']);

            $pharmaceuticalEstablishments = DB::table('pharmaceutical_establishments')->orderBy('name')->get(['id', 'name']);



            return Inertia::render(
                'Contents/TechnicalFileSearch',
                [
                    'userData' => $this->getUserData(),
                    'medications' => $medications,
                    'devices' => $devices,
                    'pharmaceuticalEstablishments' => $pharmaceuticalEstablishments,
                    'classifications' => $classifications,
                    'designations' => $designations,
                    'dosages' => $dosages,
                    'forms' => $forms,
                    'presentations' => $presentations,
                    'dcis' => $dcis,

                ]
            );
        }
    }
}
