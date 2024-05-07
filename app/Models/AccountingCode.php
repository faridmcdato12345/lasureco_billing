<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingCode extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'accounting_codes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'parent_code',
        'main_code',
        'a_code',
        // 'g_code',
        'is_last'
    ];

    protected $dates = ['deleted_at'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M d, Y H:i:s a');
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M d, Y H:i:s a');
    }
    public function getDeletedAtAttribute($value)
    {
        if($value == NULL){
            return NULL;
        }

        return Carbon::parse($value)->format('M d, Y H:i:s a');
    }
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    // public function vouchers()
    // {
    //     return $this->belongsToMany(Voucher::class, 'accounting_code_voucher', 'accounting_code_id', 'voucher_id')
    //         ->withPivot('debit', 'credit', 'amount');
    // }
}
