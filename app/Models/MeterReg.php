<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeterReg extends Model
{
    use HasFactory,SoftDeletes;

    protected $primaryKey = 'mr_id';
    public $timestamps = false;
    protected $table = 'meter_reg';
    protected $fillable = [
        'br_id',
        'cm_id',
        'ff_id',
        'cons_account',
        'mr_bill_no',
        'mr_amount',
        'mr_partial',
        'mr_kwh_used',
        'mr_prev_reading',
        'mr_pres_reading',
        'mr_date_year_month',
        'mr_status',
        'mr_date_reg',
        'mr_due_date',
        'mr_discon_date',
        'mr_cancel_bill',
        'mr_printed',
        'mr_seq',
        'mr_pres_dem_reading',
        'mr_prev_dem_reading',
        'mr_dem_kwh_used',
        'mr_wrap',
        'mr_digit',
        'deleted_at',
        'mr_mtrReader',
        'mr_lfln_disc',
        'user_id',
        'mr_add_energy',
        'temp_cons_type',
        'temp_user_id',
        'uploaded_at',

    ];
    protected $dates = ['deleted_at'];
    

    public function getMrDateYearMonthAttribute($value)
    {
        return $this->attributes['mr_date_year_month'] =  date("F Y", strtotime(substr($value,0,4).'-'.substr($value,4,2)));
    } 
    
    public function getCmConStatusAttribute()
    {
        if($this->attributes['cm_con_status'] == 1)
        {
            return 'Active';
        }

        return 'Disconnected';
    }   
    // protected function serializeDate(DateTimeInterface $date)
    // {
    //     return $date->format('F Y');
    // }
    // protected $casts = [
    //     'mr_date_year_month' => 'datetime:F Y',
    // ];

    // public function getMrDateYearMonthAttribute( $value ) {
    //     return $this->attributes['mr_date_year_month'] = date("F Y", strtotime($value));
    // }

    // protected function asDateTime($value)
    // {
    //     return parent::asDateTime($value)->format('F Y');
    // }

    public function rates(): BelongsTo{
        return $this->belongsTo(BillRates::class,'br_id');
    }
    public function consumer(): BelongsTo{
        return $this->belongsTo(Consumer::class,'cm_id');
    }

    
}