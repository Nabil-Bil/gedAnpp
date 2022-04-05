<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AccessController extends Controller
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
}
