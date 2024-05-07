@extends('layout.master')
@section('title', 'Summary of Sales Unbundled for LGU')
@section('content')

<style>
    .mainTable {
        margin-top: -3%;
    }
    input, select, option {
        cursor: pointer;
    }
    #buttonPrint {
        color: royalblue; 
        background-color: white; 
        height: 40px; 
        width: 7%;
        float: right;
        margin-right: 2%;
        border-radius: 3px;
    }
</style>

<p class="contentheader">Summary of Sales Unbundled for LGU</p>
<div class="main">
    <br><br>
    <table  class="content-table">
        <tr>
            <td>
                Filter
            </td>
            <td>
                <input type="text" id="byWhat" hidden>
                <select id="filter" style="color: black; width: 98%;">
                    <option value="area" selected> Area</option>
                    <option value="town"> Town</option>
                    <option value="route"> Route</option>
                </select>
            </td>
        </tr>
        <tr>
            <td height=60px></td>
        </tr>
        <tr>
            <td style="width: 15%;">
                Bill Period:
            </td>
            <td>
                <input type="month" id="month" style="width: 98%;">
            </td>
        </tr>
        <tr><td height=60px></td></tr>
        <tr>
            <td colspan="2">
                <button id='buttonPrint' onclick="printBills()" disabled> Print </button>
            </td>
        </tr>
    </table>
</div>

<script>
    var billPeriod = document.querySelector("#month");
    billPeriod.addEventListener("change", function(){
        if(billPeriod.value !== ""){
            document.querySelector("#buttonPrint").disabled = false;
        } else {
            document.querySelector("#buttonPrint").disabled = true;
        }
    })

    function printBills(){
        var date = document.querySelector("#month").value;
        var filter = document.querySelector("#filter").value;
    
        const toSend = {
            'date': date,
            'filter': filter,
            'type': 'lgu'
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_summary_of_sales_unbundled_for_lgus")}}'
        window.open($url);
    }
</script>
@endsection
