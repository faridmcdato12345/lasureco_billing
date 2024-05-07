@extends('layout.master')
@section('title', 'Consumer List with Power Subsidy')
@section('content')

<p class="contentheader">Consumer List with Power Subsidy</p>
<div class="main">
    <table style ="height:280px;" border="0" class="content-table">
        <tr>
            <td class="thead">
                Area Code:
            </td>
            <td class="input-td">
                <input type="text" class="input-Txt" href="#accNo" name="area" value="01 - District" readonly>
            </td>
            <td  class="thead">
                As of Date:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
        </tr>
        <tr>
            <td  class="thead">
                Month/Year:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
        </tr>
        <tr>
        </tr>

        <tr>
        <td class = "thead">
            </td>
            <td class = "thead">
            </td>
            <td colspan="3" class = "thead">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Position
            </td>
        </tr>
        </table>
        <table style ="width:80%;height:300px;" border="0" class="content-table">
        <tr>

            <td class = "thead">
                Prepared By:
            </td>
            <td class = "input-td">
                <input type = "text" placeholder = "Prepared By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td class = "thead">
                Checked By:
            </td>
            <td class = "input-td">
                <input type = "text" placeholder = "Checked By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
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
        c[5].style.color="blue";
    }
</script>
@endsection
