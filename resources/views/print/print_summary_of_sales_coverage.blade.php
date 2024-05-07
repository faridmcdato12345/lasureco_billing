<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Print Summary of Sales Coverage</title>

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
        margin: auto;
        font-size: 15px;
        width: 97%;
        border-right: 0.75px dashed;
    }
    .left {
        border-left: 0.75px dashed;
        text-align: center;
    }
    .right {
        border-right: 0.75px dashed;
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
    <div id="printBody">
</div>
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    var billPeriod = param.date;
    var billdate = "";

    function getData(){
        var xhr = new XMLHttpRequest();
        
        var salesCoverage = "{{route('report.sales.coverage.summary',['date'=>':date'])}}"
        var newSalesCoverage = salesCoverage.replace(':date', billPeriod);
        xhr.open('GET', newSalesCoverage, true);
        xhr.send();
        
        xhr.onload = function(){
            if(xhr.status == 200){
            getDate();
            var output = "";

            var data = JSON.parse(this.responseText);
            var consPerKWH = data.Cons_Per_Kwh_Used;
            var consType = data.Cons_Type;
            
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                output += '<label style="font-size: 20px;"> SUMMARY OF SALES </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br>';
                output += '<table id="table"><tr>';
                output += '<th class="top left bot"> Consumer Type </th>';
                output += '<th class="top left bot"> Number of Receiving Services </th>';
                output += '<th class="top left bot"> KWH Sold </th>';
                output += '<th class="top left bot"> Amount </th>';
                output += '<th class="top left bot"> KWH Sold </th>';
                output += '<th class="top left bot"> Amount </th>';
                output += '</tr>'; 
            
                var pinakaNumOfRcvngSrvcs = 0;
                var pinakaKwhSold = 0;
                var pinakaAmount = 0;
                var pinakaKwhSold2 = 0;
                var pinakaAmount2 = 0;

                for(var i in consPerKWH) {
                    output += "<tr> <td class='left'> &nbsp;" + i + "</td><td class='left'></td><td class='left'></td><td class='left'></td><td class='left'></td><td class='left'></td></tr>";

                    var areaNumOfRcvngSrvcs = 0;
                    var areaKwhSold = 0;
                    var areaAmount = 0;
                    var areaKwhSold2 = 0;
                    var areaAmount2 = 0;

                    for(var x in consPerKWH[i]) {
                        output += "<tr> <td class='left bot top''>" + x + "</td><td class='left bot top'></td><td class='left bot top'></td><td class='left bot top'></td><td class='left bot top'></td><td class='left bot top'></td></tr>";
                        var b = consPerKWH[i][x];
                        
                        var numOfRcvngSrvcs = 0; 
                        var kwhSold = 0; 
                        var amount = 0; 
                        var kwhSold2 = 0; 
                        var amount2 = 0; 

                        for(var e in consType) {
                            if(consPerKWH[i][x][consType[e].Cons_Type] !== undefined) {
                                output += "<tr> <td class='left' style='text-align: left !important;'> &nbsp;" + consType[e].Cons_Type + "</td>";
                                var consumerType = consPerKWH[i][x][consType[e].Cons_Type][0];
                                output += "<td class='left'>" + consumerType.Minimum_Receiving_Services.toLocaleString("en-US") + "</td>";
                                numOfRcvngSrvcs += consumerType.Minimum_Receiving_Services;
                                
                                if(consumerType.Demand_Kwh_Sold == null) {
                                    output += "<td class='left'> 0 </td>";
                                } else {
                                    kwhSold += consumerType.Demand_Kwh_Sold;
                                    output += "<td class='left'>" + consumerType.Demand_Kwh_Sold.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                                }

                                if(consumerType.Demand_Amount_Sold == null) {
                                    output += "<td class='left'> 0 </td>";
                                } else {
                                    amount += consumerType.Demand_Amount_Sold;
                                    output += "<td class='left'>" + consumerType.Demand_Amount_Sold.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                                }
                                
                                if(consumerType.Energy_Kwh_Sold == null) {
                                    output += "<td class='left'> 0 </td>";
                                } else {
                                    kwhSold2 += consumerType.Energy_Kwh_Sold;
                                    output += "<td class='left'>" + consumerType.Energy_Kwh_Sold.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                                }

                                if(consumerType.Energy_Amount_Sold == null) {
                                    output += "<td class='left'> 0 </td>";
                                } else {
                                    amount2 += consumerType.Demand_Amount_Sold;
                                    output += "<td class='left'>" + consumerType.Energy_Amount_Sold.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                                }
                                
                            } else {
                                output += "<tr> <td class='left' style='text-align: left !important;'> &nbsp;" + consType[e].Cons_Type + "</td>";
                                output += "<td class='left'> 0 </td> <td class='left'> 0 </td> <td class='left'> 0 </td> <td class='left'> 0 </td> <td class='left'> 0 </td> </tr>";
                            }
                        }
                        output += "<tr> <td class='left top' style='text-align: left !important;'> &nbsp;" + x + " Town Total </td>";
                        output += "<td class='left top'>" + numOfRcvngSrvcs.toLocaleString("en-US") + "</td>";
                        output += "<td class='left top'>" + kwhSold.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                        output += "<td class='left top'>" + amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                        output += "<td class='left top'>" + kwhSold2.toLocaleString("en-US", { minimumFractionDigits: 2 }) +"</td>";
                        output += "<td class='left top'>" + amount2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                        output += "</tr>";

                        areaNumOfRcvngSrvcs += numOfRcvngSrvcs;
                        areaKwhSold += kwhSold;
                        areaAmount += amount;
                        areaKwhSold2 += kwhSold2;
                        areaAmount2 += amount2;

                        numOfRcvngSrvcs = 0; 
                        kwhSold = 0; 
                        amount = 0; 
                        kwhSold2 = 0; 
                        amount2 = 0; 
                    }
                    output += "<tr> <td class='left top bot' style='text-align: left !important;'>&nbsp;&nbsp;" + i + " Area Total </td>";
                    output += "<td class='left top bot'>" + areaNumOfRcvngSrvcs.toLocaleString("en-US") + "</td>";
                    output += "<td class='left top bot'>" + areaKwhSold.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top bot'>" + areaAmount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top bot'>" + areaKwhSold2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top bot'>" + areaAmount2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td></tr>";

                    pinakaNumOfRcvngSrvcs += areaNumOfRcvngSrvcs;
                    pinakaKwhSold += areaKwhSold;
                    pinakaAmount += areaAmount;
                    pinakaKwhSold2 += areaKwhSold2;
                    pinakaAmount2 += areaAmount2;

                    areaNumOfRcvngSrvcs = 0;
                    areaKwhSold = 0;
                    areaAmount = 0;
                    areaKwhSold2 = 0;
                    areaAmount2 = 0;
                }

                output += "<tr> <td colspan=6 style='border-left: 0.75px dashed;'> &nbsp; </td> </tr>";
                output += "<tr> <td class='left bot top'> <b> Grand Total </b> </td>";
                output += "<td class='left bot top'> <b>" + pinakaNumOfRcvngSrvcs.toLocaleString("en-US") + "</b> </td>";
                output += "<td class='left bot top'> <b>" + pinakaKwhSold.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</b> </td>";
                output += "<td class='left bot top'> <b>" + pinakaAmount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</b> </td>";
                output += "<td class='left bot top'> <b>" + pinakaKwhSold2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</b> </td>";
                output += "<td class='left bot top'> <b>" + pinakaAmount2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</b> </td>";
                console.log(pinakaNumOfRcvngSrvcs.toLocaleString("en-US"));
                console.log(pinakaKwhSold.toLocaleString("en-US", { minimumFractionDigits: 2 }));
                console.log(pinakaAmount.toLocaleString("en-US", { minimumFractionDigits: 2 }));
                console.log(pinakaKwhSold2.toLocaleString("en-US", { minimumFractionDigits: 2 }));
                console.log(pinakaAmount2.toLocaleString("en-US", { minimumFractionDigits: 2 }));

            } else if(xhr.status == 422){
                alert('No Sales Found');
                window.close();
            }
            document.querySelector('#printBody').innerHTML = output;
        }
    }
    
    function getDate() {
        var date = billPeriod;
        var dateToSpell = "";
        var month = date.slice(5, 8);
        var year = date.slice(0, 4);

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