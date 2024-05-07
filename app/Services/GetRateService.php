<?php

namespace App\Services;

use App\Models\AuditTrail;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class GetRateService {

    public function GetRate($bp,$loc){
        if($loc == ''){
            $consDetails = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
            ->where('mr.mr_date_year_month',$bp)
            ->where('mr.mr_printed',1)
            ->get());
            // $consDetails = collect(DB::table('meter_reg as mr')
            // ->join('billing_rates as br','mr.br_id','=','br.id')
            // ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
            // ->join('cons_master as cm', 'mr.cm_id','=','cm.cm_id')
            // ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            // ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            // ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            // ->where('mr.mr_date_year_month',$bp)
            // ->where('mr.mr_printed',1)
            // ->get());
        }else if($loc == 'lgu'){
            $consDetails = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
            ->where('mr.mr_date_year_month',$bp)
            ->where('mr.mr_printed',1)
            // ->where('ct.ct_id',7)
            ->where(function($query){
                $query->where('cm_lgu2',1)
                ->orWhere('cm_lgu5',1);
            })
            ->get());
        }
        else if($loc == 'all'){
            
            $consDetails = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
            ->where('mr.mr_date_year_month',$bp)
            ->where('mr.mr_printed',1)
            // ->where('ct.ct_id',7)
            ->get());
            // DD();
        }else{
            $consDetails = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
            ->where('mr.mr_date_year_month',$bp)
            ->where('ac.ac_id',$loc)
            ->where('mr.mr_printed',1)
            // ->where('ct.ct_id',7)
            ->get());
        }
        
        $map = $consDetails->map(function ($item){
            /* ---------------------------------------- GENERATION CHARGES ---------------------------------------*/
            $genSysCharge = round(round($item->br_gensys_rate * $item->mr_kwh_used,3),2);
            $powerActRed = round(round($item->br_par_rate * $item->mr_kwh_used,3),2);
            $franBenToHost = round(round($item->br_fbhc_rate * $item->mr_kwh_used,3),2);
            $forexAdjCharge = round(round($item->br_forex_rate * $item->mr_kwh_used,3),2);
            // $subTotalGc = number_format(($genSysCharge + $powerActRed + $franBenToHost + $forexAdjCharge),2);
            $subTotalGc = number_format($genSysCharge + $powerActRed + $franBenToHost + $forexAdjCharge,2,'.','');
            /*----------------------------------------- TRANSMISSION CHARGES --------------------------------------*/
            $tranSysCharge = round(round($item->br_transsys_rate * $item->mr_kwh_used,3),2);
            $tranDemCharge = round(round($item->br_transdem_rate * $item->mr_dem_kwh_used,3),2);
            $sysLossCharge = round(round($item->br_sysloss_rate * $item->mr_kwh_used,3),2);
            $subTotalTc = number_format($tranSysCharge + $tranDemCharge + $sysLossCharge,2,'.','');
            /*----------------------------------------- DISTRIBUTION CHARGES --------------------------------------*/
            $distSysCharge = round(round($item->br_distsys_rate * $item->mr_kwh_used,3),2);
            $distDemCharge = round(round($item->br_distdem_rate * $item->mr_dem_kwh_used,3),2);
            $supFixCharge = round($item->br_suprtlcust_fixed,2); //fix per cst
            $supSysCharge = round(round($item->br_supsys_rate * $item->mr_kwh_used,3),2);
            $meterFixCharge = round($item->br_mtrrtlcust_fixed,2); //fix 5per cst
            $meterSysCharge = round(round($item->br_mtrsys_rate * $item->mr_kwh_used,3),2);
            $subTotalDc = number_format($distSysCharge + $distDemCharge + $supFixCharge + $supSysCharge + $meterFixCharge + $meterSysCharge,2,'.','');
            /*----------------------------------------- OTHER CHARGES --------------------------------------*/
            $lflnDisc = ($item->mr_lfln_disc != 0) ? $item->mr_lfln_disc * -1 : 0;
            $lflnDiscSubs = ($item->mr_lfln_disc == 0) ? round(round($item->br_lfln_subs_rate * $item->mr_kwh_used,3),2) : 0;
            $senCitDiscSubs = round(round($item->br_sc_subs_rate * $item->mr_kwh_used,3),2);
            $intClssCrssSubs = round(round($item->br_intrclscrssubrte * $item->mr_kwh_used,3),2);
            $mccCapex = round(round($item->br_capex_rate * $item->mr_kwh_used,3),2);
            $loanCond = round(round($item->br_loancon_rate_kwh * $item->mr_kwh_used,3),2);
            $loanConFix = round($item->br_loancon_rate_fix,2); //fix
            $subTotalOc = number_format($lflnDiscSubs + $senCitDiscSubs + $intClssCrssSubs + $mccCapex + $loanCond + $loanConFix,2,'.','');
            /*----------------------------------------- UNIVERSAL CHARGES --------------------------------------*/
            $missElectSPUG = round(round($item->br_uc4_miss_rate_spu * $item->mr_kwh_used,3),2);
            $missElectRED = round(round($item->br_uc4_miss_rate_red * $item->mr_kwh_used,3),2);
            $enviChrg = round(round($item->br_uc6_envi_rate * $item->mr_kwh_used,3),2);
            $equaliRoyalty = round(round($item->br_uc5_equal_rate * $item->mr_kwh_used,3),2);
            $npcStrCC = round(round($item->br_uc2_npccon_rate * $item->mr_kwh_used,3),2);
            $npcStrDebt = round(round($item->br_uc1_npcdebt_rate * $item->mr_kwh_used,3),2);
            $fitAll = round(round($item->br_fit_all * $item->mr_kwh_used,3),2);
            $subTotalUc = number_format($missElectSPUG + $missElectRED + $enviChrg + $equaliRoyalty + $npcStrCC + $npcStrDebt + $fitAll,2,'.','');
            /*----------------------------------------- VALUE ADDED TAX --------------------------------------*/
            $genVat = round(round($item->br_vat_gen * $item->mr_kwh_used,3),2);
            $powerActRedVat = round(round($item->br_vat_par * $item->mr_kwh_used,3),2);
            $tranSysVat = round(round($item->br_vat_trans * $item->mr_kwh_used,3),2);
            $transDemVat = round(round($item->br_vat_transdem * $item->mr_dem_kwh_used,3),2);
            $sysLossVat = round(round($item->br_vat_systloss * $item->mr_kwh_used,3),2);
            $distSysVat = round(round($item->br_vat_distrib_kwh * $item->mr_kwh_used,3),2);
            $distDemVat = round(round($item->br_vat_distdem * $item->mr_dem_kwh_used,3),2);
            $supplyFixVat = round(round($item->br_vat_supfix,3),2);
            $supplySysVat = round(round($item->br_vat_supsys * $item->mr_kwh_used,3),2);
            $meterFixVat = round($item->br_vat_mtr_fix,2);
            $meterSysVat = round(round($item->br_vat_metersys * $item->mr_kwh_used,3),2);
            // $lflnDIscSubsVat = round(round($item->br_vat_lfln * $item->mr_kwh_used,3),2);
            $lflnDIscSubsVat = ($item->mr_lfln_disc != 0) ? 0 : round(round($item->br_vat_lfln * $item->mr_kwh_used,3),2);
            $loanCondVat = round(round($item->br_vat_loancondo * $item->mr_kwh_used,3),2);
            $loanCondFixVat = round($item->br_vat_loancondofix,2);
            $otherVat = 0;
            $subTotalVat = number_format($genVat + $powerActRedVat + $tranSysVat + $transDemVat + $sysLossVat + $distSysVat + $distDemVat + 
                $supplyFixVat + $supplySysVat + $meterFixVat + $meterSysVat + $lflnDIscSubsVat + $loanCondVat + $loanCondFixVat + $otherVat,2,'.','');

            return[
                /* ------------------------------------------- ADDITIONAL FOR OTHER REPORTS ------------------------------------------------------*/
                'account_no'=>$item->cm_account_no,
                'KWH_USED'=>$item->mr_kwh_used,
                'BILL_AMOUNT'=>round($item->mr_amount,2),
                'KW_SOLD'=>$item->mr_dem_kwh_used,
                'DEM_AMOUNT'=>round($tranDemCharge + $distDemCharge,2),
                /* ------------------------------------------- ------------------------  ------------------------------------------------------*/
                'Ct_Desc'=>$item->ct_desc,
                'Ct_ID'=>$item->ct_id,
                'Area_ID'=>$item->ac_id,
                'ac_id'=>$item->ac_id,
                'tc_code'=>$item->tc_code,
                'rc_code'=>$item->rc_code,
                'ac_name'=>$item->ac_name,
                'tc_name'=>$item->tc_name,
                'rc_desc'=>$item->rc_desc,
                'lgu2'=>$item->cm_lgu2,
                'lgu5'=>$item->cm_lgu5,
                'Generation_System_Charge' => $genSysCharge,
                'Power_Act_Reduction' => $powerActRed,
                // 'Name' => $item->cm_account_no,
                'Franchise_Benefits_To_Host' => $franBenToHost,
                'FOREX_Adjustment_Charge' => $forexAdjCharge,
                /* ------------------------------------------- TRANSMISSION CHARGES  ------------------------------------------------------*/
                'Trans_Demand_Charge' => $tranDemCharge,
                'Transmission_System_Charge' => $tranSysCharge,
                'System_Loss_Charge' => $sysLossCharge,
                /* ------------------------------------------- DISTRIBUTION CHARGES ------------------------------------------------------*/
                'Dist_Demand_Charge' => $distDemCharge,
                'Distribution_System_Charge' => $distSysCharge,
                'Supply_System_Fixed_Charge' => $supFixCharge,
                'Supply_System_Charge' => $supSysCharge,
                'Retail_Customer_Meter_Charge' => $meterSysCharge,
                'Retail_Customer_Mtr_Fixed_Charge' => $meterFixCharge,
                /* ------------------------------------------- UNIVERSAL CHARGES ------------------------------------------------------*/
                'UC_SPUG' => $missElectSPUG,
                'UC_RED_Cash_Incentive' => $missElectRED,
                'UC_Environmental_Charge' => $enviChrg,
                'UC_Equal_of_Taxes_Royalties' => $equaliRoyalty,
                'UC_NPC_Stranded_Contract_Cost' => $npcStrCC,
                'UC_NPC_Stranded_Debt_Cost' => $npcStrDebt,
                'Feed_in_Tariff_Allowance' => $fitAll,
                /* ------------------------------------------- OTHER CHARGES ------------------------------------------------------*/
                'Inter_Class_Cross_Subsidy' => $intClssCrssSubs,
                // 'Inter Class Corss Subsidy Adj.' => 
                'Members_Contributed_Capital' => $mccCapex,
                'Lifeline_Rate_Subsidy' => $lflnDiscSubs,
                'Lifeline_Rate_Discount' => $lflnDisc,
                // 'Transformer Losses' => 
                // 'BackBill_Rebates_Refund' => 
                'Senior_Citizen_Subsidy' => $senCitDiscSubs,
                // 'Senior Citizen (Discount)' => 
                // 'Prompt Payment Discount Adj' => 
                'lOAN_COND' => $loanCond,
                'lOAN_COND_FIX' => $loanConFix,
                /* ------------------------------------------- VALUE ADDED TAX ------------------------------------------------------*/
                'Generation' => $genVat,
                'Transmission_System' => $tranSysVat,
                'Transmission_Demand' => $transDemVat,
                'System_Loss' => $sysLossVat,
                'Distribution_System' => $distSysVat,
                'Distribution_Demand' => $distDemVat,
                // 'Others' => 
                'Loan_Condonation_KWH' => $loanCondVat,
                'Loan_Condonation_Fix' => $loanCondFixVat,
                'Power_Act_Red_Vat'=> $powerActRedVat, //new
                'Supply_Fix_Vat'=> $supplyFixVat, //new
                'Supply_Sys_Vat'=> $supplySysVat, //new
                'Meter_Fix_Vat'=> $meterFixVat, //new
                'Meter_Sys_Vat'=> $meterSysVat, //new
                'lfln_disc_subs_vat'=> $lflnDIscSubsVat, //new
            ];
        });
        
        return $map;
    }

}