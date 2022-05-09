<?php

namespace App\Http\Controllers;

use App\Models\Dci;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DciController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'value' => 'required',
        ]);

        Dci::create($request->input());

        return Redirect(route('medication.index'));
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
            '*' => 'required'
        ]);



        Dci::find($id)->update($request->input());

        return Redirect::route("medication.index");
    }


    public function destroy(Request $request)
    {
        foreach ($request->ids as $id) {
            Dci::find($id)->delete();
        }
        return Redirect::route('medication.index');
    }
}
