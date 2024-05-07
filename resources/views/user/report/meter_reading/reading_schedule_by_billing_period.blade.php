@extends('layout.master')
@section('title', 'Reading Schedule by Billing Period')
@section('content')

<p class="contentheader">Reading Schedule by Billing Period</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td style="width:25%;" class="thead">
               MRD Number:
            </td>
            <td class="input-td">
                <input style ="width:70%;" type="text" class="input-Txt" href="#route" name="area" value="9006 - Abdulwally Bacarat" readonly>
            </td>
        </tr>
        <tr>
        <td class="thead">
            Year Month:
        </td>
        <td class="input-td">
                <input style ="width:60.7%;" type="month">
        </td>
        </tr>
        <tr>
            <td class = "thead">
            </td>
            <td class = "thead">
            </td>
            <td class = "thead">
            Position
            </td>
        </tr>
        <tr>
            <td class = "thead">
                Prepared By:
            </td>
            <td class = "input-td">
                <input style = "width:70%;" type = "text" placeholder = "Prepared By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td class = "thead">
                Verified By:
            </td>
            <td class = "input-td">
                <input style = "width:70%;" type = "text" placeholder = "Verified By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td class = "thead">
                Received By:
            </td>
            <td class = "input-td">
                <input style = "width:70%;" type = "text" placeholder = "Received By" readonly>
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
                <input style = "width:70%;" type = "text" placeholder = "Noted By" readonly>
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
        c[1].style.color="blue";
    }
</script>
@endsection
