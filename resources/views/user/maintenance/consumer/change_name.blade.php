@extends('layout.master')
@section('title', 'Change Name')
@section('content')

<p class="contentheader">Change Consumer Information</p>
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
                        <button style="background-color:rgb(23, 108, 191);color:white;" class="modalBtn modal-button" href="#cConsumerType">Select</button>
                    </td>
                </tr>
        </table>
    </div>
</div>
<div id="cConsumerType" class="modal">
        <div class="modal-content" style="width: 70%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Change Consumer Information</h3>
            <span class="closes" href="#cConsumerType">&times;</span>
        </div>
        <div class="modal-body">
            <div class="modaldiv">
            <table class="EMR-table" style="color:black;height:70px;">
                <tr>
                    <th>Main Account:</th>
                    <td>10-1001-0002</td>
                    <th>Customer Type:</th>
                    <td>Public Building</td>
                </tr>
                <tr>
                    <th> </th>
                    <td>Datu Sobo Maranda</td>
                    <th style="width:17%;">Account Status:</th>
                    <td>D</td>
                </tr>
                <tr>
                    <th> </th>
                    <td>Baranggay Baclayan Lilod, Ditsaan-Ramain, LDS</td>
                </tr>
            </table>
            <hr>
            <table border= 0 class="EMR-table" style="color:black;height:70px;">
                <tr>
                    <th>Surname:</th>
                    <td><input style="text" placeholder="Surname"></td>
                    <th>Birthday:</th>
                    <td><input type="date"></td>
                </tr>
                <tr>
                    <th>Firstname:</th>
                    <td><input style="text" placeholder="Firstname"> </td>
                    <th>Member Type</th>
                    <td>
                        <select name="Sort">
                        <option value="volvo">Single</option>
                        <option value="saab">Married</option>
                        </select></td>
                </tr>
                <tr>
                     <th>Middlename:</th>
                     <td><input style="text" placeholder="Middlename"> </td>
                    <th>Board Res. No:</th>
                    <td><input style="text" placeholder="Board No.">  </td>
                </tr>
                <tr>
                    <th>Spouse/Co Member:</th>
                    <td><input style="text" placeholder="Spouse"> </td>
                    <th>Mem. Approval:</th>
                    <td><input style="text" value ="00/00/0000"> </td>
                </tr>
                <tr>
                    <th>Relationship:</th>
                    <td><input type="text" placeholder="Relationship"></td>
                    <th>Document No.</th>
                    <td><input type="text" placeholder="Document No."></td>
                </tr>
                <tr>
                    <th>Address:</th>
                    <td><input type="text" placeholder="Address"></td>
                </tr>
            </table>
            <table border= 0 class="EMR-table" style="color:black;height:70px;">
                <tr>
                    <th>Membership Fee:</th>
                    <td>1,150.00</td>
                    <th>Membership OR#:</th>
                    <td>03000004370</td>
                    <th>Membership Fee:</th>
                    <td>00/00/0000</td>
                </tr>
                <tr>
                    <th>Reason:</th>
                    <td colspan=5><input type="text" placeholder="Reason"></td>
                </tr>
                <tr>
                    <th>Change By:</th>
                    <td colspan=5><input type="text" placeholder="Changed By"></td>
                </tr>
                <tr><td> </td></tr>
                <tr>
                <td colspan="6">
                    <button class="modal-button" style="float:right;color:white;background-color:rgb(23,108,191);width:70px;height: 40px;">
                        Save
                    </button>
                </td>
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
