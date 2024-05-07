<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Sales per type with Consumer Name </title>

</head>
<style media="print">
    @page {
      size: landscape;
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
    var billPeriod = param.date;
    var town_code_from = param.town_code_from;
    var town_code_to = param.town_code_to;
    var area_id = param.area_id;
	var selected = param.selected;

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.area_id = area_id;
        toSend.town_code_from = town_code_from;
        toSend.town_code_to = town_code_to;
        toSend.date = billPeriod;
		toSend.selected = selected;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var sales = "{{route('report.sales.type.per.consumer.name')}}";
        xhr.open('POST', sales, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                localStorage.clear();
                getDate();
                var data = JSON.parse(this.responseText);
                var salesPerRoute = data.Sales_Per_route;
                
                var output = "";
            
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> SALES PER TYPE WITH CONSUMER NAME </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br><br>';

                for(var a in salesPerRoute) {
                    var route = a;
                    for(var b in salesPerRoute[route]) {
                        var consType = b;
                        output += '<table id="table"><tr>';  
                        output += '<th style="height: 40px;">' + consType + '</th> </tr>'; 
                        output += '<tr> <th class="left top bot "> Account Number </th>';
                        output += '<th class="left top bot"> Number of Consumers </th>';
                        output += '<th class="left top bot"> KWH Used </th>';
                        output += '<th class="left top bot"> GEN </th>';
                        output += '<th class="left top bot"> TRANS </th>';
                        output += '<th class="left top bot"> DIST </th>';
                        output += '<th class="left top bot"> Other Charges </th>';
                        output += '<th class="left top bot"> Universal Charges </th>';
                        output += '<th class="left top bot"> VAT </th>';
                        output += '<th class="left top bot"> Other NB </th>';
                        output += '<th class="left top bot right"> Total Bill </th>';

                        for(var x in salesPerRoute[route][consType]){
                            var account = x;

                            if(x > 0 && x % 2000 == 0){
                                output += '</table>';
                                output +='<div class="page-break"></div>';
                                
                                output += '<table id="table"><tr>';
                                output += '<th>' + consType + '</th> </tr>';
                                output += '<tr> <th> Account Number </th>';
                                output += '<th> Number of Consumers </th>';
                                output += '<th> KWH Used </th>';
                                output += '<th> GEN </th>';
                                output += '<th> TRANS </th>';
                                output += '<th> DIST </th>';
                                output += '<th> Other Charges </th>';
                                output += '<th> Universal Charges </th>';
                                output += '<th> VAT </th>';
                                output += '<th> Other NB </th>';
                                output += '<th> Total Bill </th>';
                                output += '<tr>';
                                output += '<td>' + salesPerRoute[route][consType][account].Account_No + '</td>';
                                output += '<td>' + salesPerRoute[route][consType][account].Name_Of_Consumer + '</td>';
                                output += '<td>' + salesPerRoute[route][consType][account].KWH_USED + '</td>';
                                output += '<td>' + salesPerRoute[route][consType][account].GEN + '</td>';
                                output += '<td>' + salesPerRoute[route][consType][account].TRANS + '</td>';
                                output += '<td>' + salesPerRoute[route][consType][account].DIST + '</td>';
                                output += '<td>' + salesPerRoute[route][consType][account].OTHER + '</td>';
                                output += '<td>' + salesPerRoute[route][consType][account].UNIV_CHARGE + '</td>';
                                output += '<td>' + salesPerRoute[route][consType][account].VAT + '</td>';
                                output += '<td>' + salesPerRoute[route][consType][account].OTHER_NB + '</td>';
                                output += '<td>' + salesPerRoute[route][consType][account].TOTAL_BILL + '</td>';
                                output += '</tr>';
                            }
                            else{
                                var consName = salesPerRoute[route][consType][account].Name_Of_Consumer;
                                var max_chars = 25;
                                
                                if(consName.length > max_chars) {
                                    consName = consName.substr(0, max_chars);
                                }

                                output += '<tr>';
                                output += '<td class="left">' + salesPerRoute[route][consType][account].Account_No + '</td>';
                                output += '<td class="left">' + consName + '</td>';
                                output += '<td class="left">' + salesPerRoute[route][consType][account].KWH_USED + '</td>';
                                output += '<td class="left">' + salesPerRoute[route][consType][account].GEN + '</td>';
                                output += '<td class="left">' + salesPerRoute[route][consType][account].TRANS + '</td>';
                                output += '<td class="left">' + salesPerRoute[route][consType][account].DIST + '</td>';
                                output += '<td class="left">' + salesPerRoute[route][consType][account].OTHER + '</td>';
                                output += '<td class="left">' + salesPerRoute[route][consType][account].UNIV_CHARGE + '</td>';
                                output += '<td class="left">' + salesPerRoute[route][consType][account].VAT + '</td>';
                                output += '<td class="left">' + salesPerRoute[route][consType][account].OTHER_NB + '</td>';
                                output += '<td class="left right">' + salesPerRoute[route][consType][account].TOTAL_BILL + '</td>';
                                output += '</tr>';
                            }
                        }
                    }
                } 

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Reports Found');
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