@extends('layout.master')
@section('title', '5% VAT Collected Report - LGUs')
@section('content')

<p class="contentheader">5% VAT Collected Report - LGUs</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td> 
                Report Per:
            </td>
            <td> 
                <select name="repPer1"> 
                    <option value="area"> Area </option>
                    <option value="town"> Town </option>
                    <option value="route"> Route </option>
                </select>
            </td>
            <td> 
                &nbsp; 
                <select name="repPer2"> 
                    <option value="summary"> Summary </option>
                    <option value="others"> Others </option>
                </select>
            </td>
        </tr>
        <tr> 
            <td> 
                Area From: 
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#town" name="area" placeholder="Select Area" readonly>
            </td>
            <td> 
                &nbsp; <input type="text" class="input-Txt" href="#town" name="area" placeholder="Select Area" readonly>
            </td>
        </tr>
        <tr> 
            <td> 
                Collection Date Range:
            </td>
            <td> 
                <input type="date" name="collDateFrom">
            </td>
            <td> 
                &nbsp; <input type="date" name="collDateTo">
            </td>
        </tr>
        <tr> 
            <td> 
                Print Report:
            </td>
            <td> 
                <select name="5VCRL"> 
                    <option value="byDateRange"> By Date Range </option>
                    <option value="others"> Others </option>
                </select>
            </td>
        </tr>
        <tr> 
            <td colspan=4> 
                <button class="modal-button" style="float: right; margin-right: 5px; height: 40px;"> 
                    Print
                </button>
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
        c[16].style.color="blue";
     }
</script>
@endsection
