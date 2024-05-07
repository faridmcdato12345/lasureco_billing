@extends('layout.master')
@section('title', 'Transformer')
@section('content')
<p class="contentheader">Transformer</p>
<div class="main">
    <div style="margin-top:30px;overflow-x:hidden;height:150px;width: 80%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
        <table  style="text-align:center;margin:auto;width: 100%;">
                <tr style="border-bottom:1px solid black;">
                    <th>
                       TSF
                    </th>
                    <th>
                      TSF Type
                    </th>
                    <th>
                       Serial #
                    </th>
                    <th>
                       Brand
                    </th>
                    <th>
                       Service Type
                    </th>
                    <th>
                        KVA
                    </th>
                    <th>
                        # P.Bush
                    </th>
                    <th>
                        # S.Bush
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
                        Silicon
                    </td>
                    <td>
                        00111010
                    </td>
                    <td>
                        Shilin
                    </td>
                    <td>
                        Distribution
                    </td>
                    <td>
                        25.0
                    </td>
                    <td>
                        2
                    </td>
                    <td>
                        3
                    </td>
                    <td>
                        <button class="modalBtn" style="background-color:rgb(23, 108, 191);color:white;" >Edit</button>
                        <button class="modalBtn" style="background-color:red;color:white;" >Delete</button>
                    </td>
                </tr>
        </table>
    </div>
    <hr>
    <table border=0 class="EMR-table" style="width:90%;height:100px">
        <tr>
            <th style="font-size:12px;"> Phasing Tapping: </th>
            <td><select style="width:60%;font-size:12px;" name="Sort">
                    <option value="volvo"> </option>
                    <option value="saab">E-wallet</option>
                </select> </td>
            <th style="font-size:12px;">Ext. Cur:</th>
            <td><input style="width:60%;font-size:12px;" type="text" value="0.00"></td>
            <th style="font-size:12px;">Ownership:</th>
            <td><select style="width:60%;font-size:12px;" name="Sort">
                    <option value="volvo">Coop Owned</option>
                    <option value="saab">E-wallet</option>
                </select> </td>
            <th style="font-size:12px;">Cor X:</th>
            <td><input style="width:60%;font-size:12px;" type="text" value="0.00"></td>
        </tr>
        <tr>
            <th style="font-size:12px;"> X/R Ratio: </th>
            <td><input style="width:60%;font-size:12px;" type = "text" value="0.00"> </td>
            <th style="font-size:12px;">Primary Voltage:</th>
            <td><input style="width:60%;font-size:12px;" type="text"></td>
            <th style="font-size:12px;"> </th>
            <td> </td>
            <th style="font-size:12px;">Cor Y:</th>
            <td><input style="width:60%;font-size:12px;" type="text" value="0.00"></td>
        </tr>
        <tr>
            <th style="font-size:12px;"> % Impedance: </th>
            <td><input style="width:60%;font-size:12px;" type = "text" value="0.00%"> </td>
            <th style="font-size:12px;">Sec. Voltage:</th>
            <td><input style="width:60%;font-size:12px;" type="text"></td>
            <th style="font-size:12px;">Rental Fee</th>
            <td><input style="width:60%;font-size:12px;" type="text" value="0.00"></td>
            <th style="font-size:12px;">Cor Z:</th>
            <td><input style="width:60%;font-size:12px;" type="text" value="0.00"></td>
        </tr>
        <tr>
            <th style="font-size:12px;"> No Load Loss: </th>
            <td><input style="width:60%;font-size:12px;" type = "text" value="0"> </td>
            <th style="font-size:12px;">Connection Type:</th>
            <td><input style="width:60%;font-size:12px;" type="text"></td>
            <th style="font-size:12px;">Date Pulled Out:</th>
            <td><input style="width:60%;font-size:12px;" type="text" value="00/00/0000"></td>
        </tr>
        <tr>
            <th style="font-size:12px;"> Copperloss: </th>
            <td><input style="width:60%;font-size:12px;" type = "text" value="0"> </td>
            <th style="font-size:12px;">Install Date:</th>
            <td><input style="width:60%;font-size:12px;" type="text" value="00/00/0000"></td>
        </tr>
        <tr>
            <th style="font-size:12px;"> Coreloss: </th>
            <td><input style="width:60%;font-size:12px;" type = "text" value="0"> </td>
            <th style="font-size:12px;">Install By:</th>
            <td><input style="width:60%;font-size:12px;" type="text" value="00/00/0000"></td>
        </tr>
        <tr>
            <th style="font-size:12px;"> TLTR: </th>
            <td><input style="width:60%;font-size:12px;" type = "text" value="0"> </td>
            <th style="font-size:12px;">Location:</th>
            <td colspan=2><input style="width:60%;font-size:12px;" type="text"></td>
        </tr>
        <tr>
            <th style="font-size:12px;"> Pole ID: </th>
            <td><input style="width:60%;font-size:12px;" type = "text"> </td>
            <th style="font-size:12px;">Remarks:</th>
            <td colspan=2><input style="width:60%;font-size:12px;" type="text"></td>
        </tr>
    </table>
    <table class="EMR-table" style="width:80%;height: 100px;">
        <tr>
            <td><button id="printBtn" style="border-radius:5px;height:40px;float:left;"> Create</button>
            <button id="printBtn" style="border-radius:5px;height:40px;float:left;"> Print</button></td>
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
