@extends('layout.master')
@section('title', 'LGU Collection Report')
@section('content')

<p class="contentheader">LGU Collection Report</p>
<div class="main">
    <table border=0 class="content-table">
        <tr>
            <td style="width: 15%;">
                Collected Date:
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
