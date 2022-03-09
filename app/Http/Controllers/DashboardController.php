<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Contents/Main',[
            'user_data'=>Auth::user(),
        ]);
    }

    public function library()
    {
     return Inertia::render('Contents/Library',[
        'user_data'=>Auth::user(),
    ]);
    }

    public function following()
    {
        return Inertia::render('Contents/Following',[
            'user_data'=>Auth::user(),
        ]);
    }
}
