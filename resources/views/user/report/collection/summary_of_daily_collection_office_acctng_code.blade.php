@extends('layout.master')
@section('title', 'Summary of Daily Collection/Office/Accounting Code')
@section('content')

<p class="contentheader">Summary of Daily Collection/Office/Accounting Code</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td>
                Branch Code:
            </td>
            <td>
                <input type="text" class="input-Txt" href="#town" name="branch" placeholder="Select Branch" readonly>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>
                Date Collected:
            </td>
            <td>
                <input type="date" name="dateCollFrom">
            </td>
            <td>
                &nbsp; To:
            </td>
            <td>
                <input type="date" name="dateCollFrom">
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
                &nbsp; <input type="text" name="prepByPos" placeholder="Position" style="width: 95%;">
            </td>
        </tr>
        <tr>
            <td>
                Noted By:
            </td>
            <td>
                <input type="text" name="notedBy" placeholder="Noted By">
            </td>
            <td colspan=2>
                &nbsp; <input type="text" name="notedByPos" placeholder="Position" style="width: 95%;">
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
                &nbsp; <input type="text" name="appByPos" placeholder="Position" style="width: 95%;">
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <button class="modal-button" style="height: 40px; float: right; margin-right: 10px;">
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
        c[9].style.color="blue";
     }
</script>
@endsection
