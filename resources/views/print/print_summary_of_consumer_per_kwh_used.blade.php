<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>PRINT SUMMARY OF CONSUMER PER KWH USED</title>

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
        height:100px;
        font-size: 17px;
        margin-left: 1%;
        width: 97%; 
    }
    th {
        border-bottom: 1px solid #555;
        border-top: 1px solid #555;
        height: 50px;
    }
    td {
        text-align: center;
        height: 50px;
    }
</style>

<body onload="getData()">
    <div id = "printBody">
</div>
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    var townId = param.townId;
    var rcFrom = param.rcFrom;
    var rcTo = param.rcTo;
    var date = param.date;
    var kwhFrom = param.kwhFrom;
    var kwhTo = param.kwhTo;
    
    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date = date;
        toSend.town_id = townId;
        toSend.route_code_from = rcFrom;
        toSend.route_code_to = rcTo;
        toSend.kwh_used_from = kwhFrom;
        toSend.kwh_used_to = kwhTo;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var consPerKWH = "{{route('report.consumer.per.kwhused.summary')}}";
        xhr.open('POST', consPerKWH, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var consPerKWH = data.Cons_Per_Kwh_Used;
                var num = 0;
                
                var output = " ";

                if(recap == "No") {
                    for(var i in consPerKWH){
                        var asd = i;

                        output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                        output += '<label style="font-size: 18px;"> SUMMARY OF BILLS - ALL CONSUMERS </label> <br><br>';
                        output += '<lable style="font-size: 18px;">' + billdate + '</label> </center>';
                        output += '<div style="margin-top: -62px; margin-left: 5px;"> <label style="font-size: 18px;">' + i + '</label> <br>';
                        output += '<br> <br> <table id="table"><tr>';
                        output += '<th style="border-left: 1px solid #555;"> ACCOUNT NUMBER </th>';
                        output += '<th> TYPE </th>';
                        output += '<th> NAME </th>';
                        output += '<th> BILL NUMBER </th>';
                        output += '<th> KWH USED </th>';
                        output += '<th> METER NUMBER </th>';
                        output += '<th> STATUS </th>';
                        output += '<th style="border-right: 1px solid #555;"> REMARKS </th>';
                        output += '</tr>';
                        
                        for(var a in i){
                            num += 1;
                            if(num > 0 && num % 20 == 0){
                                output += '<br> <br> </table> <br><br><br><br><br><br>';
                                output +='<div class="page-break"></div>';
                                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                                output += '<label style="font-size: 18px;"> SUMMARY OF BILLS - ALL CONSUMERS </label> <br><br>';
                                output += '<lable style="font-size: 18px;">' + billdate + '</label> </center>';
                                output += '<div style="margin-top: -62px;"> <label style="font-size: 17px;">' + i + '</label> <br>';
                                output += '<table id="table"><tr>';
                                output += '<th style="border-left: 1px solid #555;"> ACCOUNT NUMBER </th>';
                                output += '<th> TYPE </th>';
                                output += '<th> NAME </th>';
                                output += '<th> BILL NUMBER </th>';
                                output += '<th> KWH USED </th>';
                                output += '<th> METER NUMBER </th>';
                                output += '<th> STATUS </th>';
                                output += '<th style="border-right: 1px solid #555;"> REMARKS </th>';
                                output += '</tr>';
                                output += '<td>' + consPerKWH[asd][a].Account_No + '</td>';
                                output += '<td>' + consPerKWH[asd][a].Type + '</td>';
                                output += '<td>' + consPerKWH[asd][a].Name + '</td>';
                                output += '<td>' + consPerKWH[asd][a].Bill_No + '</td>';
                                output += '<td>' + consPerKWH[asd][a].KWH_Used + '</td>';
                                output += '<td>' + consPerKWH[asd][a].Meter_No + '</td>';
                                output += '<td>' + consPerKWH[asd][a].Status + '</td>';
                                output += '<td>' + consPerKWH[asd][a].Remarks + '</td>';
                                output += '</tr>';
                            }
                            else{
                                output += '<tr>';
                                output += '<td>' + consPerKWH[asd][a].Account_No + '</td>';
                                output += '<td>' + consPerKWH[asd][a].Type + '</td>';
                                output += '<td>' + consPerKWH[asd][a].Name + '</td>';
                                output += '<td>' + consPerKWH[asd][a].Bill_No + '</td>';
                                output += '<td>' + consPerKWH[asd][a].KWH_Used + '</td>';
                                output += '<td>' + consPerKWH[asd][a].Meter_No + '</td>';
                                output += '<td>' + consPerKWH[asd][a].Status + '</td>';
                                output += '<td>' + consPerKWH[asd][a].Remarks + '</td>';
                                output += '</tr>';
                            }
                        }
                    }
                } else {
                    output += 'asd';
                }
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