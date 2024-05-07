<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeterReading extends Controller
{
    public function mrrInq1(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date_period);
        $mrrInq = collect(
            DB::table('meter_reg as mr')
            ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->where('cm.rc_id',$request->route_id)
            ->where('mr.mr_date_year_month',$billingPeriod)
            ->get()
        );

        if(!$mrrInq->first())
        {
            return response(['Message'=>'No Record Found'],422);
        }

        $mapped = $mrrInq->map(function($item){
            $mrrInq = collect(
                DB::table('meter_reg as mr')
                ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
                ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
                ->select(DB::raw('sum(mr.mr_amount) as amount,max(mr.mr_date_reg) as dateReg'))
                ->where('cm.rc_id',$item->rc_id)
                ->where('mr.mr_date_year_month','<',$item->mr_date_year_month)
                ->where('mr.cm_id',$item->cm_id)
                ->orderBy('mr.mr_date_year_month','desc')
                ->first());
            $dateCreatePres = date_create($item->mr_date_reg);
            $dateCreatePrev = ($mrrInq['dateReg'] == '')? '' : date_create($mrrInq['dateReg']);
            
            return[
                'Account_Number'=>$item->cm_account_no,
                'CT'=>$item->ct_code,
                'Status'=>($item->cm_con_status == 1)? 'A':'D',
                'Date_Read'=>date_format($dateCreatePres,"Y/m/d"),
                'Time_Read'=>date_format($dateCreatePres,"H:i:s a"),
                'Last_Reading_Date'=>($dateCreatePrev == '') ? '0' : date_format($dateCreatePrev,"Y/m/d"),
                'Prev_Reading'=>$item->mr_prev_reading,
                'Pres_Reading'=>$item->mr_pres_reading,
                'Mtr_Mult'=>$item->cm_kwh_mult,
                'KWH_Used'=>$item->mr_kwh_used,
                'Curr_Amount_Due'=>round($item->mr_amount,2),
                'Total_Amount_Due'=>round($item->mr_amount + $mrrInq['amount'],2),
                'Read'=>"Yes",
                'Print'=>($item->mr_printed == 1)? 'Yes':'No',
            ];
        });

        return response([
            'MRR_INQ_1'=>$mapped
        ]);
    }
    public function mrrInqCons2(Request $request)
    {
        $billingPeriodFrom = str_replace("-","",$request->date_period_from);
        $billingPeriodTo = str_replace("-","",$request->date_period_to);
        $mrrInqCons = collect(
            DB::table('meter_reg as mr')
            ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->where('mr.cm_id',$request->cons_id)
            ->whereBetween('mr.mr_date_year_month',[$billingPeriodFrom,$billingPeriodTo])
            ->get()
        );
        if(!$mrrInqCons->first())
        {
            return response(['Message'=>'No Record Found'],422);
        }

        $mapped = $mrrInqCons->map(function($item){
            $sum = collect(
                DB::table('meter_reg as mr')
                ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
                ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
                ->select(DB::raw('sum(mr.mr_amount) as amount'))
                ->where('cm.rc_id',$item->rc_id)
                ->where('mr.mr_date_year_month','<',$item->mr_date_year_month)
                ->where('mr.cm_id',$item->cm_id)
                ->orderBy('mr.mr_date_year_month','desc')
                ->first());
            return[
                'Date_Read'=>$item->mr_date_reg,
                'Bill_No'=>$item->mr_bill_no,
                'Yr_Mo'=>$item->mr_date_year_month,
                'Status'=>($item->cm_con_status == 1)? 'Yes' : 'No',
                'Pres_Reading'=>$item->mr_pres_reading,
                'Prev_Reading'=>$item->mr_prev_reading,
                'KWH_Used'=>$item->mr_kwh_used,
                'Curr_Amount_Due'=>$item->mr_amount,
                'Total_Amount_Due'=>round($item->mr_amount + $sum['amount'],2),
            ];
        });

        return response(['MRR_CONS_INQ'=>$mapped],200);
    }
    public function meterReadingActivity(Request $request)
    {
        $billingPeriod = str_replace("-","",$request->date_period);
        if($request->sortBy == 'account')
        {
            $mrrActivity = collect(
                DB::table('meter_reg as mr')
                ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
                ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->where('cm.rc_id',$request->route_id)
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->orderBy('cm.cm_account_no','asc')
                ->get()
            );
        }else{
            $mrrActivity = collect(
                DB::table('meter_reg as mr')
                ->join('cons_master as cm','mr.cm_id','=','cm.cm_id')
                ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
                ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
                ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
                ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
                ->where('cm.rc_id',$request->route_id)
                ->where('mr.mr_date_year_month',$billingPeriod)
                ->orderBy('cm.cm_seq_no','asc')
                ->get()
            );
        }

        if(!$mrrActivity->first())
        {
            return response(['Message'=>'No Record Found'],422);
        }

        $num = new \stdClass();
        $num->number = 0;

        $mapped = $mrrActivity->map(function($item) use($num){
            $dateCreatePres = date_create($item->mr_date_reg);
            return[
                'No'=> ++$num->number,
                'Acct_No'=>$item->cm_account_no,
                'Acct_Name'=>$item->cm_full_name,
                'Type'=>$item->ct_code,
                'Read_Print'=>'Y'.'/'.($item->mr_printed == 1)? 'Y' : 'N',
                'Previous_reading'=>$item->mr_prev_reading,
                'Present_reading'=>$item->mr_pres_reading,
                'KWH_Used'=>$item->mr_kwh_used,
                'Previous_demand'=>$item->mr_prev_dem_reading,
                'Present_demand'=>$item->mr_pres_dem_reading,
                'Demand_Used'=>$item->mr_dem_kwh_used,
                'Time_Read'=>date_format($dateCreatePres,"H:i:s a"),
            ];
        });

        return response([
            'Area'=>'0'.$mrrActivity->first()->ac_id.' '.$mrrActivity->first()->ac_name,
            'Town'=>$mrrActivity->first()->tc_code.' '.$mrrActivity->first()->tc_name,
            'Route'=>$mrrActivity->first()->rc_code.' '.$mrrActivity->first()->rc_desc,
            'Meter_Reading_Activity'=>$mapped
        ],200);
    }
}
