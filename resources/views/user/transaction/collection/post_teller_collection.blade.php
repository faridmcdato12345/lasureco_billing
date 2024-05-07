@extends('layout.master')
@section('title', 'Post Tellers Collection')
@section('stylesheet')
@include('include.style.post_teller_collection')
@endsection
@section('content')
<div id="post_collection" class="modal">
    <div class="modal-content" style="height: auto;max-height:650px;max-width:90%;">
        <div class="modal-header">
            <h3>Post Collection</h3>
            <span href="#post_collection" class="closes">×</span>
        </div>
        <div class="modal-body" style="color: black;">
            <input type="hidden" name="" id="ll_hidden_edit">
            <div class="form-group">
                <label for="ct_code">Acknowledgement Receipt:</label>
                <input type="text" name="ar" id="ar" class="form-control" required>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary post-collection">Save</button>
        </div>
	</div>
</div>
<p class="contentheader">Post Tellers Collection</p>
<div class="container consumer-container" style="background: #f9f9f9;overflow:scroll;padding-top:1%;color:#000;max-width:95%">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table" id="post_teller_datatable">
                        <thead>
                            <tr>
                                <th>Teller</th>
                                <th>Date</th>
                                <th>Total Sales</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th rowspan="1" colspan="1" class="search-bar">
                                </th>
                                <th rowspan="1" colspan="1" class="search-bar">

                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
     $(document).ready(function(){
        $('#post_teller_datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('api.posting.index')}}",
            "columns": [
                { "data": "teller_user_id"},
                { "data": "s_bill_date" },
                { "data": "total_sales" },
                { "data": 'action'},
            ],
            "pageLength" : 5,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ],
            initComplete: function () {
                var x = '';
                this.api().columns().every(function () {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).appendTo($(column.footer()).empty())
                    .on('change', function () {
                        column.search($(this).val(), false, false, true).draw();
                    });
                    x = x + 1
                });
                $('.search-bar:nth-child('+x+') input').attr('placeholder','Search here...')
            }
        });
        
     })
     $('.search-bar input').attr('placeholder','Search here...')
     var billing_date = ''
     var teller = ''
     $(document).on('click','.select-collection',function(){
        $('#ar').val("")
         billing_date = $(this).val()
         teller = $(this).next('input[type=hidden]').val()
         $('#post_collection').css('display','block')
     })
     $(document).on('click','.post-collection',function(){
        if(!$('#ar').val()){
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'AR input field is required!',
            })
         }else{
            $.ajax({
                url: "{{route('api.posting.update.ar')}}",
                dataType: "json",
                data: {
                    teller_name: teller,
                    bill_date: billing_date,
                    s_ack_receipt: $('#ar').val()
                },
                method: "post",
                success: function(data){
                    Swal.fire(
                        'Success!',
                        'Collections are now posted',
                        'success'
                    )
                    $('#post_collection').css('display','none');
                    $('#post_teller_datatable').DataTable().ajax.reload();
                    
                },
                error: function(error){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Acknowledgement receipt already used!',
                    })
                }
            })
         }
         
     })
         
</script>
@endsection
