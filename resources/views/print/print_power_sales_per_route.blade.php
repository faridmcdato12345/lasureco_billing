<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>PRINT POWER SALES PER ROUTE</title>

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
    table{
        margin:auto;
        height:100px;
        font-size: 12px;
        margin-left: 2%; 
    }
    #table3{
        margin:auto;
        font-size: 12px;
        margin-left: 2%; 
        width: 97%;
    }
    #table4{
        margin:auto;
        font-size: 12px;
        margin-top: 1.5%;
        float: right;
        margin-right: 2%; 
        width: 20%;
    }
    #table2{
        margin:auto;
        font-size: 12px;
        margin-left: 2%; 
        width: 130%;
    }
    th{
        border-bottom: 1px solid #555;
        border-top: 1px solid #555;
    }
    td {
        text-align: center;
        height: 30px;
    }
    #consCount {
        float: left;
    }
    @media screen and (max-width: 1920px) {
        #table {
            width: 90%;
            margin: auto;
            font-size: 17px;
        }
        #perConstypeTable {
            width: 90%;
            margin: auto;
            font-size: 17px;
        }
        #perConstypeTable td{
            height: 50px;
        }
        #perConstypeTableVat td{
            height: 70px;
        }
        #perConstypeTableVat {
            width: 90%;
            margin: auto;
            font-size: 17px;
        }
        #table td, th {
            height: 50px;
        }
        label {
            font-size: 20px;
        }
    }
    
</style>

<body onload="getData()">
    <div id = "printBody">
    </div>
    <br><br><br><br>
    <div id="totalPerConstype">
    </div>
    <br><br><br><br>
    <div id = "totalPerConstypeVat">
    </div>
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    var routeFrom = param.routeFrom;
    var routeTo = param.routeTo;
    var billPeriod = param.billPeriod;
    var billdate = "";
    
    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date = billPeriod;
        toSend.rc_code_from = routeFrom;
        toSend.rc_code_to = routeTo;
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var powerSales = "{{route('report.powersales.per.route')}}"
        xhr.open('POST', powerSales, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var consPerRoute = data.Cons_Per_Route;
                var consType = data.Total_Per_Constype;
                var grandconsType = data.Grand_Total_Per_Constype;
                var consTypeVat = data.Total_Per_Constype_Vat;
                var grandconsTypeVat = data.Grand_Total_Per_Constype_Vat;
                var output = " ";
                var output2 = " ";
                var num = 0;
                var totalPerConstype = "";
                var totalPerConstypeVat = "";
                
                for(var a in consPerRoute){
                    var routes = consPerRoute[a];
                    
                    output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                    output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                    output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                    output += '<label style="font-size: 18px;"> SUMMARY OF SALES </label> <br><br>';
                    output += '<label style="font-size: 18px;">' + billdate + '</label> </center>';
                    output += '<div style="margin-top: -62px;"> <label style="font-size: 17px;"> &nbsp;&nbsp;&nbsp; Area&nbsp;&nbsp;: ' + routes[0].Area + ' </label> <br>';
                    output += '<label style="font-size: 17px;"> &nbsp;&nbsp;&nbsp; Town : ' + routes[0].Town + '</label> <br>';
                    output += '<label style="font-size: 17px;"> &nbsp;&nbsp;&nbsp; Route: ' + routes[0].Route + '</label> </div> <br><br>';
                    output += '<table id="table"><tr>';
                    output += '<th style="border-left: 1px solid #555;"> BILL NUMBER </th>';
                    output += '<th> ACCOUNT NUMBER </th>';
                    output += '<th> NAME </th>';
                    output += '<th> TYPE </th>';
                    output += '<th> PRESENT </th>';
                    output += '<th> PREVIOUS </th>';
                    output += '<th> KWH USED </th>';
                    output += '<th> ENERGY SUBSIDY APP </th>';
                    output += '<th> BILL AMOUNT  </th>';
                    output += '<th style="border-right: 1px solid #555;"> TOTAL AMOUNT </th>';
                    output += '</tr>';

                    for(var i in routes){
                        num += 1;
                        
                        if(num > 0 && num % 20 == 0){
                            if(num < 19){
                                output += '</table> <br><br><br><br><br><br>';
                                output += '<div class="page-break"></div>';
                                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                                output += '<label style="font-size: 18px;"> SUMMARY OF SALES </lable> <br><br>';
                                output += '<lable style="font-size: 18px;">' + billdate + '</lable> </center>';
                                output += '<div style="margin-top: -62px;"> <lable style="font-size: 17px;"> &nbsp;&nbsp;&nbsp; Area&nbsp;&nbsp;:&nbsp;' + routes[i].Area +  '</label> <br>';
                                output += '<label style="font-size: 17px;"> &nbsp;&nbsp;&nbsp; Town : ' + routes[i].Town +'</label> <br>';
                                output += '<label style="font-size: 17px;"> &nbsp;&nbsp;&nbsp; Route: ' + routes[i].Route + '</label> </div> <br><br>';
                                output += '<table id="table"><tr>';
                                output += '<th style="border-left: 1px solid #555;"> BILL NUMBER </th>';
                                output += '<th> ACCOUNT NUMBER </th>';
                                output += '<th> NAME </th>';
                                output += '<th> TYPE </th>';
                                output += '<th> PRESENT </th>';
                                output += '<th> PREVIOUS </th>';
                                output += '<th> KWH USED </th>';
                                output += '<th> ENERGY SUBSIDY APP </th>';
                                output += '<th> BILL AMOUNT  </th>';
                                output += '<th style="border-right: 1px solid #555;"> TOTAL AMOUNT </th>';
                                output += '</tr><tr>';
                                output += '<td>' + num + '-' + routes[i].Bill_No  + '</td>';
                                output += '<td>' + routes[i].Account_No  + '</td>';
                                output += '<td>' + routes[i].Name  + '</td>';
                                output += '<td>' + routes[i].Type  + '</td>';
                                output += '<td>' + routes[i].Present  + '</td>';
                                output += '<td>' + routes[i].Previous  + '</td>';
                                output += '<td>' + routes[i].KWH_Used  + '</td>';
                                output += '<td>' + routes[i].Energy_Subsidy_App  + '</td>';
                                output += '<td>' + routes[i].Bill_Amount  + '</td>';
                                output += '<td>' + routes[i].Total_Amount  + '</td>';
                                output += '</tr>';
                            }
                            else{
                                output += '</table> <br><br><br><br><br><br>';
                                output += '<div class="page-break"></div>';
                                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                                output += '<label style="font-size: 18px;"> SUMMARY OF SALES </lable> <br><br>';
                                output += '<lable style="font-size: 18px;">' + billdate + '</lable> </center>';
                                output += '<div style="margin-top: -62px;"> <lable style="font-size: 17px;"> &nbsp;&nbsp;&nbsp; Area&nbsp;&nbsp;:&nbsp;' + routes[i].Area +  '</label> <br>';
                                output += '<label style="font-size: 17px;"> &nbsp;&nbsp;&nbsp; Town : ' + routes[i].Town +'</label> <br>';
                                output += '<label style="font-size: 17px;"> &nbsp;&nbsp;&nbsp; Route: ' + routes[i].Route + '</label> </div> <br><br>';
                                output += '<table id="table"><tr>';
                                output += '<th style="border-left: 1px solid #555;"> BILL NUMBER </th>';
                                output += '<th> ACCOUNT NUMBER </th>';
                                output += '<th> NAME </th>';
                                output += '<th> TYPE </th>';
                                output += '<th> PRESENT </th>';
                                output += '<th> PREVIOUS </th>';
                                output += '<th> KWH USED </th>';
                                output += '<th> ENERGY SUBSIDY APP </th>';
                                output += '<th> BILL AMOUNT  </th>';
                                output += '<th style="border-right: 1px solid #555;"> TOTAL AMOUNT </th>';
                                output += '</tr><tr>';
                                output += '<td>' + num + '-' + routes[i].Bill_No  + '</td>';
                                output += '<td>' + routes[i].Account_No  + '</td>';
                                output += '<td>' + routes[i].Name  + '</td>';
                                output += '<td>' + routes[i].Type  + '</td>';
                                output += '<td>' + routes[i].Present  + '</td>';
                                output += '<td>' + routes[i].Previous  + '</td>';
                                output += '<td>' + routes[i].KWH_Used  + '</td>';
                                output += '<td>' + routes[i].Energy_Subsidy_App  + '</td>';
                                output += '<td>' + routes[i].Bill_Amount  + '</td>';
                                output += '<td>' + routes[i].Total_Amount  + '</td>'; 
                            }
                            output += "</tr>";
                        }
                        else{
                          
                            output += '<tr>';
                            output += '<td>' + num + '-' + routes[i].Bill_No  + ' </td>';
                            output += '<td>' + routes[i].Account_No  + '</td>';
                            output += '<td>' + routes[i].Name  + '</td>';
                            output += '<td>' + routes[i].Type  + '</td>';
                            output += '<td>' + routes[i].Present  + '</td>';
                            output += '<td>' + routes[i].Previous  + '</td>';
                            output += '<td>' + routes[i].KWH_Used  + '</td>';
                            output += '<td>' + routes[i].Energy_Subsidy_App  + '</td>';
                            output += '<td>' + routes[i].Bill_Amount  + '</td>';
                            output += '<td>' + routes[i].Total_Amount  + '</td>';
                            output += '</tr>';
                        }
                    }
                }

                //Table Total 
                totalPerConstype += "<label style='margin-left: 40px;'> VAT/UNIVERSAL CHARGES SUMMARY </label><br><br><br>";
                totalPerConstype += "<table id='perConstypeTable'> <tr> <td style='border-left: 1px solid #555; border-top: 1px solid #555; border-bottom: 1px solid #555;'> Consumer Type </td>"; 
                totalPerConstype += "<td style='border-bottom: 1px solid #555; border-top: 1px solid #555;'> Cons Count </td>"; 
                totalPerConstype += "<td style='border-bottom: 1px solid #555; border-top: 1px solid #555;'> KWH Used </td>";
                totalPerConstype += "<td style='border-bottom: 1px solid #555; border-top: 1px solid #555;'> Subsidy Applied Member </td>";
                totalPerConstype += "<td style='border-bottom: 1px solid #555; border-top: 1px solid #555;'> Cons Cont </td>";
                totalPerConstype += "<td style='border-top: 1px solid #555; border-bottom: 1px solid #555;'> Bill Amount </td>";
                totalPerConstype += "<td style='border-top: 1px solid #555; border-bottom: 1px solid #555; border-right: 1px solid #555;'> Total Amount </td> </tr>";
                   
                for(var b in consType){
                    totalPerConstype += "<tr> <td>" + b + "</td>";
                    totalPerConstype += "<td>" + consType[b].Cons_Count + "</td>";
                    totalPerConstype += "<td>" + consType[b].Total_kwh_used + "</td>";
                    totalPerConstype += "<td>" + consType[b].Energy_Subsidy_APP + "</td>";
                    totalPerConstype += "<td> 0 </td>";
                    totalPerConstype += "<td>" + consType[b].Bill_Amount.toFixed(2) + "</td>";
                    totalPerConstype += "<td>" + consType[b].Total_Amount.toFixed(2) + "</td> </tr>";
                }

                totalPerConstype += "<tr> <td style='border-top: 1px solid #555;'> Totals </td>";
                totalPerConstype += "<td style='border-top: 1px solid #555;'>" + grandconsType.Grand_Cons_Count + "</td> "; 
                totalPerConstype += "<td style='border-top: 1px solid #555;'>" + grandconsType.Grand_Total_kwh_used + "</td>"; 
                totalPerConstype += "<td style='border-top: 1px solid #555;'>" + grandconsType.Grand_Energy_Subsidy_APP + "</td>"; 
                totalPerConstype += "<td style='border-top: 1px solid #555;'> 0 </td>"; 
                totalPerConstype += "<td style='border-top: 1px solid #555;'>" + grandconsType.Grand_Bill_Amount.toFixed(2) + "</td>";
                totalPerConstype += "<td style='border-top: 1px solid #555;'>" + grandconsType.Grand_Total_Amount.toFixed(2) + "</td>"; 
                totalPerConstype += "</tr> </table>";
                //End table


                //Table for Total per Constype VAT
                totalPerConstypeVat += "<label style='margin-left: 40px;'> VAT/UNIVERSAL CHARGES SUMMARY </label><br><br><br>";
                totalPerConstypeVat += "<table id='perConstypeTableVat'> <tr> <td style='border-left: 1px solid #555; border-top: 1px solid #555; border-bottom: 1px solid #555;'> Consumer Type </td>";
                totalPerConstypeVat += "<td style='border-top: 1px solid #555; border-bottom: 1px solid #555;'> Gen. VAT <br> Trans. VAT </td>";
                totalPerConstypeVat += "<td style='border-top: 1px solid #555; border-bottom: 1px solid #555;'> SysLoss VAT <br> Dist. VAT </td>";
                totalPerConstypeVat += "<td style='border-top: 1px solid #555; border-bottom: 1px solid #555;'> Other VAT <br> Dist. Dem VAT </td>";
                totalPerConstypeVat += "<td style='border-top: 1px solid #555; border-bottom: 1px solid #555;'> Trans. Dem VAT <br> LCondo KWH VAT </td>";
                totalPerConstypeVat += "<td style='border-top: 1px solid #555; border-bottom: 1px solid #555;'> LCondo FIX VAT </td>";
                totalPerConstypeVat += "<td style='border-top: 1px solid #555; border-bottom: 1px solid #555;'> UC-SCC <br> UC-SD </td>";
                totalPerConstypeVat += "<td style='border-top: 1px solid #555; border-bottom: 1px solid #555;'> UC-ME SPUG <br>  UC-ME RED </td>";
                totalPerConstypeVat += "<td style='border-right: 1px solid #555; border-top: 1px solid #555; border-bottom: 1px solid #555;'> UC Envi </td> </tr>";
                
                for(var j in consTypeVat){
                    totalPerConstypeVat += "<tr> <td>" + j + "</td>";
                    totalPerConstypeVat += "<td>" + consTypeVat[j].Gen_Vat + "<br>" + consTypeVat[j].Trans_Vat + "</td>";
                    totalPerConstypeVat += "<td>" + consTypeVat[j].SysLoss_Vat + "<br>" + consTypeVat[j].Dist_Vat + "</td>";
                    totalPerConstypeVat += "<td>" + consTypeVat[j].Other_Vat + "<br>" + consTypeVat[j].Dist_Dem_Vat + "</td>";
                    totalPerConstypeVat += "<td>" + consTypeVat[j].Trans_Dem_Vat + "<br>" + consTypeVat[j].LCondo_Kwh_Vat + "</td>";
                    totalPerConstypeVat += "<td>" + consTypeVat[j].LCondo_Fix_Vat + "</td>";
                    totalPerConstypeVat += "<td>" + consTypeVat[j].UC_SCC + "<br>" + consTypeVat[j].UC_SD + "</td>";
                    totalPerConstypeVat += "<td>" + consTypeVat[j].UC_ME_SPUG + "<br>" + consTypeVat[j].UC_ME_RED + "</td>";
                    totalPerConstypeVat += "<td>" + consTypeVat[j].UC_ME_ENVI + "</td> </tr>";
                }

                totalPerConstypeVat += "<tr> <td style='border-top: 1px solid #555;'> Totals </td>";
                totalPerConstypeVat += "<td style='border-top: 1px solid #555;'>" + grandconsTypeVat.Gen_Vat + "<br>" + grandconsTypeVat.Trans_Vat + "</td>";
                totalPerConstypeVat += "<td style='border-top: 1px solid #555;'>" + grandconsTypeVat.SysLoss_Vat + "<br>" + grandconsTypeVat.Dist_Vat + "</td>";
                totalPerConstypeVat += "<td style='border-top: 1px solid #555;'>" + grandconsTypeVat.Other_Vat + "<br>" + grandconsTypeVat.Dist_Dem_Vat + "</td>";
                totalPerConstypeVat += "<td style='border-top: 1px solid #555;'>" + grandconsTypeVat.Trans_Dem_Vat + "<br>" + grandconsTypeVat.LCondo_Kwh_Vat + "</td>";
                totalPerConstypeVat += "<td style='border-top: 1px solid #555;'>" + grandconsTypeVat.LCondo_Fix_Vat + "</td>";
                totalPerConstypeVat += "<td style='border-top: 1px solid #555;'>" + grandconsTypeVat.UC_SCC + "<br>" + grandconsTypeVat.UC_SD + "</td>";
                totalPerConstypeVat += "<td style='border-top: 1px solid #555;'>" + grandconsTypeVat.UC_ME_SPUG + "<br>" + grandconsTypeVat.UC_ME_RED + "</td>";
                totalPerConstypeVat += "<td style='border-top: 1px solid #555;'>" + grandconsTypeVat.UC_ME_ENVI + "</td> </tr>";

                totalPerConstypeVat += "</tr> </table>";
            }
            else if(xhr.status == 422){
                alert('No Power Sales Found');
                window.close();
            }

            document.querySelector('#printBody').innerHTML = output;
            document.querySelector('#totalPerConstype').innerHTML = totalPerConstype;
            document.querySelector('#totalPerConstypeVat').innerHTML = totalPerConstypeVat;
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