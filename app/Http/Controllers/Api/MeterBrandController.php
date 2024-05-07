<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMeterBrandRequest;
use Illuminate\Http\Request;
use App\Http\Resources\MeterBrandResource;
use App\Models\MeterBrand;
use Illuminate\Support\Facades\DB;

class MeterBrandController extends Controller
{
    public function index()
    {
        return MeterBrandResource::collection(
            DB::table('meter_brand')
            ->whereNull('deleted_at')
            ->orderBy('mb_code', 'asc')
            ->paginate(10));
    }
    public function store(StoreMeterBrandRequest $request)
    {
        $data = MeterBrand::create($request->all());
        return response($data,201);
    }
    public function search($request)
    {
        return MeterBrandResource::collection(
            DB::table('meter_brand')
            ->where('mb_code','LIKE', $request.'%')
            ->whereNull('deleted_at')
            ->orderBy('mb_code', 'asc')
            ->paginate(5));
    }
    public function update(StoreMeterBrandRequest $request, $id)
    {
        $data = MeterBrand::findOrFail($id);
        $data->update($request->all());
        return response(['message'=>'Succesfully Updated'],200);
    }
    public function destroy($id)
    {
        $meterBand = MeterBrand::findOrFail($id);

        if(!$meterBand->meterMasters()->first())
        {
            $meterBand->delete();
            return response(['Message' => 'Succesfully Deleted'],202);
        }
        
        return response(['Message' => 'With Children'],409);
    }

    public function getMeterBrand(){
        $query = MeterBrand::select('mb_id','mb_code','mb_name');
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<button class="btn btn-primary btn-sm meterBrandSelect" value="'.$row->mb_id.'">Select</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function select(MeterBrand $meterBrand){
        return response()->json($meterBrand,200);
    }
}
