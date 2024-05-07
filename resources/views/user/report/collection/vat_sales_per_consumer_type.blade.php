@extends('layout.master')
@section('title', 'VAT Sales per Consumer Type')
@section('content')

<style>
    .br {
        height: 40px;
    }
    input {
        cursor: pointer;
    }
    .printBtn {
        float: right;
        color: royalblue;
        background-color: white;
        margin-top: 4%;
        margin-right: 2.7%;
        height: 40px !important;
    }
</style>

<p class="contentheader">VAT Sales per Consumer Type</p>
<div class="main">
    <table class="content-table">
        <br>
        <tr> 
            <td style="width: 15%;"> 
                Area From:
            </td>
            <td> 
            <input type="text" id="areaFrom" onclick="showArea()" placeholder="Select Area from" readonly>
            <input type="text" id="areaFromId" hidden>
            </td>
        </tr>
        <tr><td class="br"></td></tr>
        <tr>
            <td> 
                Area To:
            </td>
            <td> 
            <input type="text" id="areaTo" onclick="showArea()" placeholder="Select Area to" readonly>
            <input type="text" id="areaToId" hidden>
            </td>
        </tr>
        <tr><td class="br"></td></tr>
        <tr> 
            <td> 
                Billing Month:
            </td>
            <td> 
                <input type="month" id="month" disabled>
            </td>
        </tr>
        <tr>
            <td colspan=4> 
                <button class="printBtn" onclick="print()" disabled>
                    Print
                </button>
            </td>
        </tr>
    </table>
</div>  

@include('include.modal.areamodal')

<script>
    var setAreas = new Object();

    var areaFrom = document.querySelector("#areaFrom");
    areaFrom.addEventListener("click", function(){
        setAreas.area = areaFrom.id;
    })

    var areaTo = document.querySelector("#areaTo");
    areaTo.addEventListener("click", function(){
        setAreas.area = areaTo.id;
    })

    function setArea(a){
        var areaId = a.getAttribute('areaId');
        var areaName = a.getAttribute('areaName');

        setAreas.areaId = areaId;
        setAreas.areaName = areaName;

        if(setAreas.area == "areaFrom"){
            document.querySelector("#areaFrom").value = areaName;    
            document.querySelector("#areaFromId").value = areaId;    
        } else {
            document.querySelector("#areaTo").value = areaName;
            document.querySelector("#areaToId").value = areaId;
        }

        document.querySelector("#areaCodes").style.display = "none";
        checkAreas();
    }

    function checkAreas(){
        var areaFrom = document.querySelector("#areaFrom").value;
        var areaTo = document.querySelector("#areaTo").value;

        if(areaFrom !== "" && areaTo != ""){
            document.querySelector("#month").disabled = false;
        } else {
            document.querySelector("#month").disabled = true;
        }
    }

    var month = document.querySelector("#month");
    month.addEventListener("change", function(){
        if(month.value !== ""){
            document.querySelector(".printBtn").disabled = false;
        } else {
            document.querySelector(".printBtn").disabled = true;
        }
    })


    function print() {
        var areaFrom = document.querySelector("#areaFromId").value;
        var areaTo = document.querySelector("#areaToId").value;
        var month = document.querySelector("#month").value;

        const toSend = {
            "area_from": areaFrom,
            "area_to": areaTo,
            "date_period": month
        }
        
        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_vat_sales_per_consumer_type")}}';
        window.open($url);
    }
</script>
@endsection
