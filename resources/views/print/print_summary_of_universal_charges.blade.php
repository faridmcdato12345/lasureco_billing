<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Summary of Universal Charges </title>

</head>
<style media="print">
    @page {
      size: auto;
      margin: 0mm;
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
        font-size: 15px;
        width: 97%; 
        border-bottom: 0.75px dashed;
    }
    .right {
        border-right: 0.75px dashed;
        text-align: center;
    }
    .left {
        border-left: 0.75px dashed;
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
        var selected = param.selected;
        var constype = param.constype;
    }

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.area_id = area_id;
        toSend.selected = selected;
        toSend.constype = constype;
        toSend.date = billPeriod;
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var printSummUniCharge = "{{route('report.universal.charge.summary')}}";
        xhr.open('POST', printSummUniCharge, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var Summary_Universal_Charges = data.Summary_Universal_Charges;
                var Sub_Total = data.Sub_Total;
                var Grand_Total = data.Grand_Total;

                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> SUMMARY OF UNIVERSAL CHARGES </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br><br>';
                //output += '<table id="table">';
                
                for(var x in Summary_Universal_Charges){
                    if(x != "") {
                        output += '<table> <tr> <td><b>' + x + '</td> </tr>';
                        output += '<tr> <td class="left top bot"> Town </td>';
                        output += '<td class="left top bot"> Environmental </td>';
                        output += '<td class="left top bot"> ME SPUG </td>';
                        output += '<td class="left top bot"> ME REDCI </td>';
                        output += '<td class="left top bot"> NPC SCC </td>';
                        output += '<td class="left top bot"> Stranded Debt </td>';
                        output += '<td class="left top bot right"> Total </td> </tr>';

                        for(var a in Summary_Universal_Charges[x]){
                            output += "<tr> <td class='left'>" + Summary_Universal_Charges[x][a].Town_Name + "</td>";
                            output += "<td class='left'>" + Summary_Universal_Charges[x][a].Environmental.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            output += "<td class='left'>" + Summary_Universal_Charges[x][a].ME_SPUG.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            output += "<td class='left'>" + Summary_Universal_Charges[x][a].ME_REDCI.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            output += "<td class='left'>" + Summary_Universal_Charges[x][a].NPC_SCC.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            output += "<td class='left'>" + Summary_Universal_Charges[x][a].Stranded_Debt.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            output += "<td class='left right'>" + Summary_Universal_Charges[x][a].Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                        }
                        output += '</tr><tr>';
                        output += '<td class="left top"> <b> Sub-Total </td>';
                        output += '<td class="left top">' + Sub_Total[x].Sub_Total_Environmental.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left top">' + Sub_Total[x].Sub_Total_ME_SPUG.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left top">' + Sub_Total[x].Sub_Total_ME_REDCI.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left top">' + Sub_Total[x].Sub_Total_NPC_SCC.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left top">' + Sub_Total[x].Sub_Total_Stranded_Debt.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left top right">' + Sub_Total[x].Sub_Total_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td> </tr>';
                        output += '</table> <div class="page-break"> </div> <br>';
                    }
                }
                
                output += "<table> <tr>";
                output += "<td> <b> Grand Total </td> </tr>";
                output += "<tr> <td class='left top bot'> Environmental </td>";
                output += "<td class='left top bot'> ME_SPUG </td>";
                output += "<td class='left top bot'> ME_REDCI </td>";
                output += "<td class='left top bot'> NPC_SCC </td>";
                output += "<td class='left top bot'> Stranded Debt </td>";
                output += "<td class='left top bot right'> Total </td></tr>";
                output += "<td class='left'>" + Grand_Total.Grand_Total_Environmental.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left'>" + Grand_Total.Grand_Total_ME_SPUG.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left'>" + Grand_Total.Grand_Total_ME_REDCI.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left'>" + Grand_Total.Grand_Total_NPC_SCC.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left'>" + Grand_Total.Grand_Total_STRANDED_DEBT.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left right'>" + Grand_Total.Grand_Total_TOTAL.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                 
                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Report(s) Found');
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