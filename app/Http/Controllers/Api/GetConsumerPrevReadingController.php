<?php

namespace App\Http\Controllers\Api;

use App\Models\Consumer;
use App\Models\MeterReg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class GetConsumerPrevReadingController extends Controller
{
    public function getPrevReading(Request $request){
        $cons = DB::table('meter_reg')->select('mr_id','cm_id','mr_prev_reading')->where('cm_id',$request->cons_id)->latest('mr_id')->first();
        return response()->json($cons,200);
    }
}
