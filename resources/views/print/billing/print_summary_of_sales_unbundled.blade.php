<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Summary of Bills Unbundled</title>

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
        font-size: 15px;
        width: 97%; 
        border-top: 1px dashed;
        border-right: 1px dashed;
    }
    th {
        text-align: center;
        font-weight: 700;
    }.top {
        border-top: 1px dashed;
        text-align: center;
    }
    .bot {
        border-bottom: 1px dashed;
        text-align: center;
    }
    .left {
        border-left: 1px dashed;
        text-align: center;
    }
    .right {
        border-right: 1px dashed;
        text-align: center;
    }
    #revenueTable {
        margin-left: 5%;
        width: 33%;
    }
    #revenueTable th {
        height: 30px;
        text-align: left !important;
    }
    #evatTable {
        width: 50%;
        margin-left: 5%;
    }
    #evatTable th {
        height: 30px;
        text-align: left !important;
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
    var filter = param.filter;
    var type = param.type;

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date = billPeriod;
        toSend.filter = filter;
        toSend.type = type;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var billsUnbndld = "{{route('report.sales.unbundled.summary')}}";
        xhr.open('POST', billsUnbndld, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var info = data.Sales_Unbundled_Details;
                var grandTotal = data.Grand_total[0];
                var total = 0;
                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> SUMMARY OF UNBUNDLED BILLS </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th class="bot left"> Location </th>';
                output += '<th class="bot left"> KWh Used </th>';
                output += '<th class="bot left"> Gen Sys </th>';
                output += '<th class="bot left"> Power Act Red </th>';
                output += '<th class="bot left"> Franchise Ben to host </th>';
                output += '<th class="bot left"> FOREX </th>';
                output += '<th class="bot left"> Trans Sys </th>';
                output += '<th class="bot left"> Trans Dem </th>';
                output += '<th class="bot left"> Sys Loss </th>';
                output += '<th class="bot left"> Dist Sys </th>';
                output += '<th class="bot left"> Dist Dem </th>';
                output += '<th class="bot left"> Supp Fix </th>';
                output += '<th class="bot left"> Supp Sys </th>';
                output += '<th class="bot left"> Meter Fix </th>';
                output += '<th class="bot left"> Meter Sys </th>';
                output += '<th class="bot left"> Lifeline Disc </th>';
                output += '<th class="bot left"> Lifeline Disc Sub </th>';
                output += '<th class="bot left"> Senior Cit Disc Sub </th>';
                output += '<th class="bot left"> Inter Class Cross Sub </th>';
                output += '<th class="bot left"> CAPEX </th>';
                output += '<th class="bot left"> Loan Cond </th>';
                output += '<th class="bot left"> Loan Cond Fix </th>';
                output += '<th class="bot left"> SPUG </th>';
                output += '<th class="bot left"> RED </th>';
                output += '<th class="bot left"> Envi Charge </th>';
                output += '<th class="bot left"> Equali Royal Charge </th>';
                output += '<th class="bot left"> NPC Con </th>';
                output += '<th class="bot left"> NPC Debt </th>';
                output += '<th class="bot left"> Fit All </th>';
                output += '<th class="bot left"> Gen VAT </th>';
                output += '<th class="bot left"> Power Act Red VAT </th>';
                output += '<th class="bot left"> Trans VAT </th>';
                output += '<th class="bot left"> Trans Dem VAT </th>';
                output += '<th class="bot left"> Sys Loss VAT </th>';
                output += '<th class="bot left"> Dist Sys VAT </th>';
                output += '<th class="bot left"> Dist Dem VAT </th>';
                output += '<th class="bot left"> Supply Fix VAT </th>';
                output += '<th class="bot left"> Supply Sys VAT </th>';
                output += '<th class="bot left"> Meter Fix VAT </th>';
                output += '<th class="bot left"> Meter Sys VAT </th>';
                output += '<th class="bot left"> Lfln Disc Sub VAT </th>';
                output += '<th class="bot left"> Loan Cond VAT </th>';
                output += '<th class="bot left"> Loan Cond Fix VAT </th>';
                output += '<th class="bot left"> Others VAT </th>';
                output += '<th class="bot left"> Bill Amount </th> </tr>';
                
                for(var x in info){
                    output += '<tr> <td class="left bot" style="text-align: left !important;">' + x + '</td>';
                    for(var i in info[x]){
                        output += '<td class="left bot">' + info[x][i].toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    } 
                    output += '</tr>';
                }
                output += '<tr> <td class="left bot"> <b> Total </b> </td>'; 
                for(var j in grandTotal){
                    output += '<td class="left bot">' + grandTotal[j].toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>'
                    console.log(grandTotal[j]);
                }

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Sales Found');
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