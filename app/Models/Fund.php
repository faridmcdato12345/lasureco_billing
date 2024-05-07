<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fund extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'funds';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name'
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M d, Y H:i:s a');
    }
}
