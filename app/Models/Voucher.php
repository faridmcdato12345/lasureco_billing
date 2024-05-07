<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'vouchers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'particular',
        'amount',
        'voucher_code',
        'ptto',
        'user_id',
        'funds_id'
    ];

    public function signatorys()
    {
        return $this->belongsToMany(Signatory::class);
    }
    public function funds()
    {
        return $this->belongsTo(Fund::class);
    }
    public function accountingCodeVoucher()
    {
        return $this->belongsToMany(AccountingCode::class)->withPivot('debit','credit','amount');
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M d, Y H:i:s a');
    }
    public function accountingCodes()
    {
        return $this->belongsToMany(AccountingCode::class, 'accounting_code_voucher', 'voucher_id', 'accounting_code_id')
            ->withPivot('debit', 'credit', 'amount');
    }
}
