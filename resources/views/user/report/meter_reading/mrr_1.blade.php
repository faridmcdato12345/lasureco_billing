@extends('layout.master')
@section('title', 'MRR Inquiry - 1')
@section('content')

<style>
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
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
    #bookTable {
        display: none;
        margin-top: 2.5%;
    }
    #billPeriodTable {
        display: none;
        margin-top: -1.5%;
    }

    #printBtn {
        float: right;
        margin-right: 2.9%;
        margin-top: 7%;
        font-size: 18px;
        display: none;
    }
    input {
        cursor: pointer;
    }
</style>

<p class="contentheader">MRR Inquiry - 1</p>
<div class="main">
<br><br>
    <table class="content-table" style="margin-top: -3%;">
        <tr>
            <td style="width: 12%;">
                Route:
            </td>
            <td>
                <input type="text" id="routeInp" placeholder="Select Route" onclick="showRoutes()" readonly>
                <input type="text" id="routeId" hidden>
            </td>
        </tr>
    </table>
    <table id="bookTable" class="content-table">
        <tr>
            <td style="width: 12%;">
                Book: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="number" name="Book" placeholder="Book Number">
            </td>
        </tr>
    </table>
    <table id="billPeriodTable" class="content-table">
        <tr>
            <td style="width: 12%;">
                Bill Period: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="month" id="month">
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <button id="printBtn" onclick="printMRR()">Print</button>
            </td>
        </tr>
    </table>
</div>

@include('include.modal.routemodal')

<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn2');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container2').childNodes;
        c[1].style.color="blue";
    }

    function setRouteFrom(x){
        var id = x.id;
        var name = x.getAttribute('name');
        var code = x.getAttribute('code');

        document.querySelector('#routeId').value = id;
        document.querySelector('#routeInp').value = code + " - " + name;
        document.querySelector('#routeCodes').style.display = "none";

        document.querySelector("#bookTable").style.display = "block";
        document.querySelector("#billPeriodTable").style.display = "block";
    }

    var month = document.querySelector("#month");
    
    month.addEventListener("change", function(){
        var billPeriod = month.value;

        if(billPeriod != ""){
            document.querySelector("#printBtn").style.display = "block";
        } else {
            document.querySelector("#printBtn").style.display = "none";
        }
    })

    function printMRR(){
        var routeId = document.querySelector("#routeId").value;
        var month = document.querySelector("#month").value;

        const toSend = {
            "routeId": routeId,
            "month": month
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_mrr_1")}}'
        window.open($url);
        location.reload();
    }
</script>
@endsection
