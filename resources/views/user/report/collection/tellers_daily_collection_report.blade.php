@extends('layout.master')
@section('title', 'Tellers Daily Collection Report')
@section('content')

<p class="contentheader">Daily Tellers Collection Report</p>
<div class="main">
    <table border=0 class="content-table">
        <tr>
            <td>
                Cashier:
            </td>
            <td>
                <input type="text" name="cashier" class="input-Txt" href="#town" placeholder="Select Cashier" readonly>
            </td>
            <td>
                &nbsp; Collection Date:
            </td>
            <td>
                <input type="date" name="collDate">
            </td>
        </tr>
        <tr>
            <td>
                Prepared By:
            </td>
            <td>
                <input type="text" name="prepBy" placeholder="Prepared By">
            </td>
            <td>
                &nbsp; Position:
            </td>
            <td>
                <input type="text" name="prepByPos" placeholder="Postition">
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <button class="modal-button" style="float: right; height: 40px; margin-right: 10px;"> Print </button>
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
