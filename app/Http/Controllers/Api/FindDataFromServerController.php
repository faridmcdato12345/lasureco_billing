<?php

namespace App\Http\Controllers\Api;

use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FindDataFromServerController extends Controller
{
    public function findSale(Request $request){
        $query = DB::table('sales')->where('s_bill_no',$request->bill_no)->get();
        return response()->json($query,200);
    }
}
