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
    
    public function getStatusAttribute($value)
    {
        return $value ?'Essential' : 'Not Essential';

    }

    public function pharmaceuticalEstablishment(){
        return $this->belongsTo(PharmaceuticalEstablishment::class);
    }

    public function designation(){
        return $this->belongsTo(Designation::class);
    }

    public function classification(){
        return $this->belongsTo(Classification::class);
    }
}
