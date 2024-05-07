@extends('layout.master')
@section('title', 'Collection for Multiple Accounts')
@section('content')

<p class="contentheader"> Teller Power Bill Collection w/ Multiple Accounts </p>
<div class="main">
    <table class="CMA" style="width: 97%; margin-right: auto; margin-left: auto;">
        <tr>
            <td style="width: 21.50%;"> 
                Main Account:
            </td>
            <td style="width: 21.50%;"> 
                <input type="text" name="Name" placeholder="Name" readonly>
            </td>
            <td rowspan=2 style="width: 25%"> 
                <table class="TSMTable"> 
                    <tr> 
                        <td> 
                            Type:
                        </td>
                        <td> 
                            <input type="text" name="type" placeholder="Type">
                        </td>
                    </tr>
                    <tr> 
                        <td> 
                            Status:
                        </td>
                        <td> 
                            <input type="text" name="status" placeholder="Status">
                        </td>
                    </tr>
                    <tr> 
                        <td> 
                            MSN:
                        </td>
                        <td> 
                            <input type="text" name="MSN" placeholder="MSN">
                        </td>
                    </tr>
                </table>
            </td>
            <td rowspan=2 style="width: 30%;"> 
                <table> 
                    <tr> 
                        <td style="width: 20%;">
                            TOR No.:
                        </td>
                        <td style="width: 15%;"> 
                            <input type="text" name="torNo1" value="03">
                        </td>
                        <td style="width: 35%;"> 
                            <input type="text" name="torNo2" value="018888889">
                        </td>
                    </tr>
                    <tr> 
                        <td colspan=3 style="text-align: right;"> 
                            Date: 05/26/2021 &nbsp;
                        </td>
                    </tr>
                    <tr> 
                        <td colspan=3 style="text-align: right;"> 
                            Disable Print &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr> 
            <td>
                <input type="text" class="input-Txt" name="account" href="#route" placeholder="Select Account" readonly>
            </td>
            <td> 
                <input type="text" name="address" placeholder="Address" readonly>
            </td>
        </tr>
        <tr> 
            <td colspan=4> 
                <div style="height: 35px; width: 99%; background-color: #5B9BD5; color: white; margin-left: auto; margin-right: auto; margin-top: 10px;"> 
                    <table style="width: 100%; height: 40px; font-size: 15px;"> 
                        <tr> 
                            <td> 
                                &nbsp; Acct No.
                            </td>
                            <td> 
                                Period No.
                            </td>
                            <td> 
                                KWH Used
                            </td>
                            <td> 
                                Bill Balance
                            </td>
                            <td> 
                                Surcharge
                            </td>
                            <td> 
                                LGU %
                            </td>
                            <td> 
                                LGU 5%
                            </td>
                            <td> 
                                BAPA Disc.
                            </td>
                            <td> 
                                BAPA Fare
                            </td>
                            <td> 
                                Integration
                            </td>
                            <td> 
                                AdvancePay.
                            </td>
                            <td> 
                                Amount Due
                            </td>
                        </tr>
                    </table> 
                </div>
                <div style="width: 99%; margin-right: auto; margin-left: auto; background-color: white; height: 150px; overflow-x: hidden;"> 
                    <table class="CMATable"> 
                        <tr> 
                            <td> 
                                &nbsp; Acct No.
                            </td>
                            <td> 
                                Period No.
                            </td>
                            <td> 
                                KWH Used
                            </td>
                            <td> 
                                Bill Balance
                            </td>
                            <td> 
                                Surcharge
                            </td>
                            <td> 
                                LGU %
                            </td>
                            <td> 
                                LGU 5%
                            </td>
                            <td> 
                                BAPA Disc.
                            </td>
                            <td> 
                                BAPA Fare
                            </td>
                            <td> 
                                Integration
                            </td>
                            <td> 
                                AdvancePay.
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
            <td colspan=4> 
                <table style="width: 99%; margin-left: auto; margin-right: auto; margin-top: 10px;"> 
                    <tr> 
                        <td> 
                            &nbsp; Consumer:
                        </td>
                        <td> 
                        </td>
                        <td> 
                            Disco Date:
                        </td>
                        <td> 
                            00/00/0000
                        </td>
                        <td> 
                            Due Date:
                        </td>
                        <td> 
                            00/00/0000
                        </td>
                        <td style="text-align: right;"> 
                            <button class="modal-button"> 
                                Remove Surcharge
                            </button>
                        </td>
                    </tr>
                </table>
            </td> 
            </td>
        </tr>
        <tr> 
            <td colspan=4>
                <table style="width: 99%; margin-left: auto; margin-right: auto;">
                <tr>
                    <td style="width: 60%;">
                        <div style="width: 97%; margin-left: auto; margin-right: auto; height: 35px; background-color: #5B9BD5; color: white;"> 
                            <table style="width: 100%; font-size: 15px; height: 40px;"> 
                                <tr> 
                                    <td> 
                                        &nbsp; Acctng Code
                                    </td>
                                    <td> 
                                        Description
                                    </td>
                                    <td> 
                                        VAT Amount
                                    </td>
                                    <td> 
                                        Amort Amount
                                    </td>
                                    <td> 
                                        Total Amount
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="width: 97%; margin-left: auto; margin-right: auto; background-color: white; height: 150px; overflow-x: hidden;"> 
                            <table class="ADVAT"> 
                                <tr> 
                                    <td> 
                                        &nbsp; Acctng Code
                                    </td>
                                    <td> 
                                        Description
                                    </td>
                                    <td> 
                                        VAT Amount
                                    </td>
                                    <td> 
                                        Amort Amount
                                    </td>
                                    <td> 
                                        Total Amount
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <table style="margin-top: 5px; width: 97%; margin-left: auto; margin-right: auto; font-size: 20px;"> 
                            <tr> 
                                <td> 
                                    &nbsp; Total Collections:
                                </td>
                                <td> 
                                    0.00
                                </td>
                                <td> 
                                    No. of TOR:
                                </td>
                                <td> 
                                    0
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table class="amountTable"> 
                            <tr> 
                                <td> 
                                    Total Selected Amount: &nbsp;
                                </td>
                                <td> 
                                    <input type="number" name="totSelAmt" placeholder="0.00">
                                </td>
                            </tr>
                            <tr> 
                                <td> 
                                    Amount to be Paid: &nbsp;
                                </td>
                                <td> 
                                    <input type="number" name="amtToBePaid" placeholder="0.00">
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
                                    <input type="number" name="paymentAmt" placeholder="0.00"> 
                                </td>
                            </tr>
                            <tr> 
                                <td>
                                    E-Wallet Amount: &nbsp;
                                </td>
                                <td> 
                                    <input type="number" name="ewallAmt" placeholder="0.00">
                                </td>
                            </tr>
                            <tr> 
                                <td> 
                                    Overpayment Amount: &nbsp;
                                </td>
                                <td> 
                                    <input type="number" name="OPAmt" placeholder="0.00">
                                </td>
                            </tr>
                            <tr> 
                                <td> 
                                    Change: &nbsp;
                                </td>
                                <td>  
                                    <input type="number" name="change" placeholder="0.00">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            <td>
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
