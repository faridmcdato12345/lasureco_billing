<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print MRR 1 </title>

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

        var routeId = param.routeId;
        var month = param.month;
    }

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date_period = month;
        toSend.route_id = routeId;
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var mrrInq1 = "{{route('report.meter.reading.inquiry.consumer')}}";
        xhr.open('POST', mrrInq1, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var mrrInq1 = data.MRR_INQ_1;
                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> PRINT MRR 1 </label><br>';
                output += '<table id="table"><tr>';
                output += '<th> Account Number </th>';
                output += '<th> CT </th>';
                output += '<th> Stat </th>';
                output += '<th> Date Read </th>';
                output += '<th> Time Read </th>';
                output += '<th> Last Rdng Date </th>';
                output += '<th> Prev Rdng </th>';
                output += '<th> Pres Rdng </th>';
                output += '<th> Meter Mult </th>';
                output += '<th> KWH Used </th>';
                output += '<th> Curr Amnt Due </th>';
                output += '<th> Total Amnt Due </th>';
                output += '<th> Read </th>';
                output += '<th> Print </th>';
                output += '<th> User </th>';
                output += '</tr>';
                
                for(var i in mrrInq1){
                    if(i > 0 && i % 20 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> PRINT MRR 1</label><br>';
                        output += '<table id="table"><tr>';
                        output += '<th> Account Number </th>';
                        output += '<th> CT </th>';
                        output += '<th> Stat </th>';
                        output += '<th> Date Read </th>';
                        output += '<th> Time Read </th>';
                        output += '<th> Last Rdng Date </th>';
                        output += '<th> Prev Rdng </th>';
                        output += '<th> Pres Rdng </th>';
                        output += '<th> Meter Mult </th>';
                        output += '<th> KWH Used </th>';
                        output += '<th> Curr Amnt Due </th>';
                        output += '<th> Total Amnt Due </th>';
                        output += '<th> Read </th>';
                        output += '<th> Print </th>';
                        output += '<th> User </th>';
                        output += '</tr> <tr>';
                        output += '<td>' + mrrInq1[i].Account_Number +'</td>';
                        output += '<td>' + mrrInq1[i].CT +'</td>';
                        output += '<td>' + mrrInq1[i].Status +'</td>';
                        output += '<td>' + mrrInq1[i].Date_Read +'</td>';
                        output += '<td>' + mrrInq1[i].Time_Read +'</td>';
                        output += '<td>' + mrrInq1[i].Last_Reading_Date +'</td>';
                        output += '<td>' + mrrInq1[i].Prev_Reading +'</td>';
                        output += '<td>' + mrrInq1[i].Pres_Reading +'</td>';
                        output += '<td>' + mrrInq1[i].Mtr_Mult +'</td>';
                        output += '<td>' + mrrInq1[i].KWH_Used +'</td>';
                        output += '<td>' + mrrInq1[i].Curr_Amount_Due +'</td>';
                        output += '<td>' + mrrInq1[i].Total_Amount_Due +'</td>';
                        output += '<td>' + mrrInq1[i].Read +'</td>';
                        output += '<td>' + mrrInq1[i].Print +'</td>';
                        output += '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td>' + mrrInq1[i].Account_Number +'</td>';
                        output += '<td>' + mrrInq1[i].CT +'</td>';
                        output += '<td>' + mrrInq1[i].Status +'</td>';
                        output += '<td>' + mrrInq1[i].Date_Read +'</td>';
                        output += '<td>' + mrrInq1[i].Time_Read +'</td>';
                        output += '<td>' + mrrInq1[i].Last_Reading_Date +'</td>';
                        output += '<td>' + mrrInq1[i].Prev_Reading +'</td>';
                        output += '<td>' + mrrInq1[i].Pres_Reading +'</td>';
                        output += '<td>' + mrrInq1[i].Mtr_Mult +'</td>';
                        output += '<td>' + mrrInq1[i].KWH_Used +'</td>';
                        output += '<td>' + mrrInq1[i].Curr_Amount_Due +'</td>';
                        output += '<td>' + mrrInq1[i].Total_Amount_Due +'</td>';
                        output += '<td>' + mrrInq1[i].Read +'</td>';
                        output += '<td>' + mrrInq1[i].Print +'</td>';
                        output += '</tr> <tr>';
                    }
                }

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Consumer Found');
                window.close();
            }
        }
    }
</script>