<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StoreRoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function store(Request $request){
        $data = (new StoreRoleService())->storeRole($request->role);
        if(!is_null($data)){
            foreach ($request->permission_name as $key => $p) {
                $data->givePermissionTo($p);
            }
        }
        return response(['data' => $data],201);
    }
    public function update(Role $role, Request $request){
        foreach ($request->permissions as $key => $data) {
            if(!$role->hasPermissionTo($data)){
                $role->givePermissionTo($data);
            }
        }
        return response(['data'=>$request->all()],200);
    }
}
