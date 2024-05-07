<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherType extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'voucher_types';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'fund_id'
    ];
}
