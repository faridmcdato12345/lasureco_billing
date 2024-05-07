@extends('layout.master')
@section('title', 'Data Entry of KWH Meter')
@section('content')

<p class="contentheader">Data Entry of KWH Meter</p>
<div class="main">
    <table class="EMR-table" style="height:100px;">
        <tr>
            <td style="width:15%;">Brand:</td>
            <td><input style="width:30%;" type="text" class="input-Txt" href="#accNo" placeholder="Select Brand"></td>
        </tr>
    </table>
    <div style="margin-top:30px;overflow-x:hidden;height:150px;width: 100%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
        <table  style="border-right:1px solid black;text-align:center;margin:auto;width: 100%;">
                <tr style="border-right:1px solid black;border-bottom:1px solid black;">
                    <th >
                       Serial No.
                    </th>
                    <th>
                        Lasureco Seal
                    </th>
                    <th>
                        ERC Seal
                    </th>
                    <th>
                        Catalog
                    </th>
                    <th>
                        Class
                    </th>
                    <th>
                        Rr
                    </th>
                    <th>
                        Kh
                    </th>
                    <th>
                        Ampere
                    </th>
                    <th>
                        Type
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Jaws
                    </th>
                    <th>
                        Account No.
                    </th>
                    <th>
                        Owner
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                <tr style="background-color:white; color:black;border-bottom:1px solid black;">
                    <td style="border-right:1px solid black;">
                        INSIDE METER
                    </td>
                    <td style="border-right:1px solid black;">

                    </td style="border-right:1px solid black;">
                    <td style="border-right:1px solid black;">
                        0000021
                    </td>
                    <td style="border-right:1px solid black;">

                    </td>
                    <td style="border-right:1px solid black;">
                        100
                    </td>
                    <td style="border-right:1px solid black;">

                    </td>
                    <td style="border-right:1px solid black;">
                        0.00
                    </td>
                    <td style="border-right:1px solid black;">
                      60
                    </td>
                    <td style="border-right:1px solid black;">
                      E
                    </td>
                    <td style="border-right:1px solid black;">
                      1
                    </td>
                    <td style="border-right:1px solid black;">
                     0
                    </td>
                    <td style="border-right:1px solid black;">
                     - -
                    </td>
                    <td style="border-right:1px solid black;">
                      Coop Owned
                    </td>
                    <td>
                        <button class="modalBtn" style="background-color:rgb(23, 108, 191);color:white;" >Edit</button>
                        <button class="modalBtn" style="background-color:red;color:white;" >Delete</button>
                    </td>
                </tr>
        </table>
    </div>
    <table style="margin:auto;width:100%">
        <tr>
            <th>Account Name:</th>
            <td> </td>
            <th>Address:</th>
            <td> </td>
        </tr>
    </table>
    <hr>
    <table border=0 class="EMR-table" style="width:100%;height:100px">
        <tr>
            <th>Form:</th>
            <td><input type="text" value="I210"></td>
            <th>Wire: </th>
            <td><input type="text" value="5.5"></td>
            <th>Volts: </th>
            <td><input type="text" value="230"></td>
            <th>Phase: </th>
            <td><input type="text" value="1"></td>
            <th>Digits: </th>
            <td><input type="text" value="5"></td>
        </tr>
    </table>
    <table border=0 class="EMR-table" style="width:100%;height:100px">
        <tr>
            <th colspan=2>--> KWH <--</th>
            <th> </th>
            <th>--> KW <--</th>
            <th> </th>
            <th>--> KVARh <--</th>
            <th> </th>
        </tr>
        <tr>
            <td>Accuracy %:</td>
            <td><input type="type" value="0.00%"></td>
            <td>Demand Type:</td>
            <td><input type="type" value=" "></td>
            <td>Multiplier:</td>
            <td><input type="type" value="1.0"></td>
        </tr>
        <tr>
            <td>As Found:</td>
            <td><input type="type" value="0.00%"></td>
            <td>Multiplier:</td>
            <td><input type="type" value=" "></td>
            <td>Prev KVARH Rdng:</td>
            <td><input type="type" value="1.0"></td>
        </tr>
        <tr>
            <td>As Left:</td>
            <td><input type="type" value="0.00%"></td>
            <td>Prev Dem Rdng:</td>
            <td><input type="type" value=" "></td>
        </tr>
        <tr>
            <td>Multiplier:</td>
            <td><input type="type" value="0.00%"></td>
            <td>Min Demand:</td>
            <td><input type="type" value="0.000"></td>
        </tr>
        <tr>
            <td>Prev Energy Rdng:</td>
            <td><input type="type" value="0.00%"></td>
            <td>Max Demand:</td>
            <td><input type="type" value="0.000"></td>
        </tr>
    </table>
    <table border=0 class="EMR-table" style="width:100%;height:100px">
        <tr>
            <td>Remarks:</td>
            <td><input type="type" value="0.00%"></td>
            <td>KWH Meter Condition:</td>
            <td><input type="type" value=" "></td>
        </tr>
    </table>
    <table  class="EMR-table" style="float:right;width:100%;height: 100px;">
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
