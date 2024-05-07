@extends('layout.master')
@section('title', 'Summary of Refunded Bill Deposit')
@section('content')
<p class="contentheader">Summary of Refunded Bill Deposit</p>
<div class="main">
<br><br>
    <table border="0" style = "margin-left:70px;height:350px;width:80%;" class="content-table">
        <tr>
            <td class="thead">
               Area:
            </td>
            <td class="input-td">
                <input class="input-Txt" href="#accNo" type="text" name="town" value= "03 - Marawi City and">
            </td>
        </tr>
        <tr>
        <td class="thead">
               Reporting Date From:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
            <td class="thead">
                Reporting Date To:
            </td>
            <td class="input-td">
                <input type="month" name="month">
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
        c[5].style.color="blue";
    }
</script>
@endsection
