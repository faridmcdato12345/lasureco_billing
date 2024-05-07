@extends('layout.master')
@section('title', 'Route Control')
@section('content')

<p class="contentheader">Route Control</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Area Code:
            </td>
            <td  class="input-td">
            <input type="text" class = "input-Txt" href="#accNo" name="area" placeholder="Select Area" readonly>
            </td>
            <td style="width:15%" class="thead">
                Town Code:
            </td>
            <td class="input-td">
            <input type="text" class = "input-Txt" href="#town" name="area" placeholder="Select Town" readonly>
            </td>
        </tr>
        <tr>
            <td  class="thead">
                Route Code:
            </td>
            <td class="input-td">
            <input type="text" class = "input-Txt" href="#route" name="area" placeholder="Select Route" readonly>
            </td>
            <td  class="thead">
                Date:
            </td>
            <td class="input-td">
                <input type="month" name = "month">
            </td>
        </tr>
        <tr>
        <td  class="thead">
                Consumer Status:
            </td>
            <td colspan=2>
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="y" value="y" id="y" />Active
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="z" value="z" id="z" />Disconnected
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="x" value="x" id="x" />Both
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
