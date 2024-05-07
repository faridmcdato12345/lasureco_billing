<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print General Detail Report </title>

</head>
<style media="print">
    @page {
      /* size: 5in 7in !important; */
      margin: 2mm;
      size: 13mm 18mm landscape;
    }
    table {
        font-size: 12.5px !important;
    }
    th {
        font-weight: 400 !important;
    }
</style>
<style>
    .page-break {
        page-break-after: always;
    }
    body {
        font-family: Consolas;
    }
    #table {
        float: left;
    }
    table {
        /* margin: auto; */
        width: 40%;
        font-size: 15px;
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
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    
    var billPeriod = param.billPeriod;
    var selected = param.selected;
    var townId = param.townId;
    var town = param.town;
    var newSelected = "";

    if(selected == 1) {
        newSelected = "billed_on_time";
    } else if(selected == 2) {
        newSelected = "include_late_billing";
    } 

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date = billPeriod;
        toSend.town_id = townId;
        toSend.selected = newSelected;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var printGenDetails = "{{route('report.general.detail')}}";
        xhr.open('POST', printGenDetails, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var generalReport = data.General_Report;
                var consTypeDetails = data.Consumer_Type_Detail;
                var consType = data.Consumer_type;

                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> GENERAL SUMMARY  REPORT </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br>';
                output += '<label style="font-size:20px;"> Town: ' + town + '</label> <br>';
                output += '<table id="table" class="bot"><tr>';
                output += "<th class='left top'> </th> <th colspan=3 class='left top'> General Report </th> </tr> <tr>"
                output += '<th class="left top bot" style="width: 45%;"> Route </th>';
                output += '<th class="left top bot"> No of <br> Cons </th>';
                output += '<th class="left top bot"> Total KWh <br> Used </th>';
                output += '<th class="left top bot"> Bill <br> Amount </th>';
                output += '</tr>';
                var routeName = "";
                var routeName2 = "";
                var irrigNoCons = 0;   
                var irrigKwhUsed = 0;
                var irrigBillAmount = 0;
                var cwsNoCons = 0;   
                var cwsKwhUsed = 0;
                var cwsBillAmount = 0;
                var bapsNoCons = 0;
                var bapsKwhUsed = 0;
                var bapsBillAmount =  0;
                var indNoCons = 0;
                var indNoCons1 = 0;
                var indKwhUsed = 0;
                var indKwhUsed1 = 0;
                var indBillAmount = 0;
                var indBillAmount1 = 0;
                var pubNoCons = 0; 
                var pubTotKwhUsed = 0; 
                var pubTotBillAmount = 0; 
                var strlghtNoCons = 0;
                var strlghtKwhUsed = 0;
                var strlghtBillAmount = 0;
                var comNoCons = 0;
                var comKwhUsed = 0;
                var comBillAmount = 0;
                var resNoCons = 0;
                var resKwhUsed = 0;
                var resBillAmount = 0;

                for(var i in generalReport){
                    if(i > 0 && i%10000 == 0){
                        output +='<div class="page-break"></div>';
                        output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> GENERAL SUMMARY  </label><br>';
                        output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br>';
                        output += '<label style="font-size:20px;"> Town: ' + town + '</label> <br>';
                        output += '<table id="table" class="bot"><tr>';
                        output += '<th class="left top bot"> Route </th>';
                        output += '<th class="left top bot"> No of <br> Cons </th>';
                        output += '<th class="left top bot"> Total KWh <br> Used </th>';
                        output += '<th class="left top bot right"> Bill <br> Amount </th>';
                        output += '<tr>';
                        output += '<td style="width: 20% !important;" class="left">' + i + ' ' + generalReport[i].Route_desc + '</td>';
                        output += '<td class="left">' + generalReport[i].No_of_Cons + '</td>';
                        output += '<td class="left">' + generalReport[i].Total_KWH_USED + '</td>';
                        output += '<td class="left">' + generalReport[i].Bill_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        
                    } else {
                        output += '<tr>';

                        routeName = generalReport[i].Route_desc;
                        var max_chars = 15;
                        
                        if(routeName.length > max_chars) {
                            routeName = routeName.substr(0, max_chars);
                        }

                        output += '<td style="text-align: left !important; width: 20% !important;" class="left">' + i + ' ' + routeName + '</td>';
                        output += '<td class="left">' + generalReport[i].No_of_Cons + '</td>';
                        output += '<td class="left">' + generalReport[i].Total_KWH_USED.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left">' + generalReport[i].Bill_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '</tr>';
                    }
                } 
                output += "<tr> <td class='left top'> &nbsp; </td>";
                output += "<td class='left top'>" + data.General_Total_Cons + "</td>";
                output += "<td class='left top'>" + data.General_Total_KWH_Used.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + data.General_Total_Bill_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr> </table>";  
                
                if(consTypeDetails[0].length == 0){
                    output += "<table style='float: left;   width: 20%; border-bottom: 0.75px dashed;'> <tr>";
                    output += '<th colspan="3" class="left top">' + consType[0].ct_desc + '<br> </th></tr><tr>';
                    output += '<th class="left top bot"> No of <br> Cons  </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot"> Bill <br> Amount </th>';
                    irrigNoCons
                    for(var x in generalReport){
                        output += "<tr> <td class='left'> 0 </td>";
                        output += "<td class='left'> 0.00 </td>"; 
                        output += "<td class='left'> 0.00 </td> </tr>";
                    }
                    output += "<tr> <td class='left top'> 0 </td> <td class='left top '> 0.00 </td> <td class='left top'> 0.00 </td> </tr>";
                    output += "</table>";
                } else {
                    output += "<table style='float: left; width: 20%; border-bottom: 0.75px dashed;'> <tr>";
                    output += '<th colspan="3" class="left top">' + consType[0].ct_desc + '<br> </th></tr><tr>';
                    output += '<th class="left top bot"> No of <br> Cons </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot"> Bill <br> Amount </th>';
                    for(var x in generalReport){
                        if(consTypeDetails[0][x] !== undefined){
                            output += "<tr> <td class='left'>" + consTypeDetails[0][x][consType[0].ct_desc].No_of_Cons + "</td>";
                            output += "<td class='left'> " + consTypeDetails[0][x][consType[0].ct_desc].Total_KWH_USED.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            output += "<td class='left'> " + consTypeDetails[0][x][consType[0].ct_desc].Bill_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                            irrigNoCons += consTypeDetails[0][x][consType[0].ct_desc].No_of_Cons;
                            irrigKwhUsed += consTypeDetails[0][x][consType[0].ct_desc].Total_KWH_USED;
                            irrigBillAmount += consTypeDetails[0][x][consType[0].ct_desc].Bill_Amount;
                        } else {
                            output += "<tr> <td class='left'> 0 </td>";
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td> </tr>";
                        }
                    }
                    output += "<tr> <td class='left top'>" + irrigNoCons + "</td>";
                    output += "<td class='left top'>" + irrigKwhUsed.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + irrigBillAmount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                    output += "</table>";
                }

                if(consTypeDetails[1].length == 0){
                    output += "<table style='float: left; width: 20%; border-bottom: 0.75px dashed;'> <tr>";
                    output += '<th colspan="3" class="left top">' + consType[1].ct_desc + '<br> </th></tr><tr>';
                    output += '<th class="left top bot"> No of <br> Cons </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot"> Bill <br> Amount </th>';
                    for(var x in generalReport){
                        output += "<tr> <td class='left'>  0 </td>";
                        output += "<td class='left'>  0.00 </td>"; 
                        output += "<td class='left'>  0.00 </td> </tr>";
                    }
                    output += "<tr> <td class='left top'> 0 </td> <td class='left top '> 0.00 </td> <td class='left top'> 0.00 </td> </tr>";
                    output += "</table>";
                } else {
                    output += "<table style='float: left; width: 20%; border-bottom: 0.75px dashed;'> <tr>";
                    output += '<th colspan="3" class="left top">' + consType[1].ct_desc + '<br> </th></tr><tr>';
                    output += '<th class="left top bot"> No of <br> Cons <br> </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot"> Bill <br>  Amount </th>';
                    for(var x in generalReport){
                        if(consTypeDetails[1][x] !== undefined){
                            output += "<tr> <td class='left'> " + consTypeDetails[1][x][consType[1].ct_desc].No_of_Cons + "</td>";
                            output += "<td class='left'> " + consTypeDetails[1][x][consType[1].ct_desc].Total_KWH_USED.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            output += "<td class='left'> " + consTypeDetails[1][x][consType[1].ct_desc].Bill_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                            cwsNoCons += consTypeDetails[1][x][consType[1].ct_desc].No_of_Cons;
                            cwsKwhUsed += consTypeDetails[1][x][consType[1].ct_desc].Total_KWH_USED;
                            cwsBillAmount += consTypeDetails[1][x][consType[1].ct_desc].Bill_Amount;
                        } else {
                            output += "<tr> <td class='left'> 0 </td>";
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td> </tr>";
                        }
                    }
                    output += "<tr> <td class='left top'>" + cwsNoCons + "</td>";
                    output += "<td class='left top'>" + cwsKwhUsed.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + cwsBillAmount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                    output += "</table>";
                }

                if(consTypeDetails[2].length == 0){
                    output += "<table style='float: right; width: 20%; border-bottom: 0.75px dashed;'> <tr>";
                    output += '<th colspan="3" class="left top right">' + consType[2].ct_desc + '<br> </th></tr><tr>';
                    output += '<th class="left top bot"> No of <br> Cons </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot right"> Bill <br> Amount </th>';
                    for(var x in generalReport){
                        output += "<tr> <td class='left'>  0 </td>";
                        output += "<td class='left'>  0.00 </td>"; 
                        output += "<td class='left right'>  0.00 </td> </tr>";
                    }
                    output += "<tr> <td class='left top'> 0 </td> <td class='left top '> 0.00 </td> <td class='left top right'> 0.00 </td> </tr>";
                    output += "</table>";
                } else {
                    output += "<table style='float: left; width: 20%; border-bottom: 0.75px dashed;'> <tr>";
                    output += '<th colspan="3" class="left top">' + consType[2].ct_desc + '<br> </th></tr><tr>';
                    output += '<th class="left top bot"> No of <br> Cons </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot"> Bill <br> Amount </th>';
                    for(var x in generalReport){
                        if(consTypeDetails[2][x] !== undefined){
                            output += "<tr> <td class='left'> " + consTypeDetails[2][x][consType[2].ct_desc].No_of_Cons + "</td>";
                            output += "<td class='left'> " + consTypeDetails[2][x][consType[2].ct_desc].Total_KWH_USED.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            output += "<td class='left'> " + consTypeDetails[2][x][consType[2].ct_desc].Bill_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                            bapsNoCons += consTypeDetails[2][x][consType[2].ct_desc].No_of_Cons;
                            bapsKwhUsed += consTypeDetails[2][x][consType[2].ct_desc].Total_KWH_USED;
                            bapsBillAmount += consTypeDetails[2][x][consType[2].ct_desc].Bill_Amount;
                        } else {
                            output += "<tr> <td class='left'> 0 </td>";
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td> </tr>";
                        }
                    }
                    output += "<tr> <td class='left top'>" + bapsNoCons + "</td>";
                    output += "<td class='left top'>" + bapsKwhUsed.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top right'>" + bapsBillAmount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                    output += "</table>";
                }

                output += '<br>';
                output +='<div class="page-break"></div>';
                output += '<br>';   

                if(consTypeDetails[3].length == 0){
                    output += "<table style='float: left; width: 40%; border-bottom: 0.75px dashed; margin-top: 3%;'> <tr>";
                    output += '<th colspan=4 class="left top">' + consType[3].ct_desc + '<br> </th></tr><tr>';
                    output += '<th class="left top bot" style="width: 45% !important;"> Route </th>';
                    output += '<th class="left top bot"> No of <br> Cons </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot"> Bill <br> Amount </th>';
                    for(var x in generalReport){
                        var routeName = generalReport[x].Route_desc;
                        var max_chars = 15;
                            
                        if(routeName.length > max_chars) {
                            routeName = routeName.substr(0, max_chars);
                        }
                        output += "<tr> <td class='left' style='text-align: left !important;'>" + x + " " + routeName + "</td>";
                        output += "<td class='left'>  0 </td>";
                        output += "<td class='left'>  0.00 </td>"; 
                        output += "<td class='left'>  0.00 </td> </tr>";
                    }
                    output += "<tr> <td class='left top'> &nbsp; </td> <td class='left top'> 0 </td> <td class='left top '> 0.00 </td> <td class='left top'> 0.00 </td> </tr>";
                    output += "</table>";
                } else {
                    output += "<table style='float: left; width: 40%; border-bottom: 0.75px dashed; margin-top: 3%;'> <tr>";
                    output += '<th colspan=4 class="left top">' + consType[3].ct_desc + '<br> </th></tr><tr>';
                    output += '<th class="left top bot" style="width: 45% !important;"> Route </th>';
                    output += '<th class="left top bot"> No of <br> Cons </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot"> Bill <br> Amount </th>';

                    for(var x in generalReport){
                        var routeName = generalReport[x].Route_desc;
                        var max_chars = 15;
                            
                        if(routeName.length > max_chars) {
                            routeName = routeName.substr(0, max_chars);
                        }
                        if(consTypeDetails[3][x] !== undefined){
                            output += "<tr> <td class='left' style='text-align: left;'>" + x + " " + routeName + "</td>";
                            output += "<td class='left'> " + consTypeDetails[3][x][consType[3].ct_desc].No_of_Cons + "</td>";
                            output += "<td class='left'> " + consTypeDetails[3][x][consType[3].ct_desc].Total_KWH_USED.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            output += "<td class='left'> " + consTypeDetails[3][x][consType[3].ct_desc].Bill_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                            indNoCons += consTypeDetails[3][x][consType[3].ct_desc].No_of_Cons;
                            indKwhUsed += consTypeDetails[3][x][consType[3].ct_desc].Total_KWH_USED;
                            indBillAmount += consTypeDetails[3][x][consType[3].ct_desc].Bill_Amount;
                        } else {
                            output += "<tr> <td class='left' style='text-align: left;'>" + x + " " + routeName + "</td>";
                            output += "<td class='left'> 0 </td>";
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td> </tr>";
                        }
                    }
                    output += "<tr> <td class='left top' style='text-align: left;'> </td>";
                    output += "<td class='left top'>" + indNoCons + "</td>";
                    output += "<td class='left top'>" + indKwhUsed.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + indBillAmount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                    output += "</table>";
                }

                if(consTypeDetails[4].length == 0){
                    output += "<table style='float: left; width: 20%; border-bottom: 0.75px dashed; margin-top: 3%;'> <tr>";
                    output += '<th colspan="3" class="left top">' + consType[4].ct_desc + '</th></tr><tr>';
                    output += '<th class="left top bot"> No of <br> Cons </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot"> Bill <br> Amount </th>';
                    for(var x in generalReport){
                        output += "<tr> <td class='left'> 0 </td>";
                        output += "<td class='left'>  0.00 </td>"; 
                        output += "<td class='left'>  0.00 </td> </tr>";
                    }
                    output += "<tr> <td class='left top'> 0 </td> <td class='left top '> 0.00 </td> <td class='left top'> 0.00 </td> </tr>";
                    output += "</table>";
                } else {
                    output += "<table style='float: left; width: 20%; border-bottom: 0.75px dashed; margin-top: 3%;'> <tr>";
                    output += '<th colspan="3" class="left top">' + consType[4].ct_desc + '<br> </th></tr><tr>';
                    output += '<th class="left top bot"> No of <br> Cons </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot"> Bill <br> Amount </th>';
                    for(var x in generalReport){
                        if(consTypeDetails[4][x] !== undefined){
                            output += "<tr> <td class='left'>" + consTypeDetails[4][x][consType[4].ct_desc].No_of_Cons + "</td>";
                            output += "<td class='left'>" + consTypeDetails[4][x][consType[4].ct_desc].Total_KWH_USED.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            output += "<td class='left'>" + consTypeDetails[4][x][consType[4].ct_desc].Bill_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                            pubNoCons += consTypeDetails[4][x][consType[4].ct_desc].No_of_Cons;
                            pubTotKwhUsed += consTypeDetails[4][x][consType[4].ct_desc].Total_KWH_USED;
                            pubTotBillAmount += consTypeDetails[4][x][consType[4].ct_desc].Bill_Amount;
                        } else {
                            output += "<tr> <td class='left'> 0 </td>";
                            output += "<td class='left'> 0</td>";
                            output += "<td class='left'> 0.00 </td> </tr>";
                        }
                    }
                    output += "<tr> <td class='left top'>" + pubNoCons + "</td>"; 
                    output += "<td class='left top'>" + pubTotKwhUsed.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>"; 
                    output += "<td class='left top'>" + pubTotBillAmount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td></tr>"; 
                    output += "</table>";
                }

                if(consTypeDetails[5].length == 0){
                    output += "<table style='float: left; width: 20%; border-bottom: 0.75px dashed; margin-top: 3%;'> <tr>";
                    output += '<th colspan="3" class="left top">' + consType[5].ct_desc + '<br> </th></tr><tr>';
                    output += '<th class="left top bot"> No of <br> Cons </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot"> Bill <br> Amount </th>';
                    for(var x in generalReport){
                        output += "<tr> <td class='left'>  0 </td>";
                        output += "<td class='left'>  0.00 </td>"; 
                        output += "<td class='left'>  0.00 </td> </tr>";
                    }
                    output += "<tr> <td class='left top'> 0 </td> <td class='left top'> 0.00 </td> <td class='left top'> 0.00 </td> </tr>"
                    output += "</table>";
                } else {
                    output += "<table style='float: left; width: 20%; border-bottom: 0.75px dashed; margin-top: 3%;'> <tr>";
                    output += '<th colspan="3" class="left top">' + consType[5].ct_desc + '<br> </th></tr><tr>';
                    output += '<th class="left top bot"> No of <br> Cons </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot"> Bill <br> Amount </th>';
                    for(var x in generalReport){
                        if(consTypeDetails[5][x] !== undefined){
                            output += "<tr> <td class='left'>" + consTypeDetails[5][x][consType[5].ct_desc].No_of_Cons + "</td>";
                            output += "<td class='left'>" + consTypeDetails[5][x][consType[5].ct_desc].Total_KWH_USED.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            output += "<td class='left'>" + consTypeDetails[5][x][consType[5].ct_desc].Bill_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                            strlghtNoCons += consTypeDetails[5][x][consType[5].ct_desc].No_of_Cons;
                            strlghtKwhUsed += consTypeDetails[5][x][consType[5].ct_desc].Total_KWH_USED;
                            strlghtBillAmount += consTypeDetails[5][x][consType[5].ct_desc].Bill_Amount;
                        } else {
                            output += "<tr> <td class='left'> 0 </td>";
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td> </tr>";
                        }
                    }
                    output += "<tr> <td class='left top'>" + strlghtNoCons + "</td>";
                    output += "<td class='left top'>" + strlghtKwhUsed.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + strlghtBillAmount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                    output += "</table>";
                }
                
                if(consTypeDetails[6].length == 0){
                    output += "<table style='float: left; width: 20%; border-bottom: 0.75px dashed; margin-top: 3%;'> <tr>";
                    output += '<th colspan="3" class="left top">' + consType[6].ct_desc + '<br> </th></tr><tr>';
                    output += '<th class="left top bot"> No of <br> Cons </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot right"> Bill <br> Amount </th>';
                    for(var x in generalReport){
                        output += "<tr> <td class='left'>  0 </td>";
                        output += "<td class='left'>  0.00 </td>"; 
                        output += "<td class='left'>  0.00 </td> </tr>";
                    }
                    output += "<tr> <td class='left top'> 0 </td> <td class='left top '> 0.00 </td> <td class='left top'> 0.00 </td> </tr>";
                    output += "</table>";
                } else {
                    output += "<table style='float: left; width: 20%; border-bottom: 0.75px dashed; margin-top: 3%;'> <tr>";
                    output += '<th colspan="3" class="left top right">' + consType[6].ct_desc + '<br> </th></tr><tr>';
                    output += '<th class="left top bot"> No of <br> Cons  </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot right"> Bill <br>     Amount </th>';
                    for(var x in generalReport){
                        if(consTypeDetails[6][x] !== undefined){
                            output += "<tr> <td class='left'> " + consTypeDetails[6][x][consType[6].ct_desc].No_of_Cons + "</td>";
                            output += "<td class='left'> " + consTypeDetails[6][x][consType[6].ct_desc].Total_KWH_USED.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            output += "<td class='left right'> " + consTypeDetails[6][x][consType[6].ct_desc].Bill_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                            comNoCons += consTypeDetails[6][x][consType[6].ct_desc].No_of_Cons;
                            comKwhUsed += consTypeDetails[6][x][consType[6].ct_desc].Total_KWH_USED;
                            comBillAmount += consTypeDetails[6][x][consType[6].ct_desc].Bill_Amount;
                        } else {
                            output += "<tr> <td class='left'> 0 </td>";
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left right'> 0.00 </td> </tr>";
                        }
                    }
                    output += "<tr> <td class='left top'>" + comNoCons + "</td>";
                    output += "<td class='left top'>" + comKwhUsed.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top right'>" + comBillAmount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                    output += "</table>";
                }

                output += "<br>";
                output +='<div class="page-break"></div>';
                output += '<br>';

                if(consTypeDetails[7].length == 0){
                    output += "<table style='float: left; width: 40%; border-bottom: 0.75px dashed; border-right: 0.75px dashed; margin-top: 3%;'> <tr>";
                    output += '<th colspan=4 class="left top">' + consType[7].ct_desc + '<br> </th></tr><tr>';
                    output += '<th class="left top bot" style="width: 45% !important;"> Route </th>';
                    output += '<th class="left top bot"> No of Cons <br> </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot"> Bill <br> Amount </th>';
                    for(var x in generalReport){
                        var routeName = generalReport[x].Route_desc;
                        var max_chars = 15;
                            
                        if(routeName.length > max_chars) {
                            routeName = routeName.substr(0, max_chars);
                        }
                        output += "<tr> <td class='left' style='text-align: left !important;'>" + x + " " + routeName + "</td>";
                        output += "<td class='left'>  0 </td>";
                        output += "<td class='left'>  0.00 </td>"; 
                        output += "<td class='left'>  0.00 </td> </tr>";
                    }
                    output += "<tr> <td class='left top'> &nbsp; </td> <td class='left top'> 0 </td> <td class='left top '> 0.00 </td> <td class='left top'> 0.00 </td> </tr>";
                    output += "</table>";
                } else {
                    output += "<table style='float: left; width: 40%; border-bottom: 0.75px dashed; border-right: 0.75px dashed; margin-top: 3%;'> <tr>";
                    output += '<th colspan=4 class="left top">' + consType[7].ct_desc + '<br> </th></tr><tr>';
                    output += '<th class="left top bot" style="width: 45% !important;"> Route </th>';
                    output += '<th class="left top bot"> No of Cons <br> </th>';
                    output += '<th class="left top bot"> Total KWh <br> Used </th>';
                    output += '<th class="left top bot"> Bill <br> Amount </th>';

                    for(var x in generalReport){
                        var routeName = generalReport[x].Route_desc;
                        var max_chars = 15;
                            
                        if(routeName.length > max_chars) {
                            routeName = routeName.substr(0, max_chars);
                        }
                        if(consTypeDetails[7][x] !== undefined){
                            output += "<tr> <td class='left' style='text-align: left;'>" + x + " " + routeName + "</td>";
                            output += "<td class='left'> " + consTypeDetails[7][x][consType[7].ct_desc].No_of_Cons + "</td>";
                            output += "<td class='left'> " + consTypeDetails[7][x][consType[7].ct_desc].Total_KWH_USED.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            output += "<td class='left'> " + consTypeDetails[7][x][consType[7].ct_desc].Bill_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                            indNoCons1 += consTypeDetails[7][x][consType[7].ct_desc].No_of_Cons;

                            indKwhUsed1 += consTypeDetails[7][x][consType[7].ct_desc].Total_KWH_USED;
                            indBillAmount1 += consTypeDetails[7][x][consType[7].ct_desc].Bill_Amount;
                        } else {
                            output += "<tr> <td class='left' style='text-align: left;'>" + x + " " + routeName + "</td>";
                            output += "<td class='left'> 0 </td>";
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td> </tr>";
                        }
                    }
                    output += "<tr> <td class='left top' style='text-align: left;'> </td>";
                    output += "<td class='left top'>" + indNoCons1 + "</td>";
                    output += "<td class='left top'>" + indKwhUsed1.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + indBillAmount1.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>";
                    output += "</table>";
                }

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Report Found');
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