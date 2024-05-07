<?php

namespace App\Http\Controllers\Api;

use App\Models\Sales;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class InsertServerController extends Controller
{
    public function store(Request $request){
        $data = new Sales();
        $data->cm_id = $request->cm_id;
        $data->mr_id = $request->mr_id;
        $data->f_id = $request->f_id;
        $data->ct_id = $request->ct_id;
        $data->cons_accountno = $request->cons_accountno;
        $data->s_or_num = $request->s_or_num;
        $data->s_or_amount = $request->s_or_amount;
        $data->v_id = $request->v_id;
        $data->s_bill_no = $request->s_bill_no;
        $data->s_bill_amount = $request->s_bill_amount;
        $data->s_bill_date = $request->s_bill_date;
        $data->s_status = $request->s_status;
        $data->s_mode_payment = $request->s_mode_payment;
        $data->cheque_id = $request->cheque_id;
        $data->s_ref_no = $request->s_ref_no;
        $data->ref_date = $request->ref_date;
        $data->teller_user_id = $request->teller_user_id;
        $data->temp_teller_user_id= $request->temp_teller_user_id;
        $data->s_ack_receipt= $request->s_ack_receipt;
        $data->ackn_date= $request->ackn_date;
        $data->ackn_user_id= $request->ackn_user_id;
        $data->temp_ackn_user_id= $request->temp_ackn_user_id;
        $data->mr_arrear= $request->mr_arrear;
        $data->e_wallet_applied= $request->e_wallet_applied;
        $data->e_wallet_added= $request->e_wallet_added;
        $data->ap_status= $request->ap_status;
        $data->deleted_at= $request->deleted_at;
        $data->server_added= 1;
        $data->save();
        if(!$data){
            return response()->json($data,500);
        }
        $update = DB::table('sales')->where('s_bill_no',$request->s_bill_no)->update(['server_added' => 1]);
        if(!$update){
            return response()->json($data,500);
        }
        return response()->json($data,201);
    }
}
