@extends('layout.master')
@section('title', 'User')
@section('stylesheet')
@include('include.style.user')
@endsection
@section('content')
@include('include.modal.user')
<table style="width: 100%;">
    <tr>
        <td>
            <p class="contentheader">Users</p>
        </td>
    </tr>
</table>
<div class="container" style="background: #f9f9f9;overflow:scroll;padding-top:1%;color:#000;max-width:95%;">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p style="color: black">Note: Clicking <span style="color: #1e7e34">"CHANGE PASSWORD"</span> button will change the user password to <span style="color:red">"Lasureco"</span> </p>
                    <button type='button' class='add-button btn btn-success'>Create</button>
                </div>
                <div class="panel-body">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Full name</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('include.script.user')
@endsection
