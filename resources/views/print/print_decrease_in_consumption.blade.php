<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Decrease in Consumption </title>

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
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
    <br><br><br>
    <div id = "div1"> </div>
    <div id = "div2"> </div>
    <div id = "div3"> </div>
    <div id = "div4"> </div>
    <div id = "div5"> </div>
    <div id = "div6"> </div>
    <div id = "div7"> </div>
    <div id = "div8"> </div>
</div>
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    
    var routeId = param.routeId;
    var meterReader = param.meterReader;
    var billPeriod = param.billPeriod;
    var kwhFrom = param.kwhfrom;
    var kwhTo = param.kwhto;
    
    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.bill_period = billPeriod;
        toSend.route_id = routeId;
        toSend.meter_reader = meterReader;
        toSend.kwh_from = kwhFrom;
        toSend.kwh_to = kwhTo;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var decreaseConsumption = "{{route('report.decrease.consumption')}}";
        xhr.open('POST', decreaseConsumption, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var consDetail = data.Cons_Consumption_Details;
                
                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> DECREASE IN CONSUMPTION </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th> Account Number </th>';
                output += '<th> Type Name </th>';
                output += '<th> Name </th>';
                output += '<th> Current </th>';
                output += '<th> Current Bill Period</th>';
                output += '<th> Previous </th>';
                output += '<th> Previous Bill Period </th>';
                output += '<th>' + Object.keys(consDetail).length; + '</th>';
                output += '</tr>';
                
                for(var i in consDetail){
                     if(i > 0 && i % 20 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <br><br><br><br><br> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> DECREASE IN CONSUMPTION </label><br>';
                        output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br>';
                        output += '<table id="table"><tr>';
                        output += '<th> Account Number </th>';
                        output += '<th> Type Name </th>';
                        output += '<th> Name </th>';
                        output += '<th> Current </th>';
                        output += '<th> Current Bill Period</th>';
                        output += '<th> Previous </th>';
                        output += '<th> Previous Bill Period </th>';
                        output += '<th> Rate </th>';
                        output += '<tr>';
                        output += '<td>' + consDetail[i].Account_No + '</td>';
                        output += '<td>' + consDetail[i].Type + '</td>';
                        output += '<td>' + consDetail[i].Name + '</td>';
                        output += '<td>' + consDetail[i].Current + '</td>';
                        output += '<td>' + consDetail[i].Current_Bill_Period + '</td>';
                        output += '<td>' + consDetail[i].Previous + '</td>';
                        output += '<td>' + consDetail[i].Previous_Bill_Period + '</td>';
                        output += '<td>' + consDetail[i].Rate_Increased_KWH + '</td>';
                        output += '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td>' + consDetail[i].Account_No + '</td>';
                        output += '<td>' + consDetail[i].Type + '</td>';
                        output += '<td>' + consDetail[i].Name + '</td>';
                        output += '<td>' + consDetail[i].Current + '</td>';
                        output += '<td>' + consDetail[i].Current_Bill_Period + '</td>';
                        output += '<td>' + consDetail[i].Previous + '</td>';
                        output += '<td>' + consDetail[i].Previous_Bill_Period + '</td>';
                        output += '<td>' + consDetail[i].Rate_Increased_KWH + '</td>';
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
</script>