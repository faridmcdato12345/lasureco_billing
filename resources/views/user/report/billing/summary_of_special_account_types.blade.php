@extends('layout.master')
@section('title', 'Summary of Special Account Types -Route')
@section('content')

<p class="contentheader">Summary of Special Account Types - Route</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
    <tr>
            <td class="thead">
             Area From:
            </td>
            <td class="input-td">
                <input  type="text" class="input-Txt" href="#accNo" name="area" placeholder="Select Area" readonly>
            </td>
            <td class="thead">
            Area To:
            </td>
            <td class="input-td">
                <input  type="text" class="input-Txt" href="#accNo" name="area" placeholder="Select Area" readonly>
            </td>
        </tr>
        <tr>
            <td class="thead">
             Town:
            </td>
            <td class="input-td">
                <input  type="text" class="input-Txt" href="#town" name="town" placeholder="Select Town" readonly>
            </td>
            <td class="thead">
            Route:
            </td>
            <td class="input-td">
                <input  type="text" class="input-Txt" href="#town" name="town" placeholder="Select Route" readonly>
            </td>
        </tr>
        <tr>
            <td  class="thead">
                Account Account Type:
            </td>
            <td colspan=1 class="input-td">
            <select name="Sort">
                    <option value="volvo">Employee</option>
                    <option value="saab">Unposted</option>
                </select>
            </td>
            <td  class="thead">
                Billing Period:
            </td>
            <td class="input-td">
                <input   type="month" name="month">
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
