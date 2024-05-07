<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelBill extends Model
{
    use HasFactory;

    protected $table = 'canceled_bill';
    protected $primaryKey = 'cb_id';
    public $timestamps = false;
    protected $fillable = [
        'cb_id',
        // 'mr_id',
        'cm_id',
        'cb_date_year_month',
        'cb_kwh_used',
        'cb_amount',
        'cb_bill_num',
        'user_id',
        'cb_date',
    ];
}
