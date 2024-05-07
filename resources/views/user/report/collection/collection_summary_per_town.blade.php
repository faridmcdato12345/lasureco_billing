@extends('layout.master')
@section('title', 'Collection Summary per Town')
@section('content')

<p class="contentheader">Collection Summary per Town</p>
<div class="main">
    <table border=0 class="content-table">
        <tr>
            <td style="width: 15%;">
                Area:
            </td>
            <td>
                <input type="text" class="input-Txt" href="#town" name="area" placeholder="Select Area" readonly>
            </td>
        </tr>
        <tr>
            <td>
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
