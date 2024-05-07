<?php

namespace App\Http\Controllers\api;

use datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubstationCodeRequest;
use App\Http\Resources\SubstationCodeResource;
use App\Models\SubstationCode;

class SubstationCodeController extends Controller
{
    public function index()
    {
        return SubstationCodeResource::collection(
            DB::table('substation_code')
            ->whereNull('deleted_at')
            ->orderBy('sc_desc', 'asc')
            ->paginate(5));
    }
    // public function getSubstationDataTable(){
    //     $query = DB::table('substation_code')->select('sc_id','sc_desc','sc_address','sc_rating','sc_voltprim','sc_voltsecond');
    //     return datatables($query)->make(true);
    // }
    public function store(StoreSubstationCodeRequest $request)
    {
        $data = SubstationCode::create($request->all());
        return response($data,201);
    }
    public function search($request)
    {
        return SubstationCodeResource::collection(
            DB::table('substation_code')
            ->where('sc_desc','LIKE', $request.'%')
            ->whereNull('deleted_at')
            ->orderBy('sc_desc', 'asc')
            ->paginate(5));
    }
    public function update(StoreSubstationCodeRequest $request, $id)
    {
        $data = SubstationCode::findOrFail($id);
        $data->update($request->all());
        return response(['message'=>'Succesfully Updated'],200);
    }
    public function destroy($id)
    {
        $substation = SubstationCode::findOrFail($id);

        if(!$substation->poleMasters()->first() || !$substation->transformers()->first() || !$substation->feederCodes()->first())
        {
            $substation->delete();
            return response(['Message' => 'Succesfully Deleted'],202);
        }
        
        return response(['Message' => 'With Children'],409);
    }
}
