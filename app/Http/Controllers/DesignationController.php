<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DesignationController extends Controller
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

        Designation::create($request->input());

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


        Designation::find($id)->update($request->input());

        return Redirect::route("device.index");
    }


    public function destroy(Request $request)
    {
        foreach ($request->ids as $id) {
            Designation::find($id)->delete();
        }
        return Redirect::route('device.index');
    }
}
