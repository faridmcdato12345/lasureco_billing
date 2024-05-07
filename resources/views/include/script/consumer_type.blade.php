<script>
    $(document).ready(function(){
        $('.dropdown-container3').addClass('add-display')
        $('#consumer_type_datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('api.consumerType.get.datatable')}}",
            "columns": [
                { "data": "ct_id" },
                { "data": "ct_code" },
                { "data": "ct_desc" },
                { "data": 'action'},
            ],
            "pageLength" : 5,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ]
        });
    })
    $(document).on('click','.create-consumer-type',function(){
        $('#create_consumer_type').css('display','block')
    })
    $(document).on('click','.save-consumer-type',function(){
        $.ajax({
            url: "{{route('api.consumerType.store')}}",
            dataType: "json",
            method: "post",
            data:{
                ct_code: $('#ct_code').val(),
                ct_desc: $('#ct_description').val()
            },
            success: function(data){
                $('#consumer_type_datatable').DataTable().ajax.reload();
                Swal.fire(
                    'Success!',
                    'Consumer type was saved!',
                    'success'
                )
                $('#wizardPicturePreview').attr('src', "{{asset('img/placeholder-person.png')}}")
                $('#create_consumer_type input[type=text]').val("")
                $('#create_consumer_type').css('display','none')
            },
            error: function(error){
                var errorList = []
                var errorHtmlList = ''
                if(error.responseJSON.errors.ct_code){
                    errorList.push("<li>Code is required</li>")
                    $('#ct_code').addClass('required_field')
                    errorHtmlList += "<li style='list-style-type: none;color:red'>Code is required</li>"
                }
                if(error.responseJSON.errors.ct_desc){
                    errorList.push("<li>Description is required</li>")
                    $('#ct_description').addClass('required_field')
                    errorHtmlList += "<li style='list-style-type: none;color:red'>Description is required</li>"
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
    $('#ct_code').change(function(){
        if($(this).hasClass('required_field')){
            $(this).removeClass('required_field')
        }
    })
    $('#ct_description').change(function(){
        if($(this).hasClass('required_field')){
            $(this).removeClass('required_field')
        }
    })
    $(document).on('click','.ctSelect',function(){
        var url = "{{route('api.consumerType.select',':id')}}"
        var urlUpdate = url.replace(':id',$(this).val())
        $.ajax({
            url: urlUpdate,
            dataType: "json",
            method: "get",
            success: function(data){
               $('#ct_code_edit').val(data.ct_code)
               $('#ct_description_edit').val(data.ct_desc)
               $('#ct_hidden_edit').val(data.ct_id)
            },
            error:function(error){
            }
        })
        $('#edit_consumer_type').css('display','block')
    })
    $(document).on('click','.save-edit-consumer-type',function(){
        var url = "{{route('api.consumerType.update',':id')}}"
        var urlUpdate = url.replace(':id',$('#ct_hidden_edit').val())
        $.ajax({
            url: urlUpdate,
            dataType: "json",
            method: "patch",
            data: {
                ct_code: $('#ct_code_edit').val(),
                ct_desc: $('#ct_description_edit').val()
            },
            success: function(data){
                $('#consumer_type_datatable').DataTable().ajax.reload();
                $('#edit_consumer_type').css('display','none')
                Swal.fire(
                    'Success!',
                    'Consumer type was updated!',
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
</script>