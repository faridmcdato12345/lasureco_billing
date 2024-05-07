@extends('layout.master')
@section('title', 'Lifeline Discount per Route')
@section('content')

<style>
    input {
        color: black;
        cursor: pointer;
    }
    #routeTable {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #routeTable td{
        height: 40px;
        border-bottom: 1px #ddd solid;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    .routeDiv {
        height: 250px;
    }
    #routeTbl {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #routeTbl td{
        height: 50px;
        border-bottom: 1px #ddd solid;
    }
    #printBtn {
        float: right;
        display: none;
        margin-top: 15%;
        margin-right: 3%;
    }
    #billPeriodTable {
        display: none;
        width: 90%;
        margin-top: 5%;
    }
    #paginate {
        width: 100%;
        margin: auto;
    }
    #paginate button {
        background-color: royalblue;
        border-radius: 3px;
        height: 35px;
        width: 100%; 
    }
</style>

<p class="contentheader">Lifeline Discount per Route </p>
<div class="main">
   <table class="content-table">
        <tr>
            <td style="width: 15%;">
                Route Code:
            </td>
            <td>
                <input type="text" id="routeInp" onclick="showRoutes()" placeholder="Select Route" readonly>
                <input type="text" id="routeId" hidden>
            </td>
        </tr>
    </table>
    <table class="content-table" id="billPeriodTable">
        <tr>
            <td style="width: 15%;">
                Billing Period: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 85%;">
                <input type="month" id="billPeriod">
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <button id="printBtn" onclick="printLifeLine()"> Print </button>
            </td>
        </tr>
    </table>
</div>

@include('include.modal.routemodal')

<script>
    function setRoute(x){
        var id = x.id;
        var name = x.getAttribute('name');
        var code = x.getAttribute('code');

        document.querySelector('#routeId').value = id;
        document.querySelector('#routeInp').value = code + " - " + name;
        document.querySelector('#routeCodes').style.display = "none";
        document.querySelector("#searchRoute").value = "";
        document.querySelector('#billPeriodTable').style.display = "block";
    }

    
    document.querySelector("#billPeriod").addEventListener("change", function(){
        var billDate = document.querySelector("#billPeriod").value;

        if(billDate !== ""){
            document.querySelector("#printBtn").style.display = "block";
        } else {
            document.querySelector("#printBtn").style.display = "none";
        }
    })


   

    function printLifeLine(){
        var routeId = document.querySelector('#routeId').value;
        var billDate = document.querySelector('#billPeriod').value;

        const toSend = {
            'routeId': routeId,
            'billPeriod': billDate
        }

        localStorage.setItem('data', JSON.stringify(toSend));
        $url = '{{route("print_lifeline_discount_per_route")}}'
        window.open($url);
    }
</script>
@endsection
