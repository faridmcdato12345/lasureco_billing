@extends('layout.master')
@section('title', 'Monthly Report - NB')
@section('content')

<p class="contentheader">Monthly Report - NB</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                For the Month of:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
            <td colspan=2 class="input-td">
                <input type="month" name="month">
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <button style="width:70px;margin-top:30px;height:40px;" id="printBtn" >Print</button>
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
        c[17].style.color="blue";
    }
</script>
@endsection
