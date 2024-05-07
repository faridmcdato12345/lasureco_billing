<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignatoryRequest;
use App\Models\Signatory;
use App\Http\Resources\SignatoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SignatoryController extends Controller
{
    public function index()
    {
        
        // $signatory = DB::table('signatory as s')->join('signatory_title as st','s.signatory_title_id','=','st.id')->where('s.status', 1)->get();
        $signatory = Signatory::with('signatoryTitle')->get();
        $pluck = $signatory->pluck('signatory_title_id');
        $query = DB::table('signatory_title as st')
        ->whereNotIn('st.id',$pluck)
        ->get();
        $query2 = DB::table('signatory as s')
        ->join('signatory_title as st', 'st.id', '=','s.signatory_title_id')
        ->where('s.status',0)
        ->get();
        return response([$signatory,$query,$query2]);
    }
    public function store(SignatoryRequest $request)
    {
        // dd($request->validated);
        Signatory::create($request->validated());
        
        return response(['info'=>'Succesfully Created'],201);
    }
    public function update(SignatoryRequest $request,$id)
    {
        
        $update = Signatory::find($id);
        $update->name = $request->name;
        $update->signatory_title_id = $request->signatory_title_id;
        $update->save();
         
        return response(['info'=>'Succesfully updated'],201);
    }

    // function update2($name,$signatory_title_id,$id)
    // {
    //     $update = Signatory::find($id);
    //     $update->name = $name;
    //     $update->signatory_title_id = $signatory_title_id;
    //     $update->save();
        
    //     return response(['info'=>'Succesfully updated'],201);
    // }

    public function update1(Request $request,$id)
    {
        
        // $this->update2($request->name,$request->signatory_title_id,$id);
        $signatory_title_id = DB::table('signatory')
        ->select('signatory_title_id')
        ->where('signatory_title_id',$request->signatory_title_id)
        ->where('status', 1)
        ->first();
        if($signatory_title_id){
            $update = Signatory::find($id);
            $update->name = $request->name;
            $update->save();
            return response(['info'=>'successfull updated'],201); 
        }
        $update = Signatory::find($id);
        $update->name = $request->name;
        $update->signatory_title_id = $request->signatory_title_id;
        $update->save();
        
        return response(['info'=>'Succesfully updated'],201);
    }
    // public function updateStatus(Request $request,$id)
    public function updateStatus(Signatory $signatory, Request $request)
    {
        if($request->status < 0 || $request->status > 1){
            return response(['info'=>'Invalid Status'],422);
        }
        $signatory->update([
            'status'=> $request->status,
        ]);

        return response(['info'=>'Succesfully updated'],200);
    }
}
