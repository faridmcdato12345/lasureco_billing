@extends('layout.master')
@section('title', 'Teller Non-Bill Collection')
@section('content')

<p class="contentheader"> Teller Non-Bill Collection </p>
<div class="main">
    <table class="content-table" id="collectionNB">
        <tr>
            <td colspan=2> 
                <input type="text" class="input-Txt" href="#area" name="consumer" placeholder="Select Consumer" readonly>
            </td>
            <td> 
                Type:
            </td>
            <td> 
                <input type="text" name="type" placeholder="Type">
            </td>
            <td rowspan=3 style="width: 30%;"> 
                <table style="height: 90%;" class="torTable"> 
                    <tr> 
                        <td style="width: 15%; text-align: center;"> 
                            TOR No.:
                        </td>
                        <td style="width: 10%"> 
                            <input type="text" name="torNo1" value="03">
                        </td>
                        <td style="width: 20%;"> 
                            <input type="text" name="torNo2" value="01888889">
                        </td>
                    </tr>
                    <tr> 
                        <td> </td>
                        <td colspan=2 style="text-align: right;"> 
                            Date: 05/26/2021
                        </td>
                    </tr>
                    <tr> 
                        <td colspan=3 style="text-align: center;"> 
                            MARAWI CITY WIDE OFFICE
                        </td>
                    </tr>
                    <tr> 
                        <td> </td>
                        <td colspan=2 style="text-align: right;"> 
                            Disable Print?
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr> 
            <td> 
                Name: 
            </td>
            <td> 
                <input type="text" name="name" placeholder="Name" readonly>
            </td>
            <td> 
                Status:
            </td>
            <td> 
                <input type="text" name="status" placeholder="Status" readonly>
            </td>
        </tr>
        <tr> 
            <td> 
                Address:
            </td>
            <td> 
                <input type="text" name="address" placeholder="Address" readonly>
            </td>
            <td> 
                MSN:
            </td>
            <td> 
                <input type="text" name="MSN" placeholder="MSN" readonly>
            </td>
        </tr>
        <tr> 
            <td colspan=5> 
                <div style="width: 100%; height: 40px; background-color: #5B9BD5; color: white; font-size: 15px; margin-top: 5px;">
                    <table style="width: 100%;"> 
                        <tr> 
                            <td> 
                                &nbsp; Fee Code
                            </td>
                            <td> 
                                Particulars
                            </td>
                            <td> 
                                Bill Month
                            </td>
                            <td> 
                                Bill Year
                            </td>
                            <td> 
                                Amount
                            </td>
                            <td> 
                                VAT
                            </td>
                            <td> 
                                Amount Due
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="background-color: white; height: 130px; color: black; width: 100%; font-size: 15px;"> 
                    <table class="TNBCTable"> 
                        <tr> 
                            <td> 
                                &nbsp; Fee Code
                            </td>
                            <td> 
                                Particulars
                            </td>
                            <td> 
                                Bill Month
                            </td>
                            <td> 
                                Bill Year
                            </td>
                            <td> 
                                Amount
                            </td>
                            <td> 
                                VAT
                            </td>
                            <td> 
                                Amount Due
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr> 
            <td colspan=3 style="width: 60%;"> 
                <table border=1 style="width: 100%; font-size: 18px;"> 
                    <tr> 
                        <td style="width: 30%;"> 
                            &nbsp; Total Collections:
                        </td>
                        <td style="width: 20%;"> 
                            0.00
                        </td>
                        <td style="width: 22%;"> 
                            No. of Stubs:
                        </td>
                        <td> 
                            0
                        </td>
                    </tr>
                </table>
            </td>
            <td colspan=2> 
                <table style="margin-top: 2px; float: right; margin-right: -7px;"> 
                    <tr> 
                        <td> 
                            Total Amount Due:
                        </td>
                        <td> 
                            <input type="number" name="amountDue" placeholder="0.00">
                        </td>
                    </tr>
                    <tr> 
                        <td> 
                            <select name="paymentType"> 
                                <option value="cash"> CASH AMOUNT </option>
                                <option value="check"> CHECK AMOUNT </option>
                            </select>
                        </td>
                        <td> 
                            <input type="number" name="paymentAmount" placeholder="0.00">
                        </td>
                    </tr>
                    <tr> 
                        <td> 
                            E-Wallet Amount:
                        </td>
                        <td> 
                            <input type="number" name="eWallAmount" placeholder="0.00">
                        </td>
                    </tr>
                    <tr> 
                        <td> 
                            Change:
                        </td>
                        <td> 
                            <input type="number" name="change" placeholder="0.00">
                        </td>
                    </tr>
                </table> 
            </td>
        </tr>
    </table>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container').childNodes;
        c[12].style.color="blue";
     }
</script>
@endsection
