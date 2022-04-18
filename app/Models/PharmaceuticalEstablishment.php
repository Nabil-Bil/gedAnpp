<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PharmaceuticalEstablishment extends Model
{
    use HasFactory;

    protected $guarded=['tech_manager_name.required'=>'The technical manager name is required'];
    protected $messages=[];
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }
}
