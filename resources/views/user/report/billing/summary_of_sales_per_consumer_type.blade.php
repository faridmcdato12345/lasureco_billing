@extends('layout.master')
@section('title', 'Summary of Sales per Consumer Type')
@section('content')

<style>
    input {
        cursor: pointer;
    }

    #printBtn {
        height: 45px;
        background-color: white;
        color: royalblue;
        font-size: 20px;
        margin-right: 8.5%;
        margin-top: 17%;
        display: none;
        float: right;
    }
</style>

<p class="contentheader">Summary of Sales per Consumer Type</p>
<div class="main">
    <table class="content-table" style="margin-top: 2%;">
        <tr>
            <td style = "width:15%;">
               Billing Period:
            </td>
            <td>
                <input style="width: 90%;" type="month" id="month" >
            </td>
        </tr>
    </table>
    <table class="content-table">
        <tr>
            <td>
                <button id="printBtn" onclick="printSales()">
                    Print
                </button>
            </td>
        </tr>
    </table>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn2');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container2').childNodes;
        c[5].style.color="blue";
    }

    var month = document.querySelector("#month");

    month.addEventListener("change", function(){
        var billPeriod = month.value;

        if(billPeriod !== "") {
            document.querySelector("#printBtn").style.display = "block";
        } else {
            document.querySelector("#printBtn").style.display = "none";
        }
    })

    function printSales(){
        var areaId = document.querySelector("#areaId").value;

        const toSend = {
            "date": billPeriod,
            "town_code_from": townFromId,
            "town_code_to": townToId,
            "area_id": areaId
        }
        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_sales_per_type_with_consumer_name")}}'
        window.open($url);
        location.reload();
    }
</script>
@endsection
