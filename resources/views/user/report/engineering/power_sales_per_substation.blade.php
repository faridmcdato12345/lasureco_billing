@extends('layout.master')
@section('title', 'List of Consumers with Demand')
@section('content')

<p class="contentheader">List of Consumers with Demand</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Substation:
            </td>
            <td class="input-td">
                <input  type="text" class ="input-Txt" href = "#accNo" placeholder="Select Substation">
            </td>
            <td  class="thead">
                For the Month of:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="y" value="y" id="y" />Bill Amount
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="z" value="z" id="z" />KWH Used
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="y" value="y" id="y" />No of Bills
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
        c[21].style.color="blue";
    }
</script>
@endsection
