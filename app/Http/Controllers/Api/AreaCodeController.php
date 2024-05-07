<?php

namespace App\Http\Controllers\Api;


use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\AreaCodeResource;
use App\Http\Requests\StoreAreaCodeRequest;
use App\Models\AreaCode;
use App\Models\TownCode;
use DataTables;
use Illuminate\Http\Request;

class AreaCodeController extends Controller
{
    public function index()
    {
        return AreaCodeResource::collection(
            DB::table('area_code')
            ->whereNull('deleted_at')
            ->orderBy('ac_name', 'asc')
            ->paginate(5));
    }
    public function index1()
    {
        return AreaCodeResource::collection(
            DB::table('area_code')
            ->whereNull('deleted_at')
            ->orderBy('ac_name', 'asc')
            ->get());
    }
    public function show($id)
    {
        return AreaCodeResource::collection(
            DB::table('area_code')
            ->where('ac_id',$id)
            ->whereNull('deleted_at')
            ->get());
    }
    public function getAreaCode(){
        $query = DB::table('area_code')->select('ac_id','ac_name')->get();
        return datatables()
        ->of($query)
        ->make(true);
    }
    public function store(StoreAreaCodeRequest $request)
    {
        $data = AreaCode::create($request->all());
        return response($data,201);
    }
    public function update(StoreAreaCodeRequest $request, $id)
    {
        $data = AreaCode::findOrFail($id);
        $data->update($request->all());
        return response(['message'=>'Succesfully Updated'],200);
    }
    public function destroy($id)
    {
        $area = AreaCode::findOrFail($id);
        $db = DB::table('town_code')
            ->select('ac_id')
            ->where('ac_id',$id)
            ->whereNull('deleted_at')
            ->first();
        if(!$db)
        {
            $area->delete();
            return response(['Message' => 'Succesfully Deleted'],202);
        }
        
        return response(['Message' => 'With Children'],409);
    }
}
