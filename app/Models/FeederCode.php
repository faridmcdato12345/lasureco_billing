<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FeederCode extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'feeder_code';
    protected $primaryKey = 'fc_id';
    public $timestamps = false;
    protected $fillable = [
        'fc_id',
        'sc_id',
        'fc_code',
        'fc_desc'
    ];

    protected $dates = ['deleted_at'];

    public function poleMasters()
    {
        return $this->hasMany(PoleMaster::class,'fc_id');
    }
    public function transformers()
    {
        return $this->hasMany(Transformer::class,'fc_id');
    }
}
