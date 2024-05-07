<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Aging Report </title>

</head>
<style media="print">
    @page {
      margin: 0mm;
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
        margin-left: 2% !important;
    }
    table {
        
        width: 95%;
        font-size: 15px;
        border-right: 0.75px dashed;
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
    <input type="text" id="bill1" hidden>
    <input type="text" id="bill2" hidden>
    <input type="text" id="bill3" hidden>
    <input type="text" id="bill4" hidden>
    <input type="text" id="bill5" hidden>
</body>
</html>

<script>
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();

    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));

    var xhr = new XMLHttpRequest();

    function getData(){
        toSend.route_id = param.route_id;
        toSend.date1 = param.date1;
        toSend.selected = param.selected;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var printAging = "{{route('reports.billing.aging')}}";
        xhr.open('POST', printAging, true);
        xhr.setRequestHeader("Accept", "application/json");
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);
        
        xhr.onload = function(){
            if(xhr.status == 200){ 
                var output = "";
                var data = JSON.parse(this.responseText);
                let consumers = data.Consumers;
                let accountBills = data.Account_Bills;
                // let totalPerAcc = data.Total_Consumer_Each_Bill_Period;
                let bill_1 = Object.keys(data)[4];
                let bill_2 = Object.keys(data)[3];
                let bill_3 = Object.keys(data)[2];
                let bill_4 = Object.keys(data)[1];
                let bill_5 = Object.keys(data)[0];

                for(var x=1; x<6; x++){
                    document.querySelector("#bill" +x).value = Object.keys(data)[x-1];
                }
                
                setBillDates();
                
                var b1 = document.querySelector("#bill5").value;
                var b2 = document.querySelector("#bill4").value;
                var b3 = document.querySelector("#bill3").value;
                var b4 = document.querySelector("#bill2").value;
                var b5 = document.querySelector("#bill1").value;
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 21px;"> AGING REPORT </label></center>';
                output += '<label style="font-size:19px; margin-left: 2%;"> Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += '<label style="font-size: 19px; margin-left: 2%;"> Route: ' + param.route + '</label>';
                output += '<table id="table" class="bot"><tr>';
                output += '<th class="left top bot"> Account No </th>';
                output += '<th class="left top bot"> Account </th>';
                output += '<th class="left top bot" id="date1">' + b1 + '</th>';
                output += '<th class="left top bot" id="date2">' + b2 + '</th>';
                output += '<th class="left top bot" id="date3">' + b3 + '</th>';
                output += '<th class="left top bot" id="date4">' + b4 + '</th>';
                output += '<th class="left top bot" id="date5">' + b5 + '</th>';
                output += '<th class="left top bot"> Total </th>';
                output += '</tr>';

                var consName = "";

                for(var x in consumers){
                    if(x>0 && x%43==0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<br> <center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 21px;"> AGING REPORT </label></center>';
                        output += '<label style="font-size:19px; margin-left: 2%;"> Runtime: ' + date + " - " + time + '</label> <br><br>';
                        output += '<label style="font-size: 19px; margin-left: 2%;"> Route: ' + param.route + '</label>';
                        output += '<table id="table" class="bot"><tr>';
                        output += '<th class="left top bot"> Account No </th>';
                        output += '<th class="left top bot"> Account </th>';
                        output += '<th class="left top bot" id="date1">' + b1 + '</th>';
                        output += '<th class="left top bot" id="date2">' + b2 + '</th>';
                        output += '<th class="left top bot" id="date3">' + b3 + '</th>';
                        output += '<th class="left top bot" id="date4">' + b4 + '</th>';
                        output += '<th class="left top bot" id="date5">' + b5 + '</th>';
                        output += '<th class="left top bot"> Total </th>';
                        output += '</tr>';
                        output += "<tr> <td class='left'>" + consumers[x].Account_No + "</td>";
                        
                        consName = consumers[x].name;
                        var max_chars = 3;
                        
                        if(consName.length > max_chars) {
                            consName = consName.substr(0, max_chars);
                        }

                        output += "<td class='left' style='text-align: left;'> &nbsp;&nbsp;" + consName + "</td>";

                        var accNo = consumers[x].Account_No;
                        var totalPerAcc = 18;

                        if(accountBills[accNo] !== undefined) {
                            if(accountBills[accNo][bill_1] !== undefined){
                                output += "<td class='left'>" + accountBills[accNo][bill_1][0].Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                                totalPerAcc += accountBills[accNo][bill_1][0].Amount;
                            } else {
                                output += "<td class='left'> 0.00 </td>";
                            }
                            if(accountBills[accNo][bill_2] !== undefined){
                                output += "<td class='left'>" + accountBills[accNo][bill_2][0].Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                                totalPerAcc += accountBills[accNo][bill_2][0].Amount;
                            } else {
                                output += "<td class='left'> 0.00 </td>";
                            }
                            if(accountBills[accNo][bill_3] !== undefined){
                                output += "<td class='left'>" + accountBills[accNo][bill_3][0].Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                                totalPerAcc += accountBills[accNo][bill_3][0].Amount;
                            } else {
                                output += "<td class='left'> 0.00 </td>";
                            }
                            if(accountBills[accNo][bill_4] !== undefined){
                                output += "<td class='left'>" + accountBills[accNo][bill_4][0].Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                                totalPerAcc += accountBills[accNo][bill_4][0].Amount;
                            } else {
                                output += "<td class='left'> 0.00 </td>";
                            }
                            if(accountBills[accNo][bill_5] !== undefined){
                                output += "<td class='left'>" + accountBills[accNo][bill_5][0].Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                                totalPerAcc += accountBills[accNo][bill_5][0].Amount;
                            } else {
                                output += "<td class='left'> 0.00 </td>";
                            }
                            output += "<td class='left'>" + totalPerAcc.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                            output += "</tr>";
                        } else {
                            output += "<tr> <td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td> </tr>";
                        }
                    } else {
                        output += "<tr> <td class='left'>" + consumers[x].Account_No + "</td>";
                        
                        consName = consumers[x].name;
                        var max_chars = 18;
                        
                        if(consName.length > max_chars) {
                            consName = consName.substr(0, max_chars);
                        }

                        output += "<td class='left' style='text-align: left;'> &nbsp;&nbsp;" + consName + "</td>";
                        
                        var accNo = consumers[x].Account_No;
                        var totalPerAcc = 0;

                        if(accountBills[accNo] !== undefined) {
                            if(accountBills[accNo][bill_1] !== undefined){
                                output += "<td class='left'>" + accountBills[accNo][bill_1][0].Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                                totalPerAcc += accountBills[accNo][bill_1][0].Amount;
                            } else {
                                output += "<td class='left'> 0.00 </td>";
                            }
                            if(accountBills[accNo][bill_2] !== undefined){
                                output += "<td class='left'>" + accountBills[accNo][bill_2][0].Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                                totalPerAcc += accountBills[accNo][bill_2][0].Amount;
                            } else {
                                output += "<td class='left'> 0.00 </td>";
                            }
                            if(accountBills[accNo][bill_3] !== undefined){
                                output += "<td class='left'>" + accountBills[accNo][bill_3][0].Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                                totalPerAcc += accountBills[accNo][bill_3][0].Amount;
                            } else {
                                output += "<td class='left'> 0.00 </td>";
                            }
                            if(accountBills[accNo][bill_4] !== undefined){
                                output += "<td class='left'>" + accountBills[accNo][bill_4][0].Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                                totalPerAcc += accountBills[accNo][bill_4][0].Amount;
                            } else {
                                output += "<td class='left'> 0.00 </td>";
                            }
                            if(accountBills[accNo][bill_5] !== undefined){
                                output += "<td class='left'>" + accountBills[accNo][bill_5][0].Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                                totalPerAcc += accountBills[accNo][bill_5][0].Amount;
                            } else {
                                output += "<td class='left'> 0.00 </td>";
                            }
                            output += "<td class='left'>" + totalPerAcc.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                        } else {
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td>";
                            output += "<td class='left'> 0.00 </td>";
                        }
                    }
                }
                output += "<tr>";
                output += "<td colspan=2 class='left top' style='text-align: right !important;'> <b> Total &nbsp; </td>";
                output += "<td class='left top'>" + data[bill_1] + "</td>";
                output += "<td class='left top'>" + data[bill_2] + "</td>";
                output += "<td class='left top'>" + data[bill_3] + "</td>";
                output += "<td class='left top'>" + data[bill_4] + "</td>";
                output += "<td class='left top'>" + data[bill_5] + "</td>";
                output += "<td class='left top'>" + data.Grand_Total_Consumer_Bill_Period + "</td>";
                output += "</tr></table>";
                document.querySelector("#printBody").innerHTML = output;
            } else {
                alert("No Report found!");
            }
        }
    }

    function setBillDates(x) {
        var dateToSpell = "";
        var date = "";
        const aaa = [];

        for(var x=1; x<6; x++) {
            var vari = document.querySelector("#bill" +x).value;
            var date = JSON.stringify(vari);
            
            var month = date.slice(5, 7);
            var year = date.slice(1, 5);

            if(month == "01") {
                dateToSpell = "January " + year;
            } else if(month == "02") {
                dateToSpell = "February " + year;
            } else if(month == "03") {
                dateToSpell = "March " + year;
            } else if(month == "04") {
                dateToSpell = "April " + year;
            } else if(month == "05") {
                dateToSpell = "May " + year;
            } else if(month == "06") {
                dateToSpell = "June " + year;
            } else if(month == "07") {
                dateToSpell = "July " + year;
            } else if(month == "08") {
                dateToSpell = "August " + year;
            } else if(month == "09") {
                dateToSpell = "September " + year;
            } else if(month == "10") {
                dateToSpell = "October " + year;
            } else if(month == "11") {
                dateToSpell = "November " + year;
            } else if(month == "12") {
                dateToSpell = "December " + year;
            }

            document.querySelector("#bill" +x).value = dateToSpell; 
        } 
    }
</script>