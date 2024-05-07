<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumerOtherMod extends Model
{
    use HasFactory;

    protected $primaryKey = 'com_id';
    // protected $guarded = [];
    protected $table = 'cons_other_mod';
    public $timestamps = false;
    protected $fillable = [
        'cm_id',
        'com_type',
        'com_date',
        'com_old_info',
        'com_new_info',
        'user_id',
    ];
}
