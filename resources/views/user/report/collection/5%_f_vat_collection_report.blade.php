@extends('layout.master')
@section('title', '5% F-VAT Collection Report')
@section('content')

<p class="contentheader">LGU (2% & 5%) Collected</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td> 
                Collection Date:
            </td>
            <td> 
                <input type="date" name="collDateFrom">
            </td>
            <td> 
                &nbsp; <input type="date" name="collDateTo">
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
