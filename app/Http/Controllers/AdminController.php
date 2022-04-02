<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Direction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    

    public function index()
    {
        if(Auth::user()->role=='administrateur'){
            return Inertia::render('Contents/Admin/Home', [
                'user_data' => $this->getUserData(),
                'users_number' => DB::select('select role,count(*) as number from users group by role'),
            ]);
        }
        else{
            return Inertia::render('Contents/Evaluateur');
        }
        
    }
    public function registerView()
    {

        return Inertia::render('Auth/Register', [
            'user_data' => $this->getUserData(),
            "directions"=>Direction::all(['id',"name"]),
            
        ]);
    }

 
}
