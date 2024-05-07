<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Billing Rates </title>

</head>
<style media="print">
    @page {
      size: 8.5in 13in landscape;
      margin: 1mm;
      /* margin-left: -5mm; */
      margin-top: -0.5mm;
    }
    body {
        font-size: 7px !important;
    }
    th {
        height: 10px !important;
    }
    td {
        height: 5px !important;
    }
    #ECName {
        font-size: 18px !important;
    }
    #BRTxt {
        font-size: 15px !important;
    }
    #addTxt {
        font-size: 15px !important;
    }
    .firstTD {
        width: 22% !important;
    }
    .secondTD {
        width: 22% !important;
    }
</style>
<style>
    .page-break {
        page-break-after: always;
    }
    body {
        font-family: Calibri;
    }
    table {
        font-size: 15px;
        width: 95%;
        margin: auto;
    }
    th {
        height: 50px;
        border-right: 0.75px dashed;
    }
    td {
        border-right: 0.75px dashed;
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
    #ECName {
        font-size: 24px; 
        font-weight: bold;
    }
    #BRTxt {
        font-size: 20px; 
        font-weight: bold;
    }
    #addTxt {
        font-size: 18px;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
</body>
</html>

<script>
    var param = JSON.parse(localStorage.getItem("data"));
    var billPeriod = param.billPeriod;

    var toSend = new Object();
    var billdate = "";

    function getData(){
        var request = new XMLHttpRequest();
        var route = "{{route('get.br.bp',['bp'=>':bp'])}}"
        request.open('GET', route.replace(':bp', billPeriod), true);
        request.send();

        request.onload = function(){
            getDate();
            if(request.status == 200){ 
                var  response = JSON.parse(this.responseText);
                var rates = response.Result; 
                
                var output = "";
                output += '<center> <label id="ECName"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label id="addTxt"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                output += '<label id="BRTxt"> BILLING RATES </label><br>';
                output += '<label style="font-size: 17px;"> For the month of ' + billdate + '</label>';
                output += '<table style="width:100%;border-bottom:1px dashed" >'; 
                output += '<tr> <th class="top bottom left firstTD"> &nbsp; Charges </th>';

                for(var i in rates) {
                    output += "<th class='top bottom'> &nbsp; " + rates[i].ct_code + "</th>";
                }
                output += "</tr> <tr> <td class='left' style='width: 18%;'> &nbsp; <b> Generation Charges </b> </td>"; 
                for(var i in rates) {
                    output += "<td> </td>";
                }
                output += "</tr> <tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Generation Charge </td>";
                for(var i in rates) {
                    if(rates[i].br_gensys_rate !== null) {
                        output += "<td> &nbsp;" + rates[i].br_gensys_rate + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                    
                }
                output += "</tr> <tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Power Act Reduction </td>";
                for(var i in rates) {
                    if(rates[i].br_par_rate !== null) {
                        output += "<td> &nbsp;" + rates[i].br_par_rate + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                    
                }
                output += "</tr> <tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Franchise & Benefits to Host </td>";
                for(var i in rates) {
                    if(rates[i].br_fbhc_rate !== null) {
                        output += "<td> &nbsp;" + rates[i].br_fbhc_rate + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "</tr> <tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; FOREX Adjustment Charge </td>";
                for(var i in rates) {
                    if(rates[i].br_forex_rate !== null) {
                        output += "<td> &nbsp;" + rates[i].br_forex_rate + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "</tr> <tr> <td class='left'> &nbsp; <b> Transmission Charges </b> </td>";
                for(var i in rates) {
                    output += "<td> </td>";
                }
                output += "</tr> <tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Demand Charge </td>";
                for(var i in rates) {
                    if(rates[i].br_transdem_rate !== null) {
                        output += "<td> &nbsp;" + rates[i].br_transdem_rate + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                    
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Transmission System Charge </td>";
                for(var i in rates) {
                    if(rates[i].br_transsys_rate !== null){
                        output += "<td> &nbsp;" + rates[i].br_transsys_rate + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; System Loss Charge </td>";
                for(var i in rates) {
                    if(rates[i].br_sysloss_rate !== null){
                        output += "<td> &nbsp;" + rates[i].br_sysloss_rate + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "</tr> <tr> <td class='left'> &nbsp; <b> Distribution Charges </b> </td>";
                for(var i in rates) {
                    output += "<td> </td>";
                }
                output += "</tr> <tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Demand Charge </td>";
                for(var i in rates) {
                    if(rates[i].br_distdem_rate !== null){
                        output += "<td> &nbsp;" + rates[i].br_distdem_rate + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Distribution System Charge </td>";
                for(var i in rates) {
                    if(rates[i].br_distsys_rate !== null) {
                        output += "<td> &nbsp;" + rates[i].br_distsys_rate + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                    
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Supply System Fixed Charge </td>";
                for(var i in rates) {
                    if(rates[i].br_suprtlcust_fixed !== null) {
                        output += "<td> &nbsp;" + rates[i].br_suprtlcust_fixed + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Supply System Charge </td>";
                for(var i in rates) {
                    if(rates[i].br_supsys_rate !== null) {
                        output += "<td> &nbsp;" + rates[i].br_supsys_rate + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                    
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Meter System Fixed Charge </td>";
                for(var i in rates) {
                    if(rates[i].br_mtrrtlcust_fixed !== null){
                        output += "<td> &nbsp;" + rates[i].br_mtrrtlcust_fixed + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                    
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Metering System Charge </td>";
                for(var i in rates) {
                    if(rates[i].br_mtrsys_rate !== null){
                        output += "<td> &nbsp;" + rates[i].br_mtrsys_rate + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                    
                }
                output += "<tr> <td class='left'> &nbsp; <b> Universal Charges </b> </td>";
                for(var i in rates) {
                    output += "<td> </td>";
                }
                output += "</tr> <tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; UC-SPUG </td>";
                for(var i in rates) {
                    if(rates[i].br_uc4_miss_rate_spu !== null){
                        output += "<td> &nbsp;" + rates[i].br_uc4_miss_rate_spu + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                    
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; UC-RED </td>";
                for(var i in rates) {
                    if(rates[i].br_uc4_miss_rate_red !== null){
                        output += "<td> &nbsp;" + rates[i].br_uc4_miss_rate_red + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                    
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; UC-Environmental Charges </td>";
                for(var i in rates) {
                    if(rates[i].br_uc6_envi_rate !== null){
                        output += "<td> &nbsp;" + rates[i].br_uc6_envi_rate + "</td>";
                    } else{
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; UC-Equal of Taxes & Royalties </td>";
                for(var i in rates) {
                    if(rates[i].br_uc5_equal_rate !== null){
                        output += "<td> &nbsp;" + rates[i].br_uc5_equal_rate + "</td>";
                    } else{
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                    
                }
                output += '</table><div class="page-break"></div>';
                output += '<div style="padding:15px;"></div>';
                output += '<table style="width:100%">';
                output += '<tr> <th style="width:18%;" class="top bottom left secondTD"> &nbsp; Charges </th>';

                for(var i in rates) {
                    output += "<th class='top bottom'> &nbsp; " + rates[i].ct_code + "</th>";
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; UC-NPC Stranded Contract Cost </td>";
                for(var i in rates) {
                    if(rates[i].br_uc2_npccon_rate !== null){
                        output += "<td> &nbsp;" + rates[i].br_uc2_npccon_rate + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; UC-NPC Stranded Debt </td>";
                for(var i in rates) {
                    if(rates[i].br_uc1_npcdebt_rate !== null){
                        output += "<td> &nbsp;" + rates[i].br_uc1_npcdebt_rate + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Feed-in Tariff Allowance </td>";
                for(var i in rates) {
                    if(rates[i].br_fit_all !== null){
                        output += "<td> &nbsp;" + rates[i].br_fit_all + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                
                
                output += "<tr> <td class='left'> &nbsp; <b> Other Chrages </b> </td>";
                for(var i in rates) {
                    output += "<td> </td>";
                }
                output += "</tr> <tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Inter Class Cross Subsidy </td>";
                for(var i in rates) {
                    if(rates[i].br_intrclscrssubrte !== null){
                        output += "<td> &nbsp;" + rates[i].br_intrclscrssubrte + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                    
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Inter Class Cross Subsidy Adj. </td>";
                for(var i in rates) {
                    output += "<td> &nbsp; 0 </td>";
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Members Contributed Capital </td>";
                for(var i in rates) {
                    if(rates[i].br_capex_rate !== null){
                        output += "<td> &nbsp;" + rates[i].br_capex_rate + "</td>";    
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Lifeline Rate Subsidy </td>";
                for(var i in rates) {
                    if(rates[i].br_lfln_subs_rate !== null){
                        output += "<td> &nbsp;" + rates[i].br_lfln_subs_rate + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Senior Citizen Subsidy </td>";
                for(var i in rates) {
                    if(rates[i].br_sc_subs_rate !== null){
                        output += "<td> &nbsp;" + rates[i].br_sc_subs_rate + "</td>";    
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Senior Citizen (Discount) </td>";
                for(var i in rates) {
                    output += "<td> </td>";
                }
                output += "<tr> <td class='left'> &nbsp; <b> Value Added Tax </b> </td>";
                for(var i in rates) {
                    output += "<td> </td>";
                }
                output += "</tr> <tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Generation </td>";
                for(var i in rates) {
                    if(rates[i].br_vat_gen !== null){
                        output += "<td> &nbsp;" + rates[i].br_vat_gen + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "</tr> <tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Power Act Reduction </td>";
                for(var i in rates) {
                    if(rates[i].br_vat_par !== null){
                        output += "<td> &nbsp;" + rates[i].br_vat_par + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Transmission System </td>";
                for(var i in rates) {
                    if(rates[i].br_vat_trans !== null){
                        output += "<td> &nbsp;" + rates[i].br_vat_trans + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                    
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Transmission Demand </td>";
                for(var i in rates) {
                    if(rates[i].br_vat_transdem !== null){
                        output += "<td> &nbsp;" + rates[i].br_vat_transdem + "</td>";    
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                    
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Distribution System </td>";
                for(var i in rates) {
                    if(rates[i].br_vat_distrib_kwh !== null){
                        output += "<td> &nbsp;" + rates[i].br_vat_distrib_kwh + "</td>";    
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";   
                    }
                    
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Distribution Demand </td>";
                for(var i in rates) {
                    if(rates[i].br_vat_distdem !== null){
                        output += "<td> &nbsp;" + rates[i].br_vat_distdem + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                    
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; System Loss </td>";
                for(var i in rates) {
                    if(rates[i].br_vat_systloss !== null){
                        output += "<td> &nbsp;" + rates[i].br_vat_systloss + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Meter Fix </td>";
                for(var i in rates) {
                    if(rates[i].br_vat_mtr_fix !== null){
                        output += "<td> &nbsp;" + rates[i].br_vat_mtr_fix + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Meter System </td>";
                for(var i in rates) {
                    if(rates[i].br_vat_metersys !== null){
                        output += "<td> &nbsp;" + rates[i].br_vat_metersys + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Lfln Disc./Subs. </td>";
                for(var i in rates) {
                    if(rates[i].br_vat_lfln !== null){
                        output += "<td> &nbsp;" + rates[i].br_vat_lfln + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Supply Fix </td>";
                for(var i in rates) {
                    if(rates[i].br_vat_supfix !== null){
                        output += "<td> &nbsp;" + rates[i].br_vat_supfix + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Supply System </td>";
                for(var i in rates) {
                    if(rates[i].br_vat_supsys !== null){
                        output += "<td> &nbsp;" + rates[i].br_vat_supsys + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Loan Condonation KWH </td>";
                for(var i in rates) {
                    if(rates[i].br_vat_loancondo !== null) {
                        output += "<td> &nbsp;" + rates[i].br_vat_loancondo + "</td>";
                    } else {
                        output += "<td> &nbsp; 0.0000 </td>";
                    }
                }
                output += "<tr> <td class='bottom left'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Loan Condonation Fix </td>";
                for(var i in rates) {
                    if(rates[i].br_vat_loancondofix !== null){
                        output += "<td class='bottom'> &nbsp;" + rates[i].br_vat_loancondofix + "</td>";
                    } else {
                        output += "<td class='bottom'> &nbsp; 0.0000 </td>";
                    }
                }
                output += "</tr> </table>";
                document.querySelector("#printBody").innerHTML = output;
            }
        }   
    }

    function getDate() {
        var date = JSON.stringify(billPeriod);
        var dateToSpell = "";
        var month = date.slice(6, 8);
        var year = date.slice(1, 5);
        
        if(month == "01") {
            dateToSpell = "January " + year;
        } else if(month == "02") {
            dateToSpell = "February " + year;
        } else if(month == "03") {
            dateToSpell = "March " + year;
        } else if(month == "04") {
            dateToSpell = "April " + year;
        } else if(month == "05") {
            dateToSpell = "May " + year;
        } else if(month == "06") {
            dateToSpell = "June " + year;
        } else if(month == "07") {
            dateToSpell = "July " + year;
        } else if(month == "08") {
            dateToSpell = "August " + year;
        } else if(month == "09") {
            dateToSpell = "September " + year;
        } else if(month == "10") {
            dateToSpell = "October " + year;
        } else if(month == "11") {
            dateToSpell = "November " + year;
        } else if(month == "12") {
            dateToSpell = "December " + year;
        }

        billdate = dateToSpell;
    }
</script>