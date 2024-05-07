@extends('layout.master')
@section('title', 'Assign Payor')
@section('content')

<p class="contentheader">Assign Payor</p>
<div class="main">
    <div style="margin-top:30px;overflow-x:hidden;height:150px;width: 80%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
        <table  style="margin:auto;width: 100%;">
                <tr style="border-bottom:1px solid black;">
                    <th>
                        Consumer Name
                    </th>
                    <th>
                        Account Number
                    </th>
                    <th>
                        Sequence Number
                    </th>
                    <th>
                        Meter S/N
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                <tr style="background-color:white; color:black;border-bottom:1px solid black;">
                    <td>
                        Aamino
                    </td>
                    <td>
                        38-3807-0058
                    </td>
                    <td>
                        58
                    </td>
                    <td>

                    </td>
                    <td>
                        <button style="background-color:rgb(23, 108, 191);color:white;" class="modalBtn modal-button" href="#AMASA">Select</button>
                    </td>
                </tr>
        </table>
    </div>
</div>
<div id="AMASA" class="modal">
        <div class="modal-content" style="width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Assign Main Account/ Sub Account</h3>
            <span class="closes" href="#AMASA">&times;</span>
        </div>
        <div class="modal-body">
            <div class="modaldiv">
            <table class="EMR-table" style="height:70px;">
                <tr>
                    <th style="width:17%;">Main Account:</th>
                    <td>10-1001-0002</td>
                </tr>
                <tr>
                    <th> </th>
                    <td>Datu Sobo Maranda</td>
                </tr>
                <tr>
                    <th> </th>
                    <td>Baranggay Baclayan Lilod, Ditsaan-Ramain, LDS</td>
                </tr>
            </table>
            <div style="border-bottom:1px;overflow-x:hidden;height:150px;width: 100%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
                <table border=1 style="width:100%">
                    <tr>
                        <td class = "thead">
                        Account
                        </td>
                        <td class = "thead">
                        Consumer
                        </td>
                        <td class = "thead">
                        Address
                        </td>
                        <td class = "thead">
                        MSN
                        </td>
                        <td class = "thead">
                        Action
                        </td>
                    </tr>
                    <tr style="background-color:white; color:black;border-bottom:1px solid black;">
                        <td>10-1001-002</td>
                        <td>Datu Sobo Maranda</td>
                        <td>Brgy. Baclayan Lilod, Ditsaan-Ramain, LDS</td>
                        <td>NM</td>
                        <td>
                        <button style="background-color:rgb(23, 108, 191);color:white;" class="modalBtn modal-button" href="#AMASA">Edit</button>
                        <button style="background-color:red;color:white;" class="modalBtn modal-button" href="#AMASA">Delete</button>
                        </td>
                    </tr>
                </table>
            </div>
            <table  class="EMR-table" style="width:100%;height:50px;">
                <tr>
                    <td colspan = 6><button style="margin-right:-1.2px;" id="printBtn2">Add</button></td>
                </tr>
            </table>
            </div>
        </div>
    </div>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn3');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container3').childNodes;
        c[1].style.color="blue";
    }
</script>
@endsection
