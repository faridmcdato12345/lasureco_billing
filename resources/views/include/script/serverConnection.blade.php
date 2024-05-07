<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('click','.btn-server',function(){
        $.ajax({
            url: "{{route('ip_address.check')}}",
            method: "get",
            dataType: "json",
            success: function(data){
                $('#add_server_connection').css('display','block')
            },
            error: function(error){
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Please disable the current ip address setup!',
                })
            }
        })
    })
    $(document).on('click','.create_server_connection',function(){
        $.ajax({
            url: "{{route('ip_address.store')}}",
            dataType: "json",
            method: "post",
            data: {
                ip_address: $('#ip_address').val()
            },
            success:function(data){
                Swal.fire(
                    'Success!',
                    'Server IP ADDRESS was added!',
                    'success'
                )
                setInterval(function(){ location.reload(); }, 3000);
                
            },
            error: function(error){
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong!',
                })
            }
        })
    })
    $(document).on('click','.delete-ip',function(){
        var id = $(this).attr('id')
        var url = "{{route('delete.server_ip',':id')}}"
        var urlUpdate = url.replace(':id',id)
        $.ajax({
            url: urlUpdate,
            method: "get",
            dataType: "json",
            success: function(data){
                Swal.fire(
                    'Success!',
                    'Server IP ADDRESS was deleted!',
                    'success'
                )
                setInterval(function(){ location.reload(); }, 3000);
            },
            error: function(error){
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong!',
                })
            }
        })
    })
    var ipAddressId
    $(document).on('click','.update-ip',function(){
        ipAddressId = $(this).attr('id');
        $('#edit_server_connection').css('display','block')
    })
    $(document).on('click','.update_server_connection',function(){
        var url = "{{route('update.server_ip',':id')}}"
        var urlUpdate = url.replace(':id',ipAddressId)
        $.ajax({
            url: urlUpdate,
            dataType: "json",
            data: {
                ip_address: $('#ip_address_update').val()
            },
            method: "patch",
            success: function(data){
                Swal.fire(
                    'Success!',
                    'Server IP ADDRESS was updated!',
                    'success'
                )
                setInterval(function(){ location.reload(); }, 3000);
            },
            error: function(error){
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong!',
                })
            }
        })
    })
</script>