@extends('layout.master')
@section('title', 'Area Codes')
@section('content')

<p class="contentheader">Area Codes</p>
<div class="main">
    <table class="table" id="datatable" class="dataTable table-dark">
        <thead>
            <tr>
                <th>ID</th>
                <th>NAME</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
 <script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn3');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container3').childNodes;
        c[5].style.color="blue";
    }
</script>
<script src="{{asset('js/datatable.min.js')}}"></script>
<script src="{{asset('js/datatable-button.min.js')}}"></script>
<script src="{{asset('js/button-print.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#datatable').DataTable({
            processing:true,
            serverSide:true,
            ajax: "{{route('api.area.code')}}",
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: 'Print',
                    exportOptions: {
                        modifier: {
                            page: 'current'
                        }
                    }
                },
                {
                    text: 'Create',
                    action: function ( e, dt, node, config ) {
                        alert( 'Button activated' );
                    }
                }
            ],
            "pageLength": 5,
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            "columns": [
                {data: 'ac_id'},
                {data: 'ac_name'},
            ]
        })
    })
</script>
@endsection
