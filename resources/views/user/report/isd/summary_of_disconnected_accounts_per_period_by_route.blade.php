@extends('layout.master')
@section('title', 'Summary of Disconnected Accounts per Period by Route')
@section('content')

<p class="contentheader">Summary of Disconnected Accounts per Period by Route</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Area:
            </td>
            <td class="input-td">
                <input class="input-Txt" href="#accNo" type="text" placeholder="Select Area" name="area" readonly>
            </td>
            <td  class="thead">
               Town:
            </td>
            <td class="input-td">
            <input class="input-Txt" href="#accNo" type="text" placeholder="Select Town" name="area" readonly>
            </td>
        </tr>
        <tr>
            <td  class="thead">
               Route Code From:
            </td>
            <td class="input-td">
            <input class="input-Txt" href="#route" type="text" name="area" placeholder="Select Route" readonly>
            </td>
            <td  class="thead">
               Route Code To:
            </td>
            <td class="input-td">
            <input class="input-Txt" href="#route" type="text" name="area" placeholder="Select Route" readonly>
            </td>
        </tr>
        <tr>
        <td  class="thead">
               Period From:
            </td>
            <td class="input-td">
            <input type="month" name="monthFrom" >
            </td>
            <td  class="thead">
               Period To:
            </td>
            <td class="input-td">
            <input type="month" name="monthTo">
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
        c[25].style.color="blue";
    }
</script>
@endsection
