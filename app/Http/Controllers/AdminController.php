<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    

    public function index()
    {
        return Inertia::render('Contents/Home', [
            'user_data' => $this->getUserData(),
            'users_number' => DB::select('select role,count(*) as number from users group by role')
        ]);
    }
    public function registerView()
    {

        return Inertia::render('Auth/Register', [
            'user_data' => $this->getUserData(),
            "directions"=>[
                ...Direction::all(['id',"name"]),
            ]
        ]);
    }

 
}
