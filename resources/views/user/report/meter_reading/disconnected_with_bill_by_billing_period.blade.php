@extends('layout.master')
@section('title', 'Disconnected with Bill by Billing Period')
@section('content')
<p class="contentheader">Disconnected with Bill by Billing Period</p>
<div class="main">
    <table border="0" class="content-table">
        <tr>
            <td class="thead">
                Area From:
            </td>
            <td style="width:30%;" class="input-td">
                <input type="text" name="area" class="input-Txt" href="#route" value="03 - Marawi City Wide and" readonly>
            </td>
            <td class="thead">
             Area To:
            </td>
            <td class="input-td">
                <input  type="text" class="input-Txt" href="#route" value= "43 - Marawi City Wide and" readonly>
            </td>
        </tr>
        <tr>
            <td class="thead">
                Town From:
            </td>
            <td class="input-td">
                <input type="text" name="town" class="input-Txt" href="#town" value="43 - Marawi" readonly>
            </td>
            <td class="thead">
               Town To:
            </td>
            <td class="input-td">
                <input type="text" name="town" class="input-Txt" href="#town" value="45 - Marantao" readonly>
            </td>
        </tr>
        <tr>
            <td class="thead">
                Billing Period:
            </td>
            <td class="input-td">
                <input type="month" name="bperiod">
            </td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td class="thead">
                Prepared by:
            </td>
            <td class="input-td">
                <input type="text" name="" placeholder="Prepared by" readonly>
                <input style = "margin-top:10px;" type="text" name="" placeholder="" readonly>
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
        c[1].style.color="blue";
    }
</script>
@endsection