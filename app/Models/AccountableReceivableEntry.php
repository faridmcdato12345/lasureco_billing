<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountableReceivableEntry extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'acc_rec_entry';
    protected $primaryKey = 'are_id';
    public $timestamps = false;
    protected $fillable = [
        'are_id',
        'cm_id',
        'are_amount',
        'are_date'
    ];
    protected $dates = ['deleted_at'];
}
