<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LifeLineRate extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'lifeline_rates';
    protected $primaryKey = 'll_id';
    public $timestamps = false;
    protected $fillable = [
        'll_id',
        'll_min_kwh',
        'll_max_kwh',
        'll_discount',
    ];

    protected $dates = ['deleted_at'];
}
