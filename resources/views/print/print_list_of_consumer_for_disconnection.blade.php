<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Print List of Consumer for Disconnection</title>

</head>
<style media="print">
    @page {
      size: landscape;
      margin: 0mm;
    }
    #lasuhead {
        font-size: 23px !important; 
        margin-top: 1% !important; 
    }
    table {
		font-size: 12px !important;
    }
    #totalRow {
        font-size: 11px !important;
    }
    th {
        font-weight: 400 !important;
        font-size: 15px !important;
    }
	#table2 {
		font-size: 11px !important;
	}
    #table4 {
        font-size: 11px !important;
        margin-right: -0.1% !important;
    }
</style>
<style>
    #lasuhead {
        font-size: 24px; 
        font-weight: bold;
    }
    .page-break {
        page-break-after: always;
    }
    body {
        font-family: Consolas;
    }
    table{
        margin:auto;
        font-size: 15px;
        margin-left: auto; 
        width: 97%;
        border-right: 0.75px dashed;
        border-bottom: 0.75px dashed;
    }
    th{
        border-bottom: 0.75px dashed;
        border-top: 0.75px dashed;
    }
    #consCount {
        float: left;
    }
    .left {
        border-left: 0.75px dashed;
		text-align: center;
    }
    .right {
        border-right: 0.75px dashed;
		text-align: center;
    }
    .top {
        border-top: 0.75px dashed;
		text-align: center;
    }
    .bot {
        border-bottom: 0.75px dashed;
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
    
    function getData(){
        var xhr = new XMLHttpRequest();
        
        var amount = param.amount;

        if(amount !== ""){
            toSend.amount = amount;
        }

        toSend.date = param.date;
        toSend.disc_month = param.month;
        toSend.selected = param.selected;
        toSend.location = param.location;
        toSend.constype = param.constype;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var listConsDisco = "{{route('list.disco')}}";
        xhr.open('POST', listConsDisco, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        var output = "";
        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var details = data.Details;

                output += '<center> <label id="lasuhead"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                output += '<label style="font-size: 18px;"> LIST OF CONSUMER FOR DISCONNECTION </label> <br>';
                output += '<label style="font-size: 18px;">' + param.date + '  ' + param.month + ' months </center> <br>';
                output += '<table id="table"><tr>';
                output += '<th class="left"> Account </th>';
                output += '<th> Name </th>';
                output += '<th> Address </th>';
                output += '<th> Meter Serial Number </th>';
                output += '<th> Last Payment Date </th>';
                output += '<th> Total Arrears </th>';
                output += '</tr>';
                
                var num = 0; 

                for(var i in details){
                    num += 1;
                    if(num > 0 && num % 30 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center><label id="lasuhead"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                        output += '<label style="font-size: 18px;"> LIST OF CONSUMER FOR DISCONNECTION <br>';
                        output += '<label style="font-size: 18px;">' + param.date + '  ' + param.month + ' months </center> <br>';
                        output += '<table id="table"><tr>';
                        output += '<th class="left"> Account </th>';
                        output += '<th> Name </th>';
                        output += '<th> Address </th>';
                        output += '<th> Meter Serial Number </th>';
                        output += '<th> Last Payment Date </th>';
                        output += '<th> Total Arrears </th>';
                        output += '</tr><tr>';
                        output += '<td class="left">' + details[i].ACCOUNT + '</td>';
                        output += '<td class="left">' + details[i].NAME + '</td>';
                        output += '<td class="left">' + details[i].ADDRESS + '</td>';
                        output += '<td class="left">' + details[i].Meter_No + '</td>';
                        output += '<td class="left">' + details[i].LAST_PAYMENT_DATE + '</td>';
                        output += '<td class="left">' + details[i].Total_Arrears.toLocaleString("en-US") + '</td>';
                        output += '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td class="left">' + details[i].ACCOUNT + '</td>';
                        output += '<td class="left">' + details[i].NAME + '</td>';
                        output += '<td class="left">' + details[i].ADDRESS + '</td>';
                        output += '<td class="left">' + details[i].Meter_No + '</td>';
                        output += '<td class="left">' + details[i].LAST_PAYMENT_DATE + '</td>';
                        output += '<td class="left">' + details[i].Total_Arrears.toLocaleString("en-US") + '</td>';
                        output += '</tr>';
                    }
                }
            }
            else if(xhr.status == 422){
                alert('No Consumers Found');
                window.close();
            }

            document.querySelector('#printBody').innerHTML = output;
        }
    }
</script>