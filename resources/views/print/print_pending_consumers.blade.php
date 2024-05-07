<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Pending Consumers </title>

</head>
<style media="print">
    @page {
        size: A4;
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
    table {
        margin: auto;
        width: 97%;
        font-size: 15px;
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
    
    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.id = param.id;
        toSend.filter = param.filter;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;

        var printPending = "{{route('print.pending')}}";
        xhr.open('POST', printPending, true);

        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var details = data.Details;

                var output = " ";
                var num = 0;

                output += '<center> <br> <label id="lasuhead"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                output += '<label style="font-size: 18px;"> PENDING CONSUMERS </lable> <br><br> </center>';
                // output += '<lable style="font-size: 18px;">' + billdate + '</lable> </center>';
                output += '<table id="table"><tr>';
                output += '<th class="left top bot"> Account Number </th>';
                output += '<th class="left top bot"> Name </th>';
                output += '<th class="left top bot"> Address </th>';
                output += '<th class="left top bot"> Pending Since </th>';
                output += '</tr>';
            
                for(var i in details){
                    num += 1;
                    if(num > 0 && num % 50 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <br> <label id="lasuhead"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                        output += '<label style="font-size: 18px;"> PENDING CONSUMERS </lable> <br><br> </center>';
                        // output += '<lable style="font-size: 18px;">' + billdate + '</label></center> <br><br>';
                        output += '<table id="table"><tr>';
                        output += '<th class="left top bot"> Account Number </th>';
                        output += '<th class="left top bot"> Name </th>';
                        output += '<th class="left top bot"> Address </th>';
                        output += '<th class="left top bot"> Pending Since </th>';
                        output += '</tr><tr>';
                        output += '<td class="left"> &nbsp;' + details[i].Account_No + '</td>';
                        output += '<td class="left"> &nbsp;' + details[i].Name + '</td>';
                        output += '<td class="left"> &nbsp;' + details[i].Address + '</td>';
                        output += '<td class="left"> &nbsp;' + details[i].Date_Entered + '</td>';
                        output += '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td class="left"> &nbsp;' + details[i].Account_No + '</td>';
                        output += '<td class="left"> &nbsp;' + details[i].Name + '</td>';
                        output += '<td class="left"> &nbsp;' + details[i].Address + '</td>';
                        output += '<td class="left"> &nbsp;' + details[i].Date_Entered + '</td>';
                        output += '</tr>';
                    }
                }

                output += "</table>";

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Consumers Found');
                window.close();
            }
        }
    }
</script>