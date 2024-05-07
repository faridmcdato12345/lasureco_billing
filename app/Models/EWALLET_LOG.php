<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EWALLET_LOG extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'e_wallet_log';
    public $timestamps = false;
    protected $fillable = [
        'ew_id',
        'ewl_amount',
        'ewl_remark',
        'ewl_status',
        'ewl_or',
        'ewl_or_date',
        'ewl_ap_billing',
        'ewl_date',
        'ewl_ap_billing_user_id',
    ];
}
