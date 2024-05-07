@extends('layout.master')
@section('title', 'Consumption per Type per Substation')
@section('content')

<p class="contentheader">Consumption per Type per Substation</p>
<div class="main">
    <table style ="height:350px;" border="0" class="content-table">
        <tr>
            <td colspan=2>
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="x" value="y" id="y" />Per Month
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="x" value="z" id="z" />Per Year
            </td>
        </tr>
        <tr>
            <td  class="thead">
                Substation Code:
            </td>
            <td>
                <input class = "input-Txt" href="#accNo" type="text" value = "1 - Dansalanan - A">
            </td>
            <td  class="thead">
                For the Month of:
            </td>
            <td class="input-td">
                <input type="month" name="month">
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
        c[17].style.color="blue";
    }
</script>
@endsection
