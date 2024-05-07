<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeterBrand extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'meter_brand';
    protected $primaryKey = 'mb_id';
    public $timestamps = false;
    protected $fillable = [
        'mb_id',
        'mb_code',
        'mb_name'
    ];

    protected $dates = ['deleted_at'];

    public function meterMasters()
    {
        return $this->hasMany(MeterMaster::class,'mb_id');
    }
}
