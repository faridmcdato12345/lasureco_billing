@extends('layout.master')
@section('title', 'Consumer for Reconnection')
@section('content')
<p class="contentheader">Consumer for Reconnection</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Area:
            </td>
            <td  class="input-td">
            <input type="text" class = "input-Txt" href="#accNo" name="area" placeholder="Select Area" readonly>
            </td>
            <td  class="thead">
                Town:
            </td>
            <td  class="input-td">
            <input type="text" class = "input-Txt" href="#town" name="area" placeholder="Select Town" readonly>
            </td>
        </tr>
        <tr>
            <td style="width:15%" class="thead">
                Route:
            </td>
            <td class="input-td">
            <input type="text" class = "input-Txt" href="#route" name="area" placeholder="Select Route" readonly>
            </td>
        </tr>
        <tr>
            <td  class="thead">
               Collected Date:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
            <td colspan=2 class="input-td">
                <input style="width:70%;" type="month" name="month">
            </td>
        </tr>
        <tr>
            <td  class="thead">
            Prepared By:
            </td>
            <td class="input-td">
                <input type="text" name="area" placeholder="Prepared By"  readonly>
            </td>
            <td colspan=2>
                <input style="width:70%;" type="text" name="area" placeholder="Position" readonly>
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
