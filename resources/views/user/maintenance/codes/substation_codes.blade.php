@extends('layout.master')
@section('title', 'Substation Codes')
@section('content')

<p class="contentheader">Substation Codes</p>
<div class="main">
    <div style="margin-top:30px;overflow-x:hidden;height:150px;width: 80%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
        <table  style="text-align:center;margin:auto;width: 100%;">
                <tr style="border-bottom:1px solid black;">
                    <th>
                       Substation Code
                    </th>
                    <th>
                        Description
                    </th>
                    <th>
                        Rating\MVA
                    </th>
                    <th>
                        Primarily Voltage
                    </th>
                    <th>
                        Secondary Voltage
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                <tr style="background-color:white; color:black;border-bottom:1px solid black;">
                    <td>
                        1
                    </td>
                    <td>
                        Dansalan - A
                    </td>
                    <td>
                        40.00
                    </td>
                    <td>
                        69.00
                    </td>
                    <td>
                      13.20
                    </td>
                    <td>
                        <button class="modalBtn" style="background-color:rgb(23, 108, 191);color:white;" >Edit</button>
                        <button class="modalBtn" style="background-color:red;color:white;" >Delete</button>
                    </td>
                </tr>
        </table>
    </div>
    <hr>
    <table border=0 class="EMR-table" style="width:80%;height:100px">
        <tr>
            <td> ACAM: </td>
            <td colspan = 2><input type = "text" value="514-502-00-000"> </td>
        </tr>
        <tr>
            <th>X/R Ratio:</th>
            <td><input type="text" value="0.00"></td>
            <th>Coreloss:</th>
            <td><input type="text" value="0.00"></td>
            <th>NO Load Loss:</th>
            <td><input type="text" value="0"></td>
        </tr>
        <tr>
            <th>Exciting Current:</th>
            <td><input type="text" value="0.0"></td>
            <th>Copperloss:</th>
            <td><input type="text" value="0.0"></td>
            <th>Connection Type:</th>
            <td><input type="text" value=" "></td>
        </tr>
        <tr>
            <th>% Impedence:</th>
            <td><input type="text" value="0.00%"></td>
        </tr>
    </table>
    <table class="EMR-table" style="width:80%;height: 100px;">
        <tr>
            <td><button id="printBtn" style="float:left;"> Create</button>
            <button id="printBtn" style="float:left;"> Print</button></td>
        </tr>
    </table>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn3');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container3').childNodes;
        c[5].style.color="blue";
    }
</script>
@endsection
