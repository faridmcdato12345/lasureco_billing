<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Or_Void extends Model
{
    use HasFactory;
    protected $table = 'or_void';
    protected $primaryKey = 'v_id';
    public $timestamps = false;
    protected $fillable = [
        'v_id',
        'mr_id',
        'v_bill_num',
        'v_sale_amount',
        'v_remark',
        'v_user',
        'v_or',
        'v_date'
    ];
}
