<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TechnicalFileController extends Controller
{
   public function index()
   {
       
   }

   public function create(){
       return Inertia::render('Contents/Admin/CreateTechnicalFile');
   }
}
