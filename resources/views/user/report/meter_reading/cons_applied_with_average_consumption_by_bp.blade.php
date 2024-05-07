@extends('layout.master')
@section('title', 'Cons Applied with Average Consumption by BP')
@section('content')
<p class="contentheader">Cons Applied with Average Consumption by BP</p>
<div class="main">
    <table border="0" style="height:500px" class="content-table">
        <tr>
            <td class="thead">
                Area From:
            </td>
            <td style="width:30%;" class="input-td">
                <input type="text" name="area" class="input-Txt" href="#route" value="03 - Marawi City Wide and" readonly>
            </td>
            <td class="thead">
             Area To:
            </td>
            <td class="input-td">
                <input class="input-Txt" href="#meterreader" type="text" value= "43 - Marawi City Wide and">
            </td>
        </tr>
        <tr>
            <td class="thead">
                Billing Period:
            </td>
            <td class="input-td">
                <input type="month" name="bperiod">
            </td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td class="thead">
                Prepared by:
            </td>
            <td class="input-td">
                <input type="text" name="" placeholder="Prepared by" readonly>
                <input style = "margin-top:10px;" type="text" name="" placeholder="" readonly>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <button style="height:40px;" id="printBtn">Print</button>
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
        c[1].style.color="blue";
    }
</script>
@endsection
