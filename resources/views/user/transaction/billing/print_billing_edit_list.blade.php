@extends('layout.master')
@section('title', 'Print Billing Edit List')
@section('content')

<style>
    input {
        cursor: pointer;
    }
    #printBtn {
        float: right;
        margin-top: 7%; 
        margin-right: 2.6%; 
        display: none;
    }
</style>

<p class="contentheader">Print Billing Edit List</p>
<div class="main">
    <br><br>
    <table class="content-table">
        <tr>
            <td style="width: 15%;">
                Route Code:
            </td>
            <td>
                <input type="text" id="routeInp" onclick="showRoutes()" placeholder="Select Route" readonly>
                <input type="text" id="routeId" hidden>
            <td>
        </tr>
        <tr>
            <td style="height: 100px;"> &nbsp; </td>
        </tr>
        <tr>
            <td>
                Billing Period:
            </td>
            <td>
                <input id="month" type="month" disabled>
            </td>
        </tr>
        <tr>
            <td style="height: 50px;"> &nbsp; </td>
        </tr>
        <tr>
            <td colspan=2>
                <button id="printBtn" onclick="printBillList()"> Print </button>
            </td>
        </tr>
    </table>
</div>

@include('include.modal.routemodal')
@include('include.modal.meterReaderModal')

<script>
    function setRoute(a){
        var routeId = a.id;
        var routeName = a.getAttribute('name');
        var routeCode = a.getAttribute('code');

        document.querySelector("#routeInp").value = routeCode + " - " + routeName;
        document.querySelector("#routeId").value = routeId;
        document.querySelector("#routeCodes").style.display = "none";
        document.querySelector("#month").disabled = false;
        document.querySelector("#month").focus();
    }

    var month = document.querySelector("#month");
    month.addEventListener("change", function(){
        if(month.value !== ""){
            document.querySelector("#printBtn").style.display = "block";
        } else {
            document.querySelector("#printBtn").style.display = "none";
        }
    })

    function setMeterReader(x){
        var meterId = x.id;
        var meterReader = x.getAttribute('meterNames');

        document.querySelector("#meterReaderInp").value = meterReader;
        document.querySelector("#meterReaderId").value = meterId;
        document.querySelector("#meterReader").style.display = "none";
        document.querySelector("#printBtn").style.display = "block";
    }

    function printBillList(){
        var routeId = document.querySelector("#routeId").value;
        var month = document.querySelector("#month").value;

        const toSend = {
            'routeId': routeId,
            'billPeriod': month
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_edit_billing_list")}}'
        window.open($url);
    }
</script>
@endsection
