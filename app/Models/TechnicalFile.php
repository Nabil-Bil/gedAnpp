<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TechnicalFile extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $primaryKey='code';
    public $incrementing = false;
    protected $keyType = 'string';
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('m/d/Y');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

}
