<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ConsumerType;
use App\Models\EWALLET;
use App\Models\EWALLET_LOG;
use App\Services\GetArrearsTotalRouteService;
use App\Services\GetRateService;
use App\Services\LifelineAllAreasService;
use App\Services\LifelineService;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

use function PHPUnit\Framework\isNull;

class BillingReportController extends Controller
{
    public function generalDetailReport(Request $request)
    {
        $date = str_replace("-","",$request->date);
        if($request->selected == 'billed_on_time'){
            $genReports = collect(DB::table('cons_master AS cm')
            ->join('route_code AS rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
            // ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id')
            ->join('meter_reg AS mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
            // ->leftJoin('sales AS s','mr.mr_id','=','s.mr_id')
            ->select('cm.cm_id','ct.ct_id','ct.ct_desc','mr.mr_kwh_used','mr.mr_amount','rc.rc_code','rc.rc_desc')
            ->where('tc.tc_id',$request->town_id)
            ->where('mr.mr_printed',1)
            ->where('mr.mr_date_year_month',$date)
            ->orderBy('rc.rc_desc','asc')
            ->get());

            // $genReports1 = collect(DB::table('cons_master AS cm')
            // ->join('route_code AS rc','cm.rc_id','=','rc.rc_id')
            // ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
            // ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
            // ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id')
            // ->join('meter_reg AS mr','cm.cm_id','=','mr.cm_id')
            // // ->leftJoin('sales AS s','mr.mr_id','=','s.mr_id')
            // // ->select('cm.cm_id','ct.ct_id','ct.ct_desc','mr.mr_kwh_used','mr.mr_amount','rc.rc_code','rc.rc_desc')
            // ->select(DB::raw('COUNT(cm.cm_id),sum(mr.mr_kwh_used),sum(mr.mr_amount),ct.ct_id'))
            // ->where('tc.tc_id',$request->town_id)
            // ->where('mr.mr_printed',1)
            // ->where('mr.mr_date_year_month',$date)
            // ->orderBy('rc.rc_desc','asc')
            // ->groupBy('ct.ct_id')
            // ->get());

        }else if($request->selected == 'include_late_billing'){
            $genReports = collect(DB::table('cons_master AS cm')
            ->join('route_code AS rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
            ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id')
            ->join('meter_reg AS mr','cm.cm_id','=','mr.cm_id')
            // ->leftJoin('sales AS s','mr.mr_id','=','s.mr_id')
            ->select('cm.cm_id','ct.ct_id','ct.ct_desc','mr.mr_kwh_used','mr.mr_amount','rc.rc_code','rc.rc_desc')
            ->where('tc.tc_id',$request->town_id)
            ->where('mr.mr_printed',1)
            ->where('mr.mr_date_year_month','<=',$date)
            ->orderBy('rc.rc_desc','asc')
            ->get());
        }else{
            return response(['Message'=>'Invalid Selection'],422);
        }
        
        $check = $genReports->first();
        if(!$check)
        {
            return response(['Message'=>'No Records Found'],422);
        }
        $groups = $genReports->groupBy('rc_code');  
        $genRepDetails = $groups->map(function ($group){
            return [
                'Route' => $group->first()->rc_code,
                'Route_desc' => $group->first()->rc_desc,
                'No_of_Cons' => $group->count('cm_id'),
                'Total_KWH_USED' => $group->sum('mr_kwh_used'),
                'Bill_Amount' => round($group->sum('mr_amount'),2)
            ];
        });
        $totalConsID = count($genReports->pluck('cm_id'));
        $totalSumKWHUsed = $genReports->sum('mr_kwh_used');
        $totalBillAmount = $genReports->sum('mr_amount');
        $ctGroups = collect();
        $allCT = DB::table('cons_type')
        ->select('ct_id','ct_desc')
        ->get();

        for($i=1;$i <= count($allCT);$i++)
        {
            $ctGroups->push($genReports->where('ct_id', $i)->groupBy('rc_code'));
        }
        
        for($a=0; $a < count($allCT);$a++)
        {
            
            $array[$a] = $ctGroups[$a]->map(function ($ctGroup){
                return [$ctGroup->first()->ct_desc=>[
                    'Route' => $ctGroup->first()->rc_code,
                    'Route_desc' => $ctGroup->first()->rc_desc,
                    'Ct_id' => $ctGroup->first()->ct_id,
                    'No_of_Cons' => $ctGroup->count('cm_id'),
                    'Total_KWH_USED' => $ctGroup->sum('mr_kwh_used'),
                    'Bill_Amount' => round($ctGroup->sum('mr_amount'),2)
                ]];
            });

            // $consType_Total_Cons[$a]= $array[$a]->#_of_Cons;
        }
        $groupCTID = $genReports->groupBy('ct_id');
        $conRepTotal = $groupCTID->map(function ($groupCTID){
            return [
                'Ct_id' => $groupCTID->first()->ct_id,
                'No_of_Cons' => $groupCTID->count('cm_id'),
                'Total_KWH_USED' => $groupCTID->sum('mr_kwh_used'),
                'Bill_Amount' => round($groupCTID->sum('mr_amount'),2)
            ];
        })->sortBy('Ct_id');
        
        $allConsType = collect(DB::table('cons_type')
            ->select('ct_desc')
            ->orderBy('ct_id','asc')
            ->get());
        return response([
            'Consumer_type'=> $allConsType,
            'General_Report'=>$genRepDetails,
            'General_Total_Cons'=>$totalConsID,
            'General_Total_KWH_Used'=>$totalSumKWHUsed,
            'General_Total_Bill_Amount'=>$totalBillAmount,
            'Consumer_Type_Detail'=>$array,
            'Consumer_Type_Total_Detail'=>$conRepTotal->values()->all(),
        ], 200);
            
    }
    public function genSummaryReport(Request $request)
    {
        $date = str_replace("-","",$request->date);
        if($request->selected == 'billed_on_time')
        {
            $genSummary = collect(DB::table('cons_master AS cm')
            ->leftJoin('route_code AS rc','cm.rc_id','=','rc.rc_id')
            ->leftJoin('town_code AS tc','rc.tc_id','=','tc.tc_id')
            ->leftJoin('area_code AS ac','tc.ac_id','=','ac.ac_id')
            ->leftJoin('cons_type AS ct','cm.ct_id','=','ct.ct_id')
            ->leftJoin('meter_reg AS mr','cm.cm_id','=','mr.cm_id')
            ->where('ac.ac_id',$request->area_id)
            ->where('mr.mr_date_year_month',$date)
            // ->where('mr.mr_printed',1)
            ->orderBy('tc.tc_name','asc')
            ->get());
        }else if($request->selected == 'include_late_billing'){
            $genSummary = collect(DB::table('cons_master AS cm')
            ->leftJoin('route_code AS rc','cm.rc_id','=','rc.rc_id')
            ->leftJoin('town_code AS tc','rc.tc_id','=','tc.tc_id')
            ->leftJoin('area_code AS ac','tc.ac_id','=','ac.ac_id')
            ->leftJoin('cons_type AS ct','cm.ct_id','=','ct.ct_id')
            ->leftJoin('meter_reg AS mr','cm.cm_id','=','mr.cm_id')
            ->where('ac.ac_id',$request->area_id)
            ->where('mr.mr_date_year_month','<=',$date)
            // ->where('mr.mr_printed',1)
            ->orderBy('tc.tc_name','asc')
            ->get());
        }else{
            return response(['Message'=>'Invalid Selection'],422);
        }
        
        $check = $genSummary->first();
        if(!$check)
        {
            return response(['Message'=>'No Records with the given data'],422);
        }

        $groups = $genSummary->groupBy('tc_code');  
        $genSummReport = $groups->map(function ($group){
            return [
                'Route' => $group->first()->tc_code,
                'Route_desc' => $group->first()->tc_name,
                'no_of_Cons' => $group->count('cm_id'),
                'Total_KWH_USED' => $group->sum('mr_kwh_used'),
                'Bill_Amount' => round($group->sum('mr_amount'),2)
            ];
        });
        $ctGroups = collect();
        $allCT = DB::table('cons_type')
        ->select('ct_id','ct_desc')
        ->get();

        for($i=1;$i <= count($allCT);$i++)
        {
            $ctGroups->push($genSummary->where('ct_id', $i)->groupBy('tc_id'));
        }
        
        for($a=0; $a < count($allCT);$a++)
        {
            
            $array[$a] = $ctGroups[$a]->map(function ($ctGroup){
            
            return [$ctGroup->first()->ct_desc=>[
                'Town' => $ctGroup->first()->tc_code,
                'Town_name' => $ctGroup->first()->tc_name,
                'no_of_Cons' => $ctGroup->count('cm_id'),
                'Total_KWH_USED' => $ctGroup->sum('mr_kwh_used'),
                'Bill_Amount' => round($ctGroup->sum('mr_amount'),2)
            ]];
            });
        }
        
        
        
        return response([
            'General_Report'=>$genSummReport,
            'Consumer_Type'=>$array,
        ], 200);
    }
    
    public function summaryOfBills(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        if($request->type == 'all'){
            if($request->demand == 1){
                $summaryBills = collect(DB::table('cons_master AS cm')
                ->join('route_code AS rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
                // ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id') old joined
                ->join('meter_reg AS mr','cm.cm_id','=','mr.cm_id')
                ->join('billing_rates AS br','mr.br_id','=','br.id')
                ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id') //new join for rates bases calculation
                // ->leftJoin('sales AS s','mr.mr_id','=','s.mr_id')
                ->where('rc.rc_id',$request->route_id)
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->where('br.br_billing_ym',$billingPeriod)
                ->where('mr.mr_printed',1)
                ->whereIn('ct.ct_id',[9,10,11])
                ->groupBy('cm.cm_id','mr.mr_id')
                ->orderBy('mr.mr_bill_no','asc')
                ->get());
            }else{
                $summaryBills = collect(DB::table('cons_master AS cm')
                ->join('route_code AS rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
                // ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id') old joined
                ->join('meter_reg AS mr','cm.cm_id','=','mr.cm_id')
                ->join('billing_rates AS br','mr.br_id','=','br.id')
                ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id') //new join for rates bases calculation
                // ->leftJoin('sales AS s','mr.mr_id','=','s.mr_id')
                ->where('rc.rc_id',$request->route_id)
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->where('br.br_billing_ym',$billingPeriod)
                ->where('mr.mr_printed',1)
                ->groupBy('cm.cm_id','mr.mr_id')
                ->orderBy('mr.mr_bill_no','asc')
                ->get());
            }
           
        }else if($request->type == 'additional'){
            if($request->route_id == 'all'){
                $summaryBills = collect(DB::table('cons_master AS cm')
                ->join('route_code AS rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
                // ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id') old joined
                ->join('meter_reg AS mr','cm.cm_id','=','mr.cm_id')
                ->join('billing_rates AS br','mr.br_id','=','br.id')
                ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id') //new join for rates bases calculation
                // ->leftJoin('sales AS s','mr.mr_id','=','s.mr_id')
                // ->where('rc.rc_id',$request->route_id)
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->where('br.br_billing_ym',$billingPeriod)
                ->where('mr.mr_printed',1)
                ->where('mr.sale_closed',0)
                ->groupBy('cm.cm_id','mr.mr_id')
                ->orderBy('mr.mr_bill_no','asc')
                ->get());
            }else{
                $summaryBills = collect(DB::table('cons_master AS cm')
                ->join('route_code AS rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
                // ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id') old joined
                ->join('meter_reg AS mr','cm.cm_id','=','mr.cm_id')
                ->join('billing_rates AS br','mr.br_id','=','br.id')
                ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id') //new join for rates bases calculation
                // ->leftJoin('sales AS s','mr.mr_id','=','s.mr_id')
                ->where('rc.rc_id',$request->route_id)
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->where('br.br_billing_ym',$billingPeriod)
                ->where('mr.mr_printed',1)
                ->where('mr.sale_closed',0)
                ->groupBy('cm.cm_id','mr.mr_id')
                ->orderBy('mr.mr_bill_no','asc')
                ->get());
            }
            
        }else{
            return response(['Message'=>'Invalid Passed type'],422);
        }
        
        
        $check = $summaryBills->first();
        if(!$check)
        {
            return response(['Message'=>'No Records with the given data'],422);
        }

        $summReport = $summaryBills->map(function ($group){
            // $distribCharges = ($group->br_distsys_rate * $group->mr_kwh_used) 
            // + ($group->br_supsys_rate * $group->mr_kwh_used) 
            // + ($group->br_mtrrtlcust_fixed)
            // + ($group->br_mtrsys_rate * $group->mr_kwh_used);
            // $distSys = $distribCharges * $group->br_vat_distrib_kwh;
            $liflnVat = ($group->mr_lfln_disc != 0) ? 0 : round(round($group->br_vat_lfln * $group->mr_kwh_used,3),2);
            
            $evat = round(round($group->br_vat_gen * $group->mr_kwh_used,3),2)
            + round(round($group->br_vat_par * $group->mr_kwh_used,3),2)
            + round(round($group->br_vat_trans * $group->mr_kwh_used,3),2)  
            + round(round($group->br_vat_transdem * $group->mr_dem_kwh_used,3),2) 
            + round(round($group->br_vat_systloss * $group->mr_kwh_used,3),2) 
            + round(round($group->br_vat_distrib_kwh * $group->mr_kwh_used,3),2) 
            + round(round($group->br_vat_distdem * $group->mr_dem_kwh_used,3),2) 
            + round(round($group->br_vat_supfix,3),2) 
            + round(round($group->br_vat_supsys * $group->mr_kwh_used,3),2) 
            + round(round($group->br_vat_mtr_fix,3),2) 
            + round(round($group->br_vat_metersys * $group->mr_kwh_used,3),2) 
            + $liflnVat
            + round(round($group->br_vat_loancondo * $group->mr_kwh_used,3),2) 
            + round(round($group->br_vat_loancondofix,3),2);
            // + $distSys;
            // $arrear = collect(DB::table('meter_reg')
            // // ->select('mr_amount')
            // ->where('cm_id',$group->cm_id)
            // ->where('mr_status',0)
            // ->where('mr_date_year_month','<',$group->mr_date_year_month)
            // ->orderBy('mr_date_year_month', 'desc')
            // ->sum('mr_amount'));

            $arrear = collect(DB::table('meter_reg')
            ->select(DB::raw('sum(mr_amount) as amount'))
            ->where('cm_id',$group->cm_id)
            ->where('mr_date_year_month','<',$group->mr_date_year_month)
            ->where('mr_status',0)
            // ->orderBy('mr_date_year_month', 'desc')
            ->first());

            $tArrear = $arrear['amount'];
            
        return [
            'Bill_Number' => $group->mr_bill_no,
            'SEQ'=> 0,
            'Acc_No' => $group->cm_account_no,
            'Consumer_Name' => $group->cm_full_name,
            'Type' => $group->ct_code,
            'Pres_Reading'=> $group->mr_pres_reading,
            'Prev_Reading'=> $group->mr_prev_reading,
            'Pres_DemReading'=> $group->mr_pres_dem_reading,
            'Prev_DemReading'=> $group->mr_prev_dem_reading,
            'Demand_Used'=> $group->mr_dem_kwh_used,
            'KWH_Used' => $group->mr_kwh_used,
            'Genaration_Charge' => round(round($group->br_gensys_rate * $group->mr_kwh_used,3),2),
            'Power_Act_Reduction' => round(round($group->br_par_rate * $group->mr_kwh_used,3),2),
            'FBHC' => round(round($group->br_fbhc_rate * $group->mr_kwh_used,3),2),
            'Forex_Adj' => round(round($group->br_forex_rate * $group->mr_kwh_used,3),2),
            'FIT_ALLOWANCE' => round(round($group->br_fit_all * $group->mr_kwh_used,3),2),
            'LIFELINE_DISC_SUBS' => ($group->mr_lfln_disc != 0) ? $group->mr_lfln_disc * -1 : round(round($group->br_lfln_subs_rate * $group->mr_kwh_used,3),2),
            'TRANSMN_SYS_CHARGE' => round(round($group->br_transsys_rate * $group->mr_kwh_used,3),2),
            'TRANSMN_DEM_CHARGE' => round(round($group->br_transdem_rate * $group->mr_dem_kwh_used,3),2),
            'LINELOSS_CHARGE' => round(round($group->br_sysloss_rate * $group->mr_kwh_used,3),2),
            'SR_CTZN_DISC_SUBS' => round(round($group->br_sc_subs_rate * $group->mr_kwh_used,3),2),
            'DISTRN_SYS_CHARGE' => round(round($group->br_distsys_rate * $group->mr_kwh_used,3),2),
            'DISTRN_DEM_CHARGE' => round(round($group->br_distdem_rate * $group->mr_dem_kwh_used,3),2),
            // 'DISTRN_DEM_CHARGE' => round(round($group->br_distdem_rate * $group->mr_dem_kwh_used,3),2),
            'SUPPLY_SYS_CHARGE' => round(round($group->br_supsys_rate * $group->mr_kwh_used,3),2),
            'SUPPLY_FIX_CHARGE' => round(round($group->br_suprtlcust_fixed,3),2),
            'METER_SYS_CHARGE' => round(round($group->br_mtrsys_rate * $group->mr_kwh_used,3),2),
            'METER_FIX_CHARGE' => round(round($group->br_mtrrtlcust_fixed,3),2),
            'LOAN_COND_KWH' => round(round($group->br_loancon_rate_kwh * $group->mr_kwh_used,3),2),
            'LOAN_COND_FIX' => round(round($group->br_loancon_rate_fix,3),2),
            'MISSNRY_SPUG' => round(round($group->br_uc4_miss_rate_spu * $group->mr_kwh_used,3),2),
            'MISSNRY_RED' => round(round($group->br_uc4_miss_rate_red * $group->mr_kwh_used,3),2),
            'ENVROTAL_CHARGES' => round(round($group->br_uc6_envi_rate * $group->mr_kwh_used,3),2),
            'STRANDED_DEBT' => round(round($group->br_uc1_npcdebt_rate * $group->mr_kwh_used,3),2),
            'EQ_TAX_ROYALTY' => round(round($group->br_uc5_equal_rate * $group->mr_kwh_used,3),2),
            'NPC_SCC' => round(round($group->br_uc2_npccon_rate * $group->mr_kwh_used,3),2),
            'MCC_CAPEX' => round(round($group->br_capex_rate * $group->mr_kwh_used,3),2),
            'EVAT' => round(round($evat,3),2),
            'W_ARREAR'=> round($tArrear,2),
            'ARREAR_SURCHARGE'=>0,
            'BILLED_AMOUNT' => round($group->mr_amount,2)
        ];
        });
        // dd($summReport);
        // dd($summReport);
        $groups = $summaryBills->groupBy('ct_desc');  
        $summReportKWHUsed = $groups->map(function ($group){
            return [
                'Min'=> 0,
                'Above' => $group->count('cm_id'),
                'Total' => $group->count('cm_id'),
                'Min2' => 0,
                'Above2' => $group->sum('mr_kwh_used'),
                'Total2' => $group->sum('mr_kwh_used'),
            ];
        });
        $summReportBillAmountDemand = $groups->map(function ($group){
            return [
                'Min'=> 0,
                'Above' => $group->sum('mr_amount'),
                'Total' => $group->sum('mr_amount'),
                'Min2' => 0,
                'Above2' => $group->sum('mr_amount'),
                'Total2' => $group->sum('mr_amount'),
                'KW'=>0,
                'Transn'=>$group->first()->br_vat_transdem * $group->first()->mr_dem_kwh_used,
                'Distrn'=> $group->first()->br_vat_distdem * $group->first()->mr_dem_kwh_used,
                'Total_Energy'=> $group->sum('mr_amount'),
            ];
        });
        $vatCompDetailed = $summaryBills->map(function ($summaryBills) {
            return[
                'Generation'=>round(round($summaryBills->br_vat_gen * $summaryBills->mr_kwh_used,3),2),
                'Power_Act'=>round(round($summaryBills->br_vat_par * $summaryBills->mr_kwh_used,3),2),
                'Transmission_Sys'=>round(round($summaryBills->br_vat_trans * $summaryBills->mr_kwh_used,3),2),
                'Transmission_Dem'=>round(round($summaryBills->br_vat_transdem  * $summaryBills->mr_dem_kwh_used,3),2),
                'System_Loss'=>round(round($summaryBills->br_vat_systloss * $summaryBills->mr_kwh_used,3),2),
                'Distribution_Sys'=>round(round($summaryBills->br_vat_distrib_kwh * $summaryBills->mr_kwh_used,3),2),
                'Distribution_Dem'=>round(round($summaryBills->br_vat_distdem  * $summaryBills->mr_dem_kwh_used,3),2),
                'Supply_Fix'=>round(round($summaryBills->br_vat_supfix,3),2),
                'Supply_Sys'=>round(round($summaryBills->br_vat_supsys * $summaryBills->mr_kwh_used,3),2),
                'Meter_Fix'=>round(round($summaryBills->br_vat_mtr_fix,3),2),
                'Meter_Sys'=>round(round($summaryBills->br_vat_metersys * $summaryBills->mr_kwh_used,3),2),
                'Lifeline_Disc'=>($summaryBills->mr_lfln_disc != 0) ? 0 : round(round($summaryBills->br_vat_lfln * $summaryBills->mr_kwh_used,3),2),
                'Loan_Cond'=>round(round($summaryBills->br_vat_loancondo * $summaryBills->mr_kwh_used,3),2),
                'Loan_Cond'=>round(round($summaryBills->br_vat_loancondofix,3),2),
            ];
        });

        $vat = collect([
            'Generation'=>round($vatCompDetailed->sum('Generation'),2),
            'Power_Act'=>round($vatCompDetailed->sum('Power_Act'),2),
            'Transmission_Sys'=>round($vatCompDetailed->sum('Transmission_Sys'),2),
            'Transmission_Dem'=>round($vatCompDetailed->sum('Transmission_Dem'),2),
            'System_Loss'=>round($vatCompDetailed->sum('System_Loss'),2),
            'Distribution_Sys'=>round($vatCompDetailed->sum('Distribution_Sys'),2),
            'Distribution_Dem'=>round($vatCompDetailed->sum('Distribution_Dem'),2),
            'Supply_Fix'=>round($vatCompDetailed->sum('Supply_Fix'),2),
            'Supply_Sys'=>round($vatCompDetailed->sum('Supply_Sys'),2),
            'Metering_Fix'=>round($vatCompDetailed->sum('Meter_Fix'),2),
            'Metering_Sys'=>round($vatCompDetailed->sum('Meter_Sys'),2),
            'Lifeline_Disc'=>round($vatCompDetailed->sum('Lifeline_Disc'),2),
            'Loan_Cond'=>round($vatCompDetailed->sum('Loan_Cond'),2),
            'Loan_Cond_Fix'=>round($vatCompDetailed->sum('Loan_Cond'),2),
        ]);
        
        $totalVat = $vat->sum();
        $total = collect([
            'Total_Bills_Printed'=>count($summReport),
            'Total_KWH'=>round($summReport->sum('KWH_Used'),2),
            'Total_Demand_Used'=>round($summReport->sum('Demand_Used'),2),
            'Total_Genaration_Charge'=>round(round($summReport->sum('Genaration_Charge'),3),2),
            'Total_Power_Act_Reduction'=>round(round($summReport->sum('Power_Act_Reduction'),3),2),
            'Total_FBHC'=>round(round($summReport->sum('FBHC'),3),2),
            'Total_Forex_Adj'=>round(round($summReport->sum('Forex_Adj'),3),2),
            'Total_FIT_ALLOWANCE'=>round(round($summReport->sum('FIT_ALLOWANCE'),3),2),
            'Total_LIFELINE_DISC_SUBS'=>round(round($summReport->sum('LIFELINE_DISC_SUBS'),3),2),
            'Total_TRANSMN_SYS_CHARGE'=>round(round($summReport->sum('TRANSMN_SYS_CHARGE'),3),2),
            'Total_TRANSMN_DEM_CHARGE'=>round(round($summReport->sum('TRANSMN_DEM_CHARGE'),3),2),
            'Total_LINELOSS_CHARGE'=>round(round($summReport->sum('LINELOSS_CHARGE'),3),2),
            'Total_SR_CTZN_DISC_SUBS'=>round(round($summReport->sum('SR_CTZN_DISC_SUBS'),3),2),
            'Total_DISTRN_SYS_CHARGE'=>round(round($summReport->sum('DISTRN_SYS_CHARGE'),3),2),
            'Total_DISTRN_DEM_CHARGE'=>round(round($summReport->sum('DISTRN_DEM_CHARGE'),3),2),
            'Total_DISTRN_DEM_CHARGE'=>round(round($summReport->sum('DISTRN_DEM_CHARGE'),3),2),
            'Total_SUPPLY_SYS_CHARGE'=>round(round($summReport->sum('SUPPLY_SYS_CHARGE'),3),2),
            'Total_SUPPLY_FIX_CHARGE'=>round(round($summReport->sum('SUPPLY_FIX_CHARGE'),3),2),
            'Total_METER_SYS_CHARGE'=>round(round($summReport->sum('METER_SYS_CHARGE'),3),2),
            'Total_METER_FIX_CHARGE'=>round(round($summReport->sum('METER_FIX_CHARGE'),3),2),
            'Total_LOAN_COND_KWH'=>round(round($summReport->sum('LOAN_COND_KWH'),3),2),
            'Total_LOAN_COND_FIX'=>round(round($summReport->sum('LOAN_COND_FIX'),3),2),
            'Total_MISSNRY_SPUG'=>round(round($summReport->sum('MISSNRY_SPUG'),3),2),
            'Total_MISSNRY_RED'=>round(round($summReport->sum('MISSNRY_RED'),3),2),
            'Total_ENVROTAL_CHARGES'=>round(round($summReport->sum('ENVROTAL_CHARGES'),3),2),
            'Total_STRANDED_DEBT'=>round(round($summReport->sum('STRANDED_DEBT'),3),2),
            'Total_EQ_TAX_ROYALTY'=>round(round($summReport->sum('EQ_TAX_ROYALTY'),3),2),
            'Total_NPC_SCC'=>round(round($summReport->sum('NPC_SCC'),3),2),
            'Total_MCC_CAPEX'=>round(round($summReport->sum('MCC_CAPEX'),3),2),
            'Total_EVAT'=>$summReport->sum('EVAT'),
            'Total_W_ARREAR'=>round($summReport->sum('W_ARREAR'),2),
            'Total_ARREAR_SURCHARGE'=>round($summReport->sum('ARREAR_SURCHARGE'),2),
            'Total_BILLED_AMOUNT' => $summReport->sum('BILLED_AMOUNT'),
        ]);
        $constype = collect(DB::table('cons_type')
            ->select('ct_desc')
            ->orderByDesc('ct_id')
            ->get());
        
        if(isset($request->recap))
        {
            if($request->recap == 'Yes' || $request->recap == 'yes')
            {
                return response([
                    'Area'=> '0'.$summaryBills->first()->ac_id.' '.$summaryBills->first()->ac_name,
                    'Town'=> $summaryBills->first()->tc_code.' '.$summaryBills->first()->tc_name,
                    'Route'=> $summaryBills->first()->rc_code.' '.$summaryBills->first()->rc_desc,
                    'Cons_type' =>$constype,
                    'Summary_Bill_Constype_Total_KWH_USED'=> $summReportKWHUsed,
                    'Summary_Bill_Constype_Total_BILL_Amount'=> $summReportBillAmountDemand,
                    'Summary_Bill_Constype_Vat'=> $vat,
                    'Summary_Bill_Constype_Total_Vat'=> round($totalVat,2)
                ],200);
            }
            return response([
                'Area'=> '0'.$summaryBills->first()->ac_id.' '.$summaryBills->first()->ac_name,
                'Town'=> $summaryBills->first()->tc_code.' '.$summaryBills->first()->tc_name,
                'Route'=> $summaryBills->first()->rc_code.' '.$summaryBills->first()->rc_desc,
                'Summary_Bills'=> $summReport,
                'Total_Summary_Bills'=> $total,
                'Cons_type' =>$constype,
                'Summary_Bill_Constype_Total_KWH_USED'=> $summReportKWHUsed,
                'Summary_Bill_Constype_Total_BILL_Amount'=> $summReportBillAmountDemand,
                'Summary_Bill_Constype_Vat'=> $vat,
                'Summary_Bill_Constype_Total_Vat'=> round($totalVat,2)
            ],200);
            
        }
        return response(['NADJEER PASS recap YES/yes'],200);
    }

    public function summaryOfBillsRecap(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        $summaryBillsRecap = collect(DB::table('cons_master AS cm')
        ->join('route_code AS rc','cm.rc_id','=','rc.rc_id')
        ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
        ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
        ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id')
        ->leftJoin('meter_reg AS mr','cm.cm_id','=','mr.cm_id')
        ->join('billing_rates AS br','mr.br_id','=','br.id')
        // ->leftJoin('sales AS s','mr.mr_id','=','s.mr_id')
        ->where('rc.rc_id',$request->route_id)
        ->where('mr.mr_date_year_month',$billingPeriod)
        ->where('br.br_billing_ym',$billingPeriod)
        ->orderBy('mr.mr_bill_no','asc')
        ->get());

        $check = $summaryBillsRecap->first();
        if(!$check)
        {
            return response(['Message'=>'No Records with the given data'],422);
        }
        $groups = $summaryBillsRecap->groupBy('ct_id');  
        $summReportRecap = $groups->map(function ($group){
            return [
                'Cons_type' => $group->first()->ct_desc,
                'Min'=> 0,
                'Above' => $group->count('cm_id'),
                'Total' => $group->count('cm_id'),
                'Min2' => 0,
                'Above2' => $group->sum('mr_kwh_used'),
                'Total2' => $group->sum('mr_kwh_used'),
            ];
        });
        return response([
            'Area'=> '0'.$summaryBillsRecap->first()->ac_id.' '.$summaryBillsRecap->first()->ac_name,
            'Town'=> $summaryBillsRecap->first()->tc_code.' '.$summaryBillsRecap->first()->tc_name,
            'Route'=> $summaryBillsRecap->first()->rc_code.' '.$summaryBillsRecap->first()->rc_desc,
            'Summary_Bills'=> $summReportRecap
        ],200);
    }
    public function consumerDataReport(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        if($request->selected == 'both')
        {
            $consumerDatas = collect(DB::table('cons_master AS cm')
            ->join('route_code AS rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
            ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id')
            ->leftJoin('meter_master AS mm','cm.mm_id','=','mm.mm_id')
            // ->join('meter_reg AS mr','cm.cm_id','=','mr.cm_id')
            ->leftJoin('meter_reg AS mr', function($join) use($billingPeriod)
            {
                $join->on('cm.cm_id', '=', 'mr.cm_id')
                ->on('mr.mr_date_year_month', '<=', DB::raw("'".$billingPeriod."'"));
            })
            ->select('cm.cm_account_no','cm.cm_full_name','cm.cm_address','ct.ct_code','mm.mm_serial_no',
                'ac.ac_id','ac.ac_name','tc.tc_code','tc.tc_name','rc.rc_code','rc.rc_desc',
                DB::raw("GROUP_CONCAT(mr.mr_kwh_used ORDER BY mr_date_year_month ASC) grouped_kwh"))
            // ->where('mr.mr_date_year_month','<=',$billingPeriod)
            ->where('rc.rc_id',$request->route_id)
            ->orderBy('cm.cm_account_no','ASC')
            ->groupBy('cm.cm_id')
            ->get());
        }else if($request->selected == 'active'){
            $consumerDatas = collect(DB::table('cons_master AS cm')
            ->join('route_code AS rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
            ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id')
            ->leftJoin('meter_master AS mm','cm.mm_id','=','mm.mm_id')
            // ->join('meter_reg AS mr','cm.cm_id','=','mr.cm_id')
            ->leftJoin('meter_reg AS mr', function($join) use($billingPeriod)
            {
                $join->on('cm.cm_id', '=', 'mr.cm_id')
                ->on('mr.mr_date_year_month', '<=', DB::raw("'".$billingPeriod."'"));
            })
            ->select('cm.cm_account_no','cm.cm_full_name','cm.cm_address','ct.ct_code','mm.mm_serial_no',
                'ac.ac_id','ac.ac_name','tc.tc_code','tc.tc_name','rc.rc_code','rc.rc_desc',
                DB::raw("GROUP_CONCAT(mr.mr_kwh_used ORDER BY mr_date_year_month ASC) grouped_kwh"))
            // ->where('mr.mr_date_year_month','<=',$billingPeriod)
            ->where('cm_con_status', 1)
            ->where('rc.rc_id',$request->route_id)
            ->orderBy('cm.cm_account_no','ASC')
            ->groupBy('cm.cm_id')
            ->get());
        }else if($request->selected == 'disconnected'){
            $consumerDatas = collect(DB::table('cons_master AS cm')
            ->join('route_code AS rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
            ->join('cons_type AS ct','cm.ct_id','=','ct.ct_id')
            ->leftJoin('meter_master AS mm','cm.mm_id','=','mm.mm_id')
            // ->join('meter_reg AS mr','cm.cm_id','=','mr.cm_id')
            ->leftJoin('meter_reg AS mr', function($join) use($billingPeriod)
            {
                $join->on('cm.cm_id', '=', 'mr.cm_id')
                ->on('mr.mr_date_year_month', '<=', DB::raw("'".$billingPeriod."'"));
            })
            ->select('cm.cm_account_no','cm.cm_full_name','cm.cm_address','ct.ct_code','mm.mm_serial_no',
                'ac.ac_id','ac.ac_name','tc.tc_code','tc.tc_name','rc.rc_code','rc.rc_desc',
                DB::raw("GROUP_CONCAT(mr.mr_kwh_used ORDER BY mr_date_year_month ASC) grouped_kwh"))
            // ->where('mr.mr_date_year_month','<=',$billingPeriod)
            ->where('rc.rc_id',$request->route_id)
            ->where('cm_con_status', 0)
            ->orderBy('cm.cm_account_no','ASC')
            ->groupBy('cm.cm_id')
            ->get());
            if(!$consumerDatas->first())
            {
                return response(['Message'=>'No Disconnected Consumer Found'],422);
            }
        }else{
            return response(['Message'=>'Invalid Selection'],422);
        }
        
        $check = $consumerDatas->first();
        if(!$check)
        {
            return response(['Message'=>'No Records Found'],422);
        }

        $consData = $consumerDatas->map(function ($consumerDatas){
            $explode = explode(',',$consumerDatas->grouped_kwh);
            $spliced = array_splice($explode,-3);
            $avrg = array_sum($spliced)/3;
            return [
                'Customer_ID' => $consumerDatas->cm_account_no,
                'Name' => $consumerDatas->cm_full_name,
                'Customer_Address' => $consumerDatas->cm_address,
                'Type' => $consumerDatas->ct_code,
                'Service_Voltage' =>0,
                'Kwh'=>$avrg,
                'Meter_No' => $consumerDatas->mm_serial_no,
                'Meter_Brand' => 'TBD'
            ];
        })->sortBy('Customer_ID');

        return response([
            'Area'=> '0'.$consumerDatas->first()->ac_id.' '.$consumerDatas->first()->ac_name,
            'Town'=> $consumerDatas->first()->tc_code.' '.$consumerDatas->first()->tc_name,
            'Route'=> $consumerDatas->first()->rc_code.' '.$consumerDatas->first()->rc_desc,
            'Consumer_Data'=>$consData,
            'Total_Consumers'=>$consumerDatas->count('cm_id')
        ],200);
    }
    public function advancePBAppliedToBill(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        $ewPBAppliedToBill= collect(DB::table('sales as s')
            ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
            ->join('cons_master as cm','s.cm_id','=','cm.cm_id')
            ->select('cm.cm_id','cm.cm_account_no','cm.cm_full_name','mr.mr_date_year_month','s.e_wallet_applied')
            ->where('mr.mr_date_year_month','<=',$billingPeriod)
            ->where('s.e_wallet_applied','!=',0)
            ->orderBy('cm.cm_account_no','ASC')
            ->get());
        $a = $ewPBAppliedToBill->groupBy("cm_id");
        $check = $ewPBAppliedToBill->first();
        if(!$check)
        {
            return response(['Message'=>'No Records with the given data'],422);
        }
        $count = $ewPBAppliedToBill->count();
        
        $tada = array();

        foreach($a as $i)
        {
            $totalAppliedBill = collect(DB::table('e_wallet_log as ewl')
                ->join('e_wallet as ew','ewl.ew_id','=','ew.ew_id')
                ->join('cons_master as cm','ew.cm_id','=','cm.cm_id')
                ->select(DB::raw('sum(ewl.ewl_amount) * -1 as totalApplied,cm.cm_id'))
                ->where('ewl.ewl_status','A')
                ->where('cm.cm_id', $i[0]->cm_id)
                ->groupBy('cm.cm_account_no')
                ->orderBy('cm.cm_account_no','asc')
                ->first());

            for($k = 0; $k < count($i); $k++)
            {
                $temp = intval($totalAppliedBill['totalApplied']) + intval($i[$k]->e_wallet_applied);
                $sum = intval($totalAppliedBill['totalApplied']);
                array_push($tada,
                    array('Name'=> $i[$k]->cm_full_name, 
                    'Month_Applied'=>$i[$k]->mr_date_year_month,
                    'Total_Advance_Payment'=>$sum,
                    'Account_No'=>$i[$k]->cm_account_no,
                    'Amount_Applied'=>$i[$k]->e_wallet_applied,
                    'Balance'=>$temp));
                $totalAppliedBill['totalApplied'] = $temp;
            }
        }

        return  response([$tada],200);
    }
    public function powerSalesPerRoute(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        $psPerRoute = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            // ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id') //new join for rates bases calculation
            // ->where('tc.tc_id','=',$request->town_id)
            ->whereBetween('rc.rc_code',[$request->rc_code_from,$request->rc_code_to])
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->where('mr.mr_printed',1)
            // ->groupBy('rc.rc_code','cm.cm_id','tc.tc_id','mr.mr_id')
            ->orderBy('rc.rc_code','asc')
            ->get());
            // dd($request->town_id);
        $check = $psPerRoute->first();
        if(!$check)
        {
            return response(['Message'=>'No Records with the given data'],422);
        }
        /*------------------------------Per_Route_Details-----------------------------*/
        $mappedPS = $psPerRoute->map(function($item){
            // $distribCharges = ($item->br_distsys_rate * $item->mr_kwh_used) 
            // + ($item->br_supsys_rate * $item->mr_kwh_used) 
            // + ($item->br_mtrrtlcust_fixed)
            // + ($item->br_mtrsys_rate * $item->mr_kwh_used);
            // $distSysVat = $distribCharges * $item->br_vat_distrib_kwh;
            return[
                'Area'=> '0'.''.$item->ac_id.' '.$item->ac_name,
                'Town'=> $item->tc_code.' '.$item->tc_name,
                'Route'=> $item->rc_code.' '.$item->rc_desc,
                'Bill_No'=>$item->mr_bill_no,
                'Account_No'=>$item->cm_account_no,
                'Name'=>$item->cm_full_name,
                'Type'=>$item->ct_code,
                'Present'=>$item->mr_pres_reading,
                'Previous'=>$item->mr_prev_reading,
                'KWH_Used'=>$item->mr_kwh_used,
                'Energy_Subsidy_App'=>"0.00",
                'Bill_Amount'=>$item->mr_amount,
                'Total_Amount'=>$item->mr_amount, //tbm maybe bamount * ESA
                'Route_Code'=>$item->rc_code,
                'Cons_Type_ID'=>$item->ct_id,
                'Cons_Type_Desc'=>$item->ct_desc,
                'Cons_ID'=>$item->cm_id,
                'Gen_Vat'=>round(round($item->br_vat_gen * $item->mr_kwh_used,3),2),
                'Trans_Vat'=>round(round($item->br_vat_trans * $item->mr_kwh_used,3),2),
                'SysLoss_Vat'=>round(round($item->br_vat_systloss * $item->mr_kwh_used,3),2),
                'Dist_Vat'=>round(round($item->br_vat_distrib_kwh * $item->mr_kwh_used,3),2),
                'Other_Vat'=>0,
                'Dist_Dem_Vat'=>round(round($item->br_vat_distdem * $item->mr_dem_kwh_used,3),2),
                'Trans_Dem_Vat'=>round(round($item->br_vat_transdem * $item->mr_dem_kwh_used,3),2),
                'Supply_Sys_Vat'=>round(round($item->br_vat_supsys * $item->mr_kwh_used,3),2), //new
                'Supply_Fix_Vat'=>round(round($item->br_vat_supfix,3),2), //new
                'Metering_Fix_Vat'=>round(round($item->br_vat_mtr_fix,3),2), //new
                'Metering_Sys_Vat'=>round(round($item->br_vat_metersys * $item->mr_kwh_used,3),2), //new
                'Power_Act_Red_Vat'=>round(round($item->br_vat_par * $item->mr_kwh_used,3),2), //new
                'LCondo_KWH_Vat'=>round(round($item->br_vat_loancondo * $item->mr_kwh_used,3),2),
                'LCondo_FIX_Vat'=>round(round($item->br_vat_loancondofix,3),2),
                'UC_SCC'=>round(round($item->br_uc2_npccon_rate * $item->mr_kwh_used,3),2),
                'UC_SD'=>round(round($item->br_uc1_npcdebt_rate * $item->mr_kwh_used,3),2),//ask
                'UC_ME_SPUG'=>round(round($item->br_uc4_miss_rate_spu * $item->mr_kwh_used,3),2),
                'UC_ME_RED'=>round(round($item->br_uc4_miss_rate_red * $item->mr_kwh_used,3),2),
                'UC_ENVI'=>round(round($item->br_uc6_envi_rate * $item->mr_kwh_used,3),2),
            ];
        });
        $groupsRoute = $mappedPS->groupBy('Route_Code');
        $totalPerRoute = $groupsRoute->mapWithKeys(function ($group, $key) {
            return [
                $key =>
                    [
                        'Cons_Count'=> $group->count('Cons_ID'),
                        'Total_kwh_used' => $group->sum('KWH_Used'),
                        'Energy_Subsidy_APP' => 0,
                        'Bill_Amount'=>round($group->sum('Bill_Amount'),2),
                        'Total_Amount'=>round($group->sum('Bill_Amount'),2),
                    ]
            ];
        });
        /*------------------------------Total_Per_Consumer_type-----------------------------*/
        $groupsConstype = $mappedPS->groupBy('Cons_Type_Desc');
        $totalPerConsType = $groupsConstype->mapWithKeys(function ($group, $key) {
            return [
                $key =>
                    [
                        'Cons_Count'=> $group->count('Cons_ID'),
                        'Total_kwh_used' => $group->sum('KWH_Used'),
                        'Energy_Subsidy_APP' => 0,
                        'Bill_Amount'=>$group->sum('Bill_Amount'),
                        'Total_Amount'=>$group->sum('Bill_Amount'),
                    ]
            ];
        });
        $grandTotalConsType = [
                        'Grand_Cons_Count'=> $totalPerConsType->sum('Cons_Count'),
                        'Grand_Total_kwh_used' => $totalPerConsType->sum('Total_kwh_used'),
                        'Grand_Energy_Subsidy_APP' => 0,
                        'Grand_Bill_Amount'=>$totalPerConsType->sum('Bill_Amount'),
                        'Grand_Total_Amount'=>$totalPerConsType->sum('Total_Amount'),
        ];
        /*------------------------------Tota_VAT_Per_Consumer_type-----------------------------*/
        $totalPerConsType_VAT = $groupsConstype->mapWithKeys(function ($group, $key) {
            return [
                $key =>
                    [
                        'Gen_Vat'=> round($group->sum('Gen_Vat'),2),
                        'Trans_Vat' => round($group->sum('Trans_Vat'),2),
                        'SysLoss_Vat' => round($group->sum('SysLoss_Vat'),2),
                        'Dist_Vat'=>round($group->sum('Dist_Vat'),2),
                        'Other_Vat'=>0,
                        'Dist_Dem_Vat'=>round($group->sum('Dist_Dem_Vat'),2),
                        'Trans_Dem_Vat'=>round($group->sum('Trans_Dem_Vat'),2),
                        'Supply_Sys_Vat'=>round($group->sum('Supply_Sys_Vat'),2), //new
                        'Supply_Fix_Vat'=>round($group->sum('Supply_Fix_Vat'),2), //new
                        'Metering_Fix_Vat'=>round($group->sum('Metering_Fix_Vat'),2), //new
                        'Metering_Sys_Vat'=>round($group->sum('Metering_Sys_Vat'),2), //new
                        'Power_Act_Red_Vat'=>round($group->sum('Power_Act_Red_Vat'),2), //new
                        'LCondo_Kwh_Vat'=>round($group->sum('LCondo_KWH_Vat'),2),
                        'LCondo_Fix_Vat'=>round($group->sum('LCondo_FIX_Vat'),2),
                        'UC_SCC'=>round($group->sum('UC_SCC'),2),
                        'UC_SD'=>round($group->sum('UC_SD'),2),
                        'UC_ME_SPUG'=>round($group->sum('UC_ME_SPUG'),2),
                        'UC_ME_RED'=>round($group->sum('UC_ME_RED'),2),
                        'UC_ME_ENVI'=>round($group->sum('UC_ENVI'),2)
                    ]
            ];
        });
        $grandTotalPerConsType_VAT =[
            'Gen_Vat'=> round($totalPerConsType_VAT->sum('Gen_Vat'),2),
            'Trans_Vat' => round($totalPerConsType_VAT->sum('Trans_Vat'),2),
            'SysLoss_Vat' => round($totalPerConsType_VAT->sum('SysLoss_Vat'),2),
            'Dist_Vat'=>round($totalPerConsType_VAT->sum('Dist_Vat'),2),
            'Other_Vat'=>0,
            'Dist_Dem_Vat'=>round($totalPerConsType_VAT->sum('Dist_Dem_Vat'),2),
            'Trans_Dem_Vat'=>round($totalPerConsType_VAT->sum('Trans_Dem_Vat'),2),
            'Supply_Sys_Vat'=>round($totalPerConsType_VAT->sum('Supply_Sys_Vat'),2), //new
            'Supply_Fix_Vat'=>round($totalPerConsType_VAT->sum('Supply_Fix_Vat'),2), //new
            'Metering_Fix_Vat'=>round($totalPerConsType_VAT->sum('Metering_Fix_Vat'),2), //new
            'Metering_Sys_Vat'=>round($totalPerConsType_VAT->sum('Metering_Sys_Vat'),2), //new
            'Power_Act_Red_Vat'=>round($totalPerConsType_VAT->sum('Power_Act_Red_Vat'),2), //new
            'LCondo_Kwh_Vat'=>round($totalPerConsType_VAT->sum('LCondo_KWH_Vat'),2),
            'LCondo_Fix_Vat'=>round($totalPerConsType_VAT->sum('LCondo_FIX_Vat'),2),
            'UC_SCC'=>round($totalPerConsType_VAT->sum('UC_SCC'),2),
            'UC_SD'=>round($totalPerConsType_VAT->sum('UC_SD'),2),
            'UC_ME_SPUG'=>round($totalPerConsType_VAT->sum('UC_ME_SPUG'),2),
            'UC_ME_RED'=>round($totalPerConsType_VAT->sum('UC_ME_RED'),2),
            'UC_ME_ENVI'=>round($totalPerConsType_VAT->sum('UC_ENVI'),2)
        ];

        return response([   
            'Cons_Per_Route'=>$groupsRoute,
            'Total_Per_Route'=>$totalPerRoute,
            'Total_Per_Constype'=>$totalPerConsType,
            'Grand_Total_Per_Constype'=>$grandTotalConsType,
            'Total_Per_Constype_Vat'=>$totalPerConsType_VAT,
            'Grand_Total_Per_Constype_Vat'=>$grandTotalPerConsType_VAT,
        ],200);
    }
    public function lifelineConsDetailed(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        $llineCollection = collect(DB::table('lifeline_rates')->get());
        
        $llMax = $llineCollection->max()->ll_max_kwh;
        $llMin = $llineCollection->min()->ll_min_kwh;
        if(isset($request->town_id))
        {
            $consCollection = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->where('mr.mr_printed',1)
            ->where('tc.tc_id',$request->town_id)
            ->whereBetween('mr.mr_kwh_used',[$llMin,$llMax])
            ->where('mr.mr_lfln_disc','>',0)
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->orderBy('cm.cm_account_no','asc')
            ->get());
        }else{
            $consCollection = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->where('mr.mr_printed',1)
            ->whereBetween('mr.mr_kwh_used',[$llMin,$llMax])
            ->where('mr.mr_lfln_disc','>',0)
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->orderBy('cm.cm_account_no','asc')
            ->get());
        }
        

        $check = $consCollection->first();
        if(!$check)
        {
            return response(['Message'=>'No Records with the given data'],422);
        }
        
        $mappedConsCollection = $consCollection->map(function($item)use($llineCollection){
            for($i=0;$i<count($llineCollection);$i++)
            {
                //Computation lifeline(Discount)
                // $genCharge[$i] = $item->br_gensys_rate * $item->mr_kwh_used;
                // $franBenCharge[$i] = $item->br_fbhc_rate * $item->mr_kwh_used;
                // $transCharge[$i] = $item->br_transsys_rate * $item->mr_kwh_used;
                // $transDemCharge[$i] = $item->br_transdem_rate * $item->mr_dem_kwh_used;
                // $syslossCharge[$i] = $item->br_sysloss_rate * $item->mr_kwh_used;
                // $distSysCharge[$i] = $item->br_distsys_rate * $item->mr_kwh_used;
                // $distDemCharge[$i] = $item->br_distdem_rate * $item->mr_dem_kwh_used;
                // $supFixCharge[$i] = $item->br_suprtlcust_fixed; //fix 0perCst
                // $supSysCharge[$i] = $item->br_supsys_rate * $item->mr_kwh_used;
                // $meterFixCharge[$i] = $item->br_mtrrtlcust_fixed; //fix 5perCst
                // $meterSysCharge[$i] = $item->br_mtrsys_rate * $item->mr_kwh_used;
                // $totalCharge[$i] = $genCharge[$i] + $franBenCharge[$i] + $transCharge[$i] + $transDemCharge[$i] + $syslossCharge[$i] + 
                //     $distSysCharge[$i] + $distDemCharge[$i] + $supFixCharge[$i] + $supSysCharge[$i] + $meterFixCharge[$i] + $meterSysCharge[$i];
                // //min and max
                // $discountMin[$i] = $llineCollection[$i]->ll_min_kwh;
                // $discountMax[$i] = $llineCollection[$i]->ll_max_kwh;

                // if($item->mr_kwh_used >= $discountMin[$i] && $item->mr_kwh_used <= $discountMax[$i])
                // {
                //     // $disPerc = $llineCollection[$i]->ll_discount;
                //     // $calc = 1 - $disPerc;
                //     // $discount = round(($item->mr_amount / $calc) * $disPerc,2);
                //     $discount = $totalCharge[$i] * $llineCollection[$i]->ll_discount;
                // }
                

            }
            
            return[
                'Account_No'=>$item->cm_account_no,
                'Name'=>$item->cm_full_name,
                'Kwh_Used'=>$item->mr_kwh_used,
                'LDISCOUNT'=>round($item->mr_lfln_disc,2) * -1,
                'Bill_Amount'=>$item->mr_amount,
            ];
        });
        $count = count($consCollection);
        $total = [
            'Total_Consumer'=>$count,
            'Total_Kwh_Used'=>$mappedConsCollection->sum('Kwh_Used'),
            'Total_LDiscount'=>round($mappedConsCollection->sum('LDISCOUNT'),2),
            'Total_Bill_Amount'=>round($mappedConsCollection->sum('Bill_Amount'),2)
        ];
        
        return response([
            'Area'=>'0'.$consCollection->first()->ac_id.' '.$consCollection->first()->ac_name,
            'Town'=>$consCollection->first()->tc_code.' '.$consCollection->first()->tc_name,
            'Lifeline_Consumer'=>$mappedConsCollection,
            'Total'=>$total
        ],200); 
    }
    public function summaryConsPerKWHUsed(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
    
        if(isset($request->all_area) && $request->all_area == 'yes')
        {
            $summCons = collect(DB::table('cons_master as cm')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->where('mr.mr_printed',1)
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->whereBetween('mr.mr_kwh_used',[$request->kwh_used_from,$request->kwh_used_to])
            ->orderBy('cm.cm_account_no','asc')
            ->get());
            $check = $summCons->first();
            
            if(!$check)
            {
                return response(['Message'=>'No Records with the given data'],422);
            }
            $codes = 'Area Code: 0'.$check->ac_id.' '.$check->ac_name.'
            Town Code: '.$check->tc_code.' '.$check->tc_name.'
            Route Code: '.$check->rc_code.' '.$check->rc_desc;
            $mappedSummCons = $summCons->map(function($item){
                $status = ($item->cm_con_status == '1')?'Active':'Deactivated';
                return[
                    'Account_No'=>$item->cm_account_no,
                    'Type'=>$item->ct_code,
                    'Name'=>$item->cm_full_name,
                    'Bill_No'=>$item->mr_bill_no,
                    'KWH_Used'=>$item->mr_kwh_used,
                    'Meter_No'=>$item->mm_serial_no,
                    'Status'=>$status,
                    'Remarks'=>'OK'
                ];
            });
            $totalNoCons = $summCons->count();
            return response([
                'Codes'=>nl2br($codes),
                'Cons_Per_Kwh_Used'=>$mappedSummCons,
                'Total_No_Consumer'=>$totalNoCons,
            ]);

        }

        $summCons = collect(DB::table('cons_master as cm')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->whereBetween('mr.mr_kwh_used',[$request->kwh_used_from,$request->kwh_used_to])
            ->whereBetween('rc.rc_code',[$request->route_code_from,$request->route_code_to])
            ->where('rc.tc_id',$request->town_id)
            ->groupBy('rc.rc_code','cm.cm_id','mr.mr_id')
            ->orderBy('cm.cm_account_no','asc')
            ->get());

        $check = $summCons->first();
        if(!$check)
        {
            return response(['Message'=>'No Records with the given data'],422);
        }
        $mappedSummCons = $summCons->map(function ($item){
            $status = ($item->cm_con_status == '1')?'Active':'Deactivated';
            $codes = 'Area Code: 0'.$item->ac_id.' '.$item->ac_name.'
            Town Code: '.$item->tc_code.' '.$item->tc_name.'
            Route Code: '.$item->rc_code.' '.$item->rc_desc;
            return [
                'Codes'=>nl2br($codes),
                'Account_No'=>$item->cm_account_no,
                'Type'=>$item->ct_code,
                'Name'=>$item->cm_full_name,
                'Bill_No'=>$item->mr_bill_no,
                'KWH_Used'=>$item->mr_kwh_used,
                'Meter_No'=>$item->mm_serial_no,
                'Status'=>$status,
                'Remarks'=>'OK',
            ];
        });

        $grouped = $mappedSummCons->groupBy('Codes');
        $totalPerRoute = $grouped->mapWithKeys(function ($item, $key) {
            return [
                $key =>
                    [
                        'Cons_Count'=> $item->count('Account_No'),
                    ]
            ];
        });
        $totalNoCons = $summCons->count();

        return response([
            'Cons_Per_Kwh_Used'=>$grouped,
            'Total_No_Consumer'=>$totalPerRoute
        ]);
    }
    public function summaryOfSalesCoverage($date)
    {
        $billingPeriod = str_replace("-","",$date);
        $summaryCoverage = collect(DB::table('cons_master as cm')
            ->LeftJoin('meter_reg as mr',function($join) use ($billingPeriod)
            {
                $join->on('cm.cm_id','=', 'mr.cm_id')
                ->when($billingPeriod, function ($query, $billPeriod) {
                    return $query->where('mr_date_year_month', $billPeriod);
                });
            })
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->select('ac.ac_name','tc.tc_name',DB::raw('SUM(mr.mr_kwh_used) AS Total_KWH,SUM(mr.mr_amount) AS Total_Amount,
            count(cm.cm_id) as count,SUM(mr.mr_dem_kwh_used)as Demand_Kwh_Sold,SUM(mr.mr_dem_kwh_used * mr.mr_pres_dem_reading)as Demand_Amount_Sold'),'ct.ct_desc')
            ->orderBy('ac.ac_id','asc')
            ->groupby('tc.tc_name','ct.ct_desc')
            ->get());
        $check = $summaryCoverage->first();
        if(!$check)
        {
            return response(['Message'=>'No Records with the given data'],422);
        }
        $mapped = $summaryCoverage->map(function($item){

            return[
                'Area_Name'=>$item->ac_name,
                'Town_Name'=>$item->tc_name,
                'Minimum_Receiving_Services'=>$item->count,
                'Demand_Kwh_Sold'=>$item->Demand_Kwh_Sold,
                'Demand_Amount_Sold'=>$item->Demand_Amount_Sold,
                'Energy_Kwh_Sold'=>$item->Total_KWH,
                'Energy_Amount_Sold'=>$item->Total_Amount,
                'Cons_Type'=>$item->ct_desc,
            ];
        });
        $grouped = $mapped->groupBy(['Area_Name','Town_Name','Cons_Type']);
        $constype = DB::table('cons_type')
            ->select('ct_desc as Cons_Type')
            ->get();
        return response([
            'Cons_Type'=>$constype,
            'Cons_Per_Kwh_Used'=>$grouped
        ]);
    }
    public function summaryOfBillsAmountIssued(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        $amountIssuedCurrent = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->select(DB::raw('COUNT(*) AS Number_Bill,rc.rc_desc AS Description,rc.rc_code,SUM(mr.mr_amount) AS Current_Bill'))
            ->where('tc.tc_id',$request->town_id)
            ->where('mr.mr_printed',1)
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->groupBy('rc.rc_code')
            ->orderBy('rc.rc_code')
            ->get());
        $amountIssuedArrear = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->select(DB::raw('COUNT(*) AS Number_Bill,rc.rc_desc AS Description,rc.rc_code,SUM(mr.mr_amount) AS Current_Bill,mr.mr_date_year_month,rc.rc_id'))
            ->where('tc.tc_id',$request->town_id)
            ->where('mr.mr_printed',1)
            ->where('mr.mr_status',0)
            ->where('mr.mr_date_year_month','=',$billingPeriod)
            ->groupBy('rc.rc_code')
            ->orderBy('rc.rc_code')
            ->get());
        
        $check = $amountIssuedArrear->first();
        if(!$check)
        {
            return response(['Message'=>'No Records with the given data'],422);
        }
        $mappedAmountIssuedCurrent = $amountIssuedCurrent->map(function($item){
        return[
            'Route'=>$item->rc_code,
            'Route_Barangay_Desc'=>$item->Description,
            'No_Bills'=>$item->Number_Bill,
            'Current_Bill'=>$item->Current_Bill,
            'Surcharge'=>0, 
        ];
        });
        $mappedAmountIssuedArrear = $amountIssuedArrear->map(function($item){
            $data = (new GetArrearsTotalRouteService())->GetRGetArrearsTotalRoute($item->mr_date_year_month,$item->rc_id);
            return[
                'Route'=>$item->rc_code,
                'No_Bills'=>$item->Number_Bill,
                'Arrear_Bill'=>$data,
                'Total_Amount'=>$data,   
            ];
        });
        // dd($mappedAmountIssuedArrear);
        for($i=0;$i<count($amountIssuedCurrent);$i++)
        {
            $mergeCollection[$i] = [
                    'Route'=>$mappedAmountIssuedCurrent[$i]['Route'],
                    'Route_Barangay_Desc'=>$mappedAmountIssuedCurrent[$i]['Route_Barangay_Desc'],
                    'Current_No_Bills'=>round($mappedAmountIssuedCurrent[$i]['No_Bills'],2),
                    'Current_Bills'=>round($mappedAmountIssuedCurrent[$i]['Current_Bill'],2),
                    'Arrear_Bills'=>round($mappedAmountIssuedArrear[$i]['Arrear_Bill'],2),
                    'Arrear_No_Bills'=>round($mappedAmountIssuedArrear[$i]['No_Bills'],2),
                    'Surcharge'=>$mappedAmountIssuedCurrent[$i]['Surcharge'],
                    'Total_Amount'=>round($mappedAmountIssuedArrear[$i]['Arrear_Bill'] + $mappedAmountIssuedCurrent[$i]['Current_Bill'],2)
                ];
        }
        $count = count($mergeCollection);
        $totalCurrentNoBills =0;
        $totalCurrentBills = 0;
        $totalArrearBills = 0;
        $totalArrearNoBills = 0;
        $grandTotalAmount = 0;
        for($i=0;$i<$count;$i++)
        {
            $totalCurrentNoBills += $mergeCollection[$i]['Current_No_Bills'];
            $totalCurrentBills += $mergeCollection[$i]['Current_Bills'];
            $totalArrearBills += $mergeCollection[$i]['Arrear_Bills'];
            $totalArrearNoBills += $mergeCollection[$i]['Arrear_No_Bills'];
            $grandTotalAmount += $mergeCollection[$i]['Total_Amount'];
        }

        $total =[
            'Current_No_Bills'=>$totalCurrentNoBills,
            'Current_Bills'=>round($totalCurrentBills,2),
            'Arrear_Bills'=>round($totalArrearBills,2),
            'Arrear_No_Bills'=>$totalArrearNoBills,
            'Surcharge'=>0,
            'Total_Amount'=>round($grandTotalAmount,2)
        ];
        
        return response([
            'Summ_Amount_Issued'=>$mergeCollection,
            'Totals'=>$total
        ],200);
        
    }
    public function lifelinePerArea(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        $llineCollection = collect(DB::table('lifeline_rates')->get());

        // $llMax = $llineCollection->max()->ll_max_kwh;
        // $llMin = $llineCollection->min()->ll_min_kwh;
        
        $billingPeriod = str_replace("-","",$request->date);
        $lifelinePerArea = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->where('mr.mr_printed',1)
            ->where('ac.ac_id',$request->area_id)
            ->whereBetween('mr.mr_kwh_used',[0,25])
            ->where('mr.mr_lfln_disc','>',0)
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->orderBy('cm.cm_account_no','asc')
            ->get());
        
        $check = $lifelinePerArea->first();
        if(!$check)
        {
            return response(['Message'=>'No Records with the given data'],422);
        }

        for($i=0;$i<=25;$i++)
        {
            if($i == 25){
                $rangeFrom[$i] = $i.'.00';
                $rangeTo[$i] = $i.'.00';
            }else{
                $rangeFrom[$i] = $i.'.00';
                $rangeTo[$i] = $i.'.99';
            }
            
            
            $count[$i] = $lifelinePerArea->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->count('cm_id');
            $sumKwh[$i] = $lifelinePerArea->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->sum('mr_kwh_used');
            $sumBillAmount[$i] = round($lifelinePerArea->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->sum('mr_amount'),2);
            //loop for per consumer discount
            $consDiscount[$i] = $lifelinePerArea->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->map(function ($item) use($llineCollection,$sumKwh){
                 
            //Computation lifeline(Discount) Per Consumer
            // $genCharge = $item->br_gensys_rate * $item->mr_kwh_used;
            // $franBenCharge = $item->br_fbhc_rate * $item->mr_kwh_used;
            // $transCharge = $item->br_transsys_rate * $item->mr_kwh_used;
            // $transDemCharge = $item->br_transdem_rate * $item->mr_kwh_used;
            // $syslossCharge = $item->br_sysloss_rate * $item->mr_kwh_used;
            // $distSysCharge = $item->br_distsys_rate * $item->mr_kwh_used;
            // $distDemCharge = $item->br_distdem_rate * $item->mr_kwh_used;
            // $supFixCharge = $item->br_suprtlcust_fixed; //fix 0perCst
            // $supSysCharge = $item->br_supsys_rate * $item->mr_kwh_used;
            // $meterFixCharge = $item->br_mtrrtlcust_fixed; //fix 5perCst
            // $meterSysCharge = $item->br_mtrsys_rate * $item->mr_kwh_used;
            // $totalCharge = $genCharge + $franBenCharge + $transCharge + $transDemCharge + $syslossCharge + 
            // $distSysCharge + $distDemCharge + $supFixCharge + $supSysCharge + $meterFixCharge + $meterSysCharge;
            $totalCharge = $item->mr_lfln_disc * -1;

            // for($b=0;$b<count($llineCollection);$b++)
            // {
            //     $discountMin[$b] = $llineCollection[$b]->ll_min_kwh;
            //     $discountMax[$b] = $llineCollection[$b]->ll_max_kwh;
            //     if($item->mr_kwh_used >= $discountMin[$b] && $item->mr_kwh_used <= $discountMax[$b])
            //     {
            //         $discount[$b] = ($totalCharge * $llineCollection[$b]->ll_discount) * -1;
            //     }
            // }
            return $totalCharge;
                
            })->flatten();

            $discountSumAll[$i] = round($consDiscount[$i]->sum(),2);
        }

        $llPerArea = collect([
            'Range_From'=>$rangeFrom,
            'Range_To'=>$rangeTo,
            'Count'=>$count,
            'Kwh_Used'=>$sumKwh,
            'Lifeline_Amount'=>$discountSumAll,
            'Bill_Amount'=>$sumBillAmount,
        ]);

        return response([
            'Area'=>'0'.$check->ac_id.' '.$check->ac_name,
            'Lifeline_Per_Area'=>$llPerArea,
            'Total_Count'=>array_sum($llPerArea['Count']),
            'Total_Kwh_Used'=>array_sum($llPerArea['Kwh_Used']),
            'Total_Lifeline_Amount'=>round(array_sum($llPerArea['Lifeline_Amount']),2),
            'Total_Bill_Amount'=>round(array_sum($llPerArea['Bill_Amount']),2)
        ],200);
    }
    public function lifelinePerTown(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        $llineCollection = collect(DB::table('lifeline_rates')->get());

        // $llMax = $llineCollection->max()->ll_max_kwh;
        // $llMin = $llineCollection->min()->ll_min_kwh;
        
        $billingPeriod = str_replace("-","",$request->date);
        $lifelinePerTown = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->where('mr.mr_printed',1)
            ->where('tc.tc_id',$request->town_id)
            ->whereBetween('mr.mr_kwh_used',[0,25])
            ->where('mr.mr_lfln_disc','>',0)
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->orderBy('cm.cm_account_no','asc')
            ->get());
        
        $check = $lifelinePerTown->first();
        if(!$check)
        {
            return response(['Message'=>'No Records with the given data'],422);
        }

        for($i=0;$i<=25;$i++)
        {
            if($i == 25){
                $rangeFrom[$i] = $i.'.00';
                $rangeTo[$i] = $i.'.00';
            }else{
                $rangeFrom[$i] = $i.'.00';
                $rangeTo[$i] = $i.'.99';
            }
            
            
            $count[$i] = $lifelinePerTown->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->count('cm_id');
            $sumKwh[$i] = $lifelinePerTown->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->sum('mr_kwh_used');
            $sumBillAmount[$i] = round($lifelinePerTown->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->sum('mr_amount'),2);
            //loop for per consumer discount
            $consDiscount[$i] = $lifelinePerTown->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->map(function ($item) use($llineCollection,$sumKwh){
                 
            //Computation lifeline(Discount) Per Consumer
            // $genCharge = $item->br_gensys_rate * $item->mr_kwh_used;
            // $franBenCharge = $item->br_fbhc_rate * $item->mr_kwh_used;
            // $transCharge = $item->br_transsys_rate * $item->mr_kwh_used;
            // $transDemCharge = $item->br_transdem_rate * $item->mr_kwh_used;
            // $syslossCharge = $item->br_sysloss_rate * $item->mr_kwh_used;
            // $distSysCharge = $item->br_distsys_rate * $item->mr_kwh_used;
            // $distDemCharge = $item->br_distdem_rate * $item->mr_kwh_used;
            // $supFixCharge = $item->br_suprtlcust_fixed; //fix 0perCst
            // $supSysCharge = $item->br_supsys_rate * $item->mr_kwh_used;
            // $meterFixCharge = $item->br_mtrrtlcust_fixed; //fix 5perCst
            // $meterSysCharge = $item->br_mtrsys_rate * $item->mr_kwh_used;
            // $totalCharge = $genCharge + $franBenCharge + $transCharge + $transDemCharge + $syslossCharge + 
            // $distSysCharge + $distDemCharge + $supFixCharge + $supSysCharge + $meterFixCharge + $meterSysCharge;
            $totalCharge = $item->mr_lfln_disc * -1;
            // for($b=0;$b<count($llineCollection);$b++)
            // {
            //     $discountMin[$b] = $llineCollection[$b]->ll_min_kwh;
            //     $discountMax[$b] = $llineCollection[$b]->ll_max_kwh;
            //     if($item->mr_kwh_used >= $discountMin[$b] && $item->mr_kwh_used <= $discountMax[$b])
            //     {
            //         $discount[$b] = ($totalCharge * $llineCollection[$b]->ll_discount) * -1;
            //     }
            // }
            return $totalCharge;
                
            })->flatten();

            $discountSumAll[$i] = round($consDiscount[$i]->sum(),2);
        }

        $llPerTown = collect([
            'Range_From'=>$rangeFrom,
            'Range_To'=>$rangeTo,
            'Count'=>$count,
            'Kwh_Used'=>$sumKwh,
            'Lifeline_Amount'=>$discountSumAll,
            'Bill_Amount'=>$sumBillAmount,
        ]);

        return response([
            'Area_Code'=>'0'.$check->ac_id.' '.$check->ac_name,
            'Town_Code'=>$check->tc_code.' '.$check->tc_name,
            'Lifeline_Per_Town'=>$llPerTown,
            'Total_Count'=>array_sum($llPerTown['Count']),
            'Total_Kwh_Used'=>array_sum($llPerTown['Kwh_Used']),
            'Total_Lifeline_Amount'=>round(array_sum($llPerTown['Lifeline_Amount']),2),
            'Total_Bill_Amount'=>round(array_sum($llPerTown['Bill_Amount']),2)
        ],200);
    }
    public function lifelinePerRoute(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        $llineCollection = collect(DB::table('lifeline_rates')->get());

        // $llMax = $llineCollection->max()->ll_max_kwh;
        // $llMin = $llineCollection->min()->ll_min_kwh;
        $lifelinePerRoute = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->where('mr.mr_printed',1)
            ->where('rc.rc_id',$request->route_id)
            ->whereBetween('mr.mr_kwh_used',[0,25])
            ->where('mr.mr_lfln_disc','>',0)
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->orderBy('cm.cm_account_no','asc')
            ->get());
        
        $check = $lifelinePerRoute->first();
        if(!$check)
        {
            return response(['Message'=>'No Records with the given data'],422);
        }

        for($i=0;$i<=25;$i++)
        {
            if($i == 25){
                $rangeFrom[$i] = $i.'.00';
                $rangeTo[$i] = $i.'.00';
            }else{
                $rangeFrom[$i] = $i.'.00';
                $rangeTo[$i] = $i.'.99';
            }
            
            
            $count[$i] = $lifelinePerRoute->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->count('cm_id');
            $sumKwh[$i] = $lifelinePerRoute->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->sum('mr_kwh_used');
            $sumBillAmount[$i] = round($lifelinePerRoute->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->sum('mr_amount'),2);
            //loop for per consumer discount
            $consDiscount[$i] = $lifelinePerRoute->whereBetween('mr_kwh_used',[$rangeFrom[$i],$rangeTo[$i]])->map(function ($item) use($llineCollection,$sumKwh){
                 
            //Computation lifeline(Discount) Per Consumer
            // $genCharge = $item->br_gensys_rate * $item->mr_kwh_used;
            // $franBenCharge = $item->br_fbhc_rate * $item->mr_kwh_used;
            // $transCharge = $item->br_transsys_rate * $item->mr_kwh_used;
            // $transDemCharge = $item->br_transdem_rate * $item->mr_kwh_used;
            // $syslossCharge = $item->br_sysloss_rate * $item->mr_kwh_used;
            // $distSysCharge = $item->br_distsys_rate * $item->mr_kwh_used;
            // $distDemCharge = $item->br_distdem_rate * $item->mr_kwh_used;
            // $supFixCharge = $item->br_suprtlcust_fixed; //fix 0perCst
            // $supSysCharge = $item->br_supsys_rate * $item->mr_kwh_used;
            // $meterFixCharge = $item->br_mtrrtlcust_fixed; //fix 5perCst
            // $meterSysCharge = $item->br_mtrsys_rate * $item->mr_kwh_used;
            // $totalCharge = $genCharge + $franBenCharge + $transCharge + $transDemCharge + $syslossCharge + 
            // $distSysCharge + $distDemCharge + $supFixCharge + $supSysCharge + $meterFixCharge + $meterSysCharge;
            $totalCharge = $item->mr_lfln_disc * -1;
            // for($b=0;$b<count($llineCollection);$b++)
            // {
            //     $discountMin[$b] = $llineCollection[$b]->ll_min_kwh;
            //     $discountMax[$b] = $llineCollection[$b]->ll_max_kwh;
            //     if($item->mr_kwh_used >= $discountMin[$b] && $item->mr_kwh_used <= $discountMax[$b])
            //     {
            //         $discount[$b] = ($totalCharge * $llineCollection[$b]->ll_discount) * -1;
            //     }
            // }
            return $totalCharge;
                
            })->flatten();

            $discountSumAll[$i] = round($consDiscount[$i]->sum(),2);
        }

        $llPerRoute = collect([
            'Range_From'=>$rangeFrom,
            'Range_To'=>$rangeTo,
            'Count'=>$count,
            'Kwh_Used'=>$sumKwh,
            'Lifeline_Amount'=>$discountSumAll,
            'Bill_Amount'=>$sumBillAmount,
        ]);
        

        return response([
            'Area_Code'=>'0'.$check->ac_id.' '.$check->ac_name,
            'Town_Code'=>$check->tc_code.' '.$check->tc_name,
            'Route_Code'=>$check->rc_code.' '.$check->rc_desc,
            'Lifeline_Per_Town'=>$llPerRoute,
            'Total_Count'=>array_sum($llPerRoute['Count']),
            'Total_Kwh_Used'=>array_sum($llPerRoute['Kwh_Used']),
            'Total_Lifeline_Amount'=>round(array_sum($llPerRoute['Lifeline_Amount']),2),
            'Total_Bill_Amount'=>round(array_sum($llPerRoute['Bill_Amount']),2)
        ],200);
    }
    public function summEnergySales(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        // GET RATES from service for all ConsumerType
        $loc = $request->location;
        // dd($loc);
        $data = (new GetRateService())->GetRate($billingPeriod,$loc);
        if($data->isEmpty())
        {
            return response(['Message'=> 'No Records Found'],422);
        }
        $newData = collect($data->groupBy('Ct_Desc'));
        $mapped = $newData->map(function($group){
            return[
                /*-------------------------------------------- other info ------------------------------------------------------- */
                 'COUNT'=>$group->count('KWH_USED'),
                 'KWH_USED'=>round($group->sum('KWH_USED'),2),
                 'BILL_AMOUNT'=>round($group->sum('BILL_AMOUNT'),2),
                 'KW_SOLD'=>round($group->sum('KW_SOLD'),2),
                 'DEM_AMOUNT'=>round($group->sum('DEM_AMOUNT'),2),
                 /*-------------------------------------------- GENERATION CHARGES -------------------------------------------------------*/
                 'GENSYS' => round(round($group->sum('Generation_System_Charge'),3),2),
                 'PAR' => round(round($group->sum('Power_Act_Reduction'),3),2),
                 'FBHC' => round(round($group->sum('Franchise_Benefits_To_Host'),3),2),
                 'FOREX' => round(round($group->sum('FOREX_Adjustment_Charge'),3),2),
                 /* ------------------------------------------- TRANSMISSION CHARGES  ------------------------------------------------------*/
                 'TRANSYS_DEMAND' => round(round($group->sum('Trans_Demand_Charge'),3),2),
                 'TRANSYS' => round(round($group->sum('Transmission_System_Charge'),3),2),
                 'SL' => round(round($group->sum('System_Loss_Charge'),3),2),
                 /* ------------------------------------------- DISTRIBUTION CHARGES ------------------------------------------------------*/
                 'DIST_DEMAND' => round(round($group->sum('Dist_Demand_Charge'),3),2),
                 'DISTSYS' => round(round($group->sum('Distribution_System_Charge'),3),2),
                 'SUPPLYFIX' => round(round($group->sum('Supply_System_Fixed_Charge'),3),2),
                 'SUPPLYSYS' => round(round($group->sum('Supply_System_Charge'),3),2),
                 'METSYS' => round(round($group->sum('Retail_Customer_Meter_Charge'),3),2),
                 'METFIX' => round(round($group->sum('Retail_Customer_Mtr_Fixed_Charge'),3),2),
                 /* ------------------------------------------- UNIVERSAL CHARGES ------------------------------------------------------*/
                 'ME_SPUG' => round(round($group->sum('UC_SPUG'),3),2),
                 'ME_RED' => round(round($group->sum('UC_RED_Cash_Incentive'),3),2),
                 'UC_EC' => round(round($group->sum('UC_Environmental_Charge'),3),2),
                 'EC_ETR' => round(round($group->sum('UC_Equal_of_Taxes_Royalties'),3),2),
                 'NPC_SCC' => round(round($group->sum('UC_NPC_Stranded_Contract_Cost'),3),2),
                 'NPC_SD' => round(round($group->sum('UC_NPC_Stranded_Debt_Cost'),3),2),
                 'FIT_ALL' => round(round($group->sum('Feed_in_Tariff_Allowance'),3),2),
                 /* ------------------------------------------- OTHER CHARGES ------------------------------------------------------*/
                 'ICCS' => round(round($group->sum('Inter_Class_Cross_Subsidy'),3),2),
                 'MCC_CAPEX' => round(round($group->sum('Members_Contributed_Capital'),3),2),
                 'LFLN_SUB' => round(round($group->sum('Lifeline_Rate_Subsidy'),3),2),
                 'LFLN_DISC' => round(round($group->sum('Lifeline_Rate_Discount'),3),2),
                 'SEN_CIT_DISC_SUB' => round(round($group->sum('Senior_Citizen_Subsidy'),3),2),
                 'LOAN_COND' => round(round($group->sum('lOAN_COND'),3),2),
                 'LOAN_COND_FIX' => round(round($group->sum('lOAN_COND_FIX'),3),2),
                 /* ------------------------------------------- VALUE ADDED TAX ------------------------------------------------------*/
                 'GEN_VAT' => round(round($group->sum('Generation'),3),2),
                 'TRANSYS_VAT' => round(round($group->sum('Transmission_System'),3),2),
                 'TRANS_DEM_VAT' => round(round($group->sum('Transmission_Demand'),3),2),
                 'DISTSYS_VAT' => round(round($group->sum('Distribution_System'),3),2),
                 'DIST_DEM_VAT' => round(round($group->sum('Distribution_Demand'),3),2),
                 // 'Others' => round($group->first()->br_sysloss_rate * $group->sum('mr_kwh_used'),2,),
                 'LOANCOND_VAT' => round(round($group->sum('Loan_Condonation_KWH'),3),2),
                 'LOANCOND_FIX_VAT' => round(round($group->sum('Loan_Condonation_Fix'),3),2),
                 'PAR_VAT'=>round(round($group->sum('Power_Act_Red_Vat'),3),2), //new
                 'SUPSYS_VAT'=>round(round($group->sum('Supply_Sys_Vat'),3),2), //new
                 'SUPFIX_VAT'=>round(round($group->sum('Supply_Fix_Vat'),3),2), //new
                 'METSYS_VAT'=>round(round($group->sum('Meter_Sys_Vat'),3),2), //new
                 'METFIX_VAT'=>round(round($group->sum('Meter_Fix_Vat'),3),2), //new
                 'SL_VAT'=>round(round($group->sum('System_Loss'),3),2), //new
                 'LFLN_VAT'=>round(round($group->sum('lfln_disc_subs_vat'),3),2), //new
                 'OTHER_VAT'=>0, //new
            ];
        });
        $total = [
            'COUNT' => round($mapped->sum('COUNT'),2),
            'KWH_USED' => round($mapped->sum('KWH_USED'),2),
            'BILL_AMOUNT' => round($mapped->sum('BILL_AMOUNT'),2),
            'KW_SOLD' => round($mapped->sum('KW_SOLD'),2),
            'DEM_AMOUNT' => round($mapped->sum('DEM_AMOUNT'),2),
            'GENSYS' => round($mapped->sum('GENSYS'),2),
            'PAR' => round($mapped->sum('PAR'),2),
            'FBHC' => round($mapped->sum('FBHC'),2),
            'FOREX' => round($mapped->sum('FOREX'),2),
            'TRANSYS_DEMAND' => round($mapped->sum('TRANSYS_DEMAND'),2),
            'TRANSYS' => round($mapped->sum('TRANSYS'),2),
            'SL' => round($mapped->sum('SL'),2),
            'DIST_DEMAND' => round($mapped->sum('DIST_DEMAND'),2),
            'DISTSYS' => round($mapped->sum('DISTSYS'),2),
            'SUPPLYFIX' => round($mapped->sum('SUPPLYFIX'),2),
            'SUPPLYSYS' => round($mapped->sum('SUPPLYSYS'),2),
            'METSYS' => round($mapped->sum('METSYS'),2),
            'METFIX' => round($mapped->sum('METFIX'),2),
            'ME_SPUG' => round($mapped->sum('ME_SPUG'),2),
            'ME_RED' => round($mapped->sum('ME_RED'),2),
            'UC_EC' => round($mapped->sum('UC_EC'),2),
            'EC_ETR' => round($mapped->sum('EC_ETR'),2),
            'NPC_SCC' => round($mapped->sum('NPC_SCC'),2),
            'NPC_SD' => round($mapped->sum('NPC_SD'),2),
            'FIT_ALL' => round($mapped->sum('FIT_ALL'),2),
            'ICCS' => round($mapped->sum('ICCS'),2),
            'MCC_CAPEX' => round($mapped->sum('MCC_CAPEX'),2),
            'LFLN_SUB' => round($mapped->sum('LFLN_SUB'),2),
            'LFLN_DISC' => round($mapped->sum('LFLN_DISC'),2),
            'SEN_CIT_DISC_SUB' => round($mapped->sum('SEN_CIT_DISC_SUB'),2),
            'LOAN_COND' => round($mapped->sum('LOAN_COND'),2),
            'LOAN_COND_FIX' => round($mapped->sum('LOAN_COND_FIX'),2),
            'GEN_VAT' => round($mapped->sum('GEN_VAT'),2),
            'TRANSYS_VAT' => round($mapped->sum('TRANSYS_VAT'),2),
            'TRANS_DEM_VAT' => round($mapped->sum('TRANS_DEM_VAT'),2),
            'DISTSYS_VAT' => round($mapped->sum('DISTSYS_VAT'),2),
            'DIST_DEM_VAT' => round($mapped->sum('DIST_DEM_VAT'),2),
            'LOANCOND_VAT' => round($mapped->sum('LOANCOND_VAT'),2),
            'LOANCOND_FIX_VAT' => round($mapped->sum('LOANCOND_FIX_VAT'),2),
            'PAR_VAT' => round($mapped->sum('PAR_VAT'),2),
            'SUPSYS_VAT' => round($mapped->sum('SUPSYS_VAT'),2),
            'SUPFIX_VAT' => round($mapped->sum('SUPFIX_VAT'),2),
            'METSYS_VAT' => round($mapped->sum('METSYS_VAT'),2),
            'METFIX_VAT' => round($mapped->sum('METFIX_VAT'),2),
            'SL_VAT' => round($mapped->sum('SL_VAT'),2),
            'LFLN_VAT' => round($mapped->sum('LFLN_VAT'),2),
            'OTHER_VAT' => 0,
        ];
        return response([
            'Details'=>$mapped,
            'Totals'=>$total,
        ],200);
    }
    public function lifelineConsDetailedPerRoutePerTown(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        $llineCollection = collect(DB::table('lifeline_rates')->get());

        $llMax = $llineCollection->max()->ll_max_kwh;
        $llMin = $llineCollection->min()->ll_min_kwh;
        // For Dynamic Town and Route
        if($request->route_id){
            $consCollection = collect(DB::table('cons_master as cm')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                ->join('billing_rates as br','mr.br_id','=','br.id')
                ->where('rc.rc_id',$request->route_id)
                ->whereBetween('mr.mr_kwh_used',[$llMin,$llMax])
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->orderBy('cm.cm_account_no','asc')
                ->get());
        }else if($request->town_id){
            $consCollection = collect(DB::table('cons_master as cm')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                ->join('billing_rates as br','mr.br_id','=','br.id')
                ->where('tc.tc_id',$request->town_id)
                ->whereBetween('mr.mr_kwh_used',[$llMin,$llMax])
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->orderBy('cm.cm_account_no','asc')
                ->get());
        }else{
            return response(['Message'=>'Error'],422);
        }
        

        $check = $consCollection->first();
        if(!$check)
        {
            return response(['Message'=>'No Records with the given data'],422);
        }
        
        $mappedConsCollection = $consCollection->map(function($item)use($llineCollection){
            for($i=0;$i<count($llineCollection);$i++)
            {
                //Computation lifeline(Discount)
                $genCharge[$i] = $item->br_gensys_rate * $item->mr_kwh_used;
                $franBenCharge[$i] = $item->br_fbhc_rate * $item->mr_kwh_used;
                $transCharge[$i] = $item->br_transsys_rate * $item->mr_kwh_used;
                $transDemCharge[$i] = $item->br_transdem_rate * $item->mr_kwh_used;
                $syslossCharge[$i] = $item->br_sysloss_rate * $item->mr_kwh_used;
                $distSysCharge[$i] = $item->br_distsys_rate * $item->mr_kwh_used;
                $distDemCharge[$i] = $item->br_distdem_rate * $item->mr_kwh_used;
                $supFixCharge[$i] = $item->br_suprtlcust_fixed; //fix 0perCst
                $supSysCharge[$i] = $item->br_supsys_rate * $item->mr_kwh_used;
                $meterFixCharge[$i] = $item->br_mtrrtlcust_fixed; //fix 5perCst
                $meterSysCharge[$i] = $item->br_mtrsys_rate * $item->mr_kwh_used;
                $totalCharge[$i] = $genCharge[$i] + $franBenCharge[$i] + $transCharge[$i] + $transDemCharge[$i] + $syslossCharge[$i] + 
                    $distSysCharge[$i] + $distDemCharge[$i] + $supFixCharge[$i] + $supSysCharge[$i] + $meterFixCharge[$i] + $meterSysCharge[$i];
                //min and max
                $discountMin[$i] = $llineCollection[$i]->ll_min_kwh;
                $discountMax[$i] = $llineCollection[$i]->ll_max_kwh;
                if($item->mr_kwh_used >= $discountMin[$i] && $item->mr_kwh_used <= $discountMax[$i])
                {
                    // $disPerc = $llineCollection[$i]->ll_discount;
                    // $calc = 1 - $disPerc;
                    // $discount = round(($item->mr_amount / $calc) * $disPerc,2);
                    $discount = $totalCharge[$i] * $llineCollection[$i]->ll_discount;
                }
            }
            
            return[
                'Account_No'=>$item->cm_account_no,
                'Name'=>$item->cm_full_name,
                'Kwh_Used'=>$item->mr_kwh_used,
                'LDISCOUNT'=>round($discount,2) * -1,
                'Bill_Amount'=>$item->mr_amount,
            ];
        });
        $count = count($consCollection);
        $total = [
            'Total_Consumer'=>$count,
            'Total_Kwh_Used'=>$mappedConsCollection->sum('Kwh_Used'),
            'Total_LDiscount'=>round($mappedConsCollection->sum('LDISCOUNT'),2),
            'Total_Bill_Amount'=>round($mappedConsCollection->sum('Bill_Amount'),2)
        ];
        if($request->route_id){
            return response([
                'Area'=> '0'.$check->ac_id.' '.$check->ac_name,
                'Town'=> $check->tc_code.' '.$check->tc_name,
                'Route'=> $check->rc_code.' '.$check->rc_desc,
                'Lifeline_Consumer'=>$mappedConsCollection,
                'Total'=>$total
            ],200);
        }else if($request->town_id){
            return response([
                'Area'=> '0'.$check->ac_id.' '.$check->ac_name,
                'Town'=> $check->tc_code.' '.$check->tc_name,
                'Lifeline_Consumer'=>$mappedConsCollection,
                'Total'=>$total
            ],200);
        }else{
            return response(['Message'=>'Error'],422);
        }
    }
    public function consLargeLoad($billPeriod)
    {
        $billingPeriod = str_replace("-","",$billPeriod);
        $largeLoad = collect(DB::table('cons_master as cm')
            ->join('sales as s','cm.cm_id','=','s.cm_id')
            ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->select(DB::raw('count(*) as count,sum(mr.mr_kwh_used) as kwh,sum(s.s_bill_amount) as bill'),'ct.ct_desc',
                'br.br_gensys_rate','br.br_transsys_rate','br.br_distsys_rate','br.br_sysloss_rate','br.br_distdem_rate',
                'br.br_transdem_rate','br.br_loancon_rate_kwh','br.br_loancon_rate_fix','mr.mr_kwh_used',)
            ->where('cm.large_load',1)
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->where('mr.mr_status',1)
            ->where('mr.mr_printed',1)
            ->groupBy('ct.ct_id')
            ->orderBy('ct.ct_id','asc')
            ->get());
        $check = $largeLoad->first();
        if(!$check)
        {
            return response(['Message'=>'No Records Found'],422);
        }
        
        $mapped = $largeLoad->map(function($items){
            return[
                'Type_Consumer'=>$items->ct_desc,
                'Count'=>$items->count,
                'KWH_Used'=>$items->kwh,
                'Bill_amount'=>round($items->bill,2),
                'Generation'=>round($items->br_gensys_rate * $items->mr_kwh_used,2),
                'Transmission'=>round($items->br_transsys_rate * $items->mr_kwh_used,2),
                'Distribution'=>round($items->br_distsys_rate * $items->mr_kwh_used,2),
                'Sys_Loss'=>round($items->br_sysloss_rate * $items->mr_kwh_used,2),
                'Dist_Dem'=>round($items->br_distdem_rate * $items->mr_kwh_used,2),
                'Trans_Dem'=>round($items->br_transdem_rate * $items->mr_kwh_used,2),
                'L_Cond_KWH'=>round($items->br_loancon_rate_kwh * $items->mr_kwh_used,2),
                'L_Cond_Fix'=>round($items->br_loancon_rate_fix * $items->mr_kwh_used,2),
            ];
        });

        $total = collect([
            'Count'=>$mapped->sum('Count'),
            'KWH_Used'=>round($mapped->sum('KWH_Used'),2),
            'Bill_amount'=>round($mapped->sum('Bill_amount'),2),
            'Generation'=>round($mapped->sum('Generation'),2),
            'Transmission'=>round($mapped->sum('Transmission'),2),
            'Distribution'=>round($mapped->sum('Distribution'),2),
            'Sys_Loss'=>round($mapped->sum('Sys_Loss'),2),
            'Dist_Dem'=>round($mapped->sum('Dist_Dem'),2),
            'Trans_Dem'=>round($mapped->sum('Trans_Dem'),2),
            'L_Cond_KWH'=>round($mapped->sum('L_Cond_KWH'),2),
            'L_Cond_Fix'=>round($mapped->sum('L_Cond_Fix'),2),
        ]);
        

        
        return response([
            'Consumer_Large_Load'=>$mapped,
            'Total'=>$total
        ],200);
    }
    public function increaseConsumption(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->bill_period);
        $consumption = collect(DB::table('meter_reg as mr')
            ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->where('mr.mr_printed',1)
            ->where('cm.rc_id',$request->route_id)
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->orderBy('cm.cm_account_no','desc')
            ->get());

        $check = $consumption->first();
        if(!$check)
        {
            return response(['Message'=>'No Records Found'],422);
        }
        $consumptionMapped = $consumption->map(function($item){
            $getPrev = DB::table('meter_reg as mr')
            ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->where('cm.rc_id',$item->rc_id)
            ->where('cm.cm_id',$item->cm_id)
            ->where('mr.mr_date_year_month','<',$item->mr_date_year_month)
            ->orderBy('mr.mr_date_year_month','desc')
            ->first();
            if($getPrev)
            {
                $accountNo = $getPrev->cm_account_no;
                $type = $getPrev->ct_code;
                $name = $getPrev->cm_full_name;
                $current = $item->mr_kwh_used;
                $currentBP = $item->mr_date_year_month;
                $previous = $getPrev->mr_kwh_used;
                $previousBP = $getPrev->mr_date_year_month;
            }else{
                return [];
            }
            return[
                'Account_No'=>$accountNo,
                'Type'=>$type,
                'Name'=>$name,
                'Current'=>$current,
                'Current_Bill_Period'=>$currentBP,
                'Previous'=>$previous,
                'Previous_Bill_Period'=>$previousBP,
            ];
        });
        //removing Empty array
        $filtered = $consumptionMapped->filter(function ($value, $key) {
            return !empty($value) ;
        });
        
        $kwhFrom = $request->kwh_from;
        $kwhTo = $request->kwh_to;
        $count = $filtered->count();
        for($i=1;$i<=$count;$i++)
        {
            
            if($filtered[$i]['Previous'] <  $filtered[$i]['Current'])
            {
                $resultKWH[$i] = ($filtered[$i]['Previous'] - $filtered[$i]['Current']) * -1;
                if($resultKWH[$i] > $kwhFrom && $resultKWH[$i] <= $kwhTo)
                {
                    $finalForm[$i] = collect([
                        'Account_No'=> $filtered[$i]['Account_No'],
                        'Type'=>$filtered[$i]['Type'],
                        'Name'=>$filtered[$i]['Name'],
                        'Current'=>$filtered[$i]['Current'],
                        'Current_Bill_Period'=>date('F, Y', strtotime($filtered[$i]['Current_Bill_Period'].'01')),
                        'Previous'=>$filtered[$i]['Previous'],
                        'Previous_Bill_Period'=>date('F, Y', strtotime($filtered[$i]['Previous_Bill_Period'].'01')),
                        'Rate_Increased_KWH'=>$resultKWH[$i],
                    ]);
                }
                
            }
        }
        if(empty($finalForm))
        {
            return response(['Message'=>'No Records Found'],422);
        }
        $totalCurrent = 0;
        $totalPrevious = 0;
        $totalRateIncreasedKWH = 0;
        foreach($finalForm as $item)
        {
            $totalCurrent += $item['Current'];
            $totalPrevious += $item['Previous'];
            $totalRateIncreasedKWH += $item['Rate_Increased_KWH'];
        }
        $totalCollection = collect([
            'Total_Current'=>$totalCurrent,
            'Total_Previous'=>$totalPrevious,
            'Total_Rate_Increased_KWH'=>$totalRateIncreasedKWH,
        ]);


        return response([
            'Area'=> '0'.$check->ac_id.' '.$check->ac_name,
            'Town'=> $check->tc_code.' '.$check->tc_name,
            'Route'=> $check->rc_code.' '.$check->rc_desc,
            'Meter_Reader'=> $request->meter_reader,
            'Cons_Consumption_Details'=>$finalForm,
            'Total_Consumption'=>$totalCollection,
        ],200);

    }
    public function decreaseConsumption(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->bill_period);
        $consumption = collect(DB::table('meter_reg as mr')
            ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->where('mr.mr_printed',1)
            ->where('cm.rc_id',$request->route_id)
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->orderBy('cm.cm_account_no','desc')
            ->get());

        $check = $consumption->first();
        if(!$check)
        {
            return response(['Message'=>'No Records with the given data'],422);
        }
        $consumptionMapped = $consumption->map(function($item){
            $getPrev = DB::table('meter_reg as mr')
            ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->where('cm.rc_id',$item->rc_id)
            ->where('cm.cm_id',$item->cm_id)
            ->where('mr.mr_date_year_month','<',$item->mr_date_year_month)
            ->orderBy('mr.mr_date_year_month','desc')
            ->first();
            if($getPrev)
            {
                $accountNo = $getPrev->cm_account_no;
                $type = $getPrev->ct_code;
                $name = $getPrev->cm_full_name;
                $current = $item->mr_kwh_used;
                $currentBP = $item->mr_date_year_month;
                $previous = $getPrev->mr_kwh_used;
                $previousBP = $getPrev->mr_date_year_month;
            }else{
                return [];
            }
            return[
                'Account_No'=>$accountNo,
                'Type'=>$type,
                'Name'=>$name,
                'Current'=>$current,
                'Current_Bill_Period'=>$currentBP,
                'Previous'=>$previous,
                'Previous_Bill_Period'=>$previousBP,
            ];
        });
        //removing Empty array
        $filtered = $consumptionMapped->filter(function ($value, $key) {
            return !empty($value) ;
        });
        
        $kwhFrom = $request->kwh_from;
        $kwhTo = $request->kwh_to;
        $count = $filtered->count();
        for($i=1;$i<=$count;$i++)
        {
            
            if($filtered[$i]['Current'] < $filtered[$i]['Previous'])
            {
                $resultKWH[$i] = ($filtered[$i]['Current'] - $filtered[$i]['Previous']) * -1;
                if($resultKWH[$i] > $kwhFrom && $resultKWH[$i] <= $kwhTo)
                {
                    $finalForm[$i] = collect([
                        'Account_No'=> $filtered[$i]['Account_No'],
                        'Type'=>$filtered[$i]['Type'],
                        'Name'=>$filtered[$i]['Name'],
                        'Current'=>$filtered[$i]['Current'],
                        'Current_Bill_Period'=>date('F, Y', strtotime($filtered[$i]['Current_Bill_Period'].'01')),
                        'Previous'=>$filtered[$i]['Previous'],
                        'Previous_Bill_Period'=>date('F, Y', strtotime($filtered[$i]['Previous_Bill_Period'].'01')),
                        'Rate_Decreased_KWH'=>$resultKWH[$i],
                    ]);
                }
                
            }
        }
        if(empty($finalForm))
        {
            return response(['Message'=>'No Records Found'],422);
        }
        $totalCurrent = 0;
        $totalPrevious = 0;
        $totalRateIncreasedKWH = 0;
        foreach($finalForm as $item)
        {
            $totalCurrent += $item['Current'];
            $totalPrevious += $item['Previous'];
            $totalRateIncreasedKWH += $item['Rate_Decreased_KWH'];
        }
        $totalCollection = collect([
            'Total_Current'=>$totalCurrent,
            'Total_Previous'=>$totalPrevious,
            'Total_Rate_Increased_KWH'=>$totalRateIncreasedKWH,
        ]);


        return response([
            'Area'=> '0'.$check->ac_id.' '.$check->ac_name,
            'Town'=> $check->tc_code.' '.$check->tc_name,
            'Route'=> $check->rc_code.' '.$check->rc_desc,
            'Meter_Reader'=> $request->meter_reader,
            'Cons_Consumption_Details'=>$finalForm,
            'Total_Consumption'=>$totalCollection,
        ],200);
    }
    public function customerSalesPerCharge($date)
    {
        $lifeline = DB::table('lifeline_rates')
            ->max('ll_max_kwh');
        $billingPeriod = str_replace("-","",$date);
        $consDetails = collect(DB::table('cons_master as cm')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            // ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id') //new join for rates bases calculation
            // ->where('mr.mr_kwh_used','>',$lifeline)
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->where('mr.mr_printed',1)
            ->get());
        
        // dd($consDetails2);
        if(!$consDetails->first()){
            return response(['Message'=>'No Records Found'],422);
        }
        
        /*-------------------------------------------------------- All Consumer Types -----------------------------------------------------------*/
        // GET RATES from service for all ConsumerType
        $loc = '';
        $data = (new GetRateService())->GetRate($billingPeriod,$loc);
        // dd($data);
        $newData = collect($data->groupBy('Ct_Desc'));
        // $newData = $consDetails->groupBy('ct_desc');
        $mapped = $newData->map(function($group){
            return[
                 /*-------------------------------------------- GENERATION CHARGES -------------------------------------------------------*/
                 'Generation_System_Charge' => round(round($group->sum('Generation_System_Charge'),3),2),
                 'Power_Act_Reduction' => round(round($group->sum('Power_Act_Reduction'),3),2),
                 'Franchise_Benefits_To_Host' => round(round($group->sum('Franchise_Benefits_To_Host'),3),2),
                 'FOREX_Adjustment_Charge' => round(round($group->sum('FOREX_Adjustment_Charge'),3),2),
                 /* ------------------------------------------- TRANSMISSION CHARGES  ------------------------------------------------------*/
                 'Trans_Demand_Charge' => round(round($group->sum('Trans_Demand_Charge'),3),2),
                 'Transmission_System_Charge' => round(round($group->sum('Transmission_System_Charge'),3),2),
                 'System_Loss_Charge' => round(round($group->sum('System_Loss_Charge'),3),2),
                 /* ------------------------------------------- DISTRIBUTION CHARGES ------------------------------------------------------*/
                 'Dist_Demand_Charge' => round(round($group->sum('Dist_Demand_Charge'),3),2),
                 'Distribution_System_Charge' => round(round($group->sum('Distribution_System_Charge'),3),2),
                 'Supply_System_Fixed_Charge' => round(round($group->sum('Supply_System_Fixed_Charge'),3),2),
                 'Supply_System_Charge' => round($group->sum('Supply_System_Charge'),2),
                 'Retail_Customer_Meter_Charge' => round(round($group->sum('Retail_Customer_Meter_Charge'),3),2),
                 'Retail_Customer_Mtr_Fixed_Charge' => round(round($group->sum('Retail_Customer_Mtr_Fixed_Charge'),3),2),
                 /* ------------------------------------------- UNIVERSAL CHARGES ------------------------------------------------------*/
                 'UC_SPUG' => round(round($group->sum('UC_SPUG'),3),2),
                 'UC_RED_Cash_Incentive' => round(round($group->sum('UC_RED_Cash_Incentive'),3),2),
                 'UC_Environmental_Charge' => round(round($group->sum('UC_Environmental_Charge'),3),2),
                 'UC_Equal_of_Taxes_Royalties' => round(round($group->sum('UC_Equal_of_Taxes_Royalties'),3),2),
                 'UC_NPC_Stranded_Contract_Cost' => round(round($group->sum('UC_NPC_Stranded_Contract_Cost'),3),2),
                 'UC_NPC_Stranded_Debt_Cost' => round(round($group->sum('UC_NPC_Stranded_Debt_Cost'),3),2),
                 /* ------------------------------------------- OTHER CHARGES ------------------------------------------------------*/
                 'Inter_Class_Cross_Subsidy' => round(round($group->sum('Inter_Class_Cross_Subsidy'),3),2),
                 // 'Inter Class Corss Subsidy Adj.' => round($group->first()->br_sysloss_rate * $group->sum('mr_kwh_used'),2,),
                 'Members_Contributed_Capital' => round(round($group->sum('Members_Contributed_Capital'),3),2),
                 'Lifeline_Rate_Subsidy' => round(round($group->sum('Lifeline_Rate_Subsidy'),3),2),
                 'Lifeline_Rate_Discount' => round($group->sum('Lifeline_Rate_Discount'),2),
                 // 'Transformer Losses' => round($group->first()->br_sysloss_rate * $group->sum('mr_kwh_used'),2,),
                 // 'BackBill_Rebates_Refund' => round($group->first()->br_sysloss_rate * $group->sum('mr_kwh_used'),2,),
                 'Senior_Citizen_Subsidy' => round(round($group->sum('Senior_Citizen_Subsidy'),3),2),
                 // 'Senior Citizen (Discount)' => round($group->first()->br_sysloss_rate * $group->sum('mr_kwh_used'),2,),
                 'Feed_in_Tariff_Allowance' => round(round($group->sum('Feed_in_Tariff_Allowance'),3),2),
                 // 'Prompt Payment Discount Adj' => round($group->first()->br_sysloss_rate * $group->sum('mr_kwh_used'),2,),
                 /* ------------------------------------------- VALUE ADDED TAX ------------------------------------------------------*/
                 'Generation_Vat' => round(round($group->sum('Generation'),3),2),
                 'Transmission_System_Vat' => round(round($group->sum('Transmission_System'),3),2),
                 'Transmission_Demand_Vat' => round(round($group->sum('Transmission_Demand'),3),2),
                 'Distribution_System_Vat' => round(round($group->sum('Distribution_System'),3),2),
                 'Distribution_Demand_Vat' => round(round($group->sum('Distribution_Demand'),3),2),
                 // 'Others' => round($group->first()->br_sysloss_rate * $group->sum('mr_kwh_used'),2,),
                 'Loan_Condonation_KWH_Vat' => round(round($group->sum('Loan_Condonation_KWH'),3),2),
                 'Loan_Condonation_Fix_Vat' => round(round($group->sum('Loan_Condonation_Fix'),3),2),
                 'Power_Act_Red_Vat'=>round(round($group->sum('Power_Act_Red_Vat'),3),2), //new
                 'Supply_Sys_Vat'=>round(round($group->sum('Supply_Sys_Vat'),3),2), //new
                 'Supply_Fix_Vat'=>round(round($group->sum('Supply_Fix_Vat'),3),2), //new
                 'Meter_Sys_Vat'=>round(round($group->sum('Meter_Sys_Vat'),3),2), //new
                 'Meter_Fix_Vat'=>round(round($group->sum('Meter_Fix_Vat'),3),2), //new
                 'Sys_Loss_Vat'=>round(round($group->sum('System_Loss'),3),2), //new
                 'lfln_disc_subs_vat'=>round(round($group->sum('lfln_disc_subs_vat'),3),2), //new
                 'amount'=>$group->sum('BILL_AMOUNT'),
            ];
        });

        // dd($mapped);
        /*------------------------------------------------------------ LIFELINER STARTS HERE-----------------------------------------------------------*/
        $lifelinerResedetial = $consDetails->where('ct_id',8)->where('mr_kwh_used','<=',$lifeline);
        $lifelinerResedetial->all();
        // dd($lifelinerResedetial);
        $lifelinerResedentialConsumer = collect([
            /*-------------------------------------------------- GENERATION CHARGES -------------------------------------------------------*/
                'Generation_System_Charge' => 0,
                'Power_Act_Reduction' => 0,
                'Franchise_Benefits_To_Host' =>0,
                'FOREX_Adjustment_Charge' => 0,
                /* ------------------------------------------- TRANSMISSION CHARGES  ------------------------------------------------------*/
                'Trans_Demand_Charge' => 0,
                'Transmission_System_Charge' => 0,
                'System_Loss_Charge' =>0,
                /* ------------------------------------------- DISTRIBUTION CHARGES ------------------------------------------------------*/
                'Dist_Demand_Charge' => 0,
                'Distribution_System_Charge' => 0,
                'Supply_System_Fixed_Charge' => 0,
                'Supply_System_Charge' =>0,
                'Retail_Customer_Meter_Charge' =>0,
                'Retail_Customer_Mtr_Fixed_Charge' => 0,
                /* ------------------------------------------- UNIVERSAL CHARGES ------------------------------------------------------*/
                'UC_SPUG' => 0,
                'UC_RED_Cash_Incentive' => 0,
                'UC_Environmental_Charge' =>0,
                'UC_Equal_of_Taxes_Royalties' => 0,
                'UC_NPC_Stranded_Contract_Cost' => 0,
                'UC_NPC_Stranded_Debt_Cost' => 0,
                /* ------------------------------------------- OTHER CHARGES ------------------------------------------------------*/
                'Inter_Class_Cross_Subsidy' =>0,
                // 'Inter_Class_Corss_Subsidy_Adj.' => 0,
                'Members_Contributed_Capital' => 0,
                'Lifeline_Rate_Subsidy' => 0,
                'Lifeline_Rate_Discount' => 0,
                // 'Transformer_Losses' => 0,
                // 'BackBill_Rebates_Refund' => 0,
                'Senior_Citizen_Subsidy' => 0,
                // 'Senior_Citizen_Discount' => 0,
                'Feed_in_Tariff_Allowance' => 0,
                // 'Prompt_Payment_Discount_Adj' => 0,
                /* ------------------------------------------- VALUE ADDED TAX ------------------------------------------------------*/
                'Generation_Vat' => 0,
                'Transmission_System_Vat' => 0,
                'Transmission_Demand_Vat' => 0,
                'Distribution_System_Vat' => 0,
                'Distribution_Demand_Vat' => 0,
                'System_Loss_Vat' => 0,
                // 'Others' => round($group->first()->br_sysloss_rate * $group->sum('mr_kwh_used'),2,),
                'Power_Act_Red_Vat'=>0, //new
                'Supply_Fix_Vat'=>0, //new
                'Supply_Sys_Vat'=>0, //new
                'Meter_Fix_Vat'=>0, //new
                'Meter_Sys_Vat'=>0, //new
                'lfln_disc_subs_vat'=>0, //new
                'Loan_Condonation_KWH_Vat' => 0,
                'Loan_Condonation_Fix_Vat' => 0,
        ]);
        foreach($lifelinerResedetial as $item)
        {
            /*---------------------------------------------------- GENERATION CHARGES -------------------------------------------------------*/
            $lifelinerResedentialConsumer['Generation_System_Charge'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_gensys_rate,3),2);
            $lifelinerResedentialConsumer['Power_Act_Reduction'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_par_rate,3),2);
            $lifelinerResedentialConsumer['Franchise_Benefits_To_Host'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_fbhc_rate,3),2);
            $lifelinerResedentialConsumer['FOREX_Adjustment_Charge'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_forex_rate,3),2);
            /* -------------------------------------------------- TRANSMISSION CHARGES  ------------------------------------------------------*/
            $lifelinerResedentialConsumer['Trans_Demand_Charge'] += round(round($item->mr_dem_kwh_used * $lifelinerResedetial->first()->br_transdem_rate,3),2);
            $lifelinerResedentialConsumer['Transmission_System_Charge'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_transsys_rate,3),2);
            $lifelinerResedentialConsumer['System_Loss_Charge'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_sysloss_rate,3),2);
            /* ------------------------------------------------- DISTRIBUTION CHARGES ------------------------------------------------------*/
            $lifelinerResedentialConsumer['Dist_Demand_Charge'] += round(round($item->mr_dem_kwh_used * $lifelinerResedetial->first()->br_distdem_rate,3),2);
            $lifelinerResedentialConsumer['Distribution_System_Charge'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_distsys_rate,3),2);
            $lifelinerResedentialConsumer['Supply_System_Fixed_Charge'] += round(round($lifelinerResedetial->first()->br_suprtlcust_fixed,3),2);
            $lifelinerResedentialConsumer['Supply_System_Charge'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_supsys_rate,3),2);
            $lifelinerResedentialConsumer['Retail_Customer_Meter_Charge'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_mtrsys_rate,3),2);
            $lifelinerResedentialConsumer['Retail_Customer_Mtr_Fixed_Charge'] += round(round($lifelinerResedetial->first()->br_mtrrtlcust_fixed,3),2);
             /* -------------------------------------------------- UNIVERSAL CHARGES ------------------------------------------------------*/
            $lifelinerResedentialConsumer['UC_SPUG'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_uc4_miss_rate_spu,3),2);
            $lifelinerResedentialConsumer['UC_RED_Cash_Incentive'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_uc4_miss_rate_red,3),2);
            $lifelinerResedentialConsumer['UC_Environmental_Charge'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_uc6_envi_rate,3),2);
            $lifelinerResedentialConsumer['UC_Equal_of_Taxes_Royalties'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_uc5_equal_rate,3),2);
            $lifelinerResedentialConsumer['UC_NPC_Stranded_Contract_Cost'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_uc2_npccon_rate,3),2);
            $lifelinerResedentialConsumer['UC_NPC_Stranded_Debt_Cost'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_uc1_npcdebt_rate,3),2);
            /* --------------------------------------------------- OTHER CHARGES ------------------------------------------------------*/
            $lifelinerResedentialConsumer['Inter_Class_Cross_Subsidy'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_intrclscrssubrte,3),2);
            //$lifelinerResedentialConsumer['Inter_Class_Corss_Subsidy_Adj'] += round($item->mr_kwh_used * $lifelinerResedetial->first()->br_sysloss_rate,2);
            $lifelinerResedentialConsumer['Members_Contributed_Capital'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_capex_rate,3),2);
            $lifelinerResedentialConsumer['Lifeline_Rate_Subsidy'] += 0;
            $lifelinerResedentialConsumer['Lifeline_Rate_Discount'] += round(round(round($item->mr_lfln_disc * -1,2),3),2);
            //$lifelinerResedentialConsumer['Transformer_Losses'] += round($item->mr_kwh_used * $lifelinerResedetial->first()->br_sysloss_rate,2);
            //$lifelinerResedentialConsumer['BackBill_Rebates_Refund'] += round($item->mr_kwh_used * $lifelinerResedetial->first()->br_sysloss_rate,2);
            $lifelinerResedentialConsumer['Senior_Citizen_Subsidy'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_sc_subs_rate,3),2);
            // $lifelinerResedentialConsumer['Senior_Citizen_Discount'] += round($item->mr_kwh_used * $lifelinerResedetial->first()->br_sysloss_rate,2);
            $lifelinerResedentialConsumer['Feed_in_Tariff_Allowance'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_fit_all,3),2);
            // $lifelinerResedentialConsumer['Prompt_Payment_Discount_Adj'] += round($item->mr_kwh_used * $lifelinerResedetial->first()->br_sysloss_rate,2);
            /* ------------------------------------------------------- VALUE ADDED TAX ------------------------------------------------------*/
            $lifelinerResedentialConsumer['Generation_Vat'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_vat_gen,3),2);
            $lifelinerResedentialConsumer['Transmission_System_Vat'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_vat_trans,3),2);
            $lifelinerResedentialConsumer['Transmission_Demand_Vat'] += round(round($item->mr_dem_kwh_used * $lifelinerResedetial->first()->br_vat_transdem,3),2);
            $lifelinerResedentialConsumer['Distribution_System_Vat'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_vat_distrib_kwh,3),2);
            $lifelinerResedentialConsumer['Distribution_Demand_Vat'] += round(round($item->mr_dem_kwh_used * $lifelinerResedetial->first()->br_vat_distdem,3),2);
            $lifelinerResedentialConsumer['System_Loss_Vat'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_vat_systloss,3),2);
            $lifelinerResedentialConsumer['Power_Act_Red_Vat'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_vat_par,3),2); //new
            $lifelinerResedentialConsumer['Supply_Fix_Vat'] += round(round($lifelinerResedetial->first()->br_vat_supfix,3),2); //new
            $lifelinerResedentialConsumer['Supply_Sys_Vat'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_vat_supsys,3),2); //new
            $lifelinerResedentialConsumer['Meter_Fix_Vat'] += round(round($lifelinerResedetial->first()->br_vat_mtr_fix,3),2); //new
            $lifelinerResedentialConsumer['Meter_Sys_Vat'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_vat_metersys,3),2); //new
            $lifelinerResedentialConsumer['lfln_disc_subs_vat'] += 0; //new
            // $lifelinerResedentialConsumer['Others'] += round($item->mr_kwh_used * $lifelinerResedetial->first()->br_sysloss_rate,2);
            $lifelinerResedentialConsumer['Loan_Condonation_KWH_Vat'] += round(round($item->mr_kwh_used * $lifelinerResedetial->first()->br_vat_loancondo,3),2);
            $lifelinerResedentialConsumer['Loan_Condonation_Fix_Vat'] += round(round($lifelinerResedetial->first()->br_vat_loancondofix,3),2);
        }
        /*------------------------------------------------------------ LIFELINER ENDS HERE -----------------------------------------------------------*/
        /*------------------------------------------------------------ NON-LIFELINER STARTS HERE -----------------------------------------------------------*/
        $nonLifeliner = $consDetails->where('ct_id',8)->where('mr_kwh_used','>',$lifeline);
        $nonLifeliner->all();
        $nonlifelinerResedentialConsumer = collect([
            /*-------------------------------------------------- GENERATION CHARGES -------------------------------------------------------*/
            'Generation_System_Charge' => 0,
            'Power_Act_Reduction' => 0,
            'Franchise_Benefits_To_Host' =>0,
            'FOREX_Adjustment_Charge' => 0,
            /* ------------------------------------------- TRANSMISSION CHARGES  ------------------------------------------------------*/
            'Trans_Demand_Charge' => 0,
            'Transmission_System_Charge' => 0,
            'System_Loss_Charge' =>0,
            /* ------------------------------------------- DISTRIBUTION CHARGES ------------------------------------------------------*/
            'Dist_Demand_Charge' => 0,
            'Distribution_System_Charge' => 0,
            'Supply_System_Fixed_Charge' => 0,
            'Supply_System_Charge' =>0,
            'Retail_Customer_Meter_Charge' =>0,
            'Retail_Customer_Mtr_Fixed_Charge' => 0,
            /* ------------------------------------------- UNIVERSAL CHARGES ------------------------------------------------------*/
            'UC_SPUG' => 0,
            'UC_RED_Cash_Incentive' => 0,
            'UC_Environmental_Charge' =>0,
            'UC_Equal_of_Taxes_Royalties' => 0,
            'UC_NPC_Stranded_Contract_Cost' => 0,
            'UC_NPC_Stranded_Debt_Cost' => 0,
            /* ------------------------------------------- OTHER CHARGES ------------------------------------------------------*/
            'Inter_Class_Cross_Subsidy' =>0,
            // 'Inter_Class_Corss_Subsidy_Adj.' => 0,
            'Members_Contributed_Capital' => 0,
            'Lifeline_Rate_Subsidy' => 0,
            'Lifeline_Rate_Discount' => 0,
            // 'Transformer_Losses' => 0,
            // 'BackBill_Rebates_Refund' => 0,
            'Senior_Citizen_Subsidy' => 0,
            // 'Senior_Citizen_Discount' => 0,
            'Feed_in_Tariff_Allowance' => 0,
            // 'Prompt_Payment_Discount_Adj' => 0,
            /* ------------------------------------------- VALUE ADDED TAX ------------------------------------------------------*/
            'Generation_Vat' => 0,
            'Transmission_System_Vat' => 0,
            'Transmission_Demand_Vat' => 0,
            'Distribution_System_Vat' => 0,
            'Distribution_Demand_Vat' => 0,
            'System_Loss_Vat' => 0,
            // 'Others' => round($group->first()->br_sysloss_rate * $group->sum('mr_kwh_used'),2,),
            'Power_Act_Red_Vat'=>0, //new
            'Supply_Fix_Vat'=>0, //new
            'Supply_Sys_Vat'=>0, //new
            'Meter_Fix_Vat'=>0, //new
            'Meter_Sys_Vat'=>0, //new
            'lfln_disc_subs_vat'=>0, //new
            'Loan_Condonation_KWH_Vat' => 0,
            'Loan_Condonation_Fix_Vat' => 0,
        ]);
        foreach($nonLifeliner as $item)
        {
            /*---------------------------------------------------- GENERATION CHARGES -------------------------------------------------------*/
            $nonlifelinerResedentialConsumer['Generation_System_Charge'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_gensys_rate,3),2);
            $nonlifelinerResedentialConsumer['Power_Act_Reduction'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_par_rate,3),2);
            $nonlifelinerResedentialConsumer['Franchise_Benefits_To_Host'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_fbhc_rate,3),2);
            $nonlifelinerResedentialConsumer['FOREX_Adjustment_Charge'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_forex_rate,3),2);
            /* -------------------------------------------------- TRANSMISSION CHARGES  ------------------------------------------------------*/
            $nonlifelinerResedentialConsumer['Trans_Demand_Charge'] += round(round($item->mr_dem_kwh_used * $nonLifeliner->first()->br_transdem_rate,3),2);
            $nonlifelinerResedentialConsumer['Transmission_System_Charge'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_transsys_rate,3),2);
            $nonlifelinerResedentialConsumer['System_Loss_Charge'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_sysloss_rate,3),2);
            /* ------------------------------------------------- DISTRIBUTION CHARGES ------------------------------------------------------*/
            $nonlifelinerResedentialConsumer['Dist_Demand_Charge'] += round(round($item->mr_dem_kwh_used * $nonLifeliner->first()->br_distdem_rate,3),2);
            $nonlifelinerResedentialConsumer['Distribution_System_Charge'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_distsys_rate,3),2);
            $nonlifelinerResedentialConsumer['Supply_System_Fixed_Charge'] += round(round($nonLifeliner->first()->br_suprtlcust_fixed,3),2);
            $nonlifelinerResedentialConsumer['Supply_System_Charge'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_supsys_rate,3),2);
            $nonlifelinerResedentialConsumer['Retail_Customer_Meter_Charge'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_mtrsys_rate,3),2);
            $nonlifelinerResedentialConsumer['Retail_Customer_Mtr_Fixed_Charge'] += round(round($nonLifeliner->first()->br_mtrrtlcust_fixed,3),2);
             /* -------------------------------------------------- UNIVERSAL CHARGES ------------------------------------------------------*/
            $nonlifelinerResedentialConsumer['UC_SPUG'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_uc4_miss_rate_spu,3),2);
            $nonlifelinerResedentialConsumer['UC_RED_Cash_Incentive'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_uc4_miss_rate_red,3),2);
            $nonlifelinerResedentialConsumer['UC_Environmental_Charge'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_uc6_envi_rate,3),2);
            $nonlifelinerResedentialConsumer['UC_Equal_of_Taxes_Royalties'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_uc5_equal_rate,3),2);
            $nonlifelinerResedentialConsumer['UC_NPC_Stranded_Contract_Cost'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_uc2_npccon_rate,3),2);
            $nonlifelinerResedentialConsumer['UC_NPC_Stranded_Debt_Cost'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_uc1_npcdebt_rate,3),2);
            /* --------------------------------------------------- OTHER CHARGES ------------------------------------------------------*/
            $nonlifelinerResedentialConsumer['Inter_Class_Cross_Subsidy'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_intrclscrssubrte,3),2);
            //$nonlifelinerResedentialConsumer['Inter_Class_Corss_Subsidy_Adj'] += round($item->mr_kwh_used * $nonLifeliner->first()->br_sysloss_rate,2);
            $nonlifelinerResedentialConsumer['Members_Contributed_Capital'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_capex_rate,3),2);
            $nonlifelinerResedentialConsumer['Lifeline_Rate_Subsidy'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_lfln_subs_rate,3),2);
            $nonlifelinerResedentialConsumer['Lifeline_Rate_Discount'] += 0;
            //$nonlifelinerResedentialConsumer['Transformer_Losses'] += round($item->mr_kwh_used * $nonLifeliner->first()->br_sysloss_rate,2);
            //$nonlifelinerResedentialConsumer['BackBill_Rebates_Refund'] += round($item->mr_kwh_used * $nonLifeliner->first()->br_sysloss_rate,2);
            $nonlifelinerResedentialConsumer['Senior_Citizen_Subsidy'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_sc_subs_rate,3),2);
            // $nonlifelinerResedentialConsumer['Senior_Citizen_Discount'] += round($item->mr_kwh_used * $nonLifeliner->first()->br_sysloss_rate,2);
            $nonlifelinerResedentialConsumer['Feed_in_Tariff_Allowance'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_fit_all,3),2);
            // $nonlifelinerResedentialConsumer['Prompt_Payment_Discount_Adj'] += round($item->mr_kwh_used * $nonLifeliner->first()->br_sysloss_rate,2);
            /* ------------------------------------------------------- VALUE ADDED TAX ------------------------------------------------------*/
            $nonlifelinerResedentialConsumer['Generation_Vat'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_vat_gen,3),2);
            $nonlifelinerResedentialConsumer['Transmission_System_Vat'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_vat_trans,3),2);
            $nonlifelinerResedentialConsumer['Transmission_Demand_Vat'] += round(round($item->mr_dem_kwh_used * $nonLifeliner->first()->br_vat_transdem,3),2);
            $nonlifelinerResedentialConsumer['Distribution_System_Vat'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_vat_distrib_kwh,3),2);
            $nonlifelinerResedentialConsumer['Distribution_Demand_Vat'] += round(round($item->mr_dem_kwh_used * $nonLifeliner->first()->br_vat_distdem,3),2);
            $nonlifelinerResedentialConsumer['System_Loss_Vat'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_vat_systloss,3),2);
            $nonlifelinerResedentialConsumer['Power_Act_Red_Vat'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_vat_par,3),2); //new
            $nonlifelinerResedentialConsumer['Supply_Fix_Vat'] += round(round($nonLifeliner->first()->br_vat_supfix,3),2); //new
            $nonlifelinerResedentialConsumer['Supply_Sys_Vat'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_vat_supsys,3),2); //new
            $nonlifelinerResedentialConsumer['Meter_Fix_Vat'] += round(round($nonLifeliner->first()->br_vat_mtr_fix,3),2); //new
            $nonlifelinerResedentialConsumer['Meter_Sys_Vat'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_vat_metersys,3),2); //new
            $nonlifelinerResedentialConsumer['lfln_disc_subs_vat'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_vat_lfln,3),2); //new
            // $nonlifelinerResedentialConsumer['Others'] += round($item->mr_kwh_used * $nonLifeliner->first()->br_sysloss_rate,2);
            $nonlifelinerResedentialConsumer['Loan_Condonation_KWH_Vat'] += round(round($item->mr_kwh_used * $nonLifeliner->first()->br_vat_loancondo,3),2);
            $nonlifelinerResedentialConsumer['Loan_Condonation_Fix_Vat'] += round(round($nonLifeliner->first()->br_vat_loancondofix,3),2);
            /*------------------------------------------------------------ NON-LIFELINER ENDS HERE -----------------------------------------------------------*/
        }
        $newNonlifelinerResedentialConsumer = $nonlifelinerResedentialConsumer->map(function($item){
            return round($item,2);
        });
        $newlifelinerResedentialConsumer = $lifelinerResedentialConsumer->map(function($item){
            return round($item,2);
        });
        return response([
            // 'ALL_Consumer'=>$data->Power_Act_Reduction,
            'ALL_Consumer'=>$mapped,
            'Non_lifeliner'=>$newNonlifelinerResedentialConsumer,
            'Lifeliner'=> $newlifelinerResedentialConsumer
        ],200);
    }
    public function aging(Request $request)
    {
        set_time_limit(0);
        $newDate = array();
        $date = array();
        // $count = 0;
        $add = $request->kwh;
        for($i=0;$i<5;$i++)
        {
            // $date[$i] = $newDate;
            
            if($i == 0)
            {
                $date[$i] = str_replace("-","",$request->date1);
            }else{
                $modDate = $date[$i - 1];
                $date[$i] = $this->oneMonthBehind($modDate);
            }              
            array_push($newDate,$date[$i]);
        }
        $date1 =  str_replace("-","",$request->date1);
        $count = count($newDate);
        $allDates = $newDate;
        if($request->location == 'all'){
            $location = 'ac.ac_id';
            $whereLocation = '';
        }else if($request->location == 'area'){
            $location = 'tc.tc_id';
            $whereLocation = 'ac.ac_id';
        }else if($request->location == 'town'){
            $location = 'rc.rc_id';
            $whereLocation = 'tc.tc_id';
        }else{
            $location = 'mr.cm_id';
            $whereLocation = 'rc.rc_id';
        }
        $allCons = DB::table('cons_master as cm')
        ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
        ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
        ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
        ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
        ->select(DB::raw('sum(mr.mr_amount)as mr_amount,sum(mr.mr_kwh_used)as mr_kwh_used,cm.cm_account_no,cm.cm_full_name,mr.mr_date_year_month,mr.mr_bill_no,mr.cm_id,cm.cm_con_status,
        ac.ac_id,ac.ac_name,tc.tc_id,tc.tc_name,tc.tc_code,rc.rc_id,rc.rc_code,rc.rc_desc'))
        ->where('mr.mr_printed',1)
        ->whereIn('mr.mr_date_year_month',$newDate)
        ->groupBy($location,'mr.mr_date_year_month');
        if($request->location != 'all'){
            $allCons->where($whereLocation,$request->id);
        }
        if ($add != "yes") {
            $allCons->where('mr.mr_status',0);
        }
        if($request->selected == 'inactive'){
            $allCons->where('cm_con_status',0);
        }else if($request->selected == 'active'){
            $allCons->where('cm_con_status',1);
        }
        
        $allCons = collect($allCons->get());

        $allCons2 = DB::table('cons_master as cm')
        ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
        ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
        ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
        ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
        ->select(DB::raw('sum(mr.mr_amount)as mr_amount,sum(mr.mr_kwh_used)as mr_kwh_used,cm.cm_account_no,cm.cm_full_name,mr.mr_date_year_month,mr.mr_bill_no,mr.cm_id,cm.cm_con_status,
        ac.ac_id,ac.ac_name,tc.tc_id,tc.tc_name,tc.tc_code,rc.rc_id,rc.rc_code,rc.rc_desc'))
        ->where('mr.mr_date_year_month','<=',$newDate[4])
        ->where('mr.mr_printed',1)
        ->orderBy('mr.mr_date_year_month','asc')
        ->orderBy('cm.cm_account_no','asc')
        ->groupBy($location);
        if($request->location != 'all'){
            $allCons2->where($whereLocation,$request->id);
        }
        if ($add != "yes") {
            $allCons2->where('mr.mr_status',0);
        }
        if($request->selected == 'inactive'){
            $allCons2->where('cm_con_status',0);
        }else if($request->selected == 'active'){
            $allCons2->where('cm_con_status',1);
        }
        $allCons2 = collect($allCons2->get());
        
        if(!$allCons->first() && !$allCons2->first()){
            return response(['Message'=>'No Records Found'],422);
        }
        $samp4 = $date[4];
        for($i=0;$i<$count;$i++)
        {   
            
            if($i == 4){
                
                $coll[$i] = $allCons2->map(function($item) use($samp4,$add,$request){
                    // $date = date_create($samp4);
                    if($add == "yes"){
                        $display =  $item->mr_kwh_used;
                    }else{
                        $display = round($item->mr_amount,2);
                    }
                    if($request->location == 'all'){
                        $account = $item->ac_id;
                        $name = $item->ac_name;
                    }else if($request->location == 'area'){
                        $account = $item->tc_code;
                        $name = $item->tc_name;
                    }else if($request->location == 'town'){
                        $account = $item->rc_code;
                        $name = $item->rc_desc;
                    }else{
                        $account = $item->cm_account_no;
                        $name = $item->cm_full_name;
                    }
                    return[
                        'Account_No'=>$account,
                        'Consumer_Name'=>$name,
                        'Bill_No'=> $samp4,
                        'Amount'=>$display,
                    ];
                })->sortBy('Account_No');
            }else{
                $coll[$i] = $allCons->where('mr_date_year_month',$newDate[$i])->map(function($item) use($add,$request){
                    // $date = date_create($item->mr_date_year_month);
                    if($add == "yes"){
                        $display =  $item->mr_kwh_used;
                    }else{
                        $display = round($item->mr_amount,2);
                    }
                    if($request->location == 'all'){
                        $account = $item->ac_id;
                        $name = $item->ac_name;
                    }else if($request->location == 'area'){
                        $account = $item->tc_code;
                        $name = $item->tc_name;
                    }else if($request->location == 'town'){
                        $account = $item->rc_code;
                        $name = $item->rc_desc;
                    }else{
                        $account = $item->cm_account_no;
                        $name = $item->cm_full_name;
                    }
                    return[
                        'Account_No'=>$account,
                        'Consumer_Name'=>$name,
                        // 'Bill_No'=>date_format($date,"M Y"),
                        'Bill_No'=>$item->mr_date_year_month,
                        'Amount'=>$display,
                    ];
                })->sortBy('Account_No');
            }
        }
        // Get All the Consumers/Area/Town/Route
        $getConsumers = DB::table('cons_master as cm')
            // ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id');
            if($request->location == 'all'){
                $getConsumers->select('ac.ac_id as Account_No','ac.ac_name as name')
                ->groupBy('ac.ac_id');
            }else if($request->location == 'area'){
                $getConsumers->select('tc.tc_code as Account_No','tc.tc_name as name')
                ->where('ac.ac_id',$request->id)
                ->groupBy('tc.tc_id');
            }else if($request->location == 'town'){
                $getConsumers->select('rc.rc_code as Account_No','rc.rc_desc as name')
                ->where('tc.tc_id',$request->id)
                ->groupBy('rc.rc_id');
            }else{
                $getConsumers->select('cm.cm_account_no as Account_No','cm.cm_full_name as name')
                ->where('rc.rc_id',$request->id)
                ->groupBy('cm.cm_account_no');
            }

            if($request->selected == 'inactive'){
                $getConsumers->where('cm_con_status',0)->orderBy('Account_No');
            }else if($request->selected == 'active'){
                $getConsumers->where('cm_con_status',1)->orderBy('Account_No');
            }
            $getConsumers = $getConsumers->get();
        
        
        $obj = collect($allDates);
        $mAlldates = $obj->map(function($item){
            return substr($item,0,4).'-'.substr($item,4,6);
        });
        $allItems = $coll[0]->merge($coll[1]);
        $allItems = $allItems->merge($coll[2]);
        $allItems = $allItems->merge($coll[3]);
        $allItems = $allItems->merge($coll[4]);

        //Format for Bill_No => [Dretails]
        $grouped = $allItems->groupBy(['Account_No', function ($item) {
            return $item['Bill_No'];
        }]);
        $temp = 0;
        $account = 0;
        $tEachAmount = array();
        foreach($grouped as $each){
            foreach($each as $minieach){
                $temp += $minieach[0]['Amount'];
                $account = $minieach[0]['Account_No'];
            }
            array_push($tEachAmount,['Account_No'=>$account, 'Amount'=> number_format($temp,2)]);
            $temp = 0;
        }

        return response([
            'area'=>'0'.$allCons->first()->ac_id.' '.$allCons->first()->ac_name,
            'town'=>$allCons->first()->tc_code.' '.$allCons->first()->tc_name,
            'route'=>$allCons->first()->rc_code.' '.$allCons->first()->rc_desc,
            'Consumers'=> $getConsumers,
            'Account_Bills'=>$grouped,
            $date[0] => round($coll[0]->sum('Amount'),2),
            $date[1] => round($coll[1]->sum('Amount'),2),
            $date[2] => round($coll[2]->sum('Amount'),2),
            $date[3] => round($coll[3]->sum('Amount'),2),
            $date[4] => round($coll[4]->sum('Amount'),2),
            // 'dates'=>$mAlldates,
            'Total_Consumer_Each_Bill_Period'=>$tEachAmount,
            'Grand_Total_Consumer_Bill_Period'=>number_format(round($coll[0]->sum('Amount'),2) + round($coll[1]->sum('Amount'),2)
                 + round($coll[2]->sum('Amount'),2) + round($coll[3]->sum('Amount'),2) + round($coll[4]->sum('Amount'),2),2)
        ]);
    }
    public function salesPerTypeWithConsName(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        $getAllConsBilledRoute = collect(DB::table('cons_master as cm')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->whereBetween('tc.tc_code',[$request->town_code_from,$request->town_code_to])
            ->where('tc.ac_id',$request->area_id)
            ->where('mr.mr_date_year_month',$billingPeriod)
            // ->where('mr.mr_status',1)
            ->orderBy('tc.tc_code')
            ->get());
        $check = $getAllConsBilledRoute->first();
        if(!$check)
        {
            return response(['Message'=>'No Records Found'],422);
        }

        $mappedCons = $getAllConsBilledRoute->map(function($item){
            $gen = round(round($item->br_gensys_rate * $item->mr_kwh_used,3),2) + 
            round(round($item->br_par_rate * $item->mr_kwh_used,3),2) + 
            round(round($item->br_fbhc_rate * $item->mr_kwh_used,3),2) + 
            round(round($item->br_forex_rate * $item->mr_kwh_used,3),2);
            $trans = round(round(floatval($item->br_transsys_rate * $item->mr_kwh_used),3),2) + 
            round(round(floatval($item->br_transdem_rate * $item->mr_dem_kwh_used),3),2) + 
            round(round(floatval($item->br_sysloss_rate * $item->mr_kwh_used),3),2);
            $dist = round(round(floatval($item->br_distsys_rate * $item->mr_kwh_used),3),2) + 
            round(round(floatval($item->br_distdem_rate * $item->mr_dem_kwh_used),3),2) + 
            round(round(floatval($item->br_suprtlcust_fixed),3),2) + //per cst
            round(round(floatval($item->br_supsys_rate * $item->mr_kwh_used),3),2) +
            round(round(floatval($item->br_mtrrtlcust_fixed),3),2) + //per cst
            round(round(floatval($item->br_mtrsys_rate * $item->mr_kwh_used),3),2);
            $other = round(round(floatval(($item->mr_lfln_disc != 0) ?$item->mr_lfln_disc :$item->br_lfln_subs_rate * $item->mr_kwh_used),3),2) + 
            round(round(floatval($item->br_sc_subs_rate * $item->mr_kwh_used),3),2) + 
            round(round(floatval($item->br_intrclscrssubrte ),3),2) + //per cst
            round(round(floatval($item->br_capex_rate * $item->mr_kwh_used),3),2) +
            round(round(floatval($item->br_loancon_rate_kwh * $item->mr_kwh_used),3),2) + 
            round(round(floatval($item->br_loancon_rate_fix),3),2);//per cst
            $univ = round(round(floatval($item->br_uc4_miss_rate_spu * $item->mr_kwh_used),3),2) + 
            round(round(floatval($item->br_uc4_miss_rate_red * $item->mr_kwh_used),3),2) + 
            round(round(floatval($item->br_uc6_envi_rate * $item->mr_kwh_used),3),2) +
            round(round(floatval($item->br_uc5_equal_rate * $item->mr_kwh_used),3),2) +
            round(round(floatval($item->br_uc2_npccon_rate * $item->mr_kwh_used),3),2) +
            round(round(floatval($item->br_uc1_npcdebt_rate * $item->mr_kwh_used),3),2) +
            round(round(floatval($item->br_fit_all * $item->mr_kwh_used),3),2);
            $lflnVat = ($item->mr_lfln_disc != 0) ? 0 : round(round($item->br_vat_lfln * $item->mr_kwh_used,3),2);
            $vat = round(round(floatval($item->br_vat_gen * $item->mr_kwh_used),3),2) + 
            round(round(floatval($item->br_vat_trans * $item->mr_kwh_used),3),2)  + 
            round(round(floatval($item->br_vat_transdem * $item->mr_dem_kwh_used),3),2) +
            round(round(floatval($item->br_vat_systloss * $item->mr_kwh_used),3),2)  +
            round(round(floatval($item->br_vat_distrib_kwh * $item->mr_kwh_used),3),2)  +
            round(round(floatval($item->br_vat_distdem * $item->mr_dem_kwh_used),3),2) +
            round(round(floatval($item->br_vat_par * $item->mr_kwh_used),3),2)  + //new
            round(round(floatval($item->br_vat_supsys * $item->mr_kwh_used),3),2)  + //new
            round(round(floatval($item->br_vat_metersys * $item->mr_kwh_used),3),2)  + //new
            round(round(floatval($item->br_vat_mtr_fix),3),2) + //new
            round(round(floatval($item->br_vat_loancondo * $item->mr_kwh_used),3),2) +
            round(round(floatval($item->br_vat_loancondofix),3),2) + round(round($item->br_vat_supfix,3),2) + 
            $lflnVat; //+ 
            // ($item->mr_lfln_disc != 0) ? 0 : round(round($item->br_vat_lfln * $item->mr_kwh_used,3),2);
            
            $total = round($gen + $trans + $dist + $other +
            $univ + $vat,2);
            // dd($vat);
            // dd(($item->mr_lfln_disc != 0) ? 0 : round(round($item->br_vat_lfln * $item->mr_kwh_used,3),2));
            return[
                'Town_Code'=>$item->tc_code,
                'Town_Name'=>$item->tc_name,
                'ct_id'=>$item->ct_id,
                'Consumer_Type'=>$item->ct_desc,
                'Account_No'=>$item->cm_account_no,
                'Name_Of_Consumer'=>$item->cm_full_name,
                'KWH_USED'=>$item->mr_kwh_used,
                'GEN'=>round($gen,2),
                'TRANS'=>round($trans,2),
                'DIST'=>round($dist,2),
                'OTHER'=>round($other,2),
                'UNIV_CHARGE'=>round($univ,2),
                'VAT'=>round($vat,2),
                'OTHER_NB'=>'0.00',
                'TOTAL_BILL'=> round($total,2),
            ];
        });
        
        if($request->selected == 'all'){
            $grouped = $mappedCons->groupBy(['Town_Name','Consumer_Type'])->sortBy('Account_No');

        }else{
            $grouped = $mappedCons->where('ct_id',$request->selected)->groupBy(['Town_Name','Consumer_Type'])->sortBy('Account_No');
        }

        return response([
            'Sales_Per_route'=>$grouped->values()->all()
        ]);
    }
    public function summaryOfSalesUnbundled(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);

        if($request->filter == 'area'){
            $whereGroup = 'ac_name';
        }else if($request->filter == 'town'){
            $whereGroup = 'tc_name';
        }else{
            $whereGroup = 'rc_desc';
        }
        // UPDATE ON 10/24/2022
        // ONLY AREA
        
        if($request->type == 'lgu'){
            $loc = 'lgu';
            $sales = collect((new GetRateService())->GetRate($billingPeriod,$loc));
            // dd($sales);
        }else{
            $loc = '';
            $sales = collect((new GetRateService())->GetRate($billingPeriod,$loc));
        }
        
        if($sales->isEmpty())
        {
            return response(['Message'=> 'No Records Found'],422);
        }
        $query = $sales->groupBy($whereGroup);
        $mappedToGroup = $query->map(function($item){
            return[
                // 'Consumer_Count_Total'=>$item->first()->count,
                'Total_Kwh_Used'=>round($item->sum('KWH_USED'),2),
                //GENERATION CHARGES
                'gensys'=>round($item->sum('Generation_System_Charge'),2),
                'par'=>round($item->sum('Power_Act_Reduction'),2),
                'fbhc'=>round($item->sum('Franchise_Benefits_To_Host'),2),
                'forex'=>round($item->sum('FOREX_Adjustment_Charge'),2),
                //TRANSMISSION CHARGES
                'transys'=>round($item->sum('Trans_Demand_Charge'),2),
                'transdem'=>round($item->sum('Transmission_System_Charge'),2),
                'sysloss'=>round($item->sum('System_Loss_Charge'),2),
                //DISTRIBUTION CHARGES              
                'distsys'=>round($item->sum('Dist_Demand_Charge'),2),
                'distdem'=>round($item->sum('Distribution_System_Charge'),2),
                'supfix'=>round($item->sum('Supply_System_Fixed_Charge'),2),
                'supsys'=>round($item->sum('Supply_System_Charge'),2),
                'meterfix'=>round($item->sum('Retail_Customer_Mtr_Fixed_Charge'),2),
                'metersys'=>round($item->sum('Retail_Customer_Meter_Charge'),2),
                //OTHER CHARGES
                'lflnDisc'=>round($item->sum('Lifeline_Rate_Discount'),2),
                'lflnsub'=>round($item->sum('Lifeline_Rate_Subsidy'),2),
                'sencitdiscsub'=>round($item->sum('Senior_Citizen_Subsidy'),2),
                'intClssCrssSubs'=>round($item->sum('Inter_Class_Cross_Subsidy'),2),
                'capex'=>round($item->sum('Members_Contributed_Capital'),2),
                'loancond'=>round($item->sum('lOAN_COND'),2),
                'loancondfix'=>round($item->sum('lOAN_COND_FIX'),2),
                //UNIVERSAL CHARGES
                'spug'=>round($item->sum('UC_SPUG'),2),
                'red'=>round($item->sum('UC_RED_Cash_Incentive'),2),
                'envichrge'=>round($item->sum('UC_Environmental_Charge'),2),
                'equliroyal'=>round($item->sum('UC_Equal_of_Taxes_Royalties'),2),
                'npccon'=>round($item->sum('UC_NPC_Stranded_Contract_Cost'),2),
                'npcdebt'=>round($item->sum('UC_NPC_Stranded_Debt_Cost'),2),
                'fitall'=>round($item->sum('Feed_in_Tariff_Allowance'),2),
                // VALUE ADDED TAX
                'genvat'=>round($item->sum('Generation'),2),
                'parvat'=>round($item->sum('Power_Act_Red_Vat'),2),
                'transvat'=>round($item->sum('Transmission_System'),2),
                'transdemvat'=>round($item->sum('Transmission_Demand'),2),
                'syslossvat'=>round($item->sum('System_Loss'),2),
                'distsysvat'=>round($item->sum('Distribution_System'),2),
                'distdemvat'=>round($item->sum('Distribution_Demand'),2),
                'supplyfixvat'=>round($item->sum('Supply_Fix_Vat'),2),
                'supsysvat'=>round($item->sum('Supply_Sys_Vat'),2),
                'mtrfixvat'=>round($item->sum('Meter_Fix_Vat'),2),
                'mtrsysvat'=>round($item->sum('Meter_Sys_Vat'),2),
                'lflnDiscSubvat'=>round($item->sum('lfln_disc_subs_vat'),2),
                'loancondvat'=>round($item->sum('Loan_Condonation_KWH'),2),
                'loancondifixvat'=>round($item->sum('Loan_Condonation_Fix'),2),
                'others_vat'=>'0.00',
                'billed_amount'=>round($item->sum('BILL_AMOUNT'),2)
            ];
        });

        $gtotal[] = [
            'Total_Kwh_Used'=>$mappedToGroup->sum('Total_Kwh_Used'),
            //GENERATION CHARGES
            'gensys'=>round($mappedToGroup->sum('gensys'),2),
            'par'=>round($mappedToGroup->sum('par'),2),
            'fbhc'=>round($mappedToGroup->sum('fbhc'),2),
            'forex'=>round($mappedToGroup->sum('forex'),2),
            //TRANSMISSION CHARGES
            'transys'=>round($mappedToGroup->sum('transys'),2),
            'transdem'=>round($mappedToGroup->sum('transdem'),2),
            'sysloss'=>round($mappedToGroup->sum('sysloss'),2),
            //DISTRIBUTION CHARGES              
            'distsys'=>round($mappedToGroup->sum('distsys'),2),
            'distdem'=>round($mappedToGroup->sum('distdem'),2),
            'supfix'=>round($mappedToGroup->sum('supfix'),2),
            'supsys'=>round($mappedToGroup->sum('supsys'),2),
            'meterfix'=>round($mappedToGroup->sum('meterfix'),2),
            'metersys'=>round($mappedToGroup->sum('metersys'),2),
            //OTHER CHARGES
            'lflnDisc'=>round($mappedToGroup->sum('lflnDisc'),2),
            'lflnsub'=>round($mappedToGroup->sum('lflnsub'),2),
            'sencitdiscsub'=>round($mappedToGroup->sum('sencitdiscsub'),2),
            'intClssCrssSubs'=>round($mappedToGroup->sum('intClssCrssSubs'),2),
            'capex'=>round($mappedToGroup->sum('capex'),2),
            'loancond'=>round($mappedToGroup->sum('loancond'),2),
            'loancondfix'=>round($mappedToGroup->sum('loancondfix'),2),
            //UNIVERSAL CHARGES
            'spug'=>round($mappedToGroup->sum('spug'),2),
            'red'=>round($mappedToGroup->sum('red'),2),
            'envichrge'=>round($mappedToGroup->sum('envichrge'),2),
            'equliroyal'=>round($mappedToGroup->sum('equliroyal'),2),
            'npccon'=>round($mappedToGroup->sum('npccon'),2),
            'npcdebt'=>round($mappedToGroup->sum('npcdebt'),2),
            'fitall'=>round($mappedToGroup->sum('fitall'),2),
            // VALUE ADDED TAX
            'genvat'=>round($mappedToGroup->sum('genvat'),2),
            'parvat'=>round($mappedToGroup->sum('parvat'),2),
            'transvat'=>round($mappedToGroup->sum('transvat'),2),
            'transdemvat'=>round($mappedToGroup->sum('transdemvat'),2),
            'syslossvat'=>round($mappedToGroup->sum('syslossvat'),2),
            'distsysvat'=>round($mappedToGroup->sum('distsysvat'),2),
            'distdemvat'=>round($mappedToGroup->sum('distdemvat'),2),
            'supplyfixvat'=>round($mappedToGroup->sum('supplyfixvat'),2),
            'supsysvat'=>round($mappedToGroup->sum('supsysvat'),2),
            'mtrfixvat'=>round($mappedToGroup->sum('mtrfixvat'),2),
            'mtrsysvat'=>round($mappedToGroup->sum('mtrsysvat'),2),
            'lflnDiscSubvat'=>round($mappedToGroup->sum('lflnDiscSubvat'),2),
            'loancondvat'=>round($mappedToGroup->sum('loancondvat'),2),
            'loancondifixvat'=>round($mappedToGroup->sum('loancondifixvat'),2),
            'others_vat'=>'0.00',
            'billed_amount'=>round($mappedToGroup->sum('billed_amount'),2)
        ];

        return response([
            'Sales_Unbundled_Details'=>$mappedToGroup,
            'Grand_total'=>$gtotal
        ]);
    }
    public function summaryOfRevPerTown(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        $revPerTown = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->select(DB::raw('count(cm.cm_id) as count,sum(mr.mr_kwh_used) as kwh,sum(mr.mr_amount) as amount,
                rc.rc_desc,tc.tc_name,ac.ac_name,ac.ac_id,
                sum((br.br_gensys_rate * mr.mr_kwh_used) + (br.br_par_rate * mr.mr_kwh_used) + (br.br_fbhc_rate * mr.mr_kwh_used) + (br.br_forex_rate * mr.mr_kwh_used)) as gen,
                sum(br.br_sysloss_rate * mr.mr_kwh_used) as lineloss,
                sum((br.br_transsys_rate * mr.mr_kwh_used) + (br.br_transdem_rate * mr.mr_pres_dem_reading)) as trans,
                sum((br.br_distsys_rate * mr.mr_kwh_used) + (br.br_distdem_rate * mr.mr_pres_dem_reading) + (br.br_supsys_rate * mr.mr_kwh_used) + (br.br_mtrrtlcust_fixed) + (br.br_mtrsys_rate * mr.mr_kwh_used)) as dist,
                sum(br.br_suprtlcust_fixed) as supplyFix,
                sum(br.br_mtrrtlcust_fixed) as meterFix,
                sum(br.br_par_rate * mr.mr_kwh_used) as par,
                sum(br.br_capex_rate * mr.mr_kwh_used) as mcc,sum(br.br_uc4_miss_rate_spu * mr.mr_kwh_used) as spug,
                sum(br.br_uc4_miss_rate_red * mr.mr_kwh_used) as red,sum(br.br_uc6_envi_rate * mr.mr_kwh_used) as ec,
                sum(br.br_uc2_npccon_rate * mr.mr_kwh_used) as npc,sum(br.br_loancon_rate_fix * mr.mr_kwh_used) as lcondFix,
                sum(br.br_fit_all * mr.mr_kwh_used) as fitAll,sum(br.br_lfln_subs_rate * mr.mr_kwh_used) as lflnSub,sum(mr.mr_lfln_disc) as lflnDisc'))
            // ->where('mr.mr_status',0)
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->where('ac.ac_id',$request->area_id)
            ->groupBy('tc.tc_name')
            ->get());

            $check = $revPerTown->first();
            if(!$check)
            {
                return response(['Message'=> 'No Records Found'],422);
            }
            // GET RATES from service for all ConsumerType
            $loc = $request->area_id;
            $data = (new GetRateService())->GetRate($billingPeriod,$loc);

            $newData = collect($data->groupBy('Town_Name'));
            // dd($newData);

            $mappedToGroup = $newData->map(function($items,$key){
                return[
                    'Area_ID'=>$items[0]['Area_ID'],
                    'Town'=>$key,
                    'Consumer_Count_Total'=>$items->count('Ct_Desc'),
                    'Kwh_Used'=>round($items->sum('KWH_USED'),2),
                    'Bill_Amount'=>$items->sum('BILL_AMOUNT'),
                    'Generation'=>$items->sum('Generation_System_Charge') + $items->sum('Power_Act_Reduction') + $items->sum('Franchise_Benefits_To_Host') + $items->sum('FOREX_Adjustment_Charge'),
                    'LineLoss'=>round($items->sum('System_Loss_Charge'),2),
                    'Trans'=>round($items->sum('Trans_Demand_Charge') + $items->sum('Transmission_System_Charge'),2),
                    'Dist'=> round($items->sum('Dist_Demand_Charge') + $items->sum('Distribution_System_Charge')  + $items->sum('Supply_System_Charge') + $items->sum('Retail_Customer_Meter_Charge'),2),
                    'Supply_Fix'=>round($items->sum('Supply_System_Fixed_Charge'),2),
                    'Metering_Fix'=>round($items->sum('Retail_Customer_Mtr_Fixed_Charge'),2),
                    'Capex'=>round($items->sum('Members_Contributed_Capital'),2),
                    'Lifeline_Subs'=>round($items->sum('Lifeline_Rate_Subsidy'),2),
                    'Lifeline_Disc'=>round($items->sum('Lifeline_Rate_Discount'),2),
                    'PAR'=>round($items->sum('Power_Act_Reduction'),2),
                    'UC_ME_SPUG'=>round($items->sum('UC_SPUG'),2),
                    'UC_ME_RED'=>round($items->sum('UC_RED_Cash_Incentive'),2),
                    'UC_EC'=>round($items->sum('UC_Environmental_Charge'),2),
                    'PPA_Refund'=>0,
                    'SR_Discount'=>0,
                    'SR_Subsidy'=>0,
                ];
            });

            $grouped = $mappedToGroup->groupBy('Area_ID')->map(function ($items) {
                return [
                    'Consumer_Count_Total'=>round($items->sum('Consumer_Count_Total'),2),
                    'Kwh_Used'=>round($items->sum('Kwh_Used'),2),
                    'Bill_Amount'=>round($items->sum('Bill_Amount'),2),
                    'Generation'=>round($items->sum('Generation'),2),
                    'LineLoss'=>round($items->sum('LineLoss'),2),
                    'Trans'=>round($items->sum('Trans'),2),
                    'Dist'=>round($items->sum('Dist'),2),
                    'Supply_Fix'=>round($items->sum('Supply_Fix'),2),
                    'Metering_Fix'=>round($items->sum('Metering_Fix'),2),
                    'Capex'=>round($items->sum('Capex'),2),
                    'Lifeline_Subs'=>round($items->sum('Lifeline_Subs'),2), 
                    'Lifeline_Disc'=>round($items->sum('Lifeline_Disc'),2), 
                    'PAR'=>round($items->sum('PAR'),2),
                    'UC_ME_SPUG'=>round($items->sum('UC_ME_SPUG'),2),
                    'UC_ME_RED'=>round($items->sum('UC_ME_RED'),2),
                    'UC_EC'=>round($items->sum('UC_EC'),2),
                    'PPA_Refund'=>0,
                    'SR_Discount'=>0,
                    'SR_Subsidy'=>0,
                ];
            });

            return response([
                'Per_Town'=> $mappedToGroup,
                'Towns_Total'=> $grouped->values()->all(),
            ],200);
    }
    public function summaryOfRevPerConsType(Request $request)
    {
        $billingPeriodFrom = str_replace("-","",$request->Date_From);
        $billingPeriodTo = str_replace("-","",$request->Date_To);
        $revPerConsType = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->select(DB::raw('count(cm.cm_id) as count,sum(mr.mr_kwh_used) as kwh,sum(mr.mr_amount) as amount,
                rc.rc_desc,tc.tc_name,ac.ac_name,ac.ac_id,ct.ct_desc,ct.ct_id,
                sum((br.br_gensys_rate * mr.mr_kwh_used) + (br.br_par_rate * mr.mr_kwh_used) + (br.br_fbhc_rate * mr.mr_kwh_used) + (br.br_forex_rate * mr.mr_kwh_used)) as gen,
                sum(br.br_sysloss_rate * mr.mr_kwh_used) as lineloss,
                sum((br.br_transsys_rate * mr.mr_kwh_used) + (br.br_transdem_rate * mr.mr_pres_dem_reading)) as trans,
                sum((br.br_distsys_rate * mr.mr_kwh_used) + (br.br_distdem_rate * mr.mr_pres_dem_reading) + (br.br_supsys_rate * mr.mr_kwh_used) + (br.br_mtrrtlcust_fixed) + (br.br_mtrsys_rate * mr.mr_kwh_used)) as dist,
                sum(br.br_suprtlcust_fixed) as supplyFix,
                sum(br.br_mtrrtlcust_fixed) as meterFix,
                sum(br.br_par_rate * mr.mr_kwh_used) as par,
                sum(br.br_capex_rate * mr.mr_kwh_used) as mcc,sum(br.br_uc4_miss_rate_spu * mr.mr_kwh_used) as spug,
                sum(br.br_uc4_miss_rate_red * mr.mr_kwh_used) as red,sum(br.br_uc6_envi_rate * mr.mr_kwh_used) as ec,
                sum(br.br_uc2_npccon_rate * mr.mr_kwh_used) as npc,sum(br.br_loancon_rate_fix * mr.mr_kwh_used) as lcondFix,
                sum(br.br_fit_all * mr.mr_kwh_used) as fitAll,sum(br.br_lfln_subs_rate * mr.mr_kwh_used) as lflnSub,sum(mr.mr_lfln_disc) as lflnDisc'))
            // ->where('mr.mr_status',0)
            ->whereBetween('mr.mr_date_year_month',[$billingPeriodFrom,$billingPeriodTo])
            ->groupBy('ct.ct_desc')
            ->get());
            // dd($revPerConsType);
            $check = $revPerConsType->first();
            if(!$check)
            {
                return response(['Message'=> 'No Records Found'],422);
            }

            $mappedToGroup = $revPerConsType->map(function($items){
                if($items->lflnDisc > 0){
                    $lflnSubs = 0;
                    $lflnDisc = round(round($items->lflnDisc,3),2);
                }else{
                    $lflnSubs = round(round($items->lflnSub,3),2);
                    $lflnDisc = 0;
                }
                return[
                    'Area_ID'=>$items->ac_id,
                    'Cons_Type'=>$items->ct_desc,
                    'Consumer_Count_Total'=>$items->count,
                    'Kwh_Used'=>round(round($items->kwh,3),2),
                    'Bill_Amount'=>round(round($items->amount,3),2),
                    'Generation'=>round(round($items->gen,3),2),
                    'LineLoss'=>round(round($items->lineloss,3),2),
                    'Trans'=>round(round($items->trans,3),2),
                    'Dist'=>round(round($items->dist,3),2),
                    'Supply_Fix'=>round(round($items->supplyFix,3),2),
                    'Metering_Fix'=>round(round($items->meterFix,3),2),
                    'Capex'=>round(round($items->mcc,3),2),
                    'Lifeline_Subs'=>round(round($lflnSubs,3),2),
                    'Lifeline_Disc'=>round(round($lflnDisc,3),2),
                    'PAR'=>round(round($items->par,3),2),
                    'UC_ME_SPUG'=>round(round($items->spug,3),2),
                    'UC_ME_RED'=>round(round($items->red,3),2),
                    'UC_EC'=>round(round($items->ec,3),2),
                    'PPA_Refund'=>0,
                    'SR_Discount'=>0,
                    'SR_Subsidy'=>0,
                ];
            });
            $grouped = $mappedToGroup->groupBy('Area_ID')->map(function ($items) {
                return [
                    'Consumer_Count_Total'=>round(round($items->sum('Consumer_Count_Total'),2),3),
                    'Kwh_Used'=>round(round($items->sum('Kwh_Used'),2),3),
                    'Bill_Amount'=>round(round($items->sum('Bill_Amount'),2),3),
                    'Generation'=>round(round($items->sum('Generation'),2),3),
                    'LineLoss'=>round(round($items->sum('LineLoss'),2),3),
                    'Trans'=>round(round($items->sum('Trans'),2),3),
                    'Dist'=>round(round($items->sum('Dist'),2),3),
                    'Supply_Fix'=>round(round($items->sum('Supply_Fix'),2),3),
                    'Metering_Fix'=>round(round($items->sum('Metering_Fix'),2),3),
                    'Capex'=>round(round($items->sum('Capex'),2),3),
                    'Lifeline_Subs'=>round(round($items->sum('Lifeline_Subs'),2),3),
                    'Lifeline_Disc'=>round(round($items->sum('Lifeline_Disc'),2),3),
                    'PAR'=>round(round($items->sum('PAR'),2),3),
                    'UC_ME_SPUG'=>round(round($items->sum('UC_ME_SPUG'),2),3),
                    'UC_ME_RED'=>round(round($items->sum('UC_ME_RED'),2),3),
                    'UC_EC'=>round(round($items->sum('UC_EC'),2),3),
                    'PPA_Refund'=>0,
                    'SR_Discount'=>0,
                    'SR_Subsidy'=>0,
                ];
            });

            return response([
                'Cons_type'=> $mappedToGroup,
                'Cons_Type_Total'=> $grouped,
            ],200);
    }
    public function summaryOfSalesPerConsType($date)
    {
        $billingPeriod = str_replace("-","",$date);
        $SalesPerConsType = collect(DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->select(DB::raw('count(cm.cm_id) as count,sum(mr.mr_kwh_used) as kwh,sum(mr.mr_amount) as amount,
                rc.rc_desc,tc.tc_name,ac.ac_name,ac.ac_id,ct.ct_desc,ct.ct_id,mr.mr_date_year_month,
                sum(mr.mr_pres_dem_reading) as dem_sold,sum(mr.mr_pres_dem_reading * mr.mr_dem_kwh_used) as dem_amount'))
            // ->where('mr.mr_status',0)
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->groupBy('ct.ct_desc')
            ->get());

            $check = $SalesPerConsType->first();
            if(!$check)
            {
                return response(['Message'=> 'No Records Found'],422);
            }
            $groupedCTDesc = $SalesPerConsType->groupBy('ct_desc')->map(function ($items) {
                return [
                    'Receiving_Services'=>round(round($items->sum('count'),2),3),
                    'Kwh_Used_Demand'=>round(round($items->sum('dem_sold'),2),3),
                    'Amount_Demand'=>round(round($items->sum('dem_amount'),2),3),
                    'Kwh_Used'=>round(round($items->sum('kwh'),2),3),
                    'Amount'=>round(round($items->sum('amount'),2),3),
                    'Bill_Amount'=> 0,
                ];
            });

            $grouped = $SalesPerConsType->groupBy('mr_date_year_month')->map(function ($items) {
                return [
                    'Receiving_Services'=>round(round($items->sum('count'),2),3),
                    'Kwh_Used_Demand'=>round(round($items->sum('dem_sold'),2),3),
                    'Amount_Demand'=>round(round($items->sum('dem_amount'),2),3),
                    'Kwh_Used'=>round(round($items->sum('kwh'),2),3),
                    'Amount'=>round(round($items->sum('amount'),2),3),
                ];
            });

            return response([
                'Cons_Type'=> $groupedCTDesc,
                'Cons_Type_Total'=> $grouped,
            ],200);
    }
    public function monthlySummaryLifeline($date)
    {
        $areas = collect(DB::table('area_code')
        ->select('ac_id')
        ->get());
        $count = count($areas);
        $data = collect();
        for($i=0;$i<$count;$i++)
        {
            $data->push((new LifelineAllAreasService())->lifelineAllAreas($areas[$i]->ac_id,$date));
        }
        if(!$data){
            return response(['Message'=>'No Record Found'],422);
        }
        return response(['Monthly_Lifeline'=>$data],200);
    }
    public function summaryBillAdjustment($date_period)
    {
        $billingPeriod = str_replace("-","",$date_period);
        $month = substr($billingPeriod,4,6);
        $year = substr($billingPeriod,0,4);
        // $adjBill = collect(
        //     DB::table('adjusted_powerbill as ap')
        //     ->join('meter_reg as mr','ap.mr_id','=','mr.mr_id')
        //     ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
        //     ->where('mr.mr_date_year_month',$billingPeriod)
        //     ->get()
        // );
        $adjBill = collect(
            DB::table('adjusted_powerbill as ap')
            ->join('meter_reg as mr','ap.mr_id','=','mr.mr_id')
            ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
            ->join('user as u','ap.ap_user','=','u.user_id')
            ->whereMonth('ap.ap_date',$month)
            ->whereYear('ap.ap_date',$year)
            ->get()
        );
        // dd($adjBill);    
        if(!$adjBill->first())
        {
            return response(['Message'=> 'No Records Found'],422);
        }

        $mapped = $adjBill->map(function($item){
            // if($item->mr_kwh_used > $item->ap_old_kwh)
            // {
            //     $kwhInc = $item->mr_kwh_used;
            //     $kwhDcr = 0;
            //     $amountInc = $item->mr_amount;
            //     $amountDec = 0;
            // }else{
            //     $kwhInc = 0;
            //     $kwhDcr = ($item->mr_kwh_used) * -1;
            //     $amountInc = 0;
            //     $amountDec = ($item->mr_amount) * -1;
            // }
            if($item->ap_old_kwh > $item->ap_new_kwh)
            {
                $kwhInc = $item->ap_new_kwh;
                $kwhDcr = 0;
                $amountInc = $item->ap_new_amount;
                $amountDec = 0;
            }else{
                $kwhInc = 0;
                $kwhDcr = ($item->ap_new_kwh) * -1;
                $amountInc = 0;
                $amountDec = ($item->ap_new_amount) * -1;
            }
            
            return[
                'Bill_No'=>$item->mr_bill_no,
                'Account_No'=>$item->cm_account_no,
                'Name'=>$item->cm_full_name,
                'Year_Month'=>$item->mr_date_year_month,
                'KWH_Increase'=>$kwhInc,
                'KWH_Decrease'=>$kwhDcr,
                'Amount_Increase'=>$amountInc,
                'Amount_Decrease'=>$amountDec,
                'Adjusted_By'=>$item->user_full_name,
                'Adjusted_date'=>$item->ap_date,
            ];
        });
        // dd($mapped);
        // $total = $mapped->groupBy('Bill_No')->map(function($item){
        //     return[
        //         'Total_KWH_Increase'=>$item->sum('KWH_Increase'),
        //         'Total_KWH_Decrease'=>$item->sum('KWH_Decrease'),
        //         'Total_Amount_Increase'=>$item->sum('Amount_Increase'),
        //         'Total_Amount_Decrease'=>$item->sum('Amount_Decrease'),
        //     ];
        // });
        $total = [
            'Total_KWH_Increase'=>round($mapped->sum('KWH_Increase'),2),
            'Total_KWH_Decrease'=>round($mapped->sum('KWH_Decrease'),2),
            'Total_Amount_Increase'=>round($mapped->sum('Amount_Increase'),2),
            'Total_Amount_Decrease'=>round($mapped->sum('Amount_Decrease'),2),
        ];

        return response([
            'Adj_Bill'=> $mapped,
            'Total'=>$total
        ],200);
    }
    public function unreadUnbilledCons(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date_period);
        $routeCodeFrom = $request->route_code_from;
        // $routeCodeTo = $request->route_code_to;
        $unreadBillCons = collect(
            DB::table('meter_reg as mr')
            ->rightJoin('cons_master as cm',function($join)use($billingPeriod)
            {
                $join->on('mr.cm_id', '=', 'cm.cm_id');
                $join->on('mr.mr_date_year_month','=',DB::raw("'".$billingPeriod."'"));
            })
            ->rightJoin('route_code as rc','cm.rc_id', '=', 'rc.rc_id')
            ->rightJoin('town_code as tc','rc.tc_id', '=', 'tc.tc_id')
            ->rightJoin('area_code as ac','tc.ac_id', '=', 'ac.ac_id')
            ->select('cm.mm_id','ac.ac_id','ac.ac_name','tc.tc_code','tc.tc_name','cm.cm_account_no','cm.cm_full_name',
                'cm.cm_con_status','mr.mr_pres_reading','mr.mr_prev_reading','cm.cm_kwh_mult','mr.mr_kwh_used','mr.mr_amount','rc.rc_code','rc_desc')
            ->whereNotNull('cm.cm_full_name')
            ->whereNull('mr.mr_amount')
            ->where('cm.pending','!=',1)
            ->where('rc.rc_id',$routeCodeFrom)
            ->get()
        );
        if(!$unreadBillCons->first())
        {
            return response(['Message'=> 'No Records Found'],422);
        }
        $num = new \stdClass();
        $num->increment = 0;
        $mapped = $unreadBillCons->map(function($item)use($num){
            $meterMaster = 
                DB::table('meter_master')
                ->where('mm_id',$item->mm_id)
                ->select('mm_serial_no')
                ->first();
            if(!$meterMaster){
                $meter = 0;
            }else{
                $meter = $meterMaster->mm_serial_no;
            }
            return[
                'Area_Code'=> '0'.$item->ac_id.' '.$item->ac_name,
                'Town_Code'=> $item->tc_code.' '.$item->tc_name,
                'Route_Code'=> $item->rc_code.' '.$item->rc_desc,
                'No'=> ++$num->increment,
                'Account_No'=>$item->cm_account_no,
                'Name'=>$item->cm_full_name,
                'Meter_No'=>($meter == 0)?'None': $meter,
                'Status'=>($item->cm_con_status == 1)?'Active':'Deactivated',
                'Remarks'=>'',
                'Field_Findings'=>'',
                'Previous'=>$item->mr_prev_reading,
                'Present'=>$item->mr_pres_reading,
                'Mult'=>$item->cm_kwh_mult,
                'KWH_Used'=>$item->mr_kwh_used,
            ];
        });
        $grouped = $mapped->groupBy(['Area_Code','Town_Code','Route_Code']);
        return response(['Message'=> $grouped],200);
    }
    public function monthlySummAdj($date_from,$date_to)
    {
        $from = date($date_from);
        $to = date($date_to);
        $monthlySummAdj = collect(
            DB::table('adjusted_powerbill as ap')
            ->join('meter_reg as mr','ap.mr_id','=','mr.mr_id')
            ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->select(DB::raw('min(ap.ap_id) as apID,
                br.br_gensys_rate,br.br_fbhc_rate,br.br_forex_rate,br.br_sysloss_rate,br.br_transdem_rate,br.br_transsys_rate,br.br_distdem_rate,br.br_distsys_rate,
                br.br_suprtlcust_fixed,br.br_supsys_rate,br.br_mtrrtlcust_fixed,br.br_mtrsys_rate,br.br_uc2_npccon_rate,br.br_uc4_miss_rate_spu,br.br_uc4_miss_rate_red,
                br.br_par_rate,br.br_uc6_envi_rate,br.br_loancon_rate_kwh,br.br_lfln_subs_rate,br.br_loancon_rate_fix,br.br_capex_rate,ap_new_amount,ap_new_kwh,ap_new_dem_kwh_used,
                tc.tc_name,mr.mr_kwh_used,ap_old_kwh,ac.ac_name'))
            ->whereDate('ap.ap_date','>=',$from)
            ->whereDate('ap.ap_date','<=',$to)
            ->orderBy('ap.ap_id','asc')
            // ->groupBy('mr.mr_id')
            ->get()
        );
        if(!$monthlySummAdj->first())
        {
            return response(['Message'=> 'No Records Found'],422);
        }

        $mapped = $monthlySummAdj->map(function($item){
            if($item->ap_new_kwh >= $item->ap_old_kwh)
            {
                $gen = round($item->br_gensys_rate,4) * $item->ap_new_kwh;
                $fbhc = round($item->br_fbhc_rate,4) * $item->ap_new_kwh;
                $frx = round($item->br_forex_rate,4) * $item->ap_new_kwh;
                $sysLoss = round($item->br_sysloss_rate,4) * $item->ap_new_kwh;
                $transdem = round($item->br_transdem_rate,4) * $item->ap_new_dem_kwh_used;
                $transys = round($item->br_transsys_rate,4) * $item->ap_new_kwh;
                $distDem = round($item->br_distdem_rate,4) * $item->ap_new_dem_kwh_used;
                $distSys = round($item->br_distsys_rate,4) * $item->ap_new_kwh;
                $supFix = round($item->br_suprtlcust_fixed,4);
                $supSys = round($item->br_supsys_rate,4) * $item->ap_new_kwh;
                $meterFix = round($item->br_mtrrtlcust_fixed,4);
                $meterSYs = round($item->br_mtrsys_rate,4) * $item->ap_new_kwh;
                $npcScc = round($item->br_uc2_npccon_rate,4) * $item->ap_new_kwh;
                $spug = round($item->br_uc4_miss_rate_spu,4) * $item->ap_new_kwh;
                $red = round($item->br_uc4_miss_rate_red,4) * $item->ap_new_kwh;
                $par = round($item->br_par_rate,4) * $item->ap_new_kwh;
                $env = round($item->br_uc6_envi_rate,4) * $item->ap_new_kwh;
                $lcond = round($item->br_loancon_rate_kwh,4) * $item->ap_new_kwh;
                $lifeDiscSub = round($item->br_lfln_subs_rate,4) * $item->ap_new_kwh;
                // $lifeSub = $item->br_lfln_subs_rate * $item->ap_new_kwh;
                $pparedfd = 0;
                $lconFix = round($item->br_loancon_rate_fix,4) * $item->ap_new_kwh;
                $slosAdj = 0;
                $ppaAdj = 0;
                $capex = round($item->br_capex_rate,4) * $item->ap_new_kwh;
                $srdisc = 0;
                $srsub = 0;
                $vat = 0; //later
                $pwerSub = 0;
                $billAmount = round($item->ap_new_amount,2);
            }else{
                $gen = (round($item->br_gensys_rate,4) * $item->ap_new_kwh) * -1;
                $fbhc = (round($item->br_fbhc_rate,4) * $item->ap_new_kwh) * -1;
                $frx = (round($item->br_forex_rate,4) * $item->ap_new_kwh) * -1;
                $sysLoss = (round($item->br_sysloss_rate,4) * $item->ap_new_kwh) * -1;
                $transdem = (round($item->br_transdem_rate,4) * $item->ap_new_dem_kwh_used) * -1;
                $transys = (round($item->br_transsys_rate,4) * $item->ap_new_kwh) * -1;
                $distDem = (round($item->br_distdem_rate,4) * $item->ap_new_dem_kwh_used) * -1;
                $distSys = (round($item->br_distsys_rate,4) * $item->ap_new_kwh) * -1;
                $supFix = (round($item->br_suprtlcust_fixed,4)) * -1;
                $supSys = (round($item->br_supsys_rate,4) * $item->ap_new_kwh) * -1;
                $meterFix = (round($item->br_mtrrtlcust_fixed,4)) * -1;
                $meterSYs = (round($item->br_mtrsys_rate,4) * $item->ap_new_kwh) * -1;
                $npcScc = (round($item->br_uc2_npccon_rate,4) * $item->ap_new_kwh) * -1;
                $spug = (round($item->br_uc4_miss_rate_spu,4) * $item->ap_new_kwh) * -1;
                $red = (round($item->br_uc4_miss_rate_red,4) * $item->ap_new_kwh) * -1;
                $par = (round($item->br_par_rate,4) * $item->ap_new_kwh) * -1;
                $env = (round($item->br_uc6_envi_rate,4) * $item->ap_new_kwh) * -1;
                $lcond = (round($item->br_loancon_rate_kwh,4) * $item->ap_new_kwh) * -1;
                $lifeDiscSub = (round($item->br_lfln_subs_rate,4) * $item->ap_new_kwh) * -1;
                // $lifeSub = $item->br_lfln_subs_rate * $item->ap_new_kwh;
                $pparedfd = 0;
                $lconFix = (round($item->br_loancon_rate_fix,4) * $item->ap_new_kwh) * -1;
                $slosAdj = 0;
                $ppaAdj = 0;
                $capex = (round($item->br_capex_rate,4) * $item->ap_new_kwh) * -1;
                $srdisc = 0;
                $srsub = 0;
                $vat = 0; //later
                $pwerSub = 0;
                $billAmount = (round($item->ap_new_amount,2)) * -1;
            }
            
            return[
                'ID'=>$item->apID,
                'Area'=>$item->ac_name,
                'Town'=>$item->tc_name,
                'Kwh_Used'=>$item->mr_kwh_used,
                'GENSYS'=>round($gen,2),
                'GENFRA'=>round($fbhc,2),
                'GENFRX'=>round($frx,2),
                'LINELOSS'=>round($sysLoss,2),
                'TRANSDEM'=>round($transdem,2),
                'TRANSYS'=>round($transys,2),
                'DISTDEM'=>round($distDem,2),
                'DISTSYS'=>round($distSys,2),
                'SUPPFIX'=>round($supFix,2),
                'SUPSYS'=>round($supSys,2),
                'METFIX'=>round($meterFix,2),
                'METSYS'=>round($meterSYs,2),
                'CONT_COST'=>round($npcScc,2),
                'UCME_SPUG'=>round($spug,2),
                'UCME_RED'=>round($red,2),
                'PAR'=>round($par,2),
                'LONCOND'=>round($lcond,2),
                'ENVI'=>round($env,2),
                'LIFEDISCSUB'=>round($lifeDiscSub,2),
                'PPAREFD'=>round($pparedfd,2),
                'LCONFIX'=>round($lconFix,2),
                'SLOS_ADJ'=>round($slosAdj,2),
                'PPA_ADJ'=>round($ppaAdj,2),
                'CAPEX'=>round($capex,2),
                'SRDISC'=>round($srdisc,2),
                'SRSUBS'=>round($srsub,2),
                'VAT'=>round($vat,2),
                'PWR_SUBS'=>round($pwerSub,2),
                'BILL_AMOUNT'=>round($billAmount,2),
            ];
        });
        $groupedTown = $mapped->groupBy('Town')->map(function($grouped){
            return[
                'Kwh_Used'=>($grouped->sum('Kwh_Used')) * -1,
                'GENSYS'=>round($grouped->sum('GENSYS'),2),
                'GENFRA'=>round($grouped->sum('GENFRA'),2),
                'LINELOSS'=>round($grouped->sum('LINELOSS'),2),
                'TRANSDEM'=>round($grouped->sum('TRANSDEM'),2),
                'TRANSYS'=>round($grouped->sum('TRANSYS'),2),
                'DISTDEM'=>round($grouped->sum('DISTDEM'),2),
                'DISTSYS'=>round($grouped->sum('DISTSYS'),2),
                'SUPPFIX'=>round($grouped->sum('SUPPFIX'),2),
                'SUPSYS'=>round($grouped->sum('SUPSYS'),2),
                'METFIX'=>round($grouped->sum('METFIX'),2),
                'METSYS'=>round($grouped->sum('METSYS'),2),
                'CONT_COST'=>round($grouped->sum('CONT_COST'),2),
                'UCME_SPUG'=>round($grouped->sum('UCME_SPUG'),2),
                'UCME_RED'=>round($grouped->sum('UCME_RED'),2),
                'PAR'=>round($grouped->sum('PAR'),2),
                'LONCOND'=>round($grouped->sum('LONCOND'),2),
                'ENVI'=>round($grouped->sum('ENVI'),2),
                'LIFEDISCSUB'=>round($grouped->sum('LIFEDISCSUB'),2),
                'PPAREFD'=>round($grouped->sum('PPAREFD'),2),
                'LCONFIX'=>round($grouped->sum('LCONFIX'),2),
                'SLOS_ADJ'=>round($grouped->sum('SLOS_ADJ'),2),
                'PPA_ADJ'=>round($grouped->sum('PPA_ADJ'),2),
                'CAPEX'=>round($grouped->sum('CAPEX'),2),
                'SRDISC'=>round($grouped->sum('SRDISC'),2),
                'SRSUBS'=>round($grouped->sum('SRSUBS'),2),
                'VAT'=>round($grouped->sum('VAT'),2),
                'PWR_SUBS'=>round($grouped->sum('PWR_SUBS'),2),
                'BILL_AMOUNT'=>round($grouped->sum('BILL_AMOUNT'),2),
            ];
        });

        $groupedArea = $mapped->groupBy('Area')->map(function($grouped){
            return[
                'Kwh_Used'=>($grouped->sum('Kwh_Used')) * -1,
                'GENSYS'=>round($grouped->sum('GENSYS'),2),
                'GENFRA'=>round($grouped->sum('GENFRA'),2),
                'LINELOSS'=>round($grouped->sum('LINELOSS'),2),
                'TRANSDEM'=>round($grouped->sum('TRANSDEM'),2),
                'TRANSYS'=>round($grouped->sum('TRANSYS'),2),
                'DISTDEM'=>round($grouped->sum('DISTDEM'),2),
                'DISTSYS'=>round($grouped->sum('DISTSYS'),2),
                'SUPPFIX'=>round($grouped->sum('SUPPFIX'),2),
                'SUPSYS'=>round($grouped->sum('SUPSYS'),2),
                'METFIX'=>round($grouped->sum('METFIX'),2),
                'METSYS'=>round($grouped->sum('METSYS'),2),
                'CONT_COST'=>round($grouped->sum('CONT_COST'),2),
                'UCME_SPUG'=>round($grouped->sum('UCME_SPUG'),2),
                'UCME_RED'=>round($grouped->sum('UCME_RED'),2),
                'PAR'=>round($grouped->sum('PAR'),2),
                'LONCOND'=>round($grouped->sum('LONCOND'),2),
                'ENVI'=>round($grouped->sum('ENVI'),2),
                'LIFEDISCSUB'=>round($grouped->sum('LIFEDISCSUB'),2),
                'PPAREFD'=>round($grouped->sum('PPAREFD'),2),
                'LCONFIX'=>round($grouped->sum('LCONFIX'),2),
                'SLOS_ADJ'=>round($grouped->sum('SLOS_ADJ'),2),
                'PPA_ADJ'=>round($grouped->sum('PPA_ADJ'),2),
                'CAPEX'=>round($grouped->sum('CAPEX'),2),
                'SRDISC'=>round($grouped->sum('SRDISC'),2),
                'SRSUBS'=>round($grouped->sum('SRSUBS'),2),
                'VAT'=>round($grouped->sum('VAT'),2),
                'PWR_SUBS'=>round($grouped->sum('PWR_SUBS'),2),
                'BILL_AMOUNT'=>round($grouped->sum('BILL_AMOUNT'),2),
            ];
        });
        
        return response([
            'Monthly_Adjust_Detailed_Town'=>$groupedTown,
            'Monthly_Adjust_Total'=>$groupedArea,
        ],200);
    }
    public function summBillAdjustDtl(Request $request)
    {
        $from = date($request->date_from);
        $to = date($request->date_to);
        $townFrom = date($request->town_from);
        $townTo = date($request->town_to);
        $summAdjDtl = collect(
            DB::table('adjusted_powerbill as ap')
            ->join('meter_reg as mr','ap.mr_id','=','mr.mr_id')
            ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->select(DB::raw('max(ap.ap_id) as apID,cm.cm_account_no,ct.ct_code,mr.mr_date_year_month,mr.mr_bill_no,mr.mr_amount, br.br_iccs_adj,
                br.br_gensys_rate,br.br_fbhc_rate,br.br_forex_rate,br.br_sysloss_rate,br.br_transdem_rate,br.br_transsys_rate,br.br_distdem_rate,br.br_distsys_rate, br.br_fit_all,
                br.br_suprtlcust_fixed,br.br_supsys_rate,br.br_mtrrtlcust_fixed,br.br_mtrsys_rate,br.br_uc2_npccon_rate,br.br_uc4_miss_rate_spu,br.br_uc4_miss_rate_red, br.br_uc1_npcdebt_rate,
                br.br_par_rate,br.br_uc6_envi_rate,br.br_loancon_rate_kwh,br.br_lfln_subs_rate,br.br_loancon_rate_fix,br.br_capex_rate,ap_new_amount,ap_new_kwh,ap_new_dem_kwh_used,
                tc.tc_name,mr.mr_kwh_used,ap_old_kwh,ac.ac_name,mr.mr_lfln_disc,br.br_vat_gen,br.br_vat_trans,br.br_vat_transdem,br.br_vat_systloss,br.br_vat_distrib_kwh,
                br.br_vat_distdem, br.br_uc5_equal_rate, br.br_vat_loancondo,br.br_vat_loancondofix,br.br_vat_par,br.br_vat_supsys,br.br_vat_mtr_fix,br.br_vat_metersys,br.br_vat_lfln'))
            ->whereDate('ap.ap_date','>=',$from)
            ->whereDate('ap.ap_date','<=',$to)
            ->whereBetween('tc.tc_code',[$townFrom,$townTo])
            ->groupBy('mr.mr_date_year_month')
            ->orderBy('cm.cm_account_no','asc')
            ->get()
        );
        
        if(!$summAdjDtl->first())
        {
            return response(['Message'=> 'No Records Found'],422);
        }
        
        $mapped = $summAdjDtl->map(function($item){
            return[
                'ACCOUNT_NO'=>$item->cm_account_no,
                'CONSTYPE'=>$item->ct_code,
                'YEAR_MONTH'=>$item->mr_date_year_month,
                'BILL_NUMBER'=>$item->mr_bill_no,
                'OLD_KWH'=>$item->ap_old_kwh,
                'NEW_KWH'=>$item->ap_new_kwh,
                'DIFF_KWH'=>($item->ap_old_kwh-$item->ap_new_kwh),
                'GEN_SYS'=>$item->br_gensys_rate,
                'PAR'=>$item->br_par_rate,
                'GEN_FRA'=>$item->br_fbhc_rate,
                'GEN_FRX'=>$item->br_forex_rate,
                'TRANS_SYS'=>$item->br_transsys_rate,
                'TRANS_DEM'=>$item->br_transdem_rate,
                'LINELOSS'=>$item->br_sysloss_rate,
                'DIST_SYS'=>$item->br_distsys_rate,
                'DIST_DEM'=>$item->br_distdem_rate,
                'SUP_FIX'=>$item->br_suprtlcust_fixed,
                'SUP_SYS'=>$item->br_supsys_rate,
                'METER_FIX'=>$item->br_mtrrtlcust_fixed,
                'METER_SYS'=>$item->br_mtrsys_rate,
                'LIFE_DISC'=>($item->mr_lfln_disc > 0 ) ? $item->mr_lfln_disc : 0,
                'LIFE_SUBD'=>($item->mr_lfln_disc > 0 ) ? 0 : $item->br_lfln_subs_rate,
                'SR_DISC'=>0,
                'SR_SUBS'=>0,
                'ICCS'=>$item->br_iccs_adj,
                'CAPEX'=>$item->br_capex_rate,
                'LOANCOND'=>$item->br_loancon_rate_kwh,
                'LCONFIX'=>$item->br_loancon_rate_fix,
                'UCME_SPUG'=>$item->br_uc4_miss_rate_spu,
                'UCME_RED'=>$item->br_uc4_miss_rate_red,
                'ENVI'=>$item->br_uc6_envi_rate,
                'ETR'=>$item->br_uc5_equal_rate,
                'NPC_SCC'=>$item->br_uc2_npccon_rate,
                'NPC_SD'=>$item->br_uc1_npcdebt_rate,
                'FITALL'=>$item->br_fit_all,

                
                // 'GEN_VAT'=>$item->br_vat_gen,
                // 'PAR_VAT'=>$item->br_vat_par,
                // 'TRANS_VAT'=>$item->br_vat_trans,
                // 'TRANS_DEM_VAT'=>$item->br_vat_transdem,
                // 'SYSLOSS_VAT'=>$item->br_vat_systloss,
                // 'DIST_SYS_VAT'=>$item->br_vat_distrib_kwh,
                // 'DIST_DEM_VAT'=>$item->br_vat_distdem,
                // 'SUPPLY_SYS_VAT'=>$item->br_vat_supsys,
                // 'METER_FIX_VAT'=>$item->br_vat_mtr_fix,
                // 'METER_SYS_VAT'=>$item->br_vat_metersys,
                // 'LIFELINE_VAT'=>$item->br_vat_lfln,
                // 'LOAN_COND_VAT'=>$item->br_vat_loancondo,
                // 'LOAN_COND_FIX_VAT'=>$item->br_vat_loancondofix,

                'PPAREFD'=>0,
                'PPD'=>0,

                'VAT_ALL'=> ($item->br_vat_gen+$item->br_vat_trans+$item->br_vat_transdem+$item->br_vat_systloss+
                        $item->br_vat_distrib_kwh+$item->br_vat_distdem+$item->br_vat_loancondo+$item->br_vat_loancondofix+
                        $item->br_vat_par+$item->br_vat_supsys+$item->br_vat_mtr_fix+$item->br_vat_metersys+$item->br_vat_lfln),
                
                'PWR_SUBS'=>0,
                'BILL_AMOUNT'=>$item->mr_amount,
            ];
        });

        return response([
            'Summar_Adjustment_Detail'=> $mapped
        ],200);
    }
    public function oneMonthBehind($bp)
    {
        // dd($bp);ag
        $getYear = substr($bp,0,4);
        $getMonth = substr($bp,4);
        // dd($getYear);
        if($getMonth == 1){
            $setYear = $getYear - 1;
            $setMonth = 12;
            $newBillPeriod = $setYear.''.$setMonth;
        }else{
            $setYear = $getYear;
            $setMonth = $getMonth - 1;
            if(strlen($setMonth) == 1){
                $setMonth = .0.$setMonth;
            }

            $newBillPeriod = $setYear.''.$setMonth;
        }
        // dd($newBillPeriod);
        return $newBillPeriod;
    }
    public function sample()
    {
        // ----------------------------------------------------------------------------------------------------------------
        // $query = collect(
        //     DB::table('e_wallet_log as ewl')
        //     ->join('sales as s','ewl.ewl_or','=','s.s_or_num')
        //     ->whereDate('s.s_bill_date','>','2022-02-13')
        //     ->where('s.server_added',1)
        //     ->where('ewl.ewl_amount',DB::RAW('s.s_or_amount + s.e_wallet_added'))
        //     ->get()
        // );

        // $map = $query->map(function($item){
        //     if($item->e_wallet_added == 0){
                
        //         $ewallet = collect(DB::table('e_wallet')
        //         ->where('ew_id',$item->ew_id)
        //         ->first());

        //         EWALLET::where('ew_id',$item->ew_id)
        //         ->update([
        //             'ew_total_amount' => $ewallet['ew_total_amount'] - $item->s_or_amount
        //         ]);
                
        //         DB::table('e_wallet_log')
        //         ->where('ewl_amount',$item->s_or_amount)
        //         ->where('ew_id',$item->ew_id)
        //         ->delete();
        //     }else{
        //         $ewallet = collect(DB::table('e_wallet')
        //         ->where('ew_id',$item->ew_id)
        //         ->first());

        //         EWALLET::where('ew_id',$item->ew_id)
        //         ->update([
        //             'ew_total_amount' => $ewallet['ew_total_amount'] - $item->s_or_amount
        //         ]);

        //         EWALLET_LOG::where('ew_id',$item->ew_id)->where('ewl_amount',$item->s_or_amount + $item->e_wallet_added)
        //         ->update([
        //             'ewl_amount' => $item->ewl_amount - $item->s_or_amount
        //         ]);
        //     }

        //     return[
        //         'cm'=>$item->ewl_or
        //     ];
        // });

        // $count = $query->count();
        // ----------------------------------------------------------------------------------------------------------------

        $query = collect(
            DB::table('sales as s')
                ->join('e_wallet as ew','s.cm_id','=','ew.cm_id')
                ->get()
        );

        $map = $query->map(function($item){
            if($item->e_wallet_added > 0 || $item->e_wallet_added != NULL){
                $ew = new EWALLET_LOG;
                $ew->ew_id = $item->ew_id;
                $ew->ewl_amount = $item->e_wallet_added;
                $ew->ewl_status = 'U';
                $ew->ewl_or = $item->s_or_num;
                $ew->ewl_date = $item->s_bill_date;
                $ew->save();
                if($ew){
                    $ewallet = collect(DB::table('e_wallet')
                    ->where('ew_id',$item->ew_id)
                    ->first());

                    EWALLET::where('ew_id',$item->ew_id)
                    ->update([
                        'ew_total_amount' => $ewallet['ew_total_amount'] + $item->e_wallet_added
                    ]);
                }
            }
            if($item->e_wallet_applied > 0 || $item->e_wallet_applied != NULL){
                $ew = new EWALLET_LOG;
                $ew->ew_id = $item->ew_id;
                $ew->ewl_amount = $item->e_wallet_applied;
                $ew->ewl_status = 'A';
                $ew->ewl_or = $item->s_or_num;
                $ew->ewl_date = $item->s_bill_date;
                $ew->save();
                if($ew){
                    $ewallet = collect(DB::table('e_wallet')
                    ->where('ew_id',$item->ew_id)
                    ->first());

                    EWALLET::where('ew_id',$item->ew_id)
                    ->update([
                        'ew_total_amount' => $ewallet['ew_total_amount'] - $item->e_wallet_applied
                    ]);
                }
            }
            return[
                'asd'=> 'sa'
            ];
        });
        return response([
            'Summar_Adjustment_Detail'=> 'ok',
        ],200);
        // $map = $query->map(function($item){
        //     return[
        //         'ewl_amount'=>,
        //         'or_amount',
        //         'added',
        //     ]
        // })
    }
    public function kwhReport(Request $request){
        $location = $request->location;
        $id = $request->id;
        $consid = $request->consid;
        $billperiod =  str_replace("-","",$request->billperiod);
        $locationName = "";

        if($location == "area"){
            $locationName = "ac.ac_id";
        } else if($location == "town"){
            $locationName = "tc.tc_id";
        } else{
            $locationName = "rc.rc_id";
        }

        $result = DB::table('cons_master as cm')
        ->select('rc.rc_code as Route_Number', 'rc.rc_desc as Route_Name', 'cm.cm_account_no as Account_No', 'cm.cm_full_name as NAME', 'mm.mm_serial_no as Meter', 'mr.mr_kwh_used as kWh_Used', 'mr.mr_amount as Amount')
        ->join('route_code as rc', 'cm.rc_id', '=', 'rc.rc_id')
        ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
        ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
        ->join('meter_reg as mr', 'cm.cm_id', '=', 'mr.cm_id')
        ->join('meter_master as mm', 'cm.mm_id', '=', 'mm.mm_id')
        ->whereBetween('mr.mr_kwh_used', [1, 25])
        ->where($locationName, $id)
        ->where('cm.ct_id', $consid)
        ->where('mr.mr_date_year_month', '=', $billperiod)
        ->orderBy('mr.mr_kwh_used')
        ->get();
        
        return response([
            'Message'=> $result,
            'total_consumer'=> count($result),
            'total_kwh_used'=> $result->sum('kWh_Used'),
            'total_amount'=> round($result->sum('Amount'),2),
        ], 200);
    }
    public function perKwhUsed(Request $request){
        $location = $request->location;
        $id = $request->id;
        $to = $request->to;
        $from = $request->from;
        $billperiod =  str_replace("-","",$request->billperiod);
        $locationName = "";

        if($location == "area"){
            $locationName = "ac.ac_id";
        } else if($location == "town"){
            $locationName = "tc.tc_id";
        } else{
            $locationName = "rc.rc_id";
        }

        $result = DB::table('cons_master as cm')
        ->select('rc.rc_code as Route_Number', 'rc.rc_desc as Route_Name', 'mr.mr_prev_reading as PrevRdng', 'mr.mr_pres_reading as PresRdng','cm.cm_account_no as Account_No', 'cm.cm_full_name as NAME', 'ct.ct_desc as Constype', 'cm.cm_address as Address', 'mm.mm_serial_no as Meter', 'mr.mr_kwh_used as kWh_Used', 'mr.mr_amount as Amount')
        ->join('route_code as rc', 'cm.rc_id', '=', 'rc.rc_id')
        ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
        ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
        ->join('meter_reg as mr', 'cm.cm_id', '=', 'mr.cm_id')
        ->join('meter_master as mm', 'cm.mm_id', '=', 'mm.mm_id')
        ->join('cons_type as ct', 'cm.ct_id', '=', 'ct.ct_id')
        ->whereBetween('mr.mr_kwh_used', [$from, $to])
        ->where('mr.mr_date_year_month', '=', $billperiod)
        ->orderBy('mr.mr_kwh_used');

        if($location !== "all"){
            $result->where($locationName, $id);
        }

        $finalResult = $result->get();
        
        // dd($finalResult);
        return response(['Message'=> $finalResult], 200);
    }
    public function listOfBilledOveride(Request $request){
        if($request->location == 'area'){
            $where = 'ac.ac_id';
        }else if($request->location == 'town'){
            $where = 'tc.tc_id';
        }else if($request->location == 'route'){
            $where = 'rc.rc_id';
        }else{
            return response(['info'=>'Location Not Found'],422);
        }

        $query = DB::table('cons_master as cm')
        ->select('cm.cm_account_no as Account_No','cm.cm_full_name as Account_Name','ct.ct_desc as Cons_Type','cm.cm_address as Address',
        'mm.mm_serial_no as Meter_No','mr.mr_pres_reading as Pres_Reading','mr.mr_prev_reading as Prev_Reading','mr.mr_kwh_used as kWh_Used','mr.mr_amount as Amount','ff.ff_desc as Field_Finding')
        ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
        ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
        ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
        ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
        ->join('field_finding as ff','mr.ff_id','=','ff.ff_id')
        ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
        ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
        ->where('mr.mr_date_year_month',$request->bill_month)
        ->where('mr.ff_id','!=',0)
        ->whereNotNull('mr.ff_id');
        if($request->location != 'all'){
            $query->where($where,$request->id);
        }
        
        $finalQuery = collect($query->get());
        if($finalQuery->isEmpty()){
            return response(['info'=>'No Record Found'],422);
        }
        
        return response(['info'=>$finalQuery],200);
    }
}
