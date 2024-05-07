<script>
    $(document).ready(function(){
        $('#consumer_pending').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('consumers.pending')}}",
            "columns": [
                { "data": "cm_id" },
                { "data": "cm_account_no" },
                { "data": "cm_full_name" },
                { "data": "cm_address" },
                { "data": "created_at"},
                { "data": "status" }
            ],
            "pageLength" : 10,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ],
            dom: 'Bfrtip',
            buttons: ['copyHtml5','excelHtml5','csvHtml5','pdfHtml5']
        });
    })

    function showFilterModal(){
        document.querySelector("#filterModal").style.display = "block";
    }

    // ---
    var consId,theStatus,descStatus;
    $(document).on('change', '.ad_switch', function (e) {
        consId = $(this).attr('id')
        if($(this)[0].checked){
            descStatus = 'Active'
            theStatus = 1
        }
        else{
            descStatus = 'Inactive'
            theStatus = 0
        }
        $('#change_status_remarks_modal').show()
    })
    $(document).on('click','.changeStatusRemarks',function(e){
        var statusRemarks = $('#changestatusremarks').val()
        changeStatus(consId,theStatus,statusRemarks)
        .then((response) => {
            $(".status_label").text(descStatus)
            Swal.fire(
                'Success!',
                'Consumer status was updated!',
                'success'
            )
            $('#change_status_remarks_modal').css('display','none')
            $(location.reload())
        })
        .catch((error) => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.responseJSON.message,
            })
            $(".status_label").text('Inactive')
        })
    })
    function changeStatus(cons_id,the_status,status_desc){
        let urlUpdate = "{{route('api.consumers.update.status',':id')}}";
        urlUpdate = urlUpdate.replace(':id',cons_id);
        return new Promise((resolve,reject) => {
            $.ajax({
                url: urlUpdate,
                type: "PATCH",
                dataType: "json",
                data: {
                    status: the_status,
                    remarks: status_desc
                },
                success: function(data){
                    resolve(data)
                },
                error: function(error){
                    reject(error)
                }
            })
        })
    }
    // ---

    function setArea(x){
        var areaId = x.getAttribute('areaId');
        
        const toStore = {
            "id": areaId,
            "filter": "area"
        }

        localStorage.setItem('data', JSON.stringify(toStore));

        $url = '{{route("print_pending_consumers")}}'
        window.open($url);
        location.reload();
    }

    function selectTown(x){
        var townId = x.id;
        
        const toStore = {
            "id": townId,
            "filter": "town"
        }

        localStorage.setItem('data', JSON.stringify(toStore));

        $url = '{{route("print_pending_consumers")}}'
        window.open($url);
        location.reload();
    }

    function setRoute(x){
        var routeId = x.id;
        
        const toStore = {
            "id": routeId,
            "filter": "route"
        }

        localStorage.setItem('data', JSON.stringify(toStore));

        $url = '{{route("print_pending_consumers")}}'
        window.open($url);
        location.reload();
    }

    function printAllConsumers(x){
        const toStore = {
            "filter": "all"
        }

        localStorage.setItem('data', JSON.stringify(toStore));

        $url = '{{route("print_pending_consumers")}}'
        window.open($url);
        location.reload();
    }
</script>

