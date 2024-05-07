<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeterCondition extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'meter_cond';
    protected $primaryKey = 'mc_id';
    public $timestamps = false;
    protected $fillable = [
        'mc_id',
        'mc_desc'
    ];

    public function meterMasters()
    {
        return $this->hasMany(MeterMaster::class,'mc_id');
    }
}
