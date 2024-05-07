<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Support\Facades\Auth;
use PDF;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function createUser(UserRegisterRequest $request){
        $user = User::create($request->all());
        $token = $user->createToken($request->input('username').date('Y-m-d'))->plainTextToken;
        $response = [
            'user'=>$user,
            'token'=>$token
        ];
        $user->assignRole($request->input('role'));
        return response()->json($response,201);
    }
    public function logout(){
        Auth::user()->tokens()->delete();
        return response(['message'=>'User logout']);
    }
    public function register(UserRegisterRequest $request){
        $user = User::create($request->all());
        $token = $user->createToken($request->input('username').date('Y-m-d'))->plainTextToken;
        $response = [
            'user'=>$user,
            'token'=>$token
        ];
        $user->assignRole($request->input('role'));
        return response()->json($response,201);
    }
    public function loginUser(UserLoginRequest $request){
        $user = User::where('username',$request->user_name)->first();
        if(!$user){
            return response(['message'=>'Invalid username'],401);
        }
        if(!Hash::check($request->user_pword, $user->password))
        {
            return response(['message'=>'Invalid password'],401);
        }
        $token = $user->createToken($request->user_name.date('Y-m-d'))->plainTextToken;
        $response = [
            'user'=>$user,
            'token'=>$token
        ];
        return response()->json($response,200);
    }
}
