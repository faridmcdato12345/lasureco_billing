<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ISDController extends Controller
{
    public function delinquent(Request $request)
    {
        set_time_limit(0);
        $billingPeriod = str_replace("-","",$request->date_period);
        if($request->selected == 'all'){
            if(!isset($request->cons_id)){
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->select(DB::raw('cm.cm_id,cm.cm_account_no,cm.cm_full_name,cm.cm_con_status,rc.rc_desc,count(cm.cm_id) as numBill,cm.ct_id,sum(mr.mr_amount) as amount,cm.cm_address'))
                    ->where('mr.mr_status',0)
                    ->where('mr.mr_date_year_month','<',$billingPeriod)
                    ->groupBy('cm.cm_id')
                    ->get()
                );
            }else{
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->select(DB::raw('cm.cm_id,cm.cm_account_no,cm.cm_full_name,cm.cm_con_status,rc.rc_desc,count(cm.cm_id) as numBill,cm.ct_id,sum(mr.mr_amount) as amount,cm.cm_address'))
                    ->where('mr.mr_status',0)
                    ->where('cm.ct_id', $request->cons_id)
                    ->where('mr.mr_date_year_month','<',$billingPeriod)
                    ->groupBy('cm.cm_id')
                    ->get()
                );
            }
            
        }else if($request->selected == 'area'){
            if(!isset($request->cons_id)){
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->select(DB::raw('cm.cm_id,cm.cm_account_no,cm.cm_full_name,cm.cm_con_status,rc.rc_desc,count(cm.cm_id) as numBill,cm.ct_id,sum(mr.mr_amount) as amount,cm.cm_address'))
                    ->where('ac.ac_id',$request->location_id)
                    ->where('mr.mr_status',0)
                    ->where('mr.mr_date_year_month','<',$billingPeriod)
                    ->groupBy('cm.cm_id')
                    ->get()
                );
            }else{
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->select(DB::raw('cm.cm_id,cm.cm_account_no,cm.cm_full_name,cm.cm_con_status,rc.rc_desc,count(cm.cm_id) as numBill,cm.ct_id,sum(mr.mr_amount) as amount,cm.cm_address'))
                    ->where('ac.ac_id',$request->location_id)
                    ->where('mr.mr_status',0)
                    ->where('cm.ct_id', $request->cons_id)
                    ->where('mr.mr_date_year_month','<',$billingPeriod)
                    ->groupBy('cm.cm_id')
                    ->get()
                );
            }
            
        }else if($request->selected == 'town'){
            if(!isset($request->cons_id)){
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->select(DB::raw('cm.cm_id,cm.cm_account_no,cm.cm_full_name,cm.cm_con_status,rc.rc_desc,count(cm.cm_id) as numBill,cm.ct_id,sum(mr.mr_amount) as amount,cm.cm_address'))
                    ->where('tc.tc_id',$request->location_id)
                    ->where('mr.mr_status',0)
                    ->where('mr.mr_date_year_month','<',$billingPeriod)
                    ->groupBy('cm.cm_id')
                    ->get()
                );
            }else{
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->select(DB::raw('cm.cm_id,cm.cm_account_no,cm.cm_full_name,cm.cm_con_status,rc.rc_desc,count(cm.cm_id) as numBill,cm.ct_id,sum(mr.mr_amount) as amount,cm.cm_address'))
                    ->where('tc.tc_id',$request->location_id)
                    ->where('mr.mr_status',0)
                    ->where('cm.ct_id', $request->cons_id)
                    ->where('mr.mr_date_year_month','<',$billingPeriod)
                    ->groupBy('cm.cm_id')
                    ->get()
                );
            }
            
        }else if($request->selected == 'route'){
            if(!isset($request->cons_id)){
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->select(DB::raw('cm.cm_id,cm.cm_account_no,cm.cm_full_name,cm.cm_con_status,rc.rc_desc,count(cm.cm_id) as numBill,cm.ct_id,sum(mr.mr_amount) as amount,cm.cm_address'))
                    ->where('rc.rc_id',$request->location_id)
                    ->where('mr.mr_status',0)
                    ->where('mr.mr_date_year_month','<',$billingPeriod)
                    ->groupBy('cm.cm_id')
                    ->get()
                );
            }else{
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->select(DB::raw('cm.cm_id,cm.cm_account_no,cm.cm_full_name,cm.cm_con_status,rc.rc_desc,count(cm.cm_id) as numBill,cm.ct_id,sum(mr.mr_amount) as amount,cm.cm_address'))
                    ->where('rc.rc_id',$request->location_id)
                    ->where('mr.mr_status',0)
                    ->where('cm.ct_id', $request->cons_id)
                    ->where('mr.mr_date_year_month','<',$billingPeriod)
                    ->groupBy('cm.cm_id')
                    ->get()
                );
            }
            
        }else{
            return response(['Message'=> 'Error, No Selected Type of Location'],422);
        }

        $queryMapped = $query->map(function($item){
            if($item->cm_id == 13901){
                $typeOfCust = 'High Voltage';
            }else{
                $typeOfCust = 'Low Voltage';
            }
            return[
                'Customer_Account'=>$item->cm_account_no,
                'Name_Of_Customer'=>$item->cm_full_name,
                'Type_Of_Customer'=>$typeOfCust,
                'Area'=>$item->cm_address, //Route//Baranggay
                'Connection_Status'=>($item->cm_con_status == 1) ? 'Active' : 'Disconnected',
                'No_Of_Bills'=>$item->numBill,
                'Total_Amount'=>round($item->amount,2),
            ];
        })->sortByDesc('Total_Amount')->values()->all();
        $obj = (object) $queryMapped;
        $top = collect($obj)->take($request->top)->all();
        
        return response(['Details'=>$top],200);
    }
    public function promptPayor(Request $request)
    {
        set_time_limit(0);
        $billingPeriod = str_replace("-","",$request->date_period);
        // $getYear = substr($billingPeriod,0,4);
        // $getMonth = substr($billingPeriod,4);
        // if($getMonth == 1){
        //     $setYear = $getYear - 1;
        //     $setMonth = 12;
        //     $newBillPeriod = $setYear.''.$setMonth;
        // }else{
        //     $setYear = $getYear;
        //     $setMonth = $getMonth - 1;
        //     if(strlen($setMonth) == 1){
        //         $setMonth = .0.$setMonth;
        //     }

        //     $newBillPeriod = $setYear.''.$setMonth;
        // }

        if($request->selected == 'all'){
            $query = collect(
                DB::table('sales as s')
                    ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
                    ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
                    ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->where('mr.mr_date_year_month',$billingPeriod)
                    ->where('s.mr_arrear','Y')
                    ->where('mr.mr_status',1)
                    ->where('ct.ct_id',8)
                    // ->where('rc.rc_id',)
                    ->orderBy('s.s_bill_date','asc')
                    ->orderBy('s.s_bill_date_time','asc')
                    ->get());
        }else if($request->selected == 'area'){
            $query = collect(
                DB::table('sales as s')
                    ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
                    ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
                    ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->where('mr.mr_date_year_month',$billingPeriod)
                    ->where('s.mr_arrear','Y')
                    ->where('mr.mr_status',1)
                    ->where('ct.ct_id',8)
                    ->where('ac.ac_id',$request->location_id)
                    // ->where('rc.rc_id',)
                    ->orderBy('s.s_bill_date','asc')
                    ->orderBy('s.s_bill_date_time','asc')
                    ->get());
        }else if($request->selected == 'town'){
            $query = collect(
                DB::table('sales as s')
                    ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
                    ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
                    ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->where('mr.mr_date_year_month',$billingPeriod)
                    ->where('s.mr_arrear','Y')
                    ->where('mr.mr_status',1)
                    ->where('ct.ct_id',8)
                    ->where('tc.tc_id',$request->location_id)
                    // ->where('rc.rc_id',)
                    ->orderBy('s.s_bill_date','asc')
                    ->orderBy('s.s_bill_date_time','asc')
                    ->get());
        }else if($request->selected == 'route'){
            $query = collect(
                DB::table('sales as s')
                    ->join('meter_reg as mr','s.mr_id','=','mr.mr_id')
                    ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
                    ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->where('mr.mr_date_year_month',$billingPeriod)
                    ->where('s.mr_arrear','Y')
                    ->where('mr.mr_status',1)
                    ->where('ct.ct_id',8)
                    ->where('rc.rc_id',$request->location_id)
                    // ->where('rc.rc_id',)
                    ->orderBy('s.s_bill_date','asc')
                    ->orderBy('s.s_bill_date_time','asc')
                    ->get());
        }else{
            return response(['Message'=> 'Error, No Selected Type of Location'],422);
        }
        
        
        $map = $query->map(function($item){
            $datePaid = strtotime($item->s_bill_date.' '.$item->s_bill_date_time);
            $finalCheckArrear = collect(DB::table('meter_reg')
                ->where('mr_status',0)
                ->where('cm_id',$item->cm_id)
                ->first());
            if($finalCheckArrear->isEmpty()){
                $samp = "TRUE";
            }else{
                $samp = "FAKE";
            }

            //Number of Days Before Deadline
            $periodFromConv = date('m/d/Y', strtotime($item->mr_due_date));
            $periodToConv = date('m/d/Y', strtotime($item->s_bill_date));
            
            $datetime1 = new DateTime($periodFromConv);
            $datetime2 = new DateTime($periodToConv);
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');
            return[
                'Customer_Account_No'=>$item->cm_account_no,
                'Name_Of_Customer'=>$item->cm_full_name,
                'Type_Of_Customer'=>$item->ct_desc,
                'Customer_Area'=>$item->cm_address,
                'Date_Paid'=>$item->s_bill_date.' '.$item->s_bill_date_time,
                'Number_Days_Before_Deadline'=> $days,  
                'Sample'=>$samp,
                'Amount'=>$item->s_bill_amount,
            ];
        })->sortBy('Date_Paid')->values()->all();

        $obj = collect((object) $map);
        $result = collect($obj->where('Sample','TRUE')->values()->all());
        
        // $newObj = $obj->forget('Sample');
        if($request->sortby == 2){
            $top = collect($result)->take($request->top)->sortByDesc('Date_Paid')->values()->all();
        }else if($request->sortby == 3){
            $top = collect($result)->take($request->top)->sortBy('Amount')->values()->all();
        }else if($request->sortby == 4){
            $top = collect($result)->take($request->top)->sortByDesc('Amount')->values()->all();
        }
        else{
            $top = collect($result)->take($request->top)->sortBy('Date_Paid')->values()->all();
        }
        
        return response(['Top_Prompt_Payors'=>$top]);
    }
    public function renumberSequence(Request $request)
    {
        $consQuery = DB::table('cons_master')
            ->where('cm_id',$request->cons_id)
            ->get();

        if(!$consQuery->first()){
            return response(['Message'=>'No Record Found'],422);
        }

        $consUpdate = Consumer::find($request->cons_id);
        $consUpdate->cm_seq_no = $request->seq_number;
        if($consUpdate->save())
        {
            return response(['Message'=>'Succesfully Updated.'],200);
        }else{
            return response(['Message'=>'Something Went Wrong'],422);
        }

    }
	public function masterList(Request $request)
    {
        if($request->selected == 'bigloads'){
            $query = collect(
                DB::table('cons_master as cm')
                ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
                ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
                // ->join('sales as s','cm.cm_id','=','s.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->where('rc.tc_id',$request->town_code_id)
                ->where('cm.large_load',1)
                ->whereBetween('rc.rc_code',[$request->route_code_from,$request->route_code_to])
                // ->whereBetween('')
                ->orderBy('cm.cm_account_no')
                ->get());
        }else if($request->selected == 'senior'){
            $query = collect(
                DB::table('cons_master as cm')
                ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
                ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
                // ->join('sales as s','cm.cm_id','=','s.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->where('rc.tc_id',$request->town_code_id)
                ->where('cm.senior_citizen',1)
                ->whereBetween('rc.rc_code',[$request->route_code_from,$request->route_code_to])
                // ->whereBetween('')
                ->orderBy('cm.cm_account_no')
                ->get());
        }else{
            $query = collect(
                DB::table('cons_master as cm')
                ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
                ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
                // ->join('sales as s','cm.cm_id','=','s.cm_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->where('rc.tc_id',$request->town_code_id)
                ->whereBetween('rc.rc_code',[$request->route_code_from,$request->route_code_to])
                // ->whereBetween('')
                ->orderBy('cm.cm_account_no')
                ->get());
        }
        

        if($query->isEmpty())
        {
            return response(['Message'=>'No Record Found'],422);
        }
        // dd($query);
        $mapped = $query->map(function($item){
            $series = substr($item->cm_account_no,-4);
            $membershipFee = DB::table('sales')
                ->where('cm_id',$item->cm_id)
                ->where('f_id',9)
                ->sum('s_or_amount');
            if(!$membershipFee)
            {
                $membershipFee = '0.00';
            }

            if($item->created_at > 0){
                $date = date_create($item->created_at);
                $createdAt = date_format($date,"F j, Y");
            }else{
                $createdAt = 'N.A';
            }
            return[
                'Series'=>$series,
                'Name'=>$item->cm_full_name,
                'Route'=>$item->rc_code.' '.$item->rc_desc,
                'Meter_Number'=>($item->mm_serial_no > 0) ? $item->mm_serial_no : 'N.A',
                'Type_Of_Consumer'=>$item->ct_code,
                // 'Membership_Fee'=>round($item->s_or_amount,2),
                'Membership_Fee'=>number_format($membershipFee,2),
                'Date_Approved_Confirmed'=> $createdAt,
            ];
        });

        $group = $mapped->groupBy('Route');

        return response([
            'Message'=>$group,
            'count'=>$mapped->count()
        ],200);
    }
    public function changeConsType(Request $request)
    {
        $query = collect(
            DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('cons_type_mod as ctm','cm.cm_id','=','ctm.cm_id')
            ->whereDate('ctm.ctm_date','>=',$request->date_from)
            ->whereDate('ctm.ctm_date','<=',$request->date_to)
            ->where('tc.tc_id',$request->town_id)
            ->get()
        );

        $map = $query->map(function($item){
            $date = date_create($item->ctm_date);
            $newDate = date_format($date,"F d, Y");
            return[
                'DATE'=>$newDate,
                'NAME'=>$item->cm_full_name,
                'ADDRESS'=>$item->cm_address,
                'ACCOUNT_NO'=>$item->cm_account_no,
                'OLD_INFO'=>$item->ctm_old,
                'NEW_INFO'=>$item->ctm_new,
                'REMARKS'=>$item->ctm_remarks,
            ];
        });

        return response([
            'Details'=>$map
        ],200);
    }
    public function listDisco(Request $request)
    {
        set_time_limit(0);
        $date = Carbon::parse($request->date);
        $newDateTime = date_format($date->subMonths($request->disc_month),"Y-m-d");
        if($request->constype == 'all'){
            if($request->selected == 'all'){
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
                    ->where('mr.mr_status',0)
                    ->groupBy('cm.cm_id')
                    ->orderBy('cm.cm_id')
                    ->get()
                );
            }else if($request->selected == 'area'){
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
                    ->where('ac.ac_id',$request->location)
                    ->where('mr.mr_status',0)
                    ->groupBy('cm.cm_id')
                    ->orderBy('cm.cm_id')
                    ->get()
                );
            }else if($request->selected == 'town'){
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
                    ->where('mr.mr_status',0)
                    ->where('tc.tc_id',$request->location)
                    ->groupBy('cm.cm_id')
                    ->orderBy('cm.cm_id')
                    ->get()
                );
            }else if($request->selected == 'route'){
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
                    ->where('mr.mr_status',0)
                    ->where('rc.rc_id',$request->location)
                    ->groupBy('cm.cm_id')
                    ->orderBy('cm.cm_id')
                    ->get()
                );
            }else{
                return response([
                    'Message'=>'Nadjeer Pass value of Selected Please'
                ],200);
            }
        }else{
            if($request->selected == 'all'){
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
                    ->where('mr.mr_status',0)
                    ->where('cm.ct_id',$request->constype)
                    ->groupBy('cm.cm_id')
                    ->orderBy('cm.cm_id')
                    ->get()
                );
            }else if($request->selected == 'area'){
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
                    ->where('ac.ac_id',$request->location)
                    ->where('mr.mr_status',0)
                    ->where('cm.ct_id',$request->constype)
                    ->groupBy('cm.cm_id')
                    ->orderBy('cm.cm_id')
                    ->get()
                );
            }else if($request->selected == 'town'){
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
                    ->where('mr.mr_status',0)
                    ->where('tc.tc_id',$request->location)
                    ->where('cm.ct_id',$request->constype)
                    ->groupBy('cm.cm_id')
                    ->orderBy('cm.cm_id')
                    ->get()
                );
            }else if($request->selected == 'route'){
                $query = collect(
                    DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->join('meter_reg as mr','cm.cm_id','=','mr.cm_id')
                    ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
                    ->where('mr.mr_status',0)
                    ->where('rc.rc_id',$request->location)
                    ->where('cm.ct_id',$request->constype)
                    ->groupBy('cm.cm_id')
                    ->orderBy('cm.cm_id')
                    ->get()
                );
            }else{
                return response([
                    'Message'=>'Nadjeer Pass value of Selected Please'
                ],200);
            }
        }
        
        
        $mapped = $query->map(function($item) use($newDateTime){
            $checkPayment = collect(
                DB::table('sales')
                ->select('s_bill_date')
                ->whereDate('s_bill_date','>=',$newDateTime)
                ->where('cm_id',$item->cm_id)
                ->orderByDesc('s_bill_date')
                ->first());
            // dd($checkPayment);
            //Query Total Arrears
            $totalArrear = collect(DB::table('meter_reg')
                ->where('cm_id',$item->cm_id)
                ->where('mr_status',0)
                ->sum('mr_amount'))
                ->first();
            if($checkPayment->isEmpty()){
                $payment = '0';
                // $lastBillDate  = 'NO PAYMENT';
                //GET LAST PAYMENT Date of TO BE Disconnected Consumer
                $checkPaymentnotpaid = collect(
                    DB::table('sales')
                    ->select('s_bill_date')
                    ->where('cm_id',$item->cm_id)
                    ->orderByDesc('s_bill_date')
                    ->first());
                    
                if($checkPaymentnotpaid->isEmpty()){
                    $lastBillDate  = 'No Record';
                    // dd(1);
                }else{
                    $cDate = date_create($checkPaymentnotpaid['s_bill_date']);
                    $lastBillDate = date_format($cDate,"F d, Y");
                }
                
            }else{
                $payment = '1';
                $cDate = date_create($checkPayment['s_bill_date']);
                $lastBillDate = date_format($cDate,"F d, Y");
            }

            return[
                'ACCOUNT'=>$item->cm_account_no,
                'NAME'=>$item->cm_full_name,
                'ADDRESS'=>$item->cm_address,
                'SAMPLE'=>$payment,
                'LAST_PAYMENT_DATE'=>$lastBillDate,
                'Meter_No'=>($item->mm_serial_no == NULL) ? 'No Meter' : $item->mm_serial_no,
                'Total_Arrears'=>round($totalArrear,2),
            ];
        });
        if($request->amount != NULL){
            return response([
                'Details'=>$mapped->where('SAMPLE','0')->where('Total_Arrears','>=',$request->amount)->values()->all()
            ],200);
        }else{
            return response([
                'Details'=>$mapped->where('SAMPLE','0')->values()->all()
            ],200);
        }
        
    }
    public function changeConsMeter(Request $request)
    {
        $query = collect(
            DB::table('cons_master as cm')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('cons_meter_mod as cmm','cm.cm_id','=','cmm.cm_id')
            ->whereDate('cmm.cmm_date','>=',$request->date_from)
            ->whereDate('cmm.cmm_date','<=',$request->date_to)
            ->where('tc.tc_id',$request->town_id)
            ->get()
        );

        $map = $query->map(function($item){
            $date = date_create($item->cmm_date);
            $newDate = date_format($date,"F d, Y");
            return[
                'NAME'=> $item->cm_full_name,
                'ADDRESS'=> $item->cm_address,
                'ACCOUNT_NO'=> $item->cm_account_no,
                'OLD_INFO'=> $item->cmm_old_meter_serial,
                'NEW_INFO'=> $item->cmm_new_meter_serial,
                'DATE_ACTED'=> $newDate,
                'REMARKS'=> $item->cmm_remarks,
            ];
        });

        return response([
            'Details'=>$map
        ],200);
        
    }
    public function consumerList(Request $request)
    {
        if($request->statustype == 1){
            //All
            $setStatus = 2;
        }else if($request->statustype == 2){
            //Active
            $setStatus = 1;
        }else if($request->statustype == 3){
            //Inoperative
            $setStatus = 3;
        }else if($request->statustype == 0){
            //Disco
            $setStatus = 0;
        }else{
            return response(['Message'=> 'Invalid Selected Status'],422);
        }
        if($setStatus ==  2){
            $query = collect(
                DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
                    ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
                    ->where('tc.tc_id',$request->tc_id)
                    ->where('cm.pending',0)
                    ->where('rc.rc_code',$request->rc_from)
                    ->whereNull('cm.deleted_at')
                    ->orderBy('cm.cm_account_no','asc')
                    ->get()
            );
        }else{
            $query = collect(
                DB::table('cons_master as cm')
                    ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
                    ->leftJoin('meter_master as mm','cm.mm_id','=','mm.mm_id')
                    ->where('tc.tc_id',$request->tc_id)
                    ->where('cm.pending',0)
                    ->where('cm.cm_con_status',$setStatus) // Either Active or Disconnected
                    ->where('rc.rc_code',$request->rc_from)
                    ->whereNull('cm.deleted_at')
                    ->orderBy('cm.cm_account_no','asc')
                    ->get()
            );
        }
        
        if($query->isEmpty())
        {
            return response(['Message'=> 'No Record Found'],422);
        }
        if($request->consType == 'ALL'){
            $mapped = $query->map(function($item){
                return[
                    'Account_No'=> $item->cm_account_no,
                    'Name'=> $item->cm_full_name,
                    'Type'=> $item->ct_code,
                    'Address'=> $item->cm_address,
                    'Status'=> ($item->cm_con_status == 1) ? 'A' : 'D',
                    'Meter_No'=> ($item->mm_serial_no == NULL) ? '' : $item->mm_serial_no,
                    'Brand'=> '',
                    'KWH_Mult'=> $item->cm_kwh_mult,
                    'KW_Mult'=> '0.00',
                ];
            });

        }else{
            $mapped = $query->where('ct_code',$request->consType)->map(function($item){
                return[
                    'Account_No'=> $item->cm_account_no,
                    'Name'=> $item->cm_full_name,
                    'Type'=> $item->ct_code,
                    'Address'=> $item->cm_address,
                    'Status'=> ($item->cm_con_status == 1) ? 'A' : 'D',
                    'Meter_No'=> ($item->mm_serial_no == NULL) ? '' : $item->mm_serial_no,
                    'Brand'=> '',
                    'KWH_Mult'=> $item->cm_kwh_mult,
                    'KW_Mult'=> '0.00',
                ];
            });
        }
        

        return response([
            'Area_Code'=> '0'.$query->first()->ac_id.' '.$query->first()->ac_name,
            'Town_Code'=> $query->first()->tc_code.' '.$query->first()->tc_name,
            'Route_Code'=> $query->first()->rc_code.' '.$query->first()->rc_desc,
            'Consumer_Listing'=> $mapped->values()->all(),
            'Total_Consumer'=>$mapped->count('Account_No'),
            'Total_No_Meter'=>$mapped->where('Meter_No',NULL)->count('Meter_No'),
            'Total_With_Meter'=>$mapped->where('Meter_No','!=',NULL)->count('Meter_No'),
            'Meter'=> $request->withMeter
        ]);
    }
    public function changeStatus(Request $request)
    {
        $query = collect(
            DB::table('cons_status_mod as csm')
            ->join('cons_master as cm','csm.cm_id','=','cm.cm_id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            // ->where('tc.tc_id',$request->town_id)
            ->whereDate('csm.csm_date','>=',$request->date_from)
            ->whereDate('csm.csm_date','<=',$request->date_to)
            ->get()
        );

        if($query->isEmpty())
        {
            return response(['Details'=>'No Record Found'],422);
        }

        $mapped = $query->map(function($item){
            $date = date_create($item->csm_date);
            $newDate = date_format($date,"F d, Y");
            if($item->csm_old === NULL){
                $oldInfo = 'Pending';
                $newInfo = 'New Connection';
                $samp = 2;
            }else if($item->csm_old === 1){
                $oldInfo = 'Disconnected';
                $newInfo = 'Reconnected';
                $samp = 0;
            }else{
                $oldInfo = 'Activated';
                $newInfo = 'Disconnected';
                $samp = 1;
            }
            // dd($samp);
            return[
                'NAME'=>$item->cm_full_name,
                'ADDRESS'=>$item->cm_address,
                'ACCOUNT_NO'=>$item->cm_account_no,
                'OLD_INFO'=> $oldInfo,
                'NEW_INFO'=> $newInfo,
                'DATE_ACTED'=>$newDate,
                'REMARKS'=> $item->csm_remarks,
                'SAMP'=> $samp
            ];
        });
        
        if($request->selected == 0){
            return response([
                'Details'=>$mapped->where('SAMP','1')->values()->all()
            ],200);
        }else if($request->selected == 1){
            return response([
                'Details'=>$mapped->where('SAMP','0')->values()->all()
            ],200);
        }else if($request->selected == 2){
            return response([
                'Details'=>$mapped->where('SAMP','2')->values()->all()
            ],200);
        }else{
            return response([
                'Message'=>'Invalid Selection'
            ],422);
        }
        
    }
    public function printPending(Request $request)
    {
        // filter
        if($request->filter == 'area'){
            $where = 'ac.ac_id';
        }else if($request->filter == 'town'){
            $where = 'tc.tc_id';
        }else{
            $where = 'rc.rc_id';
        }

        if($request->filter == 'all'){
            $queryPending = collect(
                DB::table('cons_master as cm')
                ->select('cm.cm_account_no','cm.cm_full_name','cm.cm_address','cm.cm_date_createdat')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->where('cm.pending',1)
                ->get());
        }else{
            $queryPending = collect(
                DB::table('cons_master as cm')
                ->select('cm.cm_account_no','cm.cm_full_name','cm.cm_address','cm.cm_date_createdat')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->where('cm.pending',1)
                ->where($where,$request->id)
                ->get()
            );
        }
        

        if($queryPending->isEmpty()){
            return response([
                'Message'=> 'No Record Found'
            ],422);
        }

        $map = $queryPending->map(function($item){
            if($item->cm_date_createdat === NULL){
                $dateCreated = 'N.A';
            }else{
                $cDate = date_create($item->cm_date_createdat);
                $dateCreated = date_format($cDate,"F d, Y");
            }
            
            return[
                'Account_No'=> $item->cm_account_no,
                'Name'=> $item->cm_full_name,
                'Address'=> $item->cm_address,
                'Date_Entered'=> $dateCreated,
            ];
        });

        return response([
            'Details'=>$map,
        ],200);
    }
    public function listConsNotified(Request $request)
    {
        if($request->selected == 'all'){
            $query = collect(
                DB::table('cons_master as cm')
                ->join('cons_notify as cn','cm.cm_id','=','cn.cm_id')
                ->get()
            );
        }else{
            $query = collect(
                DB::table('cons_master as cm')
                ->join('cons_notify as cn','cm.cm_id','=','cn.cm_id')
                ->whereDate('cn.cn_date','>=',$request->date_from)
                ->whereDate('cn.cn_date','<=',$request->date_to)
                ->get()
            );
        }
        

        if($query->isEmpty()){
            return response(['Message'=>'No Records Found'],422);
        }

        $mapped = $query->map(function($item){
            if($item->cn_date === NULL){
                $dateCreated = 'N.A';
            }else{
                $cDate = date_create($item->cn_date);
                $dateCreated = date_format($cDate,"F d, Y");
            }
            return[
                'Notification_ID'=>$item->cn_id,
                'Name'=> $item->cm_full_name,
                'Account'=> $item->cm_account_no,
                'Remarks'=> $item->cn_remarks,
                'Date_Created'=> $dateCreated,
            ];
        });

        return response([
            'Detaail'=>$mapped
        ],200);
    }
    public function newlyEnteredConsumerReport(Request $request)
    {
        // filter
        if($request->filter == 'area'){
            $where = 'ac.ac_id';
        }else if($request->filter == 'town'){
            $where = 'tc.tc_id';
        }else{
            $where = 'rc.rc_id';
        }

        if($request->filter == 'all'){
            $query = collect(
                DB::table('cons_master as cm')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->whereDate('cm.created_at','>=',$request->date_from)
                ->whereDate('cm.created_at','<=',$request->date_to)
                // ->whereBetween('cm.created_at',[$request->date_from,$request->date_to])
                // ->whereNotNull('teller_user_id')
                // ->where('teller_user_id',$request->user_id)
                ->get()
            );
        }else{
            $query = collect(
                DB::table('cons_master as cm')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->whereDate('cm.created_at','>=',$request->date_from)
                ->whereDate('cm.created_at','<=',$request->date_to)
                ->where($where,$request->id)
                // ->whereNotNull('teller_user_id')
                // ->where('teller_user_id',$request->user_id)
                ->get()
            );
        }
        
        

        if($query->isEmpty()){
            return response(['Message'=>'No Records Found'],422);
        }

        $mapped = $query->map(function($item){
            $cDate = date_create($item->created_at);
            $dateCreated = date_format($cDate,"F d, Y");
            return[
                'area'=>$item->ac_name,
                'ac_id'=>$item->ac_id,
                'town'=>$item->tc_name,
                'tc_id'=>$item->tc_id,
                'route'=>$item->rc_desc,
                'rc_id'=>$item->rc_id,
                'Account_No'=>$item->cm_account_no,
                'Name'=>$item->cm_full_name,
                'Address'=>$item->cm_address,
                'Date_Created'=>$dateCreated
            ];
        });

        // if($request->selected == 'area'){
        //     $newGroup = $mapped->where('ac_id',$request->location_id);
        // }else if($request->selected == 'town'){
        //     $newGroup = $mapped->where('tc_id',$request->location_id);
        // }else if($request->selected == 'route'){
        //     $newGroup = $mapped->where('rc_id',$request->location_id);
        // }else if($request->selected == 'all'){
        //     $newGroup = $mapped;
        // }else{
        //     return response(['info'=>'Invalid Selection'],422);
        // }
        return response([
            'Message'=>$mapped->values()->all(),
            'total_consumer'=>$mapped->count(),
        ]);
    }
    public function consMeterSummary(Request $request)
    {
        if($request->filtered == 'active'){
            $filter = 1;
        }else if($request->filtered == 'disco'){
            $filter = 0;
        }else{
            $filter = 2;
        }
        $param = 0;
        if($request->selected == 'area'){
            if($filter == 2){
                $query = collect(
                    DB::table('route_code as rc')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->where('ac.ac_id',$request->location)
                    ->orderBy('tc.tc_code')
                    ->get()
                );
                
            }else{
                $query = collect(
                    DB::table('route_code as rc')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->where('ac.ac_id',$request->location)
                    ->orderBy('tc.tc_code')
                    ->get()
                );
            }
        }else if($request->selected == 'town'){
            if($filter == 2){
                $query = collect(
                    DB::table('route_code as rc')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->where('tc.tc_id',$request->location)
                    ->orderBy('rc.rc_code')
                    ->get()
                );
            }else{
                $query = collect(
                    DB::table('route_code as rc')
                    ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                    ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                    ->where('tc.tc_id',$request->location)
                    ->orderBy('rc.rc_code')
                    ->get()
                );
            }
            
        }else{
            return response(['Message'=>'Invalid Selection'],422);
        }
        

        if($query->isEmpty()){
            return response(['Message'=>'No Record Found'],422);
        }

        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $mapped = $query->map(function($item) use($filter,$dateFrom,$dateTo){
            if($filter == 1){
                //active
                $consQuery = collect(
                    DB::table('cons_master')
                    ->select('cm_id','mm_id','rc_id')
                    ->where('rc_id',$item->rc_id)
                    ->where('cm_con_status',1)
                    // ->whereDate('created_at','>=',$dateFrom)
                    // ->whereDate('created_at','<=',$dateTo)
                    ->where('pending',0)
                    ->whereNull('deleted_at')
                    ->get()
                );
            }else if($filter == 0){
                //disco
                $consQuery = collect(
                    DB::table('cons_master')
                    ->select('cm_id','mm_id','rc_id')
                    ->where('rc_id',$item->rc_id)
                    ->where('cm_con_status',0)
                    ->where('pending',0)
                    ->whereNull('deleted_at')
                    // ->whereDate('created_at','>=',$dateFrom)
                    // ->whereDate('created_at','<=',$dateTo)
                    ->get()
                );
            }else{
                //all
                $consQuery = collect(
                    DB::table('cons_master')
                    ->select('cm_id','mm_id','rc_id')
                    ->where('rc_id',$item->rc_id)
                    ->where('pending',0)
                    ->whereNull('deleted_at')
                    // ->whereDate('created_at','>=',$dateFrom)
                    // ->whereDate('created_at','<=',$dateTo)
                    ->get()
                );
            }
            
            if($consQuery->isEmpty()){
                $item->mm_id = 0;
                $item->cm_id = 0;
                $unMetered = 0;
                $metered = 0;
            }else{
                // Count Consumer Metered and Unmetered
                
                $item->cm_id = $consQuery->count();
                $unMetered = $consQuery->whereIn('mm_id',[40085,1,2,NULL])->count(); // un-metered
                $metered = $consQuery->whereNotIn('mm_id',[40085,1,2,NULL])->count(); // metered
                // $metered = $consQuery->whereNotNull('mm_id','!=','NULL')->count(); // metered
            }
            return[
                'cons_id'=>$item->cm_id,
                'tc_id'=>$item->tc_id,
                'Town_Code'=>$item->tc_code,
                'Town_Name'=>$item->tc_name,
                'Route_Code'=>$item->rc_code,
                'Route_ID'=>$item->rc_id,
                'Route_Name'=>$item->rc_desc,
                'Metered'=> $metered,
                'Un_Metered'=> $unMetered
            ];

        });

        if($request->selected == 'area'){
            $grouped = $mapped->groupBy('tc_id');
        }else if($request->selected == 'town'){
            $grouped = $mapped->groupBy('Route_ID');
        }else{
            return response(['Message'=>'Invalid Selection'],422);
        }
        
        $groupedMap = $grouped->map(function($item){
            return[
                'Town_Code'=>$item[0]['Town_Code'],
                'Town_Name'=>$item[0]['Town_Name'],
                'Route_Code'=>$item[0]['Route_Code'],
                'Route_Name'=>$item[0]['Route_Name'],
                'Consumer_Count'=> $item->sum('cons_id'),
                'Metered_Count'=> $item->sum('Metered'),
                'Unmetered_Count'=> $item->sum('Un_Metered'),
            ];
        });
        return response([
            'Info'=> $groupedMap->values()->all()
        ],200);
    }
}
