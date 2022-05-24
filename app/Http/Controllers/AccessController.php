<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PharmaceuticalEstablishment;

class AccessController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'administrateur') {
            return Inertia::render('Contents/Admin/Home', [
                'user_data' => $this->getUserData(),
                'users_number' => DB::select('select role,count(*) as number from users group by role'),
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
