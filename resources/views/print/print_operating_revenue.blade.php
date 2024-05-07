<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <title> Print Operating Revenue </title>
</head>
<style media="print">
    @page {
        size: A4;
        margin: 0mm;
    }
    table {
        font-size: 12.5px !important;
        margin: auto;
    }
    th {
        font-weight: 400 !important;
    }
    .delete {
        display: none;
    }
    .action {
        display: none;
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
        width: 97%;
        font-size: 15px;
        float: left;
        border-right: 0.75px dashed;
        border-bottom: 0.75px dashed;
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
    .delete {
        background-color: rgb(221, 51, 51);
        color: white;
        border: 0px;
        height: 25px;
        cursor: pointer;
        border-radius: 2px;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
</body>
</html>

<script>
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();

    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.bp = param.bp;
        toSend.area_id = param.area_id;
        toSend.cons_type = param.cons_type;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;

        var operatingRevenue = "{{route('operating.revenue')}}";
        xhr.open('POST', operatingRevenue, true);

        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var info = data.info;

                var output = "";
                var totalCount = 0;
                var totalKWHSold = 0;
                var totalSpug = 0;
                var totalRedci = 0;
                var totalEc = 0;
                var totalUcScc = 0;
                var totalEVat = 0;
                var totalMcc = 0;
                var totalFitAll = 0;
                var totalBillAmount = 0;

                output += '<center> <br> <label id="lasuhead"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                output += '<label style="font-size: 20px;"> Operating Revenue </label> <br>';
                output += '<label style="font-size: 18px;">' + billdate + '</label> </center>';
                output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th class="left top bot"> Town </th>';
                output += '<th class="left top bot"> Count </th>';
                output += '<th class="left top bot"> KWH Sold </th>';
                output += '<th class="left top bot"> SPUG </th>';
                output += '<th class="left top bot"> REDCI </th>';
                output += '<th class="left top bot"> EC </th>';
                output += '<th class="left top bot"> UC-SCC </th>';
                output += '<th class="left top bot"> EVAT </th>';
                output += '<th class="left top bot"> MCC </th>';
                output += '<th class="left top bot"> FIT ALL </th>';
                output += '<th class="left top bot"> BILL AMOUNT </th>';
                output += '</tr>';
            
                for(var i in info){
                    output += '<tr>';
                    output += '<td class="left" style="text-align: left !important;"> &nbsp;&nbsp;&nbsp;' + i + '</td>';
                    output += '<td class="left"> ' + info[i].count.toLocaleString("en-US") + '</td>';
                    output += '<td class="left">' + info[i].kwh_sold.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td class="left">' + info[i].spug.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td class="left">' + info[i].redci.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td class="left">' + info[i].ec.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td class="left">' + info[i].uc_scc.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td class="left">' + info[i].e_vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td class="left">' + info[i].mcc.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td class="left">' + info[i].fit_all.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td class="left">' + info[i].bill_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '</tr>';

                    totalCount += info[i].count;
                    totalKWHSold += info[i].kwh_sold;
                    totalSpug += info[i].spug;
                    totalRedci += info[i].redci;
                    totalEc += info[i].ec;
                    totalUcScc += info[i].uc_scc;
                    totalEVat += info[i].e_vat;
                    totalMcc += info[i].mcc;
                    totalFitAll += info[i].fit_all;
                    totalBillAmount += info[i].bill_amount;
                }
                output += "<tr> <td class='left top'> <b> Total </b> </td>";
                output += "<td class='left top'>" + totalCount.toLocaleString("en-US",) + "</td>";
                output += "<td class='left top'>" + totalKWHSold.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalSpug.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalRedci.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalEc.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalUcScc.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalEVat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalMcc.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalFitAll.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalBillAmount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</table>";
                document.querySelector('#printBody').innerHTML = output;
            }   
            else if(xhr.status == 422){
                Swal.fire({
                    title: 'Notice!',
                    icon: 'error',
                    text: 'No Report found!'
                }).then(function(){ 
                    window.close();
                });
            }
        }
    }

    function getDate() {
        var billDate = param.bp;
        var date = JSON.stringify(billDate);
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