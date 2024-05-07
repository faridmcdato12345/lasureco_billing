<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintTellerDCRController extends Controller
{
    public function index()
    {
        //Carbon::now()->toDateTimeString()
        
        // $collection = DB::table('sales AS s')
        //     ->select('s.s_id','cm.cm_account_no','cm.cm_full_name','s.s_or_num','s.mr_arrear','s.s_bill_date','s.s_or_amount','s.s_bill_amount')
        //     ->join('consumer AS cm','s.cm_id','=','cm.cm_id')
        //     ->where('s.s_bill_date','2020-02-21')
        //     ->get();

        
        // $totalCurrent = DB::table('sales')
        //     ->where('s_bill_date','2020-02-21')
        //     ->sum('s_or_amount');
            
    }
}
