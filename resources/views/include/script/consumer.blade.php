<script>
    $(document).ready(function(){
        let userId = $('#user_id').val()
        let url = "{{route('api.consumers.get',':id')}}"
        let urlUpdate = url.replace(':id',userId)
        $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": urlUpdate,
                "type": "POST"
            },
            "columns": [
                { "data": "cm_id" },
                { "data": "cm_account_no" },
                { "data": "cm_full_name" },
                { "data": "cm_address" },
                { "data": "status" },
                { "data": 'action'},
            ],
            "pageLength" : 5,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ],
            "searching": true,
            initComplete: function(){
                var x = ''
                this.api().columns().every(function(){
                    var column = this
                    var input = document.createElement("input")
                    $(input).appendTo($(column.footer()).empty())
                    .on('change', function(){
                        column.search($(this).val(),false,false,true).draw()
                    })
                    x = x + 1
                })
            }         
        });
    })
    $('.create-consumer').click(function(){
        $('#wizardPicturePreview').attr('src', "{{asset('img/placeholder-person.png')}}")
        $('#createConsumer input[type=text]').val("")
        $('#consumer_info_div input[type=date]').val("")
        $('#save_consumer').css('display','block')
        $('#update_consumer').css('display','none')
        $('#createConsumer').css('display','block')
    })
    $('#m_account_n').on('click',function(){
        $('.not_main_account').slideDown("slow")
    })
    $('#m_account_y').on('click',function(){
        $('#main_account').val("")
        $('#mA_hidden').val("")
        $('#nC_hidden').val("")
        $('#near_consumer').val("")
        $('#occupant').val("")
        $('#board_reso').val("")
        $('.not_main_account').slideUp("slow")
    })
    $('#metered_yes').on('click',function(){
        $('.not_metered').slideDown("slow")
    })
    $('#metered_no').on('click',function(){
        $('.not_metered').slideUp("slow")
        $('#mm_hidden').val("")
        $('#meter_no').val("")
    })
    $('#route').on('click',function(){
        if(!$('#createConsumerRoute').hasClass('has_data')){
            $('#route_datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{route('api.routes.get')}}",
                "columns": [
                    { "data": "areas"},
                    { "data": "towns" },
                    { "data": "rc_desc" },
                    { "data": 'action'},
                ],
                "pageLength" : 5,
                "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
                "order": [ [0, 'desc'] ]
            });
            
            $('#createConsumerRoute').addClass('has_data')
        }
        $('#createConsumerRoute').css('display','block')
    })
    $(document).on('click','#select',function(){
        if($('#route').hasClass('required_field')){
            $('#route').removeClass('required_field')
        }
        let urlUpdate = "{{route('api.routes.select',':id')}}";
        urlUpdate = urlUpdate.replace(':id',$(this).val());
        $.ajax({
            url: urlUpdate,
            type: "GET",
            dataType: "json",
            success: function(data){
                $('#createConsumerRoute').hide()
                $('#route').val(data.name.rc_desc)
                $('#account_no').val(data.accntNo)
                $('#seq_no').val(data.seqNo)
                $('#rc_hidden').val(data.name.rc_id)
                $('#cm_address').val(data.name.rc_desc + ", " + data.area[0].town_code[0]['town_code_name'])
            }
        })
    })
    $('#first_name').change(function(){
        $('#cm_fullname').val($('#first_name').val() + " " + $('#middle_name').val() + " " + $('#family_name').val() + " " + $('#extension_name').val())
    })
    $('#middle_name').change(function(){
        $('#cm_fullname').val($('#first_name').val() + " " + $('#middle_name').val() + " " + $('#family_name').val() + " " + $('#extension_name').val())
    })
    $('#family_name').change(function(){
        $('#cm_fullname').val($('#first_name').val() + " " + $('#middle_name').val() + " " + $('#family_name').val() + " " + $('#extension_name').val())
    })
    $('#extension_name').change(function(){
        $('#cm_fullname').val($('#first_name').val() + " " + $('#middle_name').val() + " " + $('#family_name').val() + " " + $('#extension_name').val())
    })
    $('#consumer_type').on('click',function(){
        if(!$('#consumerType').hasClass('has_data')){
            $('#c_type_datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{route('api.consumerType.get')}}",
                "columns": [
                    { "data": "ct_code"},
                    { "data": "ct_desc"},
                    { "data": 'action'},
                ],
                "pageLength" : 5,
                "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
                "order": [ [0, 'desc'] ]
            });
            
            $('#consumerType').addClass('has_data')
        }
        $('#consumerType').css('display','block')
    })
    $(document).on('click','.ctSelect',function(){
        if($('#consumer_type').hasClass('required_field')){
            $('#consumer_type').removeClass('required_field')
        }
        let urlUpdate = "{{route('api.consumerType.select',':id')}}";
        urlUpdate = urlUpdate.replace(':id',$(this).val());
        $.ajax({
            url: urlUpdate,
            type: "GET",
            dataType: "json",
            success: function(data){
                $('#consumerType').hide()
                $('#consumer_type').val(data.ct_desc)
                $('#ct_hidden').val(data.ct_id)
            }
        })
    })
    $('#main_account').on('click',function(){
        if(!$('#mainConsumer').hasClass('has_data')){
            $('#main_consumer').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{route('api.consumers.main.account')}}",
                "columns": [
                    { "data": "cm_id" },
                    { "data": "cm_account_no" },
                    { "data": "cm_full_name" },
                    { "data": "cm_address" },
                    { "data": 'action'},
                ],
                "pageLength" : 5,
                "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
                "order": [ [0, 'asc'] ]
            });
            
            $('#mainConsumer').addClass('has_data')
        }
        $('#mainConsumer').css('display','block')
    })
    $(document).on('click','#mainConsumer .mAselect',function(){
        
        let urlUpdate = "{{route('api.consumers.select',':id')}}";
        urlUpdate = urlUpdate.replace(':id',$(this).val());
        $.ajax({
            url: urlUpdate,
            type: "GET",
            dataType: "json",
            success: function(data){
                $('#mainConsumer').hide()
                $('#main_account').val(data.cm_full_name)
                $('#mA_hidden').val(data.cm_id)
            }
        })
    })
    $("#wizard-picture").change(function(){
        readURL(this);
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
            }
            reader.readAsDataURL(input.files[0]);
        }           
	}
    $('#near_consumer').on('click',function(){
        if(!$('#nearestConsumer').hasClass('has_data')){
            $('#nearest_consumer').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{route('api.consumers.main.account')}}",
                "columns": [
                    { "data": "cm_id" },
                    { "data": "cm_account_no" },
                    { "data": "cm_full_name" },
                    { "data": "cm_address" },
                    { "data": 'action'},
                ],
                "pageLength" : 5,
                "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
                "order": [ [0, 'asc'] ]
            });
            
            $('#nearestConsumer').addClass('has_data')
        }
        $('#nearestConsumer').css('display','block')
    })
    $(document).on('click','#nearestConsumer .mAselect',function(){
        
        let urlUpdate = "{{route('api.consumers.select',':id')}}";
        urlUpdate = urlUpdate.replace(':id',$(this).val());
        $.ajax({
            url: urlUpdate,
            type: "GET",
            dataType: "json",
            success: function(data){
                $('#nearestConsumer').hide()
                $('#near_consumer').val(data.cm_full_name)
                $('#nC_hidden').val(data.cm_id)
            }
        })
    })
    $('#meter_no').on('click',function(){
        if(!$('#metered').hasClass('has_data')){
            $('#metered_table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{route('api.meter.master.get')}}",
                "columns": [
                    { "data": "mm_id" },
                    { "data": "mm_serial_no" },
                    { "data": 'action'},
                ],
                "pageLength" : 5,
                "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
                "order": [ [0, 'desc'] ]
            });
            
            $('#metered').addClass('has_data')
        }
        $('#metered').css('display','block')
    })
    $(document).on('click','.create_meter',function(){
        
        $('#create_meter button').removeClass('save_meter_change')
        $('#create_meter button').addClass('save_meter')
        $('#create_meter').css('display','block')
    })
    $(document).on('click','.save_meter',function(){
        $.ajax({
            type: "POST",
            data: {
                mm_serial_no: $('#serial').val(),
                mm_serial_no: $('#serial').val(),
                mm_brand: $('#brand_id').val(),
                mm_side_seal:$('#side_seal').val(),
                mm_terminal_seal: $('#terminal_seal').val(),
                mm_catalog_number: $('#cataglog_no').val(),
                mm_class: $('#class').val(),
                mm_demand_type: $('#demand_type').val(),
                mm_kwh_multiplier: $('#multiplier_kwh').val(),
                mm_owner: $('#owner').val(),
                mm_logo_seal: $('#logo_seal').val(),
                mm_erc_seal: $('#erc_seal').val(),
                mm_prev_energy_rdg: $('#prev_energy_reading_kwh').val(),
                mm_prev_demand_rdng: $('#prev_dem_reading_kw').val(),
                mm_min_dem_kw: $('#min_demand_kwh').val(),
                mm_max_dem_kw: $('#max_demand_kwh').val(),
                mm_accuracy_perc: $('#accuracy').val(),
                mm_asfound: $('#as_found').val(),
                mm_asleft: $('#as_left').val(),
                mm_prev_kvarh_rdng: $('#prev_kvarh').val(),
                mm_kw_multiplier: $('#multiplier_kw').val(),
                mm_kvar_multiplier: $('#multi_kvar').val(),
            },
            url: "{{route('api.meter.master.store')}}",
            datatype: "json",
            success: function(data){
                Swal.fire({
                    icon: 'success',
                    title: 'Successfully Saved!',
                    showConfirmButton: false,
                    timer: 2500
                })
                $('#create_meter').hide()
                $('#metered_table').DataTable().ajax.reload();
            }
        })
    })
    $(document).on('click','.create_change_meter',function(){
        
        $('#create_meter button').removeClass('save_meter')
        $('#create_meter button').addClass('save_meter_change')
        $('#create_meter').css('display','block')
    })
    $(document).on('click','.save_meter_change',function(){
        $.ajax({
            type: "POST",
            data: {
                mm_serial_no: $('#serial').val(),
            },
            url: "{{route('api.meter.master.store')}}",
            datatype: "json",
            success: function(data){
                Swal.fire({
                    icon: 'success',
                    title: 'Successfully Saved!',
                    showConfirmButton: false,
                    timer: 2500
                })
                $('#create_meter').hide()
                $('#change_meter_table').DataTable().ajax.reload();
            }
        })
    })
    $(document).on('click','.meterMasterSelect',function(){
        let urlUpdate = "{{route('api.meter.master.select',':id')}}";
        urlUpdate = urlUpdate.replace(':id',$(this).val());
        $.ajax({
            url: urlUpdate,
            type: "GET",
            dataType: "json",
            success: function(data){
                $('#metered').hide()
                $('#meter_no').val(data.mm_serial_no)
                $('#mm_hidden').val(data.mm_id)
            }
        })
    })
/*-------- save the consumer form ------*/
$('#save_consumer').on('click',function(){
    $.ajax({
        type: "post",
        url: "{{route('api.consumer.store')}}",
        dataType: "json",
        data: {
            rc_id: $('#rc_hidden').val(),
            ct_id: $('#ct_hidden').val(),
            tin: $('#tin').val(),
            cm_last_name: $('#family_name').val(),
            cm_first_name: $('#first_name').val(),
            cm_middle_name: $('#middle_name').val(),
            cm_full_name: $('#cm_fullname').val(),
            cm_address: $('#cm_address').val(),
            cm_birthdate: $('#bdate').val(),
            employee: $('#employee_r:checked').val(),
            temp_connect: $('#tc_r:checked').val(),
            senior_citizen: $('#sc_r:checked').val(),
            institutional: $('#ins_r:checked').val(),
            metered: $('input[type="radio"][name="metered"]:checked').val(),
            govt: $('input[type="radio"][name="gov_account"]:checked').val(),
            main_accnt: $('#mA_hidden').val(),
            large_load: $('input[type="radio"][name="l_load"]:checked').val(),
            nearest_cons: $('#nC_hidden').val(),
            occupant: $('#occupant').val(),
            board_res: $('#board_reso').val(),
            mm_id: $('#mm_hidden').val(),
            cm_account_no: $('#account_no').val(),
            main_accnt: $('input[type="radio"][name="m_account"]:checked').val(),
            cm_seq_no: $('#seq_no').val(),
            cm_lgu5: $('#five_perc').val(),
            cm_lgu2: $('#two_perc').val(),
            extension_name: $('#extension_name').val(),
            block_no: $('#block_no').val(),
            purok_no: $('#purok_no').val(),
            lot_no: $('#lot_no').val(),
            sitio: $('#sitio').val(),
            pending: 1,
            mm_master: $('#meter_no').val(),
            teller_user_id: $('#user_id').val(),
        },
        success:function(data){
            
            $('#datatable').DataTable().ajax.reload();
            $('#save_consumer').attr('disabled',true)
            Swal.fire(
                'Success!',
                'Consumer information was saved!',
                'success'
            )
            setTimeout(function() {
                $('#save_consumer').attr('disabled',false)
            }, 5000);
            $('#wizardPicturePreview').attr('src', "{{asset('img/placeholder-person.png')}}")
            $('#createConsumer input[type=text]').val("")
            $('#createConsumer input[type=date]').val("")
            saveImage()
        },
        error: function(error) {
            var errorList = []
            var errorHtmlList = ''
            if(error.responseJSON.errors.rc_id){
                errorList.push("<li>Route/Barangay is required</li>")
                $('#route').addClass('required_field')
                errorHtmlList += "<li style='list-style-type: none;color:red'>Route/Barangay is required</li>"
            }
            if(error.responseJSON.errors.ct_id){
                errorList.push("<li>Consumer type is required</li>")
                $('#consumer_type').addClass('required_field')
                errorHtmlList += "<li style='list-style-type: none;color:red'>Consumer type is required</li>"
            }
            if(error.responseJSON.errors.cm_last_name){
                errorList.push("<li>Family name is required</li>")
                $('#family_name').addClass('required_field')
                errorHtmlList += "<li style='list-style-type: none;color:red'>Family name is required</li>"
            }
            if(error.responseJSON.errors.cm_first_name){
                errorList.push("<li>First name is required</li>")
                $('#first_name').addClass('required_field')
                errorHtmlList += "<li style='list-style-type: none;color:red;'>First name is required</li>"
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
function saveImage(){
    var files = $('#wizard-picture')[0].files;
    var fd = new FormData();
    fd.append('file',files[0]);
    $.ajax({
        url: "{{route('api.consumers.update.image')}}",
        method: 'post',
        data: fd,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(data){
            return true;
        },
        error: function(error){
            return false
        }
    })
}
/****------end**/
$('#family_name').change(function(){
    if($(this).hasClass('required_field')){
        $(this).removeClass('required_field')
    }
})
$('#first_name').change(function(){
    if($(this).hasClass('required_field')){
        $(this).removeClass('required_field')
    }
})
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
/*** change meter **/
var changeMeterConsId = '';
var meterMasterSelect = '';
$(document).on('click','.change-meter',function(){
    changeMeterConsId = $(this).val()
    if(!$('#change_meter').hasClass('has_data')){
        $('#change_meter_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('api.meter.master.get')}}",
            "columns": [
                { "data": "mm_id" },
                { "data": "mm_serial_no" },
                { "data": 'action'},
            ],
            "pageLength" : 5,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ]
        });
        
        $('#change_meter').addClass('has_data')
    }
    $('#change_meter').css('display','block')
})
//history log
var historyLogId = '';
$(document).on('click','.history-log',function(){
    historyLogId = $(this).val()
    let urlUpdate = "{{route('api.cons.master.get',':id')}}";
    urlUpdate = urlUpdate.replace(':id', historyLogId);

    if(!$('#history_log').hasClass('has_data')){
        $('#history_log_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": urlUpdate,
            "columns": [
                { "data": "com_type" },
                { "data": "com_old_info" },
                { "data": 'com_new_info'},
                { "data": 'com_date'}
            ],
            "pageLength" : 10,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ]
        });
        
        $('#history_log').addClass('has_data')
    }
    $('#history_log').css('display','block')
})

//add meter to consumer
$(document).on('click','.add-meter',function(){
    changeMeterConsId = $(this).val()
    if(!$('#add_meter').hasClass('has_data')){
        $('#add_meter_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('api.meter.master.get')}}",
            "columns": [
                { "data": "mm_id" },
                { "data": "mm_serial_no" },
                { "data": 'action'},
            ],
            "pageLength" : 5,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ]
        });
        
        $('#add_meter').addClass('has_data')
    }
    $('#add_meter').css('display','block')
})
$('#old_pres_reading').focusout(function(){
    let pres_reading = parseFloat($(this).val())
    let prev_reading = parseFloat($('#old_prev_reading').val())
    let diff = pres_reading - prev_reading
    if(pres_reading < prev_reading){
        $(this).css('border-color','red')
        if($('.error-message').length == 0){
            $("<span style='color:red' class='error-message'>Present reading must not be less than previous reading</span>").insertAfter($(this));
        }
        diff = parseFloat('0')
    }
    $('#old_final_reading').val(diff)
})
$('#old_pres_reading').focus(function(){
    $('.error-message').remove()
    $(this).css('border-color','rgba(206,212,218)')
})

$(document).on('click','#change_meter .meterMasterSelect',function(){
    meterMasterSelect = $(this).val()
    $('#change_meter_remarks input[type=number]').val("")
    $('#change_meter_remarks #changerMeterRemarks').val("")
    $('#change_meter_remarks input[type=month]').val("")
    //check previous reading meter reg
    $.ajax({
        url: "{{route('api.get_consumer_prev_reading.index')}}",
        method: "post",
        dataType: "json",
        data: {
            cons_id: changeMeterConsId,
        },
        success:function(data){
            $('#change_meter_remarks').css('display','block')
            $('#old_prev_reading').val(data.mr_prev_reading)
            
        },
        error: function(error){
            console.log(error)
        }
    })
    
})
$(document).on('click','#add_meter .meterMasterSelect',function(){
    meterMasterSelect = $(this).val()
    let url = "{{route('api.consumers.add.meter',':id')}}"
    let urlUpdate = url.replace(":id",changeMeterConsId)
    $.ajax({
        url: urlUpdate,
        method: "patch",
        dataType: "json",
        data: {
            meter: meterMasterSelect
        },
        success: function(data){
            Swal.fire(
                'Success!',
                'Consumer meter was added!',
                'success'
            )
        },
        error:function(error){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
            })
        }
    })
})
$(document).on('click','.submit_meter_change', function(){
    let urlUpdate = "{{route('api.consumers.meter.change',':id')}}";
    urlUpdate = urlUpdate.replace(':id',meterMasterSelect);
    $.ajax({
        url: urlUpdate,
        type: "patch",
        data:{
            cons_id: changeMeterConsId,
            remarks: $('#changerMeterRemarks').val()
        },
        dataType: "json",
        success: function(data){
            $('#change_meter_remarks').val("")
            $('#change_meter_remarks').hide()
            $('#change_meter').hide()
            Swal.fire(
                'Success!',
                'Consumer meter was updated!',
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
var consumerId = '';
$(document).on('click','.change-name',function(){
    consumerId = $(this).val()
    $('#old-name').val($(this).attr('id'))
    $('#change_name').css('display','block')
})
$(document).on('click','.save_new_name',function(){
    var url = "{{route('api.consumers.change.name',':id')}}"
    var urlUpdate = url.replace(':id',consumerId)
    $.ajax({
        url: urlUpdate,
        dataType: "json",
        data: {
            first_name: $('#first-name').val(),
            middle_name: $('#middle-name').val(),
            last_name: $('#family-name').val()
        },
        method: "PATCH",
        success: function(data){
            $('#family-name').val("")
            $('#first-name').val("")
            $('#middle-name').val("")
            $('#change_name').css('display','none')
            $('#datatable').DataTable().ajax.reload();
            Swal.fire(
                'Success!',
                'Consumer name was updated!',
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
$(document).on('click','.change-type',function(){
    consumerId = $(this).val()
    if(!$('#consumer_type_select').hasClass('appended_option')){
        $.ajax({
            url: "{{route('api.consumerType.withoutpaginate')}}",
            dataType:"json",
            method: "get",
            success:function(data){
                for (const key in data) {
                    if (data.hasOwnProperty(key)) {
                        data[key].forEach(element => {
                            $('#consumer_type_select').append('<option value="'+element.cons_type_id+'">'+element.cons_type_desc+'</option>')
                        });
                    }
                }
                $('#consumer_type_select').addClass('appended_option')
            },
            error: function(error){
            }
        })
    }
    $('#old-type').val($(this).attr('id'))
    $('#change_type').css('display','block')
})
$(document).on('click','.save_new_type',function(){
    var url = "{{route('api.consumers.change.type',':id')}}"
    var urlUpdate = url.replace(':id',consumerId)
    $.ajax({
        url: urlUpdate,
        dataType:"json",
        data: {
            ct_id: $('#consumer_type_select').find(':selected').val(),
            remarks: $('#consumer_type_remark').val()
        },
        method: "patch",
        success: function(data){
            $('#change_type').css('display','none')
            $('#datatable').DataTable().ajax.reload();
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
$(document).on('click','.brand',function(){
    if(!$('#show_meter_brand').hasClass('has_data')){
        $('#meter_brand').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('api.meter_brand.get')}}",
            "columns": [
                { "data": "mb_id" },
                { "data": "mb_code" },
                { "data": "mb_name" },
                { "data": 'action'},
            ],
            "pageLength" : 5,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ]
        });
        
    $('#show_meter_brand').addClass('has_data')
    }
    $('#show_meter_brand').show()
})
$(document).on('click','.create_meter_brand',function(){
    $('#create_brand').show()
})

/*----select brand---*/
$(document).on('click','.meterBrandSelect',function(){
    let urlUpdate = "{{route('api.meter_brand.select',':id')}}";
    urlUpdate = urlUpdate.replace(':id',$(this).val());
    $.ajax({
        url: urlUpdate,
        type: "GET",
        dataType: "json",
        success: function(data){
            $('#show_meter_brand').hide()
            $('#brand').val(data.mb_name)
            $('#brand_id').val(data.mb_id)
        }
    })
})
$(document).on('click','.save_meter_brand',function(){
    $.ajax({
        url: "{{route('api.meter_brand.store')}}",
        dataType:"json",
        data: {
            mb_code: $('#brand_code').val(),
            mb_name: $('#brand_name').val(),
        },
        method: "post",
        success: function(data){
            $('#create_brand input["type=text"]').val('')
            $('#create_brand').css('display','none')
            $('#meter_brand').DataTable().ajax.reload();
            Swal.fire(
                'Success!',
                'Brand was added!',
                'success'
            )
        },
        error: function(error){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
            })
            if(error.responseJSON.errors.mb_code){
                $('#brand_code').addClass('required_field')
                var errorText = error.responseJSON.errors.mb_code[0]
                $('#brand_code').after('<span style="font-size:12px;color:red">'+errorText+'</span>')
            }
            if(error.responseJSON.errors.mb_name){
                $('#brand_name').addClass('required_field')
                var errorText = error.responseJSON.errors.mb_nme[0]
                $('#brand_name').after('<span style="font-size:12px;color:red">'+errorText+'</span>')
            }
        }
    })
})
/*----- consumer modify ----*/
/*----- consumer modify ----*/
$(document).on('click','.modify',function(){
    var url = "{{route('api.consumers.select',':id')}}"
    var urlUpdate = url.replace(':id',$(this).val())
    cm_id = $(this).val()
    var first_name
    var middle_name
    var last_name
    var extension_name
    $.ajax({
        url: urlUpdate,
        method: "get",
        dataType: "json",
        success: function(data){
            console.log(data)
            console.log(data.metered)
            $('#rc_hidden').val(data.rc_id)
            $('#first_name').val(data.cm_first_name)
            $('#middle_name').val(data.cm_middle_name)
            $('#family_name').val(data.cm_last_name)
            $('#extension_name').val(data.extension_name)
            $('#block_no').val(data.block_no)
            $('#purok_no').val(data.purok_no)
            $('#lot_no').val(data.lot_no)
            $('#sitio').val(data.sitio)
            $('#member_type').val(data.lot_no)
            if(data.cm_first_name != null){
                first_name = data.cm_first_name
            }
            if(data.cm_first_name == null){
                first_name = ''
            }
            if(data.cm_middle_name != null){
                middle_name = data.cm_middle_name
            }
            if(data.cm_middle_name == null){
                middle_name = ''
            }
            if(data.cm_last_name != null){
                last_name = data.cm_last_name
            }
            if(data.cm_last_name == null){
                last_name = ''
            }
            if(data.cm_extension_name != null){
                extension_name = data.cm_extension_name
            }
            if(data.cm_extension_name == null){
                extension_name = ''
            }
            $('#cm_fullname').val(data.cm_full_name)
            $('#bdate').val(data.cm_birthdate)
            if(data.cm_birthdate !== null){
                var bDate = data.cm_birthdate
                var year = bDate.substring(1,4)
                var month = bDate.substring(5,6)
                var day = bDate.substring(7,8)
            }
            $('#cm_address').val(data.cm_address)
            getRoute(data.rc_id)
            .then((response) => {
                $('#route').val(response.area[0].route_desc)
            })
            .catch((error) => {
                console.log(error)
            })
            $('#account_no').val(data.cm_account_no)
            $('#seq_no').val(data.cm_seq_no)
            $('#seq_no').attr('readonly',false)
            if(data.cm_image_url == null || data.cm_image_url === 'image_url'){
                $('#wizardPicturePreview').attr('src','{{asset("img/placeholder-person.png")}}')
            }else{
                $('#wizardPicturePreview').attr('src','{{asset("storage/consumer/profile")}}' + "/" + data.cm_image_url)
            }
            getType(data.ct_id)
            .then((response) => {
                $('#ct_hidden').val(data.ct_id)
                $('#consumer_type').val(response.ct_desc)
            })
            .catch((error) => {
                console.log(error)
            })
            $('#tin').val(data.tin)
            $('#spouse').val(data.spouse)
            if(data.employee == 1){
                $( "#employee_r" ).prop( "checked", true )
            }
            if(data.cm_voting_address == 1){
                $('#va_r').prop("checked",true)
            }
            if(data.temp_connect == 1){
                $('#tc_r').prop("checked",true)
            }
            if(data.senior_citizen == 1){
                $('#sc_r').prop("checked",true)
            }
            if(data.institutional == 1){
                $('#ins_r').prop("checked",true)
            }
            if(data.mm_id !== null){
                $('#metered_yes').prop("checked",true)
                getMeter(data.mm_id)
                .then((response) => {
                    $('#mm_hidden').val(data.mm_id)
                    $('#meter_no').val(response.mm_serial_no)
                    console.log(response.mm_serial_no)
                })
                .catch((error) => {
                    console.log(error)
                })
            }
            if(data.mm_id === null){
                $('#metered_yes').prop("checked",false)
                $('#metered_no').prop("checked",true)
                $('.not_metered').css('display','none')
            }
            if(data.main_account == 1){
                $('#m_account_y').prop("checked",true)
            }
            if(data.govt == 1){
                $("#gov_account").prop("checked",true)
            }
            if(data.special_account_type == 1){
                $("#special_r").prop("checked",true)
            }
            if(data.large_load == 1){
                $("#large_load_r").prop("checked",true)
            }
            if(data.cm_lgu5 == 1){
                $('#five_perc').prop("checked",true)
                document.querySelector('#five_perc').value = 1;
            }
            if(data.cm_lgu2 == 1){
                $('#two_perc').prop("checked",true)
                document.querySelector('#two_perc').value = 1;
            }
            $('#save_consumer').css('display','none')
            $('#update_consumer').css('display','block')
            $('#createConsumer').css('display','block')
            $('#createConsumer .modal-header h3').html('Update Consumer Form')
        },
        error: function(error){
            console.log(error)
        }
    })
})
//get consumer multiplier
$(document).on('click','.change-multiplier',function(){
    changeMultiplierConsId = $(this).val()
    let urlUpdate = "{{route('api.consumers.multiplier',':id')}}";
    urlUpdate = urlUpdate.replace(':id',changeMultiplierConsId);
    $.ajax({
        url: urlUpdate,
        method: 'get',
        dataType: 'json',
        success: function(data){
            console.log(data)
            if(data[0].cm_kwh_mult  === null){
                $('#mult_from').val(0)
            }else{
                $('#mult_from').val(data[0].cm_kwh_mult)
            }
            
        },
        error: function(error){
            console.log(error)
        }
    })
    $('#change_multiplier').css('display','block')
})
//post change meter
$(document).on('click','.updateChangeKwh',function(){
    let urlUpdate = "{{route('api.consumers.update.multiplier',':id')}}";
    urlUpdate = urlUpdate.replace(':id',changeMultiplierConsId);
    $.ajax({
        url: urlUpdate,
        method: 'PATCH',
        dataType: 'json',
        data: {
            kwh_mult: $('#mult_to').val()
        },
        success: function(data){
            Swal.fire(
                'Success!',
                'Consumer kwh multiplier has updated!',
                'success'
            )
        },
        error: function(error){
            console.log(error)
        }
    })
})
/*** update consumer **/
$(document).on('click','#update_consumer',function(){
    console.log(cm_id)
    var url = "{{route('api.consumers.modify',':id')}}"
    var urlUpdate = url.replace(':id',cm_id)
    $.ajax({
        url: urlUpdate,
        dataType: "json",
        method: "patch",
        data: {
            rc_id: $('#rc_hidden').val(),
            ct_id: $('#ct_hidden').val(),
            tin: $('#tin').val(),
            cm_last_name: $('#family_name').val(),
            cm_first_name: $('#first_name').val(),
            cm_middle_name: $('#middle_name').val(),
            cm_full_name: $('#cm_fullname').val(),
            cm_address: $('#cm_address').val(),
            cm_birthdate: $('#bdate').val(),
            employee: $('#employee_r:checked').val(),
            temp_connect: $('#tc_r:checked').val(),
            senior_citizen: $('#sc_r:checked').val(),
            institutional: $('#ins_r:checked').val(),
            metered: $('input[type="radio"][name="metered"]:checked').val(),
            govt: $('input[type="radio"][name="gov_account"]:checked').val(),
            main_accnt: $('#mA_hidden').val(),
            large_load: $('input[type="radio"][name="l_load"]:checked').val(),
            nearest_cons: $('#nC_hidden').val(),
            occupant: $('#occupant').val(),
            board_res: $('#board_reso').val(),
            mm_id: $('#mm_hidden').val(),
            cm_account_no: $('#account_no').val(),
            main_accnt: $('input[type="radio"][name="m_account"]:checked').val(),
            cm_seq_no: $('#seq_no').val(),
            cm_lgu5: $('#five_perc').val(),
            cm_lgu2: $('#two_perc').val(),
            extension_name: $('#extension_name').val(),
            block_no: $('#block_no').val(),
            purok_no: $('#purok_no').val(),
            lot_no: $('#lot_no').val(),
            sitio: $('#sitio').val(),
            mm_master:  $('#meter_no').val(),
            modify_by: $('#user_id').val()
        },
        success: function(data){
            $('#datatable').DataTable().ajax.reload();
            Swal.fire(
                'Success!',
                'Consumer information was modified!',
                'success'
            )
            $('#wizardPicturePreview').attr('src', "{{asset('img/placeholder-person.png')}}")
            $('#createConsumer input[type=text]').val("")
            $('#createConsumer input[type=date]').val("")
            $('#createConsumer').css('display','none')
            saveImage()
            setTimeout(function() {location.reload()}, 1500);
        },
        error:function(error){
            console.log(error)
        }
    })
})
function getMeter(meterId){
    var url = "{{route('api.meter.master.select',':id')}}"
    var urlUpdate = url.replace(":id",meterId)
    return new Promise((resolve,reject) => {
        $.ajax({
            url: urlUpdate,
            method: "get",
            dataType: "json",
            success: function(data){
                resolve(data)
            },
            error: function(error){
                reject(error)
            }
        })
    })
}
function getRoute(routeId){
    var url = "{{route('api.routes.select',':id')}}"
    var urlUpdate = url.replace(':id',routeId)
    return new Promise((resolve,reject) => {
        $.ajax({
            url: urlUpdate,
            method: "get",
            dataType: "json",
            success: function(data){
                resolve(data)
            },
            error: function(error){
                reject(error)
            }
        })
    })    
}
function getType(typeId){
    var url = "{{route('api.consumerType.select',':id')}}"
    var urlUpdate = url.replace(':id',typeId)
    return new Promise((resolve,reject) => {
        $.ajax({
            url: urlUpdate,
            method: "get",
            dataType: "json",
            success: function(data){
                resolve(data)
            },
            error: function(error){
                reject(error)
            }
        })
    })
}
$(document).on('click','#five_perc',function(){
    if($(this).is(':checked')){
        $(this).val(1)
    }else{
        $(this).val(0)
    }
})
$(document).on('click','#two_perc',function(){
    if($(this).is(':checked')){
        $(this).val(1)
    }else{
        $(this).val(0)
    }
})
$(document).on('click','#closeHistLog',function(){
    location.reload();
})
function remarks(id){
   document.querySelector('#add_remarks').style.display="block"; 
   cmcm_id = id;
  
}
function subRemarks(event){
    event.preventDefault();
    var a = document.querySelector('#remarks').value;
    var tosend = new Object();
    tosend.cons_id = cmcm_id;
    tosend.remarks = a;

    var route = "{{route('consumer.notify')}}";
    var xhr = new XMLHttpRequest();
            xhr.open('POST',route, true);
            xhr.setRequestHeader("Accept", "application/json");
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.send(JSON.stringify(tosend));
            xhr.onload = function() {
                if(this.status == 200){
                    var data = JSON.parse(this.responseText);

                    var message = data.Message;
                    Swal.fire({
                                title: 'Success!',
                                text: '"'+ message +'"',
                                icon: 'success',
                                confirmButtonText: 'close'
                            })
                }
            }
            

    return false;
}
</script>
