<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Print Summary of Bills</title>

</head>
<style media="print">
    @page {
      size: landscape;
      margin: 0mm;
    }
    #lasuhead {
        font-size: 23px !important; 
        margin-top: 1% !important; 
    }
    table {
		font-size: 12px !important;
    }
    #totalRow {
        font-size: 11px !important;
    }
    th {
        font-weight: 400 !important;
        font-size: 15px !important;
    }
	#table2 {
		font-size: 11px !important;
	}
    #table4 {
        font-size: 11px !important;
        margin-right: -0.1% !important;
    }
</style>
<style>
    #lasuhead {
        font-size: 24px; 
        font-weight: bold;
    }
    .page-break {
        page-break-after: always;
    }
    body {
        font-family: Consolas;
    }
    table{
        margin:auto;
        font-size: 15px;
        margin-left: auto; 
    }
    #table3{
        margin:auto;
        font-size: 12px;
        margin-left: 2%; 
        width: 97%;
    }
    #table4{
        float: right; 
        margin-top: 1%;
        margin-right: 6%;
    }
    #table2{
        margin:auto;
        margin-left: 0%;
        font-size: 14px !important;
    }
    th{
        border-bottom: 0.75px dashed;
        border-top: 0.75px dashed;
    }
    #table td {
        text-align: center;
        /* height: 30px; */
        border-bottom: 0.75px dashed;
    }
    #consCount {
        float: left;
    }
    .left {
        border-left: 0.75px dashed;
		text-align: center;
    }
    .right {
        border-right: 0.75px dashed;
		text-align: center;
    }
    .top {
        border-top: 0.75px dashed;
		text-align: center;
    }
    .bot {
        border-bottom: 0.75px dashed;
		text-align: center;
    }
</style>

<body onload="getData()">
    <div id = "printBody">
    </div>

    <div id = "consCount">
    </div>

    <div id = "consTypeVat">
    </div>
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    var routeId = param.routeId;
    var billPeriod = param.billPeriod;
    var recap = param.recap;
    var type = param.type;
    var billdate = "";
    
    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date = billPeriod;
        toSend.route_id = routeId;
        toSend.recap = recap;
        toSend.type = type;
        toSend.demand = param.demand;
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var summBills = "{{route('reports.bill.summary')}}";
        xhr.open('POST', summBills, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                // console.log(data);
                var summBill = data.Summary_Bills;
                var consType = data.Cons_type;
                var KWHUsed = data.Summary_Bill_Constype_Total_KWH_USED;
                var TotBillAmnt = data.Summary_Bill_Constype_Total_BILL_Amount;
                var TotSumBill = data.Total_Summary_Bills;
                var summBillconsTypeVat = data.Summary_Bill_Constype_Vat;
                var area = data.Area;
                var town = data.Town;
                var route = data.Route;
                var num = 0;
                var output = " ";
                var output2 = " ";
                var consCountTable = "";
                var totalSummBill = "";
                var consumerType = "";
                var consTypeVat = "";

                if(recap == "No") {
                    output += '<center> <label id="lasuhead"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                    output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                    output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                    output += '<label style="font-size: 18px;"> SUMMARY OF BILLS - ALL CONSUMERS </lable> <br><br>';
                    output += '<lable style="font-size: 18px;">' + billdate + '</lable> </center>';
                    output += '<div style="margin-top: -62px;"> <lable style="font-size: 17px;"> &nbsp;&nbsp;&nbsp; Area&nbsp;&nbsp;: ' + area + '</lable> <br>';
                    output += '<label style="font-size: 17px;"> &nbsp;&nbsp;&nbsp; Town : ' + town + '</lable> <br>';
                    output += '<label style="font-size: 17px;"> &nbsp;&nbsp;&nbsp; Route: ' + route + '</lable> </div> <br>';
                    output += '<table id="table"><tr>';
                    output += '<th class="left"> Bill Number </th>';
                    output += '<th> Seq No. </th>';
                    output += '<th> Acct No. </th>';
                    output += '<th style="width: 16% !important;"> Consumer Name </th>';
                    output += '<th> Type </th>';
                    output += '<th> Pres Read/ <br> Prev Read </th>';
                    output += '<th> KWH Used </th>';
                    output += '<th> Pres Read/ <br> Prev Read </th>';
                    output += '<th> Demand Used </th>';
                    output += '<th> Generation Charges/ <br> Power Act Reduction </th>';
                    output += '<th> FBHC/ <br> Forex Adj </th>';
                    output += '<th> FIT Allowance/ <br> Lifeline Disc/Subs </th>';
                    output += '<th> Trans Sys Charge/ <br> Trans Dem Charge </th>';
                    output += '<th> Line Loss Charge/ <br> SR Citizen Disc </th>';
                    output += '<th> Dist Sys Charge/ <br> Dist Dem Charge </th>';
                    output += '<th> Supp Sys Charge/ <br> Supp Fix Charge </th>';
                    output += '<th> Meter Sys Charge/ <br> Meter Fix Charge </th>';
                    output += '<th> Loan Condo/KWH/ <br> Loan Condo </th>';
                    output += '<th> Miss SPUG/ <br> Miss RED </th>';
                    output += '<th> Env Charges/ <br> Stranded Debt </th>';
                    output += '<th> Eq of Tax & Royalty/ <br> NPC-SCC </th>';
                    output += '<th> MCC Capex/ <br> EVAT </th>';
                    output += '<th> With Arrear/ <br> Arrear Surcharge </th>';
                    output += '<th class="right"> Billed Amount </th>';
                    output += '</tr>';
                    var consName = "";
                    var totalArrears = 0;
                    for(var i in summBill){
                        num += 1;
                        if(num > 0 && num % 19 == 0){
                            output += '</table>';
                            output +='<div class="page-break"></div>';
                            output += '<center><label id="lasuhead"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                            output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                            output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                            output += '<label style="font-size: 18px;"> SUMMARY OF BILLS - ALL CONSUMERS </lable> <br><br>';
                            output += '<lable style="font-size: 18px;">' + billdate + '</label></center> <br><br>';
                            output += '<table id="table"><tr>';
                            output += '<th class="left"> Bill Number </th>';
                            output += '<th> Seq No. </th>';
                            output += '<th> Acct No. </th>';
                            output += '<th style="width: 20% !important;"> Consumer Name </th>';
                            output += '<th> Type </th>';
                            output += '<th> Pres Read/ <br> Prev Read </th>';
                            output += '<th> KWH Used </th>';
                            output += '<th> Pres Read/ <br> Prev Read </th>';
                            output += '<th> Demand Used </th>';
                            output += '<th> Generation Charges/ <br> Power Act Reduction </th>';
                            output += '<th> FBHC/ <br> Forex Adj </th>';
                            output += '<th> FIT Allowance/ <br> Lifeline Disc/Subs </th>';
                            output += '<th> Trans Sys Charge/ <br> Trans Dem Charge </th>';
                            output += '<th> Line Loss Charge/ <br> SR Citizen Disc </th>';
                            output += '<th> Dist Sys Charge/ <br> Dist Dem Charge </th>';
                            output += '<th> Supp Sys Charge/ <br> Supp Fix Charge </th>';
                            output += '<th> Meter Sys Charge/ <br> Meter Fix Charge </th>';
                            output += '<th> Loan Condo/KWH/ <br> Loan Condo </th>';
                            output += '<th> Miss SPUG/ <br> Miss RED </th>';
                            output += '<th> Env Charges/ <br> Stranded Debt </th>';
                            output += '<th> Eq of Tax & Royalty/ <br> NPC-SCC </th>';
                            output += '<th> MCC Capex/ <br> EVAT </th>';
                            output += '<th> With Arrear/ <br> Arrear Surcharge </th>';
                            output += '<th class="right"> Billed Amount </th>';
                            output += '</tr><tr>';
                            output += '<td class="left"> &nbsp;' + summBill[i].Bill_Number + '</td>';
                            output += '<td class="left">' + summBill[i].SEQ + '</td>';

                            var accNo = JSON.stringify(summBill[i].Acc_No);
                            var newAccNo = accNo.slice(6,10);    
                            
                            output += '<td class="left">' + newAccNo + '</td>';

                            consName = summBill[i].Consumer_Name;
                            var max_chars = 25;
                            
                            if(consName.length > max_chars) {
                                consName = consName.substr(0, max_chars);
                            }
                            
                            output += '<td class="left">' + consName + '</td>';
                            output += '<td class="left">' + summBill[i].Type + '</td>';
                            output += '<td class="left">' + summBill[i].Pres_Reading.toLocaleString("en-US") + '<br>' +  summBill[i].Prev_Reading.toLocaleString("en-US") + '</td>';
                            output += '<td class="left">' + summBill[i].KWH_Used + '</td>';
                            output += '<td class="left">' + summBill[i].Pres_DemReading.toLocaleString("en-US") + '<br>' +  summBill[i].Prev_DemReading.toLocaleString("en-US") + '</td>';
                            output += '<td class="left">' + summBill[i].Demand_Used.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].Genaration_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].Power_Act_Reduction.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].FBHC.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].Forex_Adj.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].FIT_ALLOWANCE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].LIFELINE_DISC_SUBS.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].TRANSMN_SYS_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].TRANSMN_DEM_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].LINELOSS_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].SR_CTZN_DISC_SUBS.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].DISTRN_SYS_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].DISTRN_DEM_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].SUPPLY_SYS_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].SUPPLY_FIX_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].METER_SYS_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].METER_FIX_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].LOAN_COND_KWH.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].LOAN_COND_FIX.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].MISSNRY_SPUG.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].MISSNRY_RED.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].ENVROTAL_CHARGES.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].STRANDED_DEBT.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].EQ_TAX_ROYALTY.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].NPC_SCC.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].MCC_CAPEX.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].EVAT.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].W_ARREAR.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].ARREAR_SURCHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left right">' + summBill[i].BILLED_AMOUNT.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '</tr>';
                            totalArrears += summBill[i].W_ARREAR;
                        }
                        else{
                            output += '<tr>';
                            output += '<td class="left"> &nbsp;' + summBill[i].Bill_Number + '</td>';
                            output += '<td class="left">' + summBill[i].SEQ + '</td>';

                            var accNo = JSON.stringify(summBill[i].Acc_No);
                            var newAccNo = accNo.slice(6,10);    
                            
                            output += '<td class="left">' + newAccNo + '</td>';
                            
                            consName = summBill[i].Consumer_Name;
                            var max_chars = 25;
                            
                            if(consName.length > max_chars) {
                                consName = consName.substr(0, max_chars);
                            }
                            
                            output += '<td class="left">' + consName + '</td>';
                            output += '<td class="left">' + summBill[i].Type + '</td>';
                            output += '<td class="left">' + summBill[i].Pres_Reading.toLocaleString("en-US") + '<br>' +  summBill[i].Prev_Reading.toLocaleString("en-US") + '</td>';
                            output += '<td class="left">' + summBill[i].KWH_Used + '</td>';
                            output += '<td class="left">' + summBill[i].Pres_DemReading.toLocaleString("en-US") + '<br>' +  summBill[i].Prev_DemReading.toLocaleString("en-US") + '</td>';
                            output += '<td class="left">' + summBill[i].Demand_Used.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].Genaration_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].Power_Act_Reduction.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].FBHC.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].Forex_Adj.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].FIT_ALLOWANCE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].LIFELINE_DISC_SUBS.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].TRANSMN_SYS_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].TRANSMN_DEM_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].LINELOSS_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].SR_CTZN_DISC_SUBS.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].DISTRN_SYS_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].DISTRN_DEM_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].SUPPLY_SYS_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].SUPPLY_FIX_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].METER_SYS_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].METER_FIX_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].LOAN_COND_KWH.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].LOAN_COND_FIX.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].MISSNRY_SPUG.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].MISSNRY_RED.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].ENVROTAL_CHARGES.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].STRANDED_DEBT.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].EQ_TAX_ROYALTY.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].NPC_SCC.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].MCC_CAPEX.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].EVAT.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left">' + summBill[i].W_ARREAR.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '<br>' + summBill[i].ARREAR_SURCHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '<td class="left right">' + summBill[i].BILLED_AMOUNT.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            output += '</tr>';
                            totalArrears += summBill[i].W_ARREAR;
                        }
                    }
                    //var a = '204.155';
					//var b = parseFloat(a);
					//console.log(b.toFixed(2));
                    
					output += "<tr id='totalRow'> <td class='left' colspan=3> Total Bills Printed " + TotSumBill.Total_Bills_Printed + "</td><td class='left' colspan=3> Totals ==> </td><td>" + TotSumBill.Total_KWH + "&nbsp; </td>";
					output += "<td> </td> <td class='left'>" + TotSumBill.Total_Demand_Used.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + TotSumBill.Total_Genaration_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</br>" + TotSumBill.Total_Power_Act_Reduction.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + TotSumBill.Total_FBHC.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</br>" + TotSumBill.Total_Forex_Adj.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + TotSumBill.Total_FIT_ALLOWANCE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</br>" + TotSumBill.Total_LIFELINE_DISC_SUBS.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + TotSumBill.Total_TRANSMN_SYS_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</br>" + TotSumBill.Total_TRANSMN_DEM_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + TotSumBill.Total_LINELOSS_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</br>" + TotSumBill.Total_SR_CTZN_DISC_SUBS.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + TotSumBill.Total_DISTRN_SYS_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</br>" + TotSumBill.Total_DISTRN_DEM_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + TotSumBill.Total_SUPPLY_SYS_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</br>" + TotSumBill.Total_SUPPLY_FIX_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + TotSumBill.Total_METER_SYS_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</br>" + TotSumBill.Total_METER_FIX_CHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + TotSumBill.Total_LOAN_COND_KWH.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</br>" + TotSumBill.Total_LOAN_COND_FIX.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + TotSumBill.Total_MISSNRY_SPUG.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</br>" + TotSumBill.Total_MISSNRY_RED.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + TotSumBill.Total_ENVROTAL_CHARGES.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</br>" + TotSumBill.Total_STRANDED_DEBT.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + TotSumBill.Total_EQ_TAX_ROYALTY.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</br>" + TotSumBill.Total_NPC_SCC.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + TotSumBill.Total_MCC_CAPEX.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</br>" + TotSumBill.Total_EVAT.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + TotSumBill.Total_W_ARREAR.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</br>" + TotSumBill.Total_ARREAR_SURCHARGE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left right'>" + TotSumBill.Total_BILLED_AMOUNT.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "</tr></table>";
                    console.log(totalArrears);
					output +='<div class="page-break"></div>';
                    consCountTable += "<br><table id='table2'> <tr>";
                    consCountTable += "<td rowspan=2 class='left top bot' style='text-align: center;'> SUMMARY  </td> <td colspan=3 class='left top right' style='text-align: center;'> CONSUMER COUNT </td> <td colspan=3 class='top right' style='text-align: center;'> KWH USED </td> <td style='width: 50px;'> </td>";
                    consCountTable += "<td colspan=3 class='left top bot' style='text-align: center;'> TOTAL BILL AMOUNT </td> <td colspan=3 class='left top bot' style='text-align: center;'> DEMAND </td> <td rowspan=2 class='left top right bot' style='text-align: center;'> Total Energy </td> </tr>"
                    consCountTable += "<tr><td class='left top bot' style='text-align: center;'> &nbsp; Min &nbsp; </td> <td class='left top bot' style='text-align: center;'> &nbsp; Above &nbsp; </td> <td class='left top bot' style='text-align: center;'> &nbsp; Total &nbsp; </td>";
                    consCountTable += "<td class='left top bot' style='text-align: center;'> &nbsp; Min &nbsp; </td> <td class='left top bot' style='text-align: center;'> &nbsp; Above &nbsp; </td> <td class='left right top bot' style='text-align: center;'> &nbsp; Total &nbsp; </td><td> </std>";
                    consCountTable += "<td class='left bot' style='text-align: center;'> &nbsp; Min &nbsp; </td> <td class='left bot'style='text-align: center;'> &nbsp; Above &nbsp; </td> <td class='left bot'style='text-align: center;'> &nbsp; Total &nbsp; </td> <td class='left bot'style='text-align: center;'>";
                    consCountTable +=  "&nbsp; KW &nbsp; </td> <td class='left bot'style='text-align: center;'> &nbsp; Trans &nbsp; </td> <td class='left bot'style='text-align: center;'> &nbsp; Dist &nbsp;   </td></tr>";
                        
                    var kwhmin = 0;
                    var kwhAbove = 0;
                    var kwhTotal = 0;
                    var kwhMin2 = 0;
                    var kwhAbove2 = 0;
                    var kwhTotal2 = 0;
                    var billAmountMin = 0;
                    var billAmountAbove = 0;
                    var billAmountTotal = 0;
                    var billAmountKW = 0;
                    var billAmountTransn = 0;
                    var billAmountDistrn = 0;
                    var totalEnergy = 0;

                    for(var a in consType){
                        consumerType = consType[a].ct_desc;
                        consCountTable += "<tr> <td class='left'> &nbsp;" + consumerType  + "</td>";

                        var kwh = KWHUsed[consumerType];
                        var billAmount = TotBillAmnt[consumerType];

                        if(kwh !== undefined){
                            consCountTable += "<td class='left' style='text-align: center;'>" + kwh.Min + "</td>";
                            consCountTable += "<td class='left' style='text-align: center;'>" + kwh.Above + "</td>";
                            consCountTable += "<td class='left' style='text-align: center;'>" + kwh.Total + "</td>";
                            consCountTable += "<td class='left' style='text-align: center;'>" + kwh.Min2 + "</td>";
                            consCountTable += "<td class='left' style='text-align: center;'> &nbsp; " + kwh.Above2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + " &nbsp; </td>";
                            consCountTable += "<td class='left right' style='text-align: center;'> &nbsp; " + kwh.Total2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + " &nbsp; </td><td> </td>";
                            consCountTable += "<td class='left' style='text-align: center;'>" + billAmount.Min + "</td>";
                            consCountTable += "<td class='left' style='text-align: center;'> &nbsp; " + billAmount.Above.toLocaleString("en-US", { minimumFractionDigits: 2 }) + " &nbsp; </td>";
                            consCountTable += "<td class='left' style='text-align: center;'> &nbsp; " + billAmount.Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + " &nbsp; </td>";
                            consCountTable += "<td class='left' style='text-align: center;'>" + billAmount.KW + "</td>";
                            consCountTable += "<td class='left' style='text-align: center;'>" + billAmount.Transn + "</td>";
                            consCountTable += "<td class='left' style='text-align: center;'>" + billAmount.Distrn + "</td>";
                            consCountTable += "<td class='left right' style='text-align: center;'>" + billAmount.Total_Energy.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            
                            kwhmin += kwh.Min;
                            kwhAbove += kwh.Above;
                            kwhTotal += kwh.Total;
                            kwhMin2 += kwh.Min2;
                            kwhAbove2 += kwh.Above2;
                            kwhTotal2 += kwh.Total2;
                            billAmountMin += billAmount.Min;
                            billAmountAbove += billAmount.Above;
                            billAmountTotal += billAmount.Total;
                            billAmountKW += billAmount.KW;
                            billAmountTransn += billAmount.Transn;
                            billAmountDistrn += billAmount.Distrn; 
                            totalEnergy += billAmount.Total_Energy;
                        } else{
                            consCountTable += "<td class='left' style='text-align: center;'> 0 </td>";
                            consCountTable += "<td class='left' style='text-align: center;'> 0 </td>";
                            consCountTable += "<td class='left' style='text-align: center;'> 0 </td>";
                            consCountTable += "<td class='left' style='text-align: center;'> 0 </td>";
                            consCountTable += "<td class='left' style='text-align: center;'> 0 </td>";
                            consCountTable += "<td class='left right' style='text-align: center;'> 0.00 </td> <td> </td>";
                            consCountTable += "<td class='left' style='text-align: center;'> 0 </td>";
                            consCountTable += "<td class='left' style='text-align: center;'> 0 </td>";
                            consCountTable += "<td class='left' style='text-align: center;'> 0 </td>";
                            consCountTable += "<td class='left' style='text-align: center;'> 0 </td>";
                            consCountTable += "<td class='left' style='text-align: center;'> 0 </td>";
                            consCountTable += "<td class='left' style='text-align: center;'> 0 </td>";
                            consCountTable += "<td class='left right' style='text-align: center;'> 0.00 </td>";
                        }
                        
                        consCountTable += "</tr>";  
                    }
                    consCountTable += "<tr> <td class='left top bot'> &nbsp; Total </td>";
                    consCountTable += "<td class='left top bot' style='text-align: center;'>" + kwhmin + "</td>";
                    consCountTable += "<td class='left top bot' style='text-align: center;'>" + kwhAbove + "</td>";
                    consCountTable += "<td class='left top bot' style='text-align: center;'>" + kwhTotal + "</td>";
                    consCountTable += "<td class='left top bot' style='text-align: center;'>" + kwhMin2 + "</td>";
                    consCountTable += "<td class='left top bot' style='text-align: center;'>" + kwhAbove2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    consCountTable += "<td class='left right top bot' style='text-align: center;'>" + kwhTotal2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td><td></td>";
                    consCountTable += "<td class='left top bot' style='text-align: center;'>" + billAmountMin.toLocaleString("en-US") + "</td>";
                    consCountTable += "<td class='left top bot' style='text-align: center;'>" + billAmountAbove.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    consCountTable += "<td class='left top bot' style='text-align: center;'>" + billAmountTotal.toLocaleString("en-US") + "</td>";
                    consCountTable += "<td class='left top bot' style='text-align: center;'>" + billAmountKW.toLocaleString("en-US") + "</td>";
                    consCountTable += "<td class='left top bot' style='text-align: center;'>" + billAmountTransn.toLocaleString("en-US") + "</td>";
                    consCountTable += "<td class='left top bot' style='text-align: center;'>" + billAmountDistrn.toLocaleString("en-US") + "</td>";
                    consCountTable += "<td class='left right top bot' style='text-align: center;'>" + totalEnergy.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr> </table>";

                    consTypeVat += "<table id='table4'> <tr> <td class='left top bot'> &nbsp; VAT Description </td>";
                    consTypeVat += "<td class='left right top bot'> &nbsp; E-VAT Amount </td></tr> <tr>";
                    consTypeVat += "<tr> <td class='left'> &nbsp; Generation </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Generation.toLocaleString("en-US") + "</td> </tr>"; 
                    consTypeVat += "<tr> <td class='left'> &nbsp; Power Act Red </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Power_Act.toLocaleString("en-US") + "</td> </tr>"; 
                    consTypeVat += "<tr> <td class='left'> &nbsp; Trans Sys </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Transmission_Sys.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> &nbsp; Trans Dem </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Transmission_Dem.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> &nbsp; System Loss </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.System_Loss.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> &nbsp; Dist Sys </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Distribution_Sys.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> &nbsp; Dist Dem </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Distribution_Dem.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> &nbsp; Supp Fix </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Supply_Fix.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> &nbsp; Supp Sys </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Supply_Sys.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> &nbsp; Meter Fix </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Metering_Fix.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> &nbsp; Meter Sys </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Metering_Sys.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> &nbsp; Lifeline Disc </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Lifeline_Disc.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> &nbsp; Load Con KWH </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Loan_Cond.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left bot'> &nbsp; Load Con Fix </td> <td class='left right bot'> &nbsp;" + summBillconsTypeVat.Loan_Cond_Fix.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "</tr><tr> <td class='left bot'> &nbsp; Total</td> <td class='left bot right'> &nbsp;" + data.Summary_Bill_Constype_Total_Vat + "</td></tr> </table>";

                    
                } else {
                    output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                    output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                    output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                    output += '<label style="font-size: 18px;"> SUMMARY OF BILLS - ALL CONSUMERS </lable> <br><br>';
                    output += '<lable style="font-size: 18px;">' + billdate + '</lable> </center>';
                    output += '<div style="margin-top: -62px;"> <lable style="font-size: 17px;"> &nbsp;&nbsp;&nbsp; Area&nbsp;&nbsp;: ' + area + '</lable> <br>';
                    output += '<label style="font-size: 17px;"> &nbsp;&nbsp;&nbsp; Town : ' + town + '</lable> <br>';
                    output += '<label style="font-size: 17px;"> &nbsp;&nbsp;&nbsp; Route: ' + route + '</lable> </div> <br><br>';


                    consCountTable += "<br> <table id='table2' style='font-size: 15px !important;'> <tr>";
                    consCountTable += " <td class='left bot top' rowspan=2> SUMMARY </td> <td colspan=3 class='left top bot'> &nbsp; CONSUMER COUNT &nbsp; </td>";
                    consCountTable += "<td colspan=3 class='left top bot right'> &nbsp; KWH USED </td> <td style='width: 50px;'> </td>";
                    consCountTable += "<td colspan=3 class='left top bot'> &nbsp; TOTAL BILL AMOUNT </td> <td colspan=3 class='left top bot'> &nbsp; DEMAND </td><td class='left top bot right'></td></tr>"
                    consCountTable += "<tr> <td class='left bot'> Min </td> <td class='left bot'> Above </td> <td class='left bot'> Total </td>";
                    consCountTable += "<td class='left bot'> Min </td> <td class='left bot'> Above </td> <td class='left right bot'> Total </td> <td> </td>";
                    consCountTable += "<td class='left bot'> Min </td> <td class='left bot'> Above </td> <td class='left bot'> Total </td>";
                    consCountTable += "<td class='left bot'> KW </td> <td class='left bot'> Trans'n </td> <td class='left bot'> Dist </td> <td  class='left right bot'> Total Energy </td></tr>";
                        
                    var kwhmin = 0;
                    var kwhAbove = 0;
                    var kwhTotal = 0;
                    var kwhMin2 = 0;
                    var kwhAbove2 = 0;
                    var kwhTotal2 = 0;
                    var billAmountMin = 0;
                    var billAmountAbove = 0;
                    var billAmountTotal = 0;
                    var billAmountKW = 0;
                    var billAmountTransn = 0;
                    var billAmountDistrn = 0;
                    var totalEnergy = 0;

                    for(var a in consType){
                        consumerType = consType[a].ct_desc;
                        consCountTable += "<tr> <td class='left' style='text-align: left !important;'> &nbsp;" + consumerType  + "</td>";

                        var kwh = KWHUsed[consumerType];
                        var billAmount = TotBillAmnt[consumerType];

                        if(kwh !== undefined){
                            consCountTable += "<td class='left'>" + kwh.Min.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            consCountTable += "<td class='left'>" + kwh.Above.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            consCountTable += "<td class='left'>" + kwh.Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            consCountTable += "<td class='left'>" + kwh.Min2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            consCountTable += "<td class='left'>" + kwh.Above2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            consCountTable += "<td class='left right'>" + kwh.Total2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> <td> </td>";
                            consCountTable += "<td class='left'>" + billAmount.Min + "</td>";
                            consCountTable += "<td class='left'>" + billAmount.Above.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            consCountTable += "<td class='left'>" + billAmount.Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            consCountTable += "<td class='left'>" + billAmount.KW.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            consCountTable += "<td class='left'>" + billAmount.Transn.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            consCountTable += "<td class='left'>" + billAmount.Distrn.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            consCountTable += "<td class='left right'>" + billAmount.Total_Energy.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            
                            kwhmin += kwh.Min;
                            kwhAbove += kwh.Above;
                            kwhTotal += kwh.Total;
                            kwhMin2 += kwh.Min2;
                            kwhAbove2 += kwh.Above2;
                            kwhTotal2 += kwh.Total2;
                            billAmountMin += billAmount.Min;
                            billAmountAbove += billAmount.Above;
                            billAmountTotal += billAmount.Total;
                            billAmountKW += billAmount.KW;
                            billAmountTransn += billAmount.Transn;
                            billAmountDistrn += billAmount.Distrn; 
                            totalEnergy += billAmount.Total_Energy;
                        } else{
                            consCountTable += "<td class='left'> 0 </td>";
                            consCountTable += "<td class='left'> 0 </td>";
                            consCountTable += "<td class='left'> 0 </td>";
                            consCountTable += "<td class='left'> 0 </td>";
                            consCountTable += "<td class='left'> 0 </td>";
                            consCountTable += "<td class='left right'> 0 </td> <td> </td>";
                            consCountTable += "<td class='left'> 0 </td>";
                            consCountTable += "<td class='left'> 0 </td>";
                            consCountTable += "<td class='left'> 0 </td>";
                            consCountTable += "<td class='left'> 0 </td>";
                            consCountTable += "<td class='left'> 0 </td>";
                            consCountTable += "<td class='left'> 0 </td>";
                            consCountTable += "<td class='left right'> 0.000 </td>";
                        }
                        
                        consCountTable += "</tr>";  
                    }
                    consCountTable += "<tr> <td class='left top bot' style='text-align: left !important;'> &nbsp; Total </td>";
                    consCountTable += "<td class='left top bot'>" + kwhmin.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    consCountTable += "<td class='left top bot'>" + kwhAbove.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    consCountTable += "<td class='left top bot'>" + kwhTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    consCountTable += "<td class='left top bot'>" + kwhMin2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    consCountTable += "<td class='left top bot'>" + kwhAbove2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    consCountTable += "<td class='left top bot right'>" + kwhTotal2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    consCountTable += "<td></td>";
                    consCountTable += "<td class='left top bot'>" + billAmountMin.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    consCountTable += "<td class='left top bot'>" + billAmountAbove.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    consCountTable += "<td class='left top bot'>" + billAmountTotal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    consCountTable += "<td class='left top bot'>" + billAmountKW.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    consCountTable += "<td class='left top bot'>" + billAmountTransn.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    consCountTable += "<td class='left top bot'>" + billAmountDistrn.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    consCountTable += "<td class='left top bot right'>" + totalEnergy.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";

                    consTypeVat += "<table id='table4' style='font-size: 15.2px !important; margin-top: 18px; margin-right: 30px;'> <tr> <td class='left top bot'> &nbsp; VAT Description &nbsp; </td>";
                    consTypeVat += "<td class='left right top bot'> &nbsp; E-VAT Amount &nbsp; </td></tr> <tr>";
                    consTypeVat += "<tr> <td class='left'> Generation </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Generation.toLocaleString("en-US") + "</td> </tr>"; 
                    consTypeVat += "<tr> <td class='left'> Power Act Red </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Power_Act.toLocaleString("en-US") + "</td> </tr>"; 
                    consTypeVat += "<tr> <td class='left'> Trans Sys </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Transmission_Sys.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> Trans Dem </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Transmission_Dem.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> System Loss </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.System_Loss.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> Dist Sys </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Distribution_Sys.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> Dist Dem </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Distribution_Dem.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> Supp Fix </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Supply_Fix.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> Supp Sys </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Supply_Sys.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> Meter Fix </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Metering_Fix.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> Meter Sys </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Metering_Sys.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> Lifeline Disc </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Lifeline_Disc.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left'> Load Con KWH </td> <td class='left right'> &nbsp;" + summBillconsTypeVat.Loan_Cond.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "<tr> <td class='left bot'> Load Con Fix </td> <td class='left right bot'> &nbsp;" + summBillconsTypeVat.Loan_Cond_Fix.toLocaleString("en-US") + "</td></tr>"; 
                    consTypeVat += "</tr><tr> <td class='left bot'> Total</td> <td class='left bot right'> &nbsp;" + data.Summary_Bill_Constype_Total_Vat.toLocaleString("en-US") + "</td></tr> </table>";
                }
            }
            else if(xhr.status == 422){
                alert('No Bills Found');
                // window.close();
            }

            document.querySelector('#consTypeVat').innerHTML = consTypeVat;
            document.querySelector('#printBody').innerHTML = output;
            document.querySelector('#consCount').innerHTML = consCountTable;
        }
    }

    function getDate() {
        var date = JSON.stringify(billPeriod);
        var dateToSpell = "";
        var month = date.slice(6, 8);
        var year = date.slice(1, 5);

        if(month == "01") {
            dateToSpell = "January, " + year;
        } else if(month == "02") {
            dateToSpell = "February, " + year;
        } else if(month == "03") {
            dateToSpell = "March, " + year;
        } else if(month == "04") {
            dateToSpell = "April, " + year;
        } else if(month == "05") {
            dateToSpell = "May, " + year;
        } else if(month == "06") {
            dateToSpell = "June, " + year;
        } else if(month == "07") {
            dateToSpell = "July, " + year;
        } else if(month == "08") {
            dateToSpell = "August, " + year;
        } else if(month == "09") {
            dateToSpell = "September, " + year;
        } else if(month == "10") {
            dateToSpell = "October, " + year;
        } else if(month == "11") {
            dateToSpell = "November, " + year;
        } else if(month == "12") {
            dateToSpell = "December, " + year;
        }

        billdate = dateToSpell;
    }
</script>