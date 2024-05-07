<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cheque extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'cheque';
    protected $primaryKey = 'cheque_id';
    public $timestamps = false;
    protected $fillable = [
        'cheque_id',
        's_id',
        'cheque_no',
        'cheque_amount',
        'cheque_bank_acc',
        'cheque_bank',
        'cheque_bank_branch',
        'cheque_date',
        'cheque_status',
        'cheque_posted',
        'teller_user_id',
        'temp_cheque_id',
    ];
    protected $dates = ['deleted_at'];
}
