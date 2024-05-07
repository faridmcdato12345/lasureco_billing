<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsumerType extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'cons_type';
    protected $primaryKey = 'ct_id';
    public $timestamps = false;
    protected $fillable = [
        'ct_id',
        'ct_code',
        'ct_desc'
    ];

    protected $dates = ['deleted_at'];
    
    public function billRates()
    {
        return $this->hasMany(BillRates::class,'cons_type_id');
    }
    public function consMasters()
    {
        return $this->hasMany(Consumer::class,'ct_id');
    }
}
