<?php

namespace App\Http\Controllers;

use App\Models\ServerConnection;
use Illuminate\Http\Request;

class ServerConnectionController extends Controller
{
    public function index(){
        $servers = ServerConnection::select('id','ip_address','status')->where('status',1)->get();
        return view('user.utility.server_connection',compact('servers'));
    }
    public function store(Request $request){
        $request->validate([
            'ip_address'=>'required|ip',
        ]);
        $data = ServerConnection::create($request->all());
        return response()->json($data,201);
    }
    public function checkEnabledIp(){
        $ip = ServerConnection::where('status',1)->get();
        if($ip->isEmpty()){
            return response()->json($ip,200);
        }
        else{
            return response()->json($ip,404);
        }
    }
    public function deleteIp(ServerConnection $server){
        $server->status = 0;
        $server->save();
        return response()->json($server,200);
    }
    public function updateIp(ServerConnection $server, Request $request){
        $server->ip_address = $request->ip_address;
        $server->save();
        return response()->json($server,200);
    }
}
