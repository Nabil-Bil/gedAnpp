<?php

namespace App\Http\Controllers;

use App\Models\Dosage;
use App\Models\Form;
use App\Models\Presentation;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CompositionController extends Controller
{
    public function index()
    {
        return Inertia::render('Contents/Admin/Composition.vue',[
            'user_data'=>$this->getUserData(),
            'forms'=>Form::orderBy('created_at','DESC')->get(),
            'dosages'=>Dosage::orderBy('created_at','DESC')->get(),
            'presentations'=>Presentation::orderBy('created_at','DESC')->get(),
        ]);
    }

}
