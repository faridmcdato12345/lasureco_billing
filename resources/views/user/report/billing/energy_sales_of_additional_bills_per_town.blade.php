@extends('layout.master')
@section('title', 'Energy Sales of Additional Bills Per Town')
@section('content')
<p class="contentheader">Energy Sales of Additional Bills Per Town</p>
<div class="main">
<br>
    <table border="0" class="content-table">
        <tr>
            <td class="thead">
                Area:
            </td>
            <td style="width:30%;" class="input-td">
                <input class="input-Txt" href="#accNo" type="text" name="area" value="03 - Marawi City Wide and" readonly>
            </td>
            <td class="thead">
                Billing Period:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
        </tr>
        <tr>
        <td class="thead">
             Town From:
            </td>
            <td class="input-td">
                <input class="input-Txt" href="#town" type="text" value= "43 - Marawi City Wide and">
            </td>
            <td class="thead">
             Town To:
            </td>
            <td style="width:30%;" class="input-td">
                <input class="input-Txt" href="#town" type="text" name="area" value="03 - Marawi City Wide and" readonly>
            </td>
        </tr>
        <tr>
        <td class="thead">
                LB Date From:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
            <td class="thead">
                LB Date To:
            </td>
            <td class="input-td">
                <input type="month" name="month">
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
        c[5].style.color="blue";
    }
</script>
@endsection
