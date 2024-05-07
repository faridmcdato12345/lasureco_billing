<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Lifeline Discount per Town </title>

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
        margin:auto;
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
    <div id = "printBody">
</div>
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    var townId = param.townId;
    var billPeriod = param.billPeriod;
    
    
    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date = billPeriod;
        toSend.town_id = townId;
        

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var lifeLie = "{{route('report.lifeline.per.town')}}"
        xhr.open('POST', lifeLie, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var lifelinePerTown = data.Lifeline_Per_Town;
                var rangeFrom = lifelinePerTown.Range_From;

                
                var output = " ";

                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                output += '<label style="font-size: 18px;"> SUMMARY OF LIFELINE USERS PER TOWN </label> <br><br>';
                output += '<label style="font-size: 18px;">' + billdate + '</label> </center>';
                output += '<br> <div style="float: left; margin-left: 22px;"> <label style="font-size: 18px;"> Area: ' + data.Area_Code + '</label>';
                output += '<br> <label style="font-size: 18px;"> Town: ' + data.Town_Code + '</label> </div>';
                output += '<table id="table"><tr>';
                output += '<th class="top left bot"> Range From </th>';
                output += '<th class="top left bot"> Range To </th>';
                output += '<th class="top left bot"> Count </th>';
                output += '<th class="top left bot"> KWH Used </th>';
                output += '<th class="top left bot"> Lifeline Amount </th>';
                output += '<th class="top left bot"> Bil Amount </th>';
                output += '</tr>';

                for(var i=0; i<rangeFrom.length; i++) {
                    output += '<tr>';
                    
                    for(var k in lifelinePerTown) {
                        if(k !== 'Count'){
                            output += '<td class="left">' + lifelinePerTown[k][i].toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        } else {
                            output += '<td class="left">' + lifelinePerTown[k][i] + '</td>';
                        }
                        
                    }
                    output += '</tr>';
                }

                output += '</tr> <tr>';
                output += '<th class="left top"> </th> <th class="top"> TOTALS ==> </th>';
                output += '<th class="left top">' + data.Total_Count + '</th>';
				output += '<th class="left top">' + data.Total_Kwh_Used.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</th>';
                output += '<th class="left top">' + data.Total_Lifeline_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</th>';
                output += '<th class="left top">' + data.Total_Bill_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</th>';
                output += '<tr> </table>'; 
            }
            else if(xhr.status == 422){
                alert('No Report Found');
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