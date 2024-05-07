<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConsumerTypeRequest;
use Illuminate\Http\Request;
use App\Http\Resources\ConsumerTypeResource;
use App\Models\ConsumerType;
use Illuminate\Support\Facades\DB;

class ConsumerTypeController extends Controller
{
    public function index()
    {
        return ConsumerTypeResource::collection(
            DB::table('cons_type')
            ->whereNull('deleted_at')
            ->orderBy('ct_code', 'asc')
            ->paginate(10));
    }
    public function store(StoreConsumerTypeRequest $request)
    {
        $data = ConsumerType::create($request->all());
        return response($data,201);
    }
    public function update(StoreConsumerTypeRequest $request, $id)
    {
        $data = ConsumerType::findOrFail($id);
        $data->update($request->all());
        return response()->json($data,200);
    }
    public function destroy($id)
    {
        $cons_type = ConsumerType::findOrFail($id);
        
        if(!$cons_type->billrates()->first() || !$cons_type->consMasters()->first())
        {
            $cons_type->delete();
            return response(['Message' => 'Succesfully Deleted'],202);
        }

        return response(['Message' => 'With Children'],409);
    }
    public function getDatatable(){
        $query = ConsumerType::select('ct_id','ct_code','ct_desc');    
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<button class="btn btn-primary btn-sm ctSelect" value="'.$row->ct_id.'">Select</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function select(ConsumerType $consumerType){
        return response()->json($consumerType,200);
    }
    public function consumerTypeWithoutPaginate(){
        return ConsumerTypeResource::collection(
            DB::table('cons_type')
            ->whereNull('deleted_at')
            ->orderBy('ct_code', 'asc')
            ->get());
    }
    public function getDatatableIndex(){
        $query = ConsumerType::select('ct_id','ct_code','ct_desc');    
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<button class="btn btn-primary btn-sm ctSelect" value="'.$row->ct_id.'">Edit</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
