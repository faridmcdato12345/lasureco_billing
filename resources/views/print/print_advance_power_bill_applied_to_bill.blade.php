<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Advance Power Bill Applied to Bill </title>

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
</div>
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    
    var billPeriod = param.date;
    var areaId = param.area_id;

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date = billPeriod;
        toSend.area_id = areaId;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var adPBAppBill = "{{route('report.ewallet.applied.bill')}}";
        xhr.open('POST', adPBAppBill, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var powerBill = data[0];
                var totalBalance = 0;
                var totalApplied = 0;
                var totalAdvancePayment = 0;
                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> ADVANCE POWER BILL PAYMENT APPLIED TO BILL </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th> Account Number </th>';
                output += '<th> Name </th>';
                output += '<th> Month Applied </th>';
                output += '<th> Total Advance Payment </th>';
                output += '<th> Amount Applied </th>';
                output += '<th> Balance </th>';
                output += '</tr>';
                var billMonthSpelled = "";
                for(var i in powerBill){
                    if(i > 0 && i % 20 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <br><br><br><br><br> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> ADVANCE POWER BILL PAYMENT APPLIED TO BILL </label><br>';
                        output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br>';
                        output += '<table id="table"><tr>';
                        output += '<th> Account Number </th>';
                        output += '<th> Name </th>';
                        output += '<th> Month Applied </th>';
                        output += '<th> Total Advance Payment </th>';
                        output += '<th> Amount Applied </th>';
                        output += '<th> Balance </th>';
                        output += '<tr>';
                        output += '<td>' + powerBill[i].Account_No + '</td>';
                        output += '<td>' + powerBill[i].Name + '</td>';
                        
                        var billMonth = JSON.stringify(powerBill[i].Month_Applied);
                        var month = billMonth.slice(4, 6);
                        var year = billMonth.slice(0, 4);
                        
                        if(month == "01") {
                            billMonthSpelled = "January " + year;
                        } else if(month == "02") {
                            billMonthSpelled = "February " + year;
                        } else if(month == "03") {
                            billMonthSpelled = "March " + year;
                        } else if(month == "04") {
                            billMonthSpelled = "April " + year;
                        } else if(month == "05") {
                            billMonthSpelled = "May " + year;
                        } else if(month == "06") {
                            billMonthSpelled = "June " + year;
                        } else if(month == "07") {
                            billMonthSpelled = "July " + year;
                        } else if(month == "08") {
                            billMonthSpelled = "August " + year;
                        } else if(month == "09") {
                            billMonthSpelled = "September " + year;
                        } else if(month == "10") {
                            billMonthSpelled = "October " + year;
                        } else if(month == "11") {
                            billMonthSpelled = "November " + year;
                        } else if(month == "12") {
                            billMonthSpelled = "December " + year;
                        }

                        output += '<td>' + billMonthSpelled + '</td>';
                        output += '<td>' + powerBill[i].Total_Advance_Payment + '</td>';
                        output += '<td>' + powerBill[i].Amount_Applied + '</td>';
                        output += '<td>' + powerBill[i].Balance + '</td>';
                        output += '</tr>';

                        totalAdvancePayment += parseFloat(powerBill[i].Total_Advance_Payment);
                        totalApplied += parseFloat(powerBill[i].Amount_Applied);
                        totalBalance += parseFloat(powerBill[i].Balance);
                    }
                    else{
                        output += '<tr>';
                        output += '<td>' + powerBill[i].Account_No + '</td>';
                        output += '<td>' + powerBill[i].Name + '</td>';

                        var billMonth = JSON.stringify(powerBill[i].Month_Applied);
                        var month = billMonth.slice(4, 6);
                        var year = billMonth.slice(0, 4);

                        if(month == "01") {
                            billMonthSpelled = "January " + year;
                        } else if(month == "02") {
                            billMonthSpelled = "February " + year;
                        } else if(month == "03") {
                            billMonthSpelled = "March " + year;
                        } else if(month == "04") {
                            billMonthSpelled = "April " + year;
                        } else if(month == "05") {
                            billMonthSpelled = "May " + year;
                        } else if(month == "06") {
                            billMonthSpelled = "June " + year;
                        } else if(month == "07") {
                            billMonthSpelled = "July " + year;
                        } else if(month == "08") {
                            billMonthSpelled = "August " + year;
                        } else if(month == "09") {
                            billMonthSpelled = "September " + year;
                        } else if(month == "10") {
                            billMonthSpelled = "October " + year;
                        } else if(month == "11") {
                            billMonthSpelled = "November " + year;
                        } else if(month == "12") {
                            billMonthSpelled = "December " + year;
                        }

                        output += '<td>' + billMonthSpelled + '</td>';
                        output += '<td>' + powerBill[i].Total_Advance_Payment + '</td>';
                        output += '<td>' + powerBill[i].Amount_Applied + '</td>';
                        output += '<td>' + powerBill[i].Balance + '</td>';
                        output += '</tr>';

                        
                        totalAdvancePayment += parseFloat(powerBill[i].Total_Advance_Payment);
                        totalApplied += parseFloat(powerBill[i].Amount_Applied);
                        totalBalance += parseFloat(powerBill[i].Balance);
                    }
                }
                
                output += '<table id="table"><tr>';
                output += '<th> Total Advance Payment </th>';
                output += '<th> Total Amount Applied </th>';
                output += '<th> Total Balance </th></tr>';
                output += "<tr> <td>" + parseFloat(totalAdvancePayment) + "</td>";
                output += "<td>" + parseFloat(totalApplied) + "</td>";
                output += "<td>" + parseFloat(totalBalance) + "</td> </tr> </table>";

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Power Bill Found');
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