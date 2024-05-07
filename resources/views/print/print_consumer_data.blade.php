<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Print Consumer Data</title>

</head>
<style media="print">
    @page {
      size: auto;
      margin: 0mm;
    }
    table {
        font-size: 13px !important;
    }
</style>
<style>
    .page-break {
        page-break-after: always;
    }
    body {
        font-family: Consolas;
    }
    table{
        margin: auto;
        width: 97%;
        border-right: 0.75px dashed;
        border-bottom: 0.75px dashed;
        font-size: 15px;
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
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    var routeId = param.routeId;
    var billPeriod = param.billPeriod;
    var selected = param.selected;
    var newSelected = "";
    var billdate = "";
    
    if(selected == 1){
        newSelected = "active";
    } else if(selected == 2){
        newSelected = "disconnected";
    } else if(selected == 3){
        newSelected = "both";
    }

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date = billPeriod;
        toSend.route_id = routeId;
        toSend.selected = newSelected;
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var consData = "{{route('report.consumer.data')}}";
        xhr.open('POST', consData, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var consData = data.Consumer_Data;
                var area = data.Area;
                var town = data.Town;
                var route = data.Route;
                var count = data.Total_Consumers;
                var num = 0;
                var output = " ";
                var output2 = " ";
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                output += '<label style="font-size: 18px;"> CONSUMER DATA - ACTIVE </lable> <br>';
                output += '<lable style="font-size: 18px;">' + billdate + '</lable> </center>';
                output += '<div> <lable style="font-size: 17px;"> &nbsp;&nbsp; Area&nbsp;&nbsp;: ' + area + '</lable> <br>';
                output += '<label style="font-size: 17px;"> &nbsp;&nbsp; Town : ' + town + '</lable> <br>';
                output += '<label style="font-size: 17px;"> &nbsp;&nbsp; Route: ' + route + '</lable> </div> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th class="left top bot"> Customer ID </th>';
                output += '<th class="left top bot"> Name </th>';
                output += '<th class="left top bot"> Customer Address </th>';
                output += '<th class="left top bot"> Type </th>';
                output += '<th class="left top bot"> Service Voltage </th>';
                output += '<th class="left top bot"> Average Consumption </th>';
                output += '<th class="left top bot"> Meter No. </th>';
                output += '<th class="left top bot"> Meter Brand </th>';
                output += '</tr>';

                for(var i in consData){
                    num += 1;

                    var consName = consData[i].Name;
                    var max_chars = 25;
                    
                    if(consName.length > max_chars) {
                        consName = consName.substr(0, max_chars);
                    }

                    var consAdd = consData[i].Customer_Address;
                    var max_chars = 25;
                    
                    if(consAdd.length > max_chars) {
                        consAdd = consAdd.substr(0, max_chars);
                    }

                    if(num>0 && num%20==0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                        output += '<label style="font-size: 18px;"> CONSUMER DATA - ACTIVE </lable> <br>';
                        output += '<lable style="font-size: 18px;">' + billdate + '</lable> <br><br>';
                        output += '<table id="table"><tr>';
                        output += '<th class="left top bot"> Customer ID </th>';
                        output += '<th class="left top bot"> Name </th>';
                        output += '<th class="left top bot"> Customer Address </th>';
                        output += '<th class="left top bot"> Type </th>';
                        output += '<th class="left top bot"> Service Voltage </th>';
                        output += '<th class="left top bot"> Average Consumption </th>';
                        output += '<th class="left top bot"> Meter No. </th>';
                        output += '<th class="left top bot"> Meter Brand </th>';
                        output += '</tr><tr>';
                        output += '<td class="left">' + consData[i].Customer_ID + '</td>';
                        output += '<td class="left">' + consName + '</td>';
                        output += '<td class="left">' + consAdd + '</td>';
                        output += '<td class="left">' + consData[i].Type + '</td>';
                        output += '<td class="left">' + consData[i].Service_Voltage + '</td>';
                        output += '<td class="left">' + consData[i].Kwh.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left">' + consData[i].Meter_No + '</td>';
                        output += '<td class="left">' + consData[i].Meter_Brand + '</td>';
                        output += '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td class="left">' + consData[i].Customer_ID + '</td>';
                        output += '<td class="left">' + consName + '</td>';
                        output += '<td class="left">' + consAdd + '</td>';
                        output += '<td class="left">' + consData[i].Type + '</td>';
                        output += '<td class="left">' + consData[i].Service_Voltage + '</td>';
                        output += '<td class="left">' + consData[i].Kwh.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left">' + consData[i].Meter_No + '</td>';
                        output += '<td class="left">' + consData[i].Meter_Brand + '</td>';
                        output += '</tr>';
                    }
                }
                output += "</table>";
                output += "<br> <label style='font-size: 17px; text-align: left !important;'> Total Consumer Count: " + count + "</label>";
            }
            else if(xhr.status == 422){
                alert('No Consumer Found');
                window.close();
            }

            document.querySelector('#printBody').innerHTML = output;
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