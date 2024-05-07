@extends('layout.master')
@section('title', 'Summary of SCD')
@section('content')
<p class="contentheader">Summary of SCD</p>
<div class="main">
<br><br>
    <table border="0" style = "height:250px;" class="content-table">
        <tr>
            <td colspan=3 class = "input-td">
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="y" value="y" id="y" />Per Area
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="z" value="z" id="z" />Per Town
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="i" value="i" id="i" />Per Route
                <input style = "width:20px;height:20px;" type="radio" class="radio" name="x" value="x" id="x" />All
            </td>
        </tr>
        <tr>
        <td style="width:15%;"class="thead">
               Area:
            </td>
            <td class="input-td">
                <input style="width:50%;" type="text" class ="input-Txt" href="#accNo" readonly >
            </td>
        </tr>
        <tr>
        <td class="thead">
               For the Month of:
            </td>
            <td class="input-td">
                <input style="width:50%;" type="month" name="month" >
            </td>
        </tr>
        <tr></tr>
        <tr></tr><tr></tr>
    </table>
    <table style ="width:80%;height:300px;" border="0" class="content-table">
        <tr>
            <td class = "thead">
                Prepared By:
            </td>
            <td class = "input-td">
                <input type = "text" placeholder = "Prepared By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td class = "thead">
                Checked By:
            </td>
            <td class = "input-td">
                <input type = "text" placeholder = "Checked By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td class = "thead">
                Approved By:
            </td>
            <td class = "input-td">
                <input type = "text" placeholder = "Approved By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <button style = "height:40px;" id="printBtn">Print</button>
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
