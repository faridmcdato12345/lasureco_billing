@extends('layout.master')
@section('title', 'Roles')
@section('stylesheet')
@include('include.style.addRolePermission')
@endsection
@section('content')
@include('include.modal.addRolePermission')
<table style="width: 100%;">
    <tr>
        <td>
            <p class="contentheader">Roles</p>
        </td>
    </tr>
</table>
<div class="container" style="background: #f9f9f9;overflow:scroll;padding-top:1%">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <button type='button' class='add-button btn btn-success'>Add</button>
                </div>
                <div class="panel-body">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Permissions</th>
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
    @include('include.script.addRolePermission')
</div>
@endsection
