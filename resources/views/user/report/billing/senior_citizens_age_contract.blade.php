@extends('layout.master')
@section('title', 'Age Contact of SCD')
@section('content')
<p class="contentheader">Age Contact of SCD</p>
<div class="main">
<br><br>
    <table border="0" style = "height:150px;" class="content-table">
        <tr>
        <td style = "width:15%;" class="thead">
               As of Date:
            </td>
            <td class="input-td">
                <input style="width:40%;" type="month" name="month" >
            </td>
        </tr>
    </table>
    <table style ="width:80%;height:300px;" border="0" class="content-table">
        <tr>
            <td class = "thead">
                Reviewed By:
            </td>
            <td class = "input-td">
                <input type = "text" placeholder = "Reviewed By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td class = "thead">
                Noted By:
            </td>
            <td class = "input-td">
                <input type = "text" placeholder = "Noted By" readonly>
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
