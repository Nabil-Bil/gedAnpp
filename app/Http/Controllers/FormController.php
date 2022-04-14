<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FormController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Contents/Admin/CreatePharmaceuticalEstablishment.vue', [
            'user_data' => $this->getUserData(),
            
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
            '*'=>'required',
            'email'=>'required|email',
        ]);

        Form::create($request->input());

        return Redirect(route('pharmaceuticalEstablishment.index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       

        $request->validate([
            '*'=>'required'
        ]);
        
        
        Form::find($id)->update($request->input());

        return Redirect::route("composition.index");
    }


    public function destroy(Request $request)
    {
        foreach ($request->ids as $id) {
            Form::find($id)->delete();
        }
        return Redirect::route('composition.index');
    }
}
