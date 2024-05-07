<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signatory extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'signatory';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'signatory_title_id',
        'status',
    ];

    public function signatoryTitle()
    {
        return $this->belongsTo(SignatoryTitle::class);
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M d, Y H:i:s a');
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M d, Y H:i:s a');
    }
}
