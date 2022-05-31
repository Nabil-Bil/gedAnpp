<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medication extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $primaryKey = 'code';
    public $incrementing = false;
    public function getCreatedAtAttribute($value)
    {

        if (Route::current()->getName() == 'dashboard.home') {
            return $value;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function getStatusAttribute($value)
    {
        return $value ? 'Essential' : 'Not Essential';
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
    public function dosage()
    {
        return $this->belongsTo(Dosage::class);
    }
    public function presentation()
    {
        return $this->belongsTo(Presentation::class);
    }
    public function pharmaceuticalEstablishment()
    {
        return $this->belongsTo(PharmaceuticalEstablishment::class);
    }

    public function dcis()
    {
        return $this->belongsToMany(Dci::class);
    }
}
