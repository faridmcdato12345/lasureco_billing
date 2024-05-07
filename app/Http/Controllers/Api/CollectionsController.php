<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\EWALLET;
use App\Models\EWALLET_LOG;
use App\Models\MeterReg;
use App\Models\Or_Void;
use App\Models\Sales;
use App\Services\AuditTrailService;
use App\Services\GetCollectionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectionsController extends Controller
{
    public function printlistOfUnposted(Request $request)
    {
        // tba later for s_status 
        $unPostedBills = collect(DB::table('sales AS s')
            ->join('cons_master AS cm','s.cm_id','=','cm.cm_id')
            ->join('route_code AS rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
            ->join('user AS em','s.teller_user_id','=','em.user_id')
            // ->select('s.s_bill_date','s.s_or_amount','ac.ac_id','em.gas_fnamesname','s.s_id')
            ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0) as amount,s.s_bill_date,em.user_full_name'))
            ->where('ac.ac_id',$request->ac_id)
            ->whereDate('s.s_bill_date',$request->bill_date)
            ->where('s.ackn_date',NULL)
            // ->where('s.s_ack_receipt', NULL)
            ->groupBy('s.teller_user_id','s.s_bill_date')
            ->get());
        $check = $unPostedBills->first();
        if(!$check)
        {
            return response(['Msg'=> 'No Unposted Collections on Date: '.$request->bill_date.'.'], 422);
        }
        $unPostedBillResult = $unPostedBills->map(function ($unPostedBill){
            return [
                'Teller_Collector' => $unPostedBill->user_full_name,
                'Amount_Collected' => number_format(round($unPostedBill->amount,2),2)
            ];
        });
        $totalAMount = $unPostedBills->sum('amount');
        return response([
            'Unposted_Bill'=>$unPostedBillResult,
            'Total_Amount'=>$totalAMount,
            'date'=>$request->bill_date,
            // 'area'=> '0'.$unPostedBills->first()->ac_id.' '.$unPostedBills->first()->ac_name,
        ], 200);
    }
    public function printSummaryVoidedOR(Request $request)
    {
        $summaryVOR = DB::table('or_void')
            ->select('void_id','s_id','v_or','v_date')
            ->where('v_date',$request->date_voided)
            ->get();
        
        $check = $summaryVOR->first();
        if(!$check)
        {
            return response(['Message'=> 'No Voideded Collection On Date: '.$request->date_voided.'.'], 422);
        }

        return response([
            'Message'=>$summaryVOR,
            'Cashier'=>$request->cashier
        ]);
    }
    public function printSummaryDCRTeller(Request $request)
    {
        if($request->selected == 'unposted')
        {
            $summaryDCR = DB::table('sales as s')
                ->join('cons_master as cm','s.cm_id','=','cm.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
                ->join('billing_rates as br','mr.br_id','=','br.id')
                ->join('emp_master as em','s.teller_user_id','=','em.em_emp_no')
                ->select(DB::raw('br.br_fit_all * mr.mr_kwh_used as fitAll,br.br_uc4_miss_rate_spu * mr.mr_kwh_used as spug,
                    br.br_uc4_miss_rate_red * mr.mr_kwh_used as red,br.br_uc6_envi_rate * mr.mr_kwh_used as ec,
                    br.br_uc2_npccon_rate * mr.mr_kwh_used as scc,br.br_vat_gen * mr.mr_kwh_used as gen,
                    br.br_vat_trans * mr.mr_kwh_used as trans,br.br_vat_distrib_kwh * mr.mr_kwh_used as dist,
                    br.br_vat_systloss * mr.mr_kwh_used as sys,em.gas_fnamesname,rc.rc_code,s.s_or_amount as amount,
                    s.s_bill_amount as pbamount,s.f_id,rc.rc_id,s.teller_user_id,s.s_bill_date,s.s_ack_receipt,
                    s.s_id,rc.rc_code,mr.mr_kwh_used as kwh'))
                ->where('teller_user_id',$request->teller_id)
                ->where('s_ack_receipt','')
                ->where('s_bill_date',$request->bill_date)
                ->orderBy('rc.rc_code')
                ->get();
            
            if(!$summaryDCR->first())
            {
                return response(['Message'=>'No Unposted Collection with the entered data'],422);
            }
            
            $mapped = $summaryDCR->groupBy('rc_code')->map(function($item){
                return[
                    'Teller'=>$item->first()->gas_fnamesname,
                    'Route_Code'=>$item->first()->rc_code,
                    'Number_Of_Bills'=>$item->count('s_bill_id'),
                    'Amount'=>round($item->sum('amount'),2),
                    'Power_Bill'=>round($item->sum('pbamount'),2),
                    'Fit_All'=>round(round($item->sum('fitAll'),4),2),
                    'VAT'=>0,
                    'UC_ME_SPUG'=>round(round($item->sum('spug'),4),2),
                    'UC_ME_RED'=>round(round($item->sum('red'),4),2),
                    'UC_EC'=>round(round($item->sum('ec'),4),2),
                    'UC_SCC'=>round(round($item->sum('scc'),4),2),
                    'GEN_VAT'=>round(round($item->sum('gen'),4),2),
                    'TRANS_VAT'=>round(round($item->sum('trans'),4),2),
                    'DIST_VAT'=>round(round($item->sum('dist'),4),2),
                    'SYSLOSS_VAT'=>round(round($item->sum('sys'),4),2),
                    'OTHERS'=>'',
                    'SURCHARGE'=>'',
                    'Kwh_Used'=>$item->sum('kwh'),
                    'None_Bill'=>$item->where('f_id','!=',0)->sum('s_or_amount'),
                ];
            });


            $grouped = $mapped->groupBy(['Teller','Route_Code']);
            $total = $summaryDCR->groupBy('gas_fnamesname')->map(function($item){
                return[
                    'Number_Of_Bills'=>$item->count('s_id'),
                    'Amount'=>round($item->sum('amount'),2),
                    'Power_Bill'=>round($item->sum('pbamount'),2) - round(round($item->sum('fitAll'),4),2) - round(round($item->sum('spug'),4),2)
                    - round(round($item->sum('red'),4),2) - round(round($item->sum('ec'),4),2) - round(round($item->sum('scc'),4),2) - round(round($item->sum('gen'),4),2)  
                    - round(round($item->sum('trans'),4),2) - round(round($item->sum('dist'),4),2) - round(round($item->sum('sys'),4),2) 
                    - round(round($item->sum('ec'),4),2),
                    'Total_Fit_All'=>round(round($item->sum('fitAll'),4),2),
                    'Total_VAT'=>0,
                    'Total_UC_ME_SPUG'=>round(round($item->sum('spug'),4),2),
                    'Total_UC_ME_RED'=>round(round($item->sum('red'),4),2),
                    'Total_UC_EC'=>round(round($item->sum('ec'),4),2),
                    'Total_UC_SCC'=>round(round($item->sum('scc'),4),2),
                    'Total_GEN_VAT'=>round(round($item->sum('gen'),4),2),
                    'Total_TRANS_VAT'=>round(round($item->sum('trans'),4),2),
                    'Total_DIST_VAT'=>round(round($item->sum('dist'),4),2),
                    'Total_SYSLOSS_VAT'=>round(round($item->sum('sys'),4),2),
                    'Total_OTHERS'=>'',
                    'Total_SURCHARGE'=>'',
                    'Total_Kwh_Used'=>$item->sum('kwh'),
                    'Total'=>round($item->sum('amount'),2),
                    
                ];
            });
            return response([
                'Summary_DCR'=>$grouped,
                'Total_Summary_DCR'=>$total,
            ],200);
            
            return response(['Summary_DCR'=>$summaryDCR],200);
            
        }
        else if($request->selected == 'posted')
        {
            $summaryDCR = collect(DB::table('sales as s')
                ->join('cons_master as cm','s.cm_id','=','cm.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
                ->join('billing_rates as br','mr.br_id','=','br.id')
                ->join('emp_master as em','s.teller_user_id','=','em.em_emp_no')
                ->select(DB::raw('br.br_fit_all * mr.mr_kwh_used as fitAll,br.br_uc4_miss_rate_spu * mr.mr_kwh_used as spug,
                    br.br_uc4_miss_rate_red * mr.mr_kwh_used as red,br.br_uc6_envi_rate * mr.mr_kwh_used as ec,
                    br.br_uc2_npccon_rate * mr.mr_kwh_used as scc,br.br_vat_gen * mr.mr_kwh_used as gen,
                    br.br_vat_trans * mr.mr_kwh_used as trans,br.br_vat_distrib_kwh * mr.mr_kwh_used as dist,
                    br.br_vat_systloss * mr.mr_kwh_used as sys,em.gas_fnamesname,rc.rc_code,s.s_or_amount as amount,
                    s.s_bill_amount as pbamount,s.f_id,rc.rc_id,s.teller_user_id,s.s_bill_date,s.s_ack_receipt,
                    s.s_id,rc.rc_code,mr.mr_kwh_used as kwh'))
                ->where('s.teller_user_id',$request->teller_id)
                ->where('s.s_ack_receipt','!=','')
                ->where('s.s_bill_date',$request->bill_date)
                ->orderBy('rc.rc_code')
                ->get());
            if(!$summaryDCR->first())
            {
                return response(['Message'=>'No Posted Collection with the entered data'],422);
            }
            
            $mapped = $summaryDCR->groupBy('rc_code')->map(function($item){
                return[
                    'Teller'=>$item->first()->gas_fnamesname,
                    'Route_Code'=>$item->first()->rc_code,
                    'Number_Of_Bills'=>$item->count('s_bill_id'),
                    'Amount'=>round($item->sum('amount'),2),
                    'Power_Bill'=>round($item->sum('pbamount'),2),
                    'Fit_All'=>round(round($item->sum('fitAll'),4),2),
                    'VAT'=>0,
                    'UC_ME_SPUG'=>round(round($item->sum('spug'),4),2),
                    'UC_ME_RED'=>round(round($item->sum('red'),4),2),
                    'UC_EC'=>round(round($item->sum('ec'),4),2),
                    'UC_SCC'=>round(round($item->sum('scc'),4),2),
                    'GEN_VAT'=>round(round($item->sum('gen'),4),2),
                    'TRANS_VAT'=>round(round($item->sum('trans'),4),2),
                    'DIST_VAT'=>round(round($item->sum('dist'),4),2),
                    'SYSLOSS_VAT'=>round(round($item->sum('sys'),4),2),
                    'OTHERS'=>'',
                    'SURCHARGE'=>'',
                    'Kwh_Used'=>$item->sum('kwh'),
                    'None_Bill'=>$item->where('f_id','!=',0)->sum('s_or_amount'),
                ];
            });


            $grouped = $mapped->groupBy(['Teller','Route_Code']);
            $total = $summaryDCR->groupBy('gas_fnamesname')->map(function($item){
                return[
                    'Number_Of_Bills'=>$item->count('s_id'),
                    'Amount'=>round($item->sum('amount'),2),
                    'Power_Bill'=>round($item->sum('pbamount'),2) - round(round($item->sum('fitAll'),4),2) - round(round($item->sum('spug'),4),2)
                    - round(round($item->sum('red'),4),2) - round(round($item->sum('ec'),4),2) - round(round($item->sum('scc'),4),2) - round(round($item->sum('gen'),4),2)  
                    - round(round($item->sum('trans'),4),2) - round(round($item->sum('dist'),4),2) - round(round($item->sum('sys'),4),2) 
                    - round(round($item->sum('ec'),4),2),
                    'Total_Fit_All'=>round(round($item->sum('fitAll'),4),2),
                    'Total_VAT'=>0,
                    'Total_UC_ME_SPUG'=>round(round($item->sum('spug'),4),2),
                    'Total_UC_ME_RED'=>round(round($item->sum('red'),4),2),
                    'Total_UC_EC'=>round(round($item->sum('ec'),4),2),
                    'Total_UC_SCC'=>round(round($item->sum('scc'),4),2),
                    'Total_GEN_VAT'=>round(round($item->sum('gen'),4),2),
                    'Total_TRANS_VAT'=>round(round($item->sum('trans'),4),2),
                    'Total_DIST_VAT'=>round(round($item->sum('dist'),4),2),
                    'Total_SYSLOSS_VAT'=>round(round($item->sum('sys'),4),2),
                    'Total_OTHERS'=>'',
                    'Total_SURCHARGE'=>'',
                    'Total_Kwh_Used'=>$item->sum('kwh'),
                    'Total'=>round($item->sum('amount'),2),
                    
                ];
            });
            return response([
                'Summary_DCR'=>$grouped,
                'Total_Summary_DCR'=>$total,
            ],200);
        }

        return response(['Msg'=>'Error'],422);

    }
    // tba -> or_amount/bill
    public function setSummaryDCRTellerTAmount(Request $request)
    {
        if($request->selected == 'unposted')
        {
            $summDCRTAmount = DB::table('sales')
                ->where('teller_user_id',$request->teller_id)
                ->where('s_ack_receipt','')
                ->where('s_bill_date',$request->bill_date)
                ->sum('s_or_amount');
            if(!$summDCRTAmount)
            {
                return response(['Message'=>'No Unposted Collection with the entered data'],422);
            }
            
            return response(['Total_Collection'=>$summDCRTAmount],200);
        }
        else if($request->selected == 'posted')
        {
            $summDCRTAmount = DB::table('sales')
                ->where('teller_user_id',$request->teller_id)
                ->where('s_ack_receipt','!=','')
                ->where('s_bill_date',$request->bill_date)
                ->sum('s_or_amount');

            if(!$summDCRTAmount)
            {
                return response(['Message'=>'No Posted Collection with the entered data'],422);
            }
            $minOR = DB::table('sales')
                ->where('teller_user_id',$request->teller_id)
                ->where('s_ack_receipt','!=','')
                ->where('s_bill_date',$request->bill_date)
                ->min('s_or_num');
            
            $maxOR = DB::table('sales')
            ->where('teller_user_id',$request->teller_id)
            ->where('s_ack_receipt','!=','')
            ->where('s_bill_date',$request->bill_date)
            ->max('s_or_num');
                


            return response([
                'Total_Collection'=>round($summDCRTAmount,2),
                'TOR_No_From'=>$minOR,
                'TOR_No_To'=>$maxOR
            ],200);
        }

        return response(['Msg'=>'Error'],422);
    }
    public function setTransferCollectionAmount(Request $request)
    {
        $collectionAmount = DB::table('sales')
            ->whereBetween('s_bill_date',[$request->bill_date_from,$request->bill_date_to])
            ->whereBetween('s_or_num',[$request->or_from,$request->or_to])
            ->where('teller_user_id',$request->teller_id)
            ->sum('s_or_amount');

        if(!$collectionAmount)
        {
            return response(['Message'=>'No Collections with the given data'],422);
        }
        
        return response(['Message'=>$collectionAmount],200);
    }
    public function transferCollection(Request $request)
    {
        $orDetails = DB::table('sales')
            ->whereBetween('s_bill_date',[$request->bill_date_from,$request->bill_date_to])
            ->whereBetween('s_or_num',[$request->or_from,$request->or_to])
            ->get();
        $check = $orDetails->first();
        if(!$check)
        {
            return response(['Message'=> 'No Collection with the entered data'], 422);
        }
        
        DB::table('sales')
            ->whereBetween('s_bill_date',[$request->bill_date_from,$request->bill_date_to])
            ->whereBetween('s_or_num',[$request->or_from,$request->or_to])
            ->update(['teller_user_id'=>$request->teller_id]);

        return response(['Message'=>'Succesfully Transfered Collection'],200);
    }
    public function setpostCollectionAmount(Request $request)
    {
        $amountPost = DB::table('sales')
            ->whereBetween('s_or_num',[$request->or_from,$request->or_to])
            ->sum('s_or_amount');

        if(!$amountPost)
        {
            return response(['Message'=> 'No Collection with the entered data'], 422);
        }

        return response(['Total_Collection'=> $amountPost], 200);
    }
    // public function postCollection(Request $request)
    // {
    //     $tellerCollections = DB::table('sales')
    //         ->whereBetween('s_or_num',[$request->or_from,$request->or_to])
    //         ->get();
    //     $check = $tellerCollections->first();
    //     if(!$check)
    //     {
    //         return response(['Message'=> 'No Collection with the entered data'], 422);
    //     }

    //     $current_date_time = Carbon::now()->toDateTimeString();
        
    //     DB::table('sales')
    //         ->whereBetween('s_or_num',[$request->or_from,$request->or_to])
    //         ->update([
    //             's_ack_receipt'=>$request->ack_receipt,
    //             'ackn_date'=>$current_date_time
    //             // 'ackn_user_id'=>$request->user_id, tbc
    //         ]);
        
    //     return response(['Message'=> 'Succesfully Posted Collection'], 200);
    // }
    public function locateOR($id)
    {
        $orDetails = DB::table('sales AS s')
            ->join('cons_master AS cm','s.cm_id','=','cm.cm_id')
            ->leftJoin('fees AS f','s.f_id','=','f.f_id')
            ->join('meter_reg AS mr','s.mr_id','=','mr.mr_id')
            ->select('s.s_or_num','s_bill_date','cm.cm_account_no','cm.cm_full_name','f.f_code','f.f_description','mr.mr_date_year_month','s.s_or_amount')
            ->where('cm.cm_id',$id)
            ->orderBy('mr.mr_date_year_month','asc')
            ->get();
        $check = $orDetails->first();
        if(!$check)
        {
            return response(['Message'=> 'No OR To Locate'],422);
        }

        $advPowerBill = DB::table('sales AS s')
        ->join('cons_master AS cm','s.cm_id','=','cm.cm_id')
        ->leftJoin('fees AS f','s.f_id','=','f.f_id')
        ->leftJoin('meter_reg AS mr','s.mr_id','=','mr.mr_id')
        ->select('s.s_or_num','s_bill_date','cm.cm_account_no','cm.cm_full_name','f.f_code','f.f_description','mr.mr_date_year_month','s.e_wallet_added')
        ->where('cm.cm_id',$id)
        ->where('s.e_wallet_added','!=', 0)
        ->orderBy('s.s_bill_date','asc')
        ->get();
        
        return response([
            'Consumer_OR_Details'=>$orDetails,
            'Advance_Power_Bill'=>$advPowerBill
        ]);
    }
    //tbc db 
    public function cashierDCR(Request $request)
    {
        $cashierDCR = DB::table('sales AS s')
            ->leftJoin('meter_reg AS mr','s.mr_id','=','mr.mr_id')
            ->leftJoin('billing_rates AS br','mr.br_id','=','br.id')
            ->leftJoin('emp_master AS em','em.em_emp_no','=','s.teller_user_id')
            ->whereBetween('s.s_bill_date',[$request->bill_date_from,$request->bill_date_to])
            ->orderBy('teller_user_id','asc')
            ->get();
        
        $check = $cashierDCR->first();
        if(!$check)
        {
            return response(['Message'=>'No tellers DCR with the entered data'],422);
        }
        
        $collections = collect($cashierDCR);
        $dcrColls = $collections->map(function ($collection) {
            return [
                'Name' => $collection->gas_fnamesname,
                'Total_Amount' => $collection->s_or_amount,
                'Number_of_Bills' => $collection->s_id,
                'KWH_Used' => $collection->mr_kwh_used,
                'Amount' => $collection->s_bill_amount,
                'ME' => $collection->br_uc4_miss_rate_spu * $collection->mr_kwh_used,
                'EC' => $collection->br_uc6_envi_rate * $collection->mr_kwh_used,
                'CC' => $collection->br_uc2_npccon_rate * $collection->mr_kwh_used,
                'Gen_Sys_Loss' => ($collection->br_vat_systloss * $collection->mr_kwh_used) + ($collection->br_vat_gen * $collection->mr_kwh_used),
                'Trans' => $collection->br_vat_trans * $collection->mr_kwh_used,
                'Dist_Others' => $collection->br_vat_distrib_kwh * $collection->mr_kwh_used,
            ];
        });
        $groupDCRColls = $dcrColls->groupBy('Name');
        $dcrCollSum = $groupDCRColls->map(function($groupDCRColl){
            return[
                'Total_Amount' => $groupDCRColl->sum('Total_Amount'),
                'Number_Of_Bills' => $groupDCRColl->count('Number_of_Bills'),
                'KWH_Used' => $groupDCRColl->sum('KWH_Used'),
                'Amount' => $groupDCRColl->sum('Amount'),
                'ME' => $groupDCRColl->sum('ME'),
                'EC' => $groupDCRColl->sum('EC'),
                'CC' => $groupDCRColl->sum('CC'),
                'Gen_Sys_Loss' => $groupDCRColl->sum('Gen_Sys_Loss'),
                'Trans' => $groupDCRColl->sum('Trans'),
                'Dist_Others' => $groupDCRColl->sum('Dist_Others'),
            ];
        });
        $totalColls = [
            'Total_Amount'=>$dcrCollSum->sum('Total_Amount'),
            'Number_Of_Bills'=>$dcrCollSum->sum('Number_Of_Bills'),
            'KWH_Used'=>$dcrCollSum->sum('KWH_Used'),
            'Amount'=>$dcrCollSum->sum('Amount'),
            'ME' => $dcrCollSum->sum('ME'),
            'EC' => $dcrCollSum->sum('EC'),
            'CC' => $dcrCollSum->sum('CC'),
            'Gen_Sys_Loss' => $dcrCollSum->sum('Gen_Sys_Loss'),
            'Trans' => $dcrCollSum->sum('Trans'),
            'Dist_Others' => $dcrCollSum->sum('Dist_Others'),
        ];
        $typeOfBIll = [
            'Total_Bill'=>$dcrCollSum->sum('Total_Amount'),
            'Total_NBill'=>'TBD',
            'Total_Cash'=>'TBD',
            'Total_Check'=>'TBD'
        ];
        return response([
            'Cashier_DCR'=>$dcrCollSum,
            'Total_Collection'=>$totalColls,
            'Type_Of_Bills'=>$typeOfBIll
        ],200);
    }
    public function setAmountCashierDCR(Request $request)
    {
        $cashierDCR = DB::table('sales AS s')
            // ->leftJoin('meter_reg AS mr','s.mr_id','=','mr.mr_id')
            // ->leftJoin('billing_rates AS br','mr.br_id','=','br.id')
            // ->leftJoin('user AS em','em.user_id','=','s.teller_user_id')
            ->whereBetween('s.s_bill_date',[$request->bill_date_from,$request->bill_date_to])
            ->sum('s.s_or_amount'); 
        
        if(!$cashierDCR)
        {
            return response(['Message'=>'No Cashier DCR'], 422);
        }

        return response(['Total_Collection'=>$cashierDCR],200);
    }
    public function collectionList($teller)
    {
        $date = date("Y-m-d");
        $collectionList = collect(DB::table('sales AS s')
            ->join('cons_master AS cm','s.cm_id','=','cm.cm_id')
            ->leftJoin('fees AS f','s.f_id','=','f.f_id')
            ->leftJoin('meter_reg AS mr','s.mr_id','=','mr.mr_id')
            ->select('cm.cm_account_no','cm.cm_full_name','f.f_id','f.f_code','mr.mr_date_year_month',
                's.s_bill_no','s.s_or_num','s.s_bill_date','s.s_or_amount','s.v_id','s.s_mode_payment','s.e_wallet_added')
            ->whereDate('s.s_bill_date',$date)
            ->where('s.teller_user_id',$teller)
            ->get());
        
        $collection =$collectionList->map(function($item){
        
            
            if($item->f_id == NULL && $item->s_mode_payment == 'Deposit_Ewallet'){
                $type = 'D';
            }else if($item->f_id == NULL && $item->s_mode_payment != 'Deposit_Ewallet'){
                $type = 'B';
            }else{
                $type = 'N';
            }
            
            $fCode = ($item->f_id == '' || $item->f_id == 0)? '': $item->f_id;
                return[
                    'Account_No'=>$item->cm_account_no,
                    'Payee'=>$item->cm_full_name,
                    'Type'=> $type,
                    'Fee_code'=>$fCode,
                    'Period'=>($item->mr_date_year_month == NULL) ? "N.A" : $item->mr_date_year_month,
                    'Bill_No'=>($item->s_bill_no == NULL) ? "N.A" : $item->s_bill_no,
                    'TOR_No'=>$item->s_or_num,
                    'TOR_Date'=>$item->s_bill_date,
                    'Amount_Receipt'=>$item->s_or_amount + $item->e_wallet_added,
                    'Void'=> 'No',
                ];
        });

        $collectionListVoided = collect(
            DB::table('or_void as or')
                ->leftJoin('meter_reg as mr','or.mr_id','=','mr.mr_id')
                ->leftJoin('cons_master as cm','or.cm_id','=','cm.cm_id')
                ->whereDate('or.v_date',$date)
                ->where('or.v_user',$teller)
                ->get()
        );
        
        $collectionVoided =$collectionListVoided->map(function($item){
        
            // $type = ($item->f_id == '' || $item->f_id == 0)?'B':'N';
            if($item->f_id == NULL && $item->mr_id == NULL){
                $type = 'D';
            }else if($item->f_id == NULL && $item->mr_id != NULL){
                $type = 'B';
            }else{
                $type = 'N';
            }
            $fCode = ($item->f_id == '' || $item->f_id == 0)? '': $item->f_id;
                return[
                    'Account_No'=>$item->cm_account_no,
                    'Payee'=>$item->cm_full_name,
                    'Type'=> $type,
                    'Fee_code'=>$fCode,
                    'Period'=>($item->mr_date_year_month == NULL) ? "N.A" : $item->mr_date_year_month,
                    'Bill_No'=>($item->v_bill_num == NULL) ? "N.A" : $item->v_bill_num,
                    'TOR_No'=>$item->v_or,
                    'TOR_Date'=>$item->v_date,
                    'Amount_Receipt'=>$item->v_sale_amount,
                    'Void'=> 'Yes',
                ];
        });
        // dd($collectionVoided);
        $combined = $collection->merge($collectionVoided)
            ->sortBy('TOR_No');
        
        if($combined->isEmpty())
        {
            return response(['Message'=>'No Collections or Transactions For Today.'],422);
        }
        
        return response(['Collection_List_For_The_Day'=>$combined->values()->all()],200);
    }
    public function voidTransactionOR($or,$teller,$remark)
    {
        $date = date("Y-m-d");
        $orDetails = collect(DB::table('sales')
            ->select('s_id','s_status','s_or_num','s_bill_date','cm_id','mr_id')
            ->where('s_or_num',$or)
            ->where('teller_user_id',$teller)
            ->whereDate('s_bill_date',$date)
            // ->where('s_status',0)
            ->whereNull('deleted_at')
            ->first());
        if(!isset($orDetails['s_id']))
        {
            return response(['Message'=>'No Transaction for this day'],422);
        }
        $current_date_time = Carbon::now()->toDateTimeString();

        //GET PAID POWER BILL ID
        $powerBillID = DB::table('sales')
        // ->select('mr_id','s_bill_no','s_or_amount','teller_user_id','e_wallet_added','ct_id',DB::raw('COALESCE(SUM(e_wallet_applied),0) as e_applied'))
        // ->select('mr_id','s_bill_no','s_or_amount','teller_user_id','e_wallet_added','ct_id','e_wallet_applied')
        ->where('s_or_num',$or)
        ->where('teller_user_id',$teller)
        ->whereNotNull('mr_id')
        // ->where('s_status',0)
        ->whereNull('deleted_at')
        ->get();
        
        //GET PAID Non BILL
        $nonBill = DB::table('sales')
        ->select('f_id','s_bill_no','s_or_amount','teller_user_id','cm_id','e_wallet_added')
        ->where('s_or_num',$or)
        ->where('teller_user_id',$teller)
        ->whereNull('mr_id')
        ->whereNotNull('f_id')
        ->whereNull('deleted_at')
        ->get();
        
        $depositBill = DB::table('sales')
        ->select('s_bill_no','s_or_amount','teller_user_id','cm_id','e_wallet_added')
        ->where('s_or_num',$or)
        ->where('teller_user_id',$teller)
        ->where('s_mode_payment','Deposit_Ewallet')
        ->whereNull('deleted_at')
        ->get();
        if($powerBillID->isNotEmpty()){
            // dd('1');
            // Revert Changes to Not paid Power BIll
            foreach($powerBillID as $pbID=>$value)
            {
                if($value->ct_id == 3){

                    $mtrReg = MeterReg::find($value->mr_id);
                    $mtrReg->mr_partial = $mtrReg->mr_partial - $value->s_or_amount - (($value->e_wallet_applied == NULL) ? 0 : $value->e_wallet_applied);
                    $mtrReg->mr_status = 0;
                    $mtrReg->save();

                    // ->where('cm_id',$orDetails['cm_id'])
                    // ->whereNotNull('mr_id')
                    // ->update([
                    //     'mr_status' => 0,
                    //     'mr_partial' => $value->s_or_amount
                    // ]);

                }else{
                    MeterReg::where('mr_id',$value->mr_id)
                    ->where('cm_id',$orDetails['cm_id'])
                    ->whereNotNull('mr_id')
                    ->update([
                        'mr_status' => 0
                    ]);
                }

                //Create Voided Data on OR_Void Table
                $data = new Or_Void();
                $data->cm_id = $orDetails['cm_id'];
                $data->v_or = $orDetails['s_or_num'];
                $data->mr_id = $value->mr_id;
                $data->v_bill_num = $value->s_bill_no;
                $data->v_sale_amount = $value->s_or_amount + ($value->e_wallet_added == NULL) ? 0 : $value->e_wallet_added;
                $data->v_remark = $remark;
                $data->v_user = $value->teller_user_id;
                $data->v_date = $current_date_time;
                $data->save();
            }
        }
        if($nonBill->isNotEmpty()){
            // dd('2');
            foreach($nonBill as $nbID=>$value1)
            {
                //Create Voided Data on OR_Void Table
                $data2 = new Or_Void();
                $data2->cm_id = $orDetails['cm_id'];
                $data2->v_or = $orDetails['s_or_num'];
                $data2->f_id = $value1->f_id;
                $data2->v_sale_amount = $value1->s_or_amount + $value1->e_wallet_added;
                $data2->v_remark = $remark;
                $data2->v_user = $value1->teller_user_id;
                $data2->v_date = $current_date_time;
                $data2->save();
            }
        }
        if($depositBill->isNotEmpty()){
            // dd('3');
            foreach($depositBill as $dID=>$value2)
            {
                //Create Voided Data on OR_Void Table
                $data3 = new Or_Void();
                $data3->mr_id = NULL;
                $data3->f_id = NULL;
                $data3->cm_id = $orDetails['cm_id'];
                $data3->v_or = $orDetails['s_or_num'];
                $data3->v_sale_amount = $value2->s_or_amount + ($value2->e_wallet_added == NULL) ? 0 : $value2->e_wallet_added;
                $data3->v_remark = $remark;
                $data3->v_user = $value2->teller_user_id;
                $data3->v_date = $current_date_time;
                $data3->save();
            }
        }
        // if($powerBillID->isEmpty())
        // {
        //     //GET PAID Non BILL
        //     $nonBill = DB::table('sales')
        //     ->select('f_id','s_bill_no','s_or_amount','teller_user_id')
        //     ->where('s_or_num',$or)
        //     ->where('teller_user_id',$teller)
        //     ->whereNull('mr_id')
        //     ->whereNull('deleted_at')
        //     ->get();
        //     // dd($nonBill);
        //     foreach($nonBill as $nbID=>$value1)
        //     {
        //         //Create Voided Data on OR_Void Table
        //         $data2 = new Or_Void();
        //         $data2->v_or = $orDetails['s_or_num'];
        //         $data2->f_id = $value1->f_id;
        //         $data2->v_sale_amount = $value1->s_or_amount;
        //         $data2->v_remark = $remark;
        //         $data2->v_user = $value1->teller_user_id;
        //         $data2->v_date = $current_date_time;
        //         $data2->save();
        //     }
        // }else{
        //      //Revert Changes to Not paid Power BIll
        //     foreach($powerBillID as $pbID=>$value)
        //     {
        //         MeterReg::where('mr_id',$value->mr_id)
        //         ->where('cm_id',$orDetails['cm_id'])
        //         ->whereNotNull('mr_id')
        //         ->update([
        //             'mr_status' => 0
        //         ]);

        //         //Create Voided Data on OR_Void Table
        //         $data = new Or_Void();
        //         $data->v_or = $orDetails['s_or_num'];
        //         $data->mr_id = $value->mr_id;
        //         $data->v_bill_num = $value->s_bill_no;
        //         $data->v_sale_amount = $value->s_or_amount;
        //         $data->v_remark = $remark;
        //         $data->v_user = $value->teller_user_id;
        //         $data->v_date = $current_date_time;
        //         $data->save();
        //     }

        //     //GET PAID Non BILL
        //     $nonBill = DB::table('sales')
        //     ->select('f_id','s_bill_no','s_or_amount','teller_user_id')
        //     ->where('s_or_num',$or)
        //     ->where('teller_user_id',$teller)
        //     ->whereNull('mr_id')
        //     ->whereNull('deleted_at')
        //     ->get();
        //     if(!$powerBillID->isEmpty())
        //     {
        //         foreach($nonBill as $nbID=>$value1)
        //         {
        //             //Create Voided Data on OR_Void Table
        //             $data2 = new Or_Void();
        //             $data2->v_or = $orDetails['s_or_num'];
        //             $data2->f_id = $value1->f_id;
        //             $data2->v_sale_amount = $value1->s_or_amount;
        //             $data2->v_remark = $remark;
        //             $data2->v_user = $value1->teller_user_id;
        //             $data2->v_date = $current_date_time;
        //             $data2->save();
        //         }
        //     }
        // }
        
        // select the Applied and Added Ewallet (To Revert Changes)
        $ewalletTransactApplied = collect(DB::table('sales')
            ->select(DB::raw('sum(e_wallet_applied) as e_wallet_applied'))
            ->where('s_or_num',$orDetails['s_or_num'])
            ->Where('e_wallet_applied','!=',0)
            ->where('cm_id',$orDetails['cm_id'])
            ->Where('e_wallet_applied','!=','')
            ->where('teller_user_id',$teller)
            ->first());
        $ewalletTransactAdded = collect(DB::table('sales')
            ->select(DB::raw('sum(e_wallet_added) as e_wallet_added'))
            ->where('s_or_num',$orDetails['s_or_num'])
            ->Where('e_wallet_added','!=',0)
            ->where('cm_id',$orDetails['cm_id'])
            ->Where('e_wallet_added','!=','')
            ->where('teller_user_id',$teller)
            ->first());
        // dd($ewalletTransactApplied);
        $ewApplied = isset($ewalletTransactApplied['e_wallet_applied']) ? $ewalletTransactApplied['e_wallet_applied'] : 0;
        $ewAdded = isset($ewalletTransactAdded['e_wallet_added']) ? $ewalletTransactAdded['e_wallet_added'] : 0;
        // dd($ewApplied);
        //GET CURRENT E WALLET TOTAL AMOUNT
        $ewallet = collect(DB::table('e_wallet')
            ->where('cm_id',$orDetails['cm_id'])
            ->first());
        

        //ADD APPLIED EWALLET TO EWALLET TOTAL AMOUNT(revert changes) Void Ewallet_LOG
        if($ewApplied != 0)
        {
            $newWalletTotal = $ewallet['ew_total_amount'] + $ewApplied;
            EWALLET::where('cm_id',$orDetails['cm_id'])
            ->update([
                'ew_total_amount' => $newWalletTotal
            ]);

            EWALLET_LOG::where('ewl_or',$orDetails['s_or_num'])
            ->where('ewl_status','A')
            ->where('ew_id',$ewallet['ew_id'])
            ->update([
                'deleted_at' => $current_date_time,
            ]);
        }
        
        //DEDUCT ADDED EWALLET TO EWALLET TOTAL AMOUNT(revert changes) AND Void Ewallet_LOG
        if($ewAdded != 0)
        {
            $newWalletTotal2 = $ewallet['ew_total_amount'] - $ewAdded;
            
            EWALLET::where('cm_id',$orDetails['cm_id'])
            ->update([
                'ew_total_amount' => $newWalletTotal2
            ]);

            EWALLET_LOG::where('ewl_or',$orDetails['s_or_num'])
            ->where('ewl_status','U')
            ->where('ew_id',$ewallet['ew_id'])
            ->update([
                'deleted_at' => $current_date_time,
            ]);
        }
        
        //GETTING E WALLET ID FOR VOIDING EWALLET LOG
        // $getEwalletID = collect(DB::table('e_wallet')
        //     ->where('cm_id',$orDetails['cm_id'])
        //     ->first());
        //Voding E_Wallet_Log table(revert changes)
        // EWALLET_LOG::where('ewl_or',$orDetails['s_or_num'])
        // ->where('ewl_status','A')
        // ->where('ew_id',$getEwalletID['ew_id'])
        // ->update([
        //     'deleted_at' => $current_date_time,
        // ]);

        //For Audit Trail
        // $at_old_value = '';
        // $at_new_value = '';
        // $at_action = 'Void';
        // $at_table = 'Sales';
        // $at_auditable = $orDetails['s_or_num'];
        // $user_id = $teller;
        // $user_id = 41;
        // $data = (new AuditTrailService())->auditTrail($at_old_value,$at_new_value,$at_action,$at_table,$at_auditable,$user_id,$orDetails['cm_id']);
        // Void Sale Transaction
        
        // ADDITIONAL CODE FOR EWALLET CREDIT REVERT CHANGES
        $ewalletCreditApplied = collect(DB::table('sales')
        ->select(DB::raw('sum(s_or_amount) as amount'))
        ->where('s_or_num',$orDetails['s_or_num'])
        ->where('cm_id',$orDetails['cm_id'])
        ->whereNull('e_wallet_applied')
        ->whereNull('e_wallet_added')
        ->where('s_status',1)
        ->where('teller_user_id',$teller)
        ->first());

        $ewCreditApplied = isset($ewalletCreditApplied['amount']) ? $ewalletCreditApplied['amount'] : 0;
        if($ewCreditApplied != 0)
        {
            //GET CURRENT E WALLET TOTAL AMOUNT
            $ewallet2 = collect(DB::table('e_wallet')
            ->where('cm_id',$orDetails['cm_id'])
            ->first());

            $newWalletTotal3 = $ewallet2['ew_total_amount'] - $ewCreditApplied;
           
            EWALLET::where('cm_id',$orDetails['cm_id'])
            ->update([
                'ew_total_amount' => $newWalletTotal3
            ]);

            // EWALLET_LOG::where('ewl_or',$orDetails['s_or_num'])
            // ->where('ewl_status','U')
            // ->where('ew_id',$ewallet['ew_id'])
            // ->update([
            //     'deleted_at' => $current_date_time,
            // ]);
        }

        DB::table('sales')
        ->where('s_or_num',$orDetails['s_or_num'])
        ->where('s_bill_date',$orDetails['s_bill_date'])
        ->where('cm_id',$orDetails['cm_id'])
        ->delete();

        
        return response([
            'Message'=>'Successfully Voided'
        ],200);
    }
    public function showTransactionVoid($teller)
    {
        $date = date("Y-m-d");
        $transactionList = collect(DB::table('sales AS s')
            ->join('cons_master AS cm','s.cm_id','=','cm.cm_id')
            ->select(DB::raw('s.s_or_num,sum(s.s_or_amount) as totalORAmount,sum(s.s_bill_amount) as totalBillAmount,cm.cm_full_name,cm.cm_account_no,sum(s.e_wallet_added) as ewAdded,s.s_mode_payment'))
            ->whereDate('s.s_bill_date',$date)
            ->where('s.teller_user_id',$teller)
            ->groupBy('s.s_or_num')
            ->get());
            // dd($transactionList);
        $check = $transactionList->first();
        if(!$check)
        {
            return response(['Message'=>'No Collections or Transactions For Today.'],422);
        }
        
        $mapped = $transactionList->map(function($item){
            // if($item->s_mode_payment != 'Deposit_Ewallet'){
            //     $tAmount = $item->totalORAmount + $item->ewAdded;
            // }else{
            //     $tAmount = $item->ewAdded;
            // }
            $tAmount = $item->totalORAmount + $item->ewAdded;
            
            return[
                'Account_Number'=>$item->cm_account_no,
                'Payee'=>$item->cm_full_name,
                'OR_Number'=>$item->s_or_num,
                'Total_Amount'=>round($tAmount,2)
            ];
        });
        return response([
            'OR_Details'=>$mapped
        ]);
    }
    public function printTellersDailyCollection(Request $request)
    {
        
        $dailyCollection = collect(DB::table('sales as s')
            ->join('cons_master as cm','s.cm_id','=','cm.cm_id')
            ->leftJoin('meter_reg as mr','s.mr_id','=','mr.mr_id')
            ->leftJoin('cheque as ch','s.cheque_id','=','ch.cheque_id')
            ->leftJoin('fees as fe','s.f_id','=','fe.f_id')
            ->join('user as u','s.teller_user_id','=','u.user_id')
            ->where('s.teller_user_id',$request->user_id)
            ->where('s_mode_payment','!=','Deposit_Ewallet')
            ->where('s.s_bill_date',$request->date)
            ->get());
        // dd($dailyCollection);
        $dailyCollectionEWAdded = collect(DB::table('sales as s')
            ->join('cons_master as cm','s.cm_id','=','cm.cm_id')
            ->where('e_wallet_added','!=',0)
            ->where('s.teller_user_id',$request->user_id)
            ->where('s.s_bill_date',$request->date)
            ->get());
        // For Display Collection PB AND NB
        $mapDcr = $dailyCollection->map(function($item){
            $paymentDesc = '';
            if($item->mr_arrear == ''){
                $paymentType = '(NB)';
                $paymentDesc = $item->f_description;
            }else if($item->mr_arrear == 'N'){
                $paymentType = 'ARREARS';
            }else{
                $paymentType = 'CURRENT';
            }
            return[
                'Account_No'=> $item->cm_account_no.'@'.$item->cm_full_name.'@'.$item->s_or_num,
                'Payment_Type'=> $paymentType,
                'Payment_Desc'=> $paymentDesc,
                'OR'=> $item->s_or_num,
                'Year_Month'=> $item->mr_date_year_month,
                'Amount'=> $item->s_or_amount,
                'EWallet_Applied'=> ($item->e_wallet_applied == NULL) ? '' : $item->e_wallet_applied,
            ];
        })->sortBy('OR');

        // For Display Collection EWallet Added
        $ewCollection = $dailyCollectionEWAdded->map(function($item){
            return[
                'Account_No'=> $item->cm_account_no.'@'.$item->cm_full_name.'@'.$item->s_or_num,
                'Payment_Type'=> 'E-Wallet Deposit',
                'Payment_Desc'=> '',
                'OR'=> $item->s_or_num,
                'Year_Month'=> '',
                'Amount'=> $item->e_wallet_added,
                'EWallet_Applied'=> '',
            ];
        })->sortBy('OR');
        // Combined Collection PB AND NB AND Ewallet Added
        $merged = $ewCollection->merge($mapDcr);

        // Grouped Merged Account as Index // Final Display of Collection Details PB's,NB's and Ewallet Added
        $groupDCR = $merged->sortBy('OR')->groupBy('Account_No');
        // $groupDCR = $groupDCR1->sortBy('OR');

        //if empty no transaction
        if($groupDCR->isEmpty()){
            return response(['Message'=>'No Transaction for this day'],422);
        }
        $total = collect(array(
            'Total_Bills'=>$groupDCR->count('Payment_Type'),
            'Total_Receipt'=>$groupDCR->count('Payment_Type'),
        ));
        
        $recapNew = collect(array(
            'E-Wallet_Deposit'=>[
                        'Involved_OR'=>$merged->where('Payment_Type','E-Wallet Deposit')->count('E-Wallet Deposit'),
                        'Total_Amount'=>round($merged->where('Payment_Type','E-Wallet Deposit')->sum('Amount'),2),
            ],
            'Non_Bill'=>[
                'Involved_OR'=>$merged->where('Payment_Type','(NB)')->count('(NB)'),
                'Total_Amount'=>round($merged->where('Payment_Type','(NB)')->sum('Amount'),2),
            ],
            'ARREARS'=>[
                'Involved_OR'=>$merged->where('Payment_Type','ARREARS')->count('ARREARS'),
                'Total_Amount'=>round($merged->where('Payment_Type','ARREARS')->sum('Amount'),2),
            ],
            'CURRENT'=>[
                'Involved_OR'=>$merged->where('Payment_Type','CURRENT')->count('CURRENT'),
                'Total_Amount'=>round($merged->where('Payment_Type','CURRENT')->sum('Amount'),2),
            ],
        ));
        // No Display for empty indexes
        if(empty($recapNew['CURRENT']['Involved_OR'])){
            $recapNew->forget('CURRENT');
        }
        if(empty($recapNew['E-Wallet Deposit']['Involved_OR'])){
            $recapNew->forget('Advance_E_Wallet');
        }
        if(empty($recapNew['Non_Bill']['Involved_OR'])){
            $recapNew->forget('Non_Bill');
        }
        if(empty($recapNew['ARREARS']['Involved_OR'])){
            $recapNew->forget('ARREARS');
        }
        $getchequeID = collect($dailyCollection->where('cheque_id','!=',0)->unique('cheque_id'))->values();
        // $getchequeID;
        $mappedCheque = $getchequeID->map(function($item){
            return[
                'Account_No'=>$item->cm_account_no,
                'Cheque_Bank'=>$item->cheque_bank,
                'OR'=>$item->s_or,
                'teller'=>$item->username,
                'Amount'=>$item->cheque_amount,
            ];
        });

        $newTotalDCR = $groupDCR->map(function($item){
            return[
                'Total_Amount'=> $item->sum('Amount'),
            ];
        });

        // Updating Sales (Cutoff to 1) when printing dcr //added audit trail when cutting off
        if($request->cutoff == 1){
            // //For Audit Trail
            $at_old_value = '';
            $at_new_value = '';
            $at_action = 'Cutoff';
            $at_table = 'Sales';
            $at_auditable = '';
            $user_id = $request->user_id;
            $id = null;
            $data = (new AuditTrailService())->auditTrail($at_old_value,$at_new_value,$at_action,$at_table,$at_auditable,$user_id,$id);

            Sales::where('s_bill_date',$request->date)
                ->where('teller_user_id',$request->user_id)
                ->update(['s_cutoff' => 1]);
        }
        
        //Or Void
        $voidedOR = collect(
            DB::table('or_void as or')
                ->join('cons_master as cm','or.cm_id','=','cm.cm_id')
                ->where('v_user',$request->user_id)
                ->where('v_date',$request->date)
                ->get()
        );

        $mappedVoid = $voidedOR->map(function($item){
            return[
                'Account_No'=>$item->cm_account_no,
                'Cons_Name'=>$item->cm_full_name,
                'OR'=>$item->v_or,
                'Amount'=>$item->v_sale_amount,
            ];
        });
        
        if($mappedVoid->isEmpty() && $mappedCheque->isEmpty()){
            return response([
                'Details'=>$groupDCR,
                'Totals'=>$total,
                'Recap'=>$recapNew,
                'Total_Amount'=>$newTotalDCR
            ],200);
        }else if($mappedVoid->isNotEmpty() && $mappedCheque->isEmpty()){
            return response([
                'Details'=>$groupDCR,
                'Totals'=>$total,
                'Recap'=>$recapNew,
                'Void_Details'=>$mappedVoid,
                'Total_Amount'=>$newTotalDCR
            ],200);
        }else if($mappedVoid->isEmpty() && $mappedCheque->isNotEmpty()){
            return response([
                'Details'=>$groupDCR,
                'Totals'=>$total,
                'Recap'=>$recapNew,
                'Cheque'=>$mappedCheque,
                'Total_Amount'=>$newTotalDCR
            ],200);
        }else{
            return response([
                'Details'=>$groupDCR,
                'Totals'=>$total,
                'Recap'=>$recapNew,
                'Cheque'=>$mappedCheque,
                'Void_Details'=>$mappedVoid,
                'Total_Amount'=>$newTotalDCR
            ],200);
        }
    }
    public function setTellersDailyCollectionAmount(Request $request)
    {
        $currentDate = Carbon::now();
        if($currentDate == $request->date)
        {
            if($request->cutoff != 1)
            {
                $cutOffAmount = collect(
                    DB::table('sales as s')
                        ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0)'))
                        ->where('teller_user_id',intval($request->user_id))
                        ->whereDate('s_bill_date',$request->date)
                        ->first()   
                );

                $ewalletAmount = collect(
                    DB::table('sales as s')
                        ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0)'))
                        ->where('teller_user_id',intval($request->user_id))
                        ->whereDate('s_bill_date',$request->date)
                        ->where('s_mode_payment','Ewallet')
                        ->first()
                );
                return response([
                    'Cut_Off_Amount'=>$cutOffAmount->values()->all(),
                    'Total_Collection_Amount'=>$cutOffAmount->values()->all(),
                    'Ewallet_Collection_Amount'=>$ewalletAmount->values()->all(),
                ],200);
                
            }else{
                $date = Carbon::createFromFormat('Y-m-d', $request->date);
                $addedDate = $date->addDays(1);
                $Total_Collection = collect(
                    DB::table('sales as s')
                        ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0)'))
                        ->where('teller_user_id',intval($request->user_id))
                        ->whereBetween('s_bill_date',[$request->date,$addedDate])
                        ->first()
                );

                $cutOffAmount = collect(
                    DB::table('sales as s')
                        ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0)'))
                        ->where('teller_user_id',intval($request->user_id))
                        ->whereDate('s_bill_date',$request->date)
                        ->first()
                );

                $ewalletAmount = collect(
                    DB::table('sales as s')
                        ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0)'))
                        ->where('teller_user_id',intval($request->user_id))
                        ->whereDate('s_bill_date',$request->date)
                        ->where('s_mode_payment','Ewallet')
                        ->first()
                );
                return response([
                    'Cut_Off_Amount'=>$cutOffAmount->values()->all(),
                    'Total_Collection_Amount'=>$Total_Collection->values()->all(),
                    'Ewallet_Collection_Amount'=>$ewalletAmount->values()->all()
                ],200);
                
            }
        }else{
            $cutOffAmount = collect(
                DB::table('sales as s')
                    ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0)'))
                    ->where('teller_user_id',$request->user_id)
                    ->whereDate('s_bill_date',$request->date)
                    ->first()
            );
            $ewalletAmount = collect(
                DB::table('sales as s')
                    ->select(DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0)'))
                    ->where('teller_user_id',intval($request->user_id))
                    ->whereDate('s_bill_date',$request->date)
                    ->where('s_mode_payment','Ewallet')
                    ->first()
            );
            
            return response([
                'Cut_Off_Amount'=>$cutOffAmount->values()->all(),
                'Total_Collection_Amount'=>$cutOffAmount->values()->all(),
                'Ewallet_Collection_Amount'=>$ewalletAmount->values()->all(),
            ],200);
        }
        
        

        return response(['Message'=>'Error! No Transaction For This Day'],422);
    }

    public function collectionPostedReport(Request $request)
    {
        // $date = Carbon::createFromFormat('Y-m', $dp);
        $query = collect(
            DB::table('sales as s')
                ->select(DB::raw('s.teller_user_id,s.s_ack_receipt,s.ackn_date,sum(s.s_or_amount) as or_amount,sum(s.e_wallet_added) as ewAdded,s.s_bill_date,u.user_full_name'))
                ->join('user as u','s.teller_user_id','=','u.user_id')
                // ->whereMonth('s.ackn_date', $date->month)
                // ->whereYear('s.ackn_date', $date->year)
                ->whereBetween('s.ackn_date',[$request->date_from,$request->date_to])
                ->orderBy('s.ackn_date','asc')
                ->groupBy('s.ackn_date','s.teller_user_id','s.s_ack_receipt')
                ->get()
        );

        $map = $query->map(function($item){
            return[
                'Date_Posted'=>$item->ackn_date,
                'Date_Collection'=>$item->s_bill_date,
                'Teller_Name'=>$item->user_full_name,
                'Code'=>'None',
                'Amount'=>round($item->or_amount + $item->ewAdded,2),
                'AR'=>$item->s_ack_receipt,
            ];
        });
        return response([
            'Details'=>$map
        ]);
    }
    public function gcashCollectionReport(Request $request)
    {
        $gcashCollection = collect(DB::table('sales as s')
            ->join('cons_master as cm','s.cm_id','=','cm.cm_id')
            ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
            ->select(DB::raw('cm.cm_account_no, cm.cm_full_name,s.s_ref_num,sum(s.s_bill_amount) as tAmount,s_bill_date'))
            ->where('s_mode_payment','=','Online')
            ->whereDate('s.s_bill_date','>=',$request->date_from)
            ->whereDate('s.s_bill_date','<=',$request->date_to)
            ->where('cm.cm_account_no','NOT LIKE','99%')
            ->orderBy('s.s_bill_date')
            ->groupBy('cm.cm_id','s.s_ref_num')
            ->get());

        if($gcashCollection->isEmpty()){
            return response(['Message'=>'No Record Found'],422);
        }
        $map = $gcashCollection->map(function($item){
            $date = date_create($item->s_bill_date);
            
            return[
                'Account_No'=>$item->cm_account_no,
                'Name'=>$item->cm_full_name,
                'OR_No'=>$item->s_ref_num,
                'Payment_Type'=>'Online',
                'Date'=> date_format($date,"M d Y"),
                'Total_Paid'=>round($item->tAmount,2)
            ];
        });

        return response([
            'collection'=>$map,
            'total_collection'=>round($map->sum('Total_Paid'),2)
        ],200);
    }
    public function uploadedList()
    {
        $query = collect(
            DB::table('sales as s')
            ->select('u.user_full_name as teller','s.s_bill_date as collection_date')
            // ->select(DB::raw(''))
            ->join('user as u','s.teller_user_id','=','u.user_id')
            ->where('s.server_added',1)
            ->groupBy('s.s_bill_date','s.teller_user_id')
            ->orderByDesc('s.s_bill_date')
            ->limit('50')
            ->get()
        );
        if($query->isEmpty()){
            return response(['info'=>'No Record Found'],422);
        }
        return response(['info'=>$query],200);
    }

    public function uploadedListShow(Request $request)
    {
        $query = collect(
            DB::table('sales as s')
            ->select('u.user_full_name as teller','s.s_bill_date as collection_date')
            // ->select(DB::raw(''))
            ->join('user as u','s.teller_user_id','=','u.user_id')
            ->where('s.server_added',1)
            ->whereBetween('s.s_bill_date',[$request->date_from,$request->date_to])
            ->groupBy('s.s_bill_date','s.teller_user_id')
            ->orderByDesc('s.s_bill_date')
            // ->limit('50')
            ->get()
        );
        if($query->isEmpty()){
            return response(['info'=>'No Record Found'],422);
        }
        
        return response(['info'=>$query],200);
    }
    public function collectionPerAR(Request $request)
    {
        $type = 'AR';
        $sales = collect((new GetCollectionService())->salesWitRates($request->date_from,$request->date_to,$type));

        $groupByAR = $sales->groupBy('ar');
        $newSales = $groupByAR->map(function($item){
            if($item[0]['teller_id'] == 1000){
                $teller = 'Online Payment';
            }else{
                $teller = DB::table('user')
                        ->select('user_full_name')
                        ->where('user_id',$item[0]['teller_id'])
                        ->first();
                $teller = $teller->user_full_name;
            }
                
            return[
                'ar_date'=> $item[0]['date'],
                'ar'=> $item[0]['ar'],
                'teller'=> $teller,
                'no_pb'=> round($item->sum('countPB'),2),
                'amount_pb'=> round($item->where('type','PB')->sum('total_amount'),2),
                'no_nb'=> round($item->sum('countNB'),2),
                'amount_nb'=> round($item->where('type','NB')->sum('total_amount'),2),
                'total_collection'=>  round($item->sum('total_amount'),2)
            ];
        })->sortBy('ar_date')->sortBy('ar');
        $newGroup = $newSales->groupBy('ar_date','ar');
        return response(['infoAR'=>$newGroup],200);
    }
    public function collectionPerConstype(Request $request)
    {
        $type = 'from_to';
        $sales = collect((new GetCollectionService())->salesWitRates($request->date_from,$request->date_to,$type));

        $groupByConstype = $sales->groupBy('ct_id');

        $newSales = $groupByConstype->map(function($item){
            $constype = DB::table('cons_type')
                ->select('ct_desc')
                ->where('ct_id',$item[0]['ct_id'])
                ->first();
                
            return[
                'consumer_type'=> $constype->ct_desc,
                'no_pb'=> round($item->sum('countPB'),2),
                'amount_pb'=> round($item->where('type','PB')->sum('total_amount'),2),
                'no_nb'=> round($item->sum('countNB'),2),
                'amount_nb'=> round($item->where('type','NB')->sum('total_amount'),2),
                'total_collection'=>  round($item->sum('total_amount'),2)
            ];
        });

        return response(['infoCT'=>$newSales->values()->all()],200);
    }
    public function collectionByBillPeriod(Request $request)
    {
        $sales = collect((new GetCollectionService())->collectionByBillPeriodQuery($request->bill_period,$request->date_from,$request->date_to));
        if($sales->isEmpty()){
            return response(['info'=>'No Collection Found'],422);
        }

        if($request->select == 'constype'){
            $for = 1;
            if($request->location == 'area'){
                $groups = $sales->groupBy(['ac_name','ct_desc'])->toArray();
            }else if($request->location == 'town'){
                $groups = $sales->groupBy(['tc_name','ct_desc'])->toArray();
            }else if($request->location == 'route'){
                $groups = $sales->groupBy(['rc_desc','ct_desc'])->toArray();
            }else{
                return response(['info'=>'Nadjeer U forgot to pass location'],422);
            }
        }else if($request->select == 'area'){
            $for = 1;
            $groups = $sales->groupBy(['ac_name','tc_name'])->toArray();
        }else if($request->select == 'town'){
            $for = 1;
            $groups = $sales->groupBy(['tc_name','rc_desc'])->toArray();
        }else if($request->select == 'route' && isset($request->location)){
            $for = 0;
            $groups = collect($sales->where('rc_id',$request->location)->values()->all());
            // dd($groups);
        }else{
            return response(['info'=>'Nadjeer U forgot to pass Select/Location'],422);
        }
        // dd($groups);
        if($for == 1){
            $collection = array();
            $amount = 0;
            $cons = 0;
            $ct_name = '';
            $a_name = '';
            $t_name = '';
            $r_name = '';

            foreach($groups as $rows=>$items){
                // dd($rows);
                foreach($items as $value){
                    // dd($value);
                    $cons = 0;
                    $amount = 0;
                    foreach($value as $val2){
                        // dd($val2);
                        $cons = $cons + 1;
                        $ct_name = $val2->ct_desc;
                        $a_name = $val2->ac_name;
                        $t_name = $val2->tc_name;
                        $r_name = $val2->rc_desc;
                        $amount = $amount + $val2->amount;
                    } 
                    array_push($collection,
                        array(
                        'ctype'=> $ct_name,
                        'area_name'=> $a_name,
                        'town_name'=> $t_name,
                        'route_name'=> $r_name,
                        'cons'=> $cons,
                        'amount'=>$amount));
                }    
            }
        }else if($for == 0){
            $collection = $groups->map(function($items){
                return[
                    'route_name'=>$items->rc_desc,
                    'cons_name'=>$items->cm_full_name,
                    'cons_num'=>$items->cm_account_no,
                    'meter_num'=>$items->mm_serial_no,
                    'amount'=>$items->amount,
                ];
            });
        }
        

        $newCollection = collect($collection);
        if($request->select == 'constype'){
            if($request->location == 'area'){
                $final = $newCollection->groupBy(['area_name','ctype']);
            }else if($request->location == 'town'){
                $final = $newCollection->groupBy(['town_name','ctype']);
            }else if($request->location == 'route'){
                $final = $newCollection->groupBy(['route_name','ctype']);
            }else{
                return response(['info'=>'Nadjeer U forgot to pass location'],422);
            }
        }else if($request->select == 'area' && !isset($request->location)){
            $final = $newCollection->groupBy(['area_name','town_name']);
        }else if($request->select == 'town' && !isset($request->location)){
            $final = $newCollection->groupBy(['town_name','route_name']);
        }else if($request->select == 'route' && isset($request->location)){
            $final = $newCollection->groupBy('route_name');
        }

        if($final->isEmpty()){
            return response(['info'=>'No Collection Found'],422);
        }
        return response(['info'=>$final],200);
        
    }
    public function collectionRoute(Request $request)
    {
        $query = collect((new GetCollectionService())->collectionBetweenDate($request->from_date,$request->to_date,$request->route_id));
        if($query->isEmpty()){
            return response(['info'=>'No Collection Found'],422);
        }

        $totalAmount = $query->sum('amount');

        return response(['info'=>$query,'total_amount'=>round($totalAmount,2)],200);
        
        
    }
}
