<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFieldFindingRequest;
use Illuminate\Http\Request;
use App\Http\Resources\FieldFindingResource;
use App\Models\FieldFinding;
use App\Models\MeterReg;
use Illuminate\Support\Facades\DB;

class FieldFindingController extends Controller
{
    public function index()
    {
        return FieldFIndingResource::collection(
            DB::table('field_finding')
            ->whereNull('deleted_at')
            ->get());
    }

    public function threeMonthsAve($consumer_id)
    {
        $threeMonAve = 0;
        $consCollection = MeterReg::where('meter_reg.cm_id', $consumer_id)
            ->where('meter_reg.mr_status',0)
            ->whereNull('deleted_at')
            ->orderBy('meter_reg.mr_date_year_month','desc')
            ->paginate(3);
        
        foreach($consCollection as $a){
            $threeMonAve += $a->mr_kwh_used;
        }

        $threeMonAve /= 3;
        return response(['TMAve'=>round($threeMonAve,2)],200);
    }

    public function search($request)
    {
        return FieldFindingResource::collection(
            DB::table('field_finding')
            ->where('ff_desc','LIKE', $request.'%')
            ->whereNull('deleted_at')
            ->paginate(5));
    }
    public function store(StoreFieldFindingRequest $request)
    {
        $data = FieldFinding::create($request->all());
        return response($data,201);
    }
    public function update(StoreFieldFindingRequest $request, $id)
    {
        $data = FieldFinding::findOrFail($id);
        $data->update($request->all());
        return response(['message'=>'Succesfully Updated'],200);
    }
    public function destroy($id)
    {
        $ff = FieldFinding::findOrFail($id);
        $ff->delete();

        return response(['Message' => 'Succesfully Deleted'],202);
    }
}
