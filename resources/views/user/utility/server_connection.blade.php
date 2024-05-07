@extends('layout.master')
@section('title', 'Print Meter Reading Sheet')
@section('stylesheet')
@include('include.style.serverConnection')
@endsection
@section('content')
@include('include.modal.serverConnection')
<p class="contentheader">Server Connection</p>
<div class="main" style="height: 400px">
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-primary btn-server">Add</button>
            <br>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>SERVER IP ADDRESS</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($servers as $server)
                        <tr>
                            <td>{{$server->id}}</td>
                            <td>{{$server->ip_address}}</td>
                            <td>
                                <button class="btn-primary btn update-ip" id="{{$server->id}}">Edit</button>
                                <button class="btn-danger btn delete-ip" id="{{$server->id}}">Delete</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center">Server ip address not yet setup</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@include('include.script.serverConnection')
@endsection
