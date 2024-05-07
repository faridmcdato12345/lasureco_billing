@extends('layout.master')
@section('title', 'Set server ip address')
@section('stylesheet')
    <style>
        .server-container{
            margin:20px;
        }
    </style>
@endsection
@section('content')
<p class="contentheader">Server IP Address</p>
<div class="container server-container" style="background: #f9f9f9;overflow:scroll;padding-top:1%">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <button type='button' class='create-server btn btn-success'>Create</button>
                </div>
                <br>
                <div class="panel-body">
                    <table class="table" id="server-datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>IP Address</th>
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
<div id="addServer" class="modal">
	<div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Add Server IP Address</h3>
            <span href="#addServer" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="form-group">
                <label for="full_name">IP Address:</label>
                <input type="text" name="ip_address" id="ip_address" class="form-control" placeholder="Enter ip address here..." required="true">
            </div>
            <input type="button" class="btn btn-primary form-control add-server" value="Create">
        </div>
	</div>
</div>
<div id="updateServer" class="modal">
	<div class="modal-content" style="height: auto;max-height:650px;">
        <div class="modal-header">
            <h3>Update Server IP Address</h3>
            <span href="#updateServer" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <div class="form-group">
                <input type="hidden" name="" id="server_id">
                <label for="full_name">IP Address:</label>
                <input type="text" name="update_ip_address" id="update_ip_address" class="form-control" placeholder="Enter ip address here..." required="true">
            </div>
            <input type="button" class="btn btn-primary form-control update-server" value="Update">
        </div>
	</div>
</div>
<script>
    $(document).ready(function(){
        $('#server-datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('api.servers.get')}}",
            "columns": [
                { "data": "id"},
                { "data": "ip_address"},
                { "data": 'action'},
            ],
            "pageLength" : 5,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ]
        });
    })
    $(document).on('click','.create-server',function(){
        $.ajax({
            url: "{{route('api.servers.check')}}",
            dataType: "json",
            method: "get",
            success: function(data){
                $('#addServer').css('display','block')
            },
            error: function(error){
                Swal.fire({
                    icon: 'error',
                    title: 'Server ip address has already added. Please, delete first',
                    showConfirmButton: false,
                    timer: 5000
                })
            }
        })
    })
    $(document).on('click','.delete-ip',function(){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                let url = "{{route('api.servers.delete',':id')}}"
                let urlUpdate = url.replace(':id',$(this).val())
                $.ajax({
                    url: urlUpdate,
                    method: "delete",
                    dataType: "json",
                    success:function(data){
                        Swal.fire(
                            'Deleted!',
                            'Server IP address has been removed.',
                            'success'
                        )
                        $('#server-datatable').DataTable().ajax.reload();
                    },
                    error: function(error){
                    
                    }
                })
                
            }
        })
    })
    $(document).on('click','.add-server',function(){
        $.ajax({
            url: "{{route('api.servers.store')}}",
            method: "post",
            dataType: "json",
            data: {
                ip_address: $('#ip_address').val()
            },
            success: function(data){
                Swal.fire(
                    'Success!',
                    'IP address of server was successfully saved!',
                    'success'
                )
                $('#addServer').css('display','none')
                $('#server-datatable').DataTable().ajax.reload();
            },
            error: function(error){
                
            }
        })
    })
    $(document).on('click','.modify',function(){
        let url = "{{route('api.servers.show',':id')}}"
        let urlUpdate = url.replace(":id",$(this).val())
        $.ajax({
            url: urlUpdate,
            method: "get",
            dataType: "json",
            success: function(data){
                $('#update_ip_address').val(data.ip_address)
                $('#server_id').val(data.id)
                $('#updateServer').css('display','block')
            },
            error: function(error){
            
            }
        })
    })
    $(document).on('click','.update-server',function(){
        let url = "{{route('api.servers.update',':id')}}"
        let urlUpdate = url.replace(":id",$('#server_id').val())
        $.ajax({
            url: urlUpdate,
            method: "patch",
            dataType: "json",
            data: {
                ip_address: $('#update_ip_address').val()
            },
            success: function(data){
                Swal.fire(
                    'Updated!',
                    'IP address of server was successfully updated!',
                    'success'
                )
                $('#updateServer').css('display','none')
                $('#server-datatable').DataTable().ajax.reload();
            },
            error: function(error){
                
            }
        })
    })
</script>
@endsection
