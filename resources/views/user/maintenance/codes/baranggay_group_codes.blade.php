@extends('layout.master')
@section('title', 'Barangay Group Codes')
@section('content')

<p class="contentheader">Barangay Group Codes</p>
<div class="main">
    <table class="EMR-table" style="height:100px;">
        <tr>
            <td style="width:15%;">Town:</td>
            <td><input style="width:30%;" type="text" class="input-Txt" href="#town" placeholder="Select Town Code"></td>
        </tr>
    </table>
    <div style="margin-top:30px;overflow-x:hidden;height:150px;width: 80%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
        <table  style="text-align:center;margin:auto;width: 100%;">
                <tr style="border-bottom:1px solid black;">
                    <th>
                        Barangay Code
                    </th>
                    <th>
                        Description
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                <tr style="background-color:white; color:black;border-bottom:1px solid black;">
                    <td>
                        F1
                    </td>
                    <td>
                        Marawi City
                    </td>
                    <td>
                        <button class="modalBtn" style="background-color:rgb(23, 108, 191);color:white;" >Edit</button>
                        <button class="modalBtn" style="background-color:red;color:white;" >Delete</button>
                    </td>
                </tr>
        </table>
    </div>
    <table class="EMR-table" style="width:80%;height:100px">
        <tr>
            <td><button id="printBtn" > Create</button>
            <button id="printBtn" > Print   </button></td>
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
