<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Summary of Bills Unbundled </title>

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
        font-family: Calibri;
    }
    table {
        /* margin:auto; */
        height:100px;
        font-size: 17px;
        width: 97%; 
    }
    th {
        height: 60px;
    }
    td {
        text-align: center;
        height: 50px;
    }
    .toBorderTop {
        border-top: 1px dashed;
        height: 30px;
    }
    .toBorderBottom {
        border-bottom: 1px dashed;
        height: 30px;
    }
    #revenueTable {
        margin-left: 5%;
        width: 33%;
    }
    #revenueTable td {
        height: 30px;
        text-align: left !important;
    }
    #evatTable {
        width: 50%;
        margin-left: 5%;
    }
    #evatTable td {
        height: 30px;
        text-align: left !important;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
</div>
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    
    var billPeriod = param.date;
    var code = param.code;
    var codeFrom = param.Code_From;
    var codeTo = param.Code_To;

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.code = code;
        toSend.date = billPeriod;
        toSend.Code_From = codeFrom;
        toSend.Code_To = codeTo;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var billsUnbndld = "{{route('report.sales.unbundled.summary')}}";
        xhr.open('POST', billsUnbndld, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var salesUnbldDtls = data.Sales_Unbundled_Details;
                var recap = data.Recap;

                var output = " ";
                var countSubTot = 0;
                var kwhUsedSubTot = 0;
                var MCCSubTot = 0;
                var UCMESPUGSubTot = 0;
                var UCMEREDSubTot = 0;
                var UCECSubTot = 0;
                var NPCSCCSubTot = 0;
                var genVAtSubTot = 0;
                var transVATSubTot = 0;
                var sysVATSubTot = 0;
                var distVATSubTot = 0;
                var otherVATSubTot = 0;
                var distDemSubTot = 0;
                var transDemSubTot = 0;
                var lCondoKwhSubTot = 0;
                var lCondoFix = 0;
                var fitAllSubTot = 0;
                var billedAmountSubTot = 0;
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> SUMMARY OF UNBUNDLED BILLS </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br><br>';
                output += '<table id="table"><tr>';
                output += '<td class="toBorderTop"> Area/Town </td>';
                output += '<td class="toBorderTop"> Count Total </td>';
                output += '<td class="toBorderTop"> KwH Used </td>';
                output += '<td class="toBorderTop"> MCC </td>';
                output += '<td class="toBorderTop"> UCME - SPUG </td>';
                output += '<td class="toBorderTop"> UCEC </td>';
                output += '<td class="toBorderTop"> Gen VAT </td>';
                output += '<td class="toBorderTop"> Sys VAT </td>';
                output += '<td class="toBorderTop"> Other VAT </td>';
                output += '<td class="toBorderTop"> Trans. Dem. </td>';
                output += '<td class="toBorderTop"> L. Condo Fix </td>';
                output += '<td class="toBorderTop"> FIT ALL</td>';
                output += '<td class="toBorderTop"> Billed Amount </td> </tr>';
                output += '<td class="toBorderBottom"> </td> <td class="toBorderBottom"> </td> <td class="toBorderBottom"> </td> <td class="toBorderBottom"> </td>';
                output += '<td class="toBorderBottom"> UCME - RED </td>';
                output += '<td class="toBorderBottom"> NPCSCC </td>';
                output += '<td class="toBorderBottom"> Trans VAT </td>';
                output += '<td class="toBorderBottom"> Dist VAT </td>';
                output += '<td class="toBorderBottom"> Dist Dem </td>';
                output += '<td class="toBorderBottom"> L. Condo KwH </td>';
                output += '<td class="toBorderBottom"> </td> <td class="toBorderBottom"> </td> <td class="toBorderBottom"> </td> </tr>';
                
                for(var i in salesUnbldDtls){
                     if(i > 0 && i%20 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <br><br><br><br><br> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> SUMMARY OF UNBUNDLED BILLS </label><br>';
                        output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br>';
                        output += '<table id="table"><tr style="border-top: 1px solid #ddd;">';
                        output += '<td> Area/Town </td>';
                        output += '<td> Count Total </td>';
                        output += '<td> KwH Used </td>';
                        output += '<td> MCC </td>';
                        output += '<td> UCME - SPUG </td>';
                        output += '<td> UCEC </td>';
                        output += '<td> Gen VAT </td>';
                        output += '<td> Sys VAT </td>';
                        output += '<td> Other VAT </td>';
                        output += '<td> Trans. Dem. </td>';
                        output += '<td> L. Condo Fix </td>';
                        output += '<td> FIT ALL</td>';
                        output += '<td> Billed Amount </td> </tr>';
                        output += '<th> </th> <th> </th> <th> </th>';
                        output += '<td> UCME - RED </td>';
                        output += '<td> NPCSCC </td>';
                        output += '<td> Trans VAT </td>';
                        output += '<td> Dist VAT </td>';
                        output += '<td> Dist Dem </td>';
                        output += '<td> L. Condo KwH </td>';
                        output += '<th> </th> <th> </th> <th> </th> </tr>';
                        output += '<tr>';
                        output += '<td>' + i + '</td>';
                        output += '<td>' + salesUnbldDtls[i].Consumer_Count_Total + '</td>';
                        output += '<td>' + salesUnbldDtls[i].Total_Kwh_Used + '</td>';
                        output += '<td>' + salesUnbldDtls[i].MCC + '</td>';
                        output += '<td>' + salesUnbldDtls[i].UCME_SPUG + '</td>';
                        output += '<td>' + salesUnbldDtls[i].UCEC + '</td>';
                        output += '<td>' + salesUnbldDtls[i].GEN_VAT + '</td>';
                        output += '<td>' + salesUnbldDtls[i].SYS_VAT + '</td>';
                        output += '<td>' + salesUnbldDtls[i].OTHER_VAT + '</td>';
                        output += '<td>' + salesUnbldDtls[i].TRANS_DEM + '</td>';
                        output += '<td>' + salesUnbldDtls[i].L_CONDO_FIX + '</td>';
                        output += '<td>' + salesUnbldDtls[i].FIT_ALL + '</td>';
                        output += '<td>' + salesUnbldDtls[i].BILLED_AMOUNT + '</td>';
                        output += '</tr> <tr>';
                        output += "<td> </td> <td> </td> <td> </td> <td> </td>"; 
                        output += "<td>" + salesUnbldDtls[i].UCME_RED + "</td>"; 
                        output += "<td>" + salesUnbldDtls[i].NPCSCC + "</td>"; 
                        output += "<td>" + salesUnbldDtls[i].TRANS_VAT + "</td>"; 
                        output += "<td>" + salesUnbldDtls[i].DIST_VAT + "</td>"; 
                        output += "<td>" + salesUnbldDtls[i].DIST_DEM + "</td>"; 
                        output += "<td>" + salesUnbldDtls[i].L_CONDO_KWH + "</td>"; 
                        output += "<td> </td> <td> </td> <td> </td> <td> </td>  </tr>"; 

                        countSubTot += salesUnbldDtls[i].Consumer_Count_Total;
                        kwhUsedSubTot += salesUnbldDtls[i].Total_Kwh_Used;
                        MCCSubTot += salesUnbldDtls[i].MCC;
                        UCMESPUGSubTot += salesUnbldDtls[i].UCME_SPUG;
                        UCMEREDSubTot += salesUnbldDtls[i].UCME_RED;
                        UCECSubTot += salesUnbldDtls[i].UCEC;
                        NPCSCCSubTot += salesUnbldDtls[i].NPCSCC;
                        genVAtSubTot += salesUnbldDtls[i].GEN_VAT;
                        transVATSubTot += salesUnbldDtls[i].TRANS_VAT;
                        sysVATSubTot += salesUnbldDtls[i].SYS_VAT;
                        distVATSubTot += salesUnbldDtls[i].DIST_VAT;
                        otherVATSubTot += salesUnbldDtls[i].OTHER_VAT;
                        distDemSubTot += salesUnbldDtls[i].DIST_DEM;
                        transDemSubTot += salesUnbldDtls[i].TRANS_DEM;
                        lCondoKwhSubTot += salesUnbldDtls[i].L_CONDO_KWH;
                        lCondoFix += salesUnbldDtls[i].L_CONDO_FIX;
                        fitAllSubTot += salesUnbldDtls[i].FIT_ALL;
                        billedAmountSubTot += salesUnbldDtls[i].BILLED_AMOUNT;
                    }
                    else{
                        output += '<tr>';
                        output += '<td>' + i + '</td>';
                        output += '<td>' + salesUnbldDtls[i].Consumer_Count_Total + '</td>';
                        output += '<td>' + salesUnbldDtls[i].Total_Kwh_Used + '</td>';
                        output += '<td>' + salesUnbldDtls[i].MCC + '</td>';
                        output += '<td>' + salesUnbldDtls[i].UCME_SPUG + '</td>';
                        output += '<td>' + salesUnbldDtls[i].UCEC + '</td>';
                        output += '<td>' + salesUnbldDtls[i].GEN_VAT + '</td>';
                        output += '<td>' + salesUnbldDtls[i].SYS_VAT + '</td>';
                        output += '<td>' + salesUnbldDtls[i].OTHER_VAT + '</td>';
                        output += '<td>' + salesUnbldDtls[i].TRANS_DEM + '</td>';
                        output += '<td>' + salesUnbldDtls[i].L_CONDO_FIX + '</td>';
                        output += '<td>' + salesUnbldDtls[i].FIT_ALL + '</td>';
                        output += '<td>' + salesUnbldDtls[i].BILLED_AMOUNT + '</td>';
                        output += '</tr> <tr>';
                        output += "<td> </td> <td> </td> <td> </td> <td> </td>"; 
                        output += "<td>" + salesUnbldDtls[i].UCME_RED + "</td>"; 
                        output += "<td>" + salesUnbldDtls[i].NPCSCC + "</td>"; 
                        output += "<td>" + salesUnbldDtls[i].TRANS_VAT + "</td>"; 
                        output += "<td>" + salesUnbldDtls[i].DIST_VAT + "</td>"; 
                        output += "<td>" + salesUnbldDtls[i].DIST_DEM + "</td>"; 
                        output += "<td>" + salesUnbldDtls[i].L_CONDO_KWH + "</td>"; 
                        output += "<td> </td> <td> </td> <td> </td> <td> </td>  </tr>"; 

                        countSubTot += salesUnbldDtls[i].Consumer_Count_Total;
                        kwhUsedSubTot += salesUnbldDtls[i].Total_Kwh_Used;
                        MCCSubTot += salesUnbldDtls[i].MCC;
                        UCMESPUGSubTot += salesUnbldDtls[i].UCME_SPUG;
                        UCMEREDSubTot += salesUnbldDtls[i].UCME_RED;
                        UCECSubTot += salesUnbldDtls[i].UCEC;
                        NPCSCCSubTot += salesUnbldDtls[i].NPCSCC;
                        genVAtSubTot += salesUnbldDtls[i].GEN_VAT;
                        transVATSubTot += salesUnbldDtls[i].TRANS_VAT;
                        sysVATSubTot += salesUnbldDtls[i].SYS_VAT;
                        distVATSubTot += salesUnbldDtls[i].DIST_VAT;
                        otherVATSubTot += salesUnbldDtls[i].OTHER_VAT;
                        distDemSubTot += salesUnbldDtls[i].DIST_DEM;
                        transDemSubTot += salesUnbldDtls[i].TRANS_DEM;
                        lCondoKwhSubTot += salesUnbldDtls[i].L_CONDO_KWH;
                        lCondoFix += salesUnbldDtls[i].L_CONDO_FIX;
                        fitAllSubTot += salesUnbldDtls[i].FIT_ALL;
                        billedAmountSubTot += salesUnbldDtls[i].BILLED_AMOUNT;
                    }
                }

                output += "<tr> <td> SUB TOTAL </td>";
                output += "<td class='toBorderTop'>" + countSubTot + "</td>"; 
                output += "<td class='toBorderTop'>" + kwhUsedSubTot + "</td>"; 
                output += "<td class='toBorderTop'>" + MCCSubTot + "</td>"; 
                output += "<td class='toBorderTop'>" + UCMESPUGSubTot.toFixed(2) + "</td>"; 
                output += "<td class='toBorderTop'>" + UCECSubTot + "</td>"; 
                output += "<td class='toBorderTop'>" + genVAtSubTot.toFixed(2) + "</td>"; 
                output += "<td class='toBorderTop'>" + sysVATSubTot + "</td>"; 
                output += "<td class='toBorderTop'>" + otherVATSubTot + "</td>"; 
                output += "<td class='toBorderTop'>" + transDemSubTot + "</td>"; 
                output += "<td class='toBorderTop'>" + lCondoFix + "</td>"; 
                output += "<td class='toBorderTop'>" + fitAllSubTot + "</td>"; 
                output += "<td class='toBorderTop'>" + billedAmountSubTot.toFixed(2) + "</td> </tr>"; 
                output += "<tr> <td> </td> <td> </td> <td> </td> <td> </td>";
                output += "<td>" + UCMEREDSubTot + "</td>";
                output += "<td>" + NPCSCCSubTot + "</td>";
                output += "<td>" + transVATSubTot + "</td>";
                output += "<td>" + distVATSubTot + "</td>";
                output += "<td>" + distDemSubTot + "</td>";
                output += "<td>" + lCondoKwhSubTot + "</td> <td> </td> <td> </td> <td> </td> </tr>";
                output += "<tr> <td> Recap: </td> </tr> </table>";

                output += "<table id='revenueTable' style='float: left;'> <tr> <td> REVENUE </td> <td> </td> </tr>";
                output += "<tr> <td> Generation </td> <td>" + recap[0].REVENUE.Generation + "</td> </tr>";
                output += "<tr> <td> Transmission </td> <td>" + recap[0].REVENUE.Transmission + "</td> </tr>";
                output += "<tr> <td> Distribution </td> <td>" + recap[0].REVENUE.Distribution + "</td> </tr>";
                output += "<tr> <td> System Loss </td> <td>" + recap[0].REVENUE.System_Loss + "</tr>";
                output += "<tr> <td> Other Revenues </td> <td>" + recap[0].REVENUE.Other_Revenues + "</td> </tr>";
                var revTotal = recap[0].REVENUE.Generation + recap[0].REVENUE.Transmission + recap[0].REVENUE.Distribution + recap[0].REVENUE.System_Loss + recap[0].REVENUE.Other_Revenues;
                output += "<tr> <td class='toBorderTop'> Total </td> <td class='toBorderTop'>" + revTotal.toFixed(2) + "</td> </tr> </table>";

                output += "<table id='evatTable'> <tr> <td> E-VAT </td> <td> </td> </tr>";
                output += "<tr> <td> Generation </td> <td>" + recap[0].E_VAT.Generation_VAT + "</td>";
                output += "<td> Trans. Dem. </td> <td>" + recap[0].E_VAT.Trans_Dem + "</td> </tr>";
                output += "<tr> <td> Transmission </td> <td>" + recap[0].E_VAT.Transmission_VAT + "</td>";
                output += "<td> Dist. Dem. </td> <td>" + recap[0].E_VAT.Dist_Dem + "</td> </tr>";
                output += "<tr> <td> Distribution </td> <td>" + recap[0].E_VAT.Distribution_VAT + "</td>";
                output += "<td> L. Condo KWH </td> <td>" + recap[0].E_VAT.L_Condo_KWH + "</td> </tr>";
                output += "<tr> <td> System Loss </td> <td>" + recap[0].E_VAT.System_Loss_VAT + "</td>";
                output += "<td> L. Condo Fix </td> <td>" + recap[0].E_VAT.L_Condo_Fix + "</td> </tr>";
                output += "<tr> <td> Others </td> <td>" + recap[0].E_VAT.Others_VAT + "</td>";
                output += "<td> </td> <td> </td> </tr>";
                var evatTotal = recap[0].E_VAT.Generation_VAT + recap[0].E_VAT.Transmission_VAT + recap[0].E_VAT.Distribution_VAT + recap[0].E_VAT.System_Loss_VAT + recap[0].E_VAT.Others_VAT;
                var evatTotal2 = recap[0].E_VAT.Trans_Dem + recap[0].E_VAT.Dist_Dem + recap[0].E_VAT.L_Condo_KWH + recap[0].E_VAT.L_Condo_Fix;
                output += "<tr> <td class='toBorderTop'> Total </td> <td class='toBorderTop'>" + evatTotal.toFixed(2) + "</td>";
                output += "<td class='toBorderTop'> Total </td> <td class='toBorderTop'>" + evatTotal2.toFixed(2)+ "</td> </tr> </table>";
                

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