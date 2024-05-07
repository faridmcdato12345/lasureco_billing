<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script> -->
    <!-- <link href="http://fonts.cdnfonts.com/css/dot-matrix" rel="stylesheet"> -->
</head>

<style>
@media print 
{
    @page {
      size: 21.59cm 27.94cm;
      margin:0;
    }
    @page :footer {color: #fff }
    @page :header {color: #fff}
    body{
        font-family: Consolas;
        font-size: 14.2px;
    }
}
body{
    font-size:14.2px;
    font-family: Lucida Console;
    padding-left:10px;
    padding-right:20px;
}
.page-break {
    page-break-after: always;
}
body section{
    width: 85%;
    text-align: center;
    
}

</style>
<body>
    <div id = "tabs">

    </div>
    <script>
    var j=0;
    var today = new Date();
    var f = "";
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    today = dd + '/' + mm + '/' + yyyy;
    var dataP = JSON.parse(localStorage.getItem('data3'));
    var arr = dataP.PB_DETAILS;
    var output = "";  
    // console.log(reprint);
    for(let x in arr){
        j++;
        console.log(arr[x])
        var add = 0;

        add += arr[x].SUB_TOTAL_GC;
        add += arr[x].SUB_TOTAL_OC;
        add += arr[x].SUB_TOTAL_TC;
        add += arr[x].SUB_TOTAL_UC;
        add += arr[x].SUB_TOTAL_VAT;
        add += arr[x].Sub_Total_DC;
        console.log(add);
        var fitallnew = arr[x].Fit_All_RENEW.split('@');
        var q = arr[x].Gen_Sys_Chrg.split('@');
        var powerActRed = arr[x].Power_Act_Red.split('@');
        var fbtoHost = arr[x].Fran_Ben_To_Host.split('@');
        var forexAC = arr[x].FOREX_Adjust_Charge.split('@');
        var meSPUG = arr[x].Miss_Elect_SPUG.split('@');
        var meRED = arr[x].Miss_Elect_RED.split('@');
        var eChrg = arr[x].Envi_Chrg.split('@');
        var eqTR = arr[x].Equali_Of_Taxes_Royalty.split('@');
        var npcSCC = arr[x].NPC_Str_Cons_Cost.split('@');
        var npcST = arr[x].NPC_Str_Debt.split('@');
        var tSC = arr[x].Trans_Sys_Charge.split('@');
        var tDC = arr[x].Trans_Dem_Charge.split('@');
        var sLC = arr[x].System_Loss_Charge.split('@');
        /*----*/
        var dSC = arr[x].Dist_Sys_Chrg.split('@');
        var dDC = arr[x].Dist_Dem_Chrg.split('@');
        var sFC = arr[x].Supply_Fix_Chrg.split('@');
        var sSC = arr[x].Supply_Sys_Chrg.split('@');
        var mFC = arr[x].Metering_Fix_Chrg.split('@');
        var mSC = arr[x].Metering_Sys_Chrg.split('@');
        var lDS = arr[x].Lfln_Disc_Subs.split('@');
        var sCDS = arr[x].Sen_Cit_Disc_Subs.split('@');
        var iCCS = arr[x].Int_Clss_Crss_Subs.split('@');
        var mC = arr[x].MCC_CAPEX.split('@');
        var lC = arr[x].Loan_Condonation.split('@');
        var lCF = arr[x].Loan_Condon_Fix.split('@');
        if(arr[x].Meter_Number == null){
            arr[x].Meter_Number = ' ';
        }
        var gV = arr[x].Generation_Vat.split('@');
        var tSV = arr[x].Trans_Sys_Vat.split('@');
        var tDV = arr[x].Trans_Dem_Vat.split('@');
        var sLV = arr[x].Sys_Loss_Vat.split('@');
        var dSV = arr[x].Dist_Sys_Vat.split('@');
        var dDV = arr[x].Dist_Dem_Vat.split('@');
        var lCV = arr[x].Loan_Cond_Vat.split('@');
        var lCFV = arr[x].Loan_Cond_Fix_Vat.split('@');
        var oV = arr[x].Other_Vat.split('@');
        /* -----  new ------------- */
        var parV = arr[x].Power_Act_Red_Vat.split('@');
        var sFV = arr[x].Supply_Fix_Vat.split('@');
        var sSV = arr[x].Supply_Sys_Vat.split('@');
        var mFV = arr[x].Metering_Fix_Vat.split('@');
        var mSV = arr[x].Metering_Sys_Vat.split('@');
        var lDV = arr[x].Lfln_Disc_Vat.split('@');
		var address;
        var name;
        console.log(arr[x].Address)
        if(arr[x].Address == null){
            arr[x].Address = "No Address";
        }
        if(arr[x].Address.length >= 40){
            address = arr[x].Address.slice(0,40);
        }
        else{
            address = arr[x].Address;
        }
        if(arr[x].Name.length >= 40){
            name = arr[x].Name.slice(0,40);
        }
        else{
            name = arr[x].Name;
        }
        // output +='<center>';
        // output += '<label style="display:inline;font-size: 25px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
        // output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
        // output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br>';
        // output += '<label style="font-size: 20px;">' + 'STATEMENT OF ACCOUNT' + '</label><br></center>';
        // output += '<label style="font-size: 12px;">' + 'RE - PRINT' + '</label></center>'; 
    
        output +='<div style = "margin-top:1.33in;margin-bottom:0.78in;width:100%">';
        if(typeof arr[0].Reprint != 'undefined'){
            output += '<center><label style="font-size: 12px;">' + 'RE - PRINT' + '</label></center>';
        }
        else{
            output += '<center><label style="font-size: 12px;"></label></center>';
        }
        var a = arr[x].Account_No.toString();
        var a1 = a.slice(0,2);
        var a2 = a.slice(2,6);
        var a3 = a.slice(6,10);
        if(arr[x].Rate_Type == 'COMM WATER SYSTEM'){
            arr[x].Rate_Type = 'COMM WATER SYS.';
        }
        if(arr[x].Seq === null){
            arr[x].Seq = '';
        }
        output +='<label style="font-weight:bold;font-size:14px;">SEQUENCE:' + arr[x].Seq + '</label>';
        output += '<table style="border-bottom-style:dashed;border-bottom-color:black;border-bottom-width:1px;border-top-style:dashed;border-top-color:black;border-top-width:1px;width:100%;">';
        output += '<tr style="border-top: 1px dashed black;">';
        output += '<td style="width:15%;">' + 'Bill Number: ' + '</td>'+
                 '<td style="width:23.5%"><label>' +arr[x].BILL_NO + '</label></td>' +
                 '<td style="width:10%">Name: </td>' +
                 '<td><label>' + name+ '</label></td>' +
                 '</tr>' +
                 '<tr style="border-bottom:0.5px dashed black">' +
                 '<td style="width:10%">Account No: </td>' +
                 '<td><label>' + + a1 + '-' + a2 + '-' + a3 +'</label></td>' +
                 '<td >Address: </td>' +
                 '<td><label>' + address+ '</label></td>' +
                 '</tr>';
        output += '</table>';
        output += '<table style="border-bottom-style:dashed;border-bottom-color:black;border-bottom-width:1px;width:100%;"height:10px;">';
        output += '<tr>';
        output += '<td style="width:17%">Bill Period : </td>' +
                '<td style="width:23%;"><label>' + arr[x].Bill_Period+ '</label></td>' +
                 '<td style="width:5%">Rate Type &nbsp&nbsp: </td>' +
                 '<td><label>' + arr[x].Rate_Type + '</label></td>' +
                 '<td style="width:10%;">Demand: </td>' +
                 '<td style="text-align:left;"><label>' + arr[x].Demand_Pres_Reading.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</label></td>' +
                 '</tr>';
        output += '<tr>';
        output += '<td>Meter Number: </td>' +
                '<td><label>' + arr[x].Meter_Number+ '</label></td>' +
                 '<td style="width:1%">Multiplier&nbsp&nbsp: </td>' +
                 '<td style="width:20%"><label>' + 'x' + parseInt(arr[x].Multiplier) + '</label></td>' +
                 '<td style="width:20%;">Demand Kw Used: </td>' +
                 '<td style="text-align:left;"><label>' + arr[x].Demand_Kwh_used.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</label></td>' +
                 '</tr>';
        output += '<tr>';
        output += '<td>Period From : </td>' +
                '<td><label>' + arr[x].Period_From+ '</label></td>' +
                 '<td>Pres Reading: </td>' +
                 '<td><label>' + arr[x].Pres_Reading+ '</label></td>' +

                 '</tr>';           
        output += '<tr>';
        output += '<td>Period To  &nbsp&nbsp: </td>' +
                '<td><label>' + arr[x].Period_To+ '</label></td>' +
                 '<td style="width:17.9%">Prev Reading: </td>' +
                 '<td><label>' + arr[x].Prev_Reading+ '</label></td>' +
                 '</tr>';
        output += '<tr style="border-bottom:1px dashed black">';
        output += '<td colspan=2 style="width:5%">No. of Days Covered:&nbsp&nbsp' +arr[x].No_Days_Covered+ '</td>' +
                // '<td style="width:5%"><label>' + arr[x].No_Days_Covered+ '</label></td>' +
                 '<td colspan=2 style="width:18%">Total KWH Used:&nbsp' +arr[x].Total_KWH_Used+ '</td>' +
                 '</tr></table>';
            
        // output += '<div class = "row" >';
        // output += '<div class = "col-sm-6" style="border-right: 1px dashed black;">';
        output += '<div style="float:left;width:50%">';
        if(arr[x].Total_Arrears.toString().length >= 10){
        output += '<table style="font-size:13.5px;text-align:left;width:100%;">';
        }else{
        output += '<table style="text-align:left;width:100%;">';    
        }
        output +=   '<tr>' +
                    '<th>CHARGES</th>' +
                    '<th>RATE</th>' +
                    '<th style="text-align:right">AMOUNT</th></tr>' + 
                    '<tr>' +
                    '<td>GENERATION CHARGES</td></tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Gen.Sys.Chrg' + '</td>' +
                    '<td>' + q[0] + '</td>' +
                    '<td style="text-align:right;">' + q[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Power Act Red.' + '</td>' +
                    '<td>' + powerActRed[0] + '</td>' +
                    '<td style="text-align:right;">' + powerActRed[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Fran.&Ben.to Host' + '</td>' +
                    '<td>' + fbtoHost[0] + '</td>' +
                    '<td style="text-align:right;">' + fbtoHost[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'FOREX Adjust. Chrg' + '</td>' +
                    '<td>' + forexAC[0] + '</td>' +
                    '<td style="text-align:right;">' + forexAC[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:15px;">' + 'SUB TOTAL (GC)' + '</td>' +
                    '<td>' + ' ' + '</td>' +
                    '<td style="text-align:right;">' + arr[x].SUB_TOTAL_GC.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<td>TRANSMISSION CHARGES</td></tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Trans.Sys.Charge' + '</td>' +
                    '<td>' + tSC[0] + '</td>' +
                    '<td style="text-align:right;">' + tSC[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Trans.Dem.Charge' + '</td>' +
                    '<td>' + tDC[0] + '</td>' +
                    '<td style="text-align:right;">' + tDC[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'System Loss Charge' + '</td>' +
                    '<td>' + sLC[0] + '</td>' +
                    '<td style="text-align:right;">' + sLC[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:15px">' + 'SUB TOTAL (TC)' + '</td>' +
                    '<td>' + ' ' + '</td>' +
                    '<td style="text-align:right;">' + arr[x].SUB_TOTAL_TC.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '</tr>' + 
                    '<tr>' +
                    '<td >DISTRIBUTION CHARGES</td></tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Dist.Sys.Charge' + '</td>' +
                    '<td>' + dSC[0] + '</td>' +
                    '<td style="text-align:right;">' + dSC[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Dist.Dem.Charge' + '</td>' +
                    '<td>' + dDC[0] + '</td>' +
                    '<td style="text-align:right;">' + dDC[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Supply Fix Charge' + '</td>' +
                    '<td>' + sFC[0] + '</td>' +
                    '<td style="text-align:right;">' + sFC[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Supply Sys. Charge' + '</td>' +
                    '<td>' + sSC[0] + '</td>' +
                    '<td style="text-align:right;">' + sSC[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Metering Fix Charge' + '</td>' +
                    '<td>' + mFC[0] + '</td>' +
                    '<td style="text-align:right;">' + mFC[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Metering Sys Charge' + '</td>' +
                    '<td>' + mSC[0] + '</td>' +
                    '<td style="text-align:right;">' + mSC[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:15px;">' + 'SUB TOTAL (DC)' + '</td>' +
                    '<td>' + ' ' + '</td>' +
                    '<td style="text-align:right;">' + arr[x].Sub_Total_DC.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '</tr>' + 
                    '<tr>' +
                    '<td>OTHER CHARGES</td></tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Lfln Disc./Subs.' + '</td>';
                    if(lDS[1] < 0){
                       output += '<td>' + '0.0000/kwh' + '</td>';
                    }
                    else{
                        output += '<td>' + lDS[0] + '</td>';  
                    }
                    
                    output +='<td style="text-align:right;">' + lDS[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Sen.Cit.Disc./Subs.' + '</td>' +
                    '<td>' + sCDS[0] + '</td>' +
                    '<td style="text-align:right;">' + sCDS[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Int.Clss.Crss.Subs' + '</td>' +
                    '<td>' + iCCS[0] + '</td>' +
                    '<td style="text-align:right;">' + iCCS[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'MCC CAPEX' + '</td>' +
                    '<td>' + mC[0] + '</td>' +
                    '<td style="text-align:right;">' + mC[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Loan Condonation' + '</td>' +
                    '<td>' + lC[0] + '</td>' +
                    '<td style="text-align:right;">' + lC[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Loan Condon Fix' + '</td>' +
                    '<td>' + lCF[0] + '</td>' +
                    '<td style="text-align:right;">' + lCF[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:15px;">' + 'SUB TOTAL (OC)' + '</td>' +
                    '<td>' + ' ' + '</td>' +
                    '<td style="text-align:right;">' + arr[x].SUB_TOTAL_OC.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '</tr>';
        output += '<tr>' + 
                    '<td>[LAST PAYMENT]</td>'+ 
                 '</tr>';  
        var lp = arr[x].LAST_PAYMENT.split('@');

        if(typeof lp[1] == 'undefined' && typeof lp[2] == 'undefined'){
            lp[1] = ' ';
            lp[2] = ' ';
        }
        else{
            lp[1] = lp[1];
            lp[2] = ' ';
            lp[1] = parseFloat(lp[1]);
        }
        console.log(lp[1])
        output += '<tr>' + 
                  '<td>' + lp[0] + '</td>' + 
                  '<td>' + lp[1].toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' + 
                  '<td style="text-align:right;">' + lp[2] + '</td></tr>';
        output += '</table><br>';
        output += '<div style="text-align:center;border:1px solid #5B9BD5">';
        output += '<p style="display:inline;font-size:12px">Please pay your Power Bill in any Official LASURECO</p><br>' +
                  '<p style="display:inline;font-size:12px">Paying Centers, Authorized MOBILE Tellering Outlet</p><br>' +  
                  '<p style="display:inline;font-size:12px">with Official Receipt presented or</p><br>'+
                  '<p style="display:inline;font-size:12px">through Online Payment via GCASH and PayMaya.</p><br>'+
                  '<p style="display:inline;font-size:12px">Visit www.lasureco.com/online-payment</p>'+
                  '</div>';
        output += '</div>';
        output += '<div style="border-left:1px dashed black;float:right;width:49%">';
        if(arr[x].Total_Arrears.toString().length >= 10){
        output += '<table style="font-size:13.5px;text-align:left;width:100%;">';
        }else{
        output += '<table style="text-align:left;width:100%;">';    
        }
        output +=   '<tr>' +
                    '<th> CHARGES </th>' +
                    '<th> RATE </th>' +
                    '<th style="text-align:right;"> AMOUNT </th>' + 
                    '<tr>' +
                    '<td>UNIVERSAL CHARGES</td></tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Miss.Elect.(SPUG)' + '</td>' +
                    '<td>' + meSPUG[0] + '</td>' +
                    '<td style="text-align:right;">' + meSPUG[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Miss.Elect.(RED)' + '</td>' +
                    '<td>' + meRED[0] + '</td>' +
                    '<td style="text-align:right;">' + meRED[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Envi. Chrg' + '</td>' +
                    '<td>' + eChrg[0] + '</td>' +
                    '<td style="text-align:right;">' + eChrg[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Equali. of Taxes & Royalty' + '</td>' +
                    '<td>' + eqTR[0] + '</td>' +
                    '<td style="text-align:right;">' + eqTR[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'NPC Str Cons Cost' + '</td>' +
                    '<td>' + npcSCC[0] + '</td>' +
                    '<td style="text-align:right;">' + npcSCC[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'NPC Stranded Debt' + '</td>' +
                    '<td>' + npcST[0] + '</td>' +
                    '<td style="text-align:right;">' + npcST[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Fit All(Renew)' + '</td>' +
                    '<td>' + fitallnew[0] + '</td>' +
                    '<td style="text-align:right;">' + fitallnew[1] + '</td>' +
                    '</tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:15px;">' + 'SUB TOTAL (UC)' + '</td>' +
                    '<td>' + ' ' + '</td>' +
                    '<td style="text-align:right;">' + arr[x].SUB_TOTAL_UC.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '</tr>' +                    
                    '<tr>' +
                    '<td>VALUE ADDED TAX</td></tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Generation VAT' + '</td>' +
                    '<td>' + gV[0] + '</td>' +
                    '<td style="text-align:right;">' + gV[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Power Act Red VAT' + '</td>' +
                    '<td>' + parV[0] + '</td>' +
                    '<td style="text-align:right;">' + parV[1] + '</td>' +
                    '</tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Trans. Sys. VAT' + '</td>' +
                    '<td>' + tSV[0] + '</td>' +
                    '<td style="text-align:right;">' + tSV[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Trans. Dem. VAT' + '</td>' +
                    '<td>' + tDV[0] + '</td>' +
                    '<td style="text-align:right;">' + tDV[1] + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'System Loss VAT' + '</td>' +
                    '<td>' + sLV[0] + '</td>' +
                    '<td style="text-align:right;">' + sLV[1] + '</td>' +
                    '</tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Dist. Sys. VAT' + '</td>' +
                    '<td>' + dSV[0] + '</td>' +
                    '<td style="text-align:right;">' + dSV[1] + '</td>' +
                    '</tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Dist. Dem. VAT' + '</td>' +
                    '<td>' + dDV[0] + '</td>' +
                    '<td style="text-align:right;">' + dDV[1] + '</td>' +
                    '</tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Supply Fix VAT' + '</td>' +
                    '<td>' + sFV[0] + '</td>' +
                    '<td style="text-align:right;">' + sFV[1] + '</td>' +
                    '</tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Supply Sys VAT' + '</td>' +
                    '<td>' + sSV[0] + '</td>' +
                    '<td style="text-align:right;">' + sSV[1] + '</td>' +
                    '</tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Metering Fix VAT' + '</td>' +
                    '<td>' + mFV[0] + '</td>' +
                    '<td style="text-align:right;">' + mFV[1] + '</td>' +
                    '</tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Metering Sys VAT' + '</td>' +
                    '<td>' + mSV[0] + '</td>' +
                    '<td style="text-align:right;">' + mSV[1] + '</td>' +
                    '</tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;font-size:12px">' + 'Lfln Disc./Subs. VAT' + '</td>' +
                    '<td>' + lDV[0] + '</td>' +
                    '<td style="text-align:right;">' + lDV[1] + '</td>' +
                    '</tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Loan Condo. VAT' + '</td>' +
                    '<td>' + lCV[0] + '</td>' +
                    '<td style="text-align:right;">' + lCV[1] + '</td>' +
                    '</tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Loan Cond. Fix VAT' + '</td>' +
                    '<td>' + lCFV[0] + '</td>' +
                    '<td style="text-align:right;">' + lCFV[1] + '</td>' +
                    '</tr>' +
                    // '<tr style="text-align:left;">' +
                    // '<td style="padding-left:10px;">' + 'Loan Condo. VAT' + '</td>' +
                    // '<td>' + '0.0000' + '</td>' +
                    // '<td>' + '0.00' + '</td>' +
                    // '</tr>' +
                    // '<tr style="text-align:left;">' +
                    // '<td style="padding-left:10px;">' + 'Loan Condo. Fxd VAT' + '</td>' +
                    // '<td>' +'0.0000'  + '</td>' +
                    // '<td>' + '0.00' + '</td>' +
                    // '</tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Other VAT' + '</td>' +
                    '<td>' + oV[0] + '</td>' +
                    '<td style="text-align:right;">' + oV[1] + '</td>' +
                    '</tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="border-bottom: 1px dashed black;padding-left:15px;">' + 'SUB TOTAL (VAT)' + '</td>' +
                    '<td style="border-bottom: 1px dashed black">' + ' ' + '</td>' +
                    '<td style="border-bottom: 1px dashed black;text-align:right" >' + arr[x].SUB_TOTAL_VAT.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '</tr>' + 
                    '<tr style="border-bottom: 1px dashed black">' +
                    '<td>CURRENT BILL</td>' +
                    '<td>Php</td>' +
                    '<td style="text-align:right;">' + arr[x].CURRENT_MONTH.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '</tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Total Arrears' + '</td>' +
                    '<td>' + ' ' + '</td>' +
                    '<td style="text-align:right;">' + arr[x].Total_Arrears.toLocaleString("en-US", { minimumFractionDigits: 2 })+ '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Material Cost/Integ' + '</td>' +
                    '<td>' + ' ' + '</td>' +
                    '<td style="text-align:right;">' + arr[x].Material_Cost_Integ.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'Transformer Rental' + '</td>' +
                    '<td>' + ' ' + '</td>' +
                    '<td style="text-align:right;">' + arr[x].Transformer_Rental.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '</tr>' + 
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'TOTAL AMOUNT DUE' + '</td>' +
                    '<td>' + ' ' + '</td>' +
                    '<td style="text-align:right;">' + arr[x].Total_Amount_Due.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '</tr>';

                    if(arr[x].Rate_Type == 'PUBLIC BUILDING' || arr[x].Rate_Type == 'COMMERCIAL' || arr[x].Rate_Type == 'PUBLIC BUILDING 2' || arr[x].Rate_Type == 'COMMERCIAL 2'){
            output += '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'LGU 5%' + '</td>' +
                    '<td>' + ' ' + '</td>' +
                    '<td style="text-align:right;">' + arr[x].LGU_5.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '</tr>' +
                    '<tr style="text-align:left;">' +
                    '<td style="padding-left:10px;">' + 'LGU 2%' + '</td>' +
                    '<td>' + ' ' + '</td>' +
                    '<td style="text-align:right;">' + arr[x].LGU_2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '</tr>';
                    }
            output +=  '</table><br>';
        output += '<table style="font-size:14px;" width="100%">';
        output += '<tr style="text-align:left;">' +
                    '<td> E - Wallet </td>' +
                    '<td style="text-align:right;">' + arr[x].E_Wallet.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '</tr>'
        output += '<tr><td width="27%">DUE DATE: </td><td>' +arr[x].Due_Date + '</td></tr></table><br>';
        output += '</div>';
        
        output += '</div>'; 
        /*--------------------------*/
        
        output +='<div class="page-break"></div>'; 
    }
    document.querySelector('#tabs').innerHTML = output;
    window.print();
</script>
</body>
</html>
