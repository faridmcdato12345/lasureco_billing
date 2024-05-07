@extends('layout.master')
@section('title', 'Summary of Canceled Bills')
@section('content')

<style>
    input {
        cursor: pointer;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #routeCodeTable {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #routeCodeTable td{
        height: 45px;
        border-bottom: 1px #ddd solid;
    }
    .routeDiv {
        height: 250px;
        margin-top: 1%; 
    }
    #printBtn {
        margin-top: 10%;
        margin-right: 2.8%;
        display: none;
    }
    #paginate {
        width: 100%;
        margin: auto;
        margin-top: 0.5%;
    }
    #paginate button {
        background-color: royalblue;
        border-radius: 3px;
        height: 35px;
        width: 100%; 
    }
    #paginate input {
        margin: auto;
    }
</style>

<p class="contentheader"> Summary of Canceled Bills </p>
<div class="main">
    <table class="content-table" style="margin-top: 5%;">
        <tr>
            <td style="width: 12%;">
                Bill Period:
            </td>
            <td>
                <input type="month" id="billPeriod">
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <button id="printBtn" onclick="printSummBills()">Print</button>
             </td>
        </tr>
    </table>
</div>

@include('include.modal.routemodal')

<script>
    var billDate = document.querySelector("#billPeriod");

    billDate.addEventListener("change", function(){
        if(billDate.value !== ""){
            document.querySelector("#printBtn").style.display = "block";
        } else{
            document.querySelector("#printBtn").style.display = "none";
        }
    })

    function printSummBills(){
       var billPeriod = document.querySelector("#billPeriod").value;

        const toSend = {
            'billPeriod': billPeriod
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_summary_of_canceled_bills")}}'
        window.open($url);
    }
</script>
@endsection