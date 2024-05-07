<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <title> Print Monthly Cashiers DCR SO </title>
</head>
<style media="print">
    @page {
        size: Legal landscape;
        margin-left: 5mm !important;
    }
    table {
        font-size: 12px !important;
        margin: auto;
    }
    th {
        font-weight: 400 !important;
    }
    .delete {
        display: none;
    }
    .action {
        display: none;
    }
    #logo {
        display: none;
    }
</style>
<style>
    .page-break {
        page-break-after: always;
    }
    body {
        font-family: Consolas;
    }
    table {
        margin: auto;
        width: 100%;
        font-size: 12px;
        float: left;
        border-right: 0.75px dashed;
        border-bottom: 0.75px dashed;
    }
    .left{
        border-left: 0.75px dashed;
        text-align: center;
    }
    .right{
        border-right: 0.75px dashed;
        text-align: center;
    }
    .bot{
        border-bottom: 0.75px dashed;
        text-align: center;
    }
    .top{
        border-top: 0.75px dashed;
        text-align: center;
    }
    .delete {
        background-color: rgb(221, 51, 51);
        color: white;
        border: 0px;
        height: 25px;
        cursor: pointer;
        border-radius: 2px;
    }
    #numBar {
        display: block;
    }
    #logo {
		height: 70px;
		width: 70px;
        float: left;
        margin-top: 22px;
        margin-left: 25px;
	}
    #lasuText {
        font-size: 24px; 
        font-weight: bold; 
        margin-left: -90px;
    }
    #dateText {
        font-size: 18px; 
        margin-left: -100px;
    }
    #emailText {
        font-size: 15px; 
        margin-left: -100px;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
    <br>
    <p id="numBar"> </p>
</body>
</html>

<script>
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();

    var toSend = new Object();

    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    var param = JSON.parse(localStorage.getItem("data"));

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date = param.date;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;

        var monthlyDCR = "{{route('accounting.monthly.dcr')}}";
        xhr.open('POST', monthlyDCR, true);

        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var loop = data.loop;

                var output = "";
                var date = param.date;
                var year = date.slice(0,4);
                var month = date.slice(5,7);

                output += '<img id="logo" src="/img/logo.png">';
                output += "<br>";
                output += '<center> <label id="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label id="emailText"> teamlasureco@gmail.com </label><br><br><br>';
                output += "<label style='font-size: 20px;'> MONTHLY CASHIER'S DCR </label> <br>";
                output += '<label style="font-size: 18px;">' + months[month - 1] + ', ' + year + '</label> <br> </center>';
                output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th class="left top bot" rowspan=2> Day </th>';
                output += '<th class="left top bot" rowspan=2> Total Amount </th>';
                output += '<th class="top left" colspan=3> Power Bill </th>';
                output += '<th rowspan=2 class="top left bot"> Power Bill Deposit </th>';
                output += '<th rowspan=2 class="top left bot"> 2% WTax </th>';
                output += '<th rowspan=2 class="top left bot"> 5% WTax </th>';
                output += '<th rowspan=2 class="top left bot"> Sundries </th>';
                output += '<th rowspan=2 class="top left bot"> Advance Payment </th>';
                output += '<th rowspan=2 class="top left bot"> Membership </th>';
                output += '<th colspan=4 class="left top bot"> Generation Charges </th>'; 
                output += '<th colspan=3 class="left top bot"> Transmission Charges </th>'; 
                output += '<th colspan=6 class="left top bot"> Distribution Charges </th></tr>';
                output += '<tr> <th class="left top bot"> No. of Bills </th>';
                output += '<th class="left top bot"> KWH Used </th>';
                output += '<th class="left top bot"> Amount </th>';
                output += '<th class="left bot"> Gen. Sys. Chrg. </th>'; 
                output += '<th class="left bot"> Power Act Red. </th>'; 
                output += '<th class="left bot"> Fran. & Ben. to Host </th>'; 
                output += '<th class="left bot"> FOREX Adjust Chrg </th>'; 
                output += '<th class="left bot"> Trans Sys Chrg </th>'; 
                output += '<th class="left bot"> Trans Dem Chrg </th>'; 
                output += '<th class="left bot"> Sys Loss Chrg </th>'; 
                output += '<th class="left bot"> Dist Sys Chrg </th>'; 
                output += '<th class="left bot"> Dist Dem Chrg </th>'; 
                output += '<th class="left bot"> Supp Fix Chrg </th>'; 
                output += '<th class="left bot"> Supp Sys Chrg </th>'; 
                output += '<th class="left bot"> Meter Fix Chrg </th>'; 
                output += '<th class="left bot"> Meter Sys Chrg </th>'; 
                output += '</tr>';

                var totalTotal = 0;
                var totalNoBills = 0;
                var totalKWH = 0;
                var totalAmount = 0;
                var totalPBD = 0;
                var totalLGU2 = 0;
                var totalLGU5 = 0;
                var totalSundries = 0;
                var totalAdv = 0;
                var totalGenSysChrg = 0;
                var totalPAR = 0;
                var totalFBHC = 0;
                var totalFOREX = 0;
                var totalTransSysChrg = 0;
                var totalTransDemChrg = 0;
                var totalSysLossChrg = 0;
                var totalDistSysChrg = 0;
                var totalDistDemChrg = 0;
                var totalSuppFixChrg = 0;
                var totalSuppSysChrg = 0;
                var totalMeterFixChrg = 0;
                var totalMeterSysChrg = 0;
                var totalLflnDiscSubs = 0;
                var totalSenCitDiscSubs = 0;
                var totalIntClssCrssSubs = 0;
                var totalMCCCapex = 0;
                var totalLoanCond = 0;
                var totalLoanCondFix = 0;
                var totalMembership = 0;
                
                for(var x=1; x<=loop; x++){
                    output += "<tr> <td class='left'>" + x + "</td>";

                    if(data.info[x] !== undefined){
                        output += "<td class='left'>" + data.info[x].total_amount.toLocaleString("en-US") + "</td>";
                        totalTotal += data.info[x].total_amount;
                        output += "<td class='left'>" + data.info[x].number_of_bills.toLocaleString("en-US") + "</td>";
                        totalNoBills += data.info[x].number_of_bills;
                        output += "<td class='left'>" + data.info[x].kwh.toLocaleString("en-US") + "</td>";
                        totalKWH += data.info[x].kwh;
                        output += "<td class='left'>" + data.info[x].amount_PB.toLocaleString("en-US") + "</td>";
                        totalAmount += data.info[x].amount_PB;
                        output += "<td class='left'>" + data.info[x].power_bill_deposit.toLocaleString("en-US") + "</td>";
                        totalPBD += data.info[x].power_bill_deposit;
                        output += "<td class='left'>" + data.info[x].lgu_2.toLocaleString("en-US") + "</td>";
                        totalLGU2 += data.info[x].lgu_2;
                        output += "<td class='left'>" + data.info[x].lgu_5.toLocaleString("en-US") + "</td>";
                        totalLGU5 += data.info[x].lgu_5;
                        output += "<td class='left'>" + data.info[x].sundries.toLocaleString("en-US") + "</td>";
                        totalSundries += data.info[x].sundries;
                        output += "<td class='left'>" + data.info[x].advance_payment.toLocaleString("en-US") + "</td>";
                        totalAdv += data.info[x].advance_payment;
                        output += "<td class='left'>" + data.info[x].membership.toLocaleString("en-US") + "</td>";
                        totalMembership += data.info[x].membership;
                        output += "<td class='left'>" + data.info[x].gensys.toLocaleString("en-US") + "</td>";
                        totalGenSysChrg += data.info[x].gensys;
                        output += "<td class='left'>" + data.info[x].par.toLocaleString("en-US") + "</td>";
                        totalPAR += data.info[x].par;
                        output += "<td class='left'>" + data.info[x].fbhc.toLocaleString("en-US") + "</td>";
                        totalFBHC += data.info[x].fbhc;
                        output += "<td class='left'>" + data.info[x].forex.toLocaleString("en-US") + "</td>";
                        totalFOREX += data.info[x].forex;
                        output += "<td class='left'>" + data.info[x].transys.toLocaleString("en-US") + "</td>";
                        totalTransSysChrg += data.info[x].transys;
                        output += "<td class='left'>" + data.info[x].transdem.toLocaleString("en-US") + "</td>";
                        totalTransDemChrg += data.info[x].transdem;
                        output += "<td class='left'>" + data.info[x].sysloss.toLocaleString("en-US") + "</td>";
                        totalSysLossChrg += data.info[x].sysloss;
                        output += "<td class='left'>" + data.info[x].distsys.toLocaleString("en-US") + "</td>";
                        totalDistSysChrg += data.info[x].distsys;
                        output += "<td class='left'>" + data.info[x].distdem.toLocaleString("en-US") + "</td>";
                        totalDistDemChrg += data.info[x].distdem;
                        output += "<td class='left'>" + data.info[x].supfix.toLocaleString("en-US") + "</td>";
                        totalSuppFixChrg += data.info[x].supfix;
                        output += "<td class='left'>" + data.info[x].supsys.toLocaleString("en-US") + "</td>";
                        totalSuppSysChrg += data.info[x].supsys;
                        output += "<td class='left'>" + data.info[x].meterfix.toLocaleString("en-US") + "</td>";
                        totalMeterFixChrg += data.info[x].meterfix;
                        output += "<td class='left'>" + data.info[x].metersys.toLocaleString("en-US") + "</td>";
                        totalMeterSysChrg += data.info[x].metersys
                    } else {
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                    }
                    output += "</tr>";
                }
                output += "<tr><td class='left top'></td>";
                output += "<td class='left top'>" + totalTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalNoBills.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalKWH.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalAmount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalPBD.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalLGU2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalLGU5.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalSundries.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalAdv.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalMembership.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalGenSysChrg.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalPAR.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalFBHC.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalFOREX.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalTransSysChrg.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalTransDemChrg.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalSysLossChrg.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalDistSysChrg.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalDistDemChrg.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalSuppFixChrg.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalSuppSysChrg.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalMeterFixChrg.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalMeterSysChrg.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr></table>";

                output += '<div class="page-break"></div>';
                output += '<img id="logo" src="/img/logo.png">';
                output += "<br>";
                output += '<center> <label style="font-size: 24px;"> <b> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </b> </label><br>';
                output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label id="emailText"> teamlasureco@gmail.com </label><br><br><br>';
                output += "<label style='font-size: 20px;'> MONTHLY CASHIER'S DCR </label> <br>";
                output += '<label style="font-size: 18px;">' + months[month - 1] + ', ' + year + '</label> <br> </center>';
                output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += "<table id='table'>";
                output += '<tr> <th class="left bot top" rowspan=2> Day </th>';
                output += '<th class="left top bot" colspan=6> Other Charges </th>';
                output += '<th colspan=6 class="left top bot"> Universal Charges </th>'; 
                output += '<th rowspan=2 class="left top bot"> Fit All (Renew) </th>'; 
                output += '<th colspan=15 class="left top bot"> Value Added Tax </th> </tr>';
                output += '<tr> <th class="left bot"> Lfln Disc./ Subs.</th>';
                output += '<th class="left bot"> Sen. Cit. Disc./ Subs. </th>'; 
                output += '<th class="left bot"> Int. Clss. Crss. Subs. </th>'; 
                output += '<th class="left bot"> MCC CAPEX </th>'; 
                output += '<th class="left bot"> Loan Cond </th>'; 
                output += '<th class="left bot"> Loan Cond Fix </th>'; 
                output += '<th class="left bot"> Miss. Elect.(SPUG) </th>'; 
                output += '<th class="left bot"> Miss. Elect.(RED) </th>'; 
                output += '<th class="left bot"> Envi. Chrg </th>'; 
                output += '<th class="left bot"> Equali. of Taxes & Royalty </th>'; 
                output += '<th class="left bot"> NPC Str Cons Cost </th>'; 
                output += '<th class="left bot"> NPC Stranded Debt </th>';
                output += '<th class="left bot"> Gen VAT </th>'; 
                output += '<th class="left bot"> Power Act Red VAT </th>'; 
                output += '<th class="left bot"> Trans. Sys. VAT </th>'; 
                output += '<th class="left bot"> Trans. Dem. VAT </th>'; 
                output += '<th class="left bot"> Sys Loss VAT </th>'; 
                output += '<th class="left bot"> Dist Sys VAT </th>'; 
                output += '<th class="left bot"> Dist Dem VAT </th>'; 
                output += '<th class="left bot"> Supp Fix VAT </th>'; 
                output += '<th class="left bot"> Supp Sys VAT </th>'; 
                output += '<th class="left bot"> Meter Fix VAT </th>'; 
                output += '<th class="left bot"> Meter Sys VAT </th>'; 
                output += '<th class="left bot"> Lfln Disc./ Subs. VAT </th>'; 
                output += '<th class="left bot"> Loan Condo. VAT </th>'; 
                output += '<th class="left bot"> Loan Cond. Fix VAT </th>'; 
                output += '<th class="left bot"> Other VAT </th>'; 
                output += '</tr>';

                var totalSpug = 0;
                var totalRed = 0;
                var totalEnviChrg = 0;
                var totalEquliroyal = 0;
                var totalNpcCon = 0;
                var totalNpcDebt = 0;
                var totalFitAll = 0;
                var totalGenVat = 0;
                var totalParVat = 0;
                var totalTransVat = 0;
                var totalTransDemVat = 0;
                var totalSysLossVat = 0;
                var totalDistSysVat = 0;
                var totalDistDemVat = 0;
                var totalSuppFixVat = 0;
                var totalSuppSysVat = 0;
                var totalMtrFixVat = 0;
                var totalMtrSysVat = 0;
                var totalLflnDiscSubVat = 0;
                var totalLoanCondVat = 0;
                var totalLoanCondFixVat = 0;
                var totalOtherVat = 0;

                for(var x=1; x<=loop; x++){
                    output += "<tr> <td class='left'>" + x + "</td>";

                    if(data.info[x] !== undefined){ 
                        output += "<td class='left'>" + data.info[x].lflnDisc.toLocaleString("en-US") + "</td>";
                        totalLflnDiscSubs += data.info[x].lflnDisc;
                        output += "<td class='left'>" + data.info[x].sencitdiscsub.toLocaleString("en-US") + "</td>";
                        totalSenCitDiscSubs += data.info[x].sencitdiscsub;
                        output += "<td class='left'>" + data.info[x].intClssCrssSubs.toLocaleString("en-US") + "</td>";
                        totalIntClssCrssSubs += data.info[x].intClssCrssSubs;
                        output += "<td class='left'>" + data.info[x].capex.toLocaleString("en-US") + "</td>";
                        totalMCCCapex += data.info[x].capex;
                        output += "<td class='left'>" + data.info[x].loancond.toLocaleString("en-US") + "</td>";
                        totalLoanCond += data.info[x].loancond;
                        output += "<td class='left'>" + data.info[x].loancondfix.toLocaleString("en-US") + "</td>";
                        totalLoanCondFix += data.info[x].loancondfix;
                        output += "<td class='left'>" + data.info[x].spug.toLocaleString("en-US") + "</td>";
                        totalSpug += data.info[x].spug;
                        output += "<td class='left'>" + data.info[x].red.toLocaleString("en-US") + "</td>";
                        totalRed += data.info[x].red;
                        output += "<td class='left'>" + data.info[x].envichrge.toLocaleString("en-US") + "</td>";
                        totalEnviChrg += data.info[x].envichrge;
                        output += "<td class='left'>" + data.info[x].equliroyal.toLocaleString("en-US") + "</td>";
                        totalEquliroyal += data.info[x].equliroyal;
                        output += "<td class='left'>" + data.info[x].npccon.toLocaleString("en-US") + "</td>";
                        totalNpcCon += data.info[x].npccon;
                        output += "<td class='left'>" + data.info[x].npcdebt.toLocaleString("en-US") + "</td>";
                        totalNpcDebt += data.info[x].npcdebt;
                        output += "<td class='left'>" + data.info[x].fitall.toLocaleString("en-US") + "</td>";
                        totalFitAll += data.info[x].fitall;
                        output += "<td class='left'>" + data.info[x].genvat.toLocaleString("en-US") + "</td>";
                        totalGenVat += data.info[x].genvat;
                        output += "<td class='left'>" + data.info[x].parvat.toLocaleString("en-US") + "</td>";
                        totalParVat += data.info[x].parvat;
                        output += "<td class='left'>" + data.info[x].transvat.toLocaleString("en-US") + "</td>";
                        totalTransVat += data.info[x].transvat;
                        output += "<td class='left'>" + data.info[x].transdemvat.toLocaleString("en-US") + "</td>";
                        totalTransDemVat += data.info[x].transdemvat;
                        output += "<td class='left'>" + data.info[x].syslossvat.toLocaleString("en-US") + "</td>";
                        totalSysLossVat = data.info[x].syslossvat;
                        output += "<td class='left'>" + data.info[x].distsysvat.toLocaleString("en-US") + "</td>";
                        totalDistSysVat += data.info[x].distsysvat;
                        output += "<td class='left'>" + data.info[x].distdemvat.toLocaleString("en-US") + "</td>";
                        totalDistDemVat += data.info[x].distdemvat;
                        output += "<td class='left'>" + data.info[x].supplyfixvat.toLocaleString("en-US") + "</td>";
                        totalSuppFixVat += data.info[x].supplyfixvat;
                        output += "<td class='left'>" + data.info[x].supsysvat.toLocaleString("en-US") + "</td>";
                        totalSuppSysVat += data.info[x].supsysvat;
                        output += "<td class='left'>" + data.info[x].mtrfixvat.toLocaleString("en-US") + "</td>";
                        totalMtrFixVat += data.info[x].mtrfixvat;
                        output += "<td class='left'>" + data.info[x].mtrsysvat.toLocaleString("en-US") + "</td>";
                        totalMtrSysVat += data.info[x].mtrsysvat;
                        output += "<td class='left'>" + data.info[x].lflnDiscSubvat.toLocaleString("en-US") + "</td>";
                        totalLflnDiscSubVat += data.info[x].lflnDiscSubvat;
                        output += "<td class='left'>" + data.info[x].loancondvat.toLocaleString("en-US") + "</td>";
                        totalLoanCondVat += data.info[x].loancondvat;
                        output += "<td class='left'>" + data.info[x].loancondifixvat.toLocaleString("en-US") + "</td>";
                        totalLoanCondFixVat += data.info[x].loancondifixvat;
                        output += "<td class='left'>" + data.info[x].others_vat.toLocaleString("en-US") + "</td>";
                        totalOtherVat += parseInt(data.info[x].others_vat);
                    } else {
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                        output += "<td class='left'></td>";
                    }
                }

                output += "</tr><tr><td class='left top'></td>";
                output += "<td class='left top'>" + totalLflnDiscSubs.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalSenCitDiscSubs.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalIntClssCrssSubs.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalMCCCapex.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalLoanCond.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalLoanCondFix.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalSpug.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalRed.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalEnviChrg.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalEquliroyal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalNpcCon.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalNpcDebt.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalFitAll.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalGenVat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalPAR.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalTransSysChrg.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalTransDemVat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalSysLossVat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalDistSysVat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalDistDemVat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalSuppFixVat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalSuppSysVat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalMtrFixVat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalMtrSysVat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalLflnDiscSubVat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalLoanCondVat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalLoanCondFixVat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalOtherVat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr> </table>";
                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                Swal.fire({
                    title: 'Notice!',
                    icon: 'error',
                    text: 'No Report found!'
                }).then(function(){ 
                    window.close();
                });
            }
        }
    }
</script>