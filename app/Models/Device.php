<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $primaryKey = 'code';
    public $incrementing = false;

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function pharmaceuticalEstablishment(){
        return $this->belongsTo(PharmaceuticalEstablishment::class);
    }
}
