<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EWALLET extends Model
{
    use HasFactory;
    
    protected $table = 'e_wallet';
    protected $primaryKey = 'ew_id';
    public $timestamps = false;
    protected $fillable = [
        'ew_id',
        'cm_id',
        'ew_total_amount'
    ];
}
