@extends('layout.master')
@section('title', 'One time Payment')
@section('content')

<style>
    table {
        width: 89%;
        margin: auto;
        font-size: 18px;
    }
    table tr {
        height: 55px;
    }
    input {
        cursor: pointer;
    }
    ::-webkit-input-placeholder {
       text-align: left;
    }
    #route {
        text-align: left;
    }
    #payBtn  {
        float: right;
        margin-right: 2.5%;
        margin-top: 1%;
        border-radius: 3px;
        color: royalblue;
        background-color: white;
        height: 40px;
        width: 7%;
    }
</style>

<p class="contentheader">One time Payment</p>
<div class="main">
    <table>
        <tr>
            <td width='17%'> Bill Period </td>
            <td>
                <input type="month" id="billPeriod">
            </td>
        </tr>
        <tr>
            <td> No. of months </td>
            <td>
                <input type="number" id="month" placeholder="Number of months" disabled>
            </td>
        </tr>
        <tr>
            <td> Route </td>
            <td>
                <input type="text" id="route" onclick="showRoutes()" placeholder="Select Route" disabled readonly>
                <input type="text" id="routeId" hidden>
            </td>
        </tr>
        <tr>
            <td> Cheque No. </td>
            <td>
                <input type="text" id="checkNo" placeholder="Enter cheque number" disabled>
            </td>
        </tr>
        <tr>
            <td> Bank </td>
            <td>
                <input type="text" id="bank" placeholder="Enter bank name" disabled>
            </td>
        </tr>
        <tr>
            <td> Bank Account No. </td>
            <td>
                <input type="text" id="bankNo" placeholder="Enter bank account number" disabled>
            </td>
        </tr>
        <tr>
            <td> Account Name </td>
            <td>
                <input type="text" id="accountName" placeholder="Enter account name" disabled>
            </td>
        </tr>
        <tr>
            <td> Cheque Date </td>
            <td>
                <input type="date" id="chequeDate" disabled>
            </td>
        </tr>
        <tr>
            <td> OR Start </td>
            <td>
                <input type="number" id="orStart" placeholder="OR Start" disabled>
            </td>
        </tr>
        <tr>
            <td colspan=2> <button id='payBtn' onclick='pay()' disabled> Pay </button> </td>
        </tr>
    </table>
</div>

@include('include.modal.routemodal')

<script>
    function setRoute(x){
        var id = x.id;
        var code = x.getAttribute('code');
        var name = x.getAttribute('name');

        document.querySelector('#routeId').value = id;
        document.querySelector('#route').value = code + ' - ' + name;
        document.querySelector('#routeCodes').style.display = 'none';
        checkNo.disabled = false;
    }

    var month = document.querySelector("#billPeriod").value;
    var month = document.querySelector("#month");
    var route = document.querySelector("#route");
    var checkNo = document.querySelector("#checkNo");
    var bank = document.querySelector("#bank");
    var bankNo = document.querySelector("#bankNo");
    var accName = document.querySelector("#accountName");
    var chequeDate = document.querySelector("#chequeDate");
    var orStart = document.querySelector("#orStart");
    var userId = {{Auth::user()->user_id}};


    billPeriod.addEventListener('change', function(){
        if(billPeriod.value !== ""){
            month.disabled = false;
        } else {
            month.disabled = true;
        }
    })
    month.addEventListener('keyup', function(){
        if(month.value !== "") {
            route.disabled = false;
        } else {
            route.disabled = true;
        }
    })
    checkNo.addEventListener('keyup', function(){
        if(checkNo.value !== ""){
            bank.disabled = false;
        } else {
            bank.disabled = true;
        }
    })
    bank.addEventListener('keyup', function(){
        if(bank.value !== ""){
            bankNo.disabled = false;
        } else {
            bankNo.disabled = true;
        }
    })
    bankNo.addEventListener('keyup', function(){
        if(bankNo.value !== ""){
            accName.disabled = false;
        } else {
            accName.disabled = true;
        }
    })
    accName.addEventListener('keyup', function(){
        if(accName.value !== ""){
            chequeDate.disabled = false;
        } else {
            chequeDate.disabled = true;
        }
    })
    chequeDate.addEventListener('change', function(){
        if(chequeDate !== "") {
            document.querySelector("#orStart").disabled = false;
        } else {
            document.querySelector("#orStart").disabled = true;
        }
    })
    orStart.addEventListener('keyup', function(){
        if(orStart.value !== "") {
            document.querySelector("#payBtn").disabled = false;
        } else {
            document.querySelector("#payBtn").disabled = false;
        }
    })

    function pay(){
        Swal.fire({
            title: 'Do you want to proceed?',
            showDenyButton: true,
            confirmButtonText: 'Yes',
            denyButtonText: 'No',
            customClass: {
                actions: 'my-actions',
                confirmButton: 'order-2',
                denyButton: 'order-3',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                confirmPay()
            } else if (result.isDenied) {
                Swal.fire('Payment cancelled', '', 'info')
            }
        })
    }

    function confirmPay(){
        var xhr = new XMLHttpRequest();

        const toSend = {
            "bill_period_from": billPeriod.value,
            "bill_period_months": month.value,
            "route_id": document.querySelector('#routeId').value,
            "or_start": orStart.value,
            "teller_id": userId,
            "cheque_bank": bank.value,
            "cheque_bank_acc": bankNo.value,
            "cheque_acc_name": accName.value,
            "cheque_no": checkNo.value,
            "cheque_date": chequeDate.value
        }

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var oneTimePayment = "{{route('onetimepayment')}}";
        xhr.open('POST', oneTimePayment, true);
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");

        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){ 
                Swal.fire({
                    icon: 'success',
                    title: 'Payment successful.',
                    confirmButtonText: 'Yes',
                    customClass: {
                        actions: 'my-actions',
                        confirmButton: 'order-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // location.reload()
                    }
                })
            }
        }
    }
</script>
@endsection
