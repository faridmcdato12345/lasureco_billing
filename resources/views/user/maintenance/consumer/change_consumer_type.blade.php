@extends('layout.master')
@section('title', 'Change Consumer Type')
@section('content')

<p class="contentheader">Change Consumer Type</p>
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
                        <button style="background-color:rgb(23, 108, 191);color:white;" class="modalBtn modal-button" href="#AMASA3">Select</button>
                    </td>
                </tr>
        </table>
    </div>
</div>
<div id="AMASA3" class="modal">
        <div class="modal-content" style="width: 70%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Assign Main Account/ Sub Account</h3>
            <span class="closes" href="#AMASA3">&times;</span>
        </div>
        <div class="modal-body">
            <div class="modaldiv">
            <table class="EMR-table" style="height:70px;">
                <tr>
                    <th>Main Account:</th>
                    <td>10-1001-0002</td>
                    <th>Current Status:</th>
                    <td>Disconnected</td>
                </tr>
                <tr>
                    <th> </th>
                    <td>Datu Sobo Maranda</td>
                    <th style="width:17%;">Document No.:</th>
                    <td>CS0321000000001</td>
                </tr>
                <tr>
                    <th> </th>
                    <td>Baranggay Baclayan Lilod, Ditsaan-Ramain, LDS</td>
                </tr>
            </table>
            <hr>
            <table border= 0 class="EMR-table" style="height:70px;">
                <tr>
                    <th>Main Account:</th>
                    <td>10-1001-0002</td>
                    <th>Sub-Status:</th>
                    <td>
                        <select style="width:100%;" name="Sort">
                        <option value="volvo">Remove</option>
                        <option value="saab">E-wallet</option>
                        </select></td>
                </tr>
                <tr>
                    <th>Request Date/Time:</th>
                    <td><input style="width:60%" type="date" name="date"><input style="display:inline;width:40%" type="time"> </td>
                </tr>
                <tr>
                     <th>Calibration Date/Time:</th>
                    <td><input style="width:60%" type="date" name="date"><input style="display:inline;width:40%" type="time"> </td>
                    <th>Meter Brand:</th>
                    <td> </td>
                </tr>
                <tr>
                    <th>Meter S/N:</th>
                    <td>141109982</td>
                </tr>
                <tr>
                    <th>Change By</th>
                    <td><input type="text" placeholder="Changed by"></td>
                    <th>Date Changed</th>
                    <td><input type="date" name="date"></td>
                </tr>
                <tr>
                    <th>Reason:</th>
                    <td>
                        <textarea style="width:250px;height:50px;" placeholder="Reason"></textarea>
                    </td>
                    <th>Remarks:</th>
                    <td>
                        <textarea style="width:250px;height:50px;" placeholder="Remarks"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan = 6><button id="printBtn2">Save</button></td>
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
