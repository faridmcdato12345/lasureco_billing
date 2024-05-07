@extends('layout.master')
@section('title', 'Large Load per Route')
@section('content')

<p class="contentheader">Additional Bills - Previous Months</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
    <tr>
            <td class="thead">
             Current Billing Period:
            </td>
            <td class="input-td">
            <input type="month" name = "month" >
            </td>

        </tr>
        <tr>
            <td  class="thead">
                 Reading Date From:
            </td>
            <td class="input-td">
                <input type="month" name = "month" >
            </td>
            <td  class="thead">
                 Reading Date To:
            </td>
            <td class="input-td">
                <input type="month" name = "month" >
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
        c[5].style.color="blue";
    }
</script>
@endsection
