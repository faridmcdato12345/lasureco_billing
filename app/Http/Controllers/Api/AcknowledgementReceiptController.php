<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\AuditTrailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcknowledgementReceiptController extends Controller
{
    public function setTotalCollectionAR(Request $request)
    {
        $acknTotalAmount = collect(DB::table('sales')
            ->select(DB::raw('COALESCE(SUM(s_or_amount),0) + COALESCE(sum(e_wallet_added),0) as amount,ackn_date as date'))
            ->where('s_ack_receipt',$request->orig_ackn_receipt)
            // ->whereDate('ackn_date',$request->orig_ackn_date)
            ->get());
        // dd($acknTotalAmount->first()->date);
        // if($acknTotalAmount->isNotEmpty())
        // {
        //     return response(['Message'=>'Acknowledgment Receipt: '.$request->orig_ackn_receipt.' Doesnt Exist'],422);
        // }
        
        return response([
            'Total_Collection'=>round($acknTotalAmount->sum('amount'),2),
            'Date_posted'=>$acknTotalAmount->first()->date
    ], 200);
    }
    public function changeAcknRecpt(Request $request)
    {
        $acknRecpt = DB::table('sales')
            ->where('s_ack_receipt',$request->orig_ackn_receipt)
            // ->whereDate('ackn_date',$request->orig_ackn_date)
            ->get();
        
        $check = $acknRecpt->first();
        if(!$check)
        {
            return response(['Message'=>'Acknowledgment Receipt Doesnt Exist'],422);
        }

        $acknRecpt = collect(DB::table('sales')
            ->where('s_ack_receipt',$request->new_ackn_receipt)
            // ->whereDate('ackn_date',$request->orig_ackn_date)
            ->get());

        if($acknRecpt->isNotEmpty()){
            return response(['Message'=>'New Acknowledgment Receipt Already Exist'],422);
        }
        
        $current_date_time = Carbon::now()->toDateTimeString();

        //For Audit Trail
        $at_old_value = $request->orig_ackn_receipt;
        $at_new_value = $request->new_ackn_receipt;
        $at_action = 'Modify';
        $at_table = 'Sales';
        $at_auditable = 'Acknowledgement Receipt';
        $user_id = $request->user_id;
        $id = null;
        $data = (new AuditTrailService())->auditTrail($at_old_value,$at_new_value,$at_action,$at_table,$at_auditable,$user_id,$id);
        
        DB::table('sales')
            ->where('s_ack_receipt',$request->orig_ackn_receipt)
            ->update([
                's_ack_receipt'=>$request->new_ackn_receipt,
                'ackn_date'=>$current_date_time,
                'ackn_user_id'=>$request->user_id,
            ]);

        return response(['Acknowldgement_Receipt'=>'Succesfully Updated'], 200);
    }
}
