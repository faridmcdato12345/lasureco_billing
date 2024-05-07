<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePoleMasterRequest;
use App\Http\Resources\PoleMasterResource;
use App\Models\PoleMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoleMasterController extends Controller
{
    public function index()
    {
        return PoleMasterResource::collection(
            DB::table('pole_master')
            ->whereNull('deleted_at')
            ->orderBy('pm_description','asc')
            ->paginate(5));
    }
    public function store(StorePoleMasterRequest $request)
    {
        $data = PoleMaster::create($request->all());
        return response($data,201);
    }
    public function update(StorePoleMasterRequest $request, $id)
    {
        $data = PoleMaster::findOrFail($id);
        $data->update($request->all());
        return response(['message'=>'Succesfully Updated'],200);
    }
    public function destroy($id)
    {
        $poleMaster = PoleMaster::findOrFail($id);
        $poleMaster->delete();

        return response(['Message' => 'Succesfully Deleted'],202);
    }
    public function getPoleDetails($id)
    {
        return 
            DB::table('pole_master')
            ->select('pm_description','pm_location','pm_length','pm_wire_size','pm_wire_type')
            ->whereNull('deleted_at')
            ->where('pm_id',$id)
            ->orderBy('pm_description','asc')
            ->get();
    }
}
