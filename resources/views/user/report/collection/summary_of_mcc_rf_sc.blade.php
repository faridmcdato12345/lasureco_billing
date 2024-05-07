@extends('layout.master')
@section('title', 'Summary of MCC/RFSC')
@section('content')

<p class="contentheader">Summary of MCC/RFSC</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td colspan=2> 
                <select name="MCC/RFSC"> 
                    <option value="billed"> Billed </option>
                    <option value="unbilled"> Unbilled </option>
                </select>
            </td>
            <td> 
                &nbsp; Area:
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#town" name="areaFrom" placeholder="Select Area" readonly>
            </td>
        </tr>
        <tr> 
             <td style="width: 15%;"> 
                Consumer Type: 
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#town" name="areaTO" placeholder="Select Area" readonly>
            </td>
            <td> 
                &nbsp; Billing Period:
            </td>
            <td> 
                <input type="month" name="billPeriod">
            </td>
        </tr>
        <tr> 
            <td> 
                Prepared By:
            </td>
            <td> 
                <input type="text" name="prepBy" placeholder="Prepared By">
            </td>
            <td colspan=2> 
                &nbsp; <input type="text" name="prepByPos" placeholder="Position">
            </td>
        </tr>
        <tr>
            <td> 
                Checked By:
            </td>
            <td> 
                <input type="text" name="checkBy" placeholder="Checked By">
            </td>
            <td colspan=2> 
                &nbsp; <input type="text" name="checkByPos" placeholder="Position">
            </td> 
        </tr>
        <tr> 
            <td> 
                Approved By:
            </td>
            <td> 
                <input type="text" name="appBy" placeholder="Approved By">
            </td>
            <td colspan=2> 
                &nbsp; <input type="text" name="appByPos" placeholder="Position">
            </td>
        </tr>
        <tr> 
            <td colspan=4> 
                <button class="modal-button" style="height: 40px; float: right; margin-right: 5px;"> 
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
