<script>
    $(document).ready(function(){
        $('.dropdown-container3').addClass('add-display')
        $('#lifeline-datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('api.lifeline.get')}}",
            "columns": [
                { "data": "ll_id" },
                { "data": "ll_min_kwh" },
                { "data": "ll_max_kwh" },
                { "data": "ll_discount" },
                { "data": 'action'},
            ],
            "pageLength" : 5,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ]
        });
    })
    $(document).on('click','.edit-lifeline',function(){
        var url = "{{route('api.lifeline.edit',':id')}}"
        var urlUpdate = url.replace(':id',$(this).val())
        $.ajax({
            url: urlUpdate,
            dataType: "json",
            method: "get",
            success: function(data){
                $('#min_kwh').val(data.ll_min_kwh)
                $('#max_kwh').val(data.ll_max_kwh)
                $('#discount').val(data.ll_discount)
                $('#ll_hidden_edit').val(data.ll_id)
            },
            error: function(error){
            }
        })
        $('#edit_lifeline').css('display','block')
    })
    $(document).on('click','.update_lifeline',function(){
        var url = "{{route('api.lifeline.update',':id')}}"
        var urlUpdate = url.replace(':id',$('#ll_hidden_edit').val())
        $.ajax({
            url: urlUpdate,
            dataType: "json",
            method: "patch",
            data: {
                ll_min_kwh: $('#min_kwh').val(),
                ll_max_kwh: $('#max_kwh').val(),
                ll_discount: $('#discount').val()
            },
            success: function(data){
                $('#lifeline-datatable').DataTable().ajax.reload();
                $('#edit_lifeline').css('display','none')
                Swal.fire(
                    'Success!',
                    'Lifeline was updated!',
                    'success'
                )
            },
            error: function(error){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                })
            }
        })
    })
    $(document).on('click','.create-lifeline',function(){
        $('#create_lifeline').css('display','block')
    })
    $(document).on('click','.create_lifeline_save',function(){
        $.ajax({
            url: "{{route('api.lifeline.store')}}",
            dataType: "json",
            method: "post",
            data:{
                ll_min_kwh: $('#create_min_kwh').val(),
                ll_max_kwh: $('#create_max_kwh').val(),
                ll_discount: $('#create_discount').val()
            },
            success: function(data){
                $('#lifeline-datatable').DataTable().ajax.reload();
                Swal.fire(
                    'Success!',
                    'Lifeline was saved!',
                    'success'
                )
                $('#create_min_kwh').val(""),
                $('#create_max_kwh').val(""),
                $('#create_discount').val("")
                $('#create_lifeline').css('display','none')
            },
            error: function(error){
                var errorList = []
                var errorHtmlList = ''
                if(error.responseJSON.errors.ll_min_kwh){
                    errorList.push("<li>Minimum Kwh is required</li>")
                    $('#min_kwh').addClass('required_field')
                    errorHtmlList += "<li style='list-style-type: none;color:red'>Minimum Kwh is required</li>"
                }
                if(error.responseJSON.errors.ll_max_kwh){
                    errorList.push("<li>Maximum Kwh is required</li>")
                    $('#max_kwh').addClass('required_field')
                    errorHtmlList += "<li style='list-style-type: none;color:red'>Maximum Kwh is required</li>"
                }
                if(error.responseJSON.errors.ll_discount){
                    errorList.push("<li>Discount is required</li>")
                    $('#discount').addClass('required_field')
                    errorHtmlList += "<li style='list-style-type: none;color:red'>Discount is required</li>"
                }
                Swal.fire({
                    title: 'Error!',
                    icon: 'error',
                    html:errorHtmlList,
                    showCloseButton: true,
                })
            }
        })
    })
</script>