<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consumer extends Model implements Auditable
{
    use HasFactory,SoftDeletes,\OwenIt\Auditing\Auditable;

    protected $primaryKey = 'cm_id';
    protected $guarded = [];
    protected $table = 'cons_master';
    
    protected $dates = ['deleted_at'];

    public function meterRegs()
    {
        return $this->hasMany(MeterReg::class,'cm_id');
    }
    // public function eWallets()
    // {
    //     return $this->hasMany(MeterReg::class,'ac_id');
    // }
    public function routes(){
        return $this->belongsTo(RouteCode::class);
    }

    public function setCmFirstNameAttribute($value)
    {
        $this->attributes['cm_first_name'] = strtoupper($value);
    }
    public function setCmMiddleNameAttribute($value)
    {
        $this->attributes['cm_middle_name'] = strtoupper($value);
    }
    public function setCmLastNameAttribute($value){
        $this->attributes['cm_last_name'] = strtoupper($value);
    }
    public function setCmFullNameAttribute($value){
        $this->attributes['cm_full_name'] = strtoupper($value);
    }
    public function getCreatedAtAttribute($value){
        $d = strtotime($value);
        return $this->attributes['created_at'] = date('M d, Y', $d); 
    }
}
