@extends('layout.master')
@section('title', 'Transfer Meter')
@section('content')

<p class="contentheader">Transfer Meter</p>
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
                        <button style="background-color:rgb(23, 108, 191);color:white;" class="modalBtn modal-button" href="#tMeter">Select</button>
                    </td>
                </tr>
        </table>
    </div>
</div>
<div id="tMeter" class="modal">
        <div class="modal-content" style="height:90%;width: 70%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Transfer Meter</h3>
            <span class="closes" href="#tMeter">&times;</span>
        </div>
        <div class="modal-body">
            <div class="modaldiv">
            <table class="EMR-table" style="height:70px;">
                <tr>
                    <th>Main Account:</th>
                    <td>10-1001-0002</td>
                    <th>Customer Type:</th>
                    <td>Public Building</td>
                </tr>
                <tr>
                    <th> </th>
                    <td>Datu Sobo Maranda</td>
                    <th>Account Status:</th>
                    <td>Active</td>
                </tr>
                <tr>
                    <th> </th>
                    <td>Baranggay Baclayan Lilod, Ditsaan-Ramain, LDS</td>
                    <th>Barangay:</th>
                    <td>Baclayan Lilod</td>
                </tr>
            </table>
            <hr>
            <table border= 0 class="EMR-table" style="height:70px;">
                <tr>
                    <th style="font-size:12px;">Service Memo No.:</th>
                    <td><input style="font-size:12px;" type="text" value="TM0321000000001"></td>
                    <th style="font-size:12px;">Inspector:</th>
                    <td><input style="font-size:12px;" type="text" placeholder="Inspector"></td>
                    <th style="font-size:12px;">Date/time:</th>
                    <td><input style="font-size:12px;" type="date"> </td>
                    <th style="font-size:12px;"> </th>
                    <td><input style="font-size:12px;" type="time"> </td>
                </tr>
                <tr>
                    <th style="font-size:12px;"> Service Memo Date.:</th>
                    <td><input type="text" value="00/00/0000"></td>
                    <th style="font-size:12px;">SD Install Fee:</th>
                    <td><input style="font-size:12px;" type="text" value="1.00"></td>
                    <th style="font-size:12px;"> OR #:</th>
                    <td><input style="font-size:12px;" type="type" value="1.00"> </td>
                    <th style="font-size:12px;">Date:</th>
                    <td><input style="font-size:12px;" type="date"> </td>
                </tr>
                <tr>
                    <th style="font-size:12px;">Area Code:</th>
                    <td><input style="font-size:12px;" type="text" class="input-Txt" hfre="#accNo" placeholder="Select Area Code"></td>
                    <th style="font-size:12px;">Meter Install Fee:</th>
                    <td><input style="font-size:12px;" type="text" value="1.00"></td>
                    <th style="font-size:12px;">OR #:</th>
                    <td><input style="font-size:12px;" type="type" value="1.00"> </td>
                    <th style="font-size:12px;">Date:</th>
                    <td><input style="font-size:12px;" type="date"> </td>
                </tr>
                <tr>
                    <th style="font-size:12px;">Town Code:</th>
                    <td><input style="font-size:12px;" type="text" class="input-Txt" hfre="#town" placeholder="Select Town Code"></td>
                    <th style="font-size:12px;">Meter Transfer Fee:</th>
                    <td><input style="font-size:12px;" type="text" value="1.00"></td>
                    <th style="font-size:12px;">OR #:</th>
                    <td><input style="font-size:12px;" type="type" value="1.00"> </td>
                    <th style="font-size:12px;">Date:</th>
                    <td><input style="font-size:12px;" type="date"> </td>
                </tr>
                <tr>
                    <th style="font-size:12px;">Route Code:</th>
                    <td><input style="font-size:12px;" type="text" class="input-Txt" hfre="#route" placeholder="Select Route Code"></td>
                    <th style="font-size:12px;">Charge Fee:</th>
                    <td><input style="font-size:12px;" type="text" value="1.00"></td>
                    <th style="font-size:12px;">OR #:</th>
                    <td><input style="font-size:12px;" type="type" value="1.00"></td>
                    <th style="font-size:12px;">Date:</th>
                    <td><input style="font-size:12px;" type="date"> </td>
                </tr>
                <tr>
                    <th style="font-size:12px;">Barangay Code:</th>
                    <td><input style="font-size:12px;" type="text" class="input-Txt" hfre="#route" placeholder="Select Barangay Code"></td>
                    <th style="font-size:12px;">Sequence No.:</th>
                    <td><input style="font-size:12px;" type="type" value="1.00"></td>
                    <th> </th>
                    <td>  </td>
                    <th > </th>
                    <td>  </td>
                </tr>
                <tr>
                    <th style="font-size:12px;">Sitio Code:</th>
                    <td><input style="font-size:12px;" type="text" class="input-Txt" hfre="#route" placeholder="Select Barangay Code"></td>
                    <th style="font-size:12px;font-weight:bold;">Service Drop</th>
                    <td> </td>
                    <th> </th>
                    <td>  </td>
                    <th > </th>
                    <td>  </td>
                </tr>
                <tr>
                    <th style="font-size:12px;">Account No:</th>
                    <td><input style="font-size:12px;" type="text" class="input-Txt" hfre="#accNo" placeholder="Select Account Number"></td>
                    <th style="font-size:12px;font-weight:bold;">Tapping Point:</th>
                    <td><input style="font-size:12px;" type="type" value="P002 - Pole #2"></td>
                    <th> </th>
                    <td>  </td>
                    <th > </th>
                    <td>  </td>
                </tr>
                <tr>
                    <th style="font-size:12px;">Consumer:</th>
                    <td><input style="font-size:12px;" type="text" value="Ambolong, Marawi"></td>
                    <th style="font-size:12px;">Length:</th>
                    <td><input style="font-size:12px;" type="text" value="1.00"></td>
                    <th style="font-size:12px;">Cor. X:</th>
                    <td><input style="font-size:12px;" type="type" value="1.00"></td>
                    <th> </th>
                    <td> </td>
                </tr>
                <tr>
                    <th style="font-size:12px;">Nearest Consumer:</th>
                    <td><input style="font-size:12px;" type="text" value="Ambolong, Marawi"></td>
                    <th style="font-size:12px;">Wire Size:</th>
                    <td><input style="font-size:12px;" type="text" value="1.00"></td>
                    <th style="font-size:12px;">Cor. Y:</th>
                    <td><input style="font-size:12px;" type="type" value="1.00"></td>
                    <th> </th>
                    <td> </td>
                </tr>
                <tr>
                    <th> </th>
                    <td> </td>
                    <th style="font-size:12px;">Wire Type:</th>
                    <td><input style="font-size:12px;" type="text" value="1.00"></td>
                    <th style="font-size:12px;">Cor. Z:</th>
                    <td><input style="font-size:12px;" type="type" value="1.00"></td>
                    <th> </th>
                    <td> </td>
                </tr>
            </table>
            <hr>
            <table border= 0 class="EMR-table" style="height:70px;">
                <tr>
                    <th style = "font-size:12px">Transferred By</th>
                    <td><input style = "font-size:12px" type="text" placeholder="Transferred By"></td>
                    <th style = "font-size:12px">Transfer Date</th>
                    <td><input style = "font-size:12px" type="date"></td>
                </tr>
                <tr>
                    <th style = "font-size:12px">Transfer Reason</th>
                    <td><input style = "font-size:12px" type="text" placeholder="Transfer Reason"></td>
                    <th style = "font-size:12px">Transfer Time</th>
                    <td><input style = "font-size:12px" type="time"></td>
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
