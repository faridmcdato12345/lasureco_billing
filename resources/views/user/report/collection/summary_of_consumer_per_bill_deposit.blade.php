@extends('layout.master')
@section('title', 'Summary of Consumer per Bill Deposit')
@section('content')

<p class="contentheader">Summary of Consumer per Bill Deposit</p>
<div class="main">
    <table class="content-table">
        <tr> 
            <td> 
                Area:
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#route" name="areaFrom" placeholder="Select Area" readonly>
            </td>
            <td> 
                &nbsp; Town:
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#route" name="areaFrom" placeholder="Select Area" readonly>
            </td>
        </tr>
        <tr> 
            <td>
                OR Date from:
            </td>
            <td> 
                <input type="date" name="ORDateFrom">
            </td>
            <td> 
                &nbsp; OR Date To:
            </td>
            <td> 
                <input type="date" name="ORDateTo">
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
                <button class="modal-button" style="float: right; margin-right: 7px; height: 40px;"> 
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
