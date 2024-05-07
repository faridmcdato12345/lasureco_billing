<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBillRatesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'br_billing_ym' => 'required|integer',
            'br_gensys_rate'=>'nullable|numeric',
            'br_fbhc_rate'=>'nullable|numeric',
            'br_forex_rate'=>'nullable|numeric',
            'br_icera_rate'=>'nullable|numeric',
            'br_gram_rate'=>'nullable|numeric',
            'br_par_rate'=>'nullable|numeric',
            'br_transdem_rate'=>'nullable|numeric',
            'br_transsys_rate'=>'nullable|numeric',
            'br_sysloss_rate'=>'nullable|numeric',
            'br_distdem_rate'=>'nullable|numeric',
            'br_distsys_rate'=>'nullable|numeric',
            'br_suprtlcust_fixed'=>'nullable|numeric',
            'br_supsys_rate'=>'nullable|numeric',
            'br_mtrrtlcust_fixed'=>'nullable|numeric',
            'br_mtrsys_rate'=>'nullable|numeric',
            'br_loancon_rate_kwh'=>'nullable|numeric',
            'br_loancon_rate_fix'=>'nullable|numeric',
            'br_lfln_subs_rate'=>'nullable|numeric',
            'br_sc_subs_rate'=>'nullable|numeric',
            'br_uc1_npcdebt_rate'=>'nullable|numeric',
            'br_uc2_npccon_rate'=>'nullable|numeric',
            'br_uc3_duscon_rate'=>'nullable|numeric',
            'br_uc4_miss_rate_spu'=>'nullable|numeric',
            'br_uc5_equal_rate'=>'nullable|numeric',
            'br_uc6_envi_rate' =>'nullable|numeric',
            'br_uc7_crssubremrate' =>'nullable|numeric',
            'br_intrclscrssubrte'=>'nullable|numeric',
            'br_ppa_refund_rate'=>'nullable|numeric',
            'br_ppa_reco_rate'=>'nullable|numeric',
            'br_patronage_refund'=>'nullable|numeric',
            'br_capex_rate'=>'nullable|numeric',
            'br_mcrpt'=>'nullable|numeric',
            'br_adj_label'=>'nullable|numeric',
            //'br_adj_fixed'=>'nullable', later 
            'br_adj_period_backbi'=>'nullable|numeric',
            'br_adj_rate'=>'nullable|numeric',
            'br_iccs_adj'=>'nullable|numeric',
            'br_ppd_adj'=>'nullable|numeric',
            'br_fit_all'=>'nullable|numeric',
            'br_uc4_miss_rate_red'=>'nullable|numeric',
            'br_min_lfln_kwh'=>'nullable|numeric',
            'br_backbill'=>'nullable|numeric',
            'br_penalty_perc_rate'=>'nullable|numeric',
            'br_5_percent' =>'nullable|numeric',
            'br_2_percent'=>'nullable|numeric',
            'br_tax_franchise'=>'nullable|numeric',
            'br_tax_local'=>'nullable|numeric',
            'br_tax_business'=>'nullable|numeric',
            'br_vat_gen'=>'nullable|numeric',
            'br_vat_trans' =>'nullable|numeric',
            'br_vat_systloss'=>'nullable|numeric',
            'br_vat_distrib_kwh'=>'nullable|numeric',
            'br_vat_distrib_fixed'=>'nullable|numeric',
            'br_vat_other_bill'=>'nullable|numeric',
            'br_vat_other_nb'=>'nullable|numeric',
            'br_mcc_vat'=>'nullable|numeric',
            'br_lower_under_rdng'=>'nullable|numeric',
            'br_gmcp_gen'=>'nullable|numeric',
            'br_pemc_wesm'=>'nullable|numeric',
            'br_ngcp_pei'=>'nullable|numeric',
            'br_ngcp_phinma_pec'=>'nullable|numeric',
            'br_ngcp_tli'=>'nullable|numeric',
            'br_ngcp_tapgc'=>'nullable|numeric',
            'br_luelco_distr'=>'nullable|numeric',
            'br_ngcp_cip2'=>'nullable|numeric',
            'br_vat_transdem'=>'nullable|numeric',
            'br_vat_distdem'=>'nullable|numeric',
            'br_vat_loancondo'=>'nullable|numeric',
            'br_vat_loancondofix'=>'nullable|numeric',
            'br_vat_metersys'=>'nullable|numeric',
            'br_vat_supsys'=>'nullable|numeric',
            'br_vat_supfix'=>'nullable|numeric',
            'br_vat_par'=>'nullable|numeric',
            'br_vat_mtr_fix'=>'nullable|numeric',
            'br_vat_lfln'=>'nullable|numeric',
            'br_energy_chrg'=>'nullable|numeric',
            'br_ttl_dmd_chrg'=>'nullable|numeric',
            'br_ttl_fix_chrg'=>'nullable|numeric',
            'br_userid'=>'nullable',
            //'br_userdate'=>'nullable',
            //'br_usertime' =>'nullable'
        ];

        if($this->getMethod() == 'post'){
            $rules += ['cons_type_id' => 'required'];
        }

        return $rules;
    }
}
