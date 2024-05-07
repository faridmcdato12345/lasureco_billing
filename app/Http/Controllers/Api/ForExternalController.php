<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\checkLoginRequest;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForExternalController extends Controller
{
    public function checkUser(checkLoginRequest $request)
    {
        // $pass = Hash::make($request->password);
        $user = User::where('username', $request->username)->first();
        
        if (!Hash::check($request->password,$user->password)) {
            return response(['info'=>'Incorrect Username or Password'],401);
        }
        
        return response([
            'info' => 'OK',
            'user_full_name' => $user->user_full_name,
        ], 200);

    }
}