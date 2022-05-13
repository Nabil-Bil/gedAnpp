<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classification;
use Illuminate\Support\Facades\Redirect;

class ClassificationController extends Controller
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
            '*' => 'required',
        ]);

        Classification::create($request->input());

        return Redirect(route('device.index'));
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


        Classification::find($id)->update($request->input());

        return Redirect::route("device.index");
    }


    public function destroy(Request $request)
    {
        foreach ($request->ids as $id) {
            Classification::find($id)->delete();
        }
        return Redirect::route('device.index');
    }
}
