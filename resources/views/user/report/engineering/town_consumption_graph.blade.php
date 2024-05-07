@extends('layout.master')
@section('title', 'Town Consumption Graph')
@section('content')

<p class="contentheader">Town Consumption Graph</p>
<div class="main">
    <table style ="height:200px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Billing Period:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
            <td colspan=2>
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="x" value="y" id="y" />KWH Used
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="x" value="z" id="z" />Bill Amount(php)
            </td>
        </tr>
    </table>
        <table style ="width:80%;height:300px;" border="0" class="content-table">
        <tr>

            <td class = "thead">
                Approved By:
            </td>
            <td class = "input-td">
                <input type = "text" placeholder = "Approved By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td class = "thead">
                Noted By:
            </td>
            <td class = "input-td">
                <input type = "text" placeholder = "Noted By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
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
