@extends('layout.master')
@section('title', 'Annual Consumption Graph')
@section('content')

<p class="contentheader">Annual Consumption Graph</p>
<div class="main">
    <table style ="height:250px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Area:
            </td>
            <td class="input-td">
            <input type="text" class = "input-Txt" href="#accNo" name="month">
            </td>
            <td  class="thead">
                Town:
            </td>
            <td class="input-td">
                <input type="text" class = "input-Txt" href="#town" name="month">
            </td>
        </tr>
        <tr>
            <td  class="thead">
                Billing Period:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
            <td  class="thead">
                Consumer Type:
            </td>
            <td>
                <select name="Sort">
                    <option value="volvo">All Consumers</option>
                    <option value="saab">Unposted</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="x" value="y" id="y" />KWH Sold
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="x" value="z" id="z" />Demand
            </td>
        </tr>
        <tr></tr> <tr></tr>
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
                Recommending for Approval:
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
        c[17].style.color="blue";
    }
</script>
@endsection
