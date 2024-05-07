<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\EmployeeMasterResource;
use App\Models\User;

class EmployeeMasterController extends Controller
{
    public function index()
    {
        return EmployeeMasterResource::collection(
            DB::table('emp_master')
            ->whereNull('deleted_at')
            ->paginate(10));
    }
    public function show($id)
    {
        return EmployeeMasterResource::collection(
            DB::table('emp_master')
            ->where('em_id',$id)
            ->get());
    }
    public function showMeterReader()
    {
        $data = DB::table('emp_master')
        ->select('em_emp_no' , 'gas_fnamesname')
        ->where('ebs_designation','meterreader')
        ->get() ;
        return response(['data'=>$data],200);
    }
    public function showTellers()
    {
        $users = User::role('teller')->get();
        
        return response(['Tellers'=>$users],200);
    }
    public function searchTellers($search){
        $teller = User::role('teller')->where('user_full_name','LIKE',$search.'%')->get();
        return response(['Tellers'=>$teller],200);
    }
    public function showCashier()
    {
        $cashier = DB::table('emp_master')
            ->select('em_emp_no','ebs_designation','gas_fnamesname')
            ->where('ebs_designation','cashier')
            ->get();
        
        return response(['Cashiers'=>$cashier]);
    }
}
