<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Summary of Canceled Bills </title>

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
        border-bottom: 0.75px dashed;
        border-right: 0.75px dashed;
    }
    .left {
        border-left: 0.75px dashed;
        text-align: center;
    }
    .top {
        border-top: 0.75px dashed;
        text-align: center;
    }
    .bottom {
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
    
    var billPeriod = param.billPeriod;

    function getData(){
        var xhr = new XMLHttpRequest();
        
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var printCanBills = "{{route('summary.cancel.bills',['date'=>':date'])}}";
        var newPrintCanBills = printCanBills.replace(':date', billPeriod);
        xhr.open('GET', newPrintCanBills, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send();

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var bill = data.Cancelled_Bills;

                var output = "";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> SUMMARY OF CANCELED BILLS </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th class="left top bottom"> Account No. </th>';
                output += '<th class="left top bottom"> Name </th>';
                output += '<th class="left top bottom"> Year/Month </th>';
                output += '<th class="left top bottom"> KWH Used </th>';
                output += '<th class="left top bottom"> Bill Amount </th>';
                output += '<th class="left top bottom"> Cancelled By </th>';
                output += '<th class="left top bottom"> Date Cancelled </th>';
                output += '</tr>';
                
                for(var i in bill){
                     if(i > 0 && i%20 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <br><br><br><br><br> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> SUMMARY OF CANCELLED BILLS </label><br>';
                        output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br>';
                        output += '<table id="table"><tr>';
                        output += '<th class="left top bottom"> Account No. </th>';
                        output += '<th class="left top bottom"> Name </th>';
                        output += '<th class="left top bottom"> Year/Month </th>';
                        output += '<th class="left top bottom"> KWH Used </th>';
                        output += '<th class="left top bottom"> Bill Amount </th>';
                        output += '<th class="left top bottom"> Cancelled By </th>';
                        output += '<th class="left top bottom"> Date Cancelled </th>';
                        output += '<tr>';
                        output += '<td class="left">' + bill[i].cm_account_no + '</td>';
                        output += '<td class="left">' + bill[i].cm_full_name + '</td>';
                        output += '<td class="left">' + bill[i].cb_date_year_month + '</td>';
                        output += '<td class="left">' + bill[i].cb_kwh_used + '</td>';
                        output += '<td class="left">' + bill[i].cb_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left">' + bill[i].user_full_name + '</td>';
                        output += '<td class="left">' + bill[i].cb_date + '</td>';
                        output += '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td class="left">' + bill[i].cm_account_no + '</td>';
                        output += '<td class="left">' + bill[i].cm_full_name + '</td>';
                        output += '<td class="left">' + bill[i].cb_date_year_month + '</td>';
                        output += '<td class="left">' + bill[i].cb_kwh_used + '</td>';
                        output += '<td class="left">' + bill[i].cb_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left">' + bill[i].user_full_name + '</td>';
                        output += '<td class="left">' + bill[i].cb_date + '</td>';
                        output += '</tr>';
                    }
                }
                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Bills Found');
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