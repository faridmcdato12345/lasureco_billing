@extends('layout.master')
@section('title', 'DCR Control List per Route')
@section('content')

<p class="contentheader">DCR Collection Control List per Route</p>
<div class="main">
    <table border=0 class="content-table">
        <tr>
            <td>
                Route From:
            </td>
            <td>
                <input type="text" class="input-Txt" href="#route" name="routeFrom" placeholder="Route From" readonly>
            </td>
            <td>
                &nbsp; Route To:
            </td>
            <td>
                <input type="text" class="input-Txt" href="#route" name="routeTo" placeholder="Route To" readonly>
            </td>
        </tr>
        <tr>
            <td>
                Collected Date From:
            </td>
            <td>
                <input type="date" name="collDateFrom">
            </td>
            <td>
                &nbsp; Collected Date From:
            </td>
            <td>
                <input type="date" name="collDateTo">
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
