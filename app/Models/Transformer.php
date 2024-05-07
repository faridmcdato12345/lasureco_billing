<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transformer extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'transformer';
    protected $primaryKey = 'trans_id';
    public $timestamps = false;
    protected $fillable = [
        'trans_id',
        'tcf_tsf_desc',
        'tsf_serial_no',
        'tb_id',
        'sc_id',
        'fc_id',
        'tsf_type',
        'tsf_kva',
        'tsf_noprimbush',
        'tsf_nosecbush',
        'tsf_phasetapping',
        'tsf_x_r_ratio',
        'tsf_perc_inpedence',
        'tsf_no_load_loss',
        'tsf_copperloss',
        'tsf_coreloss',
        'tsf_tl_test_result',
        'tsf_pole_no',
        'tsf_exciting_current',
        'tsf_voltageprimary',
        'tsf_voltsecondary',
        'tsf_connection_type',
            // 'install_date' => $this -> {validate}
            // 'install_by' => $this -> {validate}
        'tsf_location',
        'tsf_remarks',
        'tsf_ownership',
        'tsf_rental_fee',
            //'date_pulled_out' => $this -> {validate}
        'tsf_cor_x_tr',
        'tsf_cor_y_tr',
        'tsf_cor_z_tr',
    ];

    protected $dates = ['deleted_at'];

    public function meterMasters()
    {
        return $this->hasMany(MeterMaster::class,'trans_id');
    }
}
