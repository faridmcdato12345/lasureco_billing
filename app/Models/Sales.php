<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $table = 'sales';
    protected $primaryKey = 's_id';
    public $timestamps = false;
    protected $fillable = [
        's_id',
        'mr_id',
        'f_id',
        'ct_id',
        'cons_accountno',
        's_or_num',
        'cm_id',
        's_or_amount',
        'v_id',
        's_bill_no',
        's_bill_amount',
        's_bill_date',
        's_status',
        's_mode_payment',
        'cheque_id',
        's_ref_no',
        'ref_date',
        'teller_user_id',
        'temp_teller_user_id',
        's_ack_receipt',
        'ackn_date',
        'ackn_user_id',
        'temp_ackn_user_id',
        'mr_arrear',
        'e_wallet_applied',
        'e_wallet_added',
        'ap_status',
        's_cutoff',
        'deleted_at',
        'server_added',
        's_bill_date_time'
    ];

    // protected $dates = ['deleted_at'];

    // public function getSBillAmountAttribute($value){
    //     return floatval($value);
    // }

    public function meter_reg(): BelongsTo{
        return $this->belongsTo(MeterReg::class,'mr_id');
    }
    public function consumer(): BelongsTo{
        return $this->belongsTo(Consumer::class,'cm_id');
    }
}
