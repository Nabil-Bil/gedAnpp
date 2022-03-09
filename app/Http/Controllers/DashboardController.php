<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Contents/Home',[
            'user_data'=>Auth::user(),
            'users_number'=>DB::select('select role,count(*) as number from users group by role')
        ]);
    }
    public function registerView()
    {
        return Inertia::render('Auth/Register',[
            'user_data'=>Auth::user(),
        ]);
    }
}
