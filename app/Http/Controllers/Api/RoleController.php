<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use Facade\FlareClient\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Role::select('id','name');
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('permissions', function($row){
                $x = '';
                $s = DB::table('role_has_permissions')->where('role_id',$row->id)->get();
                foreach($s as $d){
                    $z = DB::table('permissions')->select('id','name')->where('id',$d->permission_id)->first();
                    $x .= '<label class="badge badge-success">'.$z->name.'</label>';
                }
                return $x;
            })
            ->addColumn('action', function($row){
                $btn = '<button class="role-view btn btn-info btn-sm action-bttn" value="'.$row->id.'" id="role-view">View</button>';
                $btn = $btn.'<button class="role-edit btn btn-primary btn-sm action-bttn" value="'.$row->id.'" id="role-edit">Edit</button>';
                $btn = $btn.'<button class="role-delete btn btn-danger btn-sm delete-role-bttn" value="'.$row->id.'">Delete</button>';
                return $btn;
            })
            ->rawColumns(['action','permissions'])
            ->make(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $permissions = Permission::select('id','name')->get();
        $p = $role->getAllPermissions();
        return response(['data'=>$p,'role'=>$role,'permissions'=>$permissions],200);
    }

    public function addUserRole(){
        $query = Role::select('id','name');
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('permissions', function($row){
                $x = '';
                $s = DB::table('role_has_permissions')->where('role_id',$row->id)->get();
                foreach($s as $d){
                    $z = DB::table('permissions')->select('id','name')->where('id',$d->permission_id)->first();
                    $x .= '<label class="badge badge-success">'.$z->name.'</label>';
                }
                return $x;
            })
            ->addColumn('action', function($row){
                $btn = '<button class="add-user-role btn btn-primary btn-sm action-bttn" value="'.$row->name.'" id="addUserRole">Add</button>';
                return $btn;
            })
            ->rawColumns(['action','permissions'])
            ->make(true);
    }
}
