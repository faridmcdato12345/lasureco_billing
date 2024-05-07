<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMeterMasterRequest;
use App\Http\Resources\MeterMasterResource;
use App\Models\MeterMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeterMasterController extends Controller
{
    public function index()
    {
        return MeterMasterResource::collection(
            DB::table('meter_master')
            ->whereNull('deleted_at')
            ->paginate(10));
    }
    public function store(StoreMeterMasterRequest $request)
    {
        $data = MeterMaster::create($request->all());
        return response($data,201);
    }
    public function search($request)
    {
        return MeterMasterResource::collection(
            DB::table('meter_master')
            ->where('mm_serial_no','LIKE', $request.'%')
            ->whereNull('deleted_at')
            ->paginate(5));
    }
    public function update(StoreMeterMasterRequest $request, $id)
    {
        $data = MeterMaster::findOrFail($id);
        $data->update($request->all());
        return response(['message'=>'Succesfully Updated'],200);
    }
    public function destroy($id)
    {
        $meterMaster = MeterMaster::findOrFail($id);

        if(!$meterMaster->consumer()->first())
        {
            $meterMaster->delete();
            return response(['Message' => 'Succesfully Deleted'],202);
        }
        
        return response(['Message' => 'With Children'],409);
    }
    public function getDatatable(){
        $query = MeterMaster::select('mm_id','mm_serial_no');    
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<button class="btn btn-primary btn-sm meterMasterSelect" value="'.$row->mm_id.'">Select</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function select(MeterMaster $meterMaster){
        return response()->json($meterMaster,200);
    }
}
