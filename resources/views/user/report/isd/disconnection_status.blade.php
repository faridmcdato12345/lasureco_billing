@extends('layout.master')
@section('title', 'Disconnection of Accomplishment Report')
@section('content')
<p class="contentheader">Disconnection of Accomplishment Report</p>
<div class="main">
    <table style ="height:300px;" border="0" class="content-table">
        <tr>
            <td colspan=2>
            <input style = "width:20px;height:20px;" type="radio" class="radio" name="y" value="y" id="y" />Accomplished
            <input style = "width:20px;height:20px;" type="radio" class="radio" name="z" value="z" id="z" />Unaccomplished
            <input style = "width:20px;height:20px;" type="radio" class="radio" name="x" value="x" id="x" />Both
            </td>
        </tr>
        <tr>
            <td  class="thead">
                Disco. Date From:
            </td>
            <td  class="input-td">
            <input type="month" name="month">
            </td>
            <td  class="thead">
                Disco. Date To:
            </td>
            <td  class="input-td">
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
