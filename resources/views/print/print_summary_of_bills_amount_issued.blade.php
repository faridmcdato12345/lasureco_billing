<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Summary of Bills Amount Issued </title>

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
        font-family: Consolas
    }
    table {
        margin: auto;
        font-size: 15px;
        width: 97%;
	    border-right: 0.75px dashed;
	    border-bottom: 0.75px dashed;
    }
    .left {
        border-left: 0.75px dashed;
        text-align: center;
    }
    .right{
        border-right: 0.75px dashed;
        text-align: center;
    }
    .top{
        border-top: 0.75px dashed;
        text-align: center;
    }
    .bot{
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

    if(localStorage !== "") {
        var param = JSON.parse(localStorage.getItem("data"));

        var billPeriod = param.date;
        var town_id = param.town_id;
        var area = param.area;
        var town = param.town;
    }

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date = billPeriod;
        toSend.town_id = town_id;
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var summBill = "{{route('report.bill.amount.issued.summary')}}";
        xhr.open('POST', summBill, true);
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
                var summAmntIssued = data.Summ_Amount_Issued;
                var total = data.Totals;
                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> SUMMARY OF BILLS AMOUNT ISSUED </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br>';
                output += '<label style="font-size:20px;"> Area: &nbsp;' + area + '</label> </center> <br>';
                output += '<label style="font-size:20px;"> Town: ' + town + '</label> </center> <br>';
                output += '<table id="table"><tr>';
                output += '<th class="top left bot"> Route </th>';
                output += '<th class="top left bot"> Route Description </th>';
                output += '<th class="top left bot"> Number of Bills </th>';
                output += '<th class="top left bot"> Current Bill </th>';
                output += '<th class="top left bot"> Arrears </th>';
                output += '<th class="top left bot"> Total Amount </th>';
                output += '</tr>';
                
                for(var i in summAmntIssued){
                     if(i > 0 && i % 20 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <br><br><br><br><br> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> SUMMARY OF BILLS AMOUNT ISSUED </label><br>';
                        output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br>';
                        output += '<table id="table"><tr>';
                        output += '<th class="top left bot"> Route </th>';
                        output += '<th class="top left bot"> Route Description </th>';
                        output += '<th class="top left bot"> Number of Bills </th>';
                        output += '<th class="top left bot"> Current Bill </th>';
                        output += '<th class="top left bot"> Arrears </th>';
                        output += '<th class="top left bot"> Total Amount </th>';
                        output += '<tr>';
                        output += '<td class="left">' + summAmntIssued[i].Route + '</td>';
                        output += '<td class="left">' + summAmntIssued[i].Route_Barangay_Desc + '</td>';
                        output += '<td class="left">' + summAmntIssued[i].Current_No_Bills + '</td>';
                        output += '<td class="left">' + summAmntIssued[i].Current_Bills.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left">' + summAmntIssued[i].Arrear_Bills.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>'
                        output += '<td class="left">' + summAmntIssued[i].Total_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td class="left">' + summAmntIssued[i].Route + '</td>';
                        output += '<td class="left">' + summAmntIssued[i].Route_Barangay_Desc + '</td>';
                        output += '<td class="left">' + summAmntIssued[i].Current_No_Bills + '</td>';
                        output += '<td class="left">' + summAmntIssued[i].Current_Bills.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left">' + summAmntIssued[i].Arrear_Bills.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left">' + summAmntIssued[i].Total_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '</tr>';
                    }
                }
				output += '<tr> <td colspan=6 class="left top bot"> &nbsp; </td> </tr>';
				output += '<tr>';
				output += '<td class="left"> &nbsp; </td>';
				output += '<td> <b> Totals ==> </b> </td>';
				output += '<td class="left"><b>' + total.Current_No_Bills + '</b></td>';
				output += '<td class="left"><b>' + total.Current_Bills.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</b></td>';
				output += '<td class="left"><b>' + total.Arrear_Bills.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</b></td>';
				output += '<td class="left"><b>' + total.Total_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</b></td>';
				output += '</tr>';
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