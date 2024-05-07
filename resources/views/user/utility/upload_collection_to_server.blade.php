@extends('layout.master')
@section('title', 'Print Meter Reading Sheet')
@section('stylesheet')
<style>
   .text-percent{
        position: absolute;
        margin-left: 38%;
        color: #000;
   }
</style>
@endsection
@section('content')
<p class="contentheader">Upload Collection to Server</p>
<div class="main">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="by-teller" data-toggle="tab" href="#byteller" role="tab" aria-controls="by-teller" aria-selected="true">By Teller</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="all-tab" data-toggle="tab" href="#alltab" role="tab" aria-controls="all-tab" aria-selected="false">All</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="byteller" role="tabpanel" aria-labelledby="by-teller">
            <form id="store_collection">
                <div class="form-group">
                    <label for="date">From date:</label>
                    <input type="date" name="from_date" id="from_date" class="form-control">
                </div>
                <div class="form-group">
                    <label for="date">To date:</label>
                    <input type="date" name="to_date" id="to_date" class="form-control">
                </div>
                <div class="form-group">
                    <label for="teller">Teller</label>
                    <select name="user_id" id="user_id" class="form-control">
                        @forelse ($users as $user)
                            <option value="{{$user->emp_no}}">{{$user->user_full_name}}</option>
                        @empty
                        <option value="">No data</option>    
                        @endforelse
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" value="Upload" class="form-control btn btn-primary upload_button" >
                </div>
            </form>
            <div class="" id="process">
                <div class="progress">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="">
                    <span class="text-percent">0%</span>
                </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="alltab" role="tabpanel" aria-labelledby="all-tab">
            <form action="" method="post">
                @csrf
                <div class="form-group">
                    <label for="date">To date:</label>
                    <input type="date" name="to_date" id="date" class="form-control">
                </div>
                <div class="form-group">
                    <label for="date">From date:</label>
                    <input type="date" name="from_date" id="date" class="form-control">
                </div>
                <div class="form-group">
                    <input type="submit" value="Upload" class="form-control btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        var ip_address
        getServerIpAddress()
        .then((response) => {
            ip_address = response.ip_address
        })
        .catch((error) => {
            console.log(error)
        })
        
        $('#store_collection').submit(function(e){
            e.preventDefault()
            $.ajax({
                url: "{{route('api.upload_collection_server.get')}}",
                method: "post",
                dataType: "json",
                data: {
                    from_date: $('#from_date').val(),
                    to_date: $('#to_date').val(),
                    user_id: $("#user_id option:selected").val()
                },
                success: function(data){
                    console.log(typeof(data))
                    if (localStorage.getItem("collection") !== null) {
                        localStorage.removeItem("collection")
                        localStorage.setItem("collection", JSON.stringify(data));
                        collection_local_storage(ip_address)
                    }
                    else{
                        localStorage.setItem("collection", JSON.stringify(data));
                        collection_local_storage(ip_address)
                    }
                },
                error: function(error){
                    Swal.fire({
                        icon: 'error',
                        title: 'Collection not found!',
                        text: 'No collection for the given inputs!',
                    })
                }
            })
        })
    })
    function collection_local_storage(ip_address){
        var collectionLocalStorage = localStorage.getItem('collection')
        if(collectionLocalStorage !== null){
            var collectionJson = JSON.parse(collectionLocalStorage)
            var x = 0
            var myInterval = setInterval(function(){
                if(x < collectionJson.length){
                    $.ajax({
                        url: "http://"+ip_address+"/lasureco_billing/public/api/v1/find_data_to_server",
                        method: "post",
                        dataType: "json",
                        data: {
                            bill_no: collectionJson[x].s_bill_no
                        },
                        success: function(data){
                           if(Object.keys(data).length === 0){
                                insertToServerWhenEmpty(data,ip_address)
                                .then((response) => {
                                    console.log("if x: " + x)
                                    $('.upload_button').attr('disabled','true')
                                    var percentage = (x/collectionJson.length) * 100
                                    $('.text-percent').text(percentage.toFixed(2) + '%')
                                    $('.progress-bar').css('width', percentage + '%');
                                })
                                .catch((error) => {
                                    x = collectionJson.length
                                    if(x == collectionJson.length){
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Network Error',
                                            text: 'Disconnected to server',
                                        })
                                        $('.upload_button').removeAttr('disabled')
                                        $('.progress-bar').css('width', '0%');
                                        $('.text-percent').text('0%')
                                        dateFieldNull()
                                        clearInterval(myInterval)
                                    }
                                })
                                x++
                           }else{
                            insertToServerWhenNotEmpty(data,ip_address)
                            .then((response) => {
                                console.log("else x: " + x)
                                $.ajax({
                                    url:"http://"+ip_address+"/lasureco_billing/public/api/v1/insert_ewallet_log_server",
                                    method: "post",
                                    dataType: "json",
                                    data: {
                                        ew_id: data[0]['s_id'],
                                        ewl_amount: data[0]['e_wallet_added'] + data[0]['s_or_amount'],
                                        ewl_remark: 'Added e-wallet because of double payment from offile/mobile tellers',
                                        ewl_status: 'U',
                                        ewl_or: data[0]['s_or_num'],
                                        ewl_or_date: null,
                                        ewl_ap_billing: null,
                                        ewl_date: null,
                                        ewl_ap_billing_user_id: null,
                                    },
                                    success:function(data){
                                        console.log(data)
                                        $('.upload_button').attr('disabled','true')
                                        var percentage = (x/collectionJson.length) * 100
                                        $('.text-percent').text(percentage.toFixed(2) + '%')
                                        $('.progress-bar').css('width', percentage + '%');
                                    },
                                    error:function(error){
                                        console.log(error)
                                    }
                                })
                            })
                            .catch((error) => {
                                x = collectionJson.length
                                console.log(error)
                                if(x == collectionJson.length){
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Network Error',
                                        text: 'Disconnected to server',
                                    })
                                    $('.upload_button').removeAttr('disabled')
                                    $('.progress-bar').css('width', '0%');
                                    $('.text-percent').text('0%')
                                    dateFieldNull()
                                    clearInterval(myInterval)
                                }
                            })
                           
                            x++
                           }
                        },
                        error: function(error){
                            x = collectionJson.length
                            console.log(error)
                            if(x == collectionJson.length){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Network Error',
                                    text: 'Disconnected to server',
                                })
                                $('.upload_button').removeAttr('disabled')
                                $('.progress-bar').css('width', '0%');
                                $('.text-percent').text('0%')
                                dateFieldNull()
                                clearInterval(myInterval)
                            }
                        }
                    })
                }else{
                    Swal.fire(
                        'Success!',
                        'Collection has beed upload to server',
                        'success'
                    )  
                    $('.upload_button').removeAttr('disabled')
                    $('.progress-bar').css('width', '0%');
                    $('.text-percent').text('0%')
                    dateFieldNull()
                    clearInterval(myInterval)
                }
            },2000)
        }
    }
    function stopMyInterval(){
        clearInterval(myInterval)
    }
    function insertToServerWhenEmpty(data,ip_address){
        return new Promise((resolve,reject) => {
            $.ajax({
                url: "http://"+ip_address+"/lasureco_billing/public/api/v1/insert_to_server",
                method: "post",
                dataType: "json",
                data: {
                    mr_id: data[0]['mr_id'],
                    f_id: data[0]['f_id'],
                    ct_id: data[0]['ct_id'],
                    cons_accountno: data[0]['cons_accountno'],
                    s_or_num: data[0]['s_or_num'],
                    cm_id: data[0]['cm_id'],
                    s_or_amount: data[0]['s_or_amount'],
                    v_id: data[0]['v_id'],
                    s_bill_no: data[0]['s_bill_no'],
                    s_bill_amount: data[0]['s_bill_amount'],
                    s_bill_date: data[0]['s_bill_date'],
                    s_status: data[0]['s_status'],
                    s_mode_payment: data[0]['s_mode_payment'],
                    cheque_id: data[0]['cheque_id'],
                    s_ref_no: data[0]['s_ref_no'],
                    ref_date: data[0]['ref_date'],
                    teller_user_id: data[0]['teller_user_id'],
                    temp_teller_user_id: data[0]['temp_teller_user_id'],
                    s_ack_receipt: data[0]['s_ack_receipt'],
                    ackn_date: data[0]['ackn_date'],
                    ackn_user_id: data[0]['ackn_user_id'],
                    temp_ackn_user_id: data[0]['temp_ackn_user_id'],
                    mr_arrear: data[0]['mr_arrear'],
                    e_wallet_applied: data[0]['e_wallet_applied'],
                    e_wallet_added: data[0]['e_wallet_added'],
                    ap_status: data[0]['ap_status'],
                    deleted_at: data[0]['deleted_at'],
                    server_added: 1
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
    function insertToServerWhenNotEmpty(data,ip_address){
        return new Promise((resolve,reject) => {
            $.ajax({
                url: "http://"+ip_address+"/lasureco_billing/public/api/v1/insert_to_server",
                method: "post",
                dataType: "json",
                data: {
                    mr_id: data[0]['mr_id'],
                    f_id: data[0]['f_id'],
                    ct_id: data[0]['ct_id'],
                    cons_accountno: data[0]['cons_accountno'],
                    s_or_num: data[0]['s_or_num'],
                    cm_id: data[0]['cm_id'],
                    s_or_amount: null,
                    v_id: data[0]['v_id'],
                    s_bill_no: data[0]['s_bill_no'],
                    s_bill_amount: data[0]['s_bill_amount'],
                    s_bill_date: data[0]['s_bill_date'],
                    s_status: data[0]['s_status'],
                    s_mode_payment: data[0]['s_mode_payment'],
                    cheque_id: data[0]['cheque_id'],
                    s_ref_no: data[0]['s_ref_no'],
                    ref_date: data[0]['ref_date'],
                    teller_user_id: data[0]['teller_user_id'],
                    temp_teller_user_id: data[0]['temp_teller_user_id'],
                    s_ack_receipt: data[0]['s_ack_receipt'],
                    ackn_date: data[0]['ackn_date'],
                    ackn_user_id: data[0]['ackn_user_id'],
                    temp_ackn_user_id: data[0]['temp_ackn_user_id'],
                    mr_arrear: data[0]['mr_arrear'],
                    e_wallet_applied: data[0]['e_wallet_applied'],
                    e_wallet_added: data[0]['e_wallet_added'] + data[0]['s_or_amount'],
                    ap_status: data[0]['ap_status'],
                    deleted_at: data[0]['deleted_at'],
                    server_added: 1
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
    dateFieldNull = () => {
        $('input[type=date]').val('')
    }

    async function getServerIpAddress(){
        return new Promise((resolve,reject) => {
            $.ajax({
                url: "{{route('api.servers.get.ip')}}",
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
</script>
@endsection
