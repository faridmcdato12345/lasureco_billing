<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>SUMMARY OF POSTED NON-BILL</title>

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
    table{
        margin:auto;
        width: 90%;
        height:100px
    }
    th{
        border-bottom: 1px solid #555;
    }
    td {
        text-align: center;
        height: 30px;
    }
</style>

<body onload="getData()">
    <div id = "printBody">
    </div>
</body>
</html>

<script>
    var toSend = new Object();
    function getData(){
        var xhr = new XMLHttpRequest();
        var param = JSON.parse(localStorage.getItem("data"));
        var ac_id = param.ac_id;
        var to = param.to;
        var from = param.from;
        var areaName = param.areaName;
        
        toSend.ac_id = ac_id;
        toSend.to = to;
        toSend.from = from;
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        // var route = "{{route('print.collection.nonbill.summary')}}";
        xhr.open('POST', 'http://10.12.10.100:8082/api/v1/fees/summary');
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var summNB = data.NonBill;
                
                var output = " ";
                var output2 = " ";
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> ' + areaName + ' </label><br><br>';  
                output += '<label style="font-size: 20px;"> NON-BILL COLLECTION </label><br>';
                output += '<label style="font-size:20px;"> DATE COLLECTED: ' + from + ' to ' + to + ' </label> </center> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th> ACCOUNT NO </th>';
                output += '<th> NAME </th>';
                output += '<th> SURCHARGE </th>';
                output += '<th> PB INTEG </th>';
                output += '<th> MCT </th>';
                output += '<th> ALLOCATION </th>';
                output += '<th> TSF </th>';
                output += '<th> POLE </th>';
                output += '<th> SUNDRIES </th>';
                output += '<th> TOTAL </th>';
                output += '</tr>';
                for(var i in summNB){
                    var num = parseInt(i)+1;
                    if(i > 0 && i%20 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> ' + areaName + ' </label><br><br>';  
                        output += '<label style="font-size: 20px;"> NON-BILL COLLECTION </label><br>';
                        output += '<label style="font-size:20px;"> DATE COLLECTED: ' + from + ' to ' + to + ' </label> </center> <br>';
                        output += '<table id="table"><tr>';
                        output += '<th> ACCOUNT NO </th>';
                        output += '<th> NAME </th>';
                        output += '<th> SURCHARGE </th>';
                        output += '<th> PB INTEG </th>';
                        output += '<th> MCT </th>';
                        output += '<th> ALLOCATION </th>';
                        output += '<th> TSF </th>';
                        output += '<th> POLE </th>';
                        output += '<th> SUNDRIES </th>';
                        output += '<th> TOTAL </th>';
                        output += '</tr> <tr>';
                        output += '<td>' + summNB[i].cm_account_no + '</td>';
                        output += '<td style="text-align: left;"> &nbsp;&nbsp;' + summNB[i].cm_full_name + '</td>';
                        output += '<td> 0.00 </td>';
                        output += '<td> 0.00 </td>';
                        output += '<td> 0.00 </td>';
                        output += '<td> 0.00 </td>';
                        output += '<td> 0.00 </td>';
                        output += '<td> 0.00 </td>';
                        output += '<td>' + summNB[i].s_bill_amount + '</td>';
                        output += '<td>' + summNB[i].s_bill_amount + '</td>';
                        output += '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td>' + summNB[i].cm_account_no + '</td>';
                        output += '<td style="text-align: left;"> &nbsp;&nbsp;' + summNB[i].cm_full_name + '</td>';
                        output += '<td> 0.00 </td>';
                        output += '<td> 0.00 </td>';
                        output += '<td> 0.00 </td>';
                        output += '<td> 0.00 </td>';
                        output += '<td> 0.00 </td>';
                        output += '<td> 0.00 </td>';
                        output += '<td>' + summNB[i].s_bill_amount + '</td>';
                        output += '<td>' + summNB[i].s_bill_amount + '</td>';
                        output += '</tr>';
                    }
                }
            }
            else if(xhr.status == 422){
                alert('No Consumer Found');
                window.close();
            }

            document.querySelector('#printBody').innerHTML = output;
        }
    }
</script>