<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ConsumerTypeResource;
use Illuminate\Support\Facades\DB;

class BillRatesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'bill_rate_id' => $this -> id,
            'cons_type'=> ConsumerTypeResource::collection(
                DB::table('cons_type')
                ->where('ct_id',$this -> cons_type_id)
                ->whereNull('deleted_at')
                ->get()),
            'bill_rate_ym'=> $this -> br_billing_ym,
            'bill_rate_gensys' => $this -> br_gensys_rate,
            'bill_rate_fbhc' => $this -> br_fbhc_rate,
            'bill_rate_forex' => $this -> br_forex_rate,
            'bill_rate_icera' => $this -> br_icera_rate,
            'bill_rate_gram' => $this -> br_gram_rate,
            'bill_rate_par' => $this -> br_par_rate,
            'bill_rate_transdem' => $this -> br_transdem_rate,
            'bill_rate_transsys' => $this -> br_transsys_rate,
            'bill_rate_sysloss' => $this -> br_sysloss_rate,
            'bill_rate_distdem' => $this -> br_distdem_rate,
            'bill_rate_distsys' => $this -> br_distsys_rate,
            'bill_rate_suprtlcust' => $this -> br_suprtlcust_fixed,
            'bill_rate_supsys' => $this -> br_supsys_rate,
            'bill_rate_mtrrtlcust' => $this -> br_mtrrtlcust_fixed,
            'bill_rate_mtrsys' => $this -> br_mtrsys_rate,
            'bill_rate_loancon_kwh' => $this -> br_loancon_rate_kwh,
            'bill_rate_loancon_fix' => $this -> br_loancon_rate_fix,
            'bill_rate_lfln_subs' => $this -> br_lfln_subs_rate,
            'bill_rate_sc_subs' => $this -> br_sc_subs_rate,
            'bill_rate_uc1_npcdebt' => $this -> br_uc1_npcdebt_rate,
            'bill_rate_uc2_npccon' => $this -> br_uc2_npccon_rate,
            'bill_rate_uc3_duscon' => $this -> br_uc3_duscon_rate,
            'bill_rate_uc4_miss_rate_spu' => $this -> br_uc4_miss_rate_spu,
            'bill_rate_uc5_equal' => $this -> br_uc5_equal_rate,
            'bill_rate_uc6_envi' => $this -> br_uc6_envi_rate,
            'bill_rate_crssubremrate' => $this -> br_uc7_crssubremrate,
            'bill_rate_intrclscrssubrete' => $this -> br_intrclscrssubrte,
            'bill_rate_ppa_refund' => $this -> br_ppa_refund_rate,
            'bill_rate_ppa_reco' => $this -> br_ppa_reco_rate,
            'bill_rate_patronage_refund' => $this -> br_patronage_refund,
            'bill_rate_capex' => $this -> br_capex_rate,
            'bill_rate_mcrpt' => $this -> br_mcrpt,
            'bill_rate_adj_label' => $this -> br_adj_label,
            'bill_rate_adj_fixed' => $this -> br_adj_fixed,
            'bill_rate_adj_period_backbi' => $this -> br_adj_period_backbi,
            'bill_rate_adj' => $this -> br_adj_rate,
            'bill_rate_iccs_adj' => $this -> br_iccs_adj,
            'bill_rate_ppd_adj' => $this -> br_ppd_adj,
            'bill_rate_fit_all' => $this -> br_fit_all,
            'bill_rate_uc4_miss_red' => $this -> br_uc4_miss_rate_red,
            'bill_rate_min_lfln_kwh' => $this -> br_min_lfln_kwh,
            'bill_rate_backbill' => $this -> br_backbill,
            'bill_rate_penalty_perc' => $this -> br_penalty_perc_rate,
            'bill_rate_5_percent' => $this -> br_5_percent,
            'bill_rate_2_percent' => $this -> br_2_percent,
            'bill_rate_tax_franchise' => $this -> br_tax_franchise,
            'bill_rate_tax_local' => $this -> br_tax_local,
            'bill_rate_tax_business' => $this -> br_tax_business,
            'bill_rate_vat_gen' => $this -> br_vat_gen,
            'bill_rate_vat_trans' => $this -> br_vat_trans,
            'bill_rate_vat_systloss' => $this -> br_vat_systloss,
            'bill_rate_vat_distrib_kwh' => $this -> br_vat_distrib_kwh,
            'bill_rate_vat_distrib_fixed' => $this -> br_vat_distrib_fixed,
            'bill_rate_vat_other_bill' => $this -> br_vat_other_bill,
            'bill_rate_vat_other_nb' => $this -> br_vat_other_nb,
            'bill_rate_mcc_vat' => $this -> br_mcc_vat,
            'bill_rate_lower_under_rdng' => $this -> br_lower_under_rdng,
            'bill_rate_gmcp_gen' => $this -> br_gmcp_gen,
            'bill_rate_pemc_wesm' => $this -> br_pemc_wesm,
            'bill_rate_ngcp_pei' => $this -> br_ngcp_pei,
            'bill_rate_ngcp_phinma_pec' => $this -> br_ngcp_phinma_pec,
            'bill_rate_ngcp_tli' => $this -> br_ngcp_tli,
            'bill_rate_ngcp_tapgc' => $this -> br_ngcp_tapgc,
            'bill_rate_luelco_distr' => $this -> br_luelco_distr,
            'bill_rate_ngcp_cip2' => $this -> br_ngcp_cip2,
            'bill_rate_vat_transdem' => $this -> br_vat_transdem,
            'bill_rate_vat_distdem' => $this -> br_vat_distdem,
            'bill_rate_vat_loancondo' => $this -> br_vat_loancondo,
            'bill_rate_vat_loancondofix' => $this -> br_vat_loancondofix,
            'bill_rate_vat_metersys' => $this -> br_vat_metersys,
            'bill_rate_vat_supsys' => $this -> br_vat_supsys,
            'bill_rate_vat_par' => $this -> br_vat_par,
            'bill_rate_vat_mtrFix' => $this -> br_vat_mtr_fix,
            'bill_rate_vat_lfln' => $this -> br_vat_lfln,
            'bill_rate_userid' => $this -> br_userid,
            'bill_rate_userdate' => $this -> br_userdate,
            'bill_rate_usertime' => $this -> br_usertime
        ];
    }
}
