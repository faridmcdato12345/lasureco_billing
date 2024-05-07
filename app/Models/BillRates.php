<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillRates extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'billing_rates';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'cons_type_id',
		'br_billing_ym',
		'br_gensys_rate',
		'br_fbhc_rate',
		'br_forex_rate',
		'br_icera_rate',
		'br_gram_rate',
		'br_par_rate',
		'br_transdem_rate',
		'br_transsys_rate',
		'br_sysloss_rate',
		'br_distdem_rate',
		'br_distsys_rate',
		'br_suprtlcust_fixed',
		'br_supsys_rate',
		'br_mtrrtlcust_fixed',
		'br_mtrsys_rate',
		'br_loancon_rate_kwh',
		'br_loancon_rate_fix',
		'br_lfln_subs_rate' ,
		'br_sc_subs_rate',
		'br_uc1_npcdebt_rate',
		'br_uc2_npccon_rate',
		'br_uc3_duscon_rate',
		'br_uc4_miss_rate_spu',
		'br_uc5_equal_rate',
		'br_uc6_envi_rate',
		'br_uc7_crssubremrate',
		'br_intrclscrssubrte',
		'br_ppa_refund_rate',
		'br_ppa_reco_rate',
		'br_patronage_refund',
		'br_capex_rate',
		'br_mcrpt',
		'br_adj_label',
		'br_adj_fixed',
		'br_adj_period_backbi',
		'br_adj_rate',
		'br_iccs_adj',
		'br_ppd_adj',
		'br_fit_all',
		'br_uc4_miss_rate_red',
		'br_min_lfln_kwh',
		'br_backbill',
		'br_penalty_perc_rate',
		'br_5_percent',
		'br_2_percent',
		'br_tax_franchise',
		'br_tax_local',
		'br_tax_business',
		'br_vat_gen',
		'br_vat_trans',
		'br_vat_systloss',
		'br_vat_distrib_kwh',
		'br_vat_distrib_fixed',
		'br_vat_other_bill',
		'br_vat_other_nb',
		'br_mcc_vat',
		'br_lower_under_rdng',
		'br_gmcp_gen',
		'br_pemc_wesm',
		'br_ngcp_pei',
		'br_ngcp_phinma_pec',
		'br_ngcp_tli',
		'br_ngcp_tapgc',
		'br_luelco_distr',
		'br_ngcp_cip2',
		'br_vat_transdem',
		'br_vat_distdem',
		'br_vat_loancondo',
		'br_vat_loancondofix',
		'br_vat_metersys',
		'br_vat_supsys',
		'br_vat_supfix',
		'br_vat_par',
		'br_vat_mtr_fix',
		'br_vat_lfln',
		'br_energy_chrg',
		'br_ttl_dmd_chrg',
		'br_ttl_fix_chrg',
		'br_userid',
		'br_userdate',
		'br_usertime',
    ];
	
	protected $dates = ['deleted_at'];

    public function setBrBillingYmAttribute($value)
    {
        $this->attributes['br_billing_ym'] = str_replace("-","",$value);
    }
	// public function getBrBillingYmAttribute($value)
    // {
    //     return $this->attributes['br_billing_ym'] =  date("F Y", strtotime(substr($value,0,4).'-'.substr($value,4,2)));
    // } 
}