<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Summary of Energy Sales per Consumer Type </title>

</head>
<style media="print">
    @page {
      size: auto;
      margin: 2mm;
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
        font-size: 15px;
        margin: auto;
        border-right: 0.75px dashed;
        border-bottom: 0.75px dashed;
    }
    th {
        height: 60px;
    }
    td {
        text-align: center;
    }
    .right {
        border-right: 0.75px dashed;
        text-align: center;
    }
    .left {
        border-left: 0.75px dashed;
        text-align: center;
    }
    .bot {
        border-bottom: 0.75px dashed;
        text-align: center;
    }
    .top {
        border-top: 0.75px dashed;
        text-align: center;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    
    toSend.location = param.location;
    toSend.date = param.date;

    var billPeriod = param.date;

    function getData(){
        var xhr = new XMLHttpRequest();

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var summSales = "{{route('report.energy.sales.summary')}}";
        xhr.open('POST', summSales, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var details = data.Details;
                var Totals = data.Totals;

                console.log(data);
                var output = "";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> PRINT SUMMARY OF ENERGY SALES PER CONSUMER TYPE </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br>';
                output += '<table id="table"><tr>';
                output += '<td rowspan=2 class="left top bot"> Cons Type </td>';
                output += '<td rowspan=2 class="left top bot"> Count </td>';
                output += '<td colspan=2 class="left top"> DEMAND </td>';
                output += '<td colspan=2 class="left top"> ENERGY </td>';
                output += '<td colspan=4 class="left top"> GENERATION SYSTEM CHARGES </td>';
                output += '<td colspan=2 class="left top"> TRANSMISSION SYSTEM CHARGES </td>';
                output += '<td class="left top"> SYSTEM LOSS CHARGES </td>';
                output += '<td colspan=6 class="left top"> DISTRIBUTION CHARGES </td>';
                output += '<td colspan=7 class="left top"> OTHER CHARGES </td>';
                output += '</tr>';
                output += '<th class="left top bot"> KW Sold </th>';
                output += '<th class="left top bot"> Amount </th>';
                output += '<th class="left top bot"> KWH-Used </th>';
                output += '<th class="left top bot"> Bill Amount </th>';
                output += '<th class="left top bot"> Gen. Sys. </th>';
                output += '<th class="left top bot"> PAR </th>';
                output += '<th class="left top bot"> FBHC </th>';
                output += '<th class="left top bot"> FOREX </th>';
                output += '<th class="left top bot"> Trans. Sys. </th>';
                output += '<th class="left top bot"> Trans. Demand </th>';
                output += '<th class="left top bot"> SL </th>';
                output += '<th class="left top bot"> Dist. Sys. </th>';
                output += '<th class="left top bot"> Dist. Demand </th>';
                output += '<th class="left top bot"> Supply Fix </th>';
                output += '<th class="left top bot"> Supply Sys. </th>';
                output += '<th class="left top bot"> Met. Fix </th>';
                output += '<th class="left top bot"> Met. Sys. </th>';
                output += '<th class="left top bot"> Lifln Disc </th>';
                output += '<th class="left top bot"> Lifln Sub </th>';
                output += '<th class="left top bot"> Sen.Cit. <br> Disc. <br> Subs. </th>';
                output += '<th class="left top bot"> ICCS </th>';
                output += '<th class="left top bot"> MCC Capex </th>';
                output += '<th class="left top bot"> Loan Cond. </th>';
                output += '<th class="left top bot"> Loan Cond. Fix </th>';
                output += '</tr>';
                
                for(var int in details){
                    if(int == "STREETLIGHTS") {
                        output += "<tr> <td class='left' style='text-align: left !important;'> &nbsp; STRLGHTS</td>";
                    } else if(int == "RESIDENTIAL") {
                        output += "<tr> <td class='left' style='text-align: left !important;'> &nbsp; RSDNTLS</td>";
                    } else if(int == "COMMERCIAL") {
                        output += "<tr> <td class='left' style='text-align: left !important;'> &nbsp; C0MRCL</td>";
                    } else if(int == "INDUSTRIAL") {
                        output += "<tr> <td class='left' style='text-align: left !important;'> &nbsp; INDSTRL</td>";
                    } 
                    else {
                        output += "<tr> <td class='left' style='text-align: left !important;'> &nbsp;" + int + "</td>";
                    }

                    output += "<td class='left'>" + details[int].COUNT + "</td>";
                    output += "<td class='left'>" + details[int].KW_SOLD + "</td>";
                    output += "<td class='left'>" + details[int].DEM_AMOUNT + "</td>";
                    output += "<td class='left'>" + details[int].KWH_USED + "</td>";
                    output += "<td class='left'>" + details[int].BILL_AMOUNT + "</td>";
                    output += "<td class='left'>" + details[int].GENSYS + "</td>";
                    output += "<td class='left'>" + details[int].PAR + "</td>";
                    output += "<td class='left'>" + details[int].FBHC + "</td>";
                    output += "<td class='left'>" + details[int].FOREX + "</td>";
                    output += "<td class='left'>" + details[int].TRANSYS + "</td>";
                    output += "<td class='left'>" + details[int].TRANSYS_DEMAND + "</td>";
                    output += "<td class='left'>" + details[int].SL + "</td>";
                    output += "<td class='left'>" + details[int].DISTSYS + "</td>";
                    output += "<td class='left'>" + details[int].DIST_DEMAND + "</td>";
                    output += "<td class='left'>" + details[int].SUPPLYFIX + "</td>";
                    output += "<td class='left'>" + details[int].SUPPLYSYS + "</td>";
                    output += "<td class='left'>" + details[int].METFIX + "</td>";
                    output += "<td class='left'>" + details[int].METSYS + "</td>";
                    output += "<td class='left'>" + details[int].LFLN_DISC + "</td>";
                    output += "<td class='left'>" + details[int].LFLN_SUB + "</td>";
                    output += "<td class='left'>" + details[int].SEN_CIT_DISC_SUB + "</td>";
                    output += "<td class='left'>" + details[int].ICCS + "</td>";
                    output += "<td class='left'>" + details[int].MCC_CAPEX + "</td>";
                    output += "<td class='left'>" + details[int].LOAN_COND + "</td>";
                    output += "<td class='left'>" + details[int].LOAN_COND_FIX + "</td>";
                    output += "</tr>";
                }
                output += "<tr> <td class='left top'> Total </td>";
                output += "<td class='left top'>" + Totals.COUNT + "</td>";
                output += "<td class='left top'>" + Totals.KW_SOLD + "</td>";
                output += "<td class='left top'>" + Totals.DEM_AMOUNT + "</td>";
                output += "<td class='left top'>" + Totals.KWH_USED + "</td>";
                output += "<td class='left top'>" + Totals.BILL_AMOUNT + "</td>";
                output += "<td class='left top'>" + Totals.GENSYS + "</td>";
                output += "<td class='left top'>" + Totals.PAR + "</td>";
                output += "<td class='left top'>" + Totals.FBHC + "</td>";
                output += "<td class='left top'>" + Totals.FOREX + "</td>";
                output += "<td class='left top'>" + Totals.TRANSYS + "</td>";
                output += "<td class='left top'>" + Totals.TRANSYS_DEMAND + "</td>";
                output += "<td class='left top'>" + Totals.SL + "</td>";
                output += "<td class='left top'>" + Totals.DISTSYS + "</td>";
                output += "<td class='left top'>" + Totals.DIST_DEMAND + "</td>";
                output += "<td class='left top'>" + Totals.SUPPLYFIX + "</td>";
                output += "<td class='left top'>" + Totals.SUPPLYSYS + "</td>";
                output += "<td class='left top'>" + Totals.METFIX + "</td>";
                output += "<td class='left top'>" + Totals.METSYS + "</td>";
                output += "<td class='left top'>" + Totals.LFLN_DISC + "</td>";
                output += "<td class='left top'>" + Totals.LFLN_SUB + "</td>";
                output += "<td class='left top'>" + Totals.SEN_CIT_DISC_SUB + "</td>";
                output += "<td class='left top'>" + Totals.ICCS + "</td>";
                output += "<td class='left top'>" + Totals.MCC_CAPEX + "</td>";
                output += "<td class='left top'>" + Totals.LOAN_COND + "</td>";
                output += "<td class='left top'>" + Totals.LOAN_COND_FIX + "</td>";
                output += "</tr>";
                output += "</table> <br> <table id='table'> <tr>";
                output += "<th rowspan=2 class='left top bot'> Consumer Type  </th>";
                output += "<th colspan=6 class='left top'> UNIVERSAL CHARGES </th>";
                output += "<th colspan=17 class='left top'> VALUE ADDED TAX (VAT) </th>";
                output += "</tr>";
                output += "<th class='left top bot'> ME(SPUG) </th>";
                output += "<th class='left top bot'> ME(RED) </th>";
                output += "<th class='left top bot'> UC-EC </th>";
                output += "<th class='left top bot'> EC-ETR </th>";
                output += "<th class='left top bot'> NPC-SCC </th>";
                output += "<th class='left top bot'> NPC-SD </th>";
                output += "<th class='left top bot'> FIT-ALL  </th>";
                output += "<th class='left top bot'> Gen.  </th>";
                output += "<th class='left top bot'> PAR  </th>";
                output += "<th class='left top bot'> Trans.  </th>";
                output += "<th class='left top bot'> Trans. Demand  </th>";
                output += "<th class='left top bot'> Sys.  </th>";
                output += "<th class='left top bot'> Dist.  </th>";
                output += "<th class='left top bot'> Dist. Demand  </th>";
                output += "<th class='left top bot'> Supply Fix  </th>";
                output += "<th class='left top bot'> Supply Sys.  </th>";
                output += "<th class='left top bot'> Met. Fix  </th>";
                output += "<th class='left top bot'> Met. Sys.  </th>";
                output += "<th class='left top bot'> Lfln. Subs./Disc.  </th>";
                output += "<th class='left top bot'> Loan Cond. Fix  </th>";
                output += "<th class='left top bot'> Loan Cond. </th>";
                output += "<th class='left top bot'> Other </th> </tr>";

                for(var marino in details){
                    output += "<tr> <td class='left' style='text-align: left !important;'> &nbsp;" + marino + "</td>";
                    output += "<td class='left'>" + details[marino].ME_SPUG + "</td>";
                    output += "<td class='left'>" + details[marino].ME_RED + "</td>";
                    output += "<td class='left'>" + details[marino].UC_EC + "</td>";
                    output += "<td class='left'>" + details[marino].EC_ETR + "</td>";
                    output += "<td class='left'>" + details[marino].NPC_SCC + "</td>";
                    output += "<td class='left'>" + details[marino].NPC_SD + "</td>";
                    output += "<td class='left'>" + details[marino].FIT_ALL + "</td>";
                    output += "<td class='left'>" + details[marino].GEN_VAT + "</td>";
                    output += "<td class='left'>" + details[marino].PAR_VAT + "</td>";
                    output += "<td class='left'>" + details[marino].TRANSYS_VAT + "</td>";
                    output += "<td class='left'>" + details[marino].TRANS_DEM_VAT + "</td>";
                    output += "<td class='left'>" + details[marino].SL_VAT + "</td>";
                    output += "<td class='left'>" + details[marino].DISTSYS_VAT + "</td>";
                    output += "<td class='left'>" + details[marino].DIST_DEM_VAT + "</td>";
                    output += "<td class='left'>" + details[marino].SUPFIX_VAT + "</td>";
                    output += "<td class='left'>" + details[marino].SUPSYS_VAT + "</td>";
                    output += "<td class='left'>" + details[marino].METFIX_VAT + "</td>";
                    output += "<td class='left'>" + details[marino].METSYS_VAT + "</td>";
                    output += "<td class='left'>" + details[marino].LFLN_VAT + "</td>";
                    output += "<td class='left'>" + details[marino].LOANCOND_FIX_VAT + "</td>";
                    output += "<td class='left'>" + details[marino].LOANCOND_VAT + "</td>";
                    output += "<td class='left'>" + details[marino].OTHER_VAT + "</td>";
                }
                output += "<tr> <td class='left top'> Total </td>";
                output += "<td class='left top'>" + Totals.ME_SPUG + "</td>";
                output += "<td class='left top'>" + Totals.ME_RED + "</td>";
                output += "<td class='left top'>" + Totals.UC_EC + "</td>";
                output += "<td class='left top'>" + Totals.EC_ETR + "</td>";
                output += "<td class='left top'>" + Totals.NPC_SCC + "</td>";
                output += "<td class='left top'>" + Totals.NPC_SD + "</td>";
                output += "<td class='left top'>" + Totals.FIT_ALL + "</td>";
                output += "<td class='left top'>" + Totals.GEN_VAT + "</td>";
                output += "<td class='left top'>" + Totals.PAR_VAT + "</td>";
                output += "<td class='left top'>" + Totals.TRANSYS_VAT + "</td>";
                output += "<td class='left top'>" + Totals.TRANS_DEM_VAT + "</td>";
                output += "<td class='left top'>" + Totals.SL_VAT + "</td>";
                output += "<td class='left top'>" + Totals.DISTSYS_VAT + "</td>";
                output += "<td class='left top'>" + Totals.DIST_DEM_VAT + "</td>";
                output += "<td class='left top'>" + Totals.SUPFIX_VAT + "</td>";
                output += "<td class='left top'>" + Totals.SUPSYS_VAT + "</td>";
                output += "<td class='left top'>" + Totals.METFIX_VAT + "</td>";
                output += "<td class='left top'>" + Totals.METSYS_VAT + "</td>";
                output += "<td class='left top'>" + Totals.LFLN_VAT + "</td>";
                output += "<td class='left top'>" + Totals.LOANCOND_FIX_VAT + "</td>";
                output += "<td class='left top'>" + Totals.LOANCOND_VAT + "</td>";
                output += "<td class='left top'>" + Totals.OTHER_VAT + "</td>";

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Report Found');
                // window.close();
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