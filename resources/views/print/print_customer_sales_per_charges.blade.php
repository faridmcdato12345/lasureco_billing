<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Customer Sales per Charge  </title>

</head>
<style media="print">
    @page {
        size: landscape;
        margin-top: 0mm;
    }
    #charges {
        width: 28% !important;
    }
    table {
        font-size: 13px !important;
        margin: auto;
    }
	#mcc {
		border-bottom: 0.75px dashed !important;
	}
    #LRS {
        border-top: 0.75px dashed !important;
    }
    #breakline {
        display: block !important;
        height: 80px !important;
    }
</style>
<style>
    #charges {
        height: 50px; 
        width: 24%;
    }
    .page-break {
        page-break-after: always;
    }
    body {
        font-family: Consolas;
    }
    table {
        font-size: 15px;
        margin: auto;
    }
    td {
        text-align: center;
    }
    .left {
        border-left: 0.75px dashed;
    }
    .right {
        border-right: 0.75px dashed;
    }
    .bottom {
        border-bottom: 0.75px dashed;
    }
    .top {
        border-top: 0.75px dashed;
    }
    #breakline {
        display: none;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
</div>
</body>
</html>

<script>
    var toSend = new Object();

    if(localStorage !== "") {
        var param = JSON.parse(localStorage.getItem("data"));

        var billPeriod = param.date;
        var billdate = "";
    }

    function getData(){
        var xhr = new XMLHttpRequest();
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        console.log(token);
        var salesPerCharge = "{{route('report.sales.customer.per.charge',['date'=>':date'])}}";
        var newSalesPerCharge = salesPerCharge.replace(':date', billPeriod);
        xhr.open('GET', newSalesPerCharge, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send();

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var allConsumer = data.ALL_Consumer;
                var nonLifeliner = data.Non_lifeliner;
                var Lifeliner = data.Lifeliner;
				console.table(data);
                var output = "";
                
                output += '<center><label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> CUSTOMER SALES PER CHARGES </label><br>';
                output += '<label style="font-size: 20px;">' + billdate + '</label></center><br>';
                output += "<table>";
                    
                output += "<tr> <td class='left top bottom right' id='charges'> Charges </td>";
                for(var x in allConsumer){
                    output += "<td class='right top bottom'> &nbsp;" + x + "</td>";
                }
                output += "<td class='top bottom'> &nbsp; RESIDENTIAL Lifeliner &nbsp; </td>"
                output += "<td class='left top bottom right'> &nbsp; RESIDENTIAL Non-lifeliner &nbsp; </td>";
                output += "</tr>";
                
                output += "<tr><td style='text-align: left;' class='left'> &nbsp; <b> Generation Charges </b> </td>";
                for(var i in allConsumer){
                    output += "<td class='left'> </td>";
                }
                output += "<td class='left'> </td><td class='left  right'> </td></tr><tr>";
                output += "<tr><td style='text-align: left;' class='left'> &nbsp; Generation </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Generation_System_Charge == undefined){
                        output += "<td class='left'> 0.00 </td>";    
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Generation_System_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Generation_System_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Generation_System_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Power Action Reduction </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Power_Act_Red_Vat == undefined){
                        output += "<td class='left'> 0.00 </td>";    
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Power_Act_Reduction.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Power_Act_Reduction.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Power_Act_Reduction.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Franchise & Benefits to Host </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Franchise_Benefits_To_Host === undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Franchise_Benefits_To_Host.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Franchise_Benefits_To_Host.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Franchise_Benefits_To_Host.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; FOREX Adjustment Charge </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].FOREX_Adjustment_Charge == undefined) {
                        output += "<td class='left'> 0.00 </td>";    
                    } else {
                        output += "<td class='left'>" + allConsumer[i].FOREX_Adjustment_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.FOREX_Adjustment_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.FOREX_Adjustment_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr>";
                output += "<tr><td style='text-align: left;' class='left'> &nbsp; <b> Transmission System Charges </b> </td>";
                for(var i in allConsumer){
                    output += "<td class='left'> </td>";
                }
                output += "<td class='left'> </td><td class='left  right'> </td></tr><tr>";
                output += "<tr><td style='text-align: left;' class='left'> &nbsp; Transmission System </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Transmission_System_Charge == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Transmission_System_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Transmission_System_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Transmission_System_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
				output += "<td class='left'><td class='left right'> </td></tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Demand Charge </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Trans_Demand_Charge == undefined) {
                        output += "<td class='left'> 0.00 </td>";    
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Trans_Demand_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";    
                    }
                }
                output += "<td class='left'>" + Lifeliner.Trans_Demand_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Trans_Demand_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; System Loss Charge </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].System_Loss_Charge == undefined) {
                        output += "<td class='left'> 0.00 </td>";    
                    } else {
                        output += "<td class='left'>" + allConsumer[i].System_Loss_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";    
                    }
                }
                output += "<td class='left'>" + Lifeliner.System_Loss_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.System_Loss_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'><b> &nbsp; Distribution Charges </td>";
                for(var i in allConsumer){
                    output += "<td class='left'> </td>";
                }
                output += "<td class='left'> </td> <td class='left right'> </td></tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Demand Charge </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Dist_Demand_Charge == undefined) {
                        output += "<td class='left'> 0.00 </td>";    
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Dist_Demand_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Dist_Demand_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Dist_Demand_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Distribution System Charge </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Distribution_System_Charge == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Distribution_System_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Distribution_System_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Distribution_System_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Supply System Fixed Charge </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Supply_System_Fixed_Charge == undefined){
                        output += "<td class='left'> 0.00 </td>";    
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Supply_System_Fixed_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Supply_System_Fixed_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Supply_System_Fixed_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Supply System Charge </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Supply_System_Charge == undefined){
                        output += "<td class='left'> 0.00 </td>";    
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Supply_System_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Supply_System_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Supply_System_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Retail Customer Meter Charge </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Retail_Customer_Meter_Charge == undefined) {
                        output += "<td class='left'> 0.00 </td>";    
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Retail_Customer_Meter_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Retail_Customer_Meter_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Retail_Customer_Meter_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Retail Customer Meter Fixed </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Retail_Customer_Mtr_Fixed_Charge == undefined) {
                        output += "<td class='left'> 0.00 </td>";    
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Retail_Customer_Mtr_Fixed_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";     
                    }
                }
                output += "<td class='left'>" + Lifeliner.Retail_Customer_Mtr_Fixed_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Retail_Customer_Mtr_Fixed_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> <b> &nbsp; Universal Charges </td>";
                for(var i in allConsumer){
                    output += "<td class='left'> </td>";
                }
                output += "<td class='left'> </td> <td class='left right'> </td></tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; UC - SPUG </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].UC_SPUG == undefined) {
                        output += "<td> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].UC_SPUG.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";    
                    }
                }
                output += "<td class='left'>" + Lifeliner.UC_SPUG.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.UC_SPUG.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; UC - RED Cash Incentive </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].UC_RED_Cash_Incentive == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].UC_RED_Cash_Incentive.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.UC_RED_Cash_Incentive.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.UC_RED_Cash_Incentive.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; UC - Environmental Charge </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].UC_Environmental_Charge == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].UC_Environmental_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";    
                    }
                }
                output += "<td class='left'>" + Lifeliner.UC_Environmental_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.UC_Environmental_Charge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; UC - Equal. of Taxes & Royalties </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].UC_Equal_of_Taxes_Royalties == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].UC_Equal_of_Taxes_Royalties.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.UC_Equal_of_Taxes_Royalties.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.UC_Equal_of_Taxes_Royalties.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; UC - NPC Stranded Con Cost </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].UC_NPC_Stranded_Contract_Cost == undefined) {
                        output += "<td> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].UC_NPC_Stranded_Contract_Cost.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.UC_NPC_Stranded_Contract_Cost.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.UC_NPC_Stranded_Contract_Cost.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; UC - NPC Stranded Debt Cost </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].UC_NPC_Stranded_Debt_Cost == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].UC_NPC_Stranded_Debt_Cost.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.UC_NPC_Stranded_Debt_Cost.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.UC_NPC_Stranded_Debt_Cost.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> <b> &nbsp; Other Charges </td>";
                for(var i in allConsumer){
                    output += "<td class='left'> </td>";
                }
                output += "<td class='left'> </td><td class='left right'> </td></tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Inter Class Cross Subsidy </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Inter_Class_Cross_Subsidy == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Inter_Class_Cross_Subsidy.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Inter_Class_Cross_Subsidy.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Inter_Class_Cross_Subsidy.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Int Class Cross Subs Adj. </td>";
                for(var i in allConsumer){
                    output += "<td class='left'> 0.00 </td>";
                }
                output += "<td class='left'> 0.00 </td> <td class='left right'> 0.00 </td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left' id='mcc'> &nbsp; Members Contributed Capital </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Members_Contributed_Capital == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left' id='mcc'>" + allConsumer[i].Members_Contributed_Capital.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left' id='mcc'>" + Lifeliner.Members_Contributed_Capital.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right' id='mcc'>" + nonLifeliner.Members_Contributed_Capital.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<tr colspan=8 id='breakline'> </tr>";
                output += "<td style='text-align: left;' class='left' id='LRS'> &nbsp; Lifeline Rate Subsidy </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Lifeline_Rate_Subsidy == undefined){
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left' id='LRS'>" + allConsumer[i].Lifeline_Rate_Subsidy.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";    
                    }
                }
                output += "<td class='left' id='LRS'>" + Lifeliner.Lifeline_Rate_Subsidy.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right' id='LRS'>" + nonLifeliner.Lifeline_Rate_Subsidy.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Lifeline Rate (Discount) </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Lifeline_Rate_Discount == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Lifeline_Rate_Discount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";    
                    }
                }
                output += "<td class='left'>" + Lifeliner.Lifeline_Rate_Discount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Lifeline_Rate_Discount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Transformer Losses </td>";
                for(var i in allConsumer){
                    output += "<td class='left'> 0.00 </td>";
                }
                output += "<td class='left'> 0.00 </td> <td class='left right'> 0.00 </td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Backbill/Rebates/Refund </td>";
                for(var i in allConsumer){
                    output += "<td class='left'> 0.00 </td>";
                }
                output += "<td class='left'> 0.00 </td> <td class='left right'> 0.00 </td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Senior Citizen Subsidy </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Senior_Citizen_Subsidy == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Senior_Citizen_Subsidy.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Senior_Citizen_Subsidy.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Senior_Citizen_Subsidy.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Senior Citizen Subsidy (Discount) </td>";
                for(var i in allConsumer){
                    output += "<td class='left'> 0.00 </td>";
                }
                output += "<td class='left'> 0.00 </td> <td class='left right'> 0.00 </td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Feed-in Tariff Allowance </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Feed_in_Tariff_Allowance == undefined){
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Feed_in_Tariff_Allowance.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Feed_in_Tariff_Allowance.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Feed_in_Tariff_Allowance.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Prompt Payment Discount Adj. </td>";
                for(var i in allConsumer){
                    output += "<td class='left'> 0.00 </td>";
                }
                output += "<td class='left'> 0.00 </td><td class='left right'> 0.00 </td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Prompt Payment Discount Adj. </td>";
                for(var i in allConsumer){
                    output += "<td class='left'> 0.00 </td>";
                }
                output += "<td class='left'> 0.00 </td><td class='left right'> 0.00 </td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp;<b> Value Added Tax </td>";
                for(var i in allConsumer){
                    output += "<td class='left'> </td>";
                }
                output += "<td class='left'> </td><td class='left right'> </td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Generation </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Generation_Vat == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Generation_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Generation_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Generation_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Transmission System </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Transmission_System_Vat == undefined){
                        output += "<td class='left'> 0.00 </td>";    
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Transmission_System_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";    
                    }
                }
                output += "<td class='left'>" + Lifeliner.Transmission_System_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Transmission_System_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Transmission Demand </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Transmission_Demand_Vat == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Transmission_Demand_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Transmission_Demand_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Transmission_Demand_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; System Loss </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Sys_Loss_Vat == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Sys_Loss_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.System_Loss_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.System_Loss_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Distribution System </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Distribution_System_Vat == undefined){
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Distribution_System_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Distribution_System_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Distribution_System_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Distribution Demand </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Distribution_Demand_Vat == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Distribution_Demand_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 })
                    }
                }
                output += "<td class='left'>" + Lifeliner.Distribution_Demand_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Distribution_Demand_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Power Act Reduction </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Power_Act_Red_Vat == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Power_Act_Red_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Power_Act_Red_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Power_Act_Red_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Meter Fix </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Meter_Fix_Vat == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Meter_Fix_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";    
                    }
                }
                output += "<td class='left'>" + Lifeliner.Meter_Fix_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Meter_Fix_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Meter System </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Meter_Sys_Vat == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Meter_Sys_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Meter_Sys_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Meter_Sys_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Supply System </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Supply_Sys_Vat == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Supply_Sys_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Supply_Sys_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Supply_Sys_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Supply Fix </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Supply_Fix_Vat == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Supply_Fix_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Supply_Fix_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Supply_Fix_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Lifeline Disc. Sub. </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].lfln_disc_subs_vat == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].lfln_disc_subs_vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.lfln_disc_subs_vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.lfln_disc_subs_vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Others </td>";
                for(var i in allConsumer){
                    output += "<td class='left'> 0.00 </td>";
                }
                output += "<td class='left'> 0.00 </td><td class='left right'> 0.00 </td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left'> &nbsp; Loan Condonation KWH </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Loan_Condonation_KWH_Vat == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left'>" + allConsumer[i].Loan_Condonation_KWH_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    }
                }
                output += "<td class='left'>" + Lifeliner.Loan_Condonation_KWH_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + nonLifeliner.Loan_Condonation_KWH_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr><tr>";
                output += "<td style='text-align: left;' class='left bottom'> &nbsp; Loan Condonation Fix </td>";
                for(var i in allConsumer){
                    if(allConsumer[i].Loan_Condonation_Fix_Vat == undefined) {
                        output += "<td class='left'> 0.00 </td>";
                    } else {
                        output += "<td class='left bottom'>" + allConsumer[i].Loan_Condonation_Fix_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";   
                    }
                }
                output += "<td class='left bottom'>" + Lifeliner.Loan_Condonation_Fix_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left bottom right'>" + nonLifeliner.Loan_Condonation_Fix_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr>";
                output += "</table>";
                
                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Sales Found');
                window.close();
            }
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