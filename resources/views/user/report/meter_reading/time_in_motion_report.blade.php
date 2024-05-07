@extends('layout.master')
@section('title', 'Time in Motion Report')
@section('content')
<p class="contentheader">Time in Motion Report</p>
<div class="main">
<br><br>
    <table border="0" style = "margin-left:70px;height:350px;width:80%;" class="content-table">
        <tr>
            <td class="thead">
                Month/Year:
            </td>
            <td class="input-td">
                <input type="month" name="my">
            <td>
            <td class="thead">
                Meter Reader:
            </td>
            <td class="input-td">
                <input class="input-Txt" href="#meterreader" type="text" name="town" value=" " readonly>
            </td>
        </tr>
        <tr>
             <td class="thead">
               Town:
            </td class="input-td">
            <td colspan="2">
                <input class="input-Txt" href="#town" type="text" name="town" value= "03 - Marawi City and">
            </td>
            <td class="thead">
                Route:
            </td>
            <td class="input-td">
                <input class="input-Txt" href="#route" type="text" name="town" value="04 - MSU" readonly>
            </td>
        </tr>
        <tr>
         <td class="thead">
                Book:
            </td>
            <td class="input-td">
                <input  type="number" name="Book" value="1">
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <button style = "height:40px;" id="printBtn">Print</button>
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
