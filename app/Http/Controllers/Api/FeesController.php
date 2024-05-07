<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeesRequest;
use Illuminate\Http\Request;
use App\Http\Resources\FeesResource;
use App\Models\Fees;
use Illuminate\Support\Facades\DB;

class FeesController extends Controller
{
    public function index()
    {
        return FeesResource::collection(
            DB::table('fees')
            ->whereNull('deleted_at')
            ->orderBy('f_description', 'asc')
            ->get());
    }
    public function store(StoreFeesRequest $request)
    {
        $data = Fees::create($request->all());
        return response($data,201);
    }
    public function search($request)
    {
        return FeesResource::collection(
            DB::table('fees')
            ->where('f_description','LIKE', $request.'%')
            ->whereNull('deleted_at')
            ->orderBy('f_description', 'asc')
            ->paginate(10));
    }
    public function update(StoreFeesRequest $request, $id)
    {
        $data = Fees::findOrFail($id);
        $data->update($request->all());
        return response(['message'=>'Succesfully Updated'],200);
    }
    public function destroy($id)
    {
        $fees = Fees::findOrFail($id);
        $fees->delete();

        return response(['Message' => 'Succesfully Deleted'],202);
    }
    public function printSummNBCollection(Request $request)
    {
        $summNBColl = DB::table('cons_master AS cm')
            ->join('route_code AS rc','cm.rc_id','=','rc.rc_id')
            ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
            ->join('area_code AS ac','tc.ac_id','=','ac.ac_Id')
            ->join('sales AS s','cm.cm_id','=','s.cm_id')
            ->join('fees AS f','s.f_id','=','f.f_id')
            ->select('cm.cm_account_no','cm.cm_full_name','s.f_id','s.s_or_amount','s.s_bill_amount',
                's.s_bill_date','f.f_description','f.f_code')
            ->where('ac.ac_id',$request->ac_id)
            ->where('s.f_id','!=',0)
            ->Where('s.f_id','!=',36)
            ->whereBetween('s.s_bill_date',[$request->from,$request->to])
            ->get();
        $check = $summNBColl->first();
        if(!$check)
        {
            return response(['Message'=>'No Summary of NB Collection on Date From '.$request->from.' To '.$request->to],422);
        }
        
        $summColl = collect($summNBColl);
        $sumColl = $summColl->sum('s_bill_amount');
        $f_id = $summNBColl->pluck('f_id')->first();
        // $actgFCode = $summNBColl->pluck('f_code')->sortBy('f_id')->unique();
        // $actgFdesc = $summNBColl->pluck('f_description')->sortBy('f_id')->unique();
        $actgGroups = $summNBColl->groupBy('f_id');
        $actgGroupSum = $actgGroups->map(function ($actgGroup) {
            return [
                'actg_code' => $actgGroup->first()->f_code,
                'actg_desc' => $actgGroup->first()->f_description,
                'actg_amount' => $actgGroup->sum('s_bill_amount')
            ];
        });

        return response([
            'NonBill'=> $summNBColl,
            'Total_Amount'=>$sumColl,
            'Fee_ID'=>$f_id,
            'Actg_Amount'=>$actgGroupSum

        ]);
    }
}
