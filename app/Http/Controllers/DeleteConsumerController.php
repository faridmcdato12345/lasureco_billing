<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DeleteConsumerController extends Controller
{
    public function getConsumers(){
        $query = Consumer::select('cm_id','ct_id','cm_account_no','cm_full_name','cm_address','cm_con_status','mm_master','created_at');    
        // dd($query);
        return datatables($query)
        ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<button class="modify btn btn-danger btn-sm deleteButton" data-id="'.$row->cm_id.'" id="role-view" data-toggle="modal" data-target="#addRole">Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function deleteConsumer(Request $request) {
        $query = DB::table('sales')
        ->where('cm_id', '=', $request->id)
        ->count();
        $query2 = DB::table('meter_reg')
        ->where('cm_id', '=', $request->id)
        ->count();

        if($query > 0 || $query2 > 0){
            return response(['Message'=>'Do not delete!'],201);
        } else {
            return response(['Message'=>'Go ahead and delete!'],200);
        }
    }

    public function destroyConsumer(Request $request) {
        Consumer::where('cm_id', '=', $request->id)->delete();

        return response(['Message'=>'Successful!']);
    }

    public function supervisory(Request $request) {
        $password = DB::table('user')
        ->where('user_id', 16)
        ->value('password');

        // dd($password);

        if (Hash::check($request->password, $password)) {
            return response(['Message'=>'1']);
        } else {
            return response(['Message'=>'0']);
        }
    }
}