<script>
    $(document).ready(function(){
            $.fn.dataTable.ext.errMode = 'throw';
            $('#cons11').DataTable({
            "processing": true,
            // "serverSide": true,
            "ajax": "{{route('list1.show')}}",
            "columns": [
                { "data": "fullname"},
                { "data": "accno"},
                { "data": "complaint_no" },
                { "data": "category" },
                { "data": "sub_category" },
                { "data": "created_at" },
                { "data": "finding" },
                { "data": "recommendation"},
                { "data": "user_status" },
                { "data": "action"},
            ],        

            "pageLength" : 7,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ],
            dom: 'Bfrtip',
            buttons: ['copyHtml5','excelHtml5','csvHtml5','pdfHtml5']   
        });  
        var table = $('#cons11').DataTable();
        $('.dataTables_filter input')
            .off()
            .on('change',function(){
                table.search(this.value).draw();
            }); 
    })

    function printConsumers(){
        $url = '{{route("print_pending_consumers")}}'
        window.open($url);
    }
</script>

