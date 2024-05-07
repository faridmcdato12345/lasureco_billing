<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AreaCode extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'area_code';
    protected $primaryKey = 'ac_id';
    public $timestamps = false;
    protected $fillable = [
        'ac_id',
        'ac_name',
    ];
    protected $dates = ['deleted_at'];

    public function towns()
    {
        return $this->hasMany(TownCode::class,'ac_id');
    }
    
}
