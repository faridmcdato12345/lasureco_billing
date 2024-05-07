<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMeterCondition;
use Illuminate\Http\Request;
use App\Http\Resources\MeterConditionResource;
use App\Models\MeterCondition;
use Illuminate\Support\Facades\DB;

class MeterConditionController extends Controller
{
    public function index()
    {
        return MeterConditionResource::collection(
            DB::table('meter_cond')
            ->whereNull('deleted_at')
            ->orderBy('mc_desc', 'asc')
            -> paginate(10));
    }
    public function store(StoreMeterCondition $request)
    {
        $data = MeterCondition::create($request->all());
        return response($data,201);
    }
    public function update(StoreMeterCondition $request, $id)
    {
        $data = MeterCondition::findOrFail($id);
        $data->update($request->all());
        return response(['message'=>'Succesfully Updated'],200);
    }
    public function destroy($id)
    {
        $meterCond = MeterCondition::findOrFail($id);

        if(!$meterCond->meterMasters()->first())
        {
            $meterCond->delete();
            return response(['Message' => 'Succesfully Deleted'],202);
        }
        
        return response(['Message' => 'With Children'],409);
    }
}
