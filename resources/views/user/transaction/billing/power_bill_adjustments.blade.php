@extends('layout.master')
@section('title', 'Power Bill Adjustments')
@section('content')
<style>
    /* button:hover{
       background-color:aquamarine; 
    } */
</style>
<p class="contentheader">Power Bill Adjustments</p>
<div class="main">
    <br><br>
    <div class="d-flex flex-row">
        <div class="col-5">
            <!-- <div style="overflow-y:scroll;height:990px" > -->
            <table border=0  style="color:white;">
                <tr>
                    <td class="thead">
                       Account No:
                    </td>
                    <td>
                    <input type="text" style="border-radius:3px;height:35px;margin-left:10px;color:black" id = "accNoID" onclick="showConsumerAcct()" name="account_number" placeholder="Select Account No." readonly>
                    </td>
                </tr>
                <tr>
                    <td class="thead">
                       Billing Period:
                    </td>
                    <td>
                        <input type="month" id="dateP" onfocusout="datePout(this.value);" style="border-radius:3px;height:35px;margin-left:10px;color:black" >
                    </td>
                </tr>
            </table>
            <br>
            <table id = "info" >
                <!-- <tr>
                    <td>Acct Name: </td>
                </tr>
                <tr>
                    <td>Acct Address: </td>
                </tr>
                <tr>
                    <td>Acct Type: </td>
                </tr>
                <tr>
                    <td>Acct Status: </td>
                </tr> -->
            </table>
            <hr>
            <table>
                <tr>
                    <td>Adjustment Date: </td>
                    <td><input id = "dateAdjust" type="text" style="border-radius:3px;height:35px;margin-left:10px;color:black" readonly></td>
                </tr>
                <tr>
                    <td>Adjustment No: </td>
                    <td><input type="text" id = "adjustNo" style="border-radius:3px;height:35px;margin-left:10px;color:black"  readonly></td>
                </tr>
                <tr>
                    <td>Billing Period: </td>
                    <td><input id="BillingPeriod" type="text" style="border-radius:3px;height:35px;margin-left:10px;color:black" readonly></td>
                </tr>
                <tr>
                    <td>Bill No.: </td>
                    <td><input id = "billNo" type="text" style="border-radius:3px;height:35px;margin-left:10px;color:black" readonly></td>
                </tr>
            </table>
            <hr>
            <label style="font-size:12px;">Adjustment Type</label>
            <center>
            <label style="font-size:24px;">ADJUSTED</label>
            </center>
            <hr>
            <table>
                <tr>
                    <td>Adjustment Amount: </td>
                    <td><input  id = "adjAmount" type="number" formatter="currency" step="0.01" style="border-radius:3px;height:35px;margin-left:10px;color:black"></td>
                </tr>
                <tr>
                    <td>Adjusted kWh: </td>
                    <td><input id = "adjkWh" type="number" formatter="currency" step="0.01" style="border-radius:3px;height:35px;margin-left:10px;color:black"></td>
                </tr>
            </table>
            <hr>
            <!-- <table style="margin-left:25px">
                <tr>
                    <td>Prepared By: </td>
                </tr>
                <tr>
                    <td><input type="text" style="width:113%;border-radius:3px;height:35px;margin-left:50px;color:black"></td>
                </tr>
                <tr>
                    <td><input type="text" style="width:113%;border-radius:3px;height:35px;margin-left:50px;color:black"></td>
                </tr>
                <tr>
                    <td>Checked By: </td>
                </tr>
                <tr>
                    <td><input type="text" style="width:113%;border-radius:3px;height:35px;margin-left:50px;color:black"></td>
                </tr>
                <tr>
                    <td><input type="text" style="width:113%;border-radius:3px;height:35px;margin-left:50px;color:black"></td>
                </tr>
                <tr>
                    <td>Approved By: </td>
                </tr>
                <tr>
                    <td><input type="text" style="width:113%;border-radius:3px;height:35px;margin-left:50px;color:black"></td>
                </tr>
                <tr>
                    <td><input type="text" style="width:113%;border-radius:3px;height:35px;margin-left:50px;color:black"></td>
                </tr>
            </table> -->
            <!-- </div> -->
        </div>
        <div class="col-7" style="border-left-style: solid;border-left-color: black;border-left-width: 2px;">
        <div style="overflow-y:scroll;height:990px" >
            <table border=0  style="color:white;">
                <tr>
                    <th> </th>
                    <th>As Adjusted</th>
                    <th>As Billed</th>
                </tr>
                <tr>
                    <td>Present Reading</td>
                    <td><input id = "presReading1" onfocusout= "presReading()" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" formatter="currency" step="0.01" placeholder="0.00"></td>
                    <td><input id = "presReading" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" value="0.00" formatter="currency" step="0.01" readonly></td>
                </tr>
                <tr>
                    <td>Previous Reading</td>
                    <td><input id = "prevReading1" onfocusout= "presReading()" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" formatter="currency" step="0.01" placeholder="0.00"></td>
                    <td><input id = "prevReading" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" value="0.00" formatter="currency" step="0.01" readonly></td>
                </tr>
                <tr>
                    <td>KWH Used</td>
                    <td><input id = "kUsed1" onfocusout="datakw(this)" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" formatter="currency" step="0.01" placeholder="0.00"></td>
                    <td><input id = "kUsed" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" formatter="currency" step="0.01" readonly></td>
                </tr>
                <tr>
                    <td>Demand Used</td>
                    <td><input id = "dUsed1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" formatter="currency" step="0.01" placeholder="0.00"></td>
                    <td><input id = "dUsed" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" formatter="currency" step="0.01" readonly></td>
                </tr>
            </table>
            <button id = "butt" style="width:100%;" class="form-control btn btn-success mt-1" onclick="kUsed(this)">calculate</button>
            <hr>
            <table style="width:100%">
                <tr>
                    <td><button id="printBtn" onclick="openCharges1()">Charges1</button></td>
                    <td><button id="printBtn" onclick="Chrgs2()">Charges2</button></td>
                    <td><button id="printBtn" onclick="openCharges3()">Charges3</button></td>
                    <td>Due Date:</td>
                    <td><input id="dueDate" style="width:60%;border-radius:3px;height:35px;margin-left:10px;color:black" type="text"></td>
                </tr>
            </table><br>
            <hr>
            <div style="overflow-y:scroll;height:250px">
            <table border=0  style="color:white;">
                <tr>
                    <td>M/Arrears</td>
                    <td><input  id = "mArrears1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" formatter="currency" step="0.01" placeholder="0.00"></td>
                    <td><input id = "mArrears" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" formatter="currency" step="0.01" readonly></td>
                </tr>
                <tr>
                    <td>Surchage</td>
                    <td><input id = "surchage1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" formatter="currency" step="0.01" placeholder="0.00"></td>
                    <td><input id = "surchage" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" formatter="currency" step="0.01" value="0.00" readonly></td>
                </tr>
                <tr>
                    <td>Total Due</td>
                    <td><input id = "totalDue1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" formatter="currency" step="0.01" placeholder="0.00"></td>
                    <td><input id = "totalDue" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" formatter="currency" step="0.01" readonly></td>
                </tr>
                <tr>
                    <td>Current Bill</td>
                    <td><input id = "currentBill1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" formatter="currency" step="0.01" placeholder="0.00"></td>
                    <td><input id = "currentBill" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" formatter="currency" step="0.01" readonly></td>
                </tr>
            </table>
            <hr>
            <table>
                <tr>
                    <td><button id ="btnsave" class = "btn btn-primary" onclick="adjustmentPB()" style = "margin-right:10px;width:120px;">Save</button></td>
                    <td><button class = "btn btn-danger" style = "width:120px;">Cancel</button></td>
                </tr>
            </table>
        </div>
        </div>
        </div>
    </div>
</div>
<div id="charges1" class="modal">
    <div class="modal-content" style="width: 50%;height:450px">
    <div class="modal-header" style="width: 100%;">
        <h3>Charges 1</h3>
        <button type="button" class="btn-close" onclick="closeCharges1();"></button>
    </div>
    <div class="modal-body" style="color: black;width:100%;height:600px">
            <div style="overflow-x:hidden;height:320px;width: 100%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
                <table border=0  style="color:white;margin:auto;">
                    <tr>
                        <th> </th>
                        <th>As Adjusted</th>
                        <th>As Billed</th>
                    </tr>
                    <tr>
                        <td>Gen. Sys. Chrg:</td>
                        <td><input id = "genSysCharg1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id = "genSysCharg" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>Power Act Red:</td>
                        <td><input id="powActRed1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id="powActRed" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>Fran & Ben to Host:</td>
                        <td><input id = "franBenHost1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id = "franBenHost" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>Forex Adjust Chrg:</td>
                        <td><input id = "ForAdjustChrg1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id = "ForAdjustChrg" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>Trans System Chrg:</td>
                        <td><input id =  "transSysChrg1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id =  "transSysChrg" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>Trans Demand Chrg:</td>
                        <td><input id = "TransDemChrg1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id = "TransDemChrg" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>System loss Charge:</td>
                        <td><input id = "SysLossChrg1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id = "SysLossChrg" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                </table>       
            </div>
    </div>
</div>
</div>
<div id="charges2" class="modal">
    <div class="modal-content" style="width: 50%;height:450px">
    <div class="modal-header" style="width: 100%;">
        <h3>Charges 2</h3>
        <button type="button" class="btn-close" onclick="closeCharges2();"></button>
    </div>
    <div class="modal-body" style="color: black;width:100%;height:600px">
            <div style="overflow-x:hidden;height:320px;width: 100%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
                <table border=0  style="color:white;margin:auto;">
                    <tr>
                        <th> </th>
                        <th>As Adjusted</th>
                        <th>As Billed</th>
                    </tr>
                    <tr>
                        <td>Dis.Sys.Charge:</td>
                        <td><input id = "disSysChrg1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id = "disSysChrg" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>Dist.Dem. Charge:</td>
                        <td><input id="disDemChrg1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id="disDemChrg" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>Supply Fix Charge:</td>
                        <td><input id= "suppFixChrg1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id= "suppFixChrg" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>Supply System Charge:</td>
                        <td><input id= "suppSysChrg1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id= "suppSysChrg" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>Metering Fix Charge:</td>
                        <td><input id="meterFixChrg1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id="meterFixChrg" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>Metering Sys. Charge:</td>
                        <td><input id = "meterSysChrg1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id = "meterSysChrg" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>Lfln Disc/.Subs.:</td>
                        <td><input id = "lflnDisSubs1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id = "lflnDisSubs" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>Sen.Cit.Disc/Subs:</td>
                        <td><input id = "senCitDiscSubs1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id = "senCitDiscSubs" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>Int Clss.Crss.Subs:</td>
                        <td><input id="iCCS1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id="iCCS" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>MCC CAPEX:</td>
                        <td><input id="mccCapex1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id="mccCapex" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>Loan Condonation:</td>
                        <td><input id="loanCondon1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id="loanCondon" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                    <tr>
                        <td>Loan Condon Fix:</td>
                        <td><input id="loanCondonFix1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00"></td>
                        <td><input id="loanCondonFix" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number"  readonly></td>
                    </tr>
                </table>       
            </div>
    </div>
</div>
</div>
<div id="charges3" class="modal">
    <div class="modal-content" style="width: 50%;height:450px">
    <div class="modal-header" style="width: 100%;">
        <h3>Charges 3</h3>
        <button type="button" class="btn-close" onclick="closeCharges3();"></button>
    </div>
    <div class="modal-body" style="color: black;width:100%;height:600px;">
            <div style="overflow-x:hidden;height:320px;width: 100%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
                <table border=0  style="color:white;margin:auto;">
                    <tr>
                        <th> </th>
                        <th>As Adjusted</th>
                        <th>As Billed</th>
                    </tr>
                    <tr>
                        <td>Miss.Elect.(SPUG):</td>
                        <td><input id="missElectS1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id="missElectS" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" readonly></td>
                    </tr>
                    <tr>
                        <td>Miss.Elect.(RED):</td>
                        <td><input id="missElectR1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id="missElectR" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" readonly></td>
                    </tr>
                    <tr>
                        <td>Envi. Chrg:</td>
                        <td><input id="enviChrg1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id="enviChrg" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" readonly></td>
                    </tr>
                    <tr>
                        <td>Equali. of Taxes & Royalty:</td>
                        <td><input id="EqualiTR1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id="EqualiTR" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" readonly></td>
                    </tr>
                    <tr>
                        <td>NPC Str.Cons.Cost:</td>
                        <td><input id="npcStrCC1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id="npcStrCC" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text"  readonly></td>
                    </tr>
                    <tr>
                        <td>NPC Stranded Debt:</td>
                        <td><input id="npcStrDebt1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id="npcStrDebt" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" readonly></td>
                    </tr>
                    <tr>
                        <td>Fit-All(Renew):</td> 
                        <td><input id = "fitAll1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id = "fitAll" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" readonly></td>
                    </tr>
                    <tr>
                        <td>Generation VAT:</td>
                        <td><input id="genVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id="genVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text"  readonly></td>
                    </tr>
                    <tr>
                        <td>Trans.Sys.VAT:</td>
                        <td><input id = "TranSysVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id = "TranSysVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text"  readonly></td>
                    </tr>
                    <tr>
                        <td>Trans.Dem.VAT:</td>
                        <td><input id = "TransDemVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id = "TransDemVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text"  readonly></td>
                    </tr>
                    <tr>
                        <td>System Loss VAT:</td>
                        <td><input id = "sLossVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id = "sLossVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text"  readonly></td>
                    </tr>
                    <tr>
                        <td>Dist.Sys.VAT:</td>
                        <td><input id = "distSysVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id = "distSysVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text"  readonly></td>
                    </tr>
                    <tr>
                        <td>Dist.Dem.VAT:</td>
                        <td><input id = "distDemVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id = "distDemVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" readonly></td>
                    </tr>
                    <tr>
                        <td>Loan Condo. VAT:</td>
                        <td><input id = "loanConVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id = "loanConVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" readonly></td>
                    </tr>
                    <tr>
                        <td>Loan Cond. Fxd VAT:</td>
                        <td><input id = "loanConFxdVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id = "loanConFxdVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text"  readonly></td>
                    </tr>
                    <tr>
                        <td>Power Act Red VAT:</td>
                        <td><input id="powerActRedVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id="powerActRedVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text"  readonly></td>
                    </tr>
                    <tr>
                        <td>Supply Fix VAT:</td>
                        <td><input id = "suppFixVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00" readonly></td>
                        <td><input id = "suppFixVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" readonly></td>
                    </tr>
                    <tr>
                        <td>Supply Sys VAT:</td>
                        <td><input id = "suppSysVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" placeholder="0.00" readonly></td>
                        <td><input id = "suppSysVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="number" readonly></td>
                    </tr>
                    <tr>
                        <td>Meter Fix VAT:</td>
                        <td><input id = "meterFixVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id = "meterFixVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" readonly></td>
                    </tr>
                    <tr>
                        <td>Meter Sys VAT:</td>
                        <td><input id = "meterSysVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id = "meterSysVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text"  readonly></td>
                    </tr>
                    <tr>
                        <td>Lfln Disc VAT:</td>
                        <td><input id = "lflnDiscVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id = "lflnDiscVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text"  readonly></td>
                    </tr>
                    <tr>
                        <td>Other VAT:</td>
                        <td><input id = "otherVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id = "otherVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text"  readonly></td>
                    </tr>
                    <tr>
                        <td>E - VAT:</td>
                        <td><input id="eVAT1" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text" placeholder="0.00" readonly></td>
                        <td><input id="eVAT" style="border-radius:3px;height:35px;margin-left:10px;color:black" type="text"  readonly></td>
                    </tr>
                </table>       
            </div>
    </div>
</div>
</div>
@include('include.modal.consumerAcctModal')
<script>
var genSysCharg;
var powActRed;
var franBenHost;
var  ForAdjustChrg;
var transSysChrg;
var TransDemChrg;
var SysLossChrg;

var genSysCharg1;
var powActRed1;
var franBenHost1;
var  ForAdjustChrg1;
var transSysChrg1;
var TransDemChrg1;
var SysLossChrg1;
/* charges2 */
var disSysChrg;
var disDemChrg;
var suppFixChrg;
var suppSysChrg;
var meterFixChrg;
var meterSysChrg;
var lflnDisSubs;
var senCitDiscSubs;
var iCCS;
var mccCapex;
var loanCondon;
var loanCondonFix;

var disSysChrg1;
var disDemChrg1;
var suppFixChrg1;
var suppSysChrg1;
var meterFixChrg1;
var meterSysChrg1;
var lflnDisSubs1;
var senCitDiscSubs1;
var iCCS1;
var mccCapex1;
var loanCondon1;
var loanCondonFix1;
/* charges3 */
var missElectS;
var missElectR;
var enviChrg;
var EqualiTR;
var npcStrCC;
var npcStrDebt;
var fitAll;
var genVAT;
var TranSysVAT;
var TransDemVAT;
var sLossVAT;
var distSysVAT;
var distDemVAT;
var loanConVAT;
var loanConFxdVAT;
var powerActRedVAT;
var suppFixVAT;
var suppSysVAT;
var meterFixVAT;
var meterSysVAT;
var lflnDiscVAT;

var missElectS1;
var missElectR1;
var enviChrg1;
var EqualiTR1;
var npcStrCC1;
var npcStrDebt1;
var fitAll1;
var genVAT1;
var TranSysVAT1;
var TransDemVAT1;
var sLossVAT1;
var distSysVAT1;
var distDemVAT1;
var loanConVAT1;
var loanConFxdVAT1;
var powerActRedVAT1;
var suppFixVAT1;
var suppSysVAT1;
var meterFixVAT1;
var meterSysVAT1;
var lflnDiscVAT1;

var tarrears;
var surchage;
var totalDue;
var cb;
var kwh;
var ajdsend = new Object();
var ac;
var constype;
var cons1; 
var mult;
function openCharges1() {
    modalD = document.querySelectorAll(".modal");
    modalD[0].style.display = "block";
}
function closeCharges1() {
    modalD = document.querySelectorAll(".modal");
    modalD[0].style.display = "none";
}
function Chrgs2() {
    modalD1 = document.querySelectorAll(".modal");
    modalD1[1].style.display = "block";
}
function closeCharges2() {
    modalD = document.querySelectorAll(".modal");
    modalD[1].style.display = "none";
}
function openCharges3() {
    modalD = document.querySelectorAll(".modal");
    modalD[2].style.display = "block";
}
function closeCharges3() {
    modalD = document.querySelectorAll(".modal");
    modalD[2].style.display = "none";
}
var dateP;
var id;
var xhr = new XMLHttpRequest();
var accts = "{{route('select.consumer.account')}}";
xhr.open('GET', accts, true);
xhr.onload = function() {
    if (this.status == 200) {
        var data = JSON.parse(this.responseText);
        var output = " ";
        var output1 = " ";
        var val = data.data;
        var val3 = data.last_page;
        var val2 = data.current_page;

        output += '<table style="text-align:left;width:100%;" border=1 class="modal-table" id="table1">';
        output += '<tr>' + '<th>' + 'Account #' + '</th>' + '<th>' + 'Consumer' + '</th></tr>';
        for (var i in val) {
            accName = val[i].cm_account_no;
            var acc = val[i].cm_id;
            output += '<tr>' + '<td id = ' + val[i].cm_id + ' onclick="tdclick(' + acc + ')" >' + val[i].cm_account_no + '</td>';
            output += '<td>' + val[i].cm_full_name + '</td>' + '</tr>';
        }
        var b = val2 + 1;

        if (val2 <= 1) {
            output1 += '<tr>';
            output1 += '<td>' + '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + val2 + ' hidden>' + 'Previous' + '</button>';
        } else {
            output1 += '<td>' + '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + val2 + '>' + 'Previous' + '</button>';
        }
        output1 += '<input style="border:0;width:10%;" type="text" class="currentpage" value="' + val2 + '" >';
        if (val2 < val3) { 
            output1 += '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + b + '  >' + 'Next' + '</button>';
        } else {
            output1 += '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + b + ' hidden>' + 'Next' + '</button>';
        }
        output += '</td>' + '</tr>' + '</table>';
        document.querySelector('.modaldiv2').innerHTML = output;
        document.querySelector('.pages2').innerHTML = output1;
    }
}
xhr.send();

function s(p) {
    var xhr = new XMLHttpRequest();
    var routee = "{{route('select.consumer.account','?page=')}}" +p ;
    xhr.open('GET', routee, true);
    xhr.onload = function() {
        if (this.status == 200) {
            var data = JSON.parse(this.responseText);
            var output = " ";
            var output1 = " ";
            var val = data.data;
            var val3 = data.last_page;
            var val2 = data.current_page;
            output += '<table style="text-align:left;width:100%;" border=1 class="modal-table" id="table1">';
            output += '<tr>' + '<th>' + 'Account #' + '</th>' + '<th>' + 'Consumer' + '</th></tr>';
            for (var i in val) {
                var acc = val[i].cm_id;
                output += '<tr>' + '<td id = ' + val[i].cm_id + ' onclick="tdclick(' + acc + ')" >' + val[i].cm_account_no + '</td>';
                output += '<td>' + val[i].cm_full_name + '</tr>';
            }
            var b = val2 + 1;
            var c = val2 - 1;
            if (val2 <= 1) {
                output1 += '<tr>';
                output1 += '<td>' + '<button id = "btn" class="btn btn-primary" onclick="setTimeout(s(this.value),3000)" value=' + c + ' hidden>' + 'Previous' + '</button>';
            } else {
                output1 += '<td>' + '<button id = "btn" class="btn btn-primary" onclick="setTimeout(s(this.value),3000)" value=' + c + '>' + 'Previous' + '</button>';
            }
            output1 += '<input style="border:0;width:10%;" type="text" class="currentpage" value="' + val2 + '" >';
            if (val2 >= val3) {
                output1 += '<button id = "btn" class="btn btn-primary" onclick="setTimeout(s(this.value),3000)" value=' + b + '  hidden>' + 'Next' + '</button>';
            } else {
                output1 += '<button id = "btn" class="btn btn-primary" onclick="setTimeout(s(this.value),3000)" value=' + b + ' >' + 'Next' + '</button>';
            }
            output += '</td>' + '</tr>' + '</table>';

            document.querySelector('.modaldiv2').innerHTML = output;
            document.querySelector('.pages2').innerHTML = output1;
        }
    }
    xhr.send();
}
function tearch() {
    var d = " ";
    var change = document.querySelector('#change')
    var cash = document.querySelector('#cash')
    var discoID = document.querySelector('#discoID')
    var dueID = document.querySelector('#dueID')
    var aP = document.querySelector('#aP')
    if (change, cash, discoID, dueID, aP) {
        change.value = '0.00';
        cash.value = '0.00';
        discoID.value = d;
        dueID.value = d;
        aP.value = d;
    }
    var input, filter;
    input = document.getElementById("dearch");
    filter = input.value.toUpperCase();
    if (filter.length == 0) {
        var xhr = new XMLHttpRequest();
        var accts = "{{route('select.consumer.account')}}";
        xhr.open('GET', accts, true);
        xhr.onload = function() {
            if (this.status == 200) {
                var data = JSON.parse(this.responseText);
                var output = " ";
                var output1 = " ";
                var val = data.data;
                var val3 = data.last_page;
                var val2 = data.current_page;
                output += '<table style="text-align:left;width:100%;" border=1 class="modal-table" id="table1">';
                output += '<tr>' + '<th>' + 'Account #' + '</th>' + '<th>' + 'Consumer' + '</th></tr>';
                for (var i in val) {
                    var acc = val[i].cm_id;
                    output += '<tr>' + '<td id = ' + val[i].cm_id + ' onclick="tdclick(' + acc + ')" >' + val[i].cm_account_no + '</td>';
                    output += '<td>' + val[i].cm_full_name + '</tr>';
                }

                var b = val2 + 1;

                if (val2 <= 1) {
                    output1 += '<tr>';
                    output1 += '<td>' + '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + val2 + ' hidden>' + 'Previous' + '</button>';
                } else {
                    output1 += '<td>' + '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + val2 + '>' + 'Previous' + '</button>';
                }
                output1 += '<input style="border:0;width:10%;"  type="text" class="currentpage" value="' + val2 + '" >';
                if (val2 < val3) {
                    output1 += '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + b + '  >' + 'Next' + '</button>';
                } else {
                    output1 += '<button id = "btn" class="btn btn-primary" onclick="s(this.value)" value=' + b + ' hidden>' + 'Next' + '</button>';
                }
                output += '</td>' + '</tr>' + '</table>';

            }
            document.querySelector('.modaldiv2').innerHTML = output;
            document.querySelector('.pages2').innerHTML = output1;
        }
        xhr.send();
    } else{
        var d = " ";

        var change = document.querySelector('#change')
        var cash = document.querySelector('#cash')
        var discoID = document.querySelector('#discoID')
        var dueID = document.querySelector('#dueID')
        var aP = document.querySelector('#aP')
        if (change, cash, discoID, dueID, aP) {
            change.value = '0.00';
            cash.value = '0.00';
            discoID.value = d;
            dueID.value = d;
            aP.value = d;
        }
        var xhr = new XMLHttpRequest();
        var searchbyname = "{{route('search.consumer.name.account',['request'=>':req'])}}";
        searchbyname = searchbyname.replace(':req',filter);
        xhr.open('GET', searchbyname, true);
        xhr.onload = function() {
            if (this.status == 200) {
                var data = JSON.parse(this.responseText);
                var output = " ";
                var val = data;
                output += '<div style="overflow:scroll;height:270px;">';
                output += '<table style="text-align:left;width:100%;height:20px;" border=1 class="modal-table" id="table1">';
                output += '<tr>' + '<th>' + 'Account #' + '</th>' + '<th>' + 'Consumer' + '</th></tr>';
                for (var i in val) {
                    var acc = val[i].cm_id;
                    output += '<tr>' + '<td id = ' + val[i].cm_id + ' onclick="tdclick(' + acc + ')" >' + val[i].cm_account_no + '</td>';
                    output += '<td>' + val[i].cm_full_name + '</tr>';
                }
                output += '</table></div>';
            }
            document.querySelector('.modaldiv2').innerHTML = output;
            document.querySelector('.pages2').innerHTML = '';

        }
        xhr.send();
    }
}
function tdclick(acc){
    id = acc;
    var a = document.getElementById(id);
    document.querySelector('#accNoID').value = a.innerHTML;
    modalD = document.querySelectorAll(".modal");
    modalD[3].style.display = 'none';
}
function datePout(d){
    dateP = d;
    datasend = new Object();
    datasend.cons_id = id;
    datasend.date = dateP;
    var xhr = new XMLHttpRequest();
    var showAdjustPB = "{{route('show.powerbill.adjustment')}}";
    xhr.open('POST', showAdjustPB, true);
    xhr.setRequestHeader("Accept", "application/json");
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onload = function() {
        if(this.status == 200){
            var data = JSON.parse(this.responseText);
            console.log(data)
            var dateAdjust = data.Adjusted_PBill.Adjustment_Date;
            ajdsend.PB_ID = data.Adjusted_PBill.PowerBill_ID;
            var dataAd = dateAdjust.split(' ');
            if(data.Adjusted_PBill.Due_Date != null){
            var dataDueD = data.Adjusted_PBill.Due_Date.split(' ');
            }
           
            else{
                var dataDueD=[];
                dataDueD[0] = " ";
            }
            document.querySelector('#adjustNo').value = data.Adjusted_PBill.Adjust_No;
            var output = "";
            constype = data.Adjusted_PBill.Account_Type;
            mult = data.Adjusted_PBill.Mult;
            output +=  '<tr>' + 
                    '<td>Acct Name: &nbsp <label style="font-size:12px;font-weight:bold;margin-left:15px;color:#ffd0d7">' +data.Adjusted_PBill.Account_Name+  '</label></td>' +
                '</tr>' +
                '<tr>' +
                    '<td>Acct Address: &nbsp <label style="font-size:12px;font-weight:bold;color:#ffd0d7">' +data.Adjusted_PBill.Account_Address+ '</label></td>' +
                '</tr>' +
                '<tr>' +
                    '<td>Acct Type: &nbsp <label style="font-size:12px;font-weight:bold;margin-left:25px;color:#ffd0d7">' +data.Adjusted_PBill.Account_Type+ '</label></td>' +
                '</tr>' +
                '<tr>' +
                    '<td>Acct Status: &nbsp <label style="font-size:12px;font-weight:bold;margin-left:11.5px;color:#ffd0d7">' +data.Adjusted_PBill.Account_Status+ '</label></td>' +
                '</tr>' +
                '<tr>' +
                    '<td>Multiplier: &nbsp <label style="font-size:12px;font-weight:bold;margin-left:26px;color:#ffd0d7">' +'x'+data.Adjusted_PBill.Mult+ '</label></td>' +
                '</tr>'; 
        }
        else if(this.status == '422'){
            alert('No Record');
            location.reload();
        }

        document.querySelector('#info').innerHTML = output;
        document.querySelector('#dateAdjust').value = dataAd[0];
        document.querySelector('#billNo').value = data.Adjusted_PBill.Bill_No;
        document.querySelector('#BillingPeriod').value = dateP;
        document.querySelector('#currentBill').value = data.Adjusted_PBill.Current_Bill;
        document.querySelector('#totalDue').value = data.Adjusted_PBill.Total_Due;
        document.querySelector('#surchage').value = data.Adjusted_PBill.Surcharge;
        document.querySelector('#mArrears').value = data.Adjusted_PBill.M_Arrears;
        tarrears = data.Adjusted_PBill.M_Arrears;
        surchage = data.Adjusted_PBill.Surcharge;
        totalDue = data.Adjusted_PBill.Total_Due;
        cb = data.Adjusted_PBill.Current_Bill;
        ajdsend.Cons_ID = data.Adjusted_PBill.Cons_ID;
        ajdsend.BR_ID = data.Adjusted_PBill.BillRates_ID;
        ajdsend.Date = dateP;
        document.querySelector('#dueDate').value = dataDueD[0];
        document.querySelector('#presReading').value =data.Adjusted_PBill.Present_Reading;
        document.querySelector('#prevReading').value = data.Adjusted_PBill.Previous_Reading;
        document.querySelector('#kUsed').value = data.Adjusted_PBill.Kwh_Used;
        kwh = data.Adjusted_PBill.Kwh_Used;
        document.querySelector('#dUsed').value = data.Adjusted_PBill.Demand_Kwh_Used;
        document.querySelector('#dUsed1').value = data.Adjusted_PBill.Demand_Kwh_Used;
        /*---------------------CHARGES1-----------------------------------------------*/
        var gcharge = data.Adjusted_PBill.Gen_Charge.split('@');
        var pActRed =  data.Adjusted_PBill.Power_Act_Red.split('@');
        var fBtHost = data.Adjusted_PBill.Fran_Ben_To_Host.split('@');
        var fAdjChrg = data.Adjusted_PBill.Forex_Adjust_Charge.split('@');
        var tChrg = data.Adjusted_PBill.Trans_Sys_Charge.split('@');
        var tDChrg = data.Adjusted_PBill.Trans_Dem_Charge.split('@');
        var sLossChrge = data.Adjusted_PBill.System_Loss_Charge.split('@');

        var gcharge1 = data.Adjusted_PBill.Gen_Charge1.split('@');
        var pActRed1 =  data.Adjusted_PBill.Power_Act_Red1.split('@');
        var fBtHost1 = data.Adjusted_PBill.Fran_Ben_To_Host1.split('@');
        var fAdjChrg1 = data.Adjusted_PBill.Forex_Adjust_Charge1.split('@');
        var tChrg1 = data.Adjusted_PBill.Trans_Sys_Charge1.split('@');
        var tDChrg1 = data.Adjusted_PBill.Trans_Dem_Charge1.split('@');
        var sLossChrge1 = data.Adjusted_PBill.System_Loss_Charge1.split('@');
        document.querySelector('#genSysCharg').value = gcharge[0];
        document.querySelector('#powActRed').value = pActRed[0];
        document.querySelector('#franBenHost').value = fBtHost[0];
        document.querySelector('#ForAdjustChrg').value = fAdjChrg[0];
        document.querySelector('#transSysChrg').value = tChrg[0];
        document.querySelector('#TransDemChrg').value = tDChrg[0];
        document.querySelector('#SysLossChrg').value = sLossChrge[0];
        genSysCharg = gcharge1[1];
        powActRed = pActRed1[1];
        franBenHost = fBtHost1[1];
        ForAdjustChrg = fAdjChrg1[1];;
        transSysChrg = tChrg1[1];
        TransDemChrg = tDChrg1[1];
        SysLossChrg = sLossChrge1[1];

        /*--------------------CHARGES2------------------------------------------------*/
        var adjDSC = data.Adjusted_PBill.Dist_Sys_Charge.split('@');
        var adjDDC = data.Adjusted_PBill.Dist_Dem_Charge.split('@');
        var adjSFC = data.Adjusted_PBill.Supply_Fix_Charge.split('@');
        var adjSSC = data.Adjusted_PBill.Supply_Sys_Charge.split('@');
        var adjMFC = data.Adjusted_PBill.Metering_Fix_Charge.split('@');
        var adjMSC = data.Adjusted_PBill.Metering_Sys_Charge.split('@');
        var adjLDS = data.Adjusted_PBill.Lifeline_Disc_Subs.split('@');
        var adjSCDS = data.Adjusted_PBill.Sen_Cit_Disc_Subs.split('@');
        var adjICCS = data.Adjusted_PBill.Int_Clss_Crss_Subs.split('@');
        var adjMC = data.Adjusted_PBill.MCC_Capex.split('@');
        var adjLC = data.Adjusted_PBill.Loan_Condonation.split('@');
        var adjLCF = data.Adjusted_PBill.Loan_Condonation_Fix.split('@');

        var adjDSC1 = data.Adjusted_PBill.Dist_Sys_Charge1.split('@');
        var adjDDC1 = data.Adjusted_PBill.Dist_Dem_Charge1.split('@');
        var adjSFC1 = data.Adjusted_PBill.Supply_Fix_Charge1.split('@');
        var adjSSC1 = data.Adjusted_PBill.Supply_Sys_Charge1.split('@');
        var adjMFC1 = data.Adjusted_PBill.Metering_Fix_Charge1.split('@');
        var adjMSC1 = data.Adjusted_PBill.Metering_Sys_Charge1.split('@');
        var adjLDS1 = data.Adjusted_PBill.Lifeline_Disc_Subs1.split('@');
        var adjSCDS1 = data.Adjusted_PBill.Sen_Cit_Disc_Subs1.split('@');
        var adjICCS1 = data.Adjusted_PBill.Int_Clss_Crss_Subs1.split('@');
        var adjMC1 = data.Adjusted_PBill.MCC_Capex1.split('@');
        var adjLC1 = data.Adjusted_PBill.Loan_Condonation1.split('@');
        var adjLCF1 = data.Adjusted_PBill.Loan_Condonation_Fix1.split('@');
        document.querySelector('#disSysChrg').value = adjDSC[0];       
        document.querySelector('#disDemChrg').value = adjDDC[0];
        document.querySelector('#suppFixChrg').value = adjSFC[0];    
        document.querySelector('#suppSysChrg').value =  adjSSC[0];        
        document.querySelector('#meterFixChrg').value = adjMFC[0];       
        document.querySelector('#meterSysChrg').value = adjMSC[0];
        document.querySelector('#lflnDisSubs').value = adjLDS[0];
        document.querySelector('#senCitDiscSubs').value = adjSCDS[0];
        document.querySelector('#iCCS').value = adjICCS[0];
        document.querySelector('#mccCapex').value = adjMC[0];
        document.querySelector('#loanCondon').value = adjLC[0];
        document.querySelector('#loanCondonFix').value = adjLCF[0];
        disSysChrg = adjDSC1[1];
        disDemChrg = adjDDC1[1];
        suppFixChrg = adjSFC1[1];
        suppSysChrg = adjSSC1[1];
        meterFixChrg = adjMFC1[1];
        meterSysChrg = adjMSC1[1];
        lflnDisSubs = adjLDS1[1];
        senCitDiscSubs = adjSCDS1[1];
        iCCS = adjICCS1[1];
        mccCapex = adjMC1[1];
        loanCondon = adjLC1[1];
        loanCondonFix = adjLCF1[1];
        /*--------------------CHARGES3------------------------------------------------*/
        var adjMSP = data.Adjusted_PBill.Miss_Elect_SPUG.split('@');
        var adjMSR = data.Adjusted_PBill.Miss_Elect_RED.split('@');
        var adjEC = data.Adjusted_PBill.Envi_Charge.split('@');
        var adjETR = data.Adjusted_PBill.Equali_Taxes_Royalty.split('@');
        var adjNSCC = data.Adjusted_PBill.NPC_Str_Cons_Cost.split('@');
        var adjNSD = data.Adjusted_PBill.NPC_Stranded_Debt.split('@');
        var adjFAR = data.Adjusted_PBill.FIT_All_Renew.split('@');
        var adjGV = data.Adjusted_PBill.Generation_Vat.split('@');
        var adjTSV = data.Adjusted_PBill.Trans_Sys_Vat.split('@');
        var adjTDV = data.Adjusted_PBill.Trans_Dem_Vat.split('@');
        var adjSLV = data.Adjusted_PBill.System_Loss_Vat.split('@');
        var adjDSV = data.Adjusted_PBill.Dist_Sys_Vat.split('@');
        var adjDDV = data.Adjusted_PBill.Dist_Dem_Vat.split('@');
        var adjLCV = data.Adjusted_PBill.Loan_Condo_Vat.split('@');
        var adjLCFV = data.Adjusted_PBill.Loan_Cond_Fixed_Vat.split('@');
        var adjPARV = data.Adjusted_PBill.Power_Act_Red_Vat.split('@');
        var adjSFV = data.Adjusted_PBill.Supply_Fix_Vat.split('@');
        var adjSSV = data.Adjusted_PBill.Supply_Sys_Vat.split('@');
        var adjMFV = data.Adjusted_PBill.Metering_Fix_Vat.split('@');
        var adjMSV = data.Adjusted_PBill.Metering_Sys_Vat.split('@');
        var adjLDV = data.Adjusted_PBill.Lfln_Disc_Vat.split('@');

        var adjMSP1 = data.Adjusted_PBill.Miss_Elect_SPUG1.split('@');
        var adjMSR1 = data.Adjusted_PBill.Miss_Elect_RED1.split('@');
        var adjEC1 = data.Adjusted_PBill.Envi_Charge1.split('@');
        var adjETR1 = data.Adjusted_PBill.Equali_Taxes_Royalty1.split('@');
        var adjNSCC1 = data.Adjusted_PBill.NPC_Str_Cons_Cost1.split('@');
        var adjNSD1 = data.Adjusted_PBill.NPC_Stranded_Debt1.split('@');
        var adjFAR1 = data.Adjusted_PBill.FIT_All_Renew1.split('@');
        var adjGV1 = data.Adjusted_PBill.Generation_Vat1.split('@');
        var adjTSV1 = data.Adjusted_PBill.Trans_Sys_Vat1.split('@');
        var adjTDV1 = data.Adjusted_PBill.Trans_Dem_Vat1.split('@');
        var adjSLV1 = data.Adjusted_PBill.System_Loss_Vat1.split('@');
        var adjDSV1 = data.Adjusted_PBill.Dist_Sys_Vat1.split('@');
        var adjDDV1 = data.Adjusted_PBill.Dist_Dem_Vat1.split('@');
        var adjLCV1 = data.Adjusted_PBill.Loan_Condo_Vat1.split('@');
        var adjLCFV1 = data.Adjusted_PBill.Loan_Cond_Fixed_Vat1.split('@');
        var adjPARV1 = data.Adjusted_PBill.Power_Act_Red_Vat1.split('@');
        var adjSFV1 = data.Adjusted_PBill.Supply_Fix_Vat1.split('@');
        var adjSSV1 = data.Adjusted_PBill.Supply_Sys_Vat1.split('@');
        var adjMFV1 = data.Adjusted_PBill.Metering_Fix_Vat1.split('@');
        var adjMSV1 = data.Adjusted_PBill.Metering_Sys_Vat1.split('@');
        var adjLDV1 = data.Adjusted_PBill.Lfln_Disc_Vat1.split('@');

        document.querySelector('#missElectS').value = adjMSP[0];
        document.querySelector('#missElectR').value = adjMSR[0];
        document.querySelector('#enviChrg').value = adjEC[0];
        document.querySelector('#EqualiTR').value = adjETR[0];
        document.querySelector('#npcStrCC').value = adjNSCC[0];
        document.querySelector('#npcStrDebt').value = adjNSD[0];
        document.querySelector('#fitAll').value = adjFAR[0];
        document.querySelector('#genVAT').value = adjGV[0];
        document.querySelector('#TranSysVAT').value =  adjTSV[0];
        document.querySelector('#TransDemVAT').value = adjTDV[0];
        document.querySelector('#sLossVAT').value = adjSLV[0];
        document.querySelector('#distSysVAT').value = adjDSV[0];
        document.querySelector('#distDemVAT').value = adjDDV[0];
        document.querySelector('#loanConVAT').value = adjLCV[0];
        document.querySelector('#loanConFxdVAT').value =  adjLCFV[0];
        document.querySelector('#powerActRedVAT').value = adjPARV[0];    
        document.querySelector('#suppFixVAT').value = adjSFV[0];
        document.querySelector('#suppSysVAT').value = adjSSV[0];
        document.querySelector('#meterFixVAT').value = adjMFV[0];
        document.querySelector('#meterSysVAT').value = adjMSV[0];
        document.querySelector('#lflnDiscVAT').value = adjLDV[0];
        document.querySelector('#otherVAT').value = data.Adjusted_PBill.Other_Vat;
        var evat = data.Adjusted_PBill.E_VAT;
        document.querySelector('#eVAT').value = evat;
        // var num = 3.86 * 0.1544;
        // console.log(Math.round(1000* 0.5849)/1000);
        // console.log(Math.round(100*0.5951)/100);
        // var a = Math.round(100*0.5951)/100;
        // console.log(a.toFixed(2));

        missElectS = adjMSP1[1]; 
        missElectR = adjMSR1[1];
        enviChrg = adjEC1[1];
        EqualiTR = adjETR1[1];
        npcStrCC = adjNSCC1[1];
        npcStrDebt = adjNSD1[1];
        fitAll = adjFAR1[1];
        genVAT = adjGV1[1];
        TranSysVAT = adjTSV1[1];
        TransDemVAT = adjTDV1[1];
        sLossVAT = adjSLV1[1];
        distSysVAT = adjDSV1[1];
        distDemVAT = adjDDV1[1];
        loanConVAT = adjLCV1[1];
        loanConFxdVAT = adjLCFV1[1];
        powerActRedVAT = adjPARV1[1];
        suppFixVAT = adjSFV1[1];
        suppSysVAT = adjSSV1[1];
        meterFixVAT = adjMFV1[1];
        meterSysVAT = adjMSV1[1];
        lflnDiscVAT = adjLDV1[1];  
        
        
    }
    xhr.send(JSON.stringify(datasend));
}
function presReading(){
            var a = document.querySelector('#presReading1').value;
            var b = document.querySelector('#prevReading1').value;
            if(isNaN(parseInt(b))){
                b = 0;
            }
            if(parseInt(a) < parseInt(b)){
                alert('ERROR!');
               document.querySelector('#prevReading1').value = '';
               document.querySelector('#prevReading1').placeholder = '0.00';
               document.querySelector('#presReading1').value = ''
               document.querySelector('#presReading1').placeholder = '0.00';
            }
            else{
                var c = parseFloat(a) - parseFloat(b);
                if(isNaN(parseFloat(c))){
                    c = 0;    
                }
                document.querySelector('#kUsed1').value = c;
                document.querySelector('#butt').value = c;
                console.log(document.querySelector('#butt'));
            }
        } 

function kUsed(a){
    if(a.value <= 25 && constype == 'RESIDENTIAL'){
        var xhr= new XMLHttpRequest();
        var kwh = a.value;
        Swal.fire({
            title: 'Info',
            text: 'Lifeline',
            icon: 'info',
            confirmButtonText: 'close'
        });
        console.log(kwh);
        var route = "{{route('get.getLifeline',['kwh' => ':id'])}}";
        route = route.replace(':id', kwh);
        console.log(route);
        xhr.open('GET',route,true);
        xhr.onload = function(){
            if(this.status == 200){
            var data = JSON.parse(this.responseText);
            console.log(data);
            document.querySelector('#btnsave').disabled = false;
        var pres = document.querySelector('#presReading1').value;
        if(pres == ''){
            ajdsend.Pres_Reading = 0;
        }
        else{
            ajdsend.Pres_Reading = pres;
        }
        var b = document.querySelector('#prevReading1').value;
        if(b == ''){
            ajdsend.Prev_Reading = 0;
        }
        else{
            ajdsend.Prev_Reading = b;
        }
        
        document.querySelector('#kUsed1').value = a.value;
        ajdsend.Kwh_Used=parseFloat(a.value);
        var gen = (genSysCharg * a.value);
        var gen1 = Math.round(1000*gen)/1000;
        var finalgen = Number(Math.round(gen1+'e2')+'e-2')

        var pow = (powActRed * a.value);
        var pow1 = Math.round(1000*pow)/1000;
        var pow2 = pow1 * (-1);
        
        var finalpow = Number(Math.round(pow1+'e2')+'e-2')
        // var finalpow = pow3 * (-1);

        var forA = (ForAdjustChrg * a.value);
        var forA1 = Math.round(1000*forA)/1000;
        var finalforA1 = Number(Math.round(forA1+'e2')+'e-2')

        var transSC = (transSysChrg * a.value);
        var transSC1 = Math.round(1000*transSC)/1000;
        var finaltransSC = Number(Math.round(transSC1+'e2')+'e-2')
        
        var sysLossC = (SysLossChrg * a.value);
        var sysLossC1 = Math.round(1000*sysLossC)/1000;
        var finalsysLossC = Number(Math.round(sysLossC1+'e2')+'e-2')

        var fbhost = (franBenHost * a.value);
        var fbhost1 = Math.round(1000*fbhost)/1000;
        var finalfbhost = Number(Math.round(fbhost1+'e2')+'e-2')
        var lf1 = (parseFloat(gen) + parseFloat(fbhost));
        console.log('****sum')
        console.log(gen1);
        console.log(pow1)
        console.log(forA1)
        console.log(fbhost1)
        var sum = finalgen + finalpow + finalforA1 + finalfbhost;
        console.log('*****')
        
        document.querySelector('#genSysCharg1').value = finalgen;
        document.querySelector('#powActRed1').value = finalpow;
        document.querySelector('#franBenHost1').value = finalfbhost;
        document.querySelector('#ForAdjustChrg1').value = finalforA1;
        document.querySelector('#transSysChrg1').value = finaltransSC;
        document.querySelector('#SysLossChrg1').value = finalsysLossC;
        var demand = document.querySelector('#dUsed1').value;
        ajdsend.Total_Demand_Kwh_Used = demand * mult;
        ajdsend.Demand_Kwh_Used = demand;
        demand = demand * mult;
        var transDC = parseFloat(TransDemChrg) * demand;
        console.log(transDC + 'testing');
        document.querySelector('#TransDemChrg1').value = parseFloat(TransDemChrg) * parseFloat(demand);
        document.querySelector('#disDemChrg1').value = parseFloat(disDemChrg) * parseFloat(demand);  //charges2
        document.querySelector('#TransDemVAT1').value = parseFloat(TransDemVAT) * parseFloat(demand);  //charges3 
        document.querySelector('#distDemVAT1').value = parseFloat(distDemVAT) * parseFloat(demand); //charges3 
        var demtrans1 = parseFloat(TransDemChrg) * demand;
        var demtrans2 = Math.round(1000 * demtrans1)/1000; 
        var demtrans = Number(Math.round(demtrans2+'e2')+'e-2')
        console.log(demtrans + 'testing');
        console.log('****')
        var sum1 = finaltransSC + finalsysLossC + demtrans;
        console.log('*****')

        var demtransV1 = parseFloat(disDemChrg) * demand;
        var demtransV2 = Math.round(1000 * demtransV1)/1000;
        var demtransV = Number(Math.round(demtransV2+'e2')+'e-2')

        var transdVat1 = parseFloat(TransDemVAT) * demand;
        var transdVat2 = Math.round(1000*transdVat1)/1000;
        var transdVat = Number(Math.round(transdVat2+'e2')+'e-2')

        var disdVat1 = parseFloat(distDemVAT) * demand;
        var disdVat2 = Math.round(1000*disdVat1)/1000;
        var disdVat = Number(Math.round(disdVat2+'e2')+'e-2')
        var lf2 = (parseFloat(transSC) + parseFloat(sysLossC));
        var lf3 = parseFloat(demtrans1) * parseFloat(demand);
       
        // var sum1 = parseFloat(fbhost)+parseFloat(gen)+parseFloat(pow)+parseFloat(forA)+parseFloat(transSC)+parseFloat(transDC)+parseFloat(sysLossC)+demtrans+demtransV+transdVat+disdVat;

        /*charges2*/
        
        var dsc1 = (disSysChrg * a.value);
        var dsc2 = Math.round(1000*dsc1)/1000;
        var dsc = Number(Math.round(dsc2+'e2')+'e-2')
        

        
        var sfc1 = parseFloat(suppFixChrg);
        var sfc2 = Math.round(1000*sfc1)/1000;
        var sfc = Number(Math.round(sfc2+'e2')+'e-2')

        var ssc1 = (suppSysChrg * a.value);
        var ssc2 = Math.round(1000*ssc1)/1000;
        var ssc = Number(Math.round(ssc2+'e2')+'e-2')
        
        var mfc1 =  parseFloat(meterFixChrg);
        var mfc2 = Math.round(1000 * mfc1)/1000;
        var mfc = Number(Math.round(mfc2+'e2')+'e-2')
    
        var msc1 =  (meterSysChrg * a.value);
        var msc2 = Math.round(1000 * msc1)/1000;
       
        var msc = Number(Math.round(msc2+'e2')+'e-2')
        var lf4 = parseFloat(dsc1) + parseFloat(ssc1) + parseFloat(msc1);
        
        // console.log(kwh)
        // console.log(dsc + ' ' + ssc + ' ' +msc);
        // console.log(dsc + ssc + msc);
        // console.log(dsc + ssc + msc * kwh);
        console.log('**********sum2')
        console.log(demtransV2)
        console.log(dsc2)
        console.log(sfc2)
        console.log(ssc2)
        console.log(mfc2)
        console.log(msc2)
        sum2 = demtransV + dsc + sfc + ssc + mfc + msc;
        console.log('**********')
        var lflds1 = (lflnDisSubs * a.value);
        var lflds2 = Math.round(1000 * lflds1)/1000;
        var lflds = Number(Math.round(lflds2+'e2')+'e-2')

        var scds1 = (senCitDiscSubs * a.value);
        var scds2 = Math.round(1000 * scds1)/1000;
        var scds = Number(Math.round(scds2+'e2')+'e-2')
    
        var iccs1 = (iCCS * a.value);
        var iccs2 = Math.round(1000 * iccs1)/1000;
        var iccs = Number(Math.round(iccs2+'e2')+'e-2')
    
        var mcc1 = (mccCapex * a.value);
        var mcc2 = Math.round(1000 * mcc1)/1000
        var mcc = Number(Math.round(mcc2+'e2')+'e-2')
    
        var lc1 = (loanCondon * a.value);
        var lc2 = Math.round(1000 * lc1);
        var lc = Number(Math.round(lc2+'e2')+'e-2')
    

        var lcf1 = parseFloat(loanCondonFix);
        var lcf2 = Math.round(1000*lcf1)/1000;
        var lcf = Number(Math.round(lcf2+'e2')+'e-2')
        console.log('**********sum3')
        console.log(scds2)
        console.log(iccs2)
        console.log(mcc2)
        console.log(lc2)
        console.log(lcf2)
        sum3 =  scds + iccs + mcc + lc + lcf;
        console.log('**********')
        // var sum2 = dsc + sfc + ssc + mfc + msc + lflds + scds + iccs + mcc + lc + lcf;
        
        var lf5 = (parseFloat(demtransV1) * parseFloat(demand)) + parseFloat(suppFixChrg) + parseFloat(meterFixChrg);
        console.log(lf1 + ' ' + lf2 + ' ' + lf3 + ' ' + lf4 + ' ' + lf5 );
        var totallf = parseFloat(lf1) + parseFloat(lf2) + parseFloat(lf3) + parseFloat(lf4) + parseFloat(lf5);
        totallf = totallf * (data.ll_discount/100);
        console.log(data.ll_discount);
        var totallf1 = parseFloat(totallf);
        var totallf2 = Math.round(1000*totallf1)/1000;
        console.log(totallf1 + '------');
        var finaltotallf = Math.round(100*totallf1)/100;
        console.log(finaltotallf);
        console.log(finaltotallf);
        console.log((lflnDisSubs * a.value).toFixed(2));
        // ($rates[0]->br_distdem_rate*$presDemRead)+($rates[0]->br_suprtlcust_fixed)+($rates[0]->br_mtrrtlcust_fixed);
        document.querySelector('#disSysChrg1').value = (disSysChrg * a.value).toFixed(2);
        document.querySelector('#suppFixChrg1').value = parseFloat(suppFixChrg).toFixed(2);
        document.querySelector('#suppSysChrg1').value =  (suppSysChrg * a.value).toFixed(2);
        document.querySelector('#meterFixChrg1').value = parseFloat(meterFixChrg).toFixed(2);
        document.querySelector('#meterSysChrg1').value = (meterSysChrg * a.value).toFixed(2);
        document.querySelector('#lflnDisSubs1').value = '-' + finaltotallf;
        document.querySelector('#senCitDiscSubs1').value = (senCitDiscSubs * a.value).toFixed(2);
        document.querySelector('#iCCS1').value = (iCCS * a.value).toFixed(2);
        document.querySelector('#mccCapex1').value = (mccCapex * a.value).toFixed(2);
        document.querySelector('#loanCondon1').value = (loanCondon * a.value).toFixed(2);
        document.querySelector('#loanCondonFix1').value = parseFloat(loanCondonFix).toFixed(2);
        ajdsend.lifeline = finaltotallf;
        /* charges3 */
        var c3mes1 = (missElectS * a.value);
        var c3mes2 = Math.round(1000*c3mes1)/1000;
        var c3mes = Number(Math.round(c3mes2+'e2')+'e-2')

        var c3mer1 = (missElectR * a.value);
        var c3mer2 = Math.round(1000*c3mer1)/1000;
        var c3mer = Number(Math.round(c3mer2+'e2')+'e-2')
    
        var c3ec1 = (enviChrg * a.value);
        var c3ec2 = Math.round(1000*c3ec1)/1000;
        var c3ec = Number(Math.round(c3ec2+'e2')+'e-2')
    
        var c3etq1 = (EqualiTR * a.value);
        var c3etq2 = Math.round(1000*c3etq1)/1000;
        var c3etq = Number(Math.round(c3etq2+'e2')+'e-2')
    
        var c3nsc1 = (npcStrCC * a.value);
        var c3nsc2 = Math.round(1000*c3nsc1)/1000;
        var c3nsc = Number(Math.round(c3nsc2+'e2')+'e-2')

        var c3nsd1 = (npcStrDebt * a.value);
        var c3nsd2 = Math.round(1000*c3nsd1)/1000;
        var c3nsd = Number(Math.round(c3nsd2+'e2')+'e-2')
    
        var c3fa1 = (fitAll * a.value);
        var c3fa2 = Math.round(1000*c3fa1)/1000;
        var c3fa = Number(Math.round(c3fa2+'e2')+'e-2')
        console.log(c3fa + '--' + 'fitall' )
        console.log('*********************sum4')
        console.log(c3mes2)
        console.log(c3mer2)
        console.log(c3ec2)
        console.log(c3etq2)
        console.log(c3nsc2)
        console.log(c3nsd2)
        console.log(c3fa2)
        sum4 = c3mes + c3mer + c3ec + c3etq + c3nsc + c3nsd + c3fa;
        console.log('*********************')
        var c3gv1 = (genVAT * a.value);
        var c3gv2 = Math.round(1000*c3gv1)/1000;
        var c3gv = Number(Math.round(c3gv2+'e2')+'e-2')
    
        var c3tsv1 = (TranSysVAT * a.value);
        var c3tsv2 = Math.round(1000*c3tsv1)/1000;
        var c3tsv = Number(Math.round(c3tsv2+'e2')+'e-2')
        
        var c3slv1 = (sLossVAT * a.value);
        var c3slv2 = Math.round(1000*c3slv1)/1000;
        var c3slv = Number(Math.round(c3slv2+'e2')+'e-2')
        
        var c3dsv1 = (distSysVAT * a.value);
        
        var c3dsv2 = Math.round(1000*c3dsv1)/1000;
        var c3dsv = Number(Math.round(c3dsv2+'e2')+'e-2')
        
        var c3lcv1 = (loanConVAT * a.value);
        var c3lcv2 = Math.round(1000*c3lcv1)/1000;
        var c3lcv = Number(Math.round(c3lcv2+'e2')+'e-2')
    
        var c3lcfv1 = parseFloat(loanConFxdVAT);
        var c3lcfv2 = Math.round(1000*c3lcfv1)/1000;
        var c3lcfv = Number(Math.round(c3lcfv2+'e2')+'e-2')
        
        var c3parv1 =  (powerActRedVAT * a.value);
        var c3parv2 = Math.round(1000*c3parv1)/1000;
        var c3parv3 = c3parv2 * (-1);
        console.log(c3parv2 + '--' + 'parv');
        var c3parv4 = Number(Math.round(c3parv3+'e2')+'e-2');
        var c3parv = c3parv4 * (-1);
        
        var c3sfv1 = parseFloat(suppFixVAT);
        var c3sfv2 = Math.round(1000*c3sfv1)/1000;
        var c3sfv = Number(Math.round(c3sfv2+'e2')+'e-2')
    
        var c3ssv1 = (suppSysVAT * a.value);
        var c3ssv2 = Math.round(1000*c3ssv1)/1000;
        var c3ssv = Number(Math.round(c3ssv2+'e2')+'e-2')
    
        var c3mfv1 = parseFloat(meterFixVAT);
        var c3mfv2 = Math.round(1000*c3mfv1)/1000;
        var c3mfv = Number(Math.round(c3mfv2+'e2')+'e-2')
        
        var c3msv1 = (meterSysVAT * a.value);
        var c3msv2 = Math.round(1000*c3msv1)/1000;
        var c3msv = Number(Math.round(c3msv2+'e2')+'e-2')
        var c3lfldv1 = (lflnDiscVAT * a.value);
        var c3lfldv2 = Math.round(1000*c3lfldv1)/1000;
        var c3lfldv = Number(Math.round(c3lfldv2+'e2')+'e-2')
        console.log('*********evat')
        console.log(c3gv2)
        console.log(c3parv)
        console.log(c3tsv2)
        console.log(transdVat2)
        console.log(c3slv2)
        console.log(c3dsv2)
        console.log(disdVat2)
        console.log(c3sfv2)
        console.log( c3ssv2)
        console.log(c3mfv2)
        console.log(c3msv2)
        console.log(c3lcv2)
        console.log(c3lcfv2)
        console.log('*********')
        var evat1 = c3gv + c3parv + c3tsv +transdVat +  c3slv + c3dsv + disdVat + c3sfv + c3ssv + c3mfv + c3msv +  c3lcv + 
        c3lcfv;
        
        console.log(evat1);
        var newevat1 = Math.round(1000*evat1)/1000;
        console.log(newevat1 + '--' + 'evat');
        var finalevat1 = Number(Math.round(newevat1+'e2')+'e-2')
        console.log('*********************')
        sum5 = finalevat1;
        console.log('*********************')
        // var sum3 =  parseFloat(transdVat) + parseFloat(disdVat) + c3mes + c3mer + c3ec + c3etq + c3nsc + c3nsd + c3fa + c3gv + c3tsv + c3slv + c3dsv + c3lcv + c3lcfv + c3parv + c3sfv + c3ssv + c3mfv + c3msv + c3lfldv;
        // console.log(sum3);
        // console.log(sum3);
        document.querySelector('#missElectS1').value = (missElectS * a.value).toFixed(2); 
        document.querySelector('#missElectR1').value =  c3mer;
        document.querySelector('#enviChrg1').value = (enviChrg * a.value).toFixed(2);
        document.querySelector('#EqualiTR1').value = (EqualiTR * a.value).toFixed(2);
        document.querySelector('#npcStrCC1').value = (npcStrCC * a.value).toFixed(2);
        document.querySelector('#npcStrDebt1').value = (npcStrDebt * a.value).toFixed(2);
        document.querySelector('#fitAll1').value = c3fa;
        document.querySelector('#genVAT1').value = (genVAT * a.value).toFixed(2);
        document.querySelector('#TranSysVAT1').value =  (TranSysVAT * a.value).toFixed(2);   
        document.querySelector('#sLossVAT1').value = (sLossVAT * a.value).toFixed(2);
        document.querySelector('#distSysVAT1').value = (distSysVAT * a.value).toFixed(2);
        document.querySelector('#loanConVAT1').value = (loanConVAT * a.value).toFixed(2);
        document.querySelector('#loanConFxdVAT1').value =  parseFloat(loanConFxdVAT).toFixed(2);
        document.querySelector('#powerActRedVAT1').value = (powerActRedVAT * a.value).toFixed(2);    
        document.querySelector('#suppFixVAT1').value = parseFloat(suppFixVAT).toFixed(2);
        document.querySelector('#suppSysVAT1').value = (suppSysVAT * a.value).toFixed(2);
        document.querySelector('#meterFixVAT1').value = parseFloat(meterFixVAT).toFixed(2);
        document.querySelector('#meterSysVAT1').value = c3msv;
        document.querySelector('#lflnDiscVAT1').value = '0.00';
        document.querySelector('#eVAT1').value = finalevat1;
        
    
        // var sum11 = Math.round(1000*sum1)/1000;
        // console.log(sum11)
        // var finalsum1 = Number(Math.round(sum11+'e2')+'e-2')
    
        // var sum22 = Math.round(1000*sum2)/1000;
        // console.log(sum22)
        // var finalsum2 = Number(Math.round(sum22+'e2')+'e-2')

        // var sum33 = Math.round(1000*sum3)/1000;
        // console.log(sum33)
        // var finalsum3 = Number(Math.round(sum33+'e2')+'e-2')
    
        var currentbill = (sum + sum1 + sum2 + sum3 + sum4 + sum5) - parseFloat(finaltotallf);
        console.log('*************')
        console.log(sum)
        console.log(sum1)
        console.log(sum2)
        console.log(sum3)
        console.log(sum4)
        console.log(sum5)
        console.log(finaltotallf)
        console.log('****************')
        var finalBill1 = Math.round(1000*currentbill)/1000;
        var finalBill = Number(Math.round(finalBill1+'e2')+'e-2')
        console.log(finalBill);
        document.querySelector('#currentBill1').value = parseFloat(finalBill).toFixed(2);
        ajdsend.Current_Bill = parseFloat(finalBill);
        document.querySelector('#surchage1').value = surchage;
        document.querySelector('#mArrears1').value = tarrears.toFixed(2);
        var totalD = parseFloat(tarrears) + parseFloat(surchage);  
        document.querySelector('#totalDue1').value = totalD.toFixed(2);
        // id = "adjAmount"id = "adjkWh"
        var adjamount = parseFloat(finalBill) - parseFloat(cb);
        document.querySelector('#adjAmount').value = parseFloat(adjamount).toFixed(2);
        var adjkwh = parseFloat(a.value) - parseFloat(kwh);
        ajdsend.User_ID = "{{Auth::user()->user_id}}";
        document.querySelector('#adjkWh').value = parseFloat(adjkwh);
            }
        }
        xhr.send();
     
    }
    else{
        document.querySelector('#btnsave').disabled = false;
        var pres = document.querySelector('#presReading1').value;
        if(pres == ''){
            ajdsend.Pres_Reading = 0;
        }
        else{
            ajdsend.Pres_Reading = pres;
        }
        var b = document.querySelector('#prevReading1').value;
        if(b == ''){
            ajdsend.Prev_Reading = 0;
        }
        else{
            ajdsend.Prev_Reading = b;
        }
        document.querySelector('#kUsed1').value = a.value;
        ajdsend.Kwh_Used=parseFloat(a.value);
        var gen = (genSysCharg * a.value);
        var gen1 = Math.round(1000*gen)/1000;
        var finalgen = Number(Math.round(gen1+'e2')+'e-2')

        var pow = (powActRed * a.value);
        var pow1 = Math.round(1000*pow)/1000;
        var pow2 = pow1 * (-1);
        var pow3 = Number(Math.round(pow2+'e2')+'e-2');
        var finalpow = pow3 * (-1);

        var forA = (ForAdjustChrg * a.value);
        var forA1 = Math.round(1000*forA)/1000;
        var finalforA1 = Number(Math.round(forA1+'e2')+'e-2')

        var transSC = (transSysChrg * a.value);
        var transSC1 = Math.round(1000*transSC)/1000;
        var finaltransSC = Number(Math.round(transSC1+'e2')+'e-2')
        
        var sysLossC = (SysLossChrg * a.value);
        var sysLossC1 = Math.round(1000*sysLossC)/1000;
        var finalsysLossC = Number(Math.round(sysLossC1+'e2')+'e-2')

        var fbhost = (franBenHost * a.value);
        var fbhost1 = Math.round(1000*fbhost)/1000;
        var finalfbhost = Number(Math.round(fbhost1+'e2')+'e-2')
        console.log('****')
        var sum = finalgen + finalpow + finalforA1 + finalfbhost;
        console.log('*****')
        
        document.querySelector('#genSysCharg1').value = finalgen;
        document.querySelector('#powActRed1').value = finalpow;
        document.querySelector('#franBenHost1').value = finalfbhost;
        document.querySelector('#ForAdjustChrg1').value = finalforA1;
        document.querySelector('#transSysChrg1').value = finaltransSC;
        document.querySelector('#SysLossChrg1').value = finalsysLossC;
        var demand = document.querySelector('#dUsed1').value;
        ajdsend.Total_Demand_Kwh_Used = demand * mult;
        ajdsend.Demand_Kwh_Used = demand;
        demand = demand * mult;
        ajdsend.lifeline = 0;
        var transDC = parseFloat(TransDemChrg) * demand;
        console.log(TransDemChrg);
        console.log(transDC + 'testing');
        document.querySelector('#TransDemChrg1').value = parseFloat(TransDemChrg) * parseFloat(demand);
        document.querySelector('#disDemChrg1').value = parseFloat(disDemChrg) * parseFloat(demand);  //charges2
        document.querySelector('#TransDemVAT1').value = parseFloat(TransDemVAT) * parseFloat(demand);  //charges3 
        document.querySelector('#distDemVAT1').value = parseFloat(distDemVAT) * parseFloat(demand); //charges3 
        var demtrans1 = parseFloat(TransDemChrg) * demand;
        var demtrans2 = Math.round(1000 * demtrans1)/1000; 
        var demtrans = Number(Math.round(demtrans2+'e2')+'e-2')
        console.log(demtrans + 'testing');
        console.log('****')
        var sum1 = finaltransSC + finalsysLossC + demtrans;
        console.log('*****')

        var demtransV1 = parseFloat(disDemChrg) * demand;
        var demtransV2 = Math.round(1000 * demtransV1)/1000;
        var demtransV = Number(Math.round(demtransV2+'e2')+'e-2')

        var transdVat1 = parseFloat(TransDemVAT) * demand;
        var transdVat2 = Math.round(1000*transdVat1)/1000;
        var transdVat = Number(Math.round(transdVat2+'e2')+'e-2')

        var disdVat1 = parseFloat(distDemVAT) * demand;
        var disdVat2 = Math.round(1000*disdVat1)/1000;
        var disdVat = Number(Math.round(disdVat2+'e2')+'e-2')

        // var sum1 = parseFloat(fbhost)+parseFloat(gen)+parseFloat(pow)+parseFloat(forA)+parseFloat(transSC)+parseFloat(transDC)+parseFloat(sysLossC)+demtrans+demtransV+transdVat+disdVat;

        /*charges2*/
        var dsc1 = (disSysChrg * a.value);
        var dsc2 = Math.round(1000*dsc1)/1000;
        var dsc = Number(Math.round(dsc2+'e2')+'e-2')

        var sfc1 = parseFloat(suppFixChrg);
        var sfc2 = Math.round(1000*sfc1)/1000;
        var sfc = Number(Math.round(sfc2+'e2')+'e-2')

        var ssc1 = (suppSysChrg * a.value);
        var ssc2 = Math.round(1000*ssc1)/1000;
        var ssc = Number(Math.round(ssc2+'e2')+'e-2')

        var mfc1 =  parseFloat(meterFixChrg);
        var mfc2 = Math.round(1000 * mfc1)/1000;
        var mfc = Number(Math.round(mfc2+'e2')+'e-2')
    
        var msc1 =  (meterSysChrg * a.value);
        var msc2 = Math.round(1000 * msc1)/1000;
        var msc = Number(Math.round(msc2+'e2')+'e-2')
        console.log('**********')
        sum2 = demtransV + dsc + sfc + ssc + mfc + msc;
        console.log('**********')
        var lflds1 = (lflnDisSubs * a.value);
        var lflds2 = Math.round(1000 * lflds1)/1000;
        var lflds = Number(Math.round(lflds2+'e2')+'e-2')

        var scds1 = (senCitDiscSubs * a.value);
        var scds2 = Math.round(1000 * scds1)/1000;
        var scds = Number(Math.round(scds2+'e2')+'e-2')
    
        var iccs1 = (iCCS * a.value);
        var iccs2 = Math.round(1000 * iccs1)/1000;
        var iccs = Number(Math.round(iccs2+'e2')+'e-2')
    
        var mcc1 = (mccCapex * a.value);
        var mcc2 = Math.round(1000 * mcc1)/1000
        var mcc = Number(Math.round(mcc2+'e2')+'e-2')
    
        var lc1 = (loanCondon * a.value);
        var lc2 = Math.round(1000 * lc1);
        var lc = Number(Math.round(lc2+'e2')+'e-2')
    

        var lcf1 = parseFloat(loanCondonFix);
        var lcf2 = Math.round(1000*lcf1)/1000;
        var lcf = Number(Math.round(lcf2+'e2')+'e-2')
        console.log('**********')
        sum3 = lflds + scds + iccs + mcc + lc + lcf;
        console.log('**********')
        // var sum2 = dsc + sfc + ssc + mfc + msc + lflds + scds + iccs + mcc + lc + lcf;

        document.querySelector('#disSysChrg1').value = (disSysChrg * a.value).toFixed(2);
        document.querySelector('#suppFixChrg1').value = parseFloat(suppFixChrg).toFixed(2);
        document.querySelector('#suppSysChrg1').value =  (suppSysChrg * a.value).toFixed(2);
        document.querySelector('#meterFixChrg1').value = parseFloat(meterFixChrg).toFixed(2);
        document.querySelector('#meterSysChrg1').value = (meterSysChrg * a.value).toFixed(2);
        document.querySelector('#lflnDisSubs1').value = (lflnDisSubs * a.value).toFixed(2);
        document.querySelector('#senCitDiscSubs1').value = (senCitDiscSubs * a.value).toFixed(2);
        document.querySelector('#iCCS1').value = (iCCS * a.value).toFixed(2);
        document.querySelector('#mccCapex1').value = (mccCapex * a.value).toFixed(2);
        document.querySelector('#loanCondon1').value = (loanCondon * a.value).toFixed(2);
        document.querySelector('#loanCondonFix1').value = parseFloat(loanCondonFix).toFixed(2);
        /* charges3 */
        var c3mes1 = (missElectS * a.value);
        var c3mes2 = Math.round(1000*c3mes1)/1000;
        var c3mes = Number(Math.round(c3mes2+'e2')+'e-2')

        var c3mer1 = (missElectR * a.value);
        var c3mer2 = Math.round(1000*c3mer1)/1000;
        var c3mer = Number(Math.round(c3mer2+'e2')+'e-2')
    
        var c3ec1 = (enviChrg * a.value);
        var c3ec2 = Math.round(1000*c3ec1)/1000;
        var c3ec = Number(Math.round(c3ec2+'e2')+'e-2')
    
        var c3etq1 = (EqualiTR * a.value);
        var c3etq2 = Math.round(1000*c3etq1)/1000;
        var c3etq = Number(Math.round(c3etq2+'e2')+'e-2')
    
        var c3nsc1 = (npcStrCC * a.value);
        var c3nsc2 = Math.round(1000*c3nsc1)/1000;
        var c3nsc = Number(Math.round(c3nsc2+'e2')+'e-2')

        var c3nsd1 = (npcStrDebt * a.value);
        var c3nsd2 = Math.round(1000*c3nsd1)/1000;
        var c3nsd = Number(Math.round(c3nsd2+'e2')+'e-2')
    
        var c3fa1 = (fitAll * a.value);
        var c3fa2 = Math.round(1000*c3fa1)/1000;
        var c3fa = Number(Math.round(c3fa2+'e2')+'e-2')
        console.log('*********************')
        sum4 = c3mes + c3mer + c3ec + c3etq + c3nsc + c3nsd + c3fa;
        console.log('*********************')
        var c3gv1 = (genVAT * a.value);
        var c3gv2 = Math.round(1000*c3gv1)/1000;
        var c3gv = Number(Math.round(c3gv2+'e2')+'e-2')
    
        var c3tsv1 = (TranSysVAT * a.value);
        var c3tsv2 = Math.round(1000*c3tsv1)/1000;
        var c3tsv = Number(Math.round(c3tsv2+'e2')+'e-2')
        
        var c3slv1 = (sLossVAT * a.value);
        var c3slv2 = Math.round(1000*c3slv1)/1000;
        var c3slv = Number(Math.round(c3slv2+'e2')+'e-2')
        
        var c3dsv1 = (distSysVAT * a.value);
        
        var c3dsv2 = Math.round(1000*c3dsv1)/1000;
        var c3dsv = Number(Math.round(c3dsv2+'e2')+'e-2')
        
        var c3lcv1 = (loanConVAT * a.value);
        var c3lcv2 = Math.round(1000*c3lcv1)/1000;
        var c3lcv = Number(Math.round(c3lcv2+'e2')+'e-2')
    
        var c3lcfv1 = parseFloat(loanConFxdVAT);
        var c3lcfv2 = Math.round(1000*c3lcfv1)/1000;
        var c3lcfv = Number(Math.round(c3lcfv2+'e2')+'e-2')
        
        var c3parv1 =  (powerActRedVAT * a.value);
        var c3parv2 = Math.round(1000*c3parv1)/1000;
        var c3parv3 = c3parv2 * (-1);
        var c3parv4 = Number(Math.round(c3parv3+'e2')+'e-2');
        var c3parv = c3parv4 * (-1);
        
        var c3sfv1 = parseFloat(suppFixVAT);
        var c3sfv2 = Math.round(1000*c3sfv1)/1000;
        var c3sfv = Number(Math.round(c3sfv2+'e2')+'e-2')
    
        var c3ssv1 = (suppSysVAT * a.value);
        var c3ssv2 = Math.round(1000*c3ssv1)/1000;
        var c3ssv = Number(Math.round(c3ssv2+'e2')+'e-2')
    
        var c3mfv1 = parseFloat(meterFixVAT);
        var c3mfv2 = Math.round(1000*c3mfv1)/1000;
        var c3mfv = Number(Math.round(c3mfv2+'e2')+'e-2')
        
        var c3msv1 = (meterSysVAT * a.value);
        var c3msv2 = Math.round(1000*c3msv1)/1000;
        var c3msv = Number(Math.round(c3msv2+'e2')+'e-2')
        
        var c3lfldv1 = (lflnDiscVAT * a.value);
        var c3lfldv2 = Math.round(1000*c3lfldv1)/1000;
        var c3lfldv = Number(Math.round(c3lfldv2+'e2')+'e-2')
    
        var evat1 = c3gv + c3parv + c3tsv +transdVat +  c3slv + c3dsv + disdVat + c3sfv + c3ssv + c3mfv + c3msv +  c3lcv + 
        c3lcfv + c3lfldv;
        
        var newevat1 = Math.round(1000*evat1)/1000;
        var finalevat1 = Number(Math.round(newevat1+'e2')+'e-2')
        console.log('*********************')
        sum5 = finalevat1;
        console.log('*********************')
        // var sum3 =  parseFloat(transdVat) + parseFloat(disdVat) + c3mes + c3mer + c3ec + c3etq + c3nsc + c3nsd + c3fa + c3gv + c3tsv + c3slv + c3dsv + c3lcv + c3lcfv + c3parv + c3sfv + c3ssv + c3mfv + c3msv + c3lfldv;
        // console.log(sum3);
        // console.log(sum3);
        document.querySelector('#missElectS1').value = (missElectS * a.value).toFixed(2); 
        document.querySelector('#missElectR1').value =  c3mer;
        document.querySelector('#enviChrg1').value = (enviChrg * a.value).toFixed(2);
        document.querySelector('#EqualiTR1').value = (EqualiTR * a.value).toFixed(2);
        document.querySelector('#npcStrCC1').value = (npcStrCC * a.value).toFixed(2);
        document.querySelector('#npcStrDebt1').value = (npcStrDebt * a.value).toFixed(2);
        document.querySelector('#fitAll1').value = (fitAll * a.value).toFixed(2);
        document.querySelector('#genVAT1').value = (genVAT * a.value).toFixed(2);
        document.querySelector('#TranSysVAT1').value =  (TranSysVAT * a.value).toFixed(2);   
        document.querySelector('#sLossVAT1').value = (sLossVAT * a.value).toFixed(2);
        document.querySelector('#distSysVAT1').value = (distSysVAT * a.value).toFixed(2);
        document.querySelector('#loanConVAT1').value = (loanConVAT * a.value).toFixed(2);
        document.querySelector('#loanConFxdVAT1').value =  parseFloat(loanConFxdVAT).toFixed(2);
        document.querySelector('#powerActRedVAT1').value = (powerActRedVAT * a.value).toFixed(2);    
        document.querySelector('#suppFixVAT1').value = parseFloat(suppFixVAT).toFixed(2);
        document.querySelector('#suppSysVAT1').value = (suppSysVAT * a.value).toFixed(2);
        document.querySelector('#meterFixVAT1').value = parseFloat(meterFixVAT).toFixed(2);
        document.querySelector('#meterSysVAT1').value = (meterSysVAT * a.value).toFixed(2);
        document.querySelector('#lflnDiscVAT1').value = (lflnDiscVAT * a.value).toFixed(2);
        document.querySelector('#eVAT1').value = finalevat1;
        
    
        // var sum11 = Math.round(1000*sum1)/1000;
        // console.log(sum11)
        // var finalsum1 = Number(Math.round(sum11+'e2')+'e-2')
    
        // var sum22 = Math.round(1000*sum2)/1000;
        // console.log(sum22)
        // var finalsum2 = Number(Math.round(sum22+'e2')+'e-2')

        // var sum33 = Math.round(1000*sum3)/1000;
        // console.log(sum33)
        // var finalsum3 = Number(Math.round(sum33+'e2')+'e-2')
    
        var currentbill = sum + sum1 + sum2 + sum3 + sum4 + sum5;
        console.log(currentbill +'--'+ 'current');
        var finalBill1 = Math.round(1000*currentbill)/1000;
        var finalBill = Number(Math.round(finalBill1+'e2')+'e-2')

        document.querySelector('#currentBill1').value = parseFloat(finalBill).toFixed(2);
        ajdsend.Current_Bill = parseFloat(finalBill);
        document.querySelector('#surchage1').value = surchage;
        document.querySelector('#mArrears1').value = tarrears.toFixed(2);
        var totalD = parseFloat(tarrears) + parseFloat(surchage);  
        document.querySelector('#totalDue1').value = totalD.toFixed(2);
        // id = "adjAmount"id = "adjkWh"
        var adjamount = parseFloat(finalBill) - parseFloat(cb);
        document.querySelector('#adjAmount').value = parseFloat(adjamount).toFixed(2);
        var adjkwh = parseFloat(a.value) - parseFloat(kwh);
        ajdsend.User_ID = "{{Auth::user()->user_id}}";
        document.querySelector('#adjkWh').value = parseFloat(adjkwh);
    // adjDSC[0]+adjDDC[0]+adjSFC[0]+adjSSC[0]+adjMFC[0]+adjMSC[0]+adjLDS[0]+adjSCDS[0]+adjICCS[0]+adjMC[0]+adjLC[0]+adjLCF[0];
 // event listener for keyup
    }
}
function adjustmentPB(){
    console.log(ajdsend)
    Swal.fire({
        title: 'Do you want to continue?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
}).then((result) => {
  if (result.isConfirmed) {
    document.querySelector('#genSysCharg1').value = 0.00;
    document.querySelector('#powActRed1').value = 0.00;
    document.querySelector('#franBenHost1').value = 0.00;
    document.querySelector('#ForAdjustChrg1').value = 0.00;
    document.querySelector('#transSysChrg1').value = 0.00;
    document.querySelector('#SysLossChrg1').value = 0.00;
    document.querySelector('#missElectS1').value = 0.00; 
    document.querySelector('#missElectR1').value =  0.00;
    document.querySelector('#enviChrg1').value = 0.00;
    document.querySelector('#EqualiTR1').value = 0.00;
    document.querySelector('#npcStrCC1').value = 0.00;
    document.querySelector('#npcStrDebt1').value = 0.00;
    document.querySelector('#fitAll1').value = 0.00;
    document.querySelector('#genVAT1').value = 0.00;
    document.querySelector('#TranSysVAT1').value =  0.00;   
    document.querySelector('#sLossVAT1').value = 0.00;
    document.querySelector('#distSysVAT1').value = 0.00;
    document.querySelector('#loanConVAT1').value = 0.00;
    document.querySelector('#loanConFxdVAT1').value =  0.00;
    document.querySelector('#powerActRedVAT1').value = 0.00;    
    document.querySelector('#suppFixVAT1').value = 0.00;
    document.querySelector('#suppSysVAT1').value = 0.00;
    document.querySelector('#meterFixVAT1').value = 0.00;
    document.querySelector('#meterSysVAT1').value = 0.00;
    document.querySelector('#lflnDiscVAT1').value = 0.00;
    document.querySelector('#disSysChrg1').value = 0.00;
    document.querySelector('#suppFixChrg1').value = 0.00;
    document.querySelector('#suppSysChrg1').value =  0.00;
    document.querySelector('#meterFixChrg1').value = 0.00;
    document.querySelector('#meterSysChrg1').value = 0.00;
    document.querySelector('#lflnDisSubs1').value = 0.00;
    document.querySelector('#senCitDiscSubs1').value = 0.00;
    document.querySelector('#iCCS1').value = 0.00;
    document.querySelector('#mccCapex1').value = 0.00;
    document.querySelector('#loanCondon1').value = 0.00;
    document.querySelector('#loanCondonFix1').value = 0.00;
    document.querySelector('#currentBill1').value = 0.00;
    document.querySelector('#surchage1').value = 0.00;
    document.querySelector('#mArrears1').value = 0.00;
    document.querySelector('#totalDue1').value = 0.00;
    document.querySelector('#adjAmount').value = 0.00;
    document.querySelector('#adjkWh').value = 0.00;
    var d = {"Data":ajdsend};
    var xhr = new XMLHttpRequest();
    var powerBill = "{{route('powerbill.adjustment')}}";
            xhr.open('POST', powerBill, true);
            xhr.setRequestHeader("Accept", "application/json");
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onload = function() {
                if(this.status == 200){
                    Swal.fire({
                    position: 'middle',
                    icon: 'success',
                    title: 'Your work has been saved',
                    showConfirmButton: false,
                    timer: 1500
                    })
                }
            }
            xhr.send(JSON.stringify(d));
            var dat = ajdsend.Date;
            var cons = cons1;
            setConsAcct(cons);
            datePout(dat);
            ajdsend = {};
  }
})
    
}
function setConsAcct(cons){
    cons1 = cons;
    id = cons.id;
    console.log(id);
    document.querySelector('#consAcct').style.display = "none";
    var accName3 = cons.childNodes[0].innerHTML;
    document.querySelector('#accNoID').value = accName3;
    // var acc = accName3.replaceAll('-','');
}
function datakw(b){
    document.querySelector('#butt').value = b.value;
}
// 
</script>

@endsection
