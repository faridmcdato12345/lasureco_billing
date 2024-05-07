<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\FeederCodeResource;
use App\Models\FeederCode;
use App\Http\Requests\StoreFeederCodeRequest;
use Illuminate\Support\Facades\DB;

class FeederCodeController extends Controller
{
    public function index()
    {
        return FeederCodeResource::collection(
            DB::table('feeder_code')
            ->whereNull('deleted_at')
            ->orderBy('fc_desc', 'asc')
            ->paginate(10));
    }
    public function getFeeder($substation)
    {
        return FeederCodeResource::collection(
            DB::table('feeder_code')
            ->whereNull('deleted_at')
            ->where('sc_id', $substation)
            ->orderBy('fc_desc', 'asc')
            ->get());
    }
    public function store(StoreFeederCodeRequest $request)
    {
        $data = FeederCode::create($request->all());
        return response($data,201);
    }

    public function search ($request){
        return FeederCodeResource::collection(
            DB::table('feeder_code')
            ->where('fc_desc','LIKE',$request.'%')
            ->whereNull('deleted_at')
            ->orderBy('fc_desc', 'asc')
            ->paginate(5));
    }
    public function searchFeederBySubID($id,$req)
    {
        return DB::table('feeder_code AS fc')
            ->join('substation_code AS sc','fc.sc_id','=','sc.sc_id')
            ->select('sc.sc_id','fc.fc_id','fc.fc_code','fc.fc_desc')
            ->where('fc.fc_desc','LIKE', $req.'%')
            ->where('sc.sc_id',$id)
            ->whereNull('fc.deleted_at')
            ->orderBy('fc.fc_desc', 'asc')
            ->get();
    }
    public function update(StoreFeederCodeRequest $request, $id)
    {
        $data = FeederCode::findOrFail($id);
        $data->update($request->all());
        return response(['message'=>'Succesfully Updated'],200);
    }
    public function destroy($id)
    {
        $feeder = FeederCode::findOrFail($id);

        if(!$feeder->poleMasters()->first() || !$feeder->transformers()->first())
        {
            $feeder->delete();
            return response(['Message' => 'Succesfully Deleted'],202);
        }
        
        return response(['Message' => 'With Children'],409);
    }
}
