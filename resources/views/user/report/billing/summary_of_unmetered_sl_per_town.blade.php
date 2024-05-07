@extends('layout.master')
@section('title', 'Summary of Unmetered SL per Town')
@section('content')
<p class="contentheader">Summary of Unmetered SL per Town</p>
<div class="main">
<br><br>
    <table border="0" style = "height:500px;" class="content-table">
        <tr>
        <td class="thead">
               Area:
            </td>
            <td class="input-td">
                <input type="month" name="month" >
            </td>
            <td class="thead">
               Town:
            </td>
            <td class="input-td">
                <input type="month" name="month" >
            </td>
        </tr>
        <tr>
        <td class="thead">
               Billing Period:
            </td>
            <td class="input-td">
                <input type="month" name="month" >
            </td>
            <td colspan=3 class = "input-td">
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="y" value="y" id="y" />Active
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="z" value="z" id="z" />Disconnected
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="i" value="i" id="i" />Both
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
