<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoleMaster extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'pole_master';
    protected $primaryKey = 'pm_id';
    public $timestamps = false;
    protected $fillable = [
        'pm_id',
        'pm_pole_no',
        'sc_id',
        'fc_id',
        'pm_description',
        'pm_location',
        'pm_rental',
        'pm_tsf_no',
        'pm_line_section',
        'pm_pole_type',
        'pm_height',
        'pm_class',
        'pm_ownership',
        'pm_code',
        'pm_type',
        'pm_name',
        'pm_typexxx',
        'pm_pole',
        'pm_configuration',
        'pm_phasing',
        'pm_structure',
        'pm_account_full',
        'pm_tapping_point',
        'pm_phase_tapping',
        'pm_length',
        'pm_wire_size',
        'pm_wire_type',
        'pm_unit',
        'pm_feederxxxx',
        'pm_cor_x',
        'pm_cor_y',
        'pm_cor_z',
        'pm_typexxxx',
        'pm_status',
        'pm_userid',
        'pm_userdate',
        'pm_usertime',
    ];
}
