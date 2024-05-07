@extends('layout.master')
@section('title', 'Summary of Sales Unbundled for LGUs')
@section('content')

<p class="contentheader">Summary of Sales - Unbundled</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td colspan=2> 
                <select name="summSalesUB" style="width: 300px;"> 
                    <option value="byArea"> By Area </option>
                    <option value="byTown"> By Town </option>
                    <option value="byRoute"> By Route </option>
                </select>
            </td>
        </tr>
        <tr> 
            <td> 
                Area From:
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#town" name="areaFrom" placeholder="Select Area" readonly>
            </td>
            <td> 
                &nbsp; Area To:
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#town" name="areaTo" placeholder="Select Area" readonly>
            </td>
        </tr>
        <tr> 
            <td> </td>
            <td> 
                <select name="summSalesUB2"> 
                    <option value="byYearMon"> by Year Month </option>
                </select>
            </td>
            <td> 
                &nbsp; Period: 
            </td>
            <td> 
                <input type="month" name="period"> 
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
