<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountingCodeRequest;
use App\Http\Resources\AccountingCodeResource;
use App\Models\AccountingCode;
use App\Services\AccountingCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingCodeController extends Controller
{
    
    public function index()
    {
        $query = DB::table("accounting_codes")
        ->select("*")
        ->get();
        
        $map = $query->map(function($item){
            $gg = '';
            $gcode = $item->a_code;
            $gcodestring = json_decode($gcode);
            // dd($gcodestring);
            $gcodelength = count($gcodestring);
            for($i = 0; $i < $gcodelength; $i++){
                $query2 = DB::table("accounting_codes")
                ->select('code')
                ->where('id', $gcodestring[$i])
                ->pluck('code');

                $gg .= $query2 . "-";
            }
            $gg = rtrim($gg, '-');
            $gg = str_replace(['[', ']', '"'], '', $gg);
            return [
                'acc_id'=>$item->id,
                'acc_code'=> $item->code,
                'acc_name'=> $item->name,
                'a_code' => $item->a_code,
                'g_code' => $gg,
                'acc_parent_code'=> $item->parent_code,
                'acc_main_code'=> $item->main_code,
                'acc_is_last'=>$item->is_last
                // 'acc_created_at'=>
                // 'acc_updated_at'=>
                // 'acc_deleted_at'=>
            ];
        });
        
        return response(['data'=>$map],200);
    }
    public function store(AccountingCodeRequest $request)
    {
        
        if(!isset($request->id) || $request->id == NULL){
            // Restrict Storing of Accounting Code from a Duplicate New Main Code
            $forNewCodeChecking = AccountingCode::where('code',$request->code)->whereNull('parent_code')->first();
            if($forNewCodeChecking){
                return response(['info'=>'Duplicate New Main Accounting Code'],422);
            }
            $data = (new AccountingCodeService())->forMainCode($request->name,$request->code);
            
        }else{
            // If With Parent, Main Code should also exist in the request
            if(!isset($request->main_code) || $request->main_code == NULL){
                return response(['info'=>'Missing Main Code'],422);
            }
            // If With Parent, array_code should also exist in the request
            if(!isset($request->a_code) || $request->a_code == NULL){
                return response(['info'=>'Missing Array Code'],422);
            }

            $data = (new AccountingCodeService())->checkingCode($request->id,$request->parent_code,$request->code,$request->main_code);
            
            if($data == 'TRUE'){
                $data = (new AccountingCodeService())->forSubCode($request->id,$request->name,$request->code,$request->parent_code,$request->main_code,$request->a_code);
            }
  
        }

        return response(['info'=>$data],($data =='Successfully Created') ? 201 : 422);
    }

    public function show($id)
    {
        return AccountingCodeResource::collection(AccountingCode::where('id',$id)->get());
    }
    public function update(AccountingCodeRequest $request, $id)
    {
        $updateCode = AccountingCode::find($id);
        $updateCode->name = $request->name;
        
        //Check Code if Already Exist (Duplicate Code)
        $forNewCodeChecking = AccountingCode::where('parent_code',$request->id)->where('code',$request->code)->first();
        if($forNewCodeChecking){
            return response(['info'=>'Error! Duplicate Code For The Same Parent'],422);
        }

        $updateCode->code = $request->code;
        if(!$updateCode->save()){
            return response(['info'=>'something went wrong'],422);
        }

        $updateCode->save();

        return response(['info'=>'Succesfully Updated'],204);

    }

    //update code individually

    public function updateCode(Request $request, $id)
    {
        DB::table('accounting_codes as ac')
        ->where('ac.id',$id)
        ->update([
            'ac.code' => $request->updatecode,
            'ac.name' => $request->updateName,
        ]);

        return response(['info'=>'Succesfully Updated'],200);
        // dd($getquery);
        // if($getquery->parent_code == null){
        //     $getquery->g_code;
        // }
        // $data = $getquery->g_code;

        // $code = json_decode($data);

        // $lastIndex = count($code)-1;

        // $code[$lastIndex] = $request->updatecode;

        // $updateT = json_encode($code);

        // $updatequery = DB::table('accounting_codes')
        // ->where('id', $id)
        // ->update([
        //     'g_code' => $updateT,
        //     'name' => $request->updateName,
        //     'code' => $request->updatecode
        // ]);
        
        // if($updatequery){
        //     return response([
        //         'data' => 'successfully updated'
        //     ],200);
        // }else{
        //     return response([
        //         'data' => 'cant update'
        //     ],422);
        // }
        
    }
    //end update code
    
    public function destroy($id)
    {
        //check if the id is a parent code (deny action)
        
        $check = AccountingCode::where('parent_code',$id)->first();
        if($check){
            return response(['info'=>'Cannot Delete Parent Code'],403);
        }

        // $aCode = AccountingCode::find($id);
        // $aCode->delete();
        $query = DB::table('accounting_codes')
        ->where('id',$id)
        ->delete();

        return response(['info'=>'deleted'],204);
    }

    public function getAll($id)
    {
        $view = AccountingCode::where('parent_code',$id)->get();
        
        return response(['info'=>$view],200);
    }
}
