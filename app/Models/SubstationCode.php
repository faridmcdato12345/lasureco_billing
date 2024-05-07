<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubstationCode extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'substation_code';
    protected $primaryKey = 'sc_id';
    public $timestamps = false;
    protected $fillable = [
        'sc_id',
        'sc_desc',
        'sc_address',
        'sc_rating',
        'sc_voltprim',
        'sc_voltsecond',
        'sc_xr_ratio',
        'sc_exciting_curr',
        'sc_simpedence',
        'sc_coreloss',
        'sc_copperloss',
        'sc_noloadloss',
        'sc_con_type',
    ];

    protected $dates = ['deleted_at'];

    public function poleMasters()
    {
        return $this->hasMany(PoleMaster::class,'sc_id');
    }
    public function transformers()
    {
        return $this->hasMany(Transformer::class,'sc_id');
    }
    public function feederCodes()
    {
        return $this->hasMany(FeederCode::class,'sc_id');
    }
}
