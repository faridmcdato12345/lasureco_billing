@extends('layout.master')
@section('title', 'Increase/Decrease in Consumption')
@section('content')

<p class="contentheader">Increase/Decrease in Consumption</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Substation:
            </td>
            <td class="input-td">
                <input  type="text" class ="input-Txt" href = "#accNo" name="area">
            </td>
            <td  class="thead">
                Feeder:
            </td>
            <td class="input-td">
                <input  type="text" class ="input-Txt" href = "#accNo" name="area">
            </td>

        </tr>
        <tr>
            <td  class="thead">
               Town:
            </td>
            <td class="input-td">
                <input  type="text" class ="input-Txt" href = "#town" name="area" readonly>
            </td>
            <td  class="thead">
                Billing Date:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
        </tr>
        <tr>
            <td  class="thead">
               Route From:
            </td>
            <td class="input-td">
                <input  type="text" class ="input-Txt" href = "#route" name="area" readonly>
            </td>
            <td  class="thead">
                Route To:
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
