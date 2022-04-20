<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Inertia\Inertia;
use App\Models\Dosage;
use App\Models\Medication;
use App\Models\Presentation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use App\Models\PharmaceuticalEstablishment;

class MedicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allMedications = [];
        foreach (Medication::orderBy('created_at', 'DESC')->get() as $medication) {

            array_push($allMedications, [
                'code' => $medication->code,
                'name' => $medication->name,
                'type' => $medication->type,
                'de_holder' => $medication->de_holder,
                'conditioning' => $medication->conditioning,

                'pharmaceutical_establishment_id' => $medication->pharmaceuticalEstablishment ? $medication->pharmaceuticalEstablishment->id : 0,
                'pharmaceutical_establishment' => $medication->pharmaceuticalEstablishment ? $medication->pharmaceuticalEstablishment->name : "Unknwon",

                'dosage_id' => $medication->dosage ? $medication->dosage->id : 0,
                'dosage' => $medication->dosage ? $medication->dosage->value : "Unknwon",

                'form_id' => $medication->form ? $medication->form->id : 0,
                'form' => $medication->form ? $medication->form->value : "Unknwon",

                'presentation_id' => $medication->presentation ? $medication->presentation->id : 0,
                'presentation' => $medication->presentation ? $medication->presentation->value : "Unknwon",

                "created_at" => $medication->created_at
            ]);
        }

        return Inertia::render('Contents/Admin/Medication.vue', [
            'user_data' => $this->getUserData(),
            'forms' => Form::orderBy('created_at', 'DESC')->get(),
            'dosages' => Dosage::orderBy('created_at', 'DESC')->get(),
            'presentations' => Presentation::orderBy('created_at', 'DESC')->get(),
            'pharmaceutical_establishments' => PharmaceuticalEstablishment::orderBy('created_at', 'DESC')->get(),
            'medications' => $allMedications
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Contents/Admin/CreateMedication.vue', [
            'user_data' => $this->getUserData(),  'forms' => Form::orderBy('created_at', 'DESC')->get(),
            'dosages' => Dosage::orderBy('created_at', 'DESC')->get(),
            'presentations' => Presentation::orderBy('created_at', 'DESC')->get(),
            'pharmaceutical_establishments' => PharmaceuticalEstablishment::orderBy('created_at', 'DESC')->get(),
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
            'code' => ['required', Rule::unique('medications', 'code')],
            'pharmaceutical_establishment' => [Rule::exists('pharmaceutical_establishments', 'id')],
            'form' =>[ 'required',Rule::exists('forms', 'id')],
            'dosage' => ['required',Rule::exists('dosages', 'id')],
            'presentation' => ['required',Rule::exists('presentations', 'id')],
        ], ['de_holder.required' => 'The DE holder field is required', 'pharmaceutical_establishment.required' => 'The pharmaceutical establishment field is required', 'pharmaceutical_establishment.exists' => 'The selected pharmaceutical establishment is invalid.']);

        Medication::create([
            'code'=>$request->code,
            'name'=>$request->name,
            'type'=>$request->type,
            'de_holder'=>$request->de_holder,
            'conditioning'=>$request->conditioning,
            'form_id'=>$request->form,
            'dosage_id'=>$request->dosage,
            'pharmaceutical_establishment_id'=>$request->pharmaceutical_establishment,
            'presentation_id'=>$request->presentation,

        ]);
        return Redirect::route('medication.index');
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
            'code' => Rule::unique('medications', 'code')->ignore($code, 'code'),
            'pharmaceutical_establishment_id' => Rule::exists('pharmaceutical_establishments', 'id'),
            'form_id' => Rule::exists('forms', 'id'),
            'dosage_id' => Rule::exists('dosages', 'id'),
            'presentation_id' => Rule::exists('presentations', 'id'),
        ]);

        Medication::find($code)->update([
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
            'conditioning' => $request->conditioning,
            'de_holder' => $request->de_holder,
            'pharmaceutical_establishment_id' => $request->pharmaceutical_establishment_id,
            'form_id' => $request->form_id,
            'presentation_id' => $request->presentation_id,
            'dosage_id' => $request->dosage_id,
        ]);

        return Redirect::route('medication.index');
    }

    public function destroy(Request $request)
    {
        foreach ($request->codes as $code) {
            Medication::find($code)->delete();
        }
        return Redirect::route('medication.index');
    }
}
