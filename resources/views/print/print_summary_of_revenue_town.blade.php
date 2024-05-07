<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Summary of Revenue per Town </title>

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
        margin:auto;
        font-size: 17px;
        width: 97%;
        border-right: 0.75px dashed; 
    }
    th {
        height: 55px;
    }
    .top {
        border-top: 0.75px dashed;
        text-align: center !important;
    }
    .bot {
        border-bottom: 0.75px dashed;
        text-align: center !important;
    }
    .left {
        border-left: 0.75px dashed;
        text-align: center !important;
    }
    .right {
        border-right: 0.75px dashed;
        text-align: center !important;
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
        var area_id = param.areaId;
        var billdate = "";
    }

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date = billPeriod;
        toSend.area_id = area_id;
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var summRevTown = "{{route('report.revenue.per.town')}}";
        xhr.open('POST', summRevTown, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var perTown = data.Per_Town;
                var townsTotal = data.Towns_Total;

                var output = "";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> SUMMARY OF REVENUE PER TOWN </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th class="top left bot"> Town </th>';
                output += '<th class="top left bot"> Count </th>';
                output += '<th class="top left bot"> KWH Used </th>';
                output += '<th class="top left bot"> Bill Amount </th>';
                output += '<th class="top left bot"> Generation </th>';
                output += '<th class="top left bot"> Lineloss </th>';
                output += '<th class="top left bot"> Trans </th>';
                output += '<th class="top left bot"> Dist </th>';
                output += '<th class="top left bot"> Supply </th>';
                output += '<th class="top left bot"> Metering </th>';
                output += '<th class="top left bot"> Capex </th>';
                output += '<th class="top left bot"> Lfline Subs </th>';
                output += '<th class="top left bot"> Lfline Disc </th>';
                output += '<th class="top left bot"> PAR </th>';
                output += '<th class="top left bot"> UCME </th>';
                output += '<th class="top left bot"> UCEC </th>';
                output += '<th class="top left bot"> PPA Refund </th>';
                output += '<th class="top left bot"> SR Discount </th>';
                output += '<th class="top left bot"> SR Subsidy </th>';
                output += '</tr>';
                
                for(var i in perTown){
                    var town = data.Per_Town[i];
                    if(i > 0 && i % 20 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> SUMMARY OF REVENUE PER TOWN </label><br>';
                        output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br><br>';
                        output += '<table id="table"><tr>';
                        output += '<th class="top left bot"> Town </th>';
                        output += '<th class="top left bot"> Count </th>';
                        output += '<th class="top left bot"> KWH Used </th>';
                        output += '<th class="top left bot"> Bill Amount </th>';
                        output += '<th class="top left bot"> Generation </th>';
                        output += '<th class="top left bot"> Lineloss </th>';
                        output += '<th class="top left bot"> Trans </th>';
                        output += '<th class="top left bot"> Dist </th>';
                        output += '<th class="top left bot"> Supply </th>';
                        output += '<th class="top left bot"> Metering </th>';
                        output += '<th class="top left bot"> Capex </th>';
                        output += '<th class="top left bot"> Lfline Subs </th>';
                        output += '<th class="top left bot"> Lfline Disc </th>';
                        output += '<th class="top left bot"> PAR </th>';
                        output += '<th class="top left bot"> UCME </th>';
                        output += '<th class="top left bot"> UCEC </th>';
                        output += '<th class="top left bot"> PPA Refund </th>';
                        output += '<th class="top left bot"> SR Discount </th>';
                        output += '<th class="top left bot" > SR Subsidy </th>';
                        output += '</tr>';
                        output += '<td class="left">' + perTown[i].Town + '</td>';
                        output += '<td class="left">' + perTown[i].Consumer_Count_Total.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Kwh_Used.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Bill_Amount.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Generation.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].LineLoss.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Trans.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Dist.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Supply_Fix.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Metering_Fix.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Capex.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Lifeline_Subs.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Lifeline_Disc.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].PAR.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].UC_ME_SPUG.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].UC_EC.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].PPA_Refund.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].SR_Discount.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].SR_Subsidy.toLocaleString("en-US") + '</td>';
                        output += '</tr>';
                    }
                    else{
                        output += '<tr>';   
                        output += '<td class="left">' + perTown[i].Town + '</td>';
                        output += '<td class="left">' + perTown[i].Consumer_Count_Total.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Kwh_Used.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Bill_Amount.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Generation.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].LineLoss.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Trans.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Dist.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Supply_Fix.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Metering_Fix.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Capex.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Lifeline_Subs.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].Lifeline_Disc.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].PAR.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].UC_ME_SPUG.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].UC_EC.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].PPA_Refund.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].SR_Discount.toLocaleString("en-US") + '</td>';
                        output += '<td class="left">' + perTown[i].SR_Subsidy.toLocaleString("en-US") + '</td>';
                        output += '</tr>';
                    }
                }
                 
                var townTot = Object.values(townsTotal)[0];
                output += "<tr> <td class='left top bot'> Total </td>";
                output += "<td class='left top bot'>" + townTot.Consumer_Count_Total.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.Kwh_Used.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.Bill_Amount.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.Generation.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.LineLoss.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.Trans.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.Dist.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.Supply_Fix.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.Metering_Fix.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.Capex.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.Lifeline_Subs.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.Lifeline_Disc.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.PAR.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.UC_ME_SPUG.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.UC_EC.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.PPA_Refund.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.SR_Discount.toLocaleString("en-US") + "</td>";
                output += "<td class='left top bot'>" + townTot.SR_Subsidy.toLocaleString("en-US") + "</td> </tr>";
                
                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Consumer Found');
                //window.close();
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