<script>
    let permissionNameFromEdit = []
    let indxId = []
    let permissionName = []
    $(document).ready(function() {
        $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('api.roles.index')}}",
            "columns": [
                { "data": "name" },
                { "data": "permissions"},
                { "data": 'action'},
            ],
            "pageLength" : 5,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
        });
    });
    $('.closes').on('click',function(){
        $('#permission-table').DataTable().clear().destroy();
    })
    $('.add-button').on('click',function(){
        
        $.ajax({
            url: "{{route('api.permission.index')}}",
            dataType:"json",
            method: "get",
            success: function(data){
                for(var i = 0; i < data.length; i++){
                    $('#permission-table tbody')
                    .append(
                        '<tr>'+
                        '<td>'+data[i].name+'</td>'+
                        '<td><input type="checkbox" value="'+data[i].name+'" name="permission" class="permission-checkbox"></td>'+
                        '</tr>'
                    )
                }
                $('#addRole').toggle();
            },
            error: function(error){
                
            }
        })
    })
    $('.check-all').on('click',function(){
        if($(this).hasClass("selected")){
            $(this).removeClass("selected")
            $('.permission-checkbox').removeClass("selected-permission")
            $('.permission-checkbox').prop('checked', false);
            permissionName = [];
        }
        else{
            $(this).addClass("selected")
            $('.permission-checkbox').addClass("selected-permission")
            $('.permission-checkbox').prop('checked', true)
            for (let index = 0; index < $('.permission-checkbox').length; index++) {
                permissionName.push($('.permission-checkbox')[index].value)
            }
        }
    })
    $(document).on('click','.permission-checkbox',function(){
        if($(this).hasClass("selected-permission")){
            $(this).removeClass("selected-permission")
            permissionName.pop((this).value)
        }
        else{
            $(this).addClass("selected-permission")
            permissionName.push((this).value)
        }
    })

    $('.add-role-permission').on('click',function(e){
        $.ajax({
            type: "POST",
            data: {
                role: $('#role').val(),
                permission_name: permissionName
            },
            url: "{{route('api.rolePermission.store')}}",
            datatype: "json",
            success: function(data){
                Swal.fire({
                    icon: 'success',
                    title: 'Role added successfully!',
                    showConfirmButton: false,
                    timer: 2500
                })
                setTimeout(function() {
                    location.reload();
                }, 2700);
            }
        })
    })
    $(document).on('click','#role-view',function(){
        if($("#showRole").hasClass('visited')){
            $('.permission-list').remove()
        }
        let urlUpdate = "{{route('api.roles.show',':id')}}";
        urlUpdate = urlUpdate.replace(':id',$(this).val());
        $.ajax({
            url: urlUpdate,
            type: "GET",
            dataType: "json",
            success: function(data){
                for(var i = 0; i < data.data.length; i++){
                    $('#showRole .search_permission ul').append('<li class="permission-list"><input type="checkbox" checked="true" disabled>'+data.data[i].name+'</li>')
                }
                var role = data.role.name
                var roleUpperCase = role.toUpperCase()
                $('#showRole .role_name p').text(roleUpperCase)
                $('#showRole').addClass('visited')
                $('#showRole').toggle()
            }
        })
    })
    $(document).on('click','#role-edit',function(){
        if($("#editRole").hasClass('visited')){
            $('.permission-list').remove()
        }
        let urlUpdate = "{{route('api.roles.show',':id')}}";
        urlUpdate = urlUpdate.replace(':id',$(this).val());
        $.ajax({
            url: urlUpdate,
            type: "GET",
            dataType: "json",
            success: function(data){
                var x = data.permissions
                var dd = []
                for(var j = 0; j < x.length; j++){
                    dd.push(data.permissions[j].name)
                }
                for(var i = 0; i < data.permissions.length; i++){
                    if(data.data[i] !== undefined){
                        if (recursiveFunction(dd, data.data[i].name, 0, x.length-1)){
                            $('#editRole .search_permission ul').append('<li class="permission-list"><input type="checkbox" checked="true" class="prev_pchecked cb" value="'+data.data[i].name+'">'+data.data[i].name+'</li>')
                            permissionNameFromEdit.push(data.data[i].name)
                        }
                    }
                    else{
                        $('#editRole .search_permission ul').append('<li class="permission-list"><input type="checkbox" value="'+data.permissions[i].name+'" class="cb">'+data.permissions[i].name+'</li>')
                    }
                }
                var role = data.role.name
                var roleUpperCase = role.toUpperCase()
                $('#editRole .role_name p').text(roleUpperCase)
                $('#editRole .role_name').append("<input type='hidden' value='"+data.role.id+"'>")
                $('#editRole').addClass('visited')
                $('#editRole').toggle()
            }
        })
    })
    $(document).on('click','.cb',function(){
        if($(this).hasClass('prev_pchecked') || $(this).hasClass('added_pchecked')){
            $(this).removeClass('prev_pchecked')
            var indx = permissionNameFromEdit.indexOf($(this).val())
            permissionNameFromEdit.splice(indx,1)
        }
        else{
            permissionNameFromEdit.push($(this).val())
            $(this).addClass('added_pchecked')
        }
    })
    $('.update-role-permission-button').on('click',function(){
        let id = $('#editRole .role_name input[type=hidden]').val()
        let urlUpdate = "{{route('api.rolePermission.update',':id')}}";
        urlUpdate = urlUpdate.replace(':id',id);
        $.ajax({
            url: urlUpdate,
            type: "PATCH",
            data: {
                permissions: permissionNameFromEdit,
            },
            dataType: "json",
            success: function(data){
                Swal.fire({
                    icon: 'success',
                    title: 'Update successfully!',
                    showConfirmButton: false,
                    timer: 2500
                })
                setTimeout(function() {
                    location.reload();
                }, 2700);
            }
        })
    })
    let recursiveFunction = function (arr, x, start, end) {
        if (start > end) return false;
        let mid=Math.floor((start + end)/2);
        if (arr[mid]===x) return true;
        if(arr[mid] > x){
            return recursiveFunction(arr, x, start, mid-1);
        }
        else{
            return recursiveFunction(arr, x, mid+1, end);
        }
    }
    $(document).on('click','.delete-role-bttn',function(){
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
                    $.ajax({
                    url: "{{route('api.role.delete')}}",
                    method: "post",
                    dataType: "json",
                    data : {
                        id: $(this).val()
                    },
                    success: function(data){
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
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
                
            }
        })
        
    })
</script>