<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeterMaster extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'meter_master';
    protected $primaryKey = 'mm_id';
    public $timestamps = false;
    protected $fillable = [
        'mm_id',
        'mb_id',
		'mc_id',
		'mm_brand',
		'mm_serial_no',
		'mm_side_seal',
		'mm_terminal_seal',
		'mm_catalog_number',
		'mm_class',
		'mm_rr',
		'mm_kh',
		'mm_ampere',
		'mm_type',
		'mm_stator',
		'mm_jaws',
		'mm_rs',
		'mm_form',
		'mm_wire',
		'mm_volts',
		'mm_phase',
		'mm_demand_type',
		'mm_time_interval',
		'mm_tsf_factor',
		'mm_kwh_multiplier',
		'mm_owner',
		'mm_logo_seal',
		'mm_digital',
		'mm_area',
		'mm_on_stock',
		'mm_others',
		'mm_erc_seal',
		'mm_wire_type',
		'mm_meter_box',
		'mm_base_meter_seal',
		'mm_last_calib_date',
		'mm_prev_energy_rdg',
		'mm_prev_demand_rdng',
		'mm_pt_ratio',
		'mm_ct_ratio',
		'mm_demand_rate',
		'mm_min_energy_kwh',
		'mm_min_dem_kw',
		'mm_max_dem_kw',
		'mm_percent_rate',
		'mm_billing_determ',
		'mm_loc_pms_no',
		'mm_remarks',
		'mm_fullacct_no',
		'mm_name',
		'mm_accuracy_perc',
		'mm_asfound',
		'mm_asleft',
		'mm_min_demand_kwh',
		'mm_max_demand_kwh',
		'mm_prev_kvarh_rdng',
		'mm_coopown',
		'mm_kw_multiplier',
		'mm_kvar_multiplier',
		'mm_last_calib_time',
		'mm_meter_digits',
		'mm_metering_type',
		'mm_kind',
		'mm_seal_type',
		'mm_seal1',
		'mm_seal2',
		'mm_seal3',
		'mm_seal4',
		'mm_seal5',
		'mm_seal6',
		'mm_color1',
		'mm_color2',
		'mm_color3',
		'mm_color4',
		'mm_color5',
		'mm_color6',
		'mm_userid',
		'mm_userdate',
		'mm_usertime',
		'mm_status',
		'mm_statusdate',
		'mm_sm_no',
		'mm_condition',
    ];

	public function consumer()
    {
        return $this->hasOne(Consumer::class, 'mm_id');
    }
}
