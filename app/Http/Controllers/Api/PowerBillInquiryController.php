<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MeterReg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Carbon\Carbon;
use PDF;

class PowerBillInquiryController extends Controller
{
    public function inquiry(Request $request){
        
        $data = MeterReg::select(
            'cons_account',
            'mr_date_year_month',
            'mr_amount',
            'mr_kwh_used',
            'mr_prev_reading',
            'mr_pres_reading',
            'mr_due_date',
            'mr_discon_date',
            'mr_status')
        ->where('cons_account',$request->account_no)
        ->where('mr_date_year_month',$request->bill_period)
        ->where('mr_printed',1)
        ->get();
        if($data->isEmpty()){
            return response()->json(['message'=>'No powerbill on the give date!'],404);
        }
        return response()->json($data,200);
    }

    // public function inquiry2(Request $request){
    //     // dd(1);
    //     // dd($request->bill_period);
    //     $samp = carbon::parse($request->bill_period)->format('Ym');
    //     // dd($samp);
    //     $data = MeterReg::select(
    //         'br_id',
    //         'cons_account',
    //         'mr_date_year_month',
    //         'mr_amount',
    //         'mr_kwh_used',
    //         'mr_prev_reading',
    //         'mr_pres_reading',
    //         'mr_due_date',
    //         'mr_discon_date',
    //         'mr_status',
    //         'mr_dem_kwh_used',
    //         'mr_lfln_disc',
    //         'cm_id',
    //         'mr_bill_no',
    //         'mr_pres_dem_reading',
    //         'mr_date_reg'
    //         ) 
    //     ->where('cons_account',$request->account_no)
    //     ->where('mr_date_year_month',$request->bill_period)
    //     ->where('mr_printed',1)
    //     ->get();
    // //    dd($data->first()->mr_date_reg);
    //     if($data->isEmpty()){
    //         return response()->json(['message'=>'No powerbill on the give date!'],404);
    //     }
    //         $brId = $data->first()->br_id;
    //         $cmId = $data->first()->cm_id;
    //         $mdYM = $data->first()->mr_due_date;
    //         $br = collect(DB::table('billing_rates')
    //         ->select('*')
    //         ->where('id',$brId)
    //         ->get());
    //         // dd($br);
    //         // $kwhUsed = 
    //         // $consumerInfo = collect(DB::table('cons_master')
    //         //     ->select('cm_seq_no','cm_full_name','cm_address','cm_account_no')
    //         //     ->where('cm_id', $cmId)
    //         //     ->first()                
    //         // );
    //         $consumerInfo = collect(DB::table('cons_master as cm')
    //             ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
    //             ->join('meter_master as mm', 'cm.mm_id','=','mm.mm_id')
    //             ->select('mm.mm_serial_no','cm.cm_kwh_mult','cm.cm_seq_no','cm.cm_lgu2','cm.cm_lgu5','cm.cm_full_name','cm.cm_address','cm.cm_account_no','ct.ct_desc')
    //             ->where('cm_id', $cmId)
    //             ->first()                
    //         );
    //         // dd($consumerInfo['cm_lgu2']);
    //         $periodFrom = collect(DB::table('meter_reg as mr')
    //             ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
    //             ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
    //             ->where('cm.cm_id',$cmId)
    //             ->where('mr.mr_date_year_month','<',$request->bill_period)
    //             ->whereNotNull('mr.mr_date_reg')
    //             ->orderBy('mr.mr_date_year_month','desc')
    //             ->first());
    //             // dd($periodFrom['mr_date_reg'],$samp);
    //         if(!isset($periodFrom['mr_date_reg']))
    //         {
    //             $periodFrom = $request->bill_period;
    //             // $item->mr_date_reg = 0;
    //         }else{
    //             $periodFrom = $periodFrom['mr_date_reg'];
    //         }
    
    //         $periodFromConv = date('m/d/Y', strtotime($periodFrom));
            
    //         $periodToConv = date('m/d/Y', strtotime($data->first()->mr_date_reg));
    //         $datetime1 = new DateTime($periodFromConv);
    //         $datetime2 = new DateTime($periodToConv);
    //         $interval = $datetime1->diff($datetime2);
    //         $days = $interval->format('%a');
    //         $lastPayment = collect(DB::table('sales as s')
    //             ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
    //             ->select(DB::raw('COALESCE(sum(s.s_or_amount),0) as bill_amount,COALESCE(sum(s.e_wallet_added),0) as ewAdd,s.s_or_num,s.cm_id,s.s_bill_date'))
    //             // ->whereRaw('a.s_bill_date = (SELECT MAX(b.s_bill_date) FROM sales b WHERE a.cm_id = b.cm_id)')
    //             ->where('s.cm_id',$cmId)
    //             ->where('mr.mr_status',1)
    //             ->orderByDesc('s.s_bill_date')
    //             ->groupBy('s.s_bill_date')
    //             ->first());
    //             $dueDate1 = date('F d, Y', strtotime($mdYM));
    //         $checkLastPayment = $lastPayment->first();
    //         if(!$checkLastPayment)
    //         {
    //             $lastPayment = 'No Payment Received';
    //         }else{
    //             // $lastPayment = collect([date('m/d/Y', strtotime($lastPayment['s_bill_date']).' '.$lastPayment['s_or_num'].' '.$lastPayment['s_bill_amount'])]);

    //             $lastPayment = date('m/d/Y', strtotime($lastPayment['s_bill_date'])).'@'.round($lastPayment['bill_amount'] + $lastPayment['ewAdd'],2);
    //         }

    //         //Query Total Arrears
    //         $totalArrear = collect(DB::table('meter_reg')
    //             ->where('cm_id',$cmId)
    //             ->where('mr_status',0)
    //             ->sum('mr_amount'))
    //             ->first();
    //         //Query Consumer E_Wallet
    //         $ewallet = collect(DB::table('e_wallet')
    //             ->where('cm_id',$cmId)
    //             ->select('ew_total_amount')
    //             ->first());

    //         $map = $br->map(function ($item) use ($data){
    //             // dd($item)
    //             // mr_lfln_disc
    //             // mr_dem_kwh_used
    //             $lflnDisc2 = $data->first()->mr_lfln_disc;
    //             $demKwhUsed = $data->first()->mr_dem_kwh_used;
    //             $kwhUsed =  $data->first()->mr_kwh_used;
    //             // dd($data);
    //              /* ---------------------------------------- GENERATION CHARGES ---------------------------------------*/
    //         $genSysCharge = round(round($item->br_gensys_rate * $kwhUsed,3),2);
    //         $powerActRed = round(round($item->br_par_rate * $kwhUsed,3),2);
    //         $franBenToHost = round(round($item->br_fbhc_rate * $kwhUsed,3),2);
    //         $forexAdjCharge = round(round($item->br_forex_rate * $kwhUsed,3),2);
    //         // $subTotalGc = number_format(($genSysCharge + $powerActRed + $franBenToHost + $forexAdjCharge),2);
    //         $subTotalGc = number_format($genSysCharge + $powerActRed + $franBenToHost + $forexAdjCharge,2,'.','');
    //         /*----------------------------------------- TRANSMISSION CHARGES --------------------------------------*/
    //         $tranSysCharge = round(round($item->br_transsys_rate * $kwhUsed,3),2);
    //         $tranDemCharge = round(round($item->br_transdem_rate * $demKwhUsed,3),2);
    //         $sysLossCharge = round(round($item->br_sysloss_rate * $kwhUsed,3),2);
    //         $subTotalTc = number_format($tranSysCharge + $tranDemCharge + $sysLossCharge,2,'.','');
    //         /*----------------------------------------- DISTRIBUTION CHARGES --------------------------------------*/
    //         $distSysCharge = round(round($item->br_distsys_rate * $kwhUsed,3),2);
    //         $distDemCharge = round(round($item->br_distdem_rate * $demKwhUsed,3),2);
    //         $supFixCharge = round($item->br_suprtlcust_fixed,2); //fix per cst
    //         $supSysCharge = round(round($item->br_supsys_rate * $kwhUsed,3),2);
    //         $meterFixCharge = round($item->br_mtrrtlcust_fixed,2); //fix 5per cst
    //         $meterSysCharge = round(round($item->br_mtrsys_rate * $kwhUsed,3),2);
    //         $subTotalDc = number_format($distSysCharge + $distDemCharge + $supFixCharge + $supSysCharge + $meterFixCharge + $meterSysCharge,2,'.','');
    //         /*----------------------------------------- OTHER CHARGES --------------------------------------*/
    //         $lflnDiscSubs = ($lflnDisc2 != 0) ? $lflnDisc2 * -1 : round(round($item->br_lfln_subs_rate * $kwhUsed,3),2);
    //         // $lflnDiscSubs = round(round($item->br_lfln_subs_rate * $kwhUsed,3),2);
    //         $senCitDiscSubs = round(round($item->br_sc_subs_rate * $kwhUsed,3),2);
    //         $intClssCrssSubs = round(round($item->br_intrclscrssubrte * $kwhUsed,3),2);
    //         $mccCapex = round(round($item->br_capex_rate * $kwhUsed,3),2);
    //         $loanCond = round(round($item->br_loancon_rate_kwh * $kwhUsed,3),2);
    //         $loanConFix = round($item->br_loancon_rate_fix,2); //fix
    //         $subTotalOc = number_format($lflnDiscSubs + $senCitDiscSubs + $intClssCrssSubs + $mccCapex + $loanCond + $loanConFix,2,'.','');
    //         /*----------------------------------------- UNIVERSAL CHARGES --------------------------------------*/
    //         $missElectSPUG = round(round($item->br_uc4_miss_rate_spu * $kwhUsed,3),2);
    //         $missElectRED = round(round($item->br_uc4_miss_rate_red * $kwhUsed,3),2);
    //         $enviChrg = round(round($item->br_uc6_envi_rate * $kwhUsed,3),2);
    //         $equaliRoyalty = round(round($item->br_uc5_equal_rate * $kwhUsed,3),2);
    //         $npcStrCC = round(round($item->br_uc2_npccon_rate * $kwhUsed,3),2);
    //         $npcStrDebt = round(round($item->br_uc1_npcdebt_rate * $kwhUsed,3),2);
    //         $fitAll = round(round($item->br_fit_all * $kwhUsed,3),2);
    //         $subTotalUc = number_format($missElectSPUG + $missElectRED + $enviChrg + $equaliRoyalty + $npcStrCC + $npcStrDebt + $fitAll,2,'.','');
    //         /*----------------------------------------- VALUE ADDED TAX --------------------------------------*/
    //         $genVat = round(round($item->br_vat_gen * $kwhUsed,3),2);
    //         $powerActRedVat = round(round($item->br_vat_par * $kwhUsed,3),2);
    //         $tranSysVat = round(round($item->br_vat_trans * $kwhUsed,3),2);
    //         $transDemVat = round(round($item->br_vat_transdem * $demKwhUsed,3),2);
    //         $sysLossVat = round(round($item->br_vat_systloss * $kwhUsed,3),2);
    //         $distSysVat = round(round($item->br_vat_distrib_kwh * $kwhUsed,3),2);
    //         $distDemVat = round(round($item->br_vat_distdem * $demKwhUsed,3),2);
    //         $supplyFixVat = round(round($item->br_vat_supfix,3),2);
    //         $supplySysVat = round(round($item->br_vat_supsys * $kwhUsed,3),2);
    //         $meterFixVat = round($item->br_vat_mtr_fix,2);
    //         $meterSysVat = round(round($item->br_vat_metersys * $kwhUsed,3),2);
    //         // $lflnDIscSubsVat = round(round($item->br_vat_lfln * $kwhUsed,3),2);
    //         $lflnDIscSubsVat = ($lflnDisc2 != 0) ? 0 : round(round($item->br_vat_lfln * $kwhUsed,3),2);
    //         $loanCondVat = round(round($item->br_vat_loancondo * $kwhUsed,3),2);
    //         $loanCondFixVat = round($item->br_vat_loancondofix,2);
    //         $otherVat = 0;
    //         $subTotalVat = number_format($genVat + $powerActRedVat + $tranSysVat + $transDemVat + $sysLossVat + $distSysVat + $distDemVat + 
    //             $supplyFixVat + $supplySysVat + $meterFixVat + $meterSysVat + $lflnDIscSubsVat + $loanCondVat + $loanCondFixVat + $otherVat,2,'.','');    

    //             return [
    //                  /* --------------------------------------------------- GENERATION CHARGES -----------------------------------------------*/
    //             'Gen_Sys_Chrg'=>number_format(round($item->br_gensys_rate,4),4,'.','').'/kwh'.'@'.number_format($genSysCharge,2,'.',''),
    //             'Power_Act_Red'=>number_format(round($item->br_par_rate,4),4,'.','').'/kwh'.'@'.number_format($powerActRed,2,'.',''),
    //             'Fran_Ben_To_Host'=>number_format(round($item->br_fbhc_rate,4),4,'.','').'/kwh'.'@'.number_format($franBenToHost,2,'.',''),
    //             'FOREX_Adjust_Charge'=>number_format(round($item->br_forex_rate,4),4,'.','').'/kwh'.'@'.number_format($forexAdjCharge,2,'.',''),
    //             'SUB_TOTAL_GC'=>round($subTotalGc,2),
    //             /*---------------------------------------------------- TRANSMISSION CHARGES ----------------------------------------------*/
    //             'Trans_Sys_Charge'=>number_format(round($item->br_transsys_rate,4),4,'.','').'/kwh'.'@'.number_format($tranSysCharge,2,'.',''),
    //             'Trans_Dem_Charge'=>number_format(round($item->br_transdem_rate,4),4,'.','').'/kW'.'@'.number_format($tranDemCharge,2,'.',''),
    //             'System_Loss_Charge'=>number_format(round($item->br_sysloss_rate,4),4,'.','').'/kwh'.'@'.number_format($sysLossCharge,2,'.',''),
    //             'SUB_TOTAL_TC'=>round($subTotalTc,2),
    //             /*----------------------------------------------------- DISTRIBUTION CHARGES ---------------------------------------------*/
    //             'Dist_Sys_Chrg'=>number_format(round($item->br_distsys_rate,4),4,'.','').'/kwh'.'@'.number_format($distSysCharge,2,'.',''),
    //             'Dist_Dem_Chrg'=>number_format(round($item->br_distdem_rate,4),4,'.','').'/kW'.'@'.number_format($distDemCharge,2,'.',''),
    //             'Supply_Fix_Chrg'=>number_format(round($item->br_suprtlcust_fixed,4),4,'.','').'/cst'.'@'.number_format($supFixCharge,2,'.',''),
    //             'Supply_Sys_Chrg'=>number_format(round($item->br_supsys_rate,4),4,'.','').'/kwh'.'@'.number_format($supSysCharge,2,'.',''),
    //             'Metering_Fix_Chrg'=>number_format(round($item->br_mtrrtlcust_fixed,4),4,'.','').'/cst'.'@'.number_format($meterFixCharge,2,'.',''),
    //             'Metering_Sys_Chrg'=>number_format(round($item->br_mtrsys_rate,4),4,'.','').'/kwh'.'@'.number_format($meterSysCharge,2,'.',''),
    //             'Sub_Total_DC'=>round($subTotalDc,2),
    //             /*-------------------------------------------------------- OTHER CHARGES -------------------------------------------------*/
    //             'Lfln_Disc_Subs'=>($lflnDisc2 != 0) ? number_format(round($item->br_lfln_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($lflnDisc2,2,'.','') * -1 : number_format(round($item->br_lfln_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($lflnDiscSubs,2,'.',''),
    //             // 'Lfln_Disc_Subs'=>number_format(round($lflnDiscSubs,4),4,'.','').'/kwh'.'@'.number_format($lflnDiscSubs,2,'.',''),
    //             'Sen_Cit_Disc_Subs'=>number_format(round($item->br_sc_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($senCitDiscSubs,2,'.',''),
    //             'Int_Clss_Crss_Subs'=>number_format(round($item->br_intrclscrssubrte,4),4,'.','').'/kwh'.'@'.number_format($intClssCrssSubs,2,'.',''),
    //             'MCC_CAPEX'=>number_format(round($item->br_capex_rate,4),4,'.','').'/kwh'.'@'.number_format($mccCapex,2,'.',''),
    //             'Loan_Condonation'=>number_format(round($item->br_loancon_rate_kwh,4),4,'.','').'/kwh'.'@'.number_format($loanCond,2,'.',''),
    //             'Loan_Condon_Fix'=>number_format(round($item->br_loancon_rate_fix,4),4,'.','').'/cst'.'@'.number_format($loanConFix,2,'.',''),
    //             'SUB_TOTAL_OC'=>round($subTotalOc,2),
    //             /*------------------------------------------------------ UNIVERSAL CHARGES -----------------------------------------------*/
    //             'Miss_Elect_SPUG'=>number_format(round($item->br_uc4_miss_rate_spu,4),4,'.','').'/kwh'.'@'.number_format($missElectSPUG,2,'.',''),
    //             'Miss_Elect_RED'=>number_format(round($item->br_uc4_miss_rate_red,4),4,'.','').'/kwh'.'@'.number_format($missElectRED,2,'.',''),
    //             'Envi_Chrg'=>number_format(round($item->br_uc6_envi_rate,4),4,'.','').'/kwh'.'@'.number_format($enviChrg,2,'.',''),
    //             'Equali_Of_Taxes_Royalty'=>number_format(round($item->br_uc5_equal_rate,4),4,'.','').'/kwh'.'@'.number_format($equaliRoyalty,2,'.',''),
    //             'NPC_Str_Cons_Cost'=>number_format(round($item->br_uc2_npccon_rate,4),4,'.','').'/kwh'.'@'.number_format($npcStrCC,2,'.',''),
    //             'NPC_Str_Debt'=>number_format(round($item->br_uc1_npcdebt_rate,4),4,'.','').'/kwh'.'@'.number_format($npcStrDebt,2,'.',''),
    //             'Fit_All_RENEW'=>number_format(round($item->br_fit_all,4),4,'.','').'/kwh'.'@'.number_format($fitAll,2,'.',''),
    //             'SUB_TOTAL_UC'=>round($subTotalUc,2),
    //             /*------------------------------------------------------- VALUE ADDED TAX ------------------------------------------------*/
    //             'Generation_Vat'=>number_format(round($item->br_vat_gen,4),4,'.','').'/kwh'.'@'.number_format($genVat,2,'.',''),
    //             'Power_Act_Red_Vat' => number_format(round($item->br_vat_par,4),4,'.','').'/kwh'.'@'.number_format($powerActRedVat,2,'.',''),
    //             'Trans_Sys_Vat'=>number_format(round($item->br_vat_trans,4),4,'.','').'/kwh'.'@'.number_format($tranSysVat,2,'.',''),
    //             'Trans_Dem_Vat'=>number_format(round($item->br_vat_transdem,4),4,'.','').'/kW'.'@'.number_format($transDemVat,2,'.',''),
    //             'Sys_Loss_Vat'=>number_format(round($item->br_vat_systloss,4),4,'.','').'/kwh'.'@'.number_format($sysLossVat,2,'.',''),
    //             'Dist_Sys_Vat'=>number_format(round($item->br_vat_distrib_kwh,4),4,'.','').'/kwh'.'@'.number_format($distSysVat,2,'.',''),
    //             'Dist_Dem_Vat'=>number_format(round($item->br_vat_distdem,4),4,'.','').'/kW'.'@'.number_format($distDemVat,2,'.',''),
    //             'Supply_Fix_Vat' => number_format(round($item->br_vat_supfix,4),4,'.','').'/cst'.'@'.number_format($supplyFixVat,2,'.',''), // display supply sys
    //             'Supply_Sys_Vat' => number_format(round($item->br_vat_supsys,4),4,'.','').'/kwh'.'@'.number_format($supplySysVat,2,'.',''), // display supply sys
    //             'Metering_Fix_Vat' => number_format(round($item->br_vat_mtr_fix,4),4,'.','').'/cst'.'@'.number_format($meterFixVat,2,'.',''), //edit
    //             'Metering_Sys_Vat' => number_format(round($item->br_vat_metersys,4),4,'.','').'/kwh'.'@'.number_format($meterSysVat,2,'.',''),
    //             // 'Lfln_Disc_Vat' => number_format(round($item->br_vat_lfln,4),4,'.','').'/kwh'.'@'.number_format($lflnDIscSubsVat,2,'.',''), //edit
    //             'Lfln_Disc_Vat' => number_format(round($item->br_vat_lfln,4),4,'.','').'/kwh'.'@'.number_format($lflnDIscSubsVat,2,'.',''),
    //             'Loan_Cond_Vat'=>number_format(round($item->br_vat_loancondo,4),4,'.','').'/kwh'.'@'.number_format($loanCondVat,2,'.',''),
    //             'Loan_Cond_Fix_Vat'=>number_format(round($item->br_vat_loancondofix,4),4,'.','').'/cst'.'@'.number_format($loanCondFixVat,2,'.',''),
    //             'Other_Vat'=>number_format(round($otherVat,4),4,'.','').'/kwh'.'@'.number_format($otherVat,2,'.',''),
    //             'SUB_TOTAL_VAT'=>round($subTotalVat,2),
    //             ];
    //         });

    //         return response([
    //             'data'=> $data,
    //             'rates' => $map,
    //             'bill_number' => $data->first()->mr_bill_no,
    //             'info'=> $consumerInfo,
    //             'last_payment'=> $lastPayment,
    //             'total_arrears' => $totalArrear,
    //             'ewallet' => $ewallet,
    //             'due_date' => $dueDate1,
    //             'Period_From'=>$periodFromConv,
    //             'Period_To'=>$periodToConv,
    //             'No_Days_Covered'=>$days,
    //             'LGU_2'=>($consumerInfo['cm_lgu2'] == 1) ? round(($data->first()->mr_amount/1.12) * 0.02,2) : 0, //add column going to store
    //             'LGU_5'=>($consumerInfo['cm_lgu5'] == 1) ? round(($data->first()->mr_amount/1.12) * 0.05,2) : 0, //add column going to store
    //         ],200);
    // }
    // public function inquiry2(Request $request)
    // {
    //     $samp = carbon::parse($request->bill_period)->format('Ym');
    //     // dd($samp);
    //     $data = MeterReg::select(
    //     'br_id',
    //     'cons_account',
    //     'mr_date_year_month',
    //     'mr_amount',
    //     'mr_kwh_used',
    //     'mr_prev_reading',
    //     'mr_pres_reading',
    //     'mr_due_date',
    //     'mr_discon_date',
    //     'mr_status',
    //     'mr_dem_kwh_used',
    //     'mr_lfln_disc',
    //     'cm_id',
    //     'mr_bill_no',
    //     'mr_pres_dem_reading',
    //     'mr_date_reg'
    //     ) 
    //     ->where('cons_account',$request->account_no)
    //     ->where('mr_date_year_month',$request->bill_period)
    //     ->where('mr_printed',1)
    //     ->get();
    //     //    dd($data->first()->mr_date_reg);
    //     if($data->isEmpty()){
    //     return response()->json(['message'=>'No powerbill on the give date!'],404);
    //     }
    //     $brId = $data->first()->br_id;
    //     $cmId = $data->first()->cm_id;
    //     $mdYM = $data->first()->mr_due_date;
    //     $br = collect(DB::table('billing_rates')
    //     ->select('*')
    //     ->where('id',$brId)
    //     ->get());
    //     // dd($br);
    //     // $kwhUsed = 
    //     // $consumerInfo = collect(DB::table('cons_master')
    //     //     ->select('cm_seq_no','cm_full_name','cm_address','cm_account_no')
    //     //     ->where('cm_id', $cmId)
    //     //     ->first()                
    //     // );
    //     $consumerInfo = collect(DB::table('cons_master as cm')
    //     ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
    //     ->join('meter_master as mm', 'cm.mm_id','=','mm.mm_id')
    //     ->select('mm.mm_serial_no','cm.cm_kwh_mult','cm.cm_seq_no','cm.cm_lgu2','cm.cm_lgu5','cm.cm_full_name','cm.cm_address','cm.cm_account_no','ct.ct_desc')
    //     ->where('cm_id', $cmId)
    //     ->first()                
    //     );
    //     // dd($consumerInfo['cm_lgu2']);
    //     $periodFrom = collect(DB::table('meter_reg as mr')
    //     ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
    //     ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
    //     ->where('cm.cm_id',$cmId)
    //     ->where('mr.mr_date_year_month','<',$request->bill_period)
    //     ->whereNotNull('mr.mr_date_reg')
    //     ->orderBy('mr.mr_date_year_month','desc')
    //     ->first());
    //     // dd($periodFrom['mr_date_reg'],$samp);
    //     if(!isset($periodFrom['mr_date_reg']))
    //     {
    //     $periodFrom = $request->bill_period;
    //     // $item->mr_date_reg = 0;
    //     }else{
    //     $periodFrom = $periodFrom['mr_date_reg'];
    //     }

    //     $periodFromConv = date('m/d/Y', strtotime($periodFrom));

    //     $periodToConv = date('m/d/Y', strtotime($data->first()->mr_date_reg));
    //     $datetime1 = new DateTime($periodFromConv);
    //     $datetime2 = new DateTime($periodToConv);
    //     $interval = $datetime1->diff($datetime2);
    //     $days = $interval->format('%a');
    //     $lastPayment = collect(DB::table('sales as s')
    //     ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
    //     ->select(DB::raw('COALESCE(sum(s.s_or_amount),0) as bill_amount,COALESCE(sum(s.e_wallet_added),0) as ewAdd,s.s_or_num,s.cm_id,s.s_bill_date'))
    //     // ->whereRaw('a.s_bill_date = (SELECT MAX(b.s_bill_date) FROM sales b WHERE a.cm_id = b.cm_id)')
    //     ->where('s.cm_id',$cmId)
    //     ->where('mr.mr_status',1)
    //     ->orderByDesc('s.s_bill_date')
    //     ->groupBy('s.s_bill_date')
    //     ->first());
    //     $dueDate1 = date('F d, Y', strtotime($mdYM));
    //     $checkLastPayment = $lastPayment->first();
    //     if(!$checkLastPayment)
    //     {
    //     $lastPayment = 'No Payment Received';
    //     }else{
    //     // $lastPayment = collect([date('m/d/Y', strtotime($lastPayment['s_bill_date']).' '.$lastPayment['s_or_num'].' '.$lastPayment['s_bill_amount'])]);

    //     $lastPayment = date('m/d/Y', strtotime($lastPayment['s_bill_date'])).'@'.round($lastPayment['bill_amount'] + $lastPayment['ewAdd'],2);
    //     }

    //     //Query Total Arrears
    //     $totalArrear = collect(DB::table('meter_reg')
    //     ->where('cm_id',$cmId)
    //     ->where('mr_status',0)
    //     ->sum('mr_amount'))
    //     ->first();
    //     //Query Consumer E_Wallet
    //     $ewallet = collect(DB::table('e_wallet')
    //     ->where('cm_id',$cmId)
    //     ->select('ew_total_amount')
    //     ->first());

    //     $map = $br->map(function ($item) use ($data){
    //     // dd($item)
    //     // mr_lfln_disc
    //     // mr_dem_kwh_used
    //     $lflnDisc2 = $data->first()->mr_lfln_disc;
    //     $demKwhUsed = $data->first()->mr_dem_kwh_used;
    //     $kwhUsed =  $data->first()->mr_kwh_used;
    //     // dd($data);
    //     /* ---------------------------------------- GENERATION CHARGES ---------------------------------------*/
    //     $genSysCharge = round(round($item->br_gensys_rate * $kwhUsed,3),2);
    //     $powerActRed = round(round($item->br_par_rate * $kwhUsed,3),2);
    //     $franBenToHost = round(round($item->br_fbhc_rate * $kwhUsed,3),2);
    //     $forexAdjCharge = round(round($item->br_forex_rate * $kwhUsed,3),2);
    //     // $subTotalGc = number_format(($genSysCharge + $powerActRed + $franBenToHost + $forexAdjCharge),2);
    //     $subTotalGc = number_format($genSysCharge + $powerActRed + $franBenToHost + $forexAdjCharge,2,'.','');
    //     /*----------------------------------------- TRANSMISSION CHARGES --------------------------------------*/
    //     $tranSysCharge = round(round($item->br_transsys_rate * $kwhUsed,3),2);
    //     $tranDemCharge = round(round($item->br_transdem_rate * $demKwhUsed,3),2);
    //     $sysLossCharge = round(round($item->br_sysloss_rate * $kwhUsed,3),2);
    //     $subTotalTc = number_format($tranSysCharge + $tranDemCharge + $sysLossCharge,2,'.','');
    //     /*----------------------------------------- DISTRIBUTION CHARGES --------------------------------------*/
    //     $distSysCharge = round(round($item->br_distsys_rate * $kwhUsed,3),2);
    //     $distDemCharge = round(round($item->br_distdem_rate * $demKwhUsed,3),2);
    //     $supFixCharge = round($item->br_suprtlcust_fixed,2); //fix per cst
    //     $supSysCharge = round(round($item->br_supsys_rate * $kwhUsed,3),2);
    //     $meterFixCharge = round($item->br_mtrrtlcust_fixed,2); //fix 5per cst
    //     $meterSysCharge = round(round($item->br_mtrsys_rate * $kwhUsed,3),2);
    //     $subTotalDc = number_format($distSysCharge + $distDemCharge + $supFixCharge + $supSysCharge + $meterFixCharge + $meterSysCharge,2,'.','');
    //     /*----------------------------------------- OTHER CHARGES --------------------------------------*/
    //     $lflnDiscSubs = ($lflnDisc2 != 0) ? $lflnDisc2 * -1 : round(round($item->br_lfln_subs_rate * $kwhUsed,3),2);
    //     // $lflnDiscSubs = round(round($item->br_lfln_subs_rate * $kwhUsed,3),2);
    //     $senCitDiscSubs = round(round($item->br_sc_subs_rate * $kwhUsed,3),2);
    //     $intClssCrssSubs = round(round($item->br_intrclscrssubrte * $kwhUsed,3),2);
    //     $mccCapex = round(round($item->br_capex_rate * $kwhUsed,3),2);
    //     $loanCond = round(round($item->br_loancon_rate_kwh * $kwhUsed,3),2);
    //     $loanConFix = round($item->br_loancon_rate_fix,2); //fix
    //     $subTotalOc = number_format($lflnDiscSubs + $senCitDiscSubs + $intClssCrssSubs + $mccCapex + $loanCond + $loanConFix,2,'.','');
    //     /*----------------------------------------- UNIVERSAL CHARGES --------------------------------------*/
    //     $missElectSPUG = round(round($item->br_uc4_miss_rate_spu * $kwhUsed,3),2);
    //     $missElectRED = round(round($item->br_uc4_miss_rate_red * $kwhUsed,3),2);
    //     $enviChrg = round(round($item->br_uc6_envi_rate * $kwhUsed,3),2);
    //     $equaliRoyalty = round(round($item->br_uc5_equal_rate * $kwhUsed,3),2);
    //     $npcStrCC = round(round($item->br_uc2_npccon_rate * $kwhUsed,3),2);
    //     $npcStrDebt = round(round($item->br_uc1_npcdebt_rate * $kwhUsed,3),2);
    //     $fitAll = round(round($item->br_fit_all * $kwhUsed,3),2);
    //     $subTotalUc = number_format($missElectSPUG + $missElectRED + $enviChrg + $equaliRoyalty + $npcStrCC + $npcStrDebt + $fitAll,2,'.','');
    //     /*----------------------------------------- VALUE ADDED TAX --------------------------------------*/
    //     $genVat = round(round($item->br_vat_gen * $kwhUsed,3),2);
    //     $powerActRedVat = round(round($item->br_vat_par * $kwhUsed,3),2);
    //     $tranSysVat = round(round($item->br_vat_trans * $kwhUsed,3),2);
    //     $transDemVat = round(round($item->br_vat_transdem * $demKwhUsed,3),2);
    //     $sysLossVat = round(round($item->br_vat_systloss * $kwhUsed,3),2);
    //     $distSysVat = round(round($item->br_vat_distrib_kwh * $kwhUsed,3),2);
    //     $distDemVat = round(round($item->br_vat_distdem * $demKwhUsed,3),2);
    //     $supplyFixVat = round(round($item->br_vat_supfix,3),2);
    //     $supplySysVat = round(round($item->br_vat_supsys * $kwhUsed,3),2);
    //     $meterFixVat = round($item->br_vat_mtr_fix,2);
    //     $meterSysVat = round(round($item->br_vat_metersys * $kwhUsed,3),2);
    //     // $lflnDIscSubsVat = round(round($item->br_vat_lfln * $kwhUsed,3),2);
    //     $lflnDIscSubsVat = ($lflnDisc2 != 0) ? 0 : round(round($item->br_vat_lfln * $kwhUsed,3),2);
    //     $loanCondVat = round(round($item->br_vat_loancondo * $kwhUsed,3),2);
    //     $loanCondFixVat = round($item->br_vat_loancondofix,2);
    //     $otherVat = 0;
    //     $subTotalVat = number_format($genVat + $powerActRedVat + $tranSysVat + $transDemVat + $sysLossVat + $distSysVat + $distDemVat + 
    //     $supplyFixVat + $supplySysVat + $meterFixVat + $meterSysVat + $lflnDIscSubsVat + $loanCondVat + $loanCondFixVat + $otherVat,2,'.','');    

    //     return [
    //     /* --------------------------------------------------- GENERATION CHARGES -----------------------------------------------*/
    //     'Gen_Sys_Chrg'=>number_format(round($item->br_gensys_rate,4),4,'.','').'/kwh'.'@'.number_format($genSysCharge,2,'.',''),
    //     'Power_Act_Red'=>number_format(round($item->br_par_rate,4),4,'.','').'/kwh'.'@'.number_format($powerActRed,2,'.',''),
    //     'Fran_Ben_To_Host'=>number_format(round($item->br_fbhc_rate,4),4,'.','').'/kwh'.'@'.number_format($franBenToHost,2,'.',''),
    //     'FOREX_Adjust_Charge'=>number_format(round($item->br_forex_rate,4),4,'.','').'/kwh'.'@'.number_format($forexAdjCharge,2,'.',''),
    //     'SUB_TOTAL_GC'=>round($subTotalGc,2),
    //     /*---------------------------------------------------- TRANSMISSION CHARGES ----------------------------------------------*/
    //     'Trans_Sys_Charge'=>number_format(round($item->br_transsys_rate,4),4,'.','').'/kwh'.'@'.number_format($tranSysCharge,2,'.',''),
    //     'Trans_Dem_Charge'=>number_format(round($item->br_transdem_rate,4),4,'.','').'/kW'.'@'.number_format($tranDemCharge,2,'.',''),
    //     'System_Loss_Charge'=>number_format(round($item->br_sysloss_rate,4),4,'.','').'/kwh'.'@'.number_format($sysLossCharge,2,'.',''),
    //     'SUB_TOTAL_TC'=>round($subTotalTc,2),
    //     /*----------------------------------------------------- DISTRIBUTION CHARGES ---------------------------------------------*/
    //     'Dist_Sys_Chrg'=>number_format(round($item->br_distsys_rate,4),4,'.','').'/kwh'.'@'.number_format($distSysCharge,2,'.',''),
    //     'Dist_Dem_Chrg'=>number_format(round($item->br_distdem_rate,4),4,'.','').'/kW'.'@'.number_format($distDemCharge,2,'.',''),
    //     'Supply_Fix_Chrg'=>number_format(round($item->br_suprtlcust_fixed,4),4,'.','').'/cst'.'@'.number_format($supFixCharge,2,'.',''),
    //     'Supply_Sys_Chrg'=>number_format(round($item->br_supsys_rate,4),4,'.','').'/kwh'.'@'.number_format($supSysCharge,2,'.',''),
    //     'Metering_Fix_Chrg'=>number_format(round($item->br_mtrrtlcust_fixed,4),4,'.','').'/cst'.'@'.number_format($meterFixCharge,2,'.',''),
    //     'Metering_Sys_Chrg'=>number_format(round($item->br_mtrsys_rate,4),4,'.','').'/kwh'.'@'.number_format($meterSysCharge,2,'.',''),
    //     'Sub_Total_DC'=>round($subTotalDc,2),
    //     /*-------------------------------------------------------- OTHER CHARGES -------------------------------------------------*/
    //     'Lfln_Disc_Subs'=>($lflnDisc2 != 0) ? number_format(round($item->br_lfln_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($lflnDisc2,2,'.','') * -1 : number_format(round($item->br_lfln_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($lflnDiscSubs,2,'.',''),
    //     // 'Lfln_Disc_Subs'=>number_format(round($lflnDiscSubs,4),4,'.','').'/kwh'.'@'.number_format($lflnDiscSubs,2,'.',''),
    //     'Sen_Cit_Disc_Subs'=>number_format(round($item->br_sc_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($senCitDiscSubs,2,'.',''),
    //     'Int_Clss_Crss_Subs'=>number_format(round($item->br_intrclscrssubrte,4),4,'.','').'/kwh'.'@'.number_format($intClssCrssSubs,2,'.',''),
    //     'MCC_CAPEX'=>number_format(round($item->br_capex_rate,4),4,'.','').'/kwh'.'@'.number_format($mccCapex,2,'.',''),
    //     'Loan_Condonation'=>number_format(round($item->br_loancon_rate_kwh,4),4,'.','').'/kwh'.'@'.number_format($loanCond,2,'.',''),
    //     'Loan_Condon_Fix'=>number_format(round($item->br_loancon_rate_fix,4),4,'.','').'/cst'.'@'.number_format($loanConFix,2,'.',''),
    //     'SUB_TOTAL_OC'=>round($subTotalOc,2),
    //     /*------------------------------------------------------ UNIVERSAL CHARGES -----------------------------------------------*/
    //     'Miss_Elect_SPUG'=>number_format(round($item->br_uc4_miss_rate_spu,4),4,'.','').'/kwh'.'@'.number_format($missElectSPUG,2,'.',''),
    //     'Miss_Elect_RED'=>number_format(round($item->br_uc4_miss_rate_red,4),4,'.','').'/kwh'.'@'.number_format($missElectRED,2,'.',''),
    //     'Envi_Chrg'=>number_format(round($item->br_uc6_envi_rate,4),4,'.','').'/kwh'.'@'.number_format($enviChrg,2,'.',''),
    //     'Equali_Of_Taxes_Royalty'=>number_format(round($item->br_uc5_equal_rate,4),4,'.','').'/kwh'.'@'.number_format($equaliRoyalty,2,'.',''),
    //     'NPC_Str_Cons_Cost'=>number_format(round($item->br_uc2_npccon_rate,4),4,'.','').'/kwh'.'@'.number_format($npcStrCC,2,'.',''),
    //     'NPC_Str_Debt'=>number_format(round($item->br_uc1_npcdebt_rate,4),4,'.','').'/kwh'.'@'.number_format($npcStrDebt,2,'.',''),
    //     'Fit_All_RENEW'=>number_format(round($item->br_fit_all,4),4,'.','').'/kwh'.'@'.number_format($fitAll,2,'.',''),
    //     'SUB_TOTAL_UC'=>round($subTotalUc,2),
    //     /*------------------------------------------------------- VALUE ADDED TAX ------------------------------------------------*/
    //     'Generation_Vat'=>number_format(round($item->br_vat_gen,4),4,'.','').'/kwh'.'@'.number_format($genVat,2,'.',''),
    //     'Power_Act_Red_Vat' => number_format(round($item->br_vat_par,4),4,'.','').'/kwh'.'@'.number_format($powerActRedVat,2,'.',''),
    //     'Trans_Sys_Vat'=>number_format(round($item->br_vat_trans,4),4,'.','').'/kwh'.'@'.number_format($tranSysVat,2,'.',''),
    //     'Trans_Dem_Vat'=>number_format(round($item->br_vat_transdem,4),4,'.','').'/kW'.'@'.number_format($transDemVat,2,'.',''),
    //     'Sys_Loss_Vat'=>number_format(round($item->br_vat_systloss,4),4,'.','').'/kwh'.'@'.number_format($sysLossVat,2,'.',''),
    //     'Dist_Sys_Vat'=>number_format(round($item->br_vat_distrib_kwh,4),4,'.','').'/kwh'.'@'.number_format($distSysVat,2,'.',''),
    //     'Dist_Dem_Vat'=>number_format(round($item->br_vat_distdem,4),4,'.','').'/kW'.'@'.number_format($distDemVat,2,'.',''),
    //     'Supply_Fix_Vat' => number_format(round($item->br_vat_supfix,4),4,'.','').'/cst'.'@'.number_format($supplyFixVat,2,'.',''), // display supply sys
    //     'Supply_Sys_Vat' => number_format(round($item->br_vat_supsys,4),4,'.','').'/kwh'.'@'.number_format($supplySysVat,2,'.',''), // display supply sys
    //     'Metering_Fix_Vat' => number_format(round($item->br_vat_mtr_fix,4),4,'.','').'/cst'.'@'.number_format($meterFixVat,2,'.',''), //edit
    //     'Metering_Sys_Vat' => number_format(round($item->br_vat_metersys,4),4,'.','').'/kwh'.'@'.number_format($meterSysVat,2,'.',''),
    //     // 'Lfln_Disc_Vat' => number_format(round($item->br_vat_lfln,4),4,'.','').'/kwh'.'@'.number_format($lflnDIscSubsVat,2,'.',''), //edit
    //     'Lfln_Disc_Vat' => number_format(round($item->br_vat_lfln,4),4,'.','').'/kwh'.'@'.number_format($lflnDIscSubsVat,2,'.',''),
    //     'Loan_Cond_Vat'=>number_format(round($item->br_vat_loancondo,4),4,'.','').'/kwh'.'@'.number_format($loanCondVat,2,'.',''),
    //     'Loan_Cond_Fix_Vat'=>number_format(round($item->br_vat_loancondofix,4),4,'.','').'/cst'.'@'.number_format($loanCondFixVat,2,'.',''),
    //     'Other_Vat'=>number_format(round($otherVat,4),4,'.','').'/kwh'.'@'.number_format($otherVat,2,'.',''),
    //     'SUB_TOTAL_VAT'=>round($subTotalVat,2),
    //     ];
    //     });



    //     $fitallnew = explode('@',$map[0]['Fit_All_RENEW']);
    //     $q = explode('@',$map[0]['Gen_Sys_Chrg']);
    //     $powerActRed = explode('@',$map[0]['Power_Act_Red']);
    //     $fbtoHost = explode('@',$map[0]['Fran_Ben_To_Host']);
    //     $forexAC = explode('@',$map[0]['FOREX_Adjust_Charge']);
    //     $meSPUG = explode('@',$map[0]['Miss_Elect_SPUG']);
    //     $meRED = explode('@',$map[0]['Miss_Elect_RED']);
    //     $eChrg = explode('@',$map[0]['Envi_Chrg']);
    //     $eqTR = explode('@',$map[0]['Equali_Of_Taxes_Royalty']);
    //     $npcSCC = explode('@',$map[0]['NPC_Str_Cons_Cost']);
    //     $npcST = explode('@',$map[0]['NPC_Str_Debt']);
    //     $tSC = explode('@',$map[0]['Trans_Sys_Charge']);
    //     $tDC = explode('@',$map[0]['Trans_Dem_Charge']);
    //     $sLC = explode('@',$map[0]['System_Loss_Charge']);
    //     /*----*/
    //     $dSC = explode('@',$map[0]['Dist_Sys_Chrg']);
    //     $dDC = explode('@',$map[0]['Dist_Dem_Chrg']);
    //     $sFC = explode('@',$map[0]['Supply_Fix_Chrg']);
    //     $sSC = explode('@',$map[0]['Supply_Sys_Chrg']);
    //     $mFC = explode('@',$map[0]['Metering_Fix_Chrg']);
    //     $mSC = explode('@',$map[0]['Metering_Sys_Chrg']);
    //     $lDS = explode('@',$map[0]['Lfln_Disc_Subs']);
    //     $sCDS = explode('@',$map[0]['Sen_Cit_Disc_Subs']);
    //     $iCCS = explode('@',$map[0]['Int_Clss_Crss_Subs']);
    //     $mC = explode('@',$map[0]['MCC_CAPEX']);
    //     $lC = explode('@',$map[0]['Loan_Condonation']);
    //     $lCF = explode('@',$map[0]['Loan_Condon_Fix']);
    //     // if(rates.Meter_Number == null){
    //     //     rates.Meter_Number = ' ';
    //     // }
    //     $gV = explode('@',$map[0]['Generation_Vat']);
    //     $tSV = explode('@',$map[0]['Trans_Sys_Vat']);
    //     $tDV = explode('@',$map[0]['Trans_Dem_Vat']);
    //     $sLV = explode('@',$map[0]['Sys_Loss_Vat']);
    //     $dSV = explode('@',$map[0]['Dist_Sys_Vat']);
    //     $dDV = explode('@',$map[0]['Dist_Dem_Vat']);
    //     $lCV = explode('@',$map[0]['Loan_Cond_Vat']);
    //     $lCFV = explode('@',$map[0]['Loan_Cond_Fix_Vat']);
    //     $oV = explode('@',$map[0]['Other_Vat']);
    //     /* -----  new ------------- */
    //     $parV = explode('@',$map[0]['Power_Act_Red_Vat']);
    //     $sFV = explode('@',$map[0]['Supply_Fix_Vat']);
    //     $sSV = explode('@',$map[0]['Supply_Sys_Vat']);
    //     $mFV = explode('@',$map[0]['Metering_Fix_Vat']);
    //     $mSV = explode('@',$map[0]['Metering_Sys_Vat']);
    //     $lDV = explode('@',$map[0]['Lfln_Disc_Vat']);
    //     /* ------- */

    //     $html = '<html><style>
    //     @page {
    //     size: 21.59cm 27.94cm;
    //     margin: 0;
    //     }

    //     body {
    //     font-family: Arial, sans-serif; /* Use a common font family */
    //     font-size: 12px;
    //     }
    //     </style><body>';
    //     $html .= '<center>';
    //     $html .= '<label style="display:inline;font-size: 25px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
    //     $html .= '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
    //     $html .= '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br>';
    //     $html .= '<label style="font-size: 20px;"> STATEMENT OF ACCOUNT </label><br></center>';
    //     $html .= '</center>';

    //     $html .= '<div style = "width:100%">';
    //     // if(typeof arr[0].Reprint != 'undefined'){
    //     $html .= '<center><label style="font-size: 12px;">' . 'Online Print' . '</label></center>';
    //     // }
    //     // else{
    //     //     output += '<center><label style="font-size: 12px;"></label></center>';
    //     // }
    //     // var a = info.cm_account_no.toString();
    //     // var a1 = a.slice(0,2);
    //     // var a2 = a.slice(2,6);
    //     // var a3 = a.slice(6,10);
    //     // if(info.ct_desc == 'COMM WATER SYSTEM'){
    //     //     info.ct_desc = 'COMM WATER SYS.';
    //     // }
    //     $html .='<label style="font-weight:bold;font-size:14px;">SEQUENCE:' .'123'. '</label>';
    //     $html .= '<table style="border-bottom-style:dashed;border-bottom-color:black;border-bottom-width:1px;border-top-style:dashed;border-top-color:black;border-top-width:1px;width:100%;">';
    //     $html .= '<tr style="border-top: 1px dashed black;">';
    //     $html .= '<td style="width:15%;">' . 'Bill Number: ' . '</td>'.
    //     '<td style="width:23.5%"><label>'.'test'.'</label></td>' .
    //     '<td style="width:10%">Name: </td>' .
    //     '<td><label>' .'test'. '</label></td>' .
    //     '</tr>' .
    //     '<tr style="border-bottom:0.5px dashed black">' .
    //     '<td style="width:10%">Account No: </td>' .
    //     '<td><label>'. 'dasdsadsa'. '</label></td>' .
    //     '<td >Address: </td>' .
    //     '<td><label>'.'info.cm_address'.'</label></td>' .
    //     '</tr>';
    //     $html .= '</table>';
    //     $html .= '<table style="border-bottom-style:dashed;border-bottom-color:black;border-bottom-width:1px;width:100%;"height:10px;">';
    //     $html .= '<tr>';
    //     $html .= '<td style="width:17%">Bill Period : </td>' .
    //     '<td style="width:23%;"><label>'.'test123'.'</label></td>' .
    //     '<td style="width:5%">Rate Type: </td>' .
    //     '<td><label>'.'commercial'.'</label></td>' .
    //     '<td style="width:10%;">Demand: </td>' .
    //     '<td style="text-align:left;"><label>'.'0.00'.'</label></td>' .
    //     '</tr>';
    //     $html .= '<tr>';
    //     $html .= '<td>Meter Number: </td>' .
    //     '<td><label>'.' '.'</label></td>' .
    //     '<td style="width:1%">Multiplier: </td>' .
    //     '<td style="width:20%"><label>'.'1'.'</label></td>' .
    //     '<td style="width:20%;">Demand Kw Used: </td>' .
    //     '<td style="text-align:left;"><label>'.'0.00'.'</label></td>' .
    //     '</tr>';
    //     $html .='<tr>';
    //     $html .= '<td>Period From : </td>' .
    //     '<td><label>'.'test'.'</label></td>' .
    //     '<td>Pres Reading: </td>' .
    //     '<td><label>'.'test'.'</label></td>' .

    //     '</tr>';           
    //     $html .= '<tr>';
    //     $html .='<td>Period To : </td>' .
    //     '<td><label>'.'test'.'</label></td>' .
    //     '<td style="width:17.9%">Prev Reading: </td>' .
    //     '<td><label>'.'test'.'</label></td>' .
    //     '</tr>';
    //     $html .='<tr style="border-bottom:1px dashed black">';
    //     $html .='<td colspan=2 style="width:5%">No. of Days Covered:' .'31'. '</td>' .
    //     // '<td style="width:5%"><label>' + arr[x].No_Days_Covered+ '</label></td>' +
    //     '<td colspan=2 style="width:18%">Total KWH Used:' .'0.00'. '</td>' .
    //     '</tr></table>';

    //     // output += '<div class = "row" >';
    //     // output += '<div class = "col-sm-6" style="border-right: 1px dashed black;">';
    //     $html .= '<div style="float:left;width:50%">';
    //     $html .= '<table style="text-align:left;width:100%;">' .
    //     '<tr>' .
    //     '<th>CHARGES</th>' .
    //     '<th>RATE</th>' .
    //     '<th style="text-align:right">AMOUNT</th></tr>' . 
    //     '<tr>' .
    //     '<td>GENERATION CHARGES</td></tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Gen.Sys.Chrg' . '</td>' .
    //     '<td>' . $q[0] . '</td>' .
    //     '<td style="text-align:right;">' . $q[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Power Act Red.' . '</td>' .
    //     '<td>' . $powerActRed[0] . '</td>' .
    //     '<td style="text-align:right;">' . $powerActRed[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Fran.&Ben.to Host' . '</td>' .
    //     '<td>' . $fbtoHost[0] . '</td>' .
    //     '<td style="text-align:right;">' . $fbtoHost[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'FOREX Adjust. Chrg' . '</td>' .
    //     '<td>' . $forexAC[0] . '</td>' .
    //     '<td style="text-align:right;">' . $forexAC[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:15px;">' . 'SUB TOTAL (GC)' . '</td>' .
    //     '<td>' . ' dsads' . '</td>' .
    //     '<td style="text-align:right;">' .'0.00' . '</td>' .
    //     '</tr>' .
    //     '<tr>' .
    //     '<td>TRANSMISSION CHARGES</td></tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Trans.Sys.Charge' . '</td>' .
    //     '<td>' . $tSC[0] . '</td>' .
    //     '<td style="text-align:right;">' . $tSC[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Trans.Dem.Charge' . '</td>' .
    //     '<td>' . $tDC[0] . '</td>' .
    //     '<td style="text-align:right;">' . $tDC[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'System Loss Charge' . '</td>' .
    //     '<td>' . $sLC[0] . '</td>' .
    //     '<td style="text-align:right;">' . $sLC[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:15px">' . 'SUB TOTAL (TC)' . '</td>' .
    //     '<td>' . ' ' . '</td>' .
    //     '<td style="text-align:right;">' .'0.00' . '</td>' .
    //     '</tr>' . 
    //     '<tr>' .
    //     '<td >DISTRIBUTION CHARGES</td></tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Dist.Sys.Charge' . '</td>' .
    //     '<td>' . $dSC[0] . '</td>' .
    //     '<td style="text-align:right;">' . $dSC[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Dist.Dem.Charge' . '</td>' .
    //     '<td>' . $dDC[0] . '</td>' .
    //     '<td style="text-align:right;">' . $dDC[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Supply Fix Charge' . '</td>' .
    //     '<td>' . $sFC[0] . '</td>' .
    //     '<td style="text-align:right;">' . $sFC[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Supply Sys. Charge' . '</td>' .
    //     '<td>' . $sSC[0] . '</td>' .
    //     '<td style="text-align:right;">' . $sSC[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Metering Fix Charge' . '</td>' .
    //     '<td>' . $mFC[0] . '</td>' .
    //     '<td style="text-align:right;">' . $mFC[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Metering Sys Charge' . '</td>' .
    //     '<td>' . $mSC[0] . '</td>' .
    //     '<td style="text-align:right;">' . $mSC[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:15px;">' . 'SUB TOTAL (DC)' . '</td>' .
    //     '<td>' . ' ' . '</td>' .
    //     '<td style="text-align:right;">' . '0.00'.'</td>' .
    //     '</tr>' . 
    //     '<tr>' .
    //     '<td>OTHER CHARGES</td></tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Lfln Disc./Subs.' . '</td>';
    //     if($lDS[1] < 0){
    //     $html .= '<td>' . '0.0000/kwh' . '</td>';
    //     }
    //     else{
    //     $html .= '<td>' . $lDS[0] . '</td>';  
    //     }

    //     $html .='<td style="text-align:right;">' . $lDS[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Sen.Cit.Disc./Subs.' . '</td>' .
    //     '<td>' . $sCDS[0] . '</td>' .
    //     '<td style="text-align:right;">' . $sCDS[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Int.Clss.Crss.Subs' . '</td>' .
    //     '<td>' . $iCCS[0] . '</td>' .
    //     '<td style="text-align:right;">' . $iCCS[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'MCC CAPEX' . '</td>' .
    //     '<td>' . $mC[0] . '</td>' .
    //     '<td style="text-align:right;">' . $mC[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Loan Condonation' . '</td>' .
    //     '<td>' . $lC[0] . '</td>' .
    //     '<td style="text-align:right;">' . $lC[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Loan Condon Fix' . '</td>' .
    //     '<td>' . $lCF[0] . '</td>' .
    //     '<td style="text-align:right;">' . $lCF[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:15px;">' . 'SUB TOTAL (OC)' . '</td>' .
    //     '<td>' . ' ' . '</td>' .
    //     '<td style="text-align:right;">' .'0.00'.'</td>' .
    //     '</tr>';
    //     $html .= '<tr>' . 
    //     '<td>[LAST PAYMENT]</td>'. 
    //     '</tr>';  
    //     // var lp = last_payment.split('@');

    //     // if(typeof lp[1] == 'undefined' && typeof lp[2] == 'undefined'){
    //     //     lp[1] = ' ';
    //     //     lp[2] = ' ';
    //     // }
    //     // else{
    //     //     lp[1] = lp[1];
    //     //     lp[2] = ' ';
    //     //     lp[1] = parseFloat(lp[1]);
    //     // }
    //     $lp[0]= 1;
    //     $lp[1]= 2;
    //     $lp[2]= 3;
    //     $html .='<tr>' .
    //     '<td>'.$lp[0].'</td>' . 
    //     '<td>'.$lp[1].'</td>' . 
    //     '<td style="text-align:right;">'.$lp[2].'</td></tr>';
    //     $html .='</table><br>';
    //     $html .='<div style="text-align:center;border:1px solid #5B9BD5">';
    //     $html .='<p style="display:inline;font-size:12px">Please pay your Power Bill in any Official LASURECO</p><br>' .
    //     '<p style="display:inline;font-size:12px">Paying Centers, Authorized MOBILE Tellering Outlet</p><br>' .  
    //     '<p style="display:inline;font-size:12px">with Official Receipt presented or</p><br>'.
    //     '<p style="display:inline;font-size:12px">through Online Payment via GCASH and PayMaya.</p><br>'.
    //     '<p style="display:inline;font-size:12px">Visit www.lasureco.com/online-payment</p>'.
    //     '</div>';
    //     $html .='</div>';
    //     $html .='<div style="border-left:1px dashed black;float:right;width:49%">';
    //     $html .='<table style="text-align:left;width:100%;">' .
    //     '<tr>' .
    //     '<th> CHARGES </th>' .
    //     '<th> RATE </th>' .
    //     '<th style="text-align:right;"> AMOUNT </th>' . 
    //     '<tr>' .
    //     '<td>UNIVERSAL CHARGES</td></tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Miss.Elect.(SPUG)' . '</td>' .
    //     '<td>' . $meSPUG[0] . '</td>' .
    //     '<td style="text-align:right;">' . $meSPUG[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Miss.Elect.(RED)' . '</td>' .
    //     '<td>' . $meRED[0] . '</td>' .
    //     '<td style="text-align:right;">' . $meRED[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Envi. Chrg' . '</td>' .
    //     '<td>' . $eChrg[0] . '</td>' .
    //     '<td style="text-align:right;">' . $eChrg[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Equali. of Taxes & Royalty' . '</td>' .
    //     '<td>' . $eqTR[0] . '</td>' .
    //     '<td style="text-align:right;">' . $eqTR[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'NPC Str Cons Cost' . '</td>' .
    //     '<td>' . $npcSCC[0] . '</td>' .
    //     '<td style="text-align:right;">' . $npcSCC[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'NPC Stranded Debt' . '</td>' .
    //     '<td>' . $npcST[0] . '</td>' .
    //     '<td style="text-align:right;">' . $npcST[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Fit All(Renew)' . '</td>' .
    //     '<td>' . $fitallnew[0] . '</td>' .
    //     '<td style="text-align:right;">' . $fitallnew[1] . '</td>' .
    //     '</tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:15px;">' . 'SUB TOTAL (UC)' . '</td>' .
    //     '<td>' . ' ' . '</td>' .
    //     '<td style="text-align:right;">' .'0.00'.'</td>' .
    //     '</tr>' .                    
    //     '<tr>' .
    //     '<td>VALUE ADDED TAX</td></tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Generation VAT' . '</td>' .
    //     '<td>' . $gV[0] . '</td>' .
    //     '<td style="text-align:right;">' . $gV[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Power Act Red VAT' . '</td>' .
    //     '<td>' .$parV[0] . '</td>' .
    //     '<td style="text-align:right;">' . $parV[1] . '</td>' .
    //     '</tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Trans. Sys. VAT' . '</td>' .
    //     '<td>' . $tSV[0] . '</td>' .
    //     '<td style="text-align:right;">' . $tSV[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Trans. Dem. VAT' . '</td>' .
    //     '<td>' . $tDV[0] . '</td>' .
    //     '<td style="text-align:right;">' . $tDV[1] . '</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'System Loss VAT' . '</td>' .
    //     '<td>' . $sLV[0] . '</td>' .
    //     '<td style="text-align:right;">' . $sLV[1] . '</td>' .
    //     '</tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Dist. Sys. VAT' . '</td>' .
    //     '<td>' . $dSV[0] . '</td>' .
    //     '<td style="text-align:right;">' . $dSV[1] . '</td>' .
    //     '</tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Dist. Dem. VAT' . '</td>' .
    //     '<td>' . $dDV[0] . '</td>' .
    //     '<td style="text-align:right;">' . $dDV[1] . '</td>' .
    //     '</tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Supply Fix VAT' . '</td>' .
    //     '<td>' . $sFV[0] . '</td>' .
    //     '<td style="text-align:right;">' . $sFV[1] . '</td>' .
    //     '</tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Supply Sys VAT' . '</td>' .
    //     '<td>' . $sSV[0] . '</td>' .
    //     '<td style="text-align:right;">' . $sSV[1] . '</td>' .
    //     '</tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Metering Fix VAT' . '</td>' .
    //     '<td>' . $mFV[0] . '</td>' .
    //     '<td style="text-align:right;">' . $mFV[1] . '</td>' .
    //     '</tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Metering Sys VAT' . '</td>' .
    //     '<td>' . $mSV[0] . '</td>' .
    //     '<td style="text-align:right;">' . $mSV[1] . '</td>' .
    //     '</tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;font-size:12px">' . 'Lfln Disc./Subs. VAT' . '</td>' .
    //     '<td>' . $lDV[0] . '</td>' .
    //     '<td style="text-align:right;">' . $lDV[1] . '</td>' .
    //     '</tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Loan Condo. VAT' . '</td>' .
    //     '<td>' . $lCV[0] . '</td>' .
    //     '<td style="text-align:right;">' . $lCV[1] . '</td>' .
    //     '</tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Loan Cond. Fix VAT' . '</td>' .
    //     '<td>' . $lCFV[0] . '</td>' .
    //     '<td style="text-align:right;">' . $lCFV[1] . '</td>' .
    //     '</tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Other VAT' . '</td>' .
    //     '<td>' . $oV[0] . '</td>' .
    //     '<td style="text-align:right;">' . $oV[1] . '</td>' .
    //     '</tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="border-bottom: 1px dashed black;padding-left:15px;">' . 'SUB TOTAL (VAT)' . '</td>' .
    //     '<td style="border-bottom: 1px dashed black">' . ' ' . '</td>' .
    //     '<td style="border-bottom: 1px dashed black;text-align:right" >'.'0.00'.'</td>' .
    //     '</tr>' . 
    //     '<tr style="border-bottom: 1px dashed black">' .
    //     '<td>CURRENT BILL</td>' .
    //     '<td>Php</td>' .
    //     '<td style="text-align:right;">' .'data.mr_amount'.'</td>' .
    //     '</tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Total Arrears' . '</td>' .
    //     '<td>' . ' ' . '</td>' .
    //     '<td style="text-align:right;">'.'total_arrears'.'</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Material Cost/Integ' . '</td>' .
    //     '<td>' . ' ' . '</td>' .
    //     '<td style="text-align:right;">0.00</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'Transformer Rental' . '</td>' .
    //     '<td>' . ' ' . '</td>' .
    //     '<td style="text-align:right;">0.00</td>' .
    //     '</tr>' . 
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'TOTAL AMOUNT DUE' . '</td>' .
    //     '<td>' . ' ' . '</td>' .
    //     '<td style="text-align:right;">'.'total_arrears'.'</td>' .
    //     '</tr>';

    //     // if(info.ct_desc == 'PUBLIC BUILDING' || info.ct_desc == 'COMMERCIAL' ){
    //     $html .='<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'LGU 5%' . '</td>' .
    //     '<td>' . 'dsadsa' . '</td>' .
    //     '<td style="text-align:right;">dasdsa</td>' .
    //     '</tr>' .
    //     '<tr style="text-align:left;">' .
    //     '<td style="padding-left:10px;">' . 'LGU 2%' . '</td>' .
    //     '<td>' . 'dsadsa' . '</td>' .
    //     '<td style="text-align:right;">dsadsa</td>' .
    //     '</tr>';
    //     // }
    //     $html .='</table><br>';
    //     $html .='<table style="font-size:12px;" width="100%">';
    //     $html .='<tr style="text-align:left;">' .
    //     '<td> E - Wallet </td>' .
    //     '<td style="text-align:right;">'.'dsadsad'.'</td>' .
    //     '</tr>';
    //     // var ddate = data.mr_due_date.split(' ');
    //     $html .='<tr><td width="27%">DUE DATE: </td><td>'.'duedate'.'</td></tr></table><br>';
    //     $html .='</div>';

    //     $html .='</div>'; 


            

    //     $html .= '</body></html>';

    //     $pdf = Pdf::loadHTML($html);

    //     return $pdf->stream('document.pdf', ['Attachment' => false]);
    // }

    public function inquiry2(Request $request)
    {
        $samp = carbon::parse($request->bill_period)->format('Ym');
        // dd($samp);
        $data = MeterReg::select(
        'br_id',
        'cons_account',
        'mr_date_year_month',
        'mr_amount',
        'mr_kwh_used',
        'mr_prev_reading',
        'mr_pres_reading',
        'mr_due_date',
        'mr_discon_date',
        'mr_status',
        'mr_dem_kwh_used',
        'mr_lfln_disc',
        'cm_id',
        'mr_bill_no',
        'mr_pres_dem_reading',
        'mr_date_reg'
        ) 
        ->where('cons_account',$request->account_no)
        ->where('mr_date_year_month',$request->bill_period)
        ->where('mr_printed',1)
        ->get();
        //    dd($data->first()->mr_date_reg);
        if($data->isEmpty()){
        return response()->json(['message'=>'No powerbill on the give date!'],404);
        }
        $brId = $data->first()->br_id;
        $cmId = $data->first()->cm_id;
        $mdYM = $data->first()->mr_due_date;
        $br = collect(DB::table('billing_rates')
        ->select('*')
        ->where('id',$brId)
        ->get());
        // dd($br);
        // $kwhUsed = 
        // $consumerInfo = collect(DB::table('cons_master')
        //     ->select('cm_seq_no','cm_full_name','cm_address','cm_account_no')
        //     ->where('cm_id', $cmId)
        //     ->first()                
        // );
        $consumerInfo = collect(DB::table('cons_master as cm')
        ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
        ->leftJoin('meter_master as mm', 'cm.mm_id','=','mm.mm_id')
        ->select('mm.mm_serial_no','cm.cm_kwh_mult','cm.cm_seq_no','cm.cm_lgu2','cm.cm_lgu5','cm.cm_full_name','cm.cm_address','cm.cm_account_no','ct.ct_desc')
        ->where('cm_id', $cmId)
        ->first()                
        );
        // dd($consumerInfo['cm_lgu2']);
        $periodFrom = collect(DB::table('meter_reg as mr')
        ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
        ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
        ->where('cm.cm_id',$cmId)
        ->where('mr.mr_date_year_month','<',$request->bill_period)
        ->whereNotNull('mr.mr_date_reg')
        ->orderBy('mr.mr_date_year_month','desc')
        ->first());
        // dd($periodFrom['mr_date_reg'],$samp);
        if(!isset($periodFrom['mr_date_reg']))
        {
        $periodFrom = $request->bill_period;
        // $item->mr_date_reg = 0;
        }else{
        $periodFrom = $periodFrom['mr_date_reg'];
        }

        $periodFromConv = date('m/d/Y', strtotime($periodFrom));

        $periodToConv = date('m/d/Y', strtotime($data->first()->mr_date_reg));
        $datetime1 = new DateTime($periodFromConv);
        $datetime2 = new DateTime($periodToConv);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');
        $lastPayment = collect(DB::table('sales as s')
        ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
        ->select(DB::raw('COALESCE(sum(s.s_or_amount),0) as bill_amount,COALESCE(sum(s.e_wallet_added),0) as ewAdd,s.s_or_num,s.cm_id,s.s_bill_date'))
        // ->whereRaw('a.s_bill_date = (SELECT MAX(b.s_bill_date) FROM sales b WHERE a.cm_id = b.cm_id)')
        ->where('s.cm_id',$cmId)
        ->where('mr.mr_status',1)
        ->orderByDesc('s.s_bill_date')
        ->groupBy('s.s_bill_date')
        ->first());
        $dueDate1 = date('F d, Y', strtotime($mdYM));
        $checkLastPayment = $lastPayment->first();
        if(!$checkLastPayment)
        {
        $lastPayment = 'No Payment Received';
        }else{
        // $lastPayment = collect([date('m/d/Y', strtotime($lastPayment['s_bill_date']).' '.$lastPayment['s_or_num'].' '.$lastPayment['s_bill_amount'])]);

        $lastPayment = date('m/d/Y', strtotime($lastPayment['s_bill_date'])).'@'.round($lastPayment['bill_amount'] + $lastPayment['ewAdd'],2);
        }

        //Query Total Arrears
        $totalArrear = collect(DB::table('meter_reg')
        ->where('cm_id',$cmId)
        ->where('mr_date_year_month','<',$request->bill_period)
        ->where('mr_status',0)
        ->sum('mr_amount'))
        ->first();
        $tad = round($data->first()->mr_amount + $totalArrear,2);
        //Query Consumer E_Wallet
        $ewallet = collect(DB::table('e_wallet')
        ->where('cm_id',$cmId)
        ->select('ew_total_amount')
        ->first());
        // dd($ewallet['ew_total_amount']);
        $map = $br->map(function ($item) use ($data){
        // dd($item)
        // mr_lfln_disc
        // mr_dem_kwh_used
            $lflnDisc2 = $data->first()->mr_lfln_disc;
            $demKwhUsed = $data->first()->mr_dem_kwh_used;
            $kwhUsed =  $data->first()->mr_kwh_used;
            // dd($data);
                /* ---------------------------------------- GENERATION CHARGES ---------------------------------------*/
                $genSysCharge = round(round($item->br_gensys_rate * $kwhUsed,3),2);
                $powerActRed = round(round($item->br_par_rate * $kwhUsed,3),2);
                $franBenToHost = round(round($item->br_fbhc_rate * $kwhUsed,3),2);
                $forexAdjCharge = round(round($item->br_forex_rate * $kwhUsed,3),2);
                // $subTotalGc = number_format(($genSysCharge + $powerActRed + $franBenToHost + $forexAdjCharge),2);
                $subTotalGc = number_format($genSysCharge + $powerActRed + $franBenToHost + $forexAdjCharge,2,'.','');
                /*----------------------------------------- TRANSMISSION CHARGES --------------------------------------*/
                $tranSysCharge = round(round($item->br_transsys_rate * $kwhUsed,3),2);
                $tranDemCharge = round(round($item->br_transdem_rate * $demKwhUsed,3),2);
                $sysLossCharge = round(round($item->br_sysloss_rate * $kwhUsed,3),2);
                $subTotalTc = number_format($tranSysCharge + $tranDemCharge + $sysLossCharge,2,'.','');
                /*----------------------------------------- DISTRIBUTION CHARGES --------------------------------------*/
                $distSysCharge = round(round($item->br_distsys_rate * $kwhUsed,3),2);
                $distDemCharge = round(round($item->br_distdem_rate * $demKwhUsed,3),2);
                $supFixCharge = round($item->br_suprtlcust_fixed,2); //fix per cst
                $supSysCharge = round(round($item->br_supsys_rate * $kwhUsed,3),2);
                $meterFixCharge = round($item->br_mtrrtlcust_fixed,2); //fix 5per cst
                $meterSysCharge = round(round($item->br_mtrsys_rate * $kwhUsed,3),2);
                $subTotalDc = number_format($distSysCharge + $distDemCharge + $supFixCharge + $supSysCharge + $meterFixCharge + $meterSysCharge,2,'.','');
                /*----------------------------------------- OTHER CHARGES --------------------------------------*/
                $lflnDiscSubs = ($lflnDisc2 != 0) ? $lflnDisc2 * -1 : round(round($item->br_lfln_subs_rate * $kwhUsed,3),2);
                // $lflnDiscSubs = round(round($item->br_lfln_subs_rate * $kwhUsed,3),2);
                $senCitDiscSubs = round(round($item->br_sc_subs_rate * $kwhUsed,3),2);
                $intClssCrssSubs = round(round($item->br_intrclscrssubrte * $kwhUsed,3),2);
                $mccCapex = round(round($item->br_capex_rate * $kwhUsed,3),2);
                $loanCond = round(round($item->br_loancon_rate_kwh * $kwhUsed,3),2);
                $loanConFix = round($item->br_loancon_rate_fix,2); //fix
                $subTotalOc = number_format($lflnDiscSubs + $senCitDiscSubs + $intClssCrssSubs + $mccCapex + $loanCond + $loanConFix,2,'.','');
                /*----------------------------------------- UNIVERSAL CHARGES --------------------------------------*/
                $missElectSPUG = round(round($item->br_uc4_miss_rate_spu * $kwhUsed,3),2);
                $missElectRED = round(round($item->br_uc4_miss_rate_red * $kwhUsed,3),2);
                $enviChrg = round(round($item->br_uc6_envi_rate * $kwhUsed,3),2);
                $equaliRoyalty = round(round($item->br_uc5_equal_rate * $kwhUsed,3),2);
                $npcStrCC = round(round($item->br_uc2_npccon_rate * $kwhUsed,3),2);
                $npcStrDebt = round(round($item->br_uc1_npcdebt_rate * $kwhUsed,3),2);
                $fitAll = round(round($item->br_fit_all * $kwhUsed,3),2);
                $subTotalUc = number_format($missElectSPUG + $missElectRED + $enviChrg + $equaliRoyalty + $npcStrCC + $npcStrDebt + $fitAll,2,'.','');
                /*----------------------------------------- VALUE ADDED TAX --------------------------------------*/
                $genVat = round(round($item->br_vat_gen * $kwhUsed,3),2);
                $powerActRedVat = round(round($item->br_vat_par * $kwhUsed,3),2);
                $tranSysVat = round(round($item->br_vat_trans * $kwhUsed,3),2);
                $transDemVat = round(round($item->br_vat_transdem * $demKwhUsed,3),2);
                $sysLossVat = round(round($item->br_vat_systloss * $kwhUsed,3),2);
                $distSysVat = round(round($item->br_vat_distrib_kwh * $kwhUsed,3),2);
                $distDemVat = round(round($item->br_vat_distdem * $demKwhUsed,3),2);
                $supplyFixVat = round(round($item->br_vat_supfix,3),2);
                $supplySysVat = round(round($item->br_vat_supsys * $kwhUsed,3),2);
                $meterFixVat = round($item->br_vat_mtr_fix,2);
                $meterSysVat = round(round($item->br_vat_metersys * $kwhUsed,3),2);
                // $lflnDIscSubsVat = round(round($item->br_vat_lfln * $kwhUsed,3),2);
                $lflnDIscSubsVat = ($lflnDisc2 != 0) ? 0 : round(round($item->br_vat_lfln * $kwhUsed,3),2);
                $loanCondVat = round(round($item->br_vat_loancondo * $kwhUsed,3),2);
                $loanCondFixVat = round($item->br_vat_loancondofix,2);
                $otherVat = 0;
                $subTotalVat = number_format($genVat + $powerActRedVat + $tranSysVat + $transDemVat + $sysLossVat + $distSysVat + $distDemVat + 
                $supplyFixVat + $supplySysVat + $meterFixVat + $meterSysVat + $lflnDIscSubsVat + $loanCondVat + $loanCondFixVat + $otherVat,2,'.','');    

                return [
                /* --------------------------------------------------- GENERATION CHARGES -----------------------------------------------*/
                'Gen_Sys_Chrg'=>number_format(round($item->br_gensys_rate,4),4,'.','').'/kwh'.'@'.number_format($genSysCharge,2,'.',''),
                'Power_Act_Red'=>number_format(round($item->br_par_rate,4),4,'.','').'/kwh'.'@'.number_format($powerActRed,2,'.',''),
                'Fran_Ben_To_Host'=>number_format(round($item->br_fbhc_rate,4),4,'.','').'/kwh'.'@'.number_format($franBenToHost,2,'.',''),
                'FOREX_Adjust_Charge'=>number_format(round($item->br_forex_rate,4),4,'.','').'/kwh'.'@'.number_format($forexAdjCharge,2,'.',''),
                'SUB_TOTAL_GC'=>round($subTotalGc,2),
                /*---------------------------------------------------- TRANSMISSION CHARGES ----------------------------------------------*/
                'Trans_Sys_Charge'=>number_format(round($item->br_transsys_rate,4),4,'.','').'/kwh'.'@'.number_format($tranSysCharge,2,'.',''),
                'Trans_Dem_Charge'=>number_format(round($item->br_transdem_rate,4),4,'.','').'/kW'.'@'.number_format($tranDemCharge,2,'.',''),
                'System_Loss_Charge'=>number_format(round($item->br_sysloss_rate,4),4,'.','').'/kwh'.'@'.number_format($sysLossCharge,2,'.',''),
                'SUB_TOTAL_TC'=>round($subTotalTc,2),
                /*----------------------------------------------------- DISTRIBUTION CHARGES ---------------------------------------------*/
                'Dist_Sys_Chrg'=>number_format(round($item->br_distsys_rate,4),4,'.','').'/kwh'.'@'.number_format($distSysCharge,2,'.',''),
                'Dist_Dem_Chrg'=>number_format(round($item->br_distdem_rate,4),4,'.','').'/kW'.'@'.number_format($distDemCharge,2,'.',''),
                'Supply_Fix_Chrg'=>number_format(round($item->br_suprtlcust_fixed,4),4,'.','').'/cst'.'@'.number_format($supFixCharge,2,'.',''),
                'Supply_Sys_Chrg'=>number_format(round($item->br_supsys_rate,4),4,'.','').'/kwh'.'@'.number_format($supSysCharge,2,'.',''),
                'Metering_Fix_Chrg'=>number_format(round($item->br_mtrrtlcust_fixed,4),4,'.','').'/cst'.'@'.number_format($meterFixCharge,2,'.',''),
                'Metering_Sys_Chrg'=>number_format(round($item->br_mtrsys_rate,4),4,'.','').'/kwh'.'@'.number_format($meterSysCharge,2,'.',''),
                'Sub_Total_DC'=>round($subTotalDc,2),
                /*-------------------------------------------------------- OTHER CHARGES -------------------------------------------------*/
                'Lfln_Disc_Subs'=>($lflnDisc2 != 0) ? number_format(round($item->br_lfln_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($lflnDisc2,2,'.','') * -1 : number_format(round($item->br_lfln_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($lflnDiscSubs,2,'.',''),
                // 'Lfln_Disc_Subs'=>number_format(round($lflnDiscSubs,4),4,'.','').'/kwh'.'@'.number_format($lflnDiscSubs,2,'.',''),
                'Sen_Cit_Disc_Subs'=>number_format(round($item->br_sc_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($senCitDiscSubs,2,'.',''),
                'Int_Clss_Crss_Subs'=>number_format(round($item->br_intrclscrssubrte,4),4,'.','').'/kwh'.'@'.number_format($intClssCrssSubs,2,'.',''),
                'MCC_CAPEX'=>number_format(round($item->br_capex_rate,4),4,'.','').'/kwh'.'@'.number_format($mccCapex,2,'.',''),
                'Loan_Condonation'=>number_format(round($item->br_loancon_rate_kwh,4),4,'.','').'/kwh'.'@'.number_format($loanCond,2,'.',''),
                'Loan_Condon_Fix'=>number_format(round($item->br_loancon_rate_fix,4),4,'.','').'/cst'.'@'.number_format($loanConFix,2,'.',''),
                'SUB_TOTAL_OC'=>round($subTotalOc,2),
                /*------------------------------------------------------ UNIVERSAL CHARGES -----------------------------------------------*/
                'Miss_Elect_SPUG'=>number_format(round($item->br_uc4_miss_rate_spu,4),4,'.','').'/kwh'.'@'.number_format($missElectSPUG,2,'.',''),
                'Miss_Elect_RED'=>number_format(round($item->br_uc4_miss_rate_red,4),4,'.','').'/kwh'.'@'.number_format($missElectRED,2,'.',''),
                'Envi_Chrg'=>number_format(round($item->br_uc6_envi_rate,4),4,'.','').'/kwh'.'@'.number_format($enviChrg,2,'.',''),
                'Equali_Of_Taxes_Royalty'=>number_format(round($item->br_uc5_equal_rate,4),4,'.','').'/kwh'.'@'.number_format($equaliRoyalty,2,'.',''),
                'NPC_Str_Cons_Cost'=>number_format(round($item->br_uc2_npccon_rate,4),4,'.','').'/kwh'.'@'.number_format($npcStrCC,2,'.',''),
                'NPC_Str_Debt'=>number_format(round($item->br_uc1_npcdebt_rate,4),4,'.','').'/kwh'.'@'.number_format($npcStrDebt,2,'.',''),
                'Fit_All_RENEW'=>number_format(round($item->br_fit_all,4),4,'.','').'/kwh'.'@'.number_format($fitAll,2,'.',''),
                'SUB_TOTAL_UC'=>round($subTotalUc,2),
                /*------------------------------------------------------- VALUE ADDED TAX ------------------------------------------------*/
                'Generation_Vat'=>number_format(round($item->br_vat_gen,4),4,'.','').'/kwh'.'@'.number_format($genVat,2,'.',''),
                'Power_Act_Red_Vat' => number_format(round($item->br_vat_par,4),4,'.','').'/kwh'.'@'.number_format($powerActRedVat,2,'.',''),
                'Trans_Sys_Vat'=>number_format(round($item->br_vat_trans,4),4,'.','').'/kwh'.'@'.number_format($tranSysVat,2,'.',''),
                'Trans_Dem_Vat'=>number_format(round($item->br_vat_transdem,4),4,'.','').'/kW'.'@'.number_format($transDemVat,2,'.',''),
                'Sys_Loss_Vat'=>number_format(round($item->br_vat_systloss,4),4,'.','').'/kwh'.'@'.number_format($sysLossVat,2,'.',''),
                'Dist_Sys_Vat'=>number_format(round($item->br_vat_distrib_kwh,4),4,'.','').'/kwh'.'@'.number_format($distSysVat,2,'.',''),
                'Dist_Dem_Vat'=>number_format(round($item->br_vat_distdem,4),4,'.','').'/kW'.'@'.number_format($distDemVat,2,'.',''),
                'Supply_Fix_Vat' => number_format(round($item->br_vat_supfix,4),4,'.','').'/cst'.'@'.number_format($supplyFixVat,2,'.',''), // display supply sys
                'Supply_Sys_Vat' => number_format(round($item->br_vat_supsys,4),4,'.','').'/kwh'.'@'.number_format($supplySysVat,2,'.',''), // display supply sys
                'Metering_Fix_Vat' => number_format(round($item->br_vat_mtr_fix,4),4,'.','').'/cst'.'@'.number_format($meterFixVat,2,'.',''), //edit
                'Metering_Sys_Vat' => number_format(round($item->br_vat_metersys,4),4,'.','').'/kwh'.'@'.number_format($meterSysVat,2,'.',''),
                // 'Lfln_Disc_Vat' => number_format(round($item->br_vat_lfln,4),4,'.','').'/kwh'.'@'.number_format($lflnDIscSubsVat,2,'.',''), //edit
                'Lfln_Disc_Vat' => number_format(round($item->br_vat_lfln,4),4,'.','').'/kwh'.'@'.number_format($lflnDIscSubsVat,2,'.',''),
                'Loan_Cond_Vat'=>number_format(round($item->br_vat_loancondo,4),4,'.','').'/kwh'.'@'.number_format($loanCondVat,2,'.',''),
                'Loan_Cond_Fix_Vat'=>number_format(round($item->br_vat_loancondofix,4),4,'.','').'/cst'.'@'.number_format($loanCondFixVat,2,'.',''),
                'Other_Vat'=>number_format(round($otherVat,4),4,'.','').'/kwh'.'@'.number_format($otherVat,2,'.',''),
                'SUB_TOTAL_VAT'=>round($subTotalVat,2),
                ];
        });



        $fitallnew = explode('@',$map[0]['Fit_All_RENEW']);
        $q = explode('@',$map[0]['Gen_Sys_Chrg']);
        $powerActRed = explode('@',$map[0]['Power_Act_Red']);
        $fbtoHost = explode('@',$map[0]['Fran_Ben_To_Host']);
        $forexAC = explode('@',$map[0]['FOREX_Adjust_Charge']);
        $meSPUG = explode('@',$map[0]['Miss_Elect_SPUG']);
        $meRED = explode('@',$map[0]['Miss_Elect_RED']);
        $eChrg = explode('@',$map[0]['Envi_Chrg']);
        $eqTR = explode('@',$map[0]['Equali_Of_Taxes_Royalty']);
        $npcSCC = explode('@',$map[0]['NPC_Str_Cons_Cost']);
        $npcST = explode('@',$map[0]['NPC_Str_Debt']);
        $tSC = explode('@',$map[0]['Trans_Sys_Charge']);
        $tDC = explode('@',$map[0]['Trans_Dem_Charge']);
        $sLC = explode('@',$map[0]['System_Loss_Charge']);
        /*----*/
        $dSC = explode('@',$map[0]['Dist_Sys_Chrg']);
        $dDC = explode('@',$map[0]['Dist_Dem_Chrg']);
        $sFC = explode('@',$map[0]['Supply_Fix_Chrg']);
        $sSC = explode('@',$map[0]['Supply_Sys_Chrg']);
        $mFC = explode('@',$map[0]['Metering_Fix_Chrg']);
        $mSC = explode('@',$map[0]['Metering_Sys_Chrg']);
        $lDS = explode('@',$map[0]['Lfln_Disc_Subs']);
        $sCDS = explode('@',$map[0]['Sen_Cit_Disc_Subs']);
        $iCCS = explode('@',$map[0]['Int_Clss_Crss_Subs']);
        $mC = explode('@',$map[0]['MCC_CAPEX']);
        $lC = explode('@',$map[0]['Loan_Condonation']);
        $lCF = explode('@',$map[0]['Loan_Condon_Fix']);
        // if(rates.Meter_Number == null){
        //     rates.Meter_Number = ' ';
        // }
        $gV = explode('@',$map[0]['Generation_Vat']);
        $tSV = explode('@',$map[0]['Trans_Sys_Vat']);
        $tDV = explode('@',$map[0]['Trans_Dem_Vat']);
        $sLV = explode('@',$map[0]['Sys_Loss_Vat']);
        $dSV = explode('@',$map[0]['Dist_Sys_Vat']);
        $dDV = explode('@',$map[0]['Dist_Dem_Vat']);
        $lCV = explode('@',$map[0]['Loan_Cond_Vat']);
        $lCFV = explode('@',$map[0]['Loan_Cond_Fix_Vat']);
        $oV = explode('@',$map[0]['Other_Vat']);
        /* -----  new ------------- */
        $parV = explode('@',$map[0]['Power_Act_Red_Vat']);
        $sFV = explode('@',$map[0]['Supply_Fix_Vat']);
        $sSV = explode('@',$map[0]['Supply_Sys_Vat']);
        $mFV = explode('@',$map[0]['Metering_Fix_Vat']);
        $mSV = explode('@',$map[0]['Metering_Sys_Vat']);
        $lDV = explode('@',$map[0]['Lfln_Disc_Vat']);
        /* ------- */

        $html = '<html><style>
        @page {
            size: 21.59cm 27.94cm;
            margin: 0.2in;
        }
        body {
            font-family: Arial, sans-serif; /* Use a common font family */
            font-size: 12px;
        }
        </style><body>';
        // $html .= '<div style= text-align: center;">';
        // $html .= '<img src ="img/lasureco-logo.png" width="100" height="80" style="margin-top:5px;float:left;padding-left:35px;" />';
        // $html .= '<img src ="img/download.png" width="100" height="80" style="margin-top:5px;float:right;padding-right:35px;" />';
        // $html .= '</div>';
        $html .= '<div style="text-align: center;">';
        $html .= '<label style="display: inline; font-size: 18px; font-weight: bold">LANAO DEL SUR ELECTRIC COOPERATIVE, INC.</label><br>';
        $html .= '<label style="font-size: 14px">Brgy. Gadongan, Marawi City, Philippines</label><br>';
        $html .= '<label style="font-size: 12px">teamlasureco@gmail.com</label><br>';
        $html .= '<label style="font-size: 16px;">STATEMENT OF ACCOUNT</label><br>';
        $html .= '</div>';

        $html .= '<div style = "margin-top:0.2in;width:100%">';
        // if(typeof arr[0].Reprint != 'undefined'){
        $html .= '<center><label style="font-size: 12px;">' . 'Online Print' . '</label></center>';
        // }
        // else{
        //     output += '<center><label style="font-size: 12px;"></label></center>';
        // }
        $a = strval($consumerInfo['cm_account_no']);
        $a1 = substr($a, 0, 2);
        $a2 = substr($a, 2, 4);
        $a3 = substr($a, 6, 4);
        if ($consumerInfo['cm_address'] === null) {
            $consumerInfo['cm_address'] = "No Address";
        }
        
        if (strlen($consumerInfo['cm_address']) >= 40) {
            $address = substr($consumerInfo['cm_address'], 0, 40);
        } else {
            $address = $consumerInfo['cm_address'];
        }
        
        if (strlen($consumerInfo['cm_full_name']) >= 40) {
            $name = substr($consumerInfo['cm_full_name'], 0, 40);
        } else {
            $name = $consumerInfo['cm_full_name'];
        }    
        if ($consumerInfo['ct_desc'] == 'COMM WATER SYSTEM') {
            $consumerInfo['ct_desc'] = 'COMM WATER SYS.';
        }
        $html .='<label style="font-weight:bold;font-size:14px;">SEQUENCE:' .$consumerInfo['cm_seq_no']. '</label>';
        $html .= '<table style="border-bottom-style:dashed;border-bottom-color:black;border-bottom-width:1px;border-top-style:dashed;border-top-color:black;border-top-width:1px;width:100%;">';
        $html .= '<tr style="border-top: 1px dashed black;">';
        $html .= '<td style="width:15%;">' . 'Bill Number: ' . '</td>'.
        '<td style="width:23.5%"><label>'.$data->first()->mr_bill_no.'</label></td>' .
        '<td style="width:10%">Name: </td>' .
        '<td><label>' .$name. '</label></td>' .
        '</tr>' .
        '<tr style="border-bottom:0.5px dashed black">' .
        '<td style="width:10%">Account No: </td>' .
        '<td><label>'. $a1 . '-' . $a2 . '-' . $a3. '</label></td>' .
        '<td >Address: </td>' .
        '<td><label>'.$address.'</label></td>' .
        '</tr>';
        $html .= '</table>';
        $html .= '<table style="border-bottom-style:dashed;border-bottom-color:black;border-bottom-width:1px;width:100%;"height:10px;">';
        $html .= '<tr>';
        $html .= '<td style="width:17%">Bill Period : </td>' .
        '<td style="width:23%;"><label>'.$data->first()->mr_date_year_month.'</label></td>' .
        '<td style="width:5%">Rate Type: </td>' .
        '<td><label>'.$consumerInfo['ct_desc'].'</label></td>' .
        '<td style="width:10%;">Demand: </td>' .
        '<td style="text-align:left;"><label>'.number_format((float)$data->first()->mr_pres_dem_reading,2,'.','').'</label></td>' .
        '</tr>';
        $html .= '<tr>';
        $html .= '<td>Meter Number: </td>' .
        '<td><label>'.$consumerInfo['mm_serial_no'].'</label></td>' .
        '<td style="width:1%">Multiplier: </td>' .
        '<td style="width:20%"><label>'.$consumerInfo['cm_kwh_mult'].'</label></td>' .
        '<td style="width:20%;">Demand Kw Used: </td>' .
        '<td style="text-align:left;"><label>'.number_format((float)$data->first()->mr_dem_kwh_used,2,'.','').'</label></td>' .
        '</tr>';
        $html .='<tr>';
        $html .= '<td>Period From : </td>' .
        '<td><label>'.$periodFromConv.'</label></td>' .
        '<td>Pres Reading: </td>' .
        '<td><label>'.$data->first()->mr_pres_reading.'</label></td>' .

        '</tr>';           
        $html .= '<tr>';
        $html .='<td>Period To : </td>' .
        '<td><label>'.$periodToConv.'</label></td>' .
        '<td style="width:17.9%">Prev Reading: </td>' .
        '<td><label>'.$data->first()->mr_prev_reading.'</label></td>' .
        '</tr>';
        $html .='<tr style="border-bottom:1px dashed black">';
        $html .='<td colspan=2 style="width:5%">No. of Days Covered:&nbsp;&nbsp;' .$days. '</td>' .
        // '<td style="width:5%"><label>' + arr[x].No_Days_Covered+ '</label></td>' +
        '<td colspan=2 style="width:18%">Total KWH Used:&nbsp;&nbsp;' .$data->first()->mr_kwh_used. '</td>' .
        '</tr></table>';

        // output += '<div class = "row" >';
        // output += '<div class = "col-sm-6" style="border-right: 1px dashed black;">';
        $html .= '<div style="float:left;width:50%">';
        $html .= '<table style="text-align:left;width:100%;">' .
        '<tr>' .
        '<th>CHARGES</th>' .
        '<th>RATE</th>' .
        '<th style="text-align:right">AMOUNT</th></tr>' . 
        '<tr>' .
        '<td>GENERATION CHARGES</td></tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Gen.Sys.Chrg' . '</td>' .
        '<td>' . $q[0] . '</td>' .
        '<td style="text-align:right;">' . $q[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Power Act Red.' . '</td>' .
        '<td>' . $powerActRed[0] . '</td>' .
        '<td style="text-align:right;">' . $powerActRed[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Fran.&Ben.to Host' . '</td>' .
        '<td>' . $fbtoHost[0] . '</td>' .
        '<td style="text-align:right;">' . $fbtoHost[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'FOREX Adjust. Chrg' . '</td>' .
        '<td>' . $forexAC[0] . '</td>' .
        '<td style="text-align:right;">' . $forexAC[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:15px;">' . 'SUB TOTAL (GC)' . '</td>' .
        '<td>' . ' ' . '</td>' .
        '<td style="text-align:right;">' .number_format((float)$map[0]['SUB_TOTAL_GC'],2,'.',',') . '</td>' .
        '</tr>' .
        '<tr>' .
        '<td>TRANSMISSION CHARGES</td></tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Trans.Sys.Charge' . '</td>' .
        '<td>' . $tSC[0] . '</td>' .
        '<td style="text-align:right;">' . $tSC[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Trans.Dem.Charge' . '</td>' .
        '<td>' . $tDC[0] . '</td>' .
        '<td style="text-align:right;">' . $tDC[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'System Loss Charge' . '</td>' .
        '<td>' . $sLC[0] . '</td>' .
        '<td style="text-align:right;">' . $sLC[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:15px">' . 'SUB TOTAL (TC)' . '</td>' .
        '<td>' . ' ' . '</td>' .
        '<td style="text-align:right;">' .$map[0]['SUB_TOTAL_TC'] . '</td>' .
        '</tr>' . 
        '<tr>' .
        '<td >DISTRIBUTION CHARGES</td></tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Dist.Sys.Charge' . '</td>' .
        '<td>' . $dSC[0] . '</td>' .
        '<td style="text-align:right;">' . $dSC[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Dist.Dem.Charge' . '</td>' .
        '<td>' . $dDC[0] . '</td>' .
        '<td style="text-align:right;">' . $dDC[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Supply Fix Charge' . '</td>' .
        '<td>' . $sFC[0] . '</td>' .
        '<td style="text-align:right;">' . $sFC[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Supply Sys. Charge' . '</td>' .
        '<td>' . $sSC[0] . '</td>' .
        '<td style="text-align:right;">' . $sSC[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Metering Fix Charge' . '</td>' .
        '<td>' . $mFC[0] . '</td>' .
        '<td style="text-align:right;">' . $mFC[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Metering Sys Charge' . '</td>' .
        '<td>' . $mSC[0] . '</td>' .
        '<td style="text-align:right;">' . $mSC[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:15px;">' . 'SUB TOTAL (DC)' . '</td>' .
        '<td>' . ' ' . '</td>' .
        '<td style="text-align:right;">' . number_format((float)$map[0]['Sub_Total_DC'],2,'.',',').'</td>' .
        '</tr>' . 
        '<tr>' .
        '<td>OTHER CHARGES</td></tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Lfln Disc./Subs.' . '</td>';
        if($lDS[1] < 0){
        $html .= '<td>' . '0.0000/kwh' . '</td>';
        }
        else{
        $html .= '<td>' . $lDS[0] . '</td>';  
        }

        $html .='<td style="text-align:right;">' . $lDS[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Sen.Cit.Disc./Subs.' . '</td>' .
        '<td>' . $sCDS[0] . '</td>' .
        '<td style="text-align:right;">' . $sCDS[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Int.Clss.Crss.Subs' . '</td>' .
        '<td>' . $iCCS[0] . '</td>' .
        '<td style="text-align:right;">' . $iCCS[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'MCC CAPEX' . '</td>' .
        '<td>' . $mC[0] . '</td>' .
        '<td style="text-align:right;">' . $mC[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Loan Condonation' . '</td>' .
        '<td>' . $lC[0] . '</td>' .
        '<td style="text-align:right;">' . $lC[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Loan Condon Fix' . '</td>' .
        '<td>' . $lCF[0] . '</td>' .
        '<td style="text-align:right;">' . $lCF[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:15px;">' . 'SUB TOTAL (OC)' . '</td>' .
        '<td>' . ' ' . '</td>' .
        '<td style="text-align:right;">' .number_format((float)$map[0]['SUB_TOTAL_OC'],2,'.',',').'</td>' .
        '</tr>';
        $html .= '<tr>' . 
        '<td>[LAST PAYMENT]</td>'. 
        '</tr>';  
        $lp = explode('@',$lastPayment);

        if(!isset($lp[1]) && !isset($lp[2])){
            $lp[1] = ' ';
            $lp[2] = ' ';
        }
        else{
            $lp[1] = $lp[1];
            $lp[2] = ' ';
            $lp[1] = floatval($lp[1]);
        }
        
        $html .='<tr>' .
        '<td>'.$lp[0].'</td>' . 
        '<td>'.number_format((float)$lp[1],2,'.',',').'</td>' . 
        '<td style="text-align:right;">'.$lp[2].'</td></tr>';
        $html .='</table><br>';
        $html .='<div style="text-align:center;border:1px solid #5B9BD5">';
        $html .='<p style="display:inline;font-size:12px">Please pay your Power Bill in any Official LASURECO</p><br>' .
        '<p style="display:inline;font-size:12px">Paying Centers, Authorized MOBILE Tellering Outlet</p><br>' .  
        '<p style="display:inline;font-size:12px">with Official Receipt presented or</p><br>'.
        '<p style="display:inline;font-size:12px">through Online Payment via GCASH and PayMaya.</p><br>'.
        '<p style="display:inline;font-size:12px">Visit www.lasureco.com/online-payment</p>'.
        '</div>';
        $html .='</div>';
        $html .='<div style="border-left:1px dashed black;float:right;width:49%">';
        $html .='<table style="text-align:left;width:100%;">' .
        '<tr>' .
        '<th> CHARGES </th>' .
        '<th> RATE </th>' .
        '<th style="text-align:right;"> AMOUNT </th>' . 
        '<tr>' .
        '<td>UNIVERSAL CHARGES</td></tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Miss.Elect.(SPUG)' . '</td>' .
        '<td>' . $meSPUG[0] . '</td>' .
        '<td style="text-align:right;">' . $meSPUG[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Miss.Elect.(RED)' . '</td>' .
        '<td>' . $meRED[0] . '</td>' .
        '<td style="text-align:right;">' . $meRED[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Envi. Chrg' . '</td>' .
        '<td>' . $eChrg[0] . '</td>' .
        '<td style="text-align:right;">' . $eChrg[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Equali. of Taxes & Royalty' . '</td>' .
        '<td>' . $eqTR[0] . '</td>' .
        '<td style="text-align:right;">' . $eqTR[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'NPC Str Cons Cost' . '</td>' .
        '<td>' . $npcSCC[0] . '</td>' .
        '<td style="text-align:right;">' . $npcSCC[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'NPC Stranded Debt' . '</td>' .
        '<td>' . $npcST[0] . '</td>' .
        '<td style="text-align:right;">' . $npcST[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Fit All(Renew)' . '</td>' .
        '<td>' . $fitallnew[0] . '</td>' .
        '<td style="text-align:right;">' . $fitallnew[1] . '</td>' .
        '</tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:15px;">' . 'SUB TOTAL (UC)' . '</td>' .
        '<td>' . ' ' . '</td>' .
        '<td style="text-align:right;">' .number_format((float)$map[0]['SUB_TOTAL_UC'],2,'.',',').'</td>' .
        '</tr>' .                    
        '<tr>' .
        '<td>VALUE ADDED TAX</td></tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Generation VAT' . '</td>' .
        '<td>' . $gV[0] . '</td>' .
        '<td style="text-align:right;">' . $gV[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Power Act Red VAT' . '</td>' .
        '<td>' .$parV[0] . '</td>' .
        '<td style="text-align:right;">' . $parV[1] . '</td>' .
        '</tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Trans. Sys. VAT' . '</td>' .
        '<td>' . $tSV[0] . '</td>' .
        '<td style="text-align:right;">' . $tSV[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Trans. Dem. VAT' . '</td>' .
        '<td>' . $tDV[0] . '</td>' .
        '<td style="text-align:right;">' . $tDV[1] . '</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'System Loss VAT' . '</td>' .
        '<td>' . $sLV[0] . '</td>' .
        '<td style="text-align:right;">' . $sLV[1] . '</td>' .
        '</tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Dist. Sys. VAT' . '</td>' .
        '<td>' . $dSV[0] . '</td>' .
        '<td style="text-align:right;">' . $dSV[1] . '</td>' .
        '</tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Dist. Dem. VAT' . '</td>' .
        '<td>' . $dDV[0] . '</td>' .
        '<td style="text-align:right;">' . $dDV[1] . '</td>' .
        '</tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Supply Fix VAT' . '</td>' .
        '<td>' . $sFV[0] . '</td>' .
        '<td style="text-align:right;">' . $sFV[1] . '</td>' .
        '</tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Supply Sys VAT' . '</td>' .
        '<td>' . $sSV[0] . '</td>' .
        '<td style="text-align:right;">' . $sSV[1] . '</td>' .
        '</tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Metering Fix VAT' . '</td>' .
        '<td>' . $mFV[0] . '</td>' .
        '<td style="text-align:right;">' . $mFV[1] . '</td>' .
        '</tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Metering Sys VAT' . '</td>' .
        '<td>' . $mSV[0] . '</td>' .
        '<td style="text-align:right;">' . $mSV[1] . '</td>' .
        '</tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;font-size:12px">' . 'Lfln Disc./Subs. VAT' . '</td>' .
        '<td>' . $lDV[0] . '</td>' .
        '<td style="text-align:right;">' . $lDV[1] . '</td>' .
        '</tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Loan Condo. VAT' . '</td>' .
        '<td>' . $lCV[0] . '</td>' .
        '<td style="text-align:right;">' . $lCV[1] . '</td>' .
        '</tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Loan Cond. Fix VAT' . '</td>' .
        '<td>' . $lCFV[0] . '</td>' .
        '<td style="text-align:right;">' . $lCFV[1] . '</td>' .
        '</tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Other VAT' . '</td>' .
        '<td>' . $oV[0] . '</td>' .
        '<td style="text-align:right;">' . $oV[1] . '</td>' .
        '</tr>' .
        '<tr style="text-align:left;">' .
        '<td style="border-bottom: 1px dashed black;padding-left:15px;">' . 'SUB TOTAL (VAT)' . '</td>' .
        '<td style="border-bottom: 1px dashed black">' . ' ' . '</td>' .
        '<td style="border-bottom: 1px dashed black;text-align:right" >'.number_format((float)$map[0]['SUB_TOTAL_VAT'],2,'.',',').'</td>' .
        '</tr>' . 
        '<tr style="border-bottom: 1px dashed black">' .
        '<td>CURRENT BILL</td>' .
        '<td>Php</td>' .
        '<td style="text-align:right;">' .number_format((float)$data->first()->mr_amount,2,'.',',').'</td>' .
        '</tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Total Arrears' . '</td>' .
        '<td>' . ' ' . '</td>' .
        '<td style="text-align:right;">'. number_format((float)$totalArrear,2,'.',',') .'</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Material Cost/Integ' . '</td>' .
        '<td>' . ' ' . '</td>' .
        '<td style="text-align:right;">0.00</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'Transformer Rental' . '</td>' .
        '<td>' . ' ' . '</td>' .
        '<td style="text-align:right;">0.00</td>' .
        '</tr>' . 
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'TOTAL AMOUNT DUE' . '</td>' .
        '<td>' . ' ' . '</td>' .
        '<td style="text-align:right;">'.number_format((float)$tad,2,'.',',').'</td>' .
        '</tr>';
        $lgu5 = ($consumerInfo['cm_lgu5'] == 1) ? round(($data->first()->mr_amount/1.12) * 0.05,2) : 0;
        $lgu2 = ($consumerInfo['cm_lgu2'] == 1) ? round(($data->first()->mr_amount/1.12) * 0.02,2) : 0;
        if($consumerInfo['ct_desc'] == 'PUBLIC BUILDING' || $consumerInfo['ct_desc'] == 'COMMERCIAL' ){
        $html .='<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'LGU 5%' . '</td>' .
        '<td>' . number_format((float)$lgu5,2,'.',',') . '</td>' .
        '<td style="text-align:right;">dasdsa</td>' .
        '</tr>' .
        '<tr style="text-align:left;">' .
        '<td style="padding-left:10px;">' . 'LGU 2%' . '</td>' .
        '<td>' . number_format((float)$lgu2,2,'.',',') . '</td>' .
        '<td style="text-align:right;">dsadsa</td>' .
        '</tr>';
        }
        $html .='</table><br>';
        $html .='<table style="font-size:12px;" width="100%">';
        $html .='<tr style="text-align:left;">' .
        '<td> E - Wallet </td>' .
        '<td style="text-align:right;">'.number_format((float)$ewallet['ew_total_amount'],2,'.',',').'</td>' .
        '</tr>';
        // var ddate = data.mr_due_date.split(' ');
        $html .='<tr><td width="27%">DUE DATE: </td><td>'.$dueDate1.'</td></tr></table><br>';
        $html .='</div>';

        $html .='</div>'; 




        $html .= '</body></html>';

        $pdf = Pdf::loadHTML($html);

        return $pdf->stream('document.pdf');
    }
}
