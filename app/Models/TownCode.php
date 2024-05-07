<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TownCode extends Model
{
    use HasFactory,SoftDeletes;
   
    protected $table = 'town_code';
    protected $primaryKey = 'tc_id';
    public $timestamps = false;
    protected $fillable = [
        'tc_id',
        'ac_id',
        'tc_code',
        'tc_name',
    ];
    protected $dates = ['deleted_at'];

    public function areaCode()
    {
        return $this->belongsTo(AreaCode::class,'ac_id');
    }
    public function routes(){
        return $this->hasMany(RouteCode::class);
    }
}

