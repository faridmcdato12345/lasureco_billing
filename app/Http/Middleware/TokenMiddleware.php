<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class TokenMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');

        // Add your token verification logic here
        $accessToken = $token;
        if(isset($accessToke)){
            $parts = explode('|', $accessToken);

            $tokenableId = $parts[0];
            $plainTextToken = $parts[1];
            $tots = Hash('sha256',$plainTextToken);
            
            $query = DB::table('personal_access_tokens')
                ->select('token')
                ->where('id', $tokenableId)
                ->first();
            if ($tots !== $query->token) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }
        // else{
        //     Session::put('last_activity', time());
        // }
        // $user = $request->user();

        // if ($user) {
        //     $lastActivity = Session::get('last_activity');

        //     // Check if the user is inactive for 5 minutes (300 seconds)
        //     if ($lastActivity && time() - $lastActivity > 300) {
        //         $user->tokens()->delete();
        //         Session::flush();
        //         return response()->json(['message' => 'Session expired due to inactivity.'], 401);
        //     }
        // }
        return $next($request);
    }
}
