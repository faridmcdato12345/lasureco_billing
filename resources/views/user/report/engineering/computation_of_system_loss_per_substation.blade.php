@extends('layout.master')
@section('title', 'Computation of System Loss per Substation')
@section('content')

<p class="contentheader">Computation of System Loss per Substation</p>
<div class="main">
    <table style ="height:200px;" border="0" class="content-table">
        <tr>
            <td  style="width:15%;"class="thead">
                Billing Period:
            </td>
            <td class="input-td">
                <input style="width:50%;" type="month" name="month">
            </td>
        </tr>
    </table>
        <table style ="width:80%;height:300px;" border="0" class="content-table">
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
