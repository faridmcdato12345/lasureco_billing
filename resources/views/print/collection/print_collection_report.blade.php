<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <title> Print Collection Report </title>
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
        width: 90%;
        font-size: 15px;
        margin: auto;
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
    var param = JSON.parse(localStorage.getItem("data"));

    const months = ["", "JANUARY", "FEBRUARY", "MARCH", "APRIL", "MAY", "JUNE", "JULY", "AUGUST", "SEPTEMBER", "OCTOBER", "NOVEMBER", "DECEMBER"];

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.bill_period = param.bill_period;
        toSend.date_from = param.date_from;
        toSend.date_to = param.date_to;
        toSend.select = param.select;
        toSend.location = param.location;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;

        var collectionReport = "{{route('collection.by.bill.period')}}";
        xhr.open('POST', collectionReport, true);

        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var info = data.info;
                var sel = "";
                var loc = "";
                var month = parseInt(param.bill_period.slice(5,7));
                var month = months[month];

                if(param.select == "constype"){
                    sel += '<label style="font-size: 20px;"> COLLECTION REPORT '; 
                    sel += "(Consumer Type - " + param.location[0].toUpperCase()+param.location.slice(1) + ") </label> <br> </center>";
                } else if(param.select == "route") {
                    sel += '<label style="font-size: 20px;"> COLLECTION REPORT (';
                    sel += param.route + ') </label> <br> </center>';
                } else {
                    sel += '<label style="font-size: 20px;"> COLLECTION REPORT (All ';
                    sel += param.select[0].toUpperCase()+param.select.slice(1) + ') </label> <br> </center>';
                }

                var output = "";
                output += '<img id="logo" src="/img/logo.png">';
                output += "<br>";
                output += '<center> <label id="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label id="emailText"> teamlasureco@gmail.com </label><br><br><br>';
                output += sel;
                output += '<center style="font-size: 18px;"> BILLED FOR THE MONTH OF ' + month + ' ' + param.bill_period.slice(0,4) + '</center>';
                output += '<center style="font-size: 18px;">' + param.date_from + ' - ' + param.date_to + '</center>';
                output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += '<table id="table"><tr>';

                for(var i in info){
                    if(i > 0 && i % 49 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<img id="logo" src="/img/logo.png">';
                        output += "<br>";
                        output += '<label id="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label id="emailText"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> COLLECTION REPORT </label> <br> </center>';
                        output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                        output += '<center><table id="table">';
                        output += '<tr><th class="left top bot" colspan=3>' + i + '</th> ';
                        output += '<th class="left top bot"> No of Cons. </th>';
                        output += '<th class="left top bot"> Amount </th> </tr>';
                        
                        
                        for(var x in info[i]){
                            output += '<tr> <td class="left">' + x + '</td> </tr>';
                        }
                    }
                    else {
                        if(param.select !== "route"){
                            var totalCons = 0;
                            var totalAmount = 0;

                            output += '<tr><th class="left top bot" style="text-align: left;"> &nbsp;' + i + '</th> ';
                            output += '<th class="left top bot"> No of Cons. </th>';
                            output += '<th class="left top bot right"> Amount </th> </tr>';
                            for(var x in info[i]){
                                output += '<tr> <td class="left" style="text-align: left;"> &nbsp;&nbsp;' + x + '</td>';
                                output += '<td class="left">' + info[i][x][0].cons + '</td>';
                                output += '<td class="left right">' + info[i][x][0].amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td> </tr>';
                                totalCons += info[i][x][0].cons;
                                totalAmount += info[i][x][0].amount;
                            }
                            output += '<tr><td class="top left bot" style="text-align: right;"> <b> Total </b> &nbsp; </td> ';
                            output += '<td class="top left bot">' + totalCons + '</td>';
                            output += '<td class="top left bot right">' + totalAmount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td> </tr>';
                            output += '<tr><td colspan=3> &nbsp; </td> </tr>';
                            output += '<tr><td colspan=3> &nbsp; </td> </tr>';
                        } else {
                            var totalAmount = 0;

                            output += '<tr><th class="left top bot" style="text-align: left;"> &nbsp;' + i + '</th> ';
                            output += '<th class="left top bot"> Account Number </th>';
                            output += '<th class="left top bot"> Meter Number </th>';
                            output += '<th class="left top bot right"> Amount </th> </tr>';
                            
                            for(var x in info[i]){
                                output += '<tr> <td class="left" style="text-align: left;"> &nbsp;&nbsp;' + info[i][x].cons_name + '</td>';
                                output += '<td class="left">' + info[i][x].cons_num + '</td>';
                                output += '<td class="left">' + info[i][x].meter_num + '</td>';
                                output += '<td class="left right">' + info[i][x].amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td> <tr>';
                                totalAmount += info[i][x].amount;
                            }
                            output += '<tr><td colspan=2 class="top left bot" style="text-align: left;"> &nbsp; Total Consumers: <b>' + info[i].length + ' </b> </td> ';
                            output += '<td class="top bot" style="text-align: right;"> </td>';
                            output += '<td class="top left bot right"> Total: <b>' + totalAmount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</b> </td> </tr>';
                            output += '<tr><td colspan=3> &nbsp; </td> </tr>';
                            output += '<tr><td colspan=3> &nbsp; </td> </tr>';
                        }
                    }
                }
                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                Swal.fire({
                    title: 'Notice!',
                    icon: 'error',
                    text: 'No Report found!'
                }).then(function(){ 
                    // window.close();
                });
            }
        }
    }
</script>