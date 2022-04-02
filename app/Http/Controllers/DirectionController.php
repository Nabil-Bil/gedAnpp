<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Direction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DirectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allDirections=Direction::all();
        return Inertia::render('Contents/Admin/Directions', [
            'user_data' => $this->getUserData(),
            "directions"=>$allDirections,
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
            "name"=>["required"],
            "service"=>"required"
           ]);
        Direction::create([
            "name"=>$request->name,
            "service"=>$request->service,
        ]);
        return Redirect::route('directions.index');
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
        "name"=>["required"],
        "service"=>"required"
       ]);
        Direction::find($id)->update([
            "name"=>$request->name,
            "service"=>$request->service
        ]);

        return Redirect::route('directions.index');


    }

    public function destroy(Request $request){
        foreach($request->ids as $id){
            Direction::find($id)->delete();
        }
        return Redirect::route('directions.index');
        
    }


}
