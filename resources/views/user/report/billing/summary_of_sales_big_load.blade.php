@extends('layout.master')
@section('title', 'Summary Sales Big - Load')
@section('content')

<style>
    input {
        cursor: pointer;
    }
    #printBtn {
        width: 6%;
        margin-top: 25%;
        margin-right: 2.5%;
        height: 45px;
        font-size: 20px;
    }
</style>

<p class="contentheader">Summary Sales Big - Load</p>
<div class="main">
    <table class="content-table" style="margin-top: 7%;">
        <tr>
            <td style="width: 12%;">
                Billing Period:
            </td>
            <td class="input-td">
                <input type="month" id="month" >
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <button id="printBtn" onclick="printLargeLoads()">Print</button>
            </td>
        </tr>
    </table>
</div>
<script>
    // window.onload=function(){
    //     var b = document.querySelector('#drpbtn2');
    //     b.classList.add('active');
    //     b.style.color="blue";
    //     var c = document.querySelector('.dropdown-container2').childNodes;
    //     c[5].style.color="blue";
    // }

    function printLargeLoads() {
        var billPeriod = document.querySelector("#month");

        if(billPeriod.value !== ""){
            var billingPeriod = billPeriod.value;

            const toSend = {
                'billPeriod': billingPeriod
            }
            localStorage.setItem('data', JSON.stringify(toSend));

            $url = '{{route("print_big_loads")}}'
            window.open($url);
        }
    }
</script>
@endsection
