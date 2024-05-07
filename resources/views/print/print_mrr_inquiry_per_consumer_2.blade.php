<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print MRR Inquiry Consumer 2 </title>

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
        /* margin:auto; */
        height:100px;
        font-size: 17px;
        width: 97%; 
    }
    th {
        height: 60px;
    }
    td {
        text-align: center;
        height: 50px;
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

        var accountId = param.accountId;
        var monthFrom = param.monthFrom;
        var monthTo= param.monthTo;
    }

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.cons_id = accountId;
        toSend.date_period_from = monthFrom;
        toSend.date_period_to = monthTo;
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var mrrInqCons2 = "{{route('report.meter.reading.inquiry.consumer2')}}";
        xhr.open('POST', mrrInqCons2, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var consInq = data.MRR_CONS_INQ;
                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> PRINT MRR INQUIRY PER CONSUMER </label><br>';
                output += '<table id="table"><tr>';
                output += '<th> Date Read </th>';
                output += '<th> Bill No </th>';
                output += '<th> Year No </th>';
                output += '<th> Status </th>';
                output += '<th> Pres Reading </th>';
                output += '<th> Prev Reading </th>';
                output += '<th> KWH Used </th>';
                output += '<th> Current Amount Due </th>';
                output += '<th> Total Amount Due </th>';
                output += '</tr>';
                
                for(var i in consInq){
                    if(i > 0 && i % 20 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> PRINT MRR INQUIRY PER CONSUMER </label><br>';
                        output += '<table id="table"><tr>';
                        output += '<th> Date Read </th>';
                        output += '<th> Bill No </th>';
                        output += '<th> Year No </th>';
                        output += '<th> Status </th>';
                        output += '<th> Pres Reading </th>';
                        output += '<th> Prev Reading </th>';
                        output += '<th> KWH Used </th>';
                        output += '<th> Current Amount Due </th>';
                        output += '<th> Total Amount Due </th>';
                        output += '</tr> <tr>';
                        output += '<td>' + consInq[i].Date_Read +'</td>';
                        output += '<td>' + consInq[i].Bill_No +'</td>';
                        output += '<td>' + consInq[i].Yr_Mo +'</td>';
                        output += '<td>' + consInq[i].Status +'</td>';
                        output += '<td>' + consInq[i].Pres_Reading +'</td>';
                        output += '<td>' + consInq[i].Prev_Reading +'</td>';
                        output += '<td>' + consInq[i].KWH_Used +'</td>';
                        output += '<td>' + consInq[i].Curr_Amount_Due +'</td>';
                        output += '<td>' + consInq[i].Total_Amount_Due +'</td>';
                        output += '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td>' + consInq[i].Date_Read +'</td>';
                        output += '<td>' + consInq[i].Bill_No +'</td>';
                        output += '<td>' + consInq[i].Yr_Mo +'</td>';
                        output += '<td>' + consInq[i].Status +'</td>';
                        output += '<td>' + consInq[i].Pres_Reading +'</td>';
                        output += '<td>' + consInq[i].Prev_Reading +'</td>';
                        output += '<td>' + consInq[i].KWH_Used +'</td>';
                        output += '<td>' + consInq[i].Curr_Amount_Due +'</td>';
                        output += '<td>' + consInq[i].Total_Amount_Due +'</td>';
                        output += '</tr> <tr>';
                    }
                }

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Consumer Found');
                //window.close();
            }
        }
    }
</script>