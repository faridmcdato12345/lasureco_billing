@extends('layout.master')
@section('title', 'Summary of Void Payments')
@section('content')

<p class="contentheader">Summary of Void Payments</p>
<div class="main">
    <table border=0 class="content-table">
        <tr>
            <td>
                Teller:
            </td>
            <td>
                <input type="text" name="teller" href="#town" placeholder="Select Teller" readonly>
            </td>
        </tr>
        <tr>
            <td>
                Covered Period:
            </td>
            <td>
                <input type="date" name="coveredPeriodFrom">
            </td>
            <td>
                &nbsp; To:
            </td>
            <td>
                <input type="date" name="coveredPeriodTo">
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <button class="modal-button" style="height: 40px; float: right; margin-right: 8px;"> Print </button>
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
