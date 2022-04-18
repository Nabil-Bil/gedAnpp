<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\PharmaceuticalEstablishment;

class PharmaceuticalEstablishmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Contents/Admin/PharmaceuticalEstablishment.vue', [
            'user_data' => $this->getUserData(),
            'PharmaceuticalEstablishments' => PharmaceuticalEstablishment::orderBy('created_at', 'DESC')->get(),
        ]);
    }

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
            '*' => 'required',
            'email' => 'required|email',
        ], ['tech_manager_name.required' => 'The technical manager name is required','manager_name.required'=>"The manager name field is required"]);

        PharmaceuticalEstablishment::create($request->input());

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
            '*' => 'required'
        ]);
        $data = $request->input();
        unset($data['updated_at']);
        unset($data['created_at']);
        unset($data['id']);

        PharmaceuticalEstablishment::find($id)->update($data);

        return Redirect::route("pharmaceuticalEstablishment.index");
    }


    public function destroy(Request $request)
    {
        foreach ($request->ids as $id) {
            PharmaceuticalEstablishment::find($id)->delete();
        }
        return Redirect::route('pharmaceuticalEstablishment.index');
    }
}
