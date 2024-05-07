<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Models\AccountingCode;

class AccountingCodeService extends Controller{
    // Storing New Main Accounting Code
    public function forMainCode($name = null,$code){
        $newCode = new AccountingCode();
        $newCode->name = $name;
        $newCode->code = $code;
        $newCode->parent_code = NULL;
        $newCode->is_last = 1;
        // dd($newCode->id);

        if($newCode->save()){
            $setNewACode = AccountingCode::find($newCode->id);
            $setNewACode->main_code= $newCode->id;
            $setNewACode->a_code = collect($newCode->id);
            $setNewACode->g_code = collect($newCode->code);
            $setNewACode->save();

            // $setNewGCode = AccountingCode::find($newCode->id);
            // $setNewGCode->g_code = collect($newCode->code);
            // $setNewGCode->save();

            // $setNewGCode = AccountingCode::find($newCode->id);
            $value = 'Successfully Created';
        }else{
            $value = 'Error! Failed To Create New Main Accounting Code';
        }
        // dd($value);
        return $value;
    }

    // Storing New Sub Accounting Code
    public function forSubCode($id,$name,$code,$parent,$main,$aCode){
        // dd($gCode . ' ' . $aCode);
        $newCode = new AccountingCode();
        $newCode->name = $name;
        $newCode->code = $code;
        $newCode->parent_code = $parent;
        $newCode->main_code = $id;
        $newCode->is_last = 1;
        // If store method is with Parent then Update Parent data is_last to 0
        $oldCode = AccountingCode::find($id);
        $oldCode->is_last = 0;
        
        if(!$oldCode->save()){
            $value = 'Parent Code Failed to Update';
        }
        $oldCode->update();
        if($newCode->save()){
            // Decode Json and Add Id of newly created code
            $a_code = json_decode($aCode, TRUE);
            array_push($a_code,intval($newCode->id));
            // $g_code = json_decode($gCode, TRUE);
            // array_push($g_code,$newCode->code);
            $setNewACode = AccountingCode::find($newCode->id);
            $setNewACode->a_code = $a_code;
            // $setNewACode->g_code = $g_code;
            $setNewACode->save();

            $value = 'Successfully Created';
        }else{
            $value = 'Error! Failed To Create New Sub Accounting Code';
        }
        // dd($value);
        return $value;
    }

    // Checking Accounting Code
    public function checkingCode($id,$parent,$code,$mainCode){
        $value = 'TRUE';
        //Check wether New Code with duplicate code on same parent code
        $forNewCodeChecking = AccountingCode::where('parent_code',$parent)->first();
        if($forNewCodeChecking){
            $checkingTwice = AccountingCode::where('code',$code)->first();
            if($checkingTwice){
                $value = 'Error Cannot Add Same Code On The Same Parent(Accounting Code)';
            }
        }
        // Check if Main Code is Valid
        $checkMainCode = AccountingCode::findOrFail($id);
        // Check if ID Exist in the Database
        // if(!$checkMainCode){
        //     $value = 'Error! ID doesnt Exist';
        // }
        if($checkMainCode['main_code'] != $mainCode)
        {
            $value = 'Error! Invalid Main Code';
        }
        
        // Check If Request Parent Code Exist in the database
        $forParentCodeChecking = AccountingCode::where('id',$parent)->first();
        if(!$forParentCodeChecking){
            $value = 'Parent Code Doesnt Exist';
        }

        //Check if parent code exist in the request
        if($parent == NULL){
            $value = 'Missing Parent Code';
        }

        return $value;
    }


}