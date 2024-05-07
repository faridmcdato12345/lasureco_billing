@extends('layout.master')
@section('title', 'List of Consumer NOT Posted to Ledger')
@section('content')

<p class="contentheader">List of Consumer NOT Posted to Ledger</p>
<div class="main">
    <table style ="height:300px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Area Code:
            </td>
            <td class="input-td">
                <input type="text" class="input-Txt" href="#route" name="area" value="01 - District" readonly>
            </td>
            <td class="thead">
                Town Code:
            </td>
            <td class="input-td">
                <input type="text" class="input-Txt" href="#town" name="area" value="10 - Ditsaan - Ramain" readonly>
            </td>
        </tr>
        <tr>
            <td class="thead">
                Route Code From:
            </td>
            <td class="input-td">
                <input type="text" class="input-Txt" href="#town" name="area" value="10 - Ditsaan - Ramain" readonly>
            </td>
            <td class="thead">
              Route Code To:
            </td>
            <td class="input-td">
                <input type="text" class="input-Txt" href="#town" name="area" value="10 - Ditsaan - Ramain" readonly>
            </td>
        </tr>
        <tr>
        <td class="thead">
                Billing Period:
            </td>
            <td class="input-td">
                <input type="month"  name="period">
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        </table>
        <table style ="width:80%;height:250px;" border="0" class="content-table">
        <tr>
            <td class = "thead">
                Prepared By:
            </td>
            <td class = "input-td">
                <input  style = "width:70%" type = "text" placeholder = "Prepared By" readonly>
            </td>
            <td class = "input-td">
                <input style = "width:70%" type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td class = "thead">
                Noted By:
            </td>
            <td class = "input-td">
                <input  style = "width:70%" type = "text" placeholder = "Noted By" readonly>
            </td>
            <td class = "input-td">
                <input style = "width:70%" type = "text" readonly>
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
