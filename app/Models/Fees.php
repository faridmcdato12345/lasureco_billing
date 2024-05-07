<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fees extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'fees';
    protected $primaryKey = 'f_id';
    public $timestamps = false;
    protected $fillable = [
        'f_id',
        'f_code',
        'f_description',
        'f_amount',
        'f_vatable',
        'f_percent'
    ];
    protected $dates = ['deleted_at'];
}
