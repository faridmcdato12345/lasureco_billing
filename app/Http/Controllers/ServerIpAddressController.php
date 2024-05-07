<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIpAddressRequestForm;
use App\Models\Server;
use Illuminate\Http\Request;

class ServerIpAddressController extends Controller
{
    public function index(){
        return view('user.utility.server_ip.index');
    }
    public function store(StoreIpAddressRequestForm $request){
        $query = Server::create($request->all());
        return response()->json($query,201);
    }
    public function datatableServer(){
        $query = Server::select('id','ip_address','status')->where('status',1);    
        return datatables($query)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<button class="modify btn btn-info btn-sm action-bttn" value="'.$row->id.'" id="role-view" data-toggle="modal" data-target="#addRole" style="margin-right:10px;">Modify</button>';
                $btn = $btn.'<button class="delete-ip btn btn-danger btn-sm action-bttn" value="'.$row->id.'">Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function delete(Server $server){
        $data = $server->delete();
        return response()->json($data,200);
    }
    public function checkServerIp(){
        $data = Server::where('status',1)->get();
        if($data->isEmpty()){
            return response()->json($data,200);
        }
        return response()->json($data,404);

        //return response()->json($data,200);
    }
    public function update(Server $server, Request $request){
        $data = $server->update($request->all());
        return response()->json($data,200);
    }
    public function show(Server $server){
        return response()->json($server,200);
    }
    public function getIp(){
        $ip = Server::select('ip_address')->where('status',1)->first();
        return response()->json($ip,200);
    }
}
