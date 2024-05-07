<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdjustedPowerBill extends Model
{
    use HasFactory;

    protected $table = 'adjusted_powerbill';
    protected $primaryKey = 'ap_id';
    public $timestamps = false;
    protected $fillable = [
        'ap_id',
        'mr_id',
        'ap_old_kwh',
        'ap_new_kwh',
        'ap_old_amount',
        'ap_new_amount',
        'ap_old_pres_reading',
        'ap_new_pres_reading',
        'ap_old_prev_reading',
        'ap_new_prev_reading',
        'ap_old_dem_kwh_used',
        'ap_new_dem_kwh_used',
        'ap_date',
        'ap_user',
    ];
}
