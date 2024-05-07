<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FieldFinding extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'field_finding';
    protected $primaryKey = 'ff_id';
    public $timestamps = false;
    protected $fillable = [
        'ff_id',
        'ff_type',
        'ff_code',
        'ff_desc',
        'ff_ffinding_average'
    ];

    protected $dates = ['deleted_at'];
}
