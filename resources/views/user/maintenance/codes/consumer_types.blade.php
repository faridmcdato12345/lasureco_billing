@extends('layout.master')
@section('title', 'Consumer Type')
@section('stylesheet')
@include('include.style.consumer_type')
@endsection
@section('content')
@include('include.modal.consumer_type')
<p class="contentheader">Consumer Type</p>
<div class="container consumer-container" style="background: #f9f9f9;overflow:scroll;padding-top:1%">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <button type='button' class='create-consumer-type btn btn-success' data-toggle='modal' data-target='#createConsumerType'>Create</button>
                </div>
                <div class="panel-body">
                    <table class="table" id="consumer_type_datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th>
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
@include('include.script.consumer_type')
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn3');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container3').childNodes;
        c[5].style.color="blue";
    }		
</script>
@endsection

