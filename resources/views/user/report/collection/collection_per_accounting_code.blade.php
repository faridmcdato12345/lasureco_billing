@extends('layout.master')
@section('title', 'Collection Per Accounting Code')
@section('content')

<p class="contentheader">Non Bill Collection Per Account Report</p>
<div class="main">
    <table border=0 class="content-table">
        <tr>
            <td>
                Non-Bill:
            </td>
            <td>
                <select name="nonBill">
                    <option value="AUPVC2">ADAPTER, UPVC, 2"</option>
                    <option value="saab">Saab</option>
                    <option value="mercedes">Mercedes</option>
                    <option value="audi">Audi</option>
                </select>
            </td>
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
            <td colspan=4>
                <button class="modal-button" style="height: 40px; float: right; margin-right: 8px;">
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
