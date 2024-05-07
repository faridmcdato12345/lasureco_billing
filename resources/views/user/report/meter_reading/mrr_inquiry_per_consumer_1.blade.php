@extends('layout.master')
@section('title', 'MMR Inquiry per Consumer - 1')
@section('content')

<p class="contentheader">MMR Inquiry per Consumer - 1</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td style="width: 15%;"> 
                Account Number:
            </td>
            <td> 
                <input type="text" class="input-Txt" name="account" href="#town" placeholder="Select Account" readonly>
            </td>
        </tr>
        <tr> 
            <td> 
                Month Range:
            </td>
            <td> 
                <input type="month" name="monthFrom">
            </td>
            <td> 
                &nbsp; <input type="month" name="monthTo">
            </td>
        </tr>
        <tr> 
            <td colspan=3> 
                <button class="modal-button" style="height: 40px; float: right; margin-right: 5px;">
                    Print
                </button>
            </td>
        </tr>
    </table>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container').childNodes;
        c[12].style.color="blue";
     }
</script>
@endsection
