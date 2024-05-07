<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RoleDeleteController extends Controller
{
    public function delete(Request $request){
        $role = DB::table('role_has_permissions')->where('role_id',$request->id)->delete();
        if($role){
            $roleMain = DB::table('roles')->where('id',$request->id)->delete();
            if($roleMain){
                return response()->json($roleMain,200);
            }
        }
    }
}
