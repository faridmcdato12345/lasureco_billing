@extends('layout.master')
@section('title', 'Senior Citizen with Discount - Detailed')
@section('content')
<p class="contentheader">Senior Citizen with Discount - Detailed</p>
<div class="main">
    <br><br>
    <table border="0" style ="height:500px;" class="content-table">
        <tr>
        <td class="thead">
                Area Code:
            </td>
            <td class="input-td">
                <input type="text" class="input-Txt" href="#accNo" placeholder="Select Route" readonly>
            </td>
            <td class="thead">
                Town Code:
            </td>
            <td class="input-td">
                <input type="text" class="input-Txt" href="#town" placeholder="Select Route" readonly>
            </td>
        </tr>
        <tr>
            <td class="thead">
                &nbsp;&nbsp; Billing Period:
            </td>
            <td>
                <input id="nobtninput" type="month">
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <button style ="height:40px;" id="printBtn">Print</button>
            </td>
        </tr>
    </table>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn2');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container').childNodes;
        c[5].style.color="blue";
     }
</script>
@endsection

