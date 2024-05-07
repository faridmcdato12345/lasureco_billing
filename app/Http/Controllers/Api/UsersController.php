<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserChangePassUpdateRequest;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = User::select('user_id','username','user_full_name','user_status');    
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<button class="change-pass btn btn-success btn-sm action-bttn" value="'.$row->user_id.'" id="role-view">Change password</button>';
                $btn = $btn . '<button class="add-role btn btn-warning btn-sm action-bttn" value="'.$row->user_id.'" id="role-add" data-toggle="modal" data-target="#addRole">Add Role</button>';
                $btn = $btn . '<button class="role-edit btn btn-primary btn-sm action-bttn" value="'.$row->user_id.'" id="role-edit" data-toggle="modal" data-target="#editRole">Edit</button>';
                return $btn;
            })
            ->addColumn('status', function($row){
                $status = $row->user_status;
                if(!$status){
                    $btn = '<div class="custom-control custom-switch">';
                    $btn = $btn . '<input type="checkbox" class="custom-control-input ad_switch" id="'.$row->user_id.'">';
                    $btn = $btn . '<label class="custom-control-label status_label" for="'.$row->user_id.'">Inactive</label>';
                    $btn = $btn . '<input type="hidden" id="status_hidden" value="0"></div>';
                }
                else{
                    $btn = '<div class="custom-control custom-switch">';
                    $btn = $btn . '<input type="checkbox" class="custom-control-input ad_switch" id="'.$row->user_id.'" checked="">';
                    $btn = $btn . '<label class="custom-control-label status_label" for="'.$row->user_id.'">Active</label>';
                    $btn = $btn . '<input type="hidden" id="status_hidden" value="1"></div>';
                }
                return $btn;
            })
            ->addColumn('roles', function($row){
                $x = '';
                $s = DB::table('model_has_roles')->where('model_id',$row->user_id)->get();
                foreach($s as $d){
                    $z = DB::table('roles')->select('id','name')->where('id',$d->role_id)->first();
                    $x .= '<div id="role-container" class="'.$row->user_id.'">';
                    $x .= '<label class="badge badge-success">'.$z->name.'</label><div class="remove-role" id="'.$z->name.'">X</div>';
                    $x .= '</div>';
                }
                return $x;
            })
            ->rawColumns(['action','roles','status'])
            ->make(true);
    }
    public function userChangePass(User $user){
        $user->password = 'Lasureco';
        $user->save();
        return response()->json($user,200);
    }
    public function updateStatus(User $user, Request $request){
        $user['user_status'] = $request->status;
        $user->save();
        return response()->json($user,200);
    }
    public function update(UserChangePassUpdateRequest $request, User $user)
    {
        if(!Hash::check($request->password, $user->password)){
            return response()->json(['message'=>'Current password is incorrect!'],401);
        }
        $user->password = $request->new_password;
        $user->save();
        return response()->json($user,200);
    }
    public function allUsers(){
        $user = count(User::all());
        return response()->json($user,200);
    }
    public function addUserRole(Request $request){
        $user = User::findOrFail($request->user);
        $user->assignRole($request->role);
        return response()->json($user,201);
    }
    public function removeUserRole(Request $request){
        $user = User::findOrFail($request->user);
        $user->removeRole($request->role);
        return response()->json($user,201);
    }
}
