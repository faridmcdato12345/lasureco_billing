<?php

namespace App\Http\Controllers;

use App\Exports\DataExport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExportSalesDataController extends Controller
{
    public function index(){
        $users = User::role('teller')->get();
        return view('user.utility.export_sales_data.index',compact('users'));
    }
    public function exportSales(Request $request){
        $f = (new DataExport($request->from_date,$request->to_date,$request->user_id))
            ->download('sales.xlsx');
        if($f){
            $sales = DB::table('sales')
            ->whereBetween('s_bill_date',[$request->from_date,$request->to_date])
            ->where('teller_user_id',$request->user_id)
            ->update(['server_added' => 0]);
            return $f;
        }
    }
}
