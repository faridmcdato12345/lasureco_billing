<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\GetCollectionService;
use App\Services\GetRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ASDController extends Controller
{
    public function collectionReportPowerBill(Request $request)
    {
        //after update now with NonBill 10/16/2022

        $billPeriod = str_replace("-","",$request->date);
        $month = substr($billPeriod,4,6);
        $year = substr($billPeriod,0,4);

        if($request->selected == 'all'){
            $query = collect(
                DB::table('sales as s')
                // ->select(DB::raw('ac.ac_id,ac.ac_name,tc.tc_name,tc.tc_code,s.mr_arrear,mr.mr_amount'))
                ->leftJoin('meter_reg as mr','s.mr_id','=','mr.mr_id')
                ->join('cons_master as cm','s.cm_id','=','cm.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->whereNull('s.f_id')
                // ->where('s.s_cutoff',1)
                ->whereMonth('s.s_bill_date',$month)
                ->whereYear('s.s_bill_date',$year)
                ->get()
            );
        }else if($request->selected == 'area'){
            $query = collect(
                DB::table('sales as s')
                // ->select(DB::raw('ac.ac_id,ac.ac_name,tc.tc_name,tc.tc_code,s.mr_arrear,mr.mr_amount'))
                ->leftJoin('meter_reg as mr','s.mr_id','=','mr.mr_id')
                ->join('cons_master as cm','s.cm_id','=','cm.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->whereNull('s.f_id')
                // ->where('s.s_cutoff',1)
                ->whereMonth('s.s_bill_date',$month)
                ->whereYear('s.s_bill_date',$year)
                ->where('ac.ac_id',$request->location)
                ->get()
            );
        }
        else if($request->selected == 'town'){
            $query = collect(
                DB::table('sales as s')
                // ->select(DB::raw('ac.ac_id,ac.ac_name,tc.tc_name,tc.tc_code,s.mr_arrear,mr.mr_amount'))
                ->leftJoin('meter_reg as mr','s.mr_id','=','mr.mr_id')
                ->join('cons_master as cm','s.cm_id','=','cm.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->whereNull('s.f_id')
                // ->where('s.s_cutoff',1)
                ->whereMonth('s.s_bill_date',$month)
                ->whereYear('s.s_bill_date',$year)
                ->where('tc.tc_id',$request->location)
                ->get()
            );
        }else{
            return response(['Message'=>'Invalid Selection'],422);
        }

        
        $map = $query->map(function($item){
            // if($item->mr_arrear == 'Y'){
            //     $arrear = 0;
            //     $current = $item->mr_amount;
            // }else{
            //     $arrear = $item->mr_amount;
            //     $current = 0;
            // }
            
            if($item->f_id == NULL || $item->f_id == 0){
                // $type = 'PB';
                if($item->mr_arrear == 'Y'){
                    // $current_arrear = 'Current';
                    $current = round($item->s_or_amount + $item->e_wallet_added,2);
                    $arrears = 0;
                }else{
                    // $current_arrear = 'Arrear';
                    $current = 0;
                    $arrears = round($item->s_or_amount + $item->e_wallet_added,2);
                }
            }else{
                // Stop when there is a NonBill
                return response(['message'=>'Error Non-Bill'],422);
            }
            return[
                'Area_ID'=> $item->ac_id,
                'Area_Code'=> '0'.$item->ac_id,
                'Area_Name'=> $item->ac_name,
                'Town_ID'=> $item->tc_id,
                'Town_Name'=> $item->tc_name,
                'Town_Code'=> $item->tc_code,
                'Route_ID'=> $item->rc_id,
                'Route_Code'=> $item->rc_code,
                'Route_Name'=> $item->rc_desc,
                'Total_Arrears'=> $arrears,
                'Total_Current'=> $current,
            ];
        });

        if($request->selected == 'all'){
            $grouped = $map->groupBy('Area_Name');
            
        }else if($request->selected == 'area'){
            $grouped = $map->groupBy('Town_ID');
        }else if($request->selected == 'town'){
            // dd(11);
            $grouped = $map->groupBy('Route_ID');
        }
        else{
            return response([
                'Message'=>'Something Went Wrong'
            ],422);
        }
        $selected = $request->selected;
        
        // $data = collect((new GetCollectionService())->MonthlyCollectionNonBill($request->date));
        $finalGroup = $grouped->map(function($item,$key){

            // if($selected == 'all'){
            //     $nonBillAmount = $data->where('ac_id',$item[0]['Area_ID'])->sum('amount');
            // }else if($selected == 'area'){
            //     $nonBillAmount = $data->where('tc_id',$item[0]['Town_ID'])->sum('amount');
            // }else{
            //     $nonBillAmount = $data->where('rc_id',$item[0]['Route_ID'])->sum('amount');
            // }
            

            return[
                'Area_Code'=>$item[0]['Area_Code'],
                'Area_Name'=>$item[0]['Area_Name'],
                'Town_Code'=>$item[0]['Town_Code'],
                'Town_Name'=>$item[0]['Town_Name'],
                'Route_Code'=>$item[0]['Route_Code'],
                'Route_Name'=>$item[0]['Route_Name'],
                'Total_Arrears'=>round($item->sum('Total_Arrears'),2),
                'Total_Current'=>round($item->sum('Total_Current'),2),
                'Total_Collection'=>round($item->sum('Total_Arrears') + $item->sum('Total_Current'),2),
                // 'Total_Non_Bill'=>round($nonBillAmount,2),
            ];
        });
        return response([
            'Details'=>$finalGroup
        ]);

    }
    public function collectionReportNonBill(Request $request)
    {
        $billPeriod = str_replace("-","",$request->date);
        $month = substr($billPeriod,4,6);
        $year = substr($billPeriod,0,4);

        if($request->selected == 'all'){
            $query = collect(
                DB::table('sales as s')
                // ->select(DB::raw('ac.ac_id,ac.ac_name,tc.tc_name,tc.tc_code,s.mr_arrear,mr.mr_amount'))
                ->join('cons_master as cm','s.cm_id','=','cm.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                // ->whereNotIn('s.f_id',['',0,NULL])
                // ->orwhere('s.s_mode_payment','Deposit_Ewallet')
                // ->whereNotIn('s.f_id',['',0])
                ->whereNotNull('s.f_id')
                // ->where('s.f_id','!=',0)
                // ->where('s.s_cutoff',1)
                ->whereMonth('s.s_bill_date',$month)
                ->whereYear('s.s_bill_date',$year)
                ->get()
            );
            // dd($query);
        }else if($request->selected == 'area'){
            $query = collect(
                DB::table('sales as s')
                // ->select(DB::raw('ac.ac_id,ac.ac_name,tc.tc_name,tc.tc_code,s.mr_arrear,mr.mr_amount'))
                ->join('cons_master as cm','s.cm_id','=','cm.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                // ->whereNotIn('s.f_id',['',0,NULL])
                // ->orwhere('s.s_mode_payment','Deposit_Ewallet')
                // ->where('s.f_id','!=',0)
                // ->where('s.s_cutoff',1)
                ->whereNotNull('s.f_id')
                ->whereMonth('s.s_bill_date',$month)
                ->whereYear('s.s_bill_date',$year)
                ->where('ac.ac_id',$request->location)
                ->get()
            );
        }
        else if($request->selected == 'town'){
            $query = collect(
                DB::table('sales as s')
                ->join('cons_master as cm','s.cm_id','=','cm.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                // ->whereNotNull('s.f_id')
                // ->orwhere('s.s_mode_payment','Deposit_Ewallet')
                // ->whereNotIn('s.f_id',['',0,NULL])
                // ->orwhere('s.s_mode_payment','Deposit_Ewallet')
                ->whereNotNull('s.f_id')
                // ->where('s.f_id','!=',0)
                // ->where('s.s_cutoff',1)
                ->whereMonth('s.s_bill_date',$month)
                ->whereYear('s.s_bill_date',$year)
                ->where('tc.tc_id',$request->location)
                ->get()
            );
            
        }else{
            return response(['Message'=>'Invalid Selection'],422);
        }

        $map = $query->map(function($item){
            return[
                'Area_ID'=> $item->ac_id,
                'Area_Code'=> '0'.$item->ac_id,
                'Area_Name'=> $item->ac_name,
                'Town_ID'=> $item->tc_id,
                'Town_Name'=> $item->tc_name,
                'Town_Code'=> $item->tc_code,
                'Route_ID'=> $item->rc_id,
                'Route_Code'=> $item->rc_code,
                'Route_Name'=> $item->rc_desc,
                'Total_Collection'=> $item->s_bill_amount,
            ];
        });

        if($request->selected == 'all'){
            $grouped = $map->groupBy('Area_Name');
            
        }else if($request->selected == 'area'){
            $grouped = $map->groupBy('Town_Name');
        }else if($request->selected == 'town'){
            // dd(11);
            $grouped = $map->groupBy('Route_Name');
        }
        else{
            return response([
                'Message'=>'Something Went Wrong'
            ],422);
        }
        
        $finalGroup = $grouped->map(function($item,$key){
            return[
                'Area_Code'=>$item[0]['Area_Code'],
                'Area_Name'=>$item[0]['Area_Name'],
                'Town_Code'=>$item[0]['Town_Code'],
                'Town_Name'=>$item[0]['Town_Name'],
                'Route_Code'=>$item[0]['Route_Code'],
                'Route_Name'=>$item[0]['Route_Name'],
                'Total_Collection'=>round($item->sum('Total_Collection'),2),
            ];
        });
        return response([
            'Details'=>$finalGroup
        ]);

    }
    public function salesVsCollection(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date);
        if($request->selected == 'all'){
            $sales = collect(DB::table('cons_master as cm')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                ->join('billing_rates as br','mr.br_id','=','br.id')
                ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
                ->leftJoin('sales as s','mr.mr_id','=','s.mr_id')
                ->select('ac.ac_id','ac.ac_name','tc.tc_id','tc.tc_name','tc.tc_code','rc.rc_id','rc.rc_code','rc.rc_desc',
                'mr.mr_date_year_month','mr.mr_status','mr.mr_amount',DB::raw('(coalesce(s.s_or_amount,0) + coalesce(s.e_wallet_added,0)) as amount,
                coalesce(s.e_wallet_added,0) as e_wallet_deposit,coalesce(s.e_wallet_applied,0) as e_wallet_applied'))
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->where('mr.mr_printed',1)
                ->where('mr.mr_id','!=',NULL)
                ->where('mr.mr_id','!=',0)
                // ->where('ct.ct_id',7)
                ->get());
        }else if($request->selected == 'area'){
                $sales = collect(DB::table('cons_master as cm')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                ->join('billing_rates as br','mr.br_id','=','br.id')
                ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
                ->leftJoin('sales as s','mr.mr_id','=','s.mr_id')
                ->select('ac.ac_id','ac.ac_name','tc.tc_id','tc.tc_name','tc.tc_code','rc.rc_id','rc.rc_code','rc.rc_desc',
                'mr.mr_date_year_month','mr.mr_status','mr.mr_amount',DB::raw('(coalesce(s.s_or_amount,0) + coalesce(s.e_wallet_added,0)) as amount,
                coalesce(s.e_wallet_added,0) as e_wallet_deposit,coalesce(s.e_wallet_applied,0) as e_wallet_applied'))
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->where('mr.mr_printed',1)
                ->where('mr.mr_id','!=',NULL)
                ->where('mr.mr_id','!=',0)
                ->where('ac.ac_id',$request->location)
                // ->where('ct.ct_id',7)
                ->get());
        }else if($request->selected == 'town'){
            $sales = collect(DB::table('cons_master as cm')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                ->join('billing_rates as br','mr.br_id','=','br.id')
                ->join('cons_type as ct','br.cons_type_id','=','ct.ct_id')
                ->leftJoin('sales as s','mr.mr_id','=','s.mr_id')
                ->select('ac.ac_id','ac.ac_name','tc.tc_id','tc.tc_name','tc.tc_code','rc.rc_id','rc.rc_code','rc.rc_desc',
                'mr.mr_date_year_month','mr.mr_status','mr.mr_amount',DB::raw('(coalesce(s.s_or_amount,0) + coalesce(s.e_wallet_added,0)) as amount,
                coalesce(s.e_wallet_added,0) as e_wallet_deposit,coalesce(s.e_wallet_applied,0) as e_wallet_applied'))
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->where('mr.mr_printed',1)
                ->where('mr.mr_id','!=',NULL)
                ->where('mr.mr_id','!=',0)
                ->where('tc.tc_id',$request->location)
                // ->where('ct.ct_id',7)
                ->get());
        }else{
            return response(['Message'=>'Invalid Selection'],422);
        }

        $map = $sales->map(function($item)use($billingPeriod){
            if($item->mr_status == 1){
                $collection = $item->amount;
                $sales = $item->mr_amount;
                $arrears = 0;
            }else if($item->mr_status == 0){
                $collection = 0;
                $sales = $item->mr_amount;
                $arrears = 0;
            }else{
                return response(['message'=>'something went wrong'],422);
            }

            return[
                'Area_ID'=> $item->ac_id,
                'Area_Code'=> '0'.$item->ac_id,
                'Area_Name'=> $item->ac_name,
                'Town_ID'=> $item->tc_id,
                'Town_Name'=> $item->tc_name,
                'Town_Code'=> $item->tc_code,
                'Route_ID'=> $item->rc_id,
                'Route_Code'=> $item->rc_code,
                'Route_Name'=> $item->rc_desc,
                'Total_Sales'=> $sales,
                'Total_Collection'=> $collection,
                'Total_Ewallet_Deposit'=> $item->e_wallet_deposit,
                'Total_Ewallet_Applied'=> $item->e_wallet_applied,
                'Arrears'=> $arrears,
            ];
        });

        if($request->selected == 'all'){
            $grouped = $map->groupBy('Area_Name');
            // $set
            
        }else if($request->selected == 'area'){
            $grouped = $map->groupBy('Town_Name');
        }else if($request->selected == 'town'){
            // dd(11);
            $grouped = $map->groupBy('Route_Name');
        }
        else{
            return response([
                'Message'=>'Something Went Wrong'
            ],422);
        }
        
        $finalGroup = $grouped->map(function($item,$key){
            return[
                'Area_Code'=>$item[0]['Area_Code'],
                'Area_Name'=>$item[0]['Area_Name'],
                'Town_Code'=>$item[0]['Town_Code'],
                'Town_Name'=>$item[0]['Town_Name'],
                'Route_Code'=>$item[0]['Route_Code'],
                'Route_Name'=>$item[0]['Route_Name'],
                'Total_Sales'=>round($item->sum('Total_Sales'),2),
                'Total_Collection'=>round($item->sum('Total_Collection'),2),
                'Total_Deposit'=>round($item->sum('Total_Ewallet_Deposit'),2),
                'Total_Applied'=>round($item->sum('Total_Ewallet_Applied'),2),
                'Arrears'=>round($item->sum('Arrears'),2),
            ];
        })->sortBy('Route_Code');

        
        return response([
            'Message'=>$finalGroup
        ],200);
    }
    public function collectionSummaryPerDate(Request $request)
    {
        $queryPB = collect(
            DB::table('sales as s')
            ->join('user as u','s.teller_user_id','=','u.user_id')
            ->select(DB::raw('s.teller_user_id,s.s_bill_date,s.s_ack_receipt,u.user_full_name,count(s.s_id) as countPB,COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0) as amountPB,0 as countNB,COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0) as amountNB'))
            // ->select(DB::raw('s.s_bill_date,s.s_ack_receipt,u.user_full_name,count(s.s_id) as countPB,COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0) as amountPB'))
            ->whereBetween('s.s_bill_date',[$request->date_from,$request->date_to])
            ->whereNotNull('s.s_ack_receipt')
            ->whereNull('s.f_id')
            // ->where('s.s_cutoff',1)
            ->groupBy('s.s_bill_date','u.user_full_name')
            ->get()
        );
        // dd($queryPB);
        $queryNB = collect(
            DB::table('sales as s')
            ->join('user as u','s.teller_user_id','=','u.user_id')
            ->select(DB::raw('s.teller_user_id,s.s_bill_date,s.s_ack_receipt,u.user_full_name,0 as countPB,sum(0) as amountPB,count(s.s_id) as countNB,COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0) as amountNB'))
            // ->select(DB::raw('s.s_bill_date,s.s_ack_receipt,u.user_full_name,count(s.s_id) as countNB,COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0) as amountNB'))
            ->whereBetween('s.s_bill_date',[$request->date_from,$request->date_to])
            ->whereNotNull('s.s_ack_receipt')
            ->whereNotNull('s.f_id')
            // ->where('s.s_cutoff',1)
            ->groupBy('s.s_bill_date','u.user_full_name')
            ->get()
        );
        // dd($queryNB->where('s_ack_receipt',96590)->isNotEmpty());
        if($queryPB->isEmpty() && $$queryNB->isEmpty()){
            return response(['message'=>'No Record Found'],422);
        }

        $map = $queryPB->map(function($item) use($queryNB){

            if($queryNB->where('s_ack_receipt',$item->s_ack_receipt)->isNotEmpty()){
                $countNB = $queryNB->where('s_ack_receipt',$item->s_ack_receipt)->sum('countNB');
                $amountNB = $queryNB->where('s_ack_receipt',$item->s_ack_receipt)->sum('amountNB');
            }else{
                $countNB = 0;
                $amountNB = 0;
            }
            return[
                'date'=>$item->s_bill_date,
                'ack_receipt'=>$item->s_ack_receipt,
                'teller_name'=>$item->user_full_name,
                'num_bill_pb'=>number_format($item->countPB,0),
                'amount_pb'=>number_format($item->amountPB,2),
                'num_bill_nb'=>number_format($countNB,0),
                'amount_nb'=>number_format($amountNB,2),
                'total_collection'=> number_format($item->amountPB + $amountNB,2)
            ];
        });

        $finalMap = $map->groupBy('date');
        
        return response(['message'=>$finalMap],200);
    }

    public function sampleRates()
    {
        $billingPeriod = str_replace("-","",'2022-08');
        // GET RATES from service for all ConsumerType
        $loc = NULL;
        // dd($loc);
        $data = (new GetRateService())->GetRate($billingPeriod,$loc);

        $map = $data->map(function($item){
            $totalRates = 
            $item['Generation_System_Charge'] +
            $item['Power_Act_Reduction'] +
            $item['Franchise_Benefits_To_Host'] +
            $item['FOREX_Adjustment_Charge'] +
            /* ------------------------------------------- TRANSMISSION CHARGES  ------------------------------------------------------*/
            $item['Trans_Demand_Charge'] +
            $item['Transmission_System_Charge'] +
            $item['System_Loss_Charge'] +
            /* ------------------------------------------- DISTRIBUTION CHARGES ------------------------------------------------------*/
            $item['Dist_Demand_Charge'] +
            $item['Distribution_System_Charge'] +
            $item['Supply_System_Fixed_Charge'] +
            $item['Supply_System_Charge'] +
            $item['Retail_Customer_Meter_Charge'] +
            $item['Retail_Customer_Mtr_Fixed_Charge'] +
            /* ------------------------------------------- UNIVERSAL CHARGES ------------------------------------------------------*/
            $item['UC_SPUG'] +
            $item['UC_RED_Cash_Incentive'] +
            $item['UC_Environmental_Charge'] +
            $item['UC_Equal_of_Taxes_Royalties'] +
            $item['UC_NPC_Stranded_Contract_Cost'] +
            $item['UC_NPC_Stranded_Debt_Cost'] +
            /* ------------------------------------------- OTHER CHARGES ------------------------------------------------------*/
            $item['Inter_Class_Cross_Subsidy'] +
            // $item['Inter Class Corss Subsidy Adj. 
            $item['Members_Contributed_Capital'] +
            $item['Lifeline_Rate_Subsidy'] +
            $item['Lifeline_Rate_Discount'] +
            // $item['Transformer Losses 
            // $item['BackBill_Rebates_Refund 
            $item['Senior_Citizen_Subsidy'] +
            // $item['Senior Citizen (Discount) 
            $item['Feed_in_Tariff_Allowance'] +
            // $item['Prompt Payment Discount Adj 
            $item['lOAN_COND'] +
            $item['lOAN_COND_FIX'] +
            /* ------------------------------------------- VALUE ADDED TAX ------------------------------------------------------*/
            $item['Generation'] +
            $item['Transmission_System'] +
            $item['Transmission_Demand'] +
            $item['System_Loss'] +
            $item['Distribution_System'] +
            $item['Distribution_Demand'] +
            // $item['Others 
            $item['Loan_Condonation_KWH'] +
            $item['Loan_Condonation_Fix'] +
            $item['Power_Act_Red_Vat'] + //new
            $item['Supply_Fix_Vat'] + //new
            $item['Supply_Sys_Vat'] + //new
            $item['Meter_Fix_Vat'] +  //new
            $item['Meter_Sys_Vat'] + //new
            $item['lfln_disc_subs_vat']; //new
            return[
                'Account'=> $item['account_no'],
                'Total'=> round($totalRates,2),
                'Bill_Amount'=> $item['BILL_AMOUNT'],
            ];
        });

        return response(['message'=>$map->values()->all()]);
    }
    public function reconnection(Request $request)
    {
        $reconnection = collect((new GetCollectionService())->nonBillCollection($request->date_from,$request->date_to));
        //selected Area/Town/Route
        if($request->selected == 'area'){
            $newRecon = $reconnection->where('ac_id',$request->id);
        }else if($request->selected == 'town'){
            $newRecon = $reconnection->where('tc_id',$request->id);
        }else if($request->selected == 'route'){
            $newRecon = $reconnection->where('rc_id',$request->id);
        }else{
            $newRecon = $reconnection;
        }

        $map = $newRecon->where('f_id',2)->map(function($item){
            return[
                'account_no'=>$item->cm_account_no,
                'name'=>$item->cm_full_name,
                'address'=>$item->cm_address,
                'meter_no'=>($item->mm_serial_no == NULL) ? 'NA' : $item->mm_serial_no,
                'reconnect_date'=>$item->s_bill_date,
                'reconnect_amount'=>number_format($item->s_or_amount,2)
            ];
        })->sortBy('reconnect_date');

        return response(['info'=>$map->values()->all()],200);
    }
    public function collectionReportAllBill(Request $request)
    {

        $sales = collect((new GetCollectionService())->sales($request->date));
        
        if($request->selected == 'all'){
            $grouped = $sales->groupBy('area_name');
            
        }else if($request->selected == 'area'){
            $grouped = $sales->where('area_id',$request->location)->groupBy('town_name');
        }else if($request->selected == 'town'){
            $grouped = $sales->where('town_id',$request->location)->groupBy('route_name');
        }
        else{
            return response([
                'Message'=>'Something Went Wrong'
            ],422);
        }
        $selected = $request->selected;

        $finalGroup = $grouped->map(function($item) use ($selected){
            if($selected == 'all'){
                $areaName = $item[0]['area_code'].' '.$item[0]['area_name'];
            }else if($selected == 'area'){
                $areaName = $item[0]['town_code'].' '.$item[0]['town_name'];
            }else{
                $areaName = $item[0]['route_code'].' '.$item[0]['route_name'];
            }
            return[
                'area_name'=>$areaName,
                'Total_Current'=>round($item->sum('current'),2),
                'Total_Arrears'=>round($item->sum('arrear'),2),
                'Total_Non_Bill'=>round($item->sum('nonbill'),2),
                'Total_Collection'=>round($item->sum('current') + $item->sum('arrear') + $item->sum('nonbill'),2),
            ];
        })->sortBy('area_name');
        // dd($finalGroup);
        return response(['info'=>$finalGroup],200);
    }
    public function collectionForTheMonthPerTown(Request $request)
    {

        $sales = collect((new GetCollectionService())->salesNew($request->date_from,$request->date_to,$request->bill_period));
        if($request->selected == 'all'){
            $grouped = $sales->groupBy('area_name');
        }else if($request->selected == 'area'){
            $grouped = $sales->where('area_id',$request->location)->groupBy('town_name');
        }else if($request->selected == 'town'){
            $grouped = $sales->where('town_id',$request->location)->groupBy('route_name');
        }
        else{
            return response([
                'Message'=>'Something Went Wrong'
            ],422);
        }

        $finalGroup = $grouped->map(function($item){

            return[
                'area_name'=>$item[0]['area_name'],
                'Total_Current'=>round($item->sum('current'),2)
            ];
        });

        return response(['info'=>$finalGroup],200);
    }
    public function penaltyReport(Request $request)
    {
        $billPeriod = str_replace("-","",$request->date);
        $month = substr($billPeriod,4,6);
        $year = substr($billPeriod,0,4);

        $query = collect(
            DB::table('sales as s')
            ->join('cons_master as cm','s.cm_id','cm.cm_id')
            ->join('user as u','s.teller_user_id','u.user_id')
            ->whereMonth('s.s_bill_date',$month)
            ->whereYear('s.s_bill_date',$year)
            ->where('s.f_id',138)
            ->get()
        );

        if($query->isEmpty()){
            return response(['info'=>'No Record Found'],422);
        }
        $map = $query->map(function($item){
            $date = date_create($item->s_bill_date);
            return[
                'account_no'=>$item->cm_account_no,
                'name'=>$item->cm_full_name,
                'amount'=>round($item->s_or_amount + $item->e_wallet_added,2),
                'teller'=>$item->user_full_name,
                'date'=> date_format($date,"M d, Y"),
            ];
        })->sortBy('date');

        return response(['info'=>$map->values()->all()],200);

    }
}