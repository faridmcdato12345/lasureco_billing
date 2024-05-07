<?php

namespace App\Services;

use App\Models\Sales;

class UploadDailyCollection{

    public function uploadCollection($from,$to,$user){
        $collections = Sales::where('teller_user_id',$user)
                        ->whereBetween('s_bill_date',[$from,$to])
                        ->get();
        if($collections->isEmpty()){
            return false;
        }
        return $collections;
    }
}