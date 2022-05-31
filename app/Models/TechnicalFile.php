<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Route;

class TechnicalFile extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';
    public function getCreatedAtAttribute($value)
    {

        if (Route::current()->getName() == 'dashboard.home') {
            return $value;
        }
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function documentsWithFilters()
    {
        $directionModules = Auth::user()->directionModules->toArray();
        foreach ($directionModules as $key => $directionModule) {
            if ($directionModule == '0') {
                unset($directionModules[$key]);
            }
        }
        if (array_key_exists('one', $directionModules)) {
            $directionModules['one'] = 1;
        }
        if (array_key_exists('two', $directionModules)) {
            $directionModules['two'] = 2;
        }
        if (array_key_exists('three', $directionModules)) {
            $directionModules['three'] = 3;
        }
        if (array_key_exists('four', $directionModules)) {
            $directionModules['four'] = 4;
        }
        if (array_key_exists('five', $directionModules)) {
            $directionModules['five'] = 5;
        }

        return $this->hasMany(Document::class)->whereIn('module_number', array_values($directionModules))->select(['id', 'name', 'module_number', 'created_at']);
    }
}
