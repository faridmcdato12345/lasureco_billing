<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <title> Print Bill Deposit Report </title>
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
    #numBar {
        display: block;
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
    <br>
    <p id="numBar"> </p>
</body>
</html>

<script>
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();

    var toSend = new Object();

    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var param = JSON.parse(localStorage.getItem("data"));

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date_from = param.date_from;
        toSend.date_to = param.date_to;

        var dateFrom = param.date_from;
        var year = dateFrom.slice(0,4);
        var month = dateFrom.slice(5,7);
        var day = dateFrom.slice(8,10)
        var dateTo = param.date_to;
        var yearTo = dateTo.slice(0,4);
        var monthTo = dateTo.slice(5,7);
        var dayTo = dateTo.slice(8,10)

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;

        var billDeposit = "{{route('report.accounting.bill.deposit')}}";
        xhr.open('POST', billDeposit, true);

        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var info = data.info;

                var output = "";
                var totalAmount = 0;

                output += '<img id="logo" src="/img/logo.png">';
                output += "<br>";
                output += '<center> <label id="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label id="emailText"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> BILL DEPOSIT REPORT </label> <br>';
                output += '<label style="font-size: 18px;">' + months[month - 1] + ' ' + day + ', ' + year;
                output += ' - ' + months[monthTo - 1] + ' ' + dayTo + ', ' + yearTo + '</label> <br> </center>';
                output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th class="left top bot"> Count </th>';
                output += '<th class="left top bot"> Account No. </th>';
                output += '<th class="left top bot"> Consumer Name </th>';
                output += '<th class="left top bot"> O.R. # </th>';
                output += '<th class="left top bot"> O.R. Date </th>';
                output += '<th class="left top bot"> Bill Deposit Amount </th>';
                output += '</tr>';
            
                for(var i in info){
                    if(i > 0 && i % 25 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<img id="logo" src="/img/logo.png">';
                        output += "<br>";
                        output += '<center> <label id="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label id="emailText"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> BILL DEPOSIT REPORT </label><br>';
                        output += '<label style="font-size: 18px;">' + months[month - 1] + ' ' + day + ', ' + year;
                        output += ' - ' + months[monthTo - 1] + ' ' + dayTo + ', ' + yearTo + '</label> <br> </center>';
                        output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                        output += '<table id="table"><tr></tr>';
                        output += '<th class="left top bot"> Count </th>';
                        output += '<th class="left top bot"> Account No. </th>';
                        output += '<th class="left top bot"> Consumer Name </th>';
                        output += '<th class="left top bot"> O.R. # </th>';
                        output += '<th class="left top bot"> O.R. Date </th>';
                        output += '<th class="left top bot"> Bill Deposit Amount </th>';
                        output += '<tr><td class="left">' + i + '</td>';
                        output += '<td class="left">' + info[i].account_no + '</td>';
                        output += '<td class="left">' + info[i].consumer_name + '</td>';
                        output += '<td class="left">' + info[i].or_no + '</td>';
                        output += '<td class="left">' + info[i].or_date + '</td>';
                        output += '<td class="left">' + info[i].bill_deposit_amt.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '</tr>';
                        totalAmount += info[i].bill_deposit_amt;
                    }
                    else{
                        output += '<td class="left">' + i + '</td>';
                        output += '<td class="left">' + info[i].account_no + '</td>';
                        output += '<td class="left">' + info[i].consumer_name + '</td>';
                        output += '<td class="left">' + info[i].or_no + '</td>';
                        output += '<td class="left">' + info[i].or_date + '</td>';
                        output += '<td class="left">' + info[i].bill_deposit_amt.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '</tr>';
                        totalAmount += info[i].bill_deposit_amt;
                    }
                }
                output += "<tr> <td colspan=5 class='left top' style='text-align: right;'> <b> Total &nbsp;&nbsp; </b> </td>";
                output += "<td class='left top'>" + totalAmount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + " </td> </tr></table>";

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
</script>