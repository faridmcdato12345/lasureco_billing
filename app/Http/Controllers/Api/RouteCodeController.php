<?php

namespace App\Http\Controllers\Api;

use App\Models\Consumer;
use App\Models\TownCode;
use App\Models\RouteCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\RouteCodeResource;
use App\Http\Requests\StoreRouteCodeRequest;

class RouteCodeController extends Controller
{
    public function index()
    {
        return RouteCodeResource::collection(
            DB::table('route_code')
            ->whereNull('deleted_at')
            ->orderBy('rc_desc', 'asc')
            ->paginate(5));
    }
    public function search($req)
    {
        if(is_numeric($req)){
            return RouteCodeResource::collection(
                DB::table('route_code')
                ->where('rc_code','LIKE', $req.'%')
                ->whereNull('deleted_at')
                ->orderBy('rc_desc', 'asc')
                ->get());
        }
        return RouteCodeResource::collection(
            DB::table('route_code')
            ->where('rc_desc','LIKE', $req.'%')
            ->whereNull('deleted_at')
            ->orderBy('rc_desc', 'asc')
            ->get());
    }
    public function searchRouteByTownID($id,$req)
    {
        if(is_numeric($req)){
            return DB::table('route_code AS rc')
                ->select('rc.rc_id','rc.rc_code','rc_desc','rc.tc_id')
                ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
                ->where('rc.rc_code','LIKE', $req.'%')
                ->where('rc.tc_id',$id)
                ->whereNull('rc.deleted_at')
                ->orderBy('rc_desc', 'asc')
                ->get();
        }
        return DB::table('route_code AS rc')
            ->select('rc.rc_id','rc.rc_code','rc_desc','rc.tc_id')
            ->join('town_code AS tc','rc.tc_id','=','tc.tc_id')
            ->where('rc.rc_desc','LIKE', $req.'%')
            ->where('rc.tc_id',$id)
            ->whereNull('rc.deleted_at')
            ->orderBy('rc_desc', 'asc')
            ->get();
    }
    public function store(StoreRouteCodeRequest $request)
    {
        // dd($request->rc_code,$request->tc_id);
        $data = RouteCode::create($request->all());
        if($data){
            $query= DB::table('route_code as rc')
            ->join('town_code as tc','rc.tc_id','=','tc.tc_id')
            ->where('rc_code',$request->rc_code)->first();
            if($query){
                $update = DB::table('route_code')
                ->where('rc_id',$query->rc_id)
                ->where('tc_id',$request->tc_id)
                ->update([
                    'temp_town_code'=> $query->tc_code
                ]);
            }
            
        }
        return response($data,201);
    }
    public function showRoutes($id)
    {
        //show Routes based on town ID
        return  RouteCodeResource::collection(
                DB::table('route_code')
                ->whereNull('deleted_at')
                ->orderBy('rc_desc', 'asc')
                ->where('tc_id',$id)
                ->paginate(5));
    }
    
    public function update(StoreRouteCodeRequest $request, $id)
    {
        $data = RouteCode::findOrFail($id);
        $data->update($request->all());
        return response(['message'=>'Succesfully Updated'],200);
    }

    public function destroy($id)
    {
        $route = RouteCode::findOrFail($id);
        
        if(!$route->consumers()->first())
        {
            $route->delete();
            return response(['Message' => 'Succesfully Deleted'],202);
        }
        
        return response(['Message' => 'With Children'],409);
    }

    public function getDatatable(){
        $query = RouteCode::with('townCode')->select('tc_id','rc_id','rc_code','rc_desc');    
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('towns', function($row){
                $s = DB::table('town_code')->select('tc_name')->where('tc_id',$row->tc_id)->first();
                return $s->tc_name;
            })
            ->addColumn('areas', function($row){
                $tid = DB::table('town_code')->select('ac_id','tc_id')->where('tc_id',$row->tc_id)->first();
                $area = DB::table('area_code')->where('ac_id',$tid->ac_id)->first();
                return $area->ac_name;
            })
            ->addColumn('action', function($row){
                $btn = '<button class="btn btn-primary btn-sm" route_code="'.$row->rc_code.'" value="'.$row->rc_id.'" id="select">Select</button>';
                return $btn;
            })
            ->rawColumns(['action','towns','areas'])
            ->make(true);
    }
    public function select(RouteCode $route){
        $rcIdAccountNo = Consumer::with('routes')->where('rc_id',$route->rc_id)->max('cm_account_no');
        $rcIdSeqNo = Consumer::with('routes')->where('rc_id',$route->rc_id)->max('cm_seq_no');
        $i = RouteCodeResource::collection(RouteCode::where('rc_id',$route->rc_id)->get());
        $freshRoute = RouteCode::where('rc_id',$route->rc_id)->first();
        $rcIdIncrement = ($rcIdAccountNo != null) ? (int)$rcIdAccountNo + 1 : $freshRoute->temp_town_code . $freshRoute->rc_code . '0001';
        $seqIdIncrement = ($rcIdSeqNo != null) ? (int)$rcIdSeqNo + 5 : 1;
        return response()->json([
            'accntNo' => $rcIdIncrement,
            'seqNo' => $seqIdIncrement,
            'name' => RouteCode::select('rc_id','rc_desc')->where('rc_id',$route->rc_id)->first(),
            'area' => $i
        ],200);
    }
}