<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allUsers = [];
        foreach (User::all()->except(Auth::user()->id) as $user) {
            array_push($allUsers, [
                "id" => $user->id,
                "first_name" => $user->first_name,
                'last_name' => $user->last_name,
                "email" => $user->email,
                "role" => $user->role,
                "direction_id"=>$user->direction->id,
                "direction_name"=>$user->direction->name
            ]);
        }
        return Inertia::render('Contents/Admin/Users', [
            'user_data' => $this->getUserData(),
            'users' => $allUsers,
            "directions" => Direction::all(),
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
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => ['required', 'string', new Password, 'confirmed'],
            "direction" => ['required', 'exists:App\Models\Direction,id',],
            "role" => ['required', Rule::in(['super admin', 'directeur', 'evaluateur'])]
        ]);

        User::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'direction_id' => $request->direction,
            'role' => $request->role,
        ]);

        return Redirect::route("users.index");
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($id),
            ],
            "direction_id" => ['required', 'exists:App\Models\Direction,id',],
            "role" => ['required', Rule::in(['super admin', 'directeur', 'evaluateur'])]
        ]);

        User::find($id)->update([
            "direction_id"=>$request->direction_id,
            "first_name"=>$request->first_name,
            "last_name"=>$request->last_name,
            "email"=>$request->email,
            "role"=>$request->role,
        ]);

        return Redirect::route("users.index");
    }

    public function destroy(Request $request)
    {
        foreach ($request->ids as $id) {
            User::find($id)->delete();
        }
        return Redirect::route('users.index');
    }
}