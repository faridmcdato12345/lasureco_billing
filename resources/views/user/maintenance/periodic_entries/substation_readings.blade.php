@extends('layout.master')
@section('title', 'Substation Readings')
@section('content')

<p class="contentheader">Substation Readings</p>
<div class="main"><br>
    <table border = 0 class="EMR-table" style="color:white;height:70px">
        <tr>
            <th style="width:12%;">Billing Period</th>
            <td><input style="width:30%;" type="month"></td>
        </tr>
    </table>
    <div style="margin-top:30px;overflow-x:hidden;height:150px;width: 80%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
        <table  style="text-align:center;margin:auto;width: 100%;">
                <tr style="border-bottom:1px solid black;">
                    <th>
                        Substation Code
                    </th>
                    <th>
                        KWH Purchased
                    </th>
                    <th>
                        Total Sales
                    </th>
                    <th>
                        Lineloss KWH
                    </th>
                    <th>
                        % Loss
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                <tr style="background-color:white; color:black;border-bottom:1px solid black;">
                    <td>

                    </td>
                    <td>
                        0.00
                    </td>
                    <td>
                        0.00
                    </td>
                    <td>
                        0.00
                    </td>
                    <td>
                        0%
                    </td>
                    <td>
                        <button class="modalBtn" style="background-color:rgb(23, 108, 191);color:white;" >Edit</button>
                        <button class="modalBtn" style="background-color:red;color:white;" >Delete</button>
                    </td>
                </tr>
        </table>
    </div>
    <table border = 0 class="EMR-table" style="width:80%;height:100px">
        <tr>
            <td>
            <button id="printBtn"> Print</button>
            <button id="printBtn"> Create</button></td>
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
