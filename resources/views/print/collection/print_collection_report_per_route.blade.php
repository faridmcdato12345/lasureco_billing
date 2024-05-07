<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <title> Print Collection Report per Route </title>
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
    var param = JSON.parse(localStorage.getItem("data"));

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.from_date = param.from;
        toSend.to_date = param.to;
        toSend.route_id = param.route;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;

        var collectionReportRoute = "{{route('collection.by.route')}}";
        xhr.open('POST', collectionReportRoute, true);

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
                output += '<img id="logo" src="/img/logo.png">';
                output += "<br>";
                output += '<center> <label id="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label id="emailText"> teamlasureco@gmail.com </label> </center> <br><br>';
                output += '<center> <label style="font-size: 20px;"> <b> COLLECTION REPORT PER ROUTE </b> </label> <br>';
                output += '<label>' + param.from + ' - ' + param.to + '</label> </center> <br>';
                output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br>';
                output += '<table id="table"><tr>';
                output += '<th class="left top bot"> Account Number </th> ';
                output += '<th class="left top bot"> Account Name </th>';
                output += '<th class="left top bot"> MM Serial  </th>';
                output += '<th class="left top bot"> Date of Payment  </th>';
                output += '<th class="left top bot"> Amount </th> </tr>';

                for(var i in info){
                    if(i > 0 && i % 49 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<img id="logo" src="/img/logo.png">';
                        output += "<br>";
                        output += '<center> <label id="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label id="emailText"> teamlasureco@gmail.com </label> </center> <br><br>';
                        output += '<center> <label style="font-size: 20px;"> <b> COLLECTION REPORT PER ROUTE </b> </label> <br>';
                        output += '<label>' + param.from + ' - ' + param.to + '</label> </center> <br>';
                        output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br>';output += '<table id="table"><tr>';
                        output += '<th class="left top bot"> Account Number </th> ';
                        output += '<th class="left top bot"> Account Name </th>';
                        output += '<th class="left top bot"> MM Serial  </th>';
                        output += '<th class="left top bot"> Date of Payment  </th>';
                        output += '<th class="left top bot"> Amount </th> </tr>';
                        output += '<tr> <td class="left">' +  info[i].cm_account_no + '</td>';
                        output += '<td class="left">' +  info[i].cm_full_name + '</td>';

                        if(info[i].mm_serial_no != null){
                            output += '<td class="left">' +  info[i].mm_serial_no + '</td>';    
                        } else {
                            output += '<td class="left"> </td>';
                        }
                        
                        output += '<td class="left">' +  info[i].s_bill_date + '</td>';
                        output += '<td class="left">' +  info[i].amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td> </tr>';
                    }
                    else {
                        output += '<tr> <td class="left">' +  info[i].cm_account_no + '</td>';
                        output += '<td class="left">' +  info[i].cm_full_name + '</td>';
                        
                        if(info[i].mm_serial_no != null){
                            output += '<td class="left">' +  info[i].mm_serial_no + '</td>';    
                        } else {
                            output += '<td class="left"> </td>';
                        }

                        output += '<td class="left">' +  info[i].s_bill_date + '</td>';
                        output += '<td class="left">' +  info[i].amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td> </tr>';
                    }
                }

                output += "<tr> <td colspan='4' class='left top'> <b style='float: right;'> Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b> </td>";
                output += "<td class='left top'>" + data.total_amount.toLocaleString("en-US") + "</td>";
                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                Swal.fire({
                    title: 'Notice!',
                    icon: 'error',
                    text: 'No Report found!'
                })
                .then(function(){ 
                    window.close();
                });
            }
        }
    }
</script>