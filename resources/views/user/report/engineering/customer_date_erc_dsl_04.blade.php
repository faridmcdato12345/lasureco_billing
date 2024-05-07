@extends('layout.master')
@section('title', 'Customer Date(ERC_DSL_04)')
@section('content')

<p class="contentheader">Customer Date(ERC_DSL_04)</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Substation:
            </td>
            <td class="input-td">
                <input  type="text" class ="input-Txt" href = "#accNo" name="substation" placeholder="Select Substation" readonly>
            </td>
            <td  class="thead">
                town:
            </td>
            <td class="input-td">
                <input  type="text" class ="input-Txt" href = "#town" name="town" placeholder="Select Town" readonly>
            </td>

        </tr>
        <tr>
            <td  class="thead">
               Route:
            </td>
            <td class="input-td">
                <input  type="text" class ="input-Txt" href = "#route" name="route" placeholder="Select Route" readonly>
            </td>
            <td  class="thead">
                For the Year/Month of:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
        </tr>
        <tr>
            <td  class="thead">
               Consumer Stat:
            </td>
            <td class="input-td">
                 <select name="Sort">
                    <option value="volvo">Both</option>
                    <option value="saab">Unposted</option>
                </select>
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
            <button style="color:white;background-color:gray;width:140px;margin-top:30px;height:40px;" id="printBtn" >Generate</button>
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
