<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use NumberFormatter;
use App\Models\Sales;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PostingController extends Controller
{
    public function index(){
        $q = DB::table('sales as s')
        ->select('teller_user_id','s_bill_date',DB::raw('COALESCE(SUM(s.s_or_amount),0) + COALESCE(sum(s.e_wallet_added),0) as total_sales'))
        ->whereNull('s_ack_receipt')
        ->orWhere('s_ack_receipt',0)
        ->where('s_cutoff',1)
        ->groupBy(['teller_user_id','s_bill_date'])
        ->get();
        // return $q;
        $cq = $q->collect();
        return DataTables::of($cq)
        ->addColumn('action', function($row) {

            $btn = '<button class="btn btn-primary btn-sm select-collection" value="'.$row->s_bill_date.'">Select</button>';
            $btn = $btn . '<input type="hidden" value="'.$row->teller_user_id.'">';
            return $btn;
        })
        ->editColumn('teller_user_id',function($row){
            $user = User::select('user_id','user_full_name')->where('user_id',$row->teller_user_id)->get();
            foreach($user as $key => $data){
                return $data->user_full_name;
            }
        })
        ->editColumn('total_sales', function($row) {
            // $x = DB::table('sales')
            // ->select('teller_user_id','s_bill_date','s_ack_receipt',DB::raw('SUM(e_wallet_added) as total_ewallet'))
            // ->where('teller_user_id',$row->teller_user_id)->where('s_ack_receipt',0)->first();
            $fmt = new \NumberFormatter("en_PH", NumberFormatter::CURRENCY);
            // $totalSales = (float)$row->total_sales + (float)$x->total_ewallet;
            $p = $fmt->format(number_format((float)$row->total_sales, 2, '.', ''));
            return $p;
        })
        ->editColumn('s_bill_date',function($row){
            return Carbon::parse($row->s_bill_date)->format('F d'.', '.' Y');
        })
        ->rawColumns(['action'])
        ->toJson();
    }
    public function addAcknowledgementReceipt(Request $request){
        
        $sales = Sales::where('s_ack_receipt',$request->s_ack_receipt)->first();
        if($sales){
            return response()->json(['message' => $request->s_ack_receipt . 'already used!'],404);
        }
        $this->validate($request, [
            's_ack_receipt' => 'required'
        ]);
        $sales = Sales::whereDate('s_bill_date',$request->bill_date)
        ->where([
            ['s_cutoff',1],
            ['teller_user_id',$request->teller_name]
        ])
        ->update(
            [
                's_ack_receipt' => $request->s_ack_receipt,
                's_status' => 0,
                'ackn_date' => Carbon::today()
            ],
        );
        return response()->json($sales,200);
    }
}
