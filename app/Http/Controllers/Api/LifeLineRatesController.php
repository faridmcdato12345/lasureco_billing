<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLifeLineRatesRequest;
use Illuminate\Http\Request;
use App\Http\Resources\LifeLineRatesResource;
use App\Models\LifeLineRate;
use App\Models\RouteCode;
use Illuminate\Support\Facades\DB;

class LifeLineRatesController extends Controller
{
    public function index()
    {
        return LifeLineRatesResource::collection(
            DB::table('lifeline_rates')
            ->whereNull('deleted_at')
            ->orderBy('ll_min_kwh', 'asc')
            ->paginate(10));
    }

    public function getLifeline($kwhConsumed)
    {
        return DB::table('lifeline_rates As ll')
            ->where('ll.ll_min_kwh', '<=',  $kwhConsumed)
            ->where('ll.ll_max_kwh', '>=',  $kwhConsumed)
            ->where('active', '=', 1)
            ->whereNull('ll.deleted_at')
            ->first();
    }

    public function store(StoreLifeLineRatesRequest $request)
    {
        $data = LifeLineRate::create($request->all());
        return response()->json($data,201);
    }
    public function update(StoreLifeLineRatesRequest $request, $id)
    {
        $data = LifeLineRate::findOrFail($id);
        $data->update($request->all());
        return response(['message'=>'Succesfully Updated'],200);
    }
    public function destroy($id)
    {
        $llrate = LifeLineRate::findOrFail($id);
        $llrate->delete();

        return response(['Message' => 'Succesfully Deleted'],202);
    }
    public function getDataTable(){
        $query = LifeLineRate::select('ll_id','ll_min_kwh','ll_max_kwh','ll_discount');    
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<button class="edit-lifeline btn btn-primary btn-sm" value="'.$row->ll_id.'">Edit</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function edit(LifeLineRate $lifeLine){
        return response()->json($lifeLine,200);
    }
}

