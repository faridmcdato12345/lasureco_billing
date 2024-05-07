@extends('layout.master')
@section('title', 'Delete Consumer')
@section('content')
<p class="contentheader">Delete Consumer</p>
<div class="main">
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
@endsection
@section('scripts')
<script src="{{asset('js/sweetalert2.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#consumer_pending').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('consumers.index')}}",
            "columns": [
                { "data": "cm_id" },
                { "data": "cm_account_no" },
                { "data": "cm_full_name" },
                { "data": "cm_address" },
                { "data": "action" },
            ],
            "pageLength" : 10,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ],
            dom: 'Bfrtip',
            buttons: ['copyHtml5','excelHtml5','csvHtml5','pdfHtml5']
        });
    });

    $(document).on('click','.deleteButton', function(){
        var id = $(this).attr('data-id');
        var routeUrl = "{{ route('remove.consumer') }}";
        // console.log(id);
        
        $.ajax({
            type: "post",
            url: routeUrl,
            data: {
                id: id
            },
            dataType: "json",
            success: function(response, textStatus, jqXHR) {
                // Log the status code
                if(jqXHR.status == 200){
                    Swal.fire({
                        title: 'Warning!',
                        text: "Are you sure you want to delete Consumer?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "post",
                                url: "{{ route('destroy.consumer') }}",
                                data: {
                                    id: id
                                },
                                dataType: "json",
                                success: function(response, textStatus, jqXHR) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Consumer Successfully Deleted!'
                                    }).then((result) => {
                                        $('#consumer_pending').DataTable().ajax.reload();
                                    })
                                }
                            })
                        }
                    })
                } else {
                    Swal.fire({
                        title: 'Consumer has record on either Sales or Meter Registry!',
                        text: "You can still delete the Consumer but Supervisory is needed! Proceed?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Enter Supervisory Password',
                                input: 'password',
                                inputAttributes: {
                                    autocapitalize: 'off'
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Submit'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        type: "post",
                                        url: "{{ route('verify.supervisory') }}",
                                        data: {
                                            password: result.value
                                        },
                                        dataType: "json",
                                        success: function(response, textStatus, jqXHR) {
                                            if(response.Message == '1'){
                                                $.ajax({
                                                    type: "post",
                                                    url: "{{ route('destroy.consumer') }}",
                                                    data: {
                                                        id: id
                                                    },
                                                    dataType: "json",
                                                    success: function(response, textStatus, jqXHR) {
                                                        Swal.fire({
                                                            icon: 'success',
                                                            title: 'Consumer Successfully Deleted!'
                                                        }).then((result) => {
                                                            $('#consumer_pending').DataTable().ajax.reload();
                                                        })
                                                    }
                                                })
                                            } else {
                                                Swal.fire({
                                                    position: 'center',
                                                    icon: 'error',
                                                    title: 'Incorrect Password',
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                })
                                            }
                                        }
                                    })
                                }
                            })
                        }
                    })
                }
                
                // Log the entire response
            }
        });
    });
</script>
@endsection