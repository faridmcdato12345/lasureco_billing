@extends('layout.master')
@section('title', 'Consumer')
@section('stylesheet')
@include('include.style.consumer')
@endsection
@section('content')
@include('include.modal.consumer')
<p class="contentheader">Pending Consumer</p>

<div id="filterModal" class="modal">
    <div class="modal-content" style="margin-top: 10px; width: 30%; height: auto;">
        <div class="modal-header" style="width: 100%; height: 60px; background-color: white;">
            <h3 style="color: #6D6D64;">Print by</h3>;
            <span href="#filterModal" class="closes" id="close" style="color: #d72503;"> &times; </span>
        </div>
        <div class="modal-body">
            <br>
            <table style="margin: auto; width: 90%;">
                <tr>
                    <td>
                        <button id="all" class="btn btn-primary" onclick="printAllConsumers()" style="width: 100%;"> All </button>
                    </td>
                </tr>
                <tr> <td height="15px;"></td> </tr>
                <tr>
                    <td>
                        <button id="area" class="btn btn-primary" onclick="showArea()" style="width: 100%;"> Area </button>
                    </td>
                </tr>
                <tr> <td height="15px;"></td> </tr>
                <tr>
                    <td>
                        <button id="townId" class="btn btn-primary" onclick="showTown()" style="width: 100%;"> Town </button>
                    </td>
                </tr>
                <tr> <td height="15px;"></td> </tr>
                <tr>
                    <td>
                        <button id="route" class="btn btn-primary" onclick="showRoutes()" style="width: 100%;"> Route </button>
                    </td>
                </tr>
            </table>
            <br>
        </div>
    </div>
</div>

<div class="container consumer-container" style="background: #f9f9f9;overflow:scroll;padding-top:1%">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table" id="consumer_pending">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Account #</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Created at</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <button class="print-button" onclick="showFilterModal()"> Print </button>
                </div>
            </div>
        </div>
    </div>
</div>
@include('include.script.consumer_pending')
@include('include.modal.areamodal')
@include('include.modal.townmodal')
@include('include.modal.routemodal')
@endsection
