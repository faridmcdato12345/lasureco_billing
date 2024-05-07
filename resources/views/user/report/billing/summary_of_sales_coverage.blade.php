@extends('layout.master')
@section('title', 'Summary of Sales')
@section('content')

<style>
    input {
        color: black;
        cursor: pointer;
    }
    #billingPeriod {
        width: 90%;
    }
    #printButton {
        float: right;
        background-color: white;
        height: 45px;
        width: 5%;
        color: royalblue;
        font-size: 20px;
        display: none;
        margin-top: 15%;
        margin-right: 13%;
        border-radius: 3px;
    }
</style>

<p class="contentheader">Summary of Sales</p>
<div class="main">
    <table class="content-table" style="margin-top: 3.5%;">
        <tr>
             <td style="width: 15%;">
                For the Month of:
            </td>
            <td class="input-td">
                <input type="month" id="billingPeriod">
            </td>
        </tr>
    </table>
    <button id="printButton" onclick="printSalesCoverage()">
        Print
    </button>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn2');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container2').childNodes;
        c[5].style.color="blue";
    }

    var billPeriod = document.querySelector("#billingPeriod");
    billPeriod.addEventListener("change", function(){
        if(billPeriod.value !== "") {
            document.querySelector("#printButton").style.display = "block";
        } else {
            document.querySelector("#printButton").style.display = "none";
        }
    })

    function printSalesCoverage(){
        var billingPeriod = document.querySelector('#billingPeriod').value;

        const toSend = {
            'date': billingPeriod
        }
        
        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_summary_of_sales_coverage")}}'
        window.open($url);
    }
</script>
@endsection
