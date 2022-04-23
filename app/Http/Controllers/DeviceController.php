<?php

namespace App\Http\Controllers;

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
                "designation"=>$device->designation,
                "classification"=>$device->classification,
                "characteristic"=>$device->characteristic,
                "duration"=>$device->duration,
                "created_at" => $device->created_at
            ]);
        }
        return Inertia::render('Contents/Admin/Device', [
            'user_data' => $this->getUserData(),
            'devices' => $allDevices,
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
        ],['de_holder.required' => 'The DE holder field is required', 'pharmaceutical_establishment.required' => 'The pharmaceutical establishment field is required', 'pharmaceutical_establishment.exists' => 'The selected pharmaceutical establishment is invalid.']);


        Device::create([
            'code'=>$request->code,
            'name'=>$request->name,
            'type'=>$request->type,
            'de_holder'=>$request->de_holder,
            'pharmaceutical_establishment_id'=>$request->pharmaceutical_establishment,
            'designation'=>$request->designation,
            'classification'=>$request->classification,
            'characteristic'=>$request->characteristic,
            'duration'=>$request->duration,

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
            'pharmaceutical_establishment_id' => Rule::exists('pharmaceutical_establishments', 'id'),
        ]);

        Device::find($code)->update([
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
            'de_holder' => $request->de_holder,
            'designation' => $request->designation,
            'classification'=>$request->classification,
            'characteristic'=>$request->characteristic,
            'duration'=>$request->duration,
            'pharmaceutical_establishment_id' => $request->pharmaceutical_establishment_id,
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
