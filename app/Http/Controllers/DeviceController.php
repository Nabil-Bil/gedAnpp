<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\Designation;
use Inertia\Inertia;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use App\Models\PharmaceuticalEstablishment;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $allDevices = [];
        foreach (Device::orderBy('created_at', 'DESC')->get()  as $device) {

            array_push($allDevices, [
                "code" => $device->code,
                "name" => $device->name,
                'type' => $device->type,
                "de_holder" => $device->de_holder,
                "role" => $device->role,
                "pharmaceutical_establishment_id" => $device->pharmaceuticalEstablishment ? $device->pharmaceuticalEstablishment->id : 0,
                "pharmaceutical_establishment" => $device->pharmaceuticalEstablishment ? $device->pharmaceuticalEstablishment->name : "Unknown",

                "designation_id"=> $device->designation ? $device->designation->id : 0,
                "designation"=>$device->designation ? $device->designation->value : "Unknwon",

                "classification_id"=>$device->classification ? $device->classification->id : 0,
                "classification"=>$device->classification ? $device->classification->value : "Unknwon",

                "characteristic"=>$device->characteristic,
                "status"=>$device->status,
                "created_at" => $device->created_at
            ]);
        }
        return Inertia::render('Contents/Admin/Device', [
            'user_data' => $this->getUserData(),
            'devices' => $allDevices,
            'designations'=>Designation::orderBy('created_at', 'DESC')->get(),
            'classifications'=>Classification::orderBy('created_at', 'DESC')->get(),
            "pharmaceutical_establishments" => PharmaceuticalEstablishment::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Contents/Admin/CreateDevice',[
            'user_data' => $this->getUserData(),
            "pharmaceutical_establishments" => PharmaceuticalEstablishment::all(),
            'designations'=>Designation::orderBy('created_at', 'DESC')->get(),
            'classifications'=>Classification::orderBy('created_at', 'DESC')->get(),
            
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
        $request->validate([
            '*' => 'required',
            'code' => ['required',Rule::unique('devices', 'code')],
            'pharmaceutical_establishment' => ['required',Rule::exists('pharmaceutical_establishments', 'id')],
            'designation' => ['required',Rule::exists('designations', 'id')],
            'classification' => ['required',Rule::exists('classifications', 'id')],
            'status'=>['required',Rule::in([true,false])]
        ],['de_holder.required' => 'The DE holder field is required', 'pharmaceutical_establishment.required' => 'The pharmaceutical establishment field is required', 'pharmaceutical_establishment.exists' => 'The selected pharmaceutical establishment is invalid.']);


        Device::create([
            'code'=>$request->code,
            'name'=>$request->name,
            'type'=>$request->type,
            'de_holder'=>$request->de_holder,
            'pharmaceutical_establishment_id'=>$request->pharmaceutical_establishment,
            'designation_id'=>$request->designation,
            'classification_id'=>$request->classification,
            'characteristic'=>$request->characteristic,
            'status'=>$request->status,

        ]);
        return Redirect::route('device.index');


    }

        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code)
    {
        $request->validate([
            '*' => 'required',
            'code' => Rule::unique('devices', 'code')->ignore($code, 'code'),
            'designation_id' =>Rule::exists('designations', 'id'),
            'classification_id' => Rule::exists('classifications', 'id'),
            'pharmaceutical_establishment_id' => Rule::exists('pharmaceutical_establishments', 'id'),
        ]);

        Device::find($code)->update([
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
            'de_holder' => $request->de_holder,
            'designation_id' => $request->designation_id,
            'classification_id'=>$request->classification_id,
            'characteristic'=>$request->characteristic,
            'pharmaceutical_establishment_id' => $request->pharmaceutical_establishment_id,
            'status' => $request->status=='Essential' ? 1:0,
        ]);

        return Redirect::route('device.index');
    }

    public function destroy(Request $request)
    {
        foreach ($request->codes as $code) {
            Device::find($code)->delete();
        }
        return Redirect::route('device.index');
    }
}
