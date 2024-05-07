<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Posted Collection  </title>

</head>
<style media="print">
    @page {
        size: auto;
        margin-top: 0mm;
    }
    #charges {
        width: 28% !important;
    }
    table {
        font-size: 13px !important;
        margin: auto;
    }
	#mcc {
		border-bottom: 0.75px dashed !important;
	}
    #LRS {
        border-top: 0.75px dashed !important;
    }
    #breakline {
        display: block !important;
        height: 80px !important;
    }
    #lasuText {
        font-size: 17px !important;
        float: left !important;
        margin-left: 15px !important;
        margin-top: 15px !important;
    }
    #dateText {
        font-size: 16px !important;
        float: left !important;
        margin-left: 15px !important;
    }
    #emailText {
        float: left !important;
        margin-left: -350px !important;
        margin-top: 15px !important;
    }
    .asd {
        border-bottom: 0px !important;
    }
</style>
<style>
    #charges {
        height: 50px; 
        width: 24%;
    }
    .page-break {
        page-break-after: always;
    }
    body {
        font-family: Consolas;
    }
    table {
        font-size: 15px;
        margin: auto;
        width: 97%;
        border-right: 0.75px dashed;
        border-bottom: 0.75px dashed;
    }
    td {
        text-align: center;
    }
    .left {
        border-left: 0.75px dashed;
    }
    .right {
        border-right: 0.75px dashed;
    }
    .bot {
        border-bottom: 0.75px dashed;
    }
    .top {
        border-top: 0.75px dashed;
    }
    #breakline {
        display: none;
    }
	#logo {
		height: 70px;
		width: 70px;
        float: left;
        margin-top: 22px;
        margin-left: 25px;
	}
    #lasuText {
        font-size: 24px; 
        font-weight: bold; 
        margin-left: -90px;
    }
    #dateText {
        font-size: 18px; 
        margin-left: -100px;
    }
    #emailText {
        font-size: 15px; 
        margin-left: -100px;
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

        var startDate = param.startDate;
        var endDate = param.endDate;
        // var billdate = "";
    }

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date_from = startDate;
        toSend.date_to = endDate;

        var toSendJSONed = JSON.stringify(toSend);

        var token = document.querySelector('meta[name="csrf-token"]').content;
        //console.log(token);
        var postedCollection = "{{route('collection.report.list')}}";
        xhr.open('POST', postedCollection, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                // getDate();
                var data = JSON.parse(this.responseText);
                var collection = data.Details;
                
                var output = "";
                var num = 0;
				output += '<img id="logo" src="/img/logo.png">';
                output += "<br>";
                output += '<center> <label id="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label id="emailText"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> POSTED COLLECTION  </label><br>';
                output += '<label style="font-size: 15px;">' + startDate + ' to ' + endDate + '</label></center><br><br>';
                output += '<table id="table" class="bot"><tr>';
                output += '<th class="left top bot"> Date of Posting </th>';
                output += '<th class="left top bot"> Collection Date </th>';
                output += "<th class='left top bot'> Teller's Name </th>";
                output += '<th class="left top bot"> Amount </th>';
                output += '<th class="left top bot"> AR Number </th>';
                output += '</tr>';

                for(var x in collection){
                    num += 1;
                    if(num > 0 && num % 39 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<img id="logo" src="/img/logo.png">';
                        output += '<center> <br> <label id="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label id="emailText"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> POSTED COLLECTION  </label><br>';
                        output += '<label style="font-size: 15px;">' + startDate + ' to ' + endDate + '</label></center><br><br>';
                        output += '<table id="table" class="bot"><tr>';
                        output += '<th class="left top bot"> Date of Posting </th>';
                        output += '<th class="left top bot"> Collection Date </th>';
                        output += "<th class='left top bot'> Teller's Name </th>";
                        output += '<th class="left top bot"> Amount </th>';
                        output += '<th class="left top bot"> AR Number </th>';
                        output += '</tr>';
                        output += "<tr> <td class='left bot asd'>" + collection[x].Date_Posted + "</td>"; 
                        output += "<td class='left bot asd'>" + collection[x].Date_Collection + "</td>"; 
                        output += "<td class='left bot asd' style='text-align: left !important;'> &nbsp;&nbsp;" + collection[x].Teller_Name + "</td>"; 
                        output += "<td class='left bot asd'>" + collection[x].Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>"; 
                        output += "<td class='left bot asd'>" + collection[x].AR + "</td>"; 
                        output += "</tr>";
                    } else {
                        output += "<tr> <td class='left bot asd'>" + collection[x].Date_Posted + "</td>"; 
                        output += "<td class='left bot asd'>" + collection[x].Date_Collection + "</td>"; 
                        output += "<td class='left bot asd' style='text-align: left !important;'> &nbsp;&nbsp;" + collection[x].Teller_Name + "</td>"; 
                        output += "<td class='left bot asd'>" + collection[x].Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>"; 
                        output += "<td class='left bot asd'>" + collection[x].AR + "</td>"; 
                        output += "</tr>";
                    }
                }
                output += "</table><br><br>";
                output += "<label style='float: left; margin-left: 20px;'> Prepared By: SITTIEASIAH ALOYODAN</label>";
                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Collection Found');
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