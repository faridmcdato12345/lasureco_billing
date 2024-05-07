<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransformerRequest;
use App\Http\Resources\TransformerResource;
use App\Models\Transformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransformerController extends Controller
{
    public function index()
    {
        return TransformerResource::collection(
            DB::table('transformer')
            ->whereNull('deleted_at')
            ->orderBy('tcf_tsf_desc', 'asc')
            ->paginate(10));
    }
    public function store(StoreTransformerRequest $request)
    {
        $data = Transformer::create($request->all());
        return response($data,201);
    }
    public function search($request)
    {
        return TransformerResource::collection(
            DB::table('transformer')
            ->where('tcf_tsf_desc','LIKE', $request.'%')
            ->whereNull('deleted_at')
            ->paginate(5));
    }
    public function update(StoreTransformerRequest $request, $id)
    {
        $data = Transformer::findOrFail($id);
        $data->update($request->all());
        return response(['message'=>'Succesfully Updated'],200);
    }
    public function destroy($id)
    {
        $transformer = Transformer::findOrFail($id);
        if(!$transformer->meterMasters()->first())
        {
            $transformer->delete();
            return response(['Message' => 'Succesfully Deleted'],202);
        }
        
        return response(['Message' => 'With Children'],409);
    }
}
