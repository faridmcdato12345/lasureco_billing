<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TownCodeResource;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTownCodeRequest;
use App\Http\Requests\UpdateTownCodeReqest;
use App\Models\TownCode;

class TownCodeController extends Controller
{
    public function index()
    {
        return TownCodeResource::collection(
            DB::table('town_code')
            ->whereNull('deleted_at')
            ->orderBy('tc_name', 'desc')
            ->paginate(5));
    }
    public function show($id)
    {
        return TownCodeResource::collection(
            DB::table('town_code')
            ->where('tc_id',$id)
            ->whereNull('deleted_at')
            ->get());
    }
    public function store(StoreTownCodeRequest $request)
    {
        $data = TownCode::create($request->all());
        return response($data,201);
    }
    public function showTowns($id)
    {
        return TownCodeResource::collection(
            DB::table('town_code')
            ->select('tc_id','tc_name','tc_code','ac_id')
            ->orderBy('tc_name', 'asc')
            ->where('ac_id',$id)
            ->whereNull('deleted_at')
            ->paginate(5));
    }
    public function search($request)
    {
        if(is_numeric($request)){
            return TownCodeResource::collection(
                DB::table('town_code')
                ->where('tc_code','LIKE', $request.'%')
                ->whereNull('deleted_at')
                ->get());
        }
        
        return TownCodeResource::collection(
            DB::table('town_code')
            ->where('tc_name','LIKE', $request.'%')
            ->whereNull('deleted_at')
            ->get());
    }
    public function searchByTownByAreaId($id,$req)
    {
        if(is_numeric($req)){
            return DB::table('town_code AS tc')
                ->select('tc.tc_id','tc.tc_code','tc_name','tc.ac_id')
                ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
                ->where('tc.tc_code','LIKE', $req.'%')
                ->where('tc.ac_id',$id)
                ->whereNull('tc.deleted_at')
                ->orderBy('tc_name', 'asc')
                ->get();
        }
        return DB::table('town_code AS tc')
            ->select('tc.tc_id','tc.tc_code','tc_name','tc.ac_id')
            ->join('area_code AS ac','tc.ac_id','=','ac.ac_id')
            ->where('tc.tc_name','LIKE', $req.'%')
            ->where('tc.ac_id',$id)
            ->whereNull('tc.deleted_at')
            ->orderBy('tc_name', 'asc')
            ->get();
    }
    public function update(StoreTownCodeRequest $request, $id)
    {
        $data = TownCode::findOrFail($id);
        $data->update($request->all());
        return response(['message'=>'Succesfully Updated'],200);
    }
    public function destroy($id)
    {
        $town = TownCode::findOrFail($id);
        $db = DB::table('route_code')
            ->select('tc_id')
            ->where('tc_id',$id)
            ->first();
        if(!$db)
        {
            $town->delete();
            return response(['Message' => 'Succesfully Deleted'],202);
        }
        
        return response(['Message' => 'With Children'],409);
    }
}
