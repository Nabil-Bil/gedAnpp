<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Direction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use PhpOption\None;

class DirectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $directions=[];
        foreach(Direction::orderBy('created_at','DESC')->get() as $direction){
            $modules=[];
        $direction->one==true ?array_push($modules,'one'):NULL;
        $direction->two==true ?array_push($modules,'two'):NULL;
        $direction->three==true ?array_push($modules,'three'):NULL;
        $direction->four==true ?array_push($modules,'four'):NULL;
        $direction->five==true ?array_push($modules,'five'):NULL;


            array_push($directions,[
                "id"=>$direction->id,
                "name"=>$direction->name,
                "service"=>$direction->service,
                "created_at"=>$direction->created_at,
                "modules"=>$modules
            ]);
        }
        return Inertia::render('Contents/Admin/Directions', [
            'user_data' => $this->getUserData(),
            "directions"=>$directions,
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

           $direction=new Direction([
            "name"=>$request->name,
            "service"=>$request->service,
            "one"=>in_array("one",$request->modulesAccess),
            "two"=>in_array("two",$request->modulesAccess),
            "three"=>in_array("three",$request->modulesAccess),
            "four"=>in_array("four",$request->modulesAccess),
            "five"=>in_array("five",$request->modulesAccess),
           ]);

        $direction->save();
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
            "service"=>$request->service,
            "one"=>in_array("one",$request->modules),
            "two"=>in_array("two",$request->modules),
            "three"=>in_array("three",$request->modules),
            "four"=>in_array("four",$request->modules),
            "five"=>in_array("five",$request->modules),

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
