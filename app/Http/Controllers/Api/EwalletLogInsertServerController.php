<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\EWALLET_LOG;

class EwalletLogInsertServerController extends Controller
{
    public function store(Request $request){
        $insert = EWALLET_LOG::create($request->all());
        return response()->json($insert,201);
    }
}
