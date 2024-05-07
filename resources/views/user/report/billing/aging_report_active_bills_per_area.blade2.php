@extends('layout.master')
@section('title', 'Aging Report Active Bills per Area')
@section('stylesheet')
    <style>
        .main select {
            width: 100%;
        }
        .main{
            background-color: #f9f9f9;
            border-radius: 10px;
            color: #000;
            height: auto;
            max-height: auto;
            padding-top: 10px;
            padding-bottom: 10px;
            font-size: 16px!important;
        }
        #route{
            padding-left: 10%;
            padding-right: 10%;
        }
        #route .dataTable{
            width:100%;
        }
    </style>
@endsection
@section('content')
<p class="contentheader">Aging Report Active Bills per Area</p>
<div class="main container">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <span>Route Code:</span>
                <div class="row">
                    <div class="col-md-8">
                        <input type="hidden" name="route_id" id="route_id">
                        <input type="number" name="route__code" id="route__code" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <span class="route_desc"></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="as_of_date">Status:</label>
                <select name="status" id="status" class="form-control">
                    <option value="" selected>Choose here</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="both">Both</option>
                </select>
            </div>
            <h4>Range</h4>
            <hr>
            <div class="form-group">
                <label for="">Bill Period:</label>
                <input type="month" name="date_1" id="date_1" class="form-control">
            </div>
            <div class="form-group">
                <button class="btn btn-primary form-control print_aging">Print</button>
            </div>
        </div>
    </div>
</div>
<div id="route" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Routes</h3>
            <span href="#route" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table" id="route_table">
                                <thead>
                                    <tr>
                                        <th>Area</th>
                                        <th>Town</th>
                                        <th>Code</th>
                                        <th>Route</th>
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
	</div>
</div>
<script>
    $(document).ready(function(){
        localStorage.removeItem("aging")
    })
    $(document).on('click','#route__code',function(){
        if(!$('#route').hasClass('has_data')){
            $('#route_table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{route('api.routes.get')}}",
                "columns": [
                    { "data": "areas" },
                    { "data": "towns" },
                    { "data": "rc_code"},
                    { "data": "rc_desc" },
                    { "data": 'action'},
                ],
                "pageLength" : 5,
                "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
                "order": [ [0, 'desc'] ]
            });
            $('#route').addClass('has_data')
        }
        $('#route').css('display','block')
    })
    $(document).on('click','#select',function(){
        $('#route_id').val($(this).val())
        $('#route__code').val($(this).attr("route_code"))
        $('#route').css('display','none')
        let url = "{{route('api.routes.select',':id')}}"
        let urlUpdate = url.replace(':id',$(this).val())
        $.ajax({
            url: urlUpdate,
            method: "get",
            dataType: "json",
            success: function(data){
                $('.route_desc').html('<p style="margin-top:0.5rem">Route name: '+data.name.rc_desc+'</p>')
            },
            error: function(error){
               
            }
        })
    })
    $(document).on('click','.print_aging',function(){
        $.ajax({
            url: "{{route('reports.billing.aging')}}",
            method: "post",
            dataType: "json",
            data: {
                route_id: $('#route_id').val(),
                selected: $('#status :selected').val(),
                date1: $('#date_1').val(),
            },
            success:function(data){
                localStorage.setItem('aging',JSON.stringify(data))
                $(':input').val('')
                window.open("{{route('print.aging')}}");
                location.reload();
            },
            error: function(error){
                Swal.fire({
                    icon: 'error',
                    title: 'Not Found!',
                    text: 'No Record Found!',
                })
                //console.log(error)
                location.reload();
            }
        })
    })
</script>
@endsection
