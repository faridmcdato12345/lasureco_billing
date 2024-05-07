<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HrAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        if($token == null){
            return response()->json(['info'=>'Authorization Required']);
        }
        
        if(strpos($token, '|') !== false){
            $parts = explode('|', $token);
            $tokenId = $parts[0];
        }else{
            // 0 -Token Invalid Format
            return response()->json(['info'=>'Unauthorized Access - (0)']);
        }
        
       
        $dateTime = Carbon::parse($parts[1]);
        $timeout = Carbon::parse($dateTime)->addHours(1);
        if(!isset($request->id)){
            return response()->json(['info'=>'Something Went Wrong']);
        }else{
            $userToken = DB::connection('hr_mysql')->table('employees')
            ->select('token')
            ->where('emp_id',$request->id)
            ->first();
            $explodeToken = explode('|', $userToken->token);
            $fToken = $explodeToken[0];

            // 1 -Token data in db not found
            if(!$userToken->token){
                return response()->json(['info'=>'Unauthorized Access - (1)']); 
            }
            // 2 - Token data in db and request not equal
            if($fToken !== $tokenId){
                return response()->json(['info'=>'Unauthorized Access - (2)']);
            }
            
        }
        if(Carbon::now()->gt(carbon::parse($timeout))){
            return response()->json(['info'=>'Session Timeout, Please Login Again']);
        }

        
        
        return $next($request);
    }
}
