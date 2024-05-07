@extends('layout.master')
@section('title', 'New Connection per Month')
@section('content')

<p class="contentheader">New Connection per Month</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Area:
            </td>
            <td class="input-td">
                <input class="input-Txt" href="#accNo" type="text" name="area" placeholder="Select Area" readonly>
            </td>
            <td  class="thead">
               Town:
            </td>
            <td class="input-td">
            <input class="input-Txt" href="#town" type="text" name="area" placeholder="Select Town" readonly>
            </td>
        </tr>
        <tr>
            <td  class="thead">
               Date From:
            </td>
            <td class="input-td">
            <input type="month" name="month">
            </td>
            <td  class="thead">
               Date To:
            </td>
            <td class="input-td">
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
        c[25].style.color="blue";
    }
</script>
@endsection
