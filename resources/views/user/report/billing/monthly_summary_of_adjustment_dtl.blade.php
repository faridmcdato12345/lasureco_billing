@extends('layout.master')
@section('title', 'Monthly Summary of Adjustment - DTL')
@section('content')

<p class="contentheader">Monthly Summary of Adjustment - DTL</p>
<div class="main">
    <table style ="width:70%;height:450px;" border="0" class="content-table">

        <tr>
            <td class = "thead">
                Adjustment From:
            </td>
            <td>
            <input type="month">
            </td>
            <td class = "thead">
             Adjustment To:
            </td>
            <td>
            <input type="month">
            </td>
        </tr>
        <tr>
        <td class = "thead">
            Adjustment Type:
            </td>
            <td >
            <select style = "border-radius:5px;" name="" id=""><option disabled selected>Adjusted</option></select>
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
        c[5].style.color="blue";
    }
</script>
@endsection
