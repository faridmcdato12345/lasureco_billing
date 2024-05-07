<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FundRequest;
use App\Models\Fund;
use Illuminate\Http\Request;

class FundController extends Controller
{
    public function index()
    {
        return Fund::paginate(10);
    }
    public function store(FundRequest $request)
    {
        $newFund = new Fund();
        $newFund->name = $request->name;
        $newFund->save();

        return response(['info'=>'Succesfuly Created'],201);
    }
    public function update(FundRequest $request,$id)
    {
        $newFund = Fund::find($id);
        if(!$newFund){
            return response(['info'=>'No Record Found for the ID Given'],422);
        }

        $newFund->name = $request->name;
        $newFund->save();

        return response(['info'=>'Succesfuly Updated'],200);
    }


}
