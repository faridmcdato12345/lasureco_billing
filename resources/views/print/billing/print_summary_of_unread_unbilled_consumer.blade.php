<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Summary of Unread/Unbilled Consumers </title>

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
        margin: auto;
        font-size: 15px;
        width: 97%; 
        border-right: 0.75px dashed;
        border-bottom: 0.75px dashed;
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
    <div id = "printBody"> </div>
</div>
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    
    var billPeriod = param.month;
    var routeFrom = param.routeFrom;

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date_period = billPeriod;
        toSend.route_code_from = routeFrom;
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var consumers = "{{route('report.consumer.unbilled')}}";
        xhr.open('POST', consumers, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var consumer = data.Message;
                var area = Object.values(consumer)[0];
                var town = Object.values(area)[0];
                var route = Object.values(town)[0];
                var areaName = Object.keys(consumer)[0];
                var townName = Object.keys(area)[0];
                var routeName = Object.keys(town)[0];
                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> SUMMARY OF UNREAD/UNBILLED CONSUMERS </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center>';
                output += '<label style="font-size:18px;"> Area Code: ' + areaName + '</label> <br>';
                output += '<label style="font-size:18px;"> Town Code: ' + townName + '</label> <br>';
                output += '<label style="font-size:18px;"> Route Code: ' + routeName + '</label> <br>';
                output += '<table id="table"><tr>';
                output += '<th class="left top bot"> No. </th>';
                output += '<th class="left top bot"> Account No. </th>';
                output += '<th class="left top bot"> Name </th>';
                output += '<th class="left top bot"> Meter No. </th>';
                output += '<th class="left top bot"> Status </th>';
                output += '<th class="left top bot"> Remarks </th>';
                output += '<th class="left top bot"> Field Findings </th>';
                output += '<th class="left top bot"> Previous </th>';
                output += '<th class="left top bot"> Present </th>';
                output += '<th class="left top bot"> Mult </th>';
                output += '<th class="left top bot"> KWH Used </th>';
                output += '</tr>';
                
                for(var i in route){
                    if(i > 0 && i % 19 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <br> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> SUMMARY OF UNREAD/UNBILLED CONSUMERS </label><br>';
                        output += '<label style="font-size:20px;">' + billdate + '</label> </center>';
                        output += '<label style="font-size:18px;"> Area Code: ' + areaName + '</label> <br>';
                        output += '<label style="font-size:18px;"> Town Code: ' + townName + '</label> <br>';
                        output += '<label style="font-size:18px;"> Route Code: ' + routeName + '</label><br>';
                        output += '<table id="table"><tr>';
                        output += '<th class="left top bot"> No. </th>';
                        output += '<th class="left top bot"> Account No. </th>';
                        output += '<th class="left top bot"> Name </th>';
                        output += '<th class="left top bot"> Meter No. </th>';
                        output += '<th class="left top bot"> Status </th>';
                        output += '<th class="left top bot"> Remarks </th>';
                        output += '<th class="left top bot"> Field Findings </th>';
                        output += '<th class="left top bot"> Previous </th>';
                        output += '<th class="left top bot"> Present </th>';
                        output += '<th class="left top bot"> Mult </th>';
                        output += '<th class="left top bot"> KWH Used </th>';
                        output += '<tr>';
                        output += '<td class="left">' + route[i].No + '</td>';
                        output += '<td class="left">' + route[i].Account_No + '</td>';
                        output += '<td class="left">' + route[i].Name + '</td>';
                        output += '<td class="left">' + route[i].Meter_No + '</td>';
                        output += '<td class="left">' + route[i].Status + '</td>';
                        output += '<td class="left">' + route[i].Remarks + '</td>';
                        output += '<td class="left">' + route[i].Field_Findings + '</td>';
                        if(route[i].Previous !== null){
                            output += '<td class="left">' + route[i].Previous + '</td>';
                        } else {
                            output += '<td class="left"> 0 </td>';
                        }

                        if(route[i].Present !== null){
                            output += '<td class="left">' + route[i].Present + '</td>';
                        } else {
                            output += '<td class="left"> 0 </td>';
                        }
                        
                        if(route[i].Mult !== null){
                            output += '<td class="left">' + route[i].Mult + '</td>';
                        } else {
                            output += '<td class="left"> 0 </td>';
                        }
                        
                        if(route[i].KWH_Used !== null){
                            output += '<td class="left">' + route[i].KWH_Used + '</td>';    
                        } else {
                            output += '<td class="left"> 0 </td>';
                        }
                        output += '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td class="left">' + route[i].No + '</td>';
                        output += '<td class="left">' + route[i].Account_No + '</td>';
                        output += '<td class="left">' + route[i].Name + '</td>';
                        output += '<td class="left">' + route[i].Meter_No + '</td>';
                        output += '<td class="left">' + route[i].Status + '</td>';
                        output += '<td class="left">' + route[i].Remarks + '</td>';
                        output += '<td class="left">' + route[i].Field_Findings + '</td>';
                        if(route[i].Previous !== null){
                            output += '<td class="left">' + route[i].Previous + '</td>';
                        } else {
                            output += '<td class="left"> 0 </td>';
                        }

                        if(route[i].Present !== null){
                            output += '<td class="left">' + route[i].Present + '</td>';
                        } else {
                            output += '<td class="left"> 0 </td>';
                        }
                        
                        if(route[i].Mult !== null){
                            output += '<td class="left">' + route[i].Mult + '</td>';
                        } else {
                            output += '<td class="left"> 0 </td>';
                        }
                        
                        if(route[i].KWH_Used !== null){
                            output += '<td class="left">' + route[i].KWH_Used + '</td>';    
                        } else {
                            output += '<td class="left"> 0 </td>';
                        }
                        output += '</tr>';
                    }
                }
                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Consumer Found');
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

    setTimeout(function() {
        localStorage.clear();
    }, 2000);

</script>