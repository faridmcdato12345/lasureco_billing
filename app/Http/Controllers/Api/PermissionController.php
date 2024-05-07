<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function getPermission(){
        $query = Permission::select('id','name');
        return datatables($query)
        ->addIndexColumn()
        ->addColumn('action', function($row){
        $btn = '<input type="checkbox" value="'.$row->id.'" name="permission" class="permission-checkbox">';
            return $btn;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
    public function index(){
        $permission = Permission::select('id','name')->get();
        return response()->json($permission,200);
    }
}
