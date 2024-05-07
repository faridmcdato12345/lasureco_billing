@extends('layout.master')
@section('title', 'Output to Excel 2')
@section('content')

<p class="contentheader">Output to Excel 2</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Area:
            </td>
            <td class="input-td">
                <input  type="text" class ="input-Txt" href = "#accNo" name="area" placeholder="Select Area" readonly>
            </td>
        </tr>
        <tr>
            <td  class="thead">
                For the Period of:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
            <td  class="thead">
                File:
            </td>
            <td class="input-td">
                <input style="border:0;" type="File"  name="file">
            </td>
        </tr>

        <tr>
            <td colspan="4">
            <button style="width:70px;margin-top:30px;height:40px;" id="printBtn" >Print</button>
            <button style="color:white;background-color:gray;width:140px;margin-top:30px;height:40px;" id="printBtn" >Output to EXCEL</button>
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
        c[21].style.color="blue";
    }
</script>
@endsection
