<script>
    var userId;
    $(document).ready(function() {
        $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('api.users.index')}}",
            "columns": [
                { "data": "username" },
                { "data": "user_full_name" },
                { "data": "roles" },
                { "data": "status"},
                { "data": 'action'},
            ],
            "pageLength" : 5,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
        });
    });
    $('.add-button').on('click',function(){
        $('#addUser').toggle();
    })
    $('.create-user').on('click',function(e){
        $.ajax({
            method: "POST",
            data: {
                user_full_name: $('#fname').val(),
                username: $('#uname').val(),
                user_status: 1,
                password: 'Lasureco',
                role: $('#role').val()
            },
            url: "{{route('api.users.register')}}",
            datatype: "json",
            success: function(data){
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Completed!',
                    showConfirmButton: false,
                    timer: 2500
                })
                setTimeout(function() {
                    location.reload();
                }, 2700);
            }
        })
    })
    $(document).on('click','.change-pass',function(){
        var url = "{{route('api.users.change.pass',':id')}}"
        var urlUpdate = url.replace(':id',$(this).val())
        $.ajax({
            url: urlUpdate,
            dataType: "json",
            method: "patch",
            success:function(data){
                Swal.fire(
                    'Success!',
                    'User password has changed to default',
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
    $(document).on('change', '.ad_switch', function (e) {
        var theStatus = ''
        if($(this)[0].checked){
            $(this).next(".status_label").text("Active")
            theStatus = 1
        }
        else{
            $(this).next(".status_label").text("Inactive")
            theStatus = 0
        }
        let urlUpdate = "{{route('api.users.update.status',':id')}}";
        urlUpdate = urlUpdate.replace(':id',$(this).attr('id'));
        $.ajax({
            url: urlUpdate,
            type: "PATCH",
            dataType: "json",
            data: {
                status: theStatus
            },
            success: function(data){
                Swal.fire(
                    'Success!',
                    'User status was updated!',
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
    $(document).on('click','.add-role',function(){
        if(!$('#add_user_role').hasClass('has_data')){
            $('#user_user_role_table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{route('api.roles.add_user_role')}}",
                "columns": [
                    { "data": "name" },
                    { "data": "permissions"},
                    { "data": 'action'},
                ],
                "pageLength" : 5,
                "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            });
            $('#add_user_role').addClass('has_data')
        }
        $('#add_user_role').css('display','block')
        userId = $(this).val()
    })
    $(document).on('click','#addUserRole',function(){
        $.ajax({
            url: "{{route('api.users.add_user_role')}}",
            method: "post",
            dataType: "json",
            data: {
                role: $(this).val(),
                user: userId
            },
            success:function(data){
                Swal.fire({
                    icon: 'success',
                    title: 'Role has been added!',
                    showConfirmButton: false,
                    timer: 2500
                })
                $('#add_user_role').css('display','none')
                $('#datatable').DataTable().ajax.reload();
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
    $(document).on('click','.remove-role',function(){
        $.ajax({
            url: "{{route('api.users.remove_user_role')}}",
            method: "post",
            dataType: "json",
            data: {
                user: $(this).parent().get(0).className,
                role: $(this).attr('id')
            },
            success:function(data){
                Swal.fire({
                    icon: 'success',
                    title: 'Role has been removed!',
                    showConfirmButton: false,
                    timer: 2500
                })
                $('#datatable').DataTable().ajax.reload();
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