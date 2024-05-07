@extends('layout.master')
@section('title', 'Data Entry of Billing Rates')
@section('content')

<style>
    #billingPeriod {
        width: 90%;
        cursor: pointer;
    }
    #currentPeriod {
        font-weight: bold;
    }
    #consTypeTable {
        width: 95%; 
        margin: auto;
        background-color: white;
        color: black;
        margin-top: 20px;
    }
    #consTypeTable td {
        height: 50px;
        border-bottom: 1px solid #ddd;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    button {
        border: none;
        border-radius: 3px;
        color: white;
    }
    .createBtn {
        background-color: #555;
    }
    .previewBtn {
        background-color: #5B9BD5;
    }
    h4{
        text-align: center;
    }
    input {
        height: 35px;
    }
    #GCFBtotal {
        width: 36.7%;
        float: right;
        margin-right: 1%;
    }
    #TCDCtotal {
        width: 36.7%;
        float: right;
        margin-right: 1%;
    }
    #MCtotal {
        width: 36.7%;
        float: right;
        margin-right: 1%;
    }
    .addBRBtn {
        background-color: royalblue;
        color: white;
        float: right;
        margin-right: 7px;
        height: 40px;
        width: 15%;
    }
    .saveBtn {
        height: 40px;
        background-color: royalblue;
        color: white;
        float: right;
        margin-right: 6px;
        width: 15%;
    }
    input {
        color: black;
    }
    #BillRatesModal {
        width: 85%; 
        height: 650px; 
            
    }
    #addBillRatesModal {
        width: 85%; 
        height: 650px; 
        margin-top: -80px;
    }
    #addRatesTable {
        width: 97%; 
        margin: auto;
    }
    #btnRatesDtlChrge {
        width: 80px;
    }
    .printBtn {
        display: none;
    }
    @media screen and (min-width:1681px) and (max-width: 1920px) {
        #consTypeTable td {
            height: 60px;
        }
        #billingPeriod {
            height: 50px;
        }
        #addBillRatesModal {
            height: 870px;
        }
        #BillRatesModal {
            height: 870px;
        }
        #addRatesTable input {
            height: 50px;
        } 
        #addRatesTable {
            font-size: 20px;
        }
        .createBtn {
            font-size: 22px;
            height: 45px;
            width: 35%;
        }
        .previewBtn {
            font-size: 22px;
            height: 45px;
            width: 35%;
        }
    }
    #printBRBtn {
        float: right;
        color: royalblue;
        margin-right: 2%;
    }
</style>
<p class="contentheader">Data Entry of Billing Rates</p>
<div class="main">
    <table style="width: 97%; margin: auto;">
        <tr>
            <td style="width: 12%;">
                &nbsp; Billing Period:
            </td>
            <td style="width: 25%;">
                <input type="month" id="billingPeriod">
                <input type="text" id="billId" hidden>
                <input type="text" id="userid" value="{{Auth::user()->user_id}}" hidden>
            </td>
            <br>
            <td id="currentPeriod"></td>
        </tr>
    </table>
    <table id="consTypeTable"> </table>
</div>

<!-- Modal -->
<div class="modal fade" id="billRates" tabindex="-1" data-backdrop="false" role="dialog" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><span id='billRatesConsType'></span> RATES </h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="color: black">
              <div class="row" >
                  <h5 name="consName" class="col-8" id="consName"></h5>
                  <h5 name="consKwh" class="col-2" id="consKwh"></h5>
                  <h5 name="consLifeline" class="col-2" id="consLifeline"></h5>
              </div>
              <hr>
               <div class="row">
                   <div class="col-6" style="border-right: solid">
                       <div id="chrges1" class="row">
                            <h6 style="background: green; color:white">General Charges</h6>
                            <label class="col-5 input-check">General System Charge</label>
                            <input type="text" id="genSysCharge" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Power Act Reduction</label>
                            <input type="text" id="powerActReduct" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Franchise & Beneficiary to Host</label>
                            <input type="text" id="FBH" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">FOREX Adjustment Charge</label>
                            <input type="text" id="FAC" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check" style="background: blue; color:white">SUB-TOTAL (GC)</label>
                            <input type="number" class="col-5" id="PrevGenChargeTotal" readonly>
                          
                         
                            <h6 style="background: green; color:white">Transmission Charges</h6>
                            <label class="col-5 input-check">Transmission System Charge</label>
                            <input type="text" id="tranSysCharge" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Transmission Demand Charge</label>
                            <input type="text" id="transDemCharge" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">System Loss Charge</label>
                            <input type="text" id="sysLossCharge" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check" style="background: blue; color:white">SUB-TOTAL (TC)</label>
                            <input type="number" class="col-5" id="PrevTransChargeTotal" readonly> 
  
                            <h6 style="background: green; color:white">Distribution Charges</h6>
                            <label class="col-5 input-check">Distribution System Charge</label>
                            <input type="text" id="distSysCharge" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Distribution Demand Charge</label>
                            <input type="text" id="distDemCharge" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Supply Fix Charge</label>
                            <input type="text" id="suppFixCharge" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Supply System Charge</label>
                            <input type="text" id="suppSysCharge" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Metering Fix Charge</label>
                            <input type="text" id="meterFixCharge" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Metering System Charge</label>
                            <input type="text" id="meterSysCharge" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check" style="background: blue; color:white">SUB-TOTAL (DC)</label>
                            <input type="number" class="col-5" id="PrevDistCharge" readonly>  
                          
                            <h6 style="background: green; color:white">Other Charges</h6>
                            <label class="col-5 input-check">Lifeline Disc./Subs.</label>
                            <input type="text" id="lifelineDiscSub" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Senior Citizen Disc./Subs.</label>
                            <input type="text" id="srCitDiscSub" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Int. Class Cross Subs.</label>
                            <input type="text" id="intClassCrossSub" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">MCC CAPEX</label>
                            <input type="text" id="MCC" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Loan Condonation</label>
                            <input type="text" id="loanCondo" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Loan Condonation Fix</label>
                            <input type="text" id="loanCondoFix" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check" style="background: blue; color:white">SUB-TOTAL (OC)</label>
                            <input type="number" class="col-5" id="PrevOtherCharge" readonly> 
                       </div> 
                   </div>
                   <div class="col-6">
                       <div class="row" id="chrges2">
                            <h6 style="background: green; color:white">Government Charges</h6>
                            <label class="col-5 input-check">Miss. Elect. (SPUG)</label>
                            <input type="text" id="misElectSPUG" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Miss. Elect. (RED)</label>
                            <input type="text" id="misElectRED" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Environment Charge</label>
                            <input type="text" id="envCharge" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Equality of Taxes & Royalty</label>
                            <input type="text" id="eqTaxRoyalty" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">NPC Stranded Cons. Cost</label>
                            <input type="text" id="NPCStranCons" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">NPC Stranded Debt</label>
                            <input type="text" id="NPCStranDebt" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">FIT-ALL (Renew)</label>
                            <input type="text" id="FITALL" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check" style="background: blue; color:white">SUB-TOTAL (GC)</label>
                            <input type="number" class="col-5" id="PrevGovCharge" readonly>  
  
                            <h6 style="background: green; color:white">Value Added Tax</h6>
                            <label class="col-5 input-check">Generation VAT</label>
                            <input type="text" id="genVAT" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Power Act Red. VAT</label>
                            <input type="text" id="powerActRedVAT" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Transmission System VAT</label>
                            <input type="text" id="tranSysVAT" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Transmission Demand VAT</label>
                            <input type="text" id="transDemVAT" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">System Loss VAT</label>
                            <input type="text" id="sysLossVAT" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Distribution System VAT</label>
                            <input type="text" id="distSysVAT" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Distribution Demand VAT</label>
                            <input type="text" id="distDemVAT" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Supply Sys. VAT</label>
                            <input type="text" id="suppSysVAT" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Supply Fix VAT</label>
                            <input type="text" id="suppFixVAT" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Metering Fix VAT</label>
                            <input type="text" id="meterFixVAT" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Metering Sys. VAT</label>
                            <input type="text" id="meterSysVAT" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Lfln Disc./Subs. VAT</label>
                            <input type="text" id="lflnDiscSubs" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Loan Condo. VAT</label>
                            <input type="text" id="loanCondoVAT" class="col-5" placeholder="0.000">
                            <label class="col-5 input-check">Loan Condo.Fixed VAT</label>
                            <input type="text" id="loanCondoFixVAT" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check">Other VAT</label>
                            <input type="text" id="otherVAT" class="col-5" placeholder="0.0000">
                            <label class="col-5 input-check" style="background: blue; color:white">SUB-TOTAL (VAT)</label>
                            <input type="number" class="col-5" id="PreVAT" readonly>

                            <h4 style="text-align: left;"> KWH ENERGY CHARGE: <span id="totalKWHchrge"> 0 </span></h4>
                            <h4 style="text-align: left;"> DEMAND CHARGE: <span id="totalDmdChrge"> 0 </span></h4>
                            <h4 style="text-align: left;"> FIX  CHARGE: <span id="totalFixChrge"> 0 </span></h4>
                       </div>
                   </div>
               </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary printBtn" onclick="printRates()"> Print </button>
            <div id="billRatesButton"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addBillRates" tabindex="-1" data-backdrop="false" role="dialog" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> <span id="addBillRatesConsType"></span> RATES</h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="color: black">
              <div class="row" >
                  <h5 name="consName" class="col-8" id="consName"></h5>
                  <h5 name="consKwh" class="col-2" id="consKwh"></h5>
                  <h5 name="consLifeline" class="col-2" id="consLifeline"></h5>
              </div>
              <hr>
               <div class="row">
                   <div class="col-6" style="border-right: solid">
                       <div id="chrges1" class="row">
                            <h6 style="background: green; color:white">General Charges</h6>
                            <label class="col-5">General System Charge</label>
                            <input type="text" id="newGenSysCharge" class="col-5 genCharges" placeholder="0.00000">
                            <label class="col-5">Power Act Reduction</label>
                            <input type="text" id="newPowerActReduct" class="col-5 genCharges" placeholder="0.00000">
                            <label class="col-5">Franchise & Beneficiary to Host</label>
                            <input type="text" id="newFBH" class="col-5 genCharges" placeholder="0.00000">
                            <label class="col-5">FOREX Adjustment Charge</label>
                            <input type="text" id="newFAC" class="col-5 genCharges" placeholder="0.00000">
                            <label class="col-5" style="background: blue; color:white">SUB-TOTAL (GC)</label>
                            <input type="number" id="genChargeTotal" class="col-5" placeholder="0.0000" readonly>
                          
                         
                            <h6 style="background: green; color:white">Transmission Charges</h6>
                            <label class="col-5">Transmission System Charge</label>
                            <input type="number" id="newTranSysCharge" class="col-5" placeholder="0.00000">
                            <label class="col-5">Transmission Demand Charge</label>
                            <input type="number" id="newTransDemCharge" class="col-5" placeholder="0.00000">
                            <label class="col-5">System Loss Charge</label>
                            <input type="number" id="newSysLossCharge" class="col-5" placeholder="0.00000">
                            <label class="col-5" style="background: blue; color:white">SUB-TOTAL (TC)</label>
                            <input type="number" id="transChargeTotal" class="col-5" placeholder="0.0000" readonly> 
  
                            <h6 style="background: green; color:white">Distribution Charges</h6>
                            <label class="col-5">Distribution System Charge</label>
                            <input type="number" id="newDistSysCharge" class="col-5" placeholder="0.00000">
                            <label class="col-5">Distribution Demand Charge</label>
                            <input type="number" id="newDistDemCharge" class="col-5" placeholder="0.00000">
                            <label class="col-5">Supply Fix Charge</label>
                            <input type="number" id="newSuppFixCharge" class="col-5" placeholder="0.00000">
                            <label class="col-5">Supply System Charge</label>
                            <input type="number" id="newSuppSysCharge" class="col-5" placeholder="0.00000">
                            <label class="col-5">Metering Fix Charge</label>
                            <input type="number" id="newMeterFixCharge" class="col-5" placeholder="0.00000">
                            <label class="col-5">Metering System Charge</label>
                            <input type="number" id="newMeterSysCharge" class="col-5" placeholder="0.00000">
                            <label class="col-5" style="background: blue; color:white">SUB-TOTAL (DC)</label>
                            <input type="number" id="distCharge" class="col-5" placeholder="0.0000" readonly>  
                          
                            <h6 style="background: green; color:white">Other Charges</h6>
                            <label class="col-5">Lifeline Disc./Subs.</label>
                            <input type="number" id="newLifelineDiscSub" class="col-5" placeholder="0.00000">
                            <label class="col-5">Senior Citizen Disc./Subs.</label>
                            <input type="number" id="newSrCitDiscSub" class="col-5" placeholder="0.00000">
                            <label class="col-5">Int. Class Cross Subs.</label>
                            <input type="number" id="newIntClassCrossSub" class="col-5" placeholder="0.00000">
                            <label class="col-5">MCC CAPEX</label>
                            <input type="number" id="newMCC" class="col-5" placeholder="0.00000">
                            <label class="col-5">Loan Condonation</label>
                            <input type="number" id="newLoanCondo" class="col-5" placeholder="0.00000">
                            <label class="col-5">Loan Condonation Fix</label>
                            <input type="number" id="newLoanCondoFix" class="col-5" placeholder="0.00000">
                            <label class="col-5" style="background: blue; color:white">SUB-TOTAL (OC)</label>
                            <input type="number" id="otherCharge" class="col-5" placeholder="0.0000" readonly> 
                       </div> 
                   </div>
                   <div class="col-6">
                       <div class="row" id="chrges2">
                            <h6 style="background: green; color:white">Government Charges</h6>
                            <label class="col-5">Miss. Elect. (SPUG)</label>
                            <input type="number" id="newMisElectSPUG" class="col-5" placeholder="0.00000">
                            <label class="col-5">Miss. Elect. (RED)</label>
                            <input type="number" id="newMisElectRED" class="col-5" placeholder="0.00000">
                            <label class="col-5">Environment Charge</label>
                            <input type="number" id="newEnvCharge" class="col-5" placeholder="0.00000">
                            <label class="col-5">Equality of Taxes & Royalty</label>
                            <input type="number" id="newEqTaxRoyalty" class="col-5" placeholder="0.00000">
                            <label class="col-5">NPC Stranded Cons. Cost</label>
                            <input type="number" id="newNPCStranCons" class="col-5" placeholder="0.00000">
                            <label class="col-5">NPC Stranded Debt</label>
                            <input type="number" id="newNPCStranDebt" class="col-5" placeholder="0.00000">
                            <label class="col-5">FIT-ALL (Renew)</label>
                            <input type="number" id="newFITALL" class="col-5" placeholder="0.00000">
                            <label class="col-5" style="background: blue; color:white">SUB-TOTAL (GC)</label>
                            <input type="number" id="govCharge" class="col-5" placeholder="0.0000" readonly>  
  
                            <h6 style="background: green; color:white">Value Added Tax</h6>
                            <label class="col-5">Generation VAT</label>
                            <input type="number" id="newGenVAT" class="col-5" placeholder="0.00000">
                            <label class="col-5">Power Act Red. VAT</label>
                            <input type="number" id="newPowerActRedVAT" class="col-5" placeholder="0.00000">
                            <label class="col-5">Transmission System VAT</label>
                            <input type="number" id="newTranSysVAT" class="col-5" placeholder="0.00000">
                            <label class="col-5">Transmission Demand VAT</label>
                            <input type="number" id="newTransDemVAT" class="col-5" placeholder="0.00000">
                            <label class="col-5">System Loss VAT</label>
                            <input type="number" id="newSysLossVAT" class="col-5" placeholder="0.00000">
                            <label class="col-5">Distribution System VAT</label>
                            <input type="number" id="newDistSysVAT" class="col-5" placeholder="0.00000">
                            <label class="col-5">Distribution Demand VAT</label>
                            <input type="number" id="newDistDemVAT" class="col-5" placeholder="0.00000">
                            <label class="col-5">Supply Sys. VAT</label>
                            <input type="number" id="newSuppSysVAT" class="col-5" placeholder="0.00000">
                            <label class="col-5">Supply Fix. VAT</label>
                            <input type="number" id="newSuppFixVAT" class="col-5" placeholder="0.0000">
                            <label class="col-5">Metering Fix VAT</label>
                            <input type="number" id="newMeterFixVAT" class="col-5" placeholder="0.00000">
                            <label class="col-5">Metering Sys. VAT</label>
                            <input type="number" id="newMeterSysVAT" class="col-5" placeholder="0.00000">
                            <label class="col-5">Lfln Disc./Subs. VAT</label>
                            <input type="number" id="newLflnDiscSubs" class="col-5" placeholder="0.00000">;
                            <label class="col-5">Loan Condo. VAT</label>
                            <input type="number" id="newLoanCondoVAT" class="col-5" placeholder="0.00000">
                            <label class="col-5">Loan Condo.Fixed VAT</label>
                            <input type="number" id="newLoanCondoFixVAT" class="col-5" placeholder="0.00000">
                            <label class="col-5">Other VAT</label>
                            <input type="number" id="newOtherVAT" class="col-5" placeholder="0.00000">
                            <label class="col-5" style="background: blue; color:white">SUB-TOTAL (VAT)</label>
                            <input type="number" id="VAT" class="col-5" placeholder="0.0000" readonly>

                            <h4 style="text-align: left;"> KWH ENERGY CHARGE: <span id="addTotalKWHchrge"> 0 </span></h4>
                            <h4 style="text-align: left;"> DEMAND CHARGE: <span id="addTotalDmdChrge"> 0 </span></h4>
                            <h4 style="text-align: left;"> FIX  CHARGE: <span id="addTotalFixChrge"> 0 </span></h4>
                            <input type="text" id="addBillPeriod" hidden>
                            <input type="text" id="addConsType" hidden>
                       </div>
                   </div>
               </div>
        </div>
        <div class="modal-footer">
  
          <button type="button" class="btn btn-secondary addBtn" onclick="addRates()">Add</button>
        </div>
      </div>
    </div>
  </div>

<script>
    var userId = "{{Auth::user()->user_id}}"; 
    var xhr = new XMLHttpRequest();
    var l = "{{Auth::id()}}";
        
    var billPeriod = document.querySelector('#billingPeriod');
    billPeriod.addEventListener("change", showRates);
   

    function showRates(){
        var xhr = new XMLHttpRequest();
        var billingPeriod = document.querySelector("#billingPeriod").value;

        var route = "{{route('get.billrate.billperiod',['billingPeriod'=>':par'])}}"
        xhr.open('GET', route.replace(':par', billingPeriod), true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200){ 
                var response = JSON.parse(this.responseText);
                var consType = response.Data;
                var output = "<tr id='thead'> <td> &nbsp; Consumer Type </td> <td> Code </td> <td style='text-align: center;'> <button id='printBRBtn' class='btn btn-light' onclick='printRates()'> Print Rates </buttin> </td> </tr>";
                for(var a in consType){
                    output += "<tr> <td> &nbsp;&nbsp;" + consType[a].ct_desc + "</td>";
                    output += "<td> &nbsp;&nbsp;" + consType[a].ct_code + "</td>";
                    
                    if(consType[a].id !== ""){
                        if(consType[a].id > 0){
                            output += '<td style="text-align: center;"> <button id="btnRatesDtlChrge" type="button" class="btn btn-secondary col-5 m-2"';
                            output += 'data-toggle="modal" data-target="#billRates" billPeriod="' + billingPeriod + '" consCode="' + consType[a].ct_code + '" consType="' + consType[a].ct_desc +'"';
                            output += 'billId ="' + consType[a].id + '" ct_id ="' + consType[a].ct_id + '" onclick="viewRates(this)"> Preview </button> </td>';
                        } else {
                            output += '<td style="text-align: center;"> <button id="btnRatesDtlChrge" type="button" class="btn btn-primary col-5 m-2"';
                            output += 'data-toggle="modal" data-target="#addBillRates" billPeriod="' + billingPeriod + '" consCode="' + consType[a].ct_code + '" consumerType="' + consType[a].ct_desc + '" consType="' + consType[a].ct_id +'"';
                            output += 'onclick="addBillRates(this)"> Create </button> </td>';
                        }
                    } else{
                        output += '<td style="text-align: center;"> <button id="btnRatesDtlChrge" type="button" class="btn btn-primary col-5 m-2"';
                        output += 'data-toggle="modal" data-target="#addBillRates" billPeriod="' + billingPeriod + '" consCode="' + consType[a].ct_code + '" consumerType="' + consType[a].ct_desc + '" consType="' + consType[a].ct_id +'"';
                        output += 'onclick="addBillRates(this)"> Create </button> </td>';
                    }
                }
            }
            document.querySelector('#consTypeTable').innerHTML = output;
        }
    }
    
    function viewRates(x){
        listenEditInput();
        
        var billPeriod = x.getAttribute("billPeriod");
        var consType = x.getAttribute("consType");
        var consCode = x.getAttribute("consCode");
        var billId = x.getAttribute("billId");
        var ct_id = x.getAttribute("ct_id");

        document.querySelector("#billId").value = billId;
        var billDate = JSON.stringify(billPeriod);
        var date = billDate.slice(1, 5) + billDate.slice(6,8);
        var viewRates = "{{route('get.rate', ['consType'=>':consType', 'billPeriod'=>':billPeriod'])}}";
        var newViewRates = viewRates.replace(':consType', ct_id);
        var newViewRates2 = newViewRates.replace(':billPeriod', date);

        xhr.open('GET', newViewRates2, true);

        xhr.send();

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                document.querySelector("#billRatesConsType").innerHTML = consType;
                document.querySelector("#genSysCharge").value = data[0].br_gensys_rate;
                document.querySelector("#powerActReduct").value = data[0].br_par_rate;
                document.querySelector("#FBH").value = data[0].br_fbhc_rate;
                document.querySelector("#FAC").value = data[0].br_forex_rate;
                document.querySelector("#tranSysCharge").value = data[0].br_transsys_rate;
                document.querySelector("#transDemCharge").value = data[0].br_transdem_rate;
                document.querySelector("#sysLossCharge").value = data[0].br_sysloss_rate;
                document.querySelector("#distSysCharge").value = data[0].br_distsys_rate;
                document.querySelector("#distDemCharge").value = data[0].br_distdem_rate;
                document.querySelector("#suppFixCharge").value = data[0].br_suprtlcust_fixed;
                document.querySelector("#suppSysCharge").value = data[0].br_supsys_rate;
                document.querySelector("#meterFixCharge").value = data[0].br_mtrrtlcust_fixed;
                document.querySelector("#meterSysCharge").value = data[0].br_mtrsys_rate;
                document.querySelector("#lifelineDiscSub").value = data[0].br_lfln_subs_rate;
                document.querySelector("#srCitDiscSub").value = data[0].br_sc_subs_rate;
                document.querySelector("#intClassCrossSub").value = data[0].br_intrclscrssubrte;
                document.querySelector("#MCC").value = data[0].br_capex_rate;
                document.querySelector("#loanCondo").value = data[0].br_loancon_rate_kwh;
                document.querySelector("#loanCondoFix").value = data[0].br_loancon_rate_fix;
                document.querySelector("#misElectSPUG").value = data[0].br_uc4_miss_rate_spu;
                document.querySelector("#misElectRED").value = data[0].br_uc4_miss_rate_red;
                document.querySelector("#envCharge").value = data[0].br_uc6_envi_rate;
                document.querySelector("#eqTaxRoyalty").value = data[0].br_uc5_equal_rate;
                document.querySelector("#NPCStranCons").value = data[0].br_uc2_npccon_rate;
                document.querySelector("#NPCStranDebt").value = data[0].br_uc1_npcdebt_rate;
                document.querySelector("#FITALL").value = data[0].br_fit_all;
                document.querySelector("#genVAT").value = data[0].br_vat_gen;
                document.querySelector("#powerActRedVAT").value = data[0].br_vat_par;
                document.querySelector("#tranSysVAT").value = data[0].br_vat_trans;
                document.querySelector("#transDemVAT").value = data[0].br_vat_transdem;
                document.querySelector("#sysLossVAT").value = data[0].br_vat_systloss;
                document.querySelector("#distSysVAT").value = data[0].br_vat_distrib_kwh;
                document.querySelector("#distDemVAT").value = data[0].br_vat_distdem;
                document.querySelector("#suppSysVAT").value = data[0].br_vat_supsys;
                document.querySelector("#suppFixVAT").value = data[0].br_vat_supfix;
                document.querySelector("#meterFixVAT").value = data[0].br_vat_mtr_fix;
                document.querySelector("#meterSysVAT").value = data[0].br_vat_metersys;
                document.querySelector("#lflnDiscSubs").value = data[0].br_vat_lfln;
                document.querySelector("#loanCondoVAT").value = data[0].br_vat_loancondo;
                document.querySelector("#loanCondoFixVAT").value = data[0].br_vat_loancondofix;

                var totalDmdChrge = parseFloat(data[0].br_transdem_rate) + parseFloat(data[0].br_distdem_rate) + parseFloat(data[0].br_vat_transdem) + parseFloat(data[0].br_vat_distdem);
                var totalFixChrge = parseFloat(data[0].br_suprtlcust_fixed) + parseFloat(data[0].br_mtrrtlcust_fixed) + parseFloat(data[0].br_vat_mtr_fix);
                var totalKWHchrge = parseFloat(data[0].br_gensys_rate) + parseFloat(data[0].br_par_rate) + parseFloat(data[0].br_fbhc_rate) + parseFloat(data[0].br_forex_rate);
                totalKWHchrge += parseFloat(data[0].br_transsys_rate) + parseFloat(data[0].br_sysloss_rate) + parseFloat(data[0].br_distsys_rate) + parseFloat(data[0].br_supsys_rate);
                totalKWHchrge += parseFloat(data[0].br_mtrsys_rate) + parseFloat(data[0].br_lfln_subs_rate) + parseFloat(data[0].br_sc_subs_rate) + parseFloat(data[0].br_intrclscrssubrte);
                totalKWHchrge += parseFloat(data[0].br_capex_rate) + parseFloat(data[0].br_loancon_rate_kwh) + parseFloat(data[0].br_loancon_rate_fix) + parseFloat(data[0].br_uc4_miss_rate_spu);
                totalKWHchrge += parseFloat(data[0].br_uc4_miss_rate_red) + parseFloat(data[0].br_uc6_envi_rate) + parseFloat(data[0].br_uc5_equal_rate) + parseFloat(data[0].br_uc2_npccon_rate);
                totalKWHchrge += parseFloat(data[0].br_uc1_npcdebt_rate) + parseFloat(data[0].br_fit_all) + parseFloat(data[0].br_vat_gen) + parseFloat(data[0].br_vat_par) + parseFloat(data[0].br_vat_trans);
                totalKWHchrge += parseFloat(data[0].br_vat_systloss) + parseFloat(data[0].br_vat_distrib_kwh) + parseFloat(data[0].br_vat_supsys) + parseFloat(data[0].br_vat_metersys);
                totalKWHchrge += parseFloat(data[0].br_vat_lfln) + parseFloat(data[0].br_vat_loancondo) + parseFloat(data[0].br_vat_loancondofix);
               
                document.querySelector("#totalKWHchrge").innerHTML = parseFloat(totalKWHchrge).toFixed(2);
                document.querySelector("#totalDmdChrge").innerHTML = parseFloat(totalDmdChrge).toFixed(2);
                document.querySelector("#totalFixChrge").innerHTML = parseFloat(totalFixChrge).toFixed(2);

                document.querySelector("#billRatesButton").innerHTML = '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';

                checkInputs();
                calculateRatesPrev();
            }
        }
    }

    function addBillRates(x){
        var billDate = x.getAttribute("billPeriod");
        var consType = x.getAttribute("consType");
        var consCode = x.getAttribute("consCode");
        var consumerType = x.getAttribute("consumerType");

		var billPeriod = JSON.stringify(billDate);
        var date = billPeriod.slice(1, 5) + billPeriod.slice(6,8) - 1;
        var viewRates = "{{route('get.rate', ['consType'=>':consType', 'billPeriod'=>':billPeriod'])}}";
        var newViewRates = viewRates.replace(':consType', consType);
        var newViewRates2 = newViewRates.replace(':billPeriod', date);

        xhr.open('GET', newViewRates2, true);

        xhr.send();

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);

                if(data[0].cons_type_id <= 8){
                    document.querySelector("#newGenSysCharge").value = data[0].br_gensys_rate;
                    document.querySelector("#newPowerActReduct").value = data[0].br_par_rate;
                    document.querySelector("#newFBH").value = data[0].br_fbhc_rate;
                    document.querySelector("#newFAC").value = data[0].br_forex_rate;
                    document.querySelector("#newTranSysCharge").value = data[0].br_transsys_rate;
                    document.querySelector("#newTransDemCharge").disabled = true;
                    document.querySelector("#newSysLossCharge").value = data[0].br_sysloss_rate;
                    document.querySelector("#newDistSysCharge").value = data[0].r_distsys_rate;
                    document.querySelector("#newDistDemCharge").disabled = true;
                    document.querySelector("#newSuppFixCharge").value = data[0].br_suprtlcust_fixed;
                    document.querySelector("#newSuppSysCharge").value = data[0].br_supsys_rate;
                    document.querySelector("#newMeterFixCharge").value = data[0].br_mtrrtlcust_fixed;
                    document.querySelector("#newMeterSysCharge").value = data[0].br_mtrsys_rate;
                    document.querySelector("#newLifelineDiscSub").value = data[0].br_lfln_subs_rate;
                    document.querySelector("#newSrCitDiscSub").value = data[0].br_sc_subs_rate;
                    document.querySelector("#newIntClassCrossSub").value = data[0].br_intrclscrssubrte;
                    document.querySelector("#newMCC").value = data[0].br_capex_rate;
                    document.querySelector("#newLoanCondo").value = data[0].br_loancon_rate_kwh;
                    document.querySelector("#newLoanCondoFix").value = data[0].br_loancon_rate_fix;
                    document.querySelector("#newMisElectSPUG").value = data[0].br_uc4_miss_rate_spu;
                    document.querySelector("#newMisElectRED").value = data[0].br_uc4_miss_rate_red;
                    document.querySelector("#newEnvCharge").value = data[0].br_uc6_envi_rate;
                    document.querySelector("#newEqTaxRoyalty").value = data[0].br_uc5_equal_rate;
                    document.querySelector("#newNPCStranCons").value = data[0].br_uc2_npccon_rate;
                    document.querySelector("#newNPCStranDebt").value = data[0].br_uc1_npcdebt_rate;
                    document.querySelector("#newFITALL").value = data[0].br_fit_all;
                    document.querySelector("#newGenVAT").value = data[0].br_vat_gen;
                    document.querySelector("#newPowerActRedVAT").value = data[0].br_vat_par;
                    document.querySelector("#newTranSysVAT").value = data[0].br_vat_trans;
                    document.querySelector("#newTransDemVAT").disabled = true;
                    document.querySelector("#newSysLossVAT").value = data[0].br_vat_systloss;
                    document.querySelector("#newDistSysVAT").value = data[0].br_vat_distrib_kwh;
                    document.querySelector("#newDistDemVAT").disabled = true;
                    document.querySelector("#newSuppSysVAT").value = data[0].br_vat_supsys;
                    document.querySelector("#newSuppFixVAT").value = data[0].br_vat_supfix;
                    document.querySelector("#newMeterFixVAT").value = data[0].br_vat_mtr_fix;
                    document.querySelector("#newMeterSysVAT").value = data[0].br_vat_metersys;
                    document.querySelector("#newLflnDiscSubs").value = data[0].br_vat_lfln;
                    document.querySelector("#newLoanCondoVAT").value = data[0].br_vat_loancondo;
                    document.querySelector("#newLoanCondoFixVAT").value = data[0].br_vat_loancondofix;
                    document.querySelector("#newOtherVAT").value = data[0].Other_Vat;
                } else {
                    document.querySelector("#newPowerActReduct").value = data[0].br_par_rate;
                    document.querySelector("#newFBH").value = data[0].br_fbhc_rate;
                    document.querySelector("#newFAC").value = data[0].br_forex_rate;
                    document.querySelector("#newTranSysCharge").value = data[0].br_transsys_rate;
                    document.querySelector("#newTransDemCharge").value = data[0].br_transdem_rate;
                    document.querySelector("#newTransDemCharge").disabled = false;
                    document.querySelector("#newSysLossCharge").value = data[0].br_sysloss_rate;
                    document.querySelector("#newDistSysCharge").value = data[0].r_distsys_rate;
                    document.querySelector("#newDistDemCharge").value = data[0].br_distdem_rate;
                    document.querySelector("#newDistDemCharge").disabled = false;
                    document.querySelector("#newSuppFixCharge").value = data[0].br_suprtlcust_fixed;
                    document.querySelector("#newSuppSysCharge").value = data[0].br_supsys_rate;
                    document.querySelector("#newMeterFixCharge").value = data[0].br_mtrrtlcust_fixed;
                    document.querySelector("#newMeterSysCharge").value = data[0].br_mtrsys_rate;
                    document.querySelector("#newLifelineDiscSub").value = data[0].br_lfln_subs_rate;
                    document.querySelector("#newSrCitDiscSub").value = data[0].br_sc_subs_rate;
                    document.querySelector("#newIntClassCrossSub").value = data[0].br_intrclscrssubrte;
                    document.querySelector("#newMCC").value = data[0].br_capex_rate;
                    document.querySelector("#newLoanCondo").value = data[0].br_loancon_rate_kwh;
                    document.querySelector("#newLoanCondoFix").value = data[0].br_loancon_rate_fix;
                    document.querySelector("#newMisElectSPUG").value = data[0].br_uc4_miss_rate_spu;
                    document.querySelector("#newMisElectRED").value = data[0].br_uc4_miss_rate_red;
                    document.querySelector("#newEnvCharge").value = data[0].br_uc6_envi_rate;
                    document.querySelector("#newEqTaxRoyalty").value = data[0].br_uc5_equal_rate;
                    document.querySelector("#newNPCStranCons").value = data[0].br_uc2_npccon_rate;
                    document.querySelector("#newNPCStranDebt").value = data[0].br_uc1_npcdebt_rate;
                    document.querySelector("#newFITALL").value = data[0].br_fit_all;
                    document.querySelector("#newGenVAT").value = data[0].br_vat_gen;
                    document.querySelector("#newPowerActRedVAT").value = data[0].br_vat_par;
                    document.querySelector("#newTranSysVAT").value = data[0].br_vat_trans;
                    document.querySelector("#newTransDemVAT").value = data[0].br_vat_transdem;
                    document.querySelector("#newTransDemVAT").disabled = false;
                    document.querySelector("#newSysLossVAT").value = data[0].br_vat_systloss;
                    document.querySelector("#newDistSysVAT").value = data[0].br_vat_distrib_kwh;
                    document.querySelector("#newDistDemVAT").value = data[0].br_vat_distdem;
                    document.querySelector("#newDistDemVAT").disabled = false;
                    document.querySelector("#newSuppSysVAT").value = data[0].br_vat_supsys;
                    document.querySelector("#newSuppFixVAT").value = data[0].br_vat_supfix;
                    document.querySelector("#newMeterFixVAT").value = data[0].br_vat_mtr_fix;
                    document.querySelector("#newMeterSysVAT").value = data[0].br_vat_metersys;
                    document.querySelector("#newLflnDiscSubs").value = data[0].br_vat_lfln;
                    document.querySelector("#newLoanCondoVAT").value = data[0].br_vat_loancondo;
                    document.querySelector("#newLoanCondoFixVAT").value = data[0].br_vat_loancondofix;
                    document.querySelector("#newOtherVAT").value = data[0].Other_Vat;
                }
            }
        }
		
        document.querySelector("#addBillPeriod").value = billDate;
        document.querySelector("#addConsType").value = consType;

        console.log(consCode);

        if(consCode == "PUB2" || consCode == "COM2") {
            document.querySelector("#addBillRatesConsType").innerHTML = consumerType + " w/ kW Demand";   
        } else {
            document.querySelector("#addBillRatesConsType").innerHTML = consumerType;
        }
        
        calculateRates();
        //nullBillRates();
    }

    function addRates(){
        document.querySelector('.addBtn').disabled = true;
        var xhr = new XMLHttpRequest();
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var addRates = "{{route('store.billrates')}}";
        xhr.open("POST", addRates, true);
		xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
		xhr.setRequestHeader("X-CSRF-TOKEN", token);
        var billPeriod = document.querySelector("#addBillPeriod").value;
        var consId = document.querySelector("#addConsType").value;
        var monthstring = JSON.stringify(billPeriod);
        var billMonth = monthstring.slice(1,5);
        billMonth +=  monthstring.slice(6,8);
        
        const toSend = {
            user_id: userId,
            cons_type_id: consId,
            br_billing_ym: parseInt(billMonth),
            br_gensys_rate: document.querySelector("#newGenSysCharge").value, 
            br_fbhc_rate: document.querySelector("#newFBH").value, 
            br_forex_rate: document.querySelector("#newFAC").value, 
            br_icera_rate: 0, 
            br_gram_rate: 0,
            br_par_rate: document.querySelector("#newPowerActReduct").value,
            br_transdem_rate: document.querySelector("#newTransDemCharge").value,
            br_transsys_rate: document.querySelector("#newTranSysCharge").value,
            br_sysloss_rate: document.querySelector("#newSysLossCharge").value, 
            br_distdem_rate: document.querySelector("#newDistDemCharge").value, 
            br_distsys_rate: document.querySelector("#newDistSysCharge").value, 
            br_suprtlcust_fixed: document.querySelector("#newSuppFixCharge").value,
            br_supsys_rate: document.querySelector("#newSuppSysCharge").value, 
            br_mtrrtlcust_fixed: document.querySelector("#newMeterFixCharge").value,
            br_mtrsys_rate: document.querySelector("#newMeterSysCharge").value, 
            br_loancon_rate_kwh: document.querySelector("#newLoanCondo").value,
            br_loancon_rate_fix: document.querySelector("#newLoanCondoFix").value,
            br_lfln_subs_rate: document.querySelector("#newLifelineDiscSub").value,
            br_sc_subs_rate: document.querySelector("#newSrCitDiscSub").value,
            br_uc1_npcdebt_rate: document.querySelector("#newNPCStranDebt").value, 
            br_uc2_npccon_rate: document.querySelector("#newNPCStranCons").value, 
            br_uc3_duscon_rate: 0, 
            br_uc4_miss_rate_spu: document.querySelector("#newMisElectSPUG").value, 
            br_uc5_equal_rate: document.querySelector("#newEqTaxRoyalty").value, 
            br_uc6_envi_rate: document.querySelector("#newEnvCharge").value, 
            br_uc7_crssubremrate: 0,
            br_intrclscrssubrte: document.querySelector("#newIntClassCrossSub").value,
            br_ppa_refund_rate: 0, 
            br_ppa_reco_rate: 0,
            br_patronage_refund: 0,
            br_capex_rate: document.querySelector("#newMCC").value, 
            br_mcrpt: 0, 
            br_adj_label: 0,
            br_adj_fixed: 0,
            br_adj_period_backbi: 0, 
            br_adj_rate: 0, 
            br_iccs_adj: 0, 
            br_ppd_adj: 0, 
            br_fit_all: document.querySelector("#newFITALL").value, 
            br_uc4_miss_rate_red: document.querySelector("#newMisElectRED").value,
            br_min_lfln_kwh: 0, 
            br_backbill: 0, 
            br_penalty_perc_rate: 0,
            br_5_percent: 0, 
            br_2_percent: 0, 
            br_tax_franchise: 0, 
            br_tax_local: 0, 
            br_tax_business: 0, 
            br_vat_gen: document.querySelector("#newGenVAT").value, 
            br_vat_trans: document.querySelector("#newTranSysVAT").value, 
            br_vat_systloss: document.querySelector("#newSysLossVAT").value, 
            br_vat_distrib_kwh: document.querySelector("#newDistSysVAT").value, 
            br_vat_distrib_fixed: 0,
            br_vat_other_bill: 0, 
            br_vat_other_nb: 0, 
            br_mcc_vat: 0, 
            br_lower_under_rdng: 0, 
            br_gmcp_gen: 0, 
            br_pemc_wesm: 0, 
            br_ngcp_pei: 0, 
            br_ngcp_phinma_pec: 0,
            br_ngcp_tli: 0, 
            br_ngcp_tapgc: 0, 
            br_luelco_distr: 0,
            br_ngcp_cip2: 0, 
            br_vat_transdem: document.querySelector("#newTransDemVAT").value,  
            br_vat_distdem: document.querySelector("#newDistDemVAT").value,  
            br_vat_loancondo: document.querySelector("#newLoanCondoVAT").value, 
            br_vat_loancondofix: document.querySelector("#newLoanCondoFixVAT").value, 
            br_vat_metersys: document.querySelector("#newMeterSysVAT").value, 
            br_vat_supsys: document.querySelector("#newSuppSysVAT").value,
            br_vat_supfix: document.querySelector("#newSuppFixVAT").value,
            br_vat_mtr_fix: document.querySelector("#newMeterFixVAT").value,
            br_vat_par: document.querySelector("#newPowerActRedVAT").value,
            br_vat_lfln: document.querySelector("#newLflnDiscSubs").value,
            Other_Vat: document.querySelector("#newOtherVAT").value,
            br_energy_chrg: parseFloat(document.querySelector("#addTotalKWHchrge").innerHTML),
            br_ttl_dmd_chrg: parseFloat(document.querySelector("#addTotalDmdChrge").innerHTML),
            br_ttl_fix_chrg: parseFloat(document.querySelector("#addTotalFixChrge").innerHTML)
        }
		console.log(toSend);
        const toSendJSONed = JSON.stringify(toSend);
		xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(this.status == 201) {
                document.querySelector("#addBillRates").style.display = "none";
                Swal.fire({
                    title: 'Success!',
                    text: '"Successfully Added Rates"',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                })
                document.querySelector('.addBtn').disabled = false;
                var billRates = document.querySelector("#addBillRates");
                var inputs = billRates.getElementsByTagName('input');

                for(var i=0; i<inputs.length; i++){
                    inputs[i].value = "";
                }
                showRates();
            }
        }
    }

    function checkInputs(){
        var billRates = document.querySelector("#billRates");
        var inputs = billRates.getElementsByTagName('input');

        for(var i=0; i<inputs.length; i++){
            if(inputs[i].value == ""){
                inputs[i].value = "0.0000";
            }
        }
    }

    function listenEditInput(){
        var billRates = document.querySelector("#billRates");
        var inputs = billRates.getElementsByTagName('input');

        for(var i=0; i<inputs.length; i++){
            var input = inputs[i];
            input.onkeyup = function(){
                var output = '<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="updateRates()"> Save </button>';
                document.querySelector("#billRatesButton").innerHTML = output;
                document.querySelector('.printBtn').style.display = "none";
            }
            inputs[i].addEventListener("keyup", calculateRatesPrev);
        }
        document.querySelector('.printBtn').style.display = "none";
    }

    function updateRates(){
        var billId = document.querySelector("#billId").value;
        var billDate = document.querySelector("#billingPeriod").value;
        var monthstring = JSON.stringify(billDate);
        var billMonth = monthstring.slice(1,5);
        billMonth +=  monthstring.slice(6,8);

        var xhr = new XMLHttpRequest();
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var updateRates = "{{route('update.billrates', ['id'=>':id'])}}";
        var newUpdateRates = updateRates.replace(':id', billId);
        xhr.open("PATCH", newUpdateRates, true);
		xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
		xhr.setRequestHeader("X-CSRF-TOKEN", token);

        const toSend = {
            'br_gensys_rate': document.querySelector("#genSysCharge").value,
            'br_billing_ym': billMonth,
            'br_par_rate': document.querySelector("#powerActReduct").value,
            'br_fbhc_rate': document.querySelector("#FBH").value,
            'br_forex_rate': document.querySelector("#FAC").value,
            'br_transsys_rate': document.querySelector("#tranSysCharge").value,
            'br_transdem_rate': document.querySelector("#transDemCharge").value,
            'br_sysloss_rate': document.querySelector("#sysLossCharge").value,
            'br_distsys_rate': document.querySelector("#distSysCharge").value,
            'br_distdem_rate': document.querySelector("#distDemCharge").value,
            'br_suprtlcust_fixed': document.querySelector("#suppFixCharge").value,
            'br_supsys_rate': document.querySelector("#suppSysCharge").value,
            'br_mtrrtlcust_fixed': document.querySelector("#meterFixCharge").value,
            'br_mtrsys_rate': document.querySelector("#meterSysCharge").value,
            'br_lfln_subs_rate': document.querySelector("#lifelineDiscSub").value,
            'br_sc_subs_rate': document.querySelector("#srCitDiscSub").value,
            'br_intrclscrssubrte': document.querySelector("#intClassCrossSub").value,
            'br_capex_rate': document.querySelector("#MCC").value,
            'br_loancon_rate_kwh': document.querySelector("#loanCondo").value,
            'br_loancon_rate_fix': document.querySelector("#loanCondoFix").value,
            'br_uc4_miss_rate_spu': document.querySelector("#misElectSPUG").value,
            'br_uc4_miss_rate_red': document.querySelector("#misElectRED").value,
            'br_uc6_envi_rate': document.querySelector("#envCharge").value,
            'br_uc5_equal_rate': document.querySelector("#eqTaxRoyalty").value,
            'br_uc2_npccon_rate': document.querySelector("#NPCStranCons").value,
            'br_uc1_npcdebt_rate': document.querySelector("#NPCStranDebt").value,
            'br_fit_all': document.querySelector("#FITALL").value,
            'br_vat_gen': document.querySelector("#genVAT").value,
            'br_vat_par': document.querySelector("#powerActRedVAT").value,
            'br_vat_trans': document.querySelector("#tranSysVAT").value,
            'br_vat_transdem': document.querySelector("#transDemVAT").value,
            'br_vat_systloss': document.querySelector("#sysLossVAT").value,
            'br_vat_distrib_kwh': document.querySelector("#distSysVAT").value,
            'br_vat_distdem': document.querySelector("#distDemVAT").value,
            'br_vat_supsys': document.querySelector("#suppSysVAT").value,
            'br_vat_supfix': document.querySelector("#suppFixVAT").value,
            'br_vat_mtr_fix': document.querySelector("#meterFixVAT").value,
            'br_vat_metersys': document.querySelector("#meterSysVAT").value,
            'br_vat_lfln': document.querySelector("#lflnDiscSubs").value,
            'br_vat_loancondo': document.querySelector("#loanCondoVAT").value,
            'br_vat_loancondofix': document.querySelector("#loanCondoFixVAT").value,
            'br_energy_chrg': document.querySelector("#totalKWHchrge").innerHTML,
            'br_ttl_dmd_chrg': document.querySelector("#totalDmdChrge").innerHTML,
            'br_ttl_fix_chrg': document.querySelector("#totalFixChrge").innerHTML
        }

        const toSendJSONed = JSON.stringify(toSend);
		xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(this.status == 200) {
                Swal.fire({
                    title: 'Success!',
                    text: '"Successfully Modified Rates"',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                })
                document.querySelector("#billRates").style.display = "none";
                showRates();
            }
        }
    }

    function calculateRates(){
        var billRates = document.querySelector("#addBillRates");
        var inputs = billRates.getElementsByTagName('input');

        for(var i=0; i<inputs.length; i++){
            inputs[i].addEventListener("keyup", function(){
                var br_gensys_rate = ""; 
                var br_par_rate = "";
                var br_fbhc_rate = "";
                var br_forex_rate = "";
                var br_transsys_rate = "";
                var br_transdem_rate = "";
                var br_sysloss_rate = "";
                var br_distsys_rate = "";
                var br_distdem_rate = "";
                var br_suprtlcust_fixed = "";
                var br_supsys_rate = "";
                var br_mtrrtlcust_fixed = "";
                var br_mtrsys_rate = "";
                var br_lfln_subs_rate = "";
                var br_sc_subs_rate = "";
                var br_intrclscrssubrte = "";
                var br_capex_rate = "";
                var br_loancon_rate_kwh = "";
                var br_loancon_rate_fix = "";
                var br_uc4_miss_rate_spu = "";
                var br_uc4_miss_rate_red = "";
                var br_uc6_envi_rate = "";
                var br_uc5_equal_rate = "";
                var br_uc2_npccon_rate = "";
                var br_uc1_npcdebt_rate = "";
                var br_fit_all = "";
                var br_vat_gen = "";
                var br_vat_par = "";
                var br_vat_trans = "";
                var br_vat_transdem = "";
                var br_vat_systloss = "";
                var br_vat_distrib_kwh = "";
                var br_vat_distdem = "";
                var br_vat_supsys = "";
                var br_vat_supfix = "";
                var br_vat_mtr_fix = "";
                var br_vat_metersys = "";
                var br_vat_lfln = "";
                var br_vat_loancondo = "";
                var br_vat_loancondofix = "";
                var Other_Vat = "";

                if(document.querySelector("#newGenSysCharge").value == ""){
                    br_gensys_rate += "0.0000";    
                } else {
                    br_gensys_rate += document.querySelector("#newGenSysCharge").value;
                }
                if(document.querySelector("#newPowerActReduct").value == ""){
                    br_par_rate += "0.0000";     
                } else {
                    br_par_rate += document.querySelector("#newPowerActReduct").value;
                }
                if(document.querySelector("#newFBH").value == ""){
                    br_fbhc_rate += "0.0000";    
                } else {
                    br_fbhc_rate += document.querySelector("#newFBH").value;
                }
                if(document.querySelector("#newFAC").value == ""){
                    br_forex_rate += "0.0000";    
                } else {
                    br_forex_rate += document.querySelector("#newFAC").value;
                }
                if(document.querySelector("#newTranSysCharge").value == ""){
                    br_transsys_rate += "0.0000";    
                } else {
                    br_transsys_rate += document.querySelector("#newTranSysCharge").value;
                }
                if(document.querySelector("#newTransDemCharge").value == ""){
                    br_transdem_rate += "0.0000";    
                } else {
                    br_transdem_rate += document.querySelector("#newTransDemCharge").value;
                }
                if(document.querySelector("#newSysLossCharge").value == ""){
                    br_sysloss_rate += "0.0000";    
                } else {
                    br_sysloss_rate += document.querySelector("#newSysLossCharge").value;
                }
                if(document.querySelector("#newDistSysCharge").value == ""){
                    br_distsys_rate += "0.0000";    
                } else {
                    br_distsys_rate += document.querySelector("#newDistSysCharge").value;
                }
                if(document.querySelector("#newDistDemCharge").value == ""){
                    br_distdem_rate += "0.0000";    
                } else {
                    br_distdem_rate += document.querySelector("#newDistDemCharge").value;    
                }
                if(document.querySelector("#newSuppFixCharge").value == ""){
                    br_suprtlcust_fixed += "0.0000";    
                } else {
                    br_suprtlcust_fixed += document.querySelector("#newSuppFixCharge").value;    
                }
                if(document.querySelector("#newSuppSysCharge").value == ""){
                    br_supsys_rate += "0.0000";    
                } else {
                    br_supsys_rate += document.querySelector("#newSuppSysCharge").value;
                }
                if(document.querySelector("#newMeterFixCharge").value == ""){
                    br_mtrrtlcust_fixed += "0.0000";
                } else {
                    br_mtrrtlcust_fixed += document.querySelector("#newMeterFixCharge").value;
                }
                if(document.querySelector("#newMeterSysCharge").value == ""){
                    br_mtrsys_rate += "0.0000";    
                } else {
                    br_mtrsys_rate += document.querySelector("#newMeterSysCharge").value;    
                }
                if(document.querySelector("#newLifelineDiscSub").value == ""){
                    br_lfln_subs_rate += "0.0000";    
                } else {
                    br_lfln_subs_rate += document.querySelector("#newLifelineDiscSub").value;
                }
                if(document.querySelector("#newSrCitDiscSub").value == ""){
                    br_sc_subs_rate += "0.0000";    
                } else {
                    br_sc_subs_rate += document.querySelector("#newSrCitDiscSub").value;    
                }
                if(document.querySelector("#newIntClassCrossSub").value == ""){
                    br_intrclscrssubrte += "0.0000";    
                } else {
                    br_intrclscrssubrte += document.querySelector("#newIntClassCrossSub").value;
                }
                if(document.querySelector("#newMCC").value == ""){
                    br_capex_rate += "0.0000";    
                } else {
                    br_capex_rate += document.querySelector("#newMCC").value;
                }
                if(document.querySelector("#newLoanCondo").value == ""){
                    br_loancon_rate_kwh += "0.0000";    
                } else {
                    br_loancon_rate_kwh += document.querySelector("#newLoanCondo").value;
                }
                if(document.querySelector("#newLoanCondoFix").value == ""){
                    br_loancon_rate_fix +=  "0.0000";    
                } else {
                    br_loancon_rate_fix += document.querySelector("#newLoanCondoFix").value;
                }
                if(document.querySelector("#newMisElectSPUG").value == ""){
                    br_uc4_miss_rate_spu += "0.0000";    
                } else {
                    br_uc4_miss_rate_spu += document.querySelector("#newMisElectSPUG").value;    
                }
                if(document.querySelector("#newMisElectRED").value == ""){
                    br_uc4_miss_rate_red += "0.0000";    
                } else {
                    br_uc4_miss_rate_red += document.querySelector("#newMisElectRED").value;
                }
                if(document.querySelector("#newEnvCharge").value == ""){
                    br_uc6_envi_rate +=  "0.0000";    
                } else {
                    br_uc6_envi_rate += document.querySelector("#newEnvCharge").value;    
                }
                if(document.querySelector("#newEqTaxRoyalty").value == ""){
                    br_uc5_equal_rate += "0.0000";    
                } else {
                    br_uc5_equal_rate += document.querySelector("#newEqTaxRoyalty").value;
                }
                if(document.querySelector("#newNPCStranCons").value == ""){
                    br_uc2_npccon_rate += "0.0000";    
                } else {
                    br_uc2_npccon_rate += document.querySelector("#newNPCStranCons").value;
                }
                if(document.querySelector("#newNPCStranDebt").value == ""){
                    br_uc1_npcdebt_rate += "0.0000";    
                } else {
                    br_uc1_npcdebt_rate += document.querySelector("#newNPCStranDebt").value;
                }
                if(document.querySelector("#newFITALL").value == ""){
                    br_fit_all += "0.0000";    
                } else {
                    br_fit_all += document.querySelector("#newFITALL").value;
                }
                if(document.querySelector("#newGenVAT").value == ""){
                    br_vat_gen += "0.0000";    
                } else {
                    br_vat_gen += document.querySelector("#newGenVAT").value;
                }
                if(document.querySelector("#newPowerActRedVAT").value == ""){
                    br_vat_par += "0.0000";    
                } else {
                    br_vat_par += document.querySelector("#newPowerActRedVAT").value;
                }
                if(document.querySelector("#newTranSysVAT").value == ""){
                    br_vat_trans += "0.0000";    
                } else {
                    br_vat_trans += document.querySelector("#newTranSysVAT").value;
                }
                if(document.querySelector("#newTransDemVAT").value == ""){
                    br_vat_transdem += "0.0000";    
                } else {
                    br_vat_transdem += document.querySelector("#newTransDemVAT").value;
                }
                if(document.querySelector("#newSysLossVAT").value == ""){
                    br_vat_systloss += "0.0000";    
                } else {
                    br_vat_systloss += document.querySelector("#newSysLossVAT").value;
                }
                if(document.querySelector("#newDistSysVAT").value == ""){
                    br_vat_distrib_kwh += "0.0000";    
                } else {
                    br_vat_distrib_kwh += document.querySelector("#newDistSysVAT").value;
                }
                if(document.querySelector("#newDistDemVAT").value == ""){
                    br_vat_distdem += "0.0000";    
                } else {
                    br_vat_distdem += document.querySelector("#newDistDemVAT").value;
                }
                if(document.querySelector("#newSuppSysVAT").value == ""){
                    br_vat_supsys += "0.0000";    
                } else {
                    br_vat_supsys += document.querySelector("#newSuppSysVAT").value;
                }
                if(document.querySelector("#newSuppFixVAT").value == ""){
                    br_vat_supfix += "0.0000";    
                } else {
                    br_vat_supfix += document.querySelector("#newSuppFixVAT").value;
                }
                if(document.querySelector("#newMeterFixVAT").value == ""){
                    br_vat_mtr_fix += "0.0000";    
                } else {
                    br_vat_mtr_fix += document.querySelector("#newMeterFixVAT").value;
                }
                if(document.querySelector("#newMeterSysVAT").value == ""){
                    br_vat_metersys += "0.0000";    
                } else {
                    br_vat_metersys += document.querySelector("#newMeterSysVAT").value;    
                }
                if(document.querySelector("#newLflnDiscSubs").value == ""){
                    br_vat_lfln += "0.0000";    
                } else {
                    br_vat_lfln += document.querySelector("#newLflnDiscSubs").value;
                }
                if(document.querySelector("#newLoanCondoVAT").value == ""){
                    br_vat_loancondo += "0.0000";     
                } else {
                    br_vat_loancondo += document.querySelector("#newLoanCondoVAT").value;
                }
                if(document.querySelector("#newLoanCondoFixVAT").value == ""){
                    br_vat_loancondofix += "0.0000";    
                } else {
                    br_vat_loancondofix += document.querySelector("#newLoanCondoFixVAT").value;
                }
                if(document.querySelector("#newOtherVAT").value == ""){
                    Other_Vat += "0.0000";    
                } else {
                    Other_Vat += document.querySelector("#newOtherVAT").value;
                }
                

                var genCharge = parseFloat(br_gensys_rate) + parseFloat(br_par_rate) + parseFloat(br_fbhc_rate) + parseFloat(br_forex_rate);
                var transCharge = parseFloat(br_transsys_rate) + parseFloat(br_transdem_rate) + parseFloat(br_sysloss_rate);
                var distCharge = parseFloat(br_distsys_rate) + parseFloat(br_distdem_rate) + parseFloat(br_suprtlcust_fixed);
                distCharge += parseFloat(br_supsys_rate) + parseFloat(br_mtrrtlcust_fixed) + parseFloat(br_mtrsys_rate);
                var otherCharge = parseFloat(br_lfln_subs_rate) + parseFloat(br_sc_subs_rate);
                otherCharge += parseFloat(br_intrclscrssubrte) + parseFloat(br_capex_rate);
                otherCharge += parseFloat(br_loancon_rate_kwh) + parseFloat(br_loancon_rate_fix);
                var govCharge = parseFloat(br_uc4_miss_rate_spu) + parseFloat(br_uc4_miss_rate_red);
                govCharge += parseFloat(br_uc6_envi_rate) + parseFloat(br_uc5_equal_rate);
                govCharge += parseFloat(br_uc2_npccon_rate) + parseFloat(br_uc1_npcdebt_rate);
                govCharge += parseFloat(br_fit_all);
                var VAT = parseFloat(br_vat_gen) + parseFloat(br_vat_par) + parseFloat(br_vat_trans);
                VAT += parseFloat(br_vat_transdem) + parseFloat(br_vat_systloss) + parseFloat(br_vat_distrib_kwh);
                VAT += parseFloat(br_vat_distdem) + parseFloat(br_vat_supsys) + parseFloat(br_vat_mtr_fix);
                VAT += parseFloat(br_vat_metersys) + parseFloat(br_vat_lfln) + parseFloat(br_vat_loancondo);
                VAT += parseFloat(br_vat_loancondofix) + parseFloat(Other_Vat) + parseFloat(br_vat_supfix);
                
                document.querySelector("#genChargeTotal").value = genCharge.toFixed(4);
                document.querySelector("#transChargeTotal").value = transCharge.toFixed(4);
                document.querySelector("#distCharge").value = distCharge.toFixed(4);
                document.querySelector("#otherCharge").value = otherCharge.toFixed(4);
                document.querySelector("#govCharge").value = govCharge.toFixed(4);
                document.querySelector("#VAT").value = VAT.toFixed(4);

                var gen = 0;
                var transgen = 0;
                var discharge = 0;
                var otherC = 0;
                var bombit = 0;
                var valueAddedCharge = 0;
                
                gen += document.querySelector("#genChargeTotal").value;
                transgen += document.querySelector("#transChargeTotal").value;
                discharge += document.querySelector("#distCharge").value;
                otherC += document.querySelector("#otherCharge").value;
                bombit += document.querySelector("#govCharge").value;
                valueAddedCharge += document.querySelector("#VAT").value;

                var totalDmdChrge = parseFloat(br_transdem_rate) + parseFloat(br_distdem_rate) + parseFloat(br_vat_transdem) + parseFloat(br_vat_distdem);
                    
                var totalFixChrge = parseFloat(br_suprtlcust_fixed) + parseFloat(br_mtrrtlcust_fixed) + parseFloat(br_vat_mtr_fix) + parseFloat(br_vat_supfix);
                totalFixChrge += parseFloat(br_loancon_rate_fix) + parseFloat(br_vat_loancondofix);
                
                var totalKWHchrge = parseFloat(br_gensys_rate) + parseFloat(br_par_rate) + parseFloat(br_fbhc_rate) + parseFloat(br_forex_rate);
                totalKWHchrge += parseFloat(br_transsys_rate) + parseFloat(br_sysloss_rate) + parseFloat(br_distsys_rate) + parseFloat(br_supsys_rate);
                totalKWHchrge += parseFloat(br_mtrsys_rate) + parseFloat(br_lfln_subs_rate) + parseFloat(br_sc_subs_rate) + parseFloat(br_intrclscrssubrte);
                totalKWHchrge += parseFloat(br_capex_rate) + parseFloat(br_loancon_rate_kwh) + parseFloat(br_uc4_miss_rate_spu);
                totalKWHchrge += parseFloat(br_uc4_miss_rate_red) + parseFloat(br_uc6_envi_rate) + parseFloat(br_uc5_equal_rate) + parseFloat(br_uc2_npccon_rate);
                totalKWHchrge += parseFloat(br_uc1_npcdebt_rate) + parseFloat(br_fit_all) + parseFloat(br_vat_gen) + parseFloat(br_vat_par) + parseFloat(br_vat_trans);
                totalKWHchrge += parseFloat(br_vat_systloss) + parseFloat(br_vat_distrib_kwh) + parseFloat(br_vat_supsys) + parseFloat(br_vat_metersys);
                totalKWHchrge += parseFloat(br_vat_lfln) + parseFloat(br_vat_loancondo);

                document.querySelector("#addTotalKWHchrge").innerHTML = parseFloat(totalKWHchrge).toFixed(4);
                document.querySelector("#addTotalDmdChrge").innerHTML = parseFloat(totalDmdChrge).toFixed(4);
                document.querySelector("#addTotalFixChrge").innerHTML = parseFloat(totalFixChrge).toFixed(4);
            })
        }
    }

    function nullBillRates(){
        document.querySelector("#newGenSysCharge").value = "";
        document.querySelector("#newPowerActReduct").value = "";
        document.querySelector("#newFBH").value = "";
        document.querySelector("#newFAC").value = "";
        document.querySelector("#newTranSysCharge").value = "";
        document.querySelector("#newTransDemCharge").value = "";
        document.querySelector("#newSysLossCharge").value = "";
        document.querySelector("#newDistSysCharge").value = "";
        document.querySelector("#newDistDemCharge").value = "";
        document.querySelector("#newSuppFixCharge").value = "";
        document.querySelector("#newSuppSysCharge").value = "";
        document.querySelector("#newMeterFixCharge").value = "";
        document.querySelector("#newMeterSysCharge").value = "";
        document.querySelector("#newLifelineDiscSub").value = "";
        document.querySelector("#newSrCitDiscSub").value = "";
        document.querySelector("#newIntClassCrossSub").value = "";
        document.querySelector("#newMCC").value = "";
        document.querySelector("#newLoanCondo").value = "";
        document.querySelector("#newLoanCondoFix").value = "";
        document.querySelector("#newMisElectSPUG").value = "";
        document.querySelector("#newMisElectRED").value = "";
        document.querySelector("#newEnvCharge").value = "";
        document.querySelector("#newEqTaxRoyalty").value = "";
        document.querySelector("#newNPCStranCons").value = "";
        document.querySelector("#newNPCStranDebt").value = "";
        document.querySelector("#newFITALL").value = "";
        document.querySelector("#newGenVAT").value = "";
        document.querySelector("#newPowerActRedVAT").value = "";
        document.querySelector("#newTranSysVAT").value = "";
        document.querySelector("#newTransDemVAT").value = "";
        document.querySelector("#newSysLossVAT").value = "";
        document.querySelector("#newDistSysVAT").value = "";
        document.querySelector("#newDistDemVAT").value = "";
        document.querySelector("#newSuppSysVAT").value = "";
        document.querySelector("#newSuppFixVAT").value = "";
        document.querySelector("#newMeterFixVAT").value = "";
        document.querySelector("#newMeterSysVAT").value = "";
        document.querySelector("#newLflnDiscSubs").value = "";
        document.querySelector("#newLoanCondoVAT").value = "";
        document.querySelector("#newLoanCondoFixVAT").value = "";
        document.querySelector("#newOtherVAT").value = "";
        document.querySelector("#genChargeTotal").value = "";
        document.querySelector("#transChargeTotal").value = "";
        document.querySelector("#distCharge").value = "";
        document.querySelector("#otherCharge").value = "";
        document.querySelector("#govCharge").value = "";
        document.querySelector("#VAT").value = "";

        document.querySelector("#addTotalKWHchrge").innerHTML = "0.00";
        document.querySelector("#addTotalDmdChrge").innerHTML = "0.00";
        document.querySelector("#addTotalFixChrge").innerHTML = "0.00";
    }

    function calculateRatesPrev(){
        var br_gensys_rate = "";
        var br_par_rate = "";
        var br_fbhc_rate = "";
        var br_forex_rate = "";
        
        if(document.querySelector("#genSysCharge").value == ""){
            br_gensys_rate += "0.0000";    
        } else {
            br_gensys_rate += document.querySelector("#genSysCharge").value;
        }
        if(document.querySelector("#powerActReduct").value == ""){
            br_par_rate += "0.0000";    
        } else {
            br_par_rate += document.querySelector("#powerActReduct").value;
        }
        if(document.querySelector("#FBH").value == ""){
            br_fbhc_rate += "0.0000";    
        } else {
            br_fbhc_rate += document.querySelector("#FBH").value;    
        }
        if(document.querySelector("#FAC").value == ""){
            br_forex_rate += "0.0000";    
        } else {
            br_forex_rate += document.querySelector("#FAC").value;
        }
        
        
        var genCharge = parseFloat(br_gensys_rate) + parseFloat(br_par_rate) + parseFloat(br_fbhc_rate) + parseFloat(br_forex_rate);
        document.querySelector("#PrevGenChargeTotal").value = genCharge.toFixed(4);
        
        var br_transsys_rate = "";
        var br_transdem_rate = "";
        var br_sysloss_rate = "";    
    
        if(document.querySelector("#tranSysCharge").value == ""){
            br_transsys_rate += "0.0000";    
        } else {
            br_transsys_rate += document.querySelector("#tranSysCharge").value;
        }
        if(document.querySelector("#transDemCharge").value == ""){
            br_transdem_rate += "0.0000";    
        } else {
            br_transdem_rate += document.querySelector("#transDemCharge").value;
        }
        if(document.querySelector("#sysLossCharge").value == ""){
            br_sysloss_rate += "0.0000";    
        } else {
            br_sysloss_rate += document.querySelector("#sysLossCharge").value;   
        }
        
        
        var transCharge = parseFloat(br_transsys_rate) + parseFloat(br_transdem_rate) + parseFloat(br_sysloss_rate);
        document.querySelector("#PrevTransChargeTotal").value = transCharge.toFixed(4);
        
        var br_distsys_rate = "";
        var br_distdem_rate = "";
        var br_suprtlcust_fixed = "";
        var br_supsys_rate = "";
        var br_mtrrtlcust_fixed = "";
        var br_mtrsys_rate = "";
        
        if(document.querySelector("#distSysCharge").value == ""){
            br_distsys_rate += "0.0000";    
        } else {
            br_distsys_rate += document.querySelector("#distSysCharge").value;
        }
        if(document.querySelector("#distDemCharge").value == ""){
            br_distdem_rate += "0.0000";    
        } else {
            br_distdem_rate += document.querySelector("#distDemCharge").value;
        }
        if(document.querySelector("#suppFixCharge").value == ""){
            br_suprtlcust_fixed +=  "0.0000";    
        } else {
            br_suprtlcust_fixed += document.querySelector("#suppFixCharge").value;     
        }
        if(document.querySelector("#suppSysCharge").value == ""){
            br_supsys_rate += "0.0000";    
        } else {
            br_supsys_rate += document.querySelector("#suppSysCharge").value;
        }
        if(document.querySelector("#meterFixCharge").value == ""){
            br_mtrrtlcust_fixed += "0.0000";    
        } else {
            br_mtrrtlcust_fixed += document.querySelector("#meterFixCharge").value;
        }
        if(document.querySelector("#meterSysCharge").value == ""){
            br_mtrsys_rate += "0.0000";
        } else {
            br_mtrsys_rate += document.querySelector("#meterSysCharge").value;
        }
        

        var distCharge = parseFloat(br_distsys_rate) + parseFloat(br_distdem_rate) + parseFloat(br_suprtlcust_fixed);
        distCharge += parseFloat(br_supsys_rate) + parseFloat(br_mtrrtlcust_fixed) + parseFloat(br_mtrsys_rate);
        document.querySelector("#PrevDistCharge").value = distCharge.toFixed(4);

        var br_lfln_subs_rate = "";
        var br_sc_subs_rate = "";
        var br_intrclscrssubrte = "";
        var br_capex_rate = "";
        var br_loancon_rate_kwh = "";
        var br_loancon_rate_fix = "";
        
        if(document.querySelector("#lifelineDiscSub").value == ""){
            br_lfln_subs_rate += "0.0000";    
        } else {
            br_lfln_subs_rate += document.querySelector("#lifelineDiscSub").value;
        }
        if(document.querySelector("#srCitDiscSub").value == ""){
            br_sc_subs_rate += "0.0000";    
        } else {
            br_sc_subs_rate += document.querySelector("#srCitDiscSub").value;
        } 
        if(document.querySelector("#intClassCrossSub").value == ""){
            br_intrclscrssubrte += "0.0000";    
        } else {
            br_intrclscrssubrte += document.querySelector("#intClassCrossSub").value;
        }
        if(document.querySelector("#MCC").value == ""){
            br_capex_rate += "0.0000";    
        } else {
            br_capex_rate += document.querySelector("#MCC").value;
        }
        if(document.querySelector("#loanCondo").value == ""){
            br_loancon_rate_kwh += "0.0000";
        } else {
            br_loancon_rate_kwh += document.querySelector("#loanCondo").value;
        }
        if(document.querySelector("#loanCondoFix").value == ""){
            br_loancon_rate_fix += "0.0000";    
        } else {
            br_loancon_rate_fix += document.querySelector("#loanCondoFix").value;
        }
        
        
        var otherCharge = parseFloat(br_lfln_subs_rate) + parseFloat(br_sc_subs_rate);
        otherCharge += parseFloat(br_intrclscrssubrte) + parseFloat(br_capex_rate);
        otherCharge += parseFloat(br_loancon_rate_kwh) + parseFloat(br_loancon_rate_fix);
        document.querySelector("#PrevOtherCharge").value = otherCharge.toFixed(4);

        var br_uc4_miss_rate_spu = "";
        var br_uc4_miss_rate_red = "";
        var br_uc6_envi_rate = "";
        var br_uc5_equal_rate = "";
        var br_uc2_npccon_rate = "";
        var br_uc1_npcdebt_rate = "";
        var br_fit_all = "";
        
        if(document.querySelector("#misElectSPUG").value == ""){
            br_uc4_miss_rate_spu += "0.0000";    
        } else {
            br_uc4_miss_rate_spu += document.querySelector("#misElectSPUG").value;
        }
        if(document.querySelector("#misElectRED").value == ""){
            br_uc4_miss_rate_red += "0.0000";    
        } else {
            br_uc4_miss_rate_red += document.querySelector("#misElectRED").value;
        }
        if(document.querySelector("#envCharge").value == ""){
            br_uc6_envi_rate += "0.0000";    
        } else {
            br_uc6_envi_rate += document.querySelector("#envCharge").value;
        }
        if(document.querySelector("#eqTaxRoyalty").value == ""){
            br_uc5_equal_rate += "0.0000";    
        } else {
            br_uc5_equal_rate += document.querySelector("#eqTaxRoyalty").value;
        }
        if(document.querySelector("#NPCStranCons").value == ""){
            br_uc2_npccon_rate += "0.0000";    
        } else {
            br_uc2_npccon_rate += document.querySelector("#NPCStranCons").value;
        }
        if(document.querySelector("#NPCStranDebt").value == ""){
            br_uc1_npcdebt_rate += "0.0000";    
        } else {
            br_uc1_npcdebt_rate += document.querySelector("#NPCStranDebt").value;
        }
        if(document.querySelector("#FITALL").value == ""){
            br_fit_all += "0.0000";    
        } else {
            br_fit_all += document.querySelector("#FITALL").value;
        }
        
        var govCharge = parseFloat(br_uc4_miss_rate_spu) + parseFloat(br_uc4_miss_rate_red);
        govCharge += parseFloat(br_uc6_envi_rate) + parseFloat(br_uc5_equal_rate);
        govCharge += parseFloat(br_uc2_npccon_rate) + parseFloat(br_uc1_npcdebt_rate);
        govCharge += parseFloat(br_fit_all);
        document.querySelector("#PrevGovCharge").value = govCharge.toFixed(4);

        var br_vat_gen = "";
        var br_vat_par = "";
        var br_vat_trans = "";
        var br_vat_transdem = "";
        var br_vat_systloss = "";
        var br_vat_distrib_kwh = "";
        var br_vat_distdem = "";
        var br_vat_supsys = "";
        var br_vat_supfix = "";
        var br_vat_mtr_fix = "";
        var br_vat_metersys = "";
        var br_vat_lfln = "";
        var br_vat_loancondo = "";
        var br_vat_loancondofix = "";
        var Other_Vat = "";

        if(document.querySelector("#genVAT").value == ""){
            br_vat_gen += "0.0000";    
        } else {
            br_vat_gen += document.querySelector("#genVAT").value;
        }
        if(document.querySelector("#powerActRedVAT").value == ""){
            br_vat_par += "0.0000";    
        } else {
            br_vat_par += document.querySelector("#powerActRedVAT").value;
        }
        if(document.querySelector("#tranSysVAT").value == ""){
            br_vat_trans += "0.0000";    
        } else {
            br_vat_trans += document.querySelector("#tranSysVAT").value;
        }
        if(document.querySelector("#transDemVAT").value == ""){
            br_vat_transdem += "0.0000";    
        } else {
            br_vat_transdem += document.querySelector("#transDemVAT").value;
        }
        if(document.querySelector("#sysLossVAT").value == ""){
            br_vat_systloss += "0.0000";
        } else {
            br_vat_systloss += document.querySelector("#sysLossVAT").value;
        }
        if(document.querySelector("#distSysVAT").value == ""){
            br_vat_distrib_kwh += "0.0000";    
        } else {
            br_vat_distrib_kwh += document.querySelector("#distSysVAT").value;
        }
        if(document.querySelector("#distDemVAT").value == ""){
            br_vat_distdem += "0.0000";    
        } else {
            br_vat_distdem += document.querySelector("#distDemVAT").value;
        }
        if(document.querySelector("#suppSysVAT").value == ""){
            br_vat_supsys += "0.0000";    
        } else {
            br_vat_supsys += document.querySelector("#suppSysVAT").value;
        }
        if(document.querySelector("#suppFixVAT").value == ""){
            br_vat_supfix += "0.0000";    
        } else {
            br_vat_supfix += document.querySelector("#suppFixVAT").value;        
        }
        if(document.querySelector("#meterFixVAT").value == ""){
            br_vat_mtr_fix += "0.0000";    
        } else {
            br_vat_mtr_fix += document.querySelector("#meterFixVAT").value;
        }
        if(document.querySelector("#meterSysVAT").value == ""){
            br_vat_metersys += "0.0000";    
        } else {
            br_vat_metersys += document.querySelector("#meterSysVAT").value;
        }
        if(document.querySelector("#lflnDiscSubs").value == ""){
            br_vat_lfln += "0.0000";        
        } else {
            br_vat_lfln += document.querySelector("#lflnDiscSubs").value;
        }
        if(document.querySelector("#loanCondoVAT").value == ""){
            br_vat_loancondo += "0.0000";    
        } else {
            br_vat_loancondo += document.querySelector("#loanCondoVAT").value;
        }
        if(document.querySelector("#loanCondoFixVAT").value == ""){
            br_vat_loancondofix += "0.0000";    
        } else {
            br_vat_loancondofix += document.querySelector("#loanCondoFixVAT").value;
        }
        if(document.querySelector("#otherVAT").value == ""){
            Other_Vat += "0.0000";    
        } else {
            Other_Vat += document.querySelector("#otherVAT").value;
        }
        
        var VAT = parseFloat(br_vat_gen) + parseFloat(br_vat_par) + parseFloat(br_vat_trans);
        VAT += parseFloat(br_vat_transdem) + parseFloat(br_vat_systloss) + parseFloat(br_vat_distrib_kwh);
        VAT += parseFloat(br_vat_distdem) + parseFloat(br_vat_supsys) + parseFloat(br_vat_supfix) + parseFloat(br_vat_mtr_fix);
        VAT += parseFloat(br_vat_metersys) + parseFloat(br_vat_lfln) + parseFloat(br_vat_loancondo);
        VAT += parseFloat(br_vat_loancondofix) + parseFloat(Other_Vat);
        document.querySelector("#PreVAT").value = VAT.toFixed(4);

        var gen = document.querySelector("#PrevGenChargeTotal").value;
        var transgen = document.querySelector("#PrevTransChargeTotal").value;
        var discharge = document.querySelector("#PrevDistCharge").value;
        var otherC = document.querySelector("#PrevOtherCharge").value;
        var bombit = document.querySelector("#PrevGovCharge").value;
        var valueAddedCharge = document.querySelector("#PreVAT").value;

        if(gen !== "" && transgen !== "" && discharge !== "" && otherC !== "" && bombit !== "" && valueAddedCharge !== ""){
            var totalDmdChrge = parseFloat(br_transdem_rate) + parseFloat(br_distdem_rate) + parseFloat(br_vat_transdem) + parseFloat(br_vat_distdem);
            
            var totalFixChrge = parseFloat(br_suprtlcust_fixed) + parseFloat(br_mtrrtlcust_fixed) + parseFloat(br_vat_mtr_fix) + parseFloat(br_loancon_rate_fix); 
            totalFixChrge += parseFloat(br_vat_loancondofix) + parseFloat(br_vat_supfix);
            
            var totalKWHchrge = parseFloat(br_gensys_rate) + parseFloat(br_par_rate) + parseFloat(br_fbhc_rate) + parseFloat(br_forex_rate);
            totalKWHchrge += parseFloat(br_transsys_rate) + parseFloat(br_sysloss_rate) + parseFloat(br_distsys_rate) + parseFloat(br_supsys_rate);
            totalKWHchrge += parseFloat(br_mtrsys_rate) + parseFloat(br_lfln_subs_rate) + parseFloat(br_sc_subs_rate) + parseFloat(br_intrclscrssubrte);
            totalKWHchrge += parseFloat(br_capex_rate) + parseFloat(br_loancon_rate_kwh) + parseFloat(br_uc4_miss_rate_spu);
            totalKWHchrge += parseFloat(br_uc4_miss_rate_red) + parseFloat(br_uc6_envi_rate) + parseFloat(br_uc5_equal_rate) + parseFloat(br_uc2_npccon_rate);
            totalKWHchrge += parseFloat(br_uc1_npcdebt_rate) + parseFloat(br_fit_all) + parseFloat(br_vat_gen) + parseFloat(br_vat_par) + parseFloat(br_vat_trans);
            totalKWHchrge += parseFloat(br_vat_systloss) + parseFloat(br_vat_distrib_kwh) + parseFloat(br_vat_supsys) + parseFloat(br_vat_metersys);
            totalKWHchrge += parseFloat(br_vat_lfln) + parseFloat(br_vat_loancondo);

            document.querySelector("#totalKWHchrge").innerHTML = parseFloat(totalKWHchrge).toFixed(2);
            document.querySelector("#totalDmdChrge").innerHTML = parseFloat(totalDmdChrge).toFixed(2);
            document.querySelector("#totalFixChrge").innerHTML = parseFloat(totalFixChrge).toFixed(2);

            if(gen > 0 && transgen > 0 && discharge > 0 && otherC > 0 && bombit > 0 && valueAddedCharge > 0){ 
                document.querySelector("#totalKWHchrge").style.display = "block";
                document.querySelector("#totalDmdChrge").style.display = "block";
                document.querySelector("#totalFixChrge").style.display = "block";
            }
        }
    }

    function printRates(){
        var billPeriod = document.querySelector("#billingPeriod").value;
        const toSend = {
            'billPeriod': billPeriod
        }
        localStorage.setItem('data', JSON.stringify(toSend));
        $url = '{{route("print_billing_rates")}}'
        window.open($url);
        document.querySelector("#billRates").style.display = "none";
    }
</script>
@endsection