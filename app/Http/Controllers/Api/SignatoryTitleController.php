<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignatoryTitleRequest;
use App\Models\Signatory;
use App\Models\SignatoryTitle;
use Illuminate\Http\Request;

class SignatoryTitleController extends Controller
{
    public function index()
    {
        return SignatoryTitle::get();
    }
    public function show($id)
    {
        return SignatoryTitle::where('id',$id)->get();
    }
    public function store(SignatoryTitleRequest $request)
    {
        SignatoryTitle::create($request->validated());
        
        return response(['info'=>'Succesfully Created'],201);
    }
    public function update(SignatoryTitleRequest $request, $id)
    {
        // dd($id);
        $update = SignatoryTitle::find($id);
        $update->name = $request->name;
        $update->update();
        
        return response(['info'=>'Succesfully updated'],204);
    }
    
}
