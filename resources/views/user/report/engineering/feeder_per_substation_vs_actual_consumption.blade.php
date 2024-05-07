@extends('layout.master')
@section('title', 'Feeder per Substation vs Actual Consumption')
@section('content')

<p class="contentheader">Feeder per Substation vs Actual Consumption</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Substation Code:
            </td>
            <td class="input-td">
                <input  type="text" class ="input-Txt" href = "#accNo" name="area">
            </td>
            <td  class="thead">
                For the Month of:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
        </tr>
        <tr>
            <td  class="thead">
                Feeder Code From:
            </td>
            <td class="input-td">
                <input  type="text" class ="input-Txt" href = "#route" name="area" readonly>
            </td>
            <td  class="thead">
                Feeder Code To:
            </td>
            <td class="input-td">
                <input  type="text" class ="input-Txt" href = "#route" name="area" readonly>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <button style="width:70px;margin-top:30px;height:40px;" id="printBtn" >Print</button>
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
        c[21].style.color="blue";
    }
</script>
@endsection
