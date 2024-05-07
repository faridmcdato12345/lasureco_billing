@extends('layout.master')
@section('title', 'Frequency Distribution of KWH Consumption - Consolidated')
@section('content')

<p class="contentheader">Frequency Distribution of KWH Consumption - Consolidated</p>
<div class="main">
<br>
    <table border="0" class="content-table">
        <tr>
            <td class="thead">
             Area Code From:
            </td>
            <td style="width:30%;" class="input-td">
                <input class="input-Txt" href="#accNo" type="text" name="area" value="03 - Marawi City Wide and" readonly>
            </td>

            <td class="thead">
                Area Code To:
            </td>
            <td class="input-td" colspan=2>
                <input class="input-Txt" href="#accNo" type="text" value= "43 - Marawi City Wide and">
            </td>
        </tr>
        <tr>
            <td class="thead">
             Town Code From:
            </td>
            <td style="width:30%;" class="input-td">
                <input class="input-Txt" href="#town" type="text" name="area" value="03 - Marawi City Wide and" readonly>
            </td>

            <td class="thead">
            Town Code To:
            </td>
            <td class="input-td" colspan=2>
                <input class="input-Txt" href="#town" type="text" value= "43 - Marawi City Wide and">
            </td>
        </tr>
        <tr>
            <td class="thead">
                Billing Period:
            </td>
            <td style="width:30%;" class="input-td">
                <input type="month" name="month" >
            </td>
            <td class="thead">
                Group 1 Max:
            </td>
            <td>
                <input type="number" name="month" value="100">
            </td>
        </tr>
        <tr>
            <td class="thead">
                Group 2 Step:
            </td>
            <td>
                <input type="number" name="month" value="25">
            </td>
             <td class="thead">
                Group 2 Max:
            </td>
            <td>
                <input type="number" name="month" value="5">
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
        c[5].style.color="blue";
    }
</script>
@endsection
