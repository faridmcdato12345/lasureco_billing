@extends('layout.master')
@section('title', 'Consumer')
@section('stylesheet')
@include('include.style.consumer')
@endsection
@section('content')
@include('include.modal.consumer')
<p class="contentheader">Consumer</p>
<input type="hidden" id="user_id" value="{{Auth::user()->user_id}}">
<div class="container consumer-container" style="background: #f9f9f9;overflow:scroll;padding-top:1%">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <button type='button' class='create-consumer btn btn-success'>Create</button>
                </div>
                <div class="panel-body">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Account #</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th rowspan="1" colspan="1" class="search-bar"></th>
                                <th rowspan="1" colspan="1" class="search-bar"></th>
                                <th rowspan="1" colspan="1" class="search-bar"></th>
                                <th rowspan="1" colspan="1" class="search-bar"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="remarksConsUpdate" class="modal">
    <div class="modal-content" style="width: 80%;">
    <div class="modal-header" style="width: 100%;">
        <h3>Remarks</h3>
        {{-- <button type="button" class="btn-close" onclick="remarksConsUpdateClose();"></button> --}}
    </div>
    <div class="modal-body">
        <textarea class="form-control" style="color:black" id="remarksUpdate" cols="30" rows="10"></textarea>
        <br>
        <button style = "width:100%" class="btn btn-primary" onclick="submitMoTo()">SUBMIT</button>
    </div>
</div>
</div>
<script>
    var toPass = new Object();
    function status123(x){
        Swal.fire({
        title: 'Do you want to continue?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                var a = document.getElementById(x.id+'a');
                if(a.value == ''){
                    a.value = 0;
                }
                toPass.old_status = a.value;
                toPass.new_status = x.value;
                toPass.id = x.id;
                if(typeof toPass.new_status != 'undefined' && typeof toPass.id != 'undefined'){
                    document.querySelector('#remarksConsUpdate').style.display="block";
                }
            }
        });
    }
    function submitMoTo(){
        var remarksData = document.querySelector("#remarksUpdate").value;
        toPass.remarks=remarksData;
        var xhr = new XMLHttpRequest();
        var consUpdate = '{{route("cons.update.status")}}';
        xhr.open('POST', consUpdate, true); 
        xhr.setRequestHeader("Accept", "application/json");
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onload = function() {
            if (this.status == 200) {
                console.log(1);
            }
        }
        xhr.send(JSON.stringify(toPass));
        Swal.fire({
            title: 'Successs',
            text: 'Successfully Updated',
            icon: 'success',
            showConfirmButton:false
        })
        setTimeout(function(){
            location.reload();
        }, 1500);
    }
</script>
@include('include.script.consumer')
@endsection
