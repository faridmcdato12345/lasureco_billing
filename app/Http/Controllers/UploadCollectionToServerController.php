<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Sales;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Services\UploadDailyCollection;

class UploadCollectionToServerController extends Controller
{

    protected $saless;
    
    public function index(){
        $users = User::role('teller')->get();
        return view('user.utility.upload_collection_to_server',compact('users'));
    }
    public function getCollection(Request $request){
        $fromDate = Carbon::parse($request->from_date)->format('Y-m-d');
        $toDate = Carbon::parse($request->to_date)->format('Y-m-d');
        $collections = Sales::where('teller_user_id',$request->user_id)
                        ->where('server_added',0)
                        ->whereBetween('s_bill_date',[$fromDate,$toDate])
                        ->get();
        if($collections->isEmpty()){
            return response()->json($collections,404);
        }
        return response()->json($collections,200);
    }
    public function updateLocalDb(Request $request){
        $sales = Sales::where('s_bill_no',$request->bill_no)->first();
        $ip = Server::select('ip_address')->first();
        $find = Http::post('http://'.$ip->ip_address.'/lasureco_billing/public/api/v1/find_data_to_server',[
            's_bill_no' => $sales->s_bill_no
        ]);
        if($find->successful()){
            $reponse = $find->getBody()->getContents();
            $queryData = json_decode($reponse);
            if(is_null($reponse)){
                $insert = Http::post('http://'.$ip->ip_address.'/lasureco_billing/public/api/v1/insert_to_server', [
                    'mr_id' => $queryData[0]->mr_id,
                    'f_id' => $queryData[0]->f_id,
                    'ct_id' => $queryData[0]->ct_id,
                    'cons_accountno' => $queryData[0]->cons_accountno,
                    's_or_num' => $queryData[0]->s_or_num,
                    'cm_id'=>$queryData[0]->cm_id,
                    's_or_amount'=>$queryData[0]->s_or_amount,
                    'v_id'=>$queryData[0]->v_id,
                    's_bill_no'=>$queryData[0]->s_bill_no,
                    's_bill_amount'=>$queryData[0]->s_bill_amount,
                    's_bill_date'=>$queryData[0]->s_bill_date,
                    's_status'=>$queryData[0]->s_status,
                    's_mode_payment'=>$queryData[0]->s_mode_payment,
                    'cheque_id'=>$queryData[0]->cheque_id,
                    's_ref_no'=>$queryData[0]->s_ref_no,
                    'ref_date'=>$queryData[0]->ref_date,
                    'teller_user_id'=>$queryData[0]->teller_user_id,
                    'temp_teller_user_id'=>$queryData[0]->temp_teller_user_id,
                    's_ack_receipt'=>$queryData[0]->s_ack_receipt,
                    'ackn_date'=>$queryData[0]->ackn_date,
                    'ackn_user_id'=>$queryData[0]->ackn_user_id,
                    'temp_ackn_user_id'=>$queryData[0]->temp_ackn_user_id,
                    'mr_arrear'=>$queryData[0]->mr_arrear,
                    'e_wallet_applied'=>$queryData[0]->e_wallet_applied,
                    'e_wallet_added'=>$queryData[0]->e_wallet_added,
                    'ap_status'=>$queryData[0]->ap_status,
                    'deleted_at'=>$queryData[0]->deleted_at,
                    'server_added'=>1
                ]);
                if($insert->successful()){
                    $update = DB::table('sales')->where('s_bill_no',$request->bill_no)->update(['server_added' => 1]);
                    return response()->json($insert,201);
                }
                if($insert->failed()){
                    return response()->json(['message'=>'if insert error'],500);
                }
            }else{
                $insert = Http::post('http://'.$ip->ip_address.'/lasureco_billing/public/api/v1/insert_to_server', [
                'mr_id' => $queryData[0]->mr_id,
                'f_id' => $queryData[0]->f_id,
                'ct_id' => $queryData[0]->ct_id,
                'cons_accountno' => $queryData[0]->cons_accountno,
                's_or_num' => $queryData[0]->s_or_num,
                'cm_id'=>$queryData[0]->cm_id,
                's_or_amount'=>null,
                'v_id'=>$queryData[0]->v_id,
                's_bill_no'=>$queryData[0]->s_bill_no,
                's_bill_amount'=>$queryData[0]->s_bill_amount,
                's_bill_date'=>$queryData[0]->s_bill_date,
                's_status'=>$queryData[0]->s_status,
                's_mode_payment'=>$queryData[0]->s_mode_payment,
                'cheque_id'=>$queryData[0]->cheque_id,
                's_ref_no'=>$queryData[0]->s_ref_no,
                'ref_date'=>$queryData[0]->ref_date,
                'teller_user_id'=>$queryData[0]->teller_user_id,
                'temp_teller_user_id'=>$queryData[0]->temp_teller_user_id,
                's_ack_receipt'=>$queryData[0]->s_ack_receipt,
                'ackn_date'=>$queryData[0]->ackn_date,
                'ackn_user_id'=>$queryData[0]->ackn_user_id,
                'temp_ackn_user_id'=>$queryData[0]->temp_ackn_user_id,
                'mr_arrear'=>$queryData[0]->mr_arrear,
                'e_wallet_applied'=>$queryData[0]->s_or_amount,
                'e_wallet_added'=>$queryData[0]->e_wallet_added,
                'ap_status'=>$queryData[0]->ap_status,
                'deleted_at'=>$queryData[0]->deleted_at,
                'server_added'=>1
                ]);
                if($insert->successful()){
                    $update = DB::table('sales')->where('s_bill_no',$request->bill_no)->update(['server_added' => 1]);
                    return response()->json($insert,201);
                }
                if($insert->failed()){
                    return response()->json($insert,500);
                }
            }
        }
        if($find->failed()){
            return response()->json($find,500);
        }
        
    }
}
