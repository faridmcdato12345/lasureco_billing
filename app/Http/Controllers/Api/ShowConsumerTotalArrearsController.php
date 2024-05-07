<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ShowConsumerTotalArrearsController extends Controller
{
    public function consumerTotalArrears(Request $request){
        $data = DB::table('meter_reg')->select('cons_account','mr_status','mr_amount',DB::raw('SUM(mr_amount) as total_arrears'))
        ->where('cons_account',$request->account_no)
        ->where('mr_status',0)
        ->where('mr_printed',1)
        ->get();
        if($data->isEmpty()){
            return response()->json(['message'=>'No arrears'],404);
        }
        return response()->json($data,200);
    }
}
