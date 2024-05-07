@extends('layout.master')
@section('title', 'Big Loads')
@section('content')

<p class="contentheader">Big Loads</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                For the Year/Month of:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>

        </tr>
        <tr>
            <td  class="thead">
                Minimum KWH Used:
            </td>
            <td class="input-td">
                <input  type="text" value = "100.00">
            </td>
            <td  class="thead">
                Maximum KWH Used:
            </td>
            <td class="input-td">
                <input  type="text" value = "0.000">
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
