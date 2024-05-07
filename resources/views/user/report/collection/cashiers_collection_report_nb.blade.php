@extends('layout.master')
@section('title', 'Cashiers Collection Report - NB')
@section('content')

<p class="contentheader">Cashiers Collection Report - NB</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td style="width: 20%;">
                Collected Date From:
            </td>
            <td>
                <input type="date" name="collDateFrom">
            </td>
            <td>
                &nbsp; To:
            </td>
            <td>
                <input type="date" name="collDateTo">
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <button class="modal-button" style="float: right; margin-right: 8px; height: 40px;">
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
