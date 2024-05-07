<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CancelBill;
use App\Models\EWALLET;
use App\Models\EWALLET_LOG;
use App\Models\MeterReg;
use App\Services\AuditTrailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function eWalletEntry(Request $request)
    {
        $consEwallet = DB::table('e_wallet')
            ->select('ew_id','ew_total_amount')
            ->where('cm_id',$request->cons_id)
            ->first();
        $ewBal = $consEwallet->ew_total_amount;
        if(!$consEwallet)
        {
            return response(['Message'=>'Consumer Ewallet Doesnt Exist'],422);
        }
        $checkOR = DB::table('sales')
            ->select('s_or_num')
            ->where('cm_id',$request->cons_id)
            ->where('s_or_num',$request->ewallet_or)
            ->first();
        if(!$checkOR)
        {
            return response(['Message'=>'Consumer OR Doesnt Exist'],422);
        }
        $equalORDate = DB::table('sales')
            ->select('s_or_num')
            ->where('cm_id',$request->cons_id)
            ->where('s_or_num',$request->ewallet_or)
            ->where('s_bill_date',$request->ewallet_or_date)
            ->first();
        if(!$equalORDate)
        {
            return response(['Message'=>'Incorrect Date on Consumer OR'],422);
        }

        $entry = EWALLET_LOG::create([
            'ew_id' => $consEwallet->ew_id,
            'ewl_amount'=> $request->ewallet_amount,
            'ewl_or'=> $request->ewallet_or,
            'ewl_or_date'=> $request->ewallet_or_date,
            'ewl_remark'=> $request->ewallet_remarks
        ]);

        $updateEWallet = EWALLET::findOrFail($consEwallet->ew_id);
            $updateEWallet->ew_total_amount = $ewBal + $request->ewallet_amount;
            $updateEWallet->save();

        return response([
            'Message'=>'Succesful Entry of Ewallet Amount',
            'Ewallet'=>$entry,
            'Ewallet_Update'=>$updateEWallet,
        ],201);
    }
    public function cancelBilling($id,$date,$user_id)
    {
        $billPeriod = str_replace("-","",$date);
        $saleToCancel = DB::table('meter_reg')
            ->where('cm_id',$id)
            ->where('mr_date_year_month',$billPeriod)
            ->where('mr_status', 0)
            ->where('mr_printed', 1)
            ->first();

        if(!$saleToCancel)
        {
            return response(['Message'=>'Bill Dont exist'],422);
        }
        //Careate/Transfer data to Canceled Bill
        $data = new CancelBill();
        $data->cm_id = $id;
        // $data->mr_id = $saleToCancel->mr_id;
        $data->cb_date_year_month = $saleToCancel->mr_date_year_month;
        $data->cb_kwh_used = $saleToCancel->mr_kwh_used;
        $data->cb_amount = $saleToCancel->mr_amount;
        $data->cb_bill_num = $saleToCancel->mr_bill_no;
        $data->user_id = $user_id;
        $data->cb_date = Carbon::now()->toDateTimeString();
        $data->save();

        //For Audit Trail
        // $at_old_value = '';
        // $at_new_value = '';
        // $at_action = 'Cancel PowerBill';
        // $at_table = 'Meter_Reg';
        // $at_auditable = '';
        // $user_id = $user_id;
        // $data = (new AuditTrailService())->auditTrail($at_old_value,$at_new_value,$at_action,$at_table,$at_auditable,$user_id,$id);
        // Delete Bill Transaction
        DB::table('meter_reg')
        ->where('mr_id',$saleToCancel->mr_id)
        ->delete();

        return response(['Message'=>'Succesfully Cancelled Bill'],200);
    }
    public function cancelBillingAmount($id,$date)
    {
        $billPeriod = str_replace("-","",$date);
        $amount = DB::table('meter_reg')
            ->where('cm_id',$id)
            ->where('mr_date_year_month',$billPeriod)
            ->whereNull('deleted_at')
            ->where('mr_status', 0)
            ->sum('mr_amount');
        if(!$amount)
        {
            return response(['Message'=>'Bill Dont exist'],422);
        }

        return response(['Amount'=>round($amount,2)],200);
    }
    public function summOfCancelledBills($date)
    {
        $billPeriod = str_replace("-","",$date);
        $month = substr($billPeriod,4,6);
        $year = substr($billPeriod,0,4);
        $canBills = DB::table('canceled_bill AS cb')
            ->join('cons_master AS cm','cb.cm_id','=','cm.cm_id')
            ->join('route_code AS rc','cm.rc_id','=','rc.rc_id')
            ->join('user AS u','cb.user_id','=','u.user_id')
            ->select('rc.rc_id','cm.cm_account_no','cm.cm_full_name','cb.cb_date_year_month','cb.cb_kwh_used','cb.cb_amount','u.user_full_name','cb.cb_date')
            // ->where('rc.rc_id',$rcid)
            ->whereMonth('cb.cb_date',$month)
            ->whereYear('cb.cb_date',$year)
            ->get();
            // dd($month,$year,$canBills);
        $check = $canBills->first();
        if(!$check)
        {
            return response(['Message'=> 'No Record Found'],422);
        }

        $addressDetails = DB::table('route_code AS rc')
            ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
            ->select('rc.rc_desc','tc.tc_name')
            ->where('rc.rc_id',$check->rc_id)
            ->first();
        return response([
            'Cancelled_Bills'=> $canBills,
            'Address_Details'=> $addressDetails
        ]);
    }

    public function printEditBillList(Request $request)
    {
        // dd($request);
        $billPeriod = str_replace("-","",$request->bill_period);
        $queryBillList = collect(
            DB::table('cons_master as cm')
            ->join('meter_reg as mr','mr.cm_id','=','cm.cm_id')
            // ->leftJoin('meter_reg as mr',function($join) use ($billPeriod)
            // {
            //     $join->on('cm.cm_id','=', 'mr.cm_id')
            //     ->when($billPeriod, function ($query, $billPeriod) {
            //         return $query->where('mr_date_year_month', $billPeriod);
            //     });
            // })
            ->join('cons_type as ct','cm.ct_id','=','ct.ct_id')
            ->join('route_code as rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code as ac','tc.ac_id','=','ac.ac_id')
            ->join('billing_rates as br','mr.br_id','=','br.id')
            ->leftJoin('emp_master as emp','mr.mr_mtrReader','=','emp.em_emp_no')
            ->where('rc.rc_id',$request->route_id)
            ->where('mr.mr_date_year_month',$billPeriod)
            ->where('mr.mr_printed','!=',1)
            ->orderBy('cm.cm_seq_no','asc')
            // ->orWhere('mr.mr_printed',0)
            ->get()
        );
        if($queryBillList->isEmpty()){
            return response(['Message'=>'No Record Found'],422);
        }

        $mapped = $queryBillList->map(function($item){
            //Query Total Arrears
            $totalArrear = collect(DB::table('meter_reg')
                ->where('cm_id',$item->cm_id)
                ->where('mr_date_year_month','<',$item->mr_date_year_month)
                ->where('mr_status',0)
                ->sum('mr_amount'))
                ->first();
            // Query Previous KWH
            if($item->cm_id == 0){
                $prevKWH = 0;
            }else{
                $prevKWH1 = collect(DB::table('meter_reg')
                ->select('mr_kwh_used')
                ->where('cm_id',$item->cm_id)
                ->where('mr_date_year_month','<',$item->mr_date_year_month)
                ->orderBy('mr_date_year_month','desc')
                ->get());
                // $prevKWH = $prevKWH1->pluck('mr_kwh_used')->first();
                if($prevKWH1->isEmpty()){
                    $prevKWH = 0;
                }else{
                    $prevKWH = $prevKWH1->pluck('mr_kwh_used')->first();
                }
            }
            
            return[
                'Account_No'=>$item->cm_account_no,
                'Consumer_Name'=>$item->cm_full_name,
                'Type'=>$item->ct_code,
                'Kwh_Present_Reading'=>$item->mr_pres_reading,
                'Kwh_Previous_Reading'=>$item->mr_prev_reading,
                'KWH'=>$item->mr_kwh_used,
                'Dem_Present_Reading'=>$item->mr_pres_dem_reading,
                'Dem_Previous_Reading'=>$item->mr_prev_dem_reading,
                'Dem_KWH'=>$item->mr_dem_kwh_used,
                'MULT'=>$item->cm_kwh_mult,
                'TSF_RENT'=>'0.00',
                'ENERGY_CHARGES'=>$item->mr_amount,
                'M_ARREARS'=>round($totalArrear,2),
                'SURCHARGE'=>'0.00',
                'TOTAL_DUE'=>round($item->mr_amount +$totalArrear,2),
                'READING_DATE'=>date('M. d, Y', strtotime($item->mr_date_reg)),
                'Prev_Month'=>$prevKWH,
                'Difference'=>$item->mr_kwh_used - $prevKWH,
                'seq'=>$item->cm_seq_no,
            ];
        });

        $consUpdatePrint = DB::table('meter_reg AS mr')
            ->join('cons_master AS cm','cm.cm_id','=','mr.cm_id')
            ->where('cm.rc_id',$request->route_id)
            ->where('mr.mr_printed',2)
            ->where('mr.mr_date_year_month',$billPeriod)
            ->update([
                'mr_printed' => 0,
            ]);

        return response([
            'Area'=>'0'.$queryBillList->first()->ac_id.' '.$queryBillList->first()->ac_name,
            'Town'=>$queryBillList->first()->tc_code.' '.$queryBillList->first()->tc_name,
            'Route'=>$queryBillList->first()->rc_code.' '.$queryBillList->first()->rc_desc,
            'Bill_List'=>$mapped->values()->all(),
            'Total_Account'=>$mapped->count(),
            'Grand_Total_Energy'=>round($mapped->sum('ENERGY_CHARGES'),2),
            'Grand_Total_M_Arrears'=>round($mapped->sum('M_ARREARS'),2),
            'Grand_Due'=>round($mapped->sum('TOTAL_DUE'),2),
            'meter_reader'=>' '.$queryBillList->first()->gas_fnamesname
        ],200);
    }
}
