<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBillRatesRequest;
use Illuminate\Http\Request;
use App\Http\Resources\BillRatesResource;
use App\Models\BillRates;
use App\Services\AuditTrailService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class BillRatesController extends Controller
{
    
    public function index()
    {
        return BillRatesResource::collection(
            DB::table('billing_rates')
            ->whereNull('deleted_at')
            ->paginate(10));
    }

    public function getRate($consType, $billPeriod)
    {
        if(is_numeric($consType)){
            return DB::table('billing_rates AS br')
            ->where('br.cons_type_id', $consType)
            ->where('br.br_billing_ym', $billPeriod)
            ->get();
        }else{
            return DB::table('billing_rates AS br')
            ->join('cons_type AS ct','ct.ct_id','=','br.cons_type_id')
            ->where('ct.ct_desc', $consType)
            ->where('br.br_billing_ym', $billPeriod)
            ->get();
        }
        
    }

    public function checkRates($billPeriod)
    {
        $rate = DB::table('billing_rates AS br')
        ->where('br.br_billing_ym', $billPeriod)
        ->first();

        $msg = 1;

        if($rate == null){
            $msg = 'Billing Period Rates is not available!';
        }
        
        return response(['msg'=>$msg],200);
    }

    public function store(StoreBillRatesRequest $request)
    {


        $data = BillRates::create($request->all());

        //For Audit Trail
        // $at_old_value = '';
        // $at_new_value = '';
        // $at_action = 'Add Bill Rates';
        // $at_table = 'Bill_Rates';
        // $at_auditable = '';
        // $user_id = $request->user_id;
        // $id = null;
        // $data = (new AuditTrailService())->auditTrail($at_old_value,$at_new_value,$at_action,$at_table,$at_auditable,$user_id,$id);
        
        return response($data,201);
    }
    public function update(StoreBillRatesRequest $request, $id)
    {

        // $checkRates = collect(DB::table('meter_reg')
        //     ->where('br_id',$id)
        //     ->get());
        // if($checkRates->isNotEmpty()){
        //     return response(['Message'=>'Bill rate is already used, Cannot Update!'],422);
        // }

        $data = BillRates::findOrFail($id);
        $data->update($request->all());
        //For Audit Trail
        $at_old_value = '';
        $at_new_value = '';
        $at_action = 'Update';
        $at_table = 'Bill_Rates';
        $at_auditable = '';
        $user_id = $request->user_id;
        $id = null;
        $data = (new AuditTrailService())->auditTrail($at_old_value,$at_new_value,$at_action,$at_table,$at_auditable,$user_id,$id);
        return response(['message'=>'Succesfully Updated'],200);
    }
    public function destroy($id)
    {
        $billRates = BillRates::findOrFail($id);
        $db = DB::table('meter_reg')
            ->select('br_id')
            ->where('br_id',$id)
            ->first();
        if(!$db)
        {
            $billRates->delete();
            return response(['Message' => 'Succesfully Deleted'],202);
        }
        
        return response(['Message' => 'With Children'],409);
    }
  
    public function printPowerBill(Request $request)
    {
        $billPeriod = str_replace("-","",$request->Date);
        $routeID = $request->Route_Id;

        $meterReg = collect(DB::table('meter_reg AS mr')
            ->join('cons_master AS cm','cm.cm_id','=','mr.cm_id')
            ->select('cm.cm_id','mr.mr_due_date')
            ->where('mr.mr_date_year_month',$billPeriod)
            ->where('cm.rc_id',$request->Route_Id)
            // ->where('mr.mr_printed',0)
            ->first());
        $check = $meterReg->first();
        if(!$check)
        {
            return response(['Message' => 'No New Power Bill for this month'], 422);
        }

        $currentDateTime = Carbon::now();
        $addDueDate = $currentDateTime->addDays($request->Due_Date);
        $dueDate = $addDueDate->toDateTimeString();
        $disconDate = $addDueDate->addDays($request->Disc_Date)->toDateTimeString();

        $printDetails = collect(DB::table('meter_reg AS mr')
            ->join('cons_master AS cm','cm.cm_id','=','mr.cm_id')
            ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id')
            ->join('billing_rates AS br','mr.br_id','=','br.id')
            ->leftJoin('meter_master AS mm','cm.mm_id','=','mm.mm_id')
            ->where('mr.mr_date_year_month',$billPeriod)
            ->whereNotNull('mr.mr_date_reg')
            ->where('cm.rc_id',$routeID)
            ->where('mr.mr_printed',0)
            ->orderBy('cm.cm_seq_no','asc')
            ->get());
        
        $check2 = $printDetails->first();
        if(!$check2)
        {
            return response(['Message' => 'All Power Bill Printed'], 422);
        }

        // Update Power Bill To printed
        $consUpdateDueNDiscs = DB::table('meter_reg AS mr')
            ->join('cons_master AS cm','cm.cm_id','=','mr.cm_id')
            ->where('cm.rc_id',$request->Route_Id)
            ->where('mr.mr_printed',0)
            ->where('mr.mr_date_year_month',$billPeriod)
            ->update([
                'mr_due_date' => $dueDate,
                'mr_discon_date' => $disconDate,
                'mr_printed' => 1,
            ]);

        $mapped = $printDetails->map(function($item) use($routeID, $dueDate)
        {
            
            $yearMonthConv = date('F, Y', strtotime($item->mr_date_year_month.'01'));
            $dueDate1 = date('F d, Y', strtotime($dueDate));
            //Query for prev Month $periodFrom
            $periodFrom = collect(DB::table('meter_reg as mr')
                ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->where('cm.cm_id',$item->cm_id)
                ->where('mr.mr_date_year_month','<',$item->mr_date_year_month)
                ->where('cm.rc_id',$routeID)
                ->where('rc.rc_id',$routeID)
                ->whereNotNull('mr.mr_date_reg')
                ->orderBy('mr.mr_date_year_month','desc')
                ->first());
                
            if(!isset($periodFrom['mr_date_reg']))
            {
                $periodFrom = $item->mr_date_reg;
                // $item->mr_date_reg = 0;
            }else{
                $periodFrom = $periodFrom['mr_date_reg'];
            }
            $periodFromConv = date('m/d/Y', strtotime($periodFrom));
            $periodToConv = date('m/d/Y', strtotime($item->mr_date_reg));
            
            $datetime1 = new DateTime($periodFromConv);
            $datetime2 = new DateTime($periodToConv);
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');
            
            //Query Total Arrears
            $totalArrear = collect(DB::table('meter_reg')
                ->where('cm_id',$item->cm_id)
                ->where('mr_date_year_month','<',$item->mr_date_year_month)
                ->where('mr_status',0)
                ->sum('mr_amount'))
                ->first();
            //Query Consumer E_Wallet
            $ewallet = collect(DB::table('e_wallet')
                ->where('cm_id',$item->cm_id)
                ->select('ew_total_amount')
                ->first());
            //Query Last Payment
            $lastPayment = collect(DB::table('sales as s')
                ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
                ->select(DB::raw('COALESCE(sum(s.s_or_amount),0) as bill_amount,COALESCE(sum(s.e_wallet_added),0) as ewAdd,s.s_or_num,s.cm_id,s.s_bill_date'))
                // ->whereRaw('a.s_bill_date = (SELECT MAX(b.s_bill_date) FROM sales b WHERE a.cm_id = b.cm_id)')
                ->where('s.cm_id',$item->cm_id)
                ->where('mr.mr_status',1)
                ->orderByDesc('s.s_bill_date')
                ->groupBy('s.s_bill_date')
                ->first());
            $checkLastPayment = $lastPayment->first();
            if(!$checkLastPayment)
            {
                $lastPayment = 'No Payment Received';
            }else{
                // $lastPayment = collect([date('m/d/Y', strtotime($lastPayment['s_bill_date']).' '.$lastPayment['s_or_num'].' '.$lastPayment['s_bill_amount'])]);

                $lastPayment = date('m/d/Y', strtotime($lastPayment['s_bill_date'])).'@'.round($lastPayment['bill_amount'] + $lastPayment['ewAdd'],2);
            }
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
            $lflnDiscSubs = ($item->mr_lfln_disc != 0) ? $item->mr_lfln_disc * -1 : round(round($item->br_lfln_subs_rate * $item->mr_kwh_used,3),2);
            // $lflnDiscSubs = round(round($item->br_lfln_subs_rate * $item->mr_kwh_used,3),2);
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
                /* --------------------------------------------------- CONSUMER DETAILS -----------------------------------------------*/
                /* --------------------------------------------------- CONSUMER DETAILS -----------------------------------------------*/
                'BILL_NO'=>$item->mr_bill_no,
                'LAST_PAYMENT'=>$lastPayment,
                'Name'=>$item->cm_full_name,
                'Account_No'=>$item->cm_account_no,
                'Seq'=>$item->cm_seq_no,
                'Address'=>$item->cm_address,
                'Bill_Period'=>$yearMonthConv,
                'Meter_Number'=>$item->mm_serial_no,
                'Period_From'=>$periodFromConv,
                'Period_To'=>$periodToConv,
                'No_Days_Covered'=>$days,
                'Rate_Type'=>$item->ct_desc,
                'Multiplier'=>number_format($item->cm_kwh_mult, 2, '.', ''),
                'Pres_Reading'=>$item->mr_pres_reading,
                'Prev_Reading'=>$item->mr_prev_reading,
                'Total_KWH_Used'=>$item->mr_kwh_used,
                'Demand_Pres_Reading'=>$item->mr_pres_dem_reading,
                'Demand_Kwh_used'=>$item->mr_dem_kwh_used,
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
                'Lfln_Disc_Subs'=>($item->mr_lfln_disc != 0) ? number_format(round($item->br_lfln_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($item->mr_lfln_disc,2,'.','') * -1 : number_format(round($item->br_lfln_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($lflnDiscSubs,2,'.',''),
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
                /* ------------------------------------------------------- TOTALS DETAILS -----------------------------------------------*/
                'CURRENT_MONTH'=>$item->mr_amount,
                'Total_Arrears'=>round($totalArrear,2),
                'Material_Cost_Integ'=>0,
                'Transformer_Rental'=>0,
                'E_Wallet'=>$ewallet['ew_total_amount'],
                'Total_Amount_Due'=>round($item->mr_amount + $totalArrear,2),
                'LGU_2'=>($item->cm_lgu2 == 1) ? round(($item->mr_amount/1.12) * 0.02,2) : 0, //add column going to store
                'LGU_5'=>($item->cm_lgu5 == 1) ? round(($item->mr_amount/1.12) * 0.05,2) : 0, //add column going to store
                'Total_7%_Discount'=>0, //add column going to store
                'Due_Date'=>$dueDate1
                ];
        });
        
        return response([
            'PB_DETAILS'=>$mapped,
            'Count'=>count($mapped)
        ], 200);
    }
    public function getCurBRConsType()
    {
        $latestBP = BillRates::select('br_billing_ym')
            ->orderBy('br_billing_ym','desc')
            ->first();

        $conDate = date("F Y", strtotime(substr($latestBP->br_billing_ym,0,4).'-'.substr($latestBP->br_billing_ym,4,2)));

        $br = DB::table('cons_type AS ct')
            ->join('billing_rates AS br','br.cons_type_id','=','ct.ct_id')
            ->select('br.id','br.br_billing_ym','ct.ct_desc')
            ->whereNull('ct.deleted_at')
            ->where('br.br_billing_ym',$latestBP->br_billing_ym)
            ->get();

        return response(['Billing_Period'=>$conDate,'data'=>$br]);
    }
    public function getBRByBillPeriod($billingPeriod)
    {
        $checkBRBP = DB::table('billing_rates')
            ->select('br_billing_ym')
            ->where('br_billing_ym',str_replace("-","",$billingPeriod))
            ->first();
        // dd($checkBRBP);
        if(is_null($checkBRBP))
        {
            $consType = DB::table('cons_type')
                ->select('ct_id','ct_desc','ct_code')
                ->orderBy('ct_id','asc')
                ->get();

            return response(['Data'=>$consType]);
        }

        $date = $checkBRBP->br_billing_ym;

        $getBRBP = DB::table('cons_type AS ct')
        ->leftJoin('billing_rates AS br',function($join) use ($date)
        {
            $join->on('br.cons_type_id','=', 'ct.ct_id')
            ->when($date, function ($query, $date) {
                return $query->where('br.br_billing_ym', $date);
            });
        })
        ->select('br.id','br.br_billing_ym','ct.ct_id','ct.ct_desc','ct.ct_code')
        ->whereNull('ct.deleted_at')
        ->orderBy('ct.ct_id','asc')
        ->get();

        return response(['Data'=>$getBRBP]);
    }
    public function previewConsBR($id)
    {
        $preview = DB::table('billing_rates')
            ->where('id',$id)
            ->first();

        return response(['Bill_rates'=>$preview]);
    }
    public function reprintPowerBill(Request $request)
    {
        $billPeriod = str_replace("-","",$request->Date);
        if(!isset($request->selected)){
            $request->selected = 'by_sequence';
        }
        if($request->selected == 'by_sequence')
        {
            if(isset($request->seq_from)){
                $printDetails = collect(DB::table('meter_reg AS mr')
                ->join('cons_master AS cm','cm.cm_id','=','mr.cm_id')
                // ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id')
                ->join('billing_rates AS br','mr.br_id','=','br.id')
                ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
                ->leftJoin('meter_master AS mm','cm.mm_id','=','mm.mm_id')
                ->where('mr.mr_date_year_month',$billPeriod)
                ->whereNotNull('mr.mr_date_reg')
                ->where('cm.rc_id',$request->Route_Id)
                ->whereBetween('cm.cm_seq_no',[$request->seq_from,$request->seq_to])
                ->whereIn('mr.mr_printed',[1,10])
                ->orderBy('cm.cm_seq_no','asc')
                ->get());
            }else{
                $printDetails = collect(DB::table('meter_reg AS mr')
                ->join('cons_master AS cm','cm.cm_id','=','mr.cm_id')
                // ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id')
                ->join('billing_rates AS br','mr.br_id','=','br.id')
                ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
                ->leftJoin('meter_master AS mm','cm.mm_id','=','mm.mm_id')
                ->where('mr.mr_date_year_month',$billPeriod)
                ->whereNotNull('mr.mr_date_reg')
                ->where('cm.rc_id',$request->Route_Id)
                ->whereNull('cm.cm_seq_no')
                ->whereIn('mr.mr_printed',[1,10])
                ->get());
            }
        }else if($request->selected == 'by_account'){
            $printDetails = collect(DB::table('meter_reg AS mr')
            ->join('cons_master AS cm','cm.cm_id','=','mr.cm_id')
            // ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id')
            
            ->join('billing_rates AS br','mr.br_id','=','br.id')
            ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
            ->leftJoin('meter_master AS mm','cm.mm_id','=','mm.mm_id')
            ->where('mr.mr_date_year_month',$billPeriod)
            ->whereNotNull('mr.mr_date_reg')
            ->where('cm.rc_id',$request->Route_Id)
            ->where('cm.cm_account_no',$request->account)
            ->whereIn('mr.mr_printed',[1,10])
            ->orderBy('cm.cm_seq_no','asc')
            ->get());
        }
        $check = $printDetails->first();
        if(!$check)
        {
            return response(['Message' => 'No Records Found'], 422);
        }
        $routeID = $request->Route_Id;
        $mapped = $printDetails->map(function($item) use($routeID)
        {
            
            $yearMonthConv = date('F, Y', strtotime($item->mr_date_year_month.'01'));
            $dueDate1 = date('F d, Y', strtotime($item->mr_due_date));
            //Query for prev Month $periodFrom
            $periodFrom = collect(DB::table('meter_reg as mr')
                ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->where('cm.cm_id',$item->cm_id)
                ->where('mr.mr_date_year_month','<',$item->mr_date_year_month)
                ->where('cm.rc_id',$routeID)
                ->where('rc.rc_id',$routeID)
                ->whereNotNull('mr.mr_date_reg')
                ->orderBy('mr.mr_date_year_month','desc')
                ->first());
                
            if(!isset($periodFrom['mr_date_reg']))
            {
                $periodFrom = $item->mr_date_reg;
                // $item->mr_date_reg = 0;
            }else{
                $periodFrom = $periodFrom['mr_date_reg'];
            }
            $periodFromConv = date('m/d/Y', strtotime($periodFrom));
            $periodToConv = date('m/d/Y', strtotime($item->mr_date_reg));
            
            $datetime1 = new DateTime($periodFromConv);
            $datetime2 = new DateTime($periodToConv);
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');
            
            //Query Total Arrears
            $totalArrear = collect(DB::table('meter_reg')
                ->where('cm_id',$item->cm_id)
                ->where('mr_date_year_month','<',$item->mr_date_year_month)
                ->where('mr_status',0)
                ->sum('mr_amount'))
                ->first();
            //Query Consumer E_Wallet
            $ewallet = collect(DB::table('e_wallet')
                ->where('cm_id',$item->cm_id)
                ->select('ew_total_amount')
                ->first());
            //Query Last Payment
            // $lastPayment = collect(DB::table('sales as a')
            //     ->select(DB::raw('a.s_or_num,a.cm_id,a.s_bill_date,a.s_bill_amount as bill_amount'))
            //     ->join('meter_reg as mr','a.cm_id','=','mr.cm_id')
            //     ->whereRaw('a.s_bill_date = (SELECT MAX(b.s_bill_date) FROM sales b WHERE a.cm_id = b.cm_id)')
            //     ->where('a.cm_id',$item->cm_id)
            //     ->where('mr.mr_status',1)
            //     ->orderByDesc('a.s_or_num')
            //     ->first());
            //Query Last Payment
            $lastPayment = collect(DB::table('sales as s')
                ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
                ->select(DB::raw('COALESCE(sum(s.s_or_amount),0) as bill_amount,COALESCE(sum(s.e_wallet_added),0) as ewAdd,s.s_or_num,s.cm_id,s.s_bill_date'))
                // ->whereRaw('a.s_bill_date = (SELECT MAX(b.s_bill_date) FROM sales b WHERE a.cm_id = b.cm_id)')
                ->where('s.cm_id',$item->cm_id)
                ->where('mr.mr_status',1)
                ->orderByDesc('s.s_bill_date')
                ->groupBy('s.s_bill_date')
                ->first());
            $checkLastPayment = $lastPayment->first();
            if(!$checkLastPayment)
            {
                $lastPayment = 'No Payment Received';
            }else{
                // $lastPayment = collect([date('m/d/Y', strtotime($lastPayment['s_bill_date']).' '.$lastPayment['s_or_num'].' '.$lastPayment['s_bill_amount'])]);

                $lastPayment = date('m/d/Y', strtotime($lastPayment['s_bill_date'])).'@'.round($lastPayment['bill_amount'] + $lastPayment['ewAdd'],2);
            }
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
            $lflnDiscSubs = ($item->mr_lfln_disc != 0) ? $item->mr_lfln_disc * -1 : round(round($item->br_lfln_subs_rate * $item->mr_kwh_used,3),2);
            // $lflnDiscSubs = round(round($item->br_lfln_subs_rate * $item->mr_kwh_used,3),2);
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
                /* --------------------------------------------------- CONSUMER DETAILS -----------------------------------------------*/
                /* --------------------------------------------------- CONSUMER DETAILS -----------------------------------------------*/
                'BILL_NO'=>$item->mr_bill_no,
                'LAST_PAYMENT'=>$lastPayment,
                'Name'=>$item->cm_full_name,
                'Account_No'=>$item->cm_account_no,
                'Seq'=>$item->cm_seq_no,
                'Reprint'=>'Re-print',
                'Address'=>$item->cm_address,
                'Bill_Period'=>$yearMonthConv,
                'Meter_Number'=>$item->mm_serial_no,
                'Period_From'=>$periodFromConv,
                'Period_To'=>$periodToConv,
                'No_Days_Covered'=>$days,
                'Rate_Type'=>$item->ct_desc,
                'Multiplier'=>number_format($item->cm_kwh_mult, 2, '.', ''),
                'Pres_Reading'=>$item->mr_pres_reading,
                'Prev_Reading'=>$item->mr_prev_reading,
                'Total_KWH_Used'=>$item->mr_kwh_used,
                'Demand_Pres_Reading'=>$item->mr_pres_dem_reading,
                'Demand_Kwh_used'=>$item->mr_dem_kwh_used,
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
                'Lfln_Disc_Subs'=>($item->mr_lfln_disc != 0) ? number_format(round($item->br_lfln_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($item->mr_lfln_disc,2,'.','') * -1 : number_format(round($item->br_lfln_subs_rate,4),4,'.','').'/kwh'.'@'.number_format($lflnDiscSubs,2,'.',''),
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
                /* ------------------------------------------------------- TOTALS DETAILS -----------------------------------------------*/
                'CURRENT_MONTH'=>$item->mr_amount,
                'Total_Arrears'=>round($totalArrear,2),
                'Material_Cost_Integ'=>0,
                'Transformer_Rental'=>0,
                'E_Wallet'=>$ewallet['ew_total_amount'],
                'Total_Amount_Due'=>round($item->mr_amount + $totalArrear,2),
                'LGU_2'=>($item->cm_lgu2 == 1) ? round(($item->mr_amount/1.12) * 0.02,2) : 0, //add column going to store
                'LGU_5'=>($item->cm_lgu5 == 1) ? round(($item->mr_amount/1.12) * 0.05,2) : 0, //add column going to store
                'Total_7%_Discount'=>0, //add column going to store
                'Due_Date'=>$dueDate1
                ];
        });
        
        return response([
            'PB_DETAILS'=>$mapped,
            'Count'=>count($mapped)
        ], 200);
    }
    public function getBillRatesBP($bp)
    {
        $billPeriod = str_replace("-","",$bp);
        $query = DB::table('billing_rates as br')
            ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
            ->where('br.br_billing_ym',$billPeriod)
            ->get();
        if(!$query->first())
        {
            return response(['Message'=>'No Record Found'],422);
        }
        return response(['Result'=>$query],200);
    }
    public function getDifferences(Request $request){
        $printDetails = collect(DB::table('meter_reg AS mr')
            ->join('cons_master AS cm','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates AS br','mr.br_id','=','br.id')
            ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->where('mr.mr_date_year_month',$request->bill_period)
            ->where('mr.mr_printed',1)
            ->where('tc.ac_id',$request->ac_id)
            ->orderBy('cm.cm_seq_no','asc')
            ->get()
        );
        
        $mapped = $printDetails->map(function($item){
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
            $lflnDiscSubs = ($item->mr_lfln_disc != 0) ? $item->mr_lfln_disc * -1 : round(round($item->br_lfln_subs_rate * $item->mr_kwh_used,3),2);
            // $lflnDiscSubs = round(round($item->br_lfln_subs_rate * $item->mr_kwh_used,3),2);
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
            
            $newSubtotal = round(round($subTotalGc,2)
            + round($subTotalTc,2)
            + round($subTotalDc,2)
            + round($subTotalOc,2)
            + round($subTotalUc,2)
            + round($subTotalVat,2),2);
            if(round($item->mr_amount,2) == $newSubtotal){
                $newTotal = 0;
            }else{
                $newTotal = $newSubtotal;
            }
            // if(round($item->mr_amount,2) != $newSubtotal && $item->br_id == 1098){
            //     DB::table('meter_reg')->where('mr_id',$item->mr_id)->where('br_id',1098)->update(['mr_amount'=>$newTotal]);
            // }
            
            return [
                'route'=>$item->rc_code,
                'account'=>$item->cm_account_no,
                'rates_id'=>$item->br_id,
                'mr_id'=> $item->mr_id,
                'orig_amount'=> $item->mr_amount,
                'fixed_amount'=> $newTotal,
            ];
        });

        $getfixed = $mapped->where('fixed_amount','!=',0);

        return $getfixed;
    }
}
