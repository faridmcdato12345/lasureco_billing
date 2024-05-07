<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Reconnection Report </title>

</head>
<style media="print">
    @page {
      size: landscape;
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
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    
    toSend.date_from = param.date_from;
    toSend.date_to = param.date_to;
    toSend.selected = param.selected;
    toSend.id = param.id;

    function getData(){
        var xhr = new XMLHttpRequest();
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var reconnectionReport = "{{route('collection.reconnection.report')}}";
        xhr.open('POST', reconnectionReport, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var info = data.info;

                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> RECONNECTION REPORT </label><br>';
                output += '<label style="font-size: 18px;">' + param.date_from + ' to ' + param.date_from +'</label> </center><br>';
                output += '<table id="table" class="bot"><tr>';
                output += '<th class="left top bot"> Account No </th>';
                output += '<th class="left top bot"> Account Name </th>';
                output += '<th class="left top bot"> Address </th>';
                output += '<th class="left top bot"> Meter No </th>';
                output += '<th class="left top bot"> Reconnection Date </th>';
                output += '<th class="left top bot"> Reconnection Amount </th>';
                output += '</tr>';

                for(var i in info){
                    if(i > 0 && i%25 == 0){
                        output +='<div class="page-break"></div>';
                        output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> RECONNECTION REPORT </label> <br>';
                        output += '<label style="font-size: 18px;">' + param.date_from + ' to ' + param.date_to +'</label> </center><br>';
                        output += '<table id="table" class="bot"><tr>';
                        output += '<th class="left top bot"> Account No </th>';
                        output += '<th class="left top bot"> Account Name </th>';
                        output += '<th class="left top bot"> Address </th>';
                        output += '<th class="left top bot"> Meter No </th>';
                        output += '<th class="left top bot"> Reconnection Date </th>';
                        output += '<th class="left top bot"> Reconnection Amount </th></tr>';
                        output += '<tr>';
                        output += '<td class="left">' + info[i].account_no + '</td>';
                        output += '<td class="left">' + info[i].name + '</td>';
                        output += '<td class="left">' + info[i].address + '</td>';
                        output += '<td class="left">' + info[i].meter_no + '</td>';
                        output += '<td class="left">' + info[i].reconnect_date + '</td>';
                        output += '<td class="left">' + info[i].reconnect_amount + '</td>';
                        output += "</tr>";
                    } else {
                        output += '<tr>';
                        output += '<td class="left">' + info[i].account_no + '</td>';
                        output += '<td class="left">' + info[i].name + '</td>';
                        output += '<td class="left">' + info[i].address + '</td>';
                        output += '<td class="left">' + info[i].meter_no + '</td>';
                        output += '<td class="left">' + info[i].reconnect_date + '</td>';
                        output += '<td class="left">' + info[i].reconnect_amount + '</td>';
                        output += '</tr>';
                    }
                }
                output += "</table>";

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Report Found');
                window.close();
            }
        }
    }
</script>