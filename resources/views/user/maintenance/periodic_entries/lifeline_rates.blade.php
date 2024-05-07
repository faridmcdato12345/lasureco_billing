@extends('layout.master')
@section('title', 'Lineline Rates')
@section('stylesheet')
@include('include.style.lifeline')
@endsection
@section('content')
@include('include.modal.lifeline')
<p class="contentheader">Data Entry Lifeline Rates</p>
<div class="container lifeline-container" style="background: #f9f9f9;overflow:scroll;padding-top:1%">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <button type='button' class='create-lifeline btn btn-success' data-toggle='modal' data-target='#createConsumer'>Create</button>
                </div>
                <div class="panel-body">
                    <table class="table" id="lifeline-datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mininimun Kw/H</th>
                                <th>Maximum Kw/H</th>
                                <th>Discount</th>
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
@include('include.script.lifeline')
@endsection
