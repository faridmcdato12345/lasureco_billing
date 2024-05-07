<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RouteCode extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'route_code';
    protected $primaryKey = 'rc_id';
    public $timestamps = false;
    protected $fillable = [
        'rc_id',
        'tc_id',
        'rc_code',
        'rc_desc',
    ];

    public function townCode()
    {
        return $this->belongsTo(TownCode::class,'tc_id');
    }
    public function consumers()
    {
        return $this->hasMany(Consumer::class,'rc_id');
    }
}
