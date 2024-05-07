@extends('layout.master')
@section('title', 'Summary of Double Payments')
@section('content')

<p class="contentheader">Summary of Double Payments</p>
<div class="main">
    <table style ="height:100px;" border="0" class="content-table">
         <tr>
            <td class="input-td">
            <select style="border-radius:5px;width:20%;" name="" id=""><option disable selected>By Month</option></select>
            </td>
        </tr>
    </table>
    <table style ="height:350px;width:80%;" border="0" class="content-table">
        <tr>
            <td class="thead">
                Month: &nbsp; &nbsp;
                <select style = "border-radius:5px;width:25%;" name="" id=""><option disabled selected>March</option></select>
                &nbsp; &nbsp; 2019
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
        var b = document.querySelector('#drpbtn');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container').childNodes;
        c[9].style.color="blue";
     }
</script>
@endsection
