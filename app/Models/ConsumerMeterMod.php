<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumerMeterMod extends Model
{
    use HasFactory;

    protected $table = 'cons_meter_mod';
    protected $primaryKey = 'cmm_id';
    public $timestamps = false;
    protected $fillable = [
        'cmm_id',
        'cm_id',
        'cmm_new_meter_serial',
        'cmm_old_meter_serial',
        'cmm_date',
        'cmm_remarks',
        'cmm_om_final_read',
    ];
}
