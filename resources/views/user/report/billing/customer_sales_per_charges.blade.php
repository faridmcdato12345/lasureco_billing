@extends('layout.master')
@section('title', 'Customer Sales per Charges')
@section('content')

<style>
    #printBtn {
        height: 45px;
        font-size: 18px;
        float: right;
        margin-right: 7.5%;
        border-radius: 3px;
        margin-top: 3%;
        display: none;
    }
    #month {
        cursor: pointer;
    }
</style>

<p class="contentheader">Customer Sales per Charges</p>

<div class="main">
    <table class="content-table">
        <tr>
            <td style="width: 13%;">
                Billing Period
            </td>
            <td>
                <input type="month" id="month">
            </td>
        </tr>
    </table>
    <button id="printBtn" onclick="printSales()"> Print </button>
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
        document.querySelector("#printBtn").style.display = "block";
    })

    function printSales(){
        var month = document.querySelector("#month").value;
       
        const toStore = {
            "date": month,
        }

        localStorage.setItem('data', JSON.stringify(toStore));

        $url = '{{route("print_customer_sales_per_charges")}}'
        window.open($url);
        
    }
</script>
@endsection
