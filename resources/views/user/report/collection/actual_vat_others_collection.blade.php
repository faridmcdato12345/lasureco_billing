@extends('layout.master')
@section('title', 'Actual VAT Others Collection')
@section('content')

<p class="contentheader">Monthly Cashiers DCR - SO</p>
<div class="main">
    <table class="content-table">
        <tr> 
            <td style="width: 15%;"> 
                Period:
            </td>
            <td> 
                <input type="month" style="width: 50%;" name="period">
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
        c[16].style.color="blue";
     }
</script>
@endsection
