@extends('layout.master')
@section('title', 'Holiday Schedule')
@section('content')

<p class="contentheader">Holiday Schedule</p>
<div class="main"><br>
    <table border = 0 class="EMR-table" style="color:white;height:250px">
        <tr>
            <th style="width:15%;" >For the Month/Year:</th>
            <td><input style="width:40%;" type="month"></td>
        </tr>
    </table>
    <table class="EMR-table" style="width:80%;height:100px">
        <tr>
            <td>
            <button id="printBtn" > Continue</button></td>
        </tr>
    </table>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn3');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container3').childNodes;
        c[9].style.color="blue";
    }
</script>
@endsection
