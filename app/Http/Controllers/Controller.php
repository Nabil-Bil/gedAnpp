<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getUserData()
    {
        try{
            $directionName=Auth::user()->direction->name;

        }catch(Exception $e){
            $directionName="Unknown";
        }
        
        return [
            "first_name" => Auth::user()->first_name,
            "last_name" => Auth::user()->last_name,
            "email" => Auth::user()->email,
            "role" => Auth::user()->role,
            "path_image" =>asset(Storage::url(Auth::user()->path_image)),
            "direction"=>$directionName,
        ];
    }
}
