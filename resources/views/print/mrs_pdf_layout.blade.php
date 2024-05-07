<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>METER READING SHEET</title>

</head>
<style media="print">
    @page {
      size: letter;
      margin: 0;
    }
</style>
<style>
    body{
        padding-top:20px;
        padding-left:10px;
        padding-right:10px;
    }
    .page-break {
        page-break-after: always;
    }

    body section{
        margin: auto;
        width: 85%;
        text-align: center;
        font-family: calibri;
    }
    td{
        padding:8px;
    }
    .abaka {
        border-bottom: 1px solid black !important;
    }
</style>

<body onload="getData()">
<div id = "printBody">
    
</div>
</body>
</html>
<script>
    function getData(){
        var xhr = new XMLHttpRequest();
        var param = JSON.parse(localStorage.getItem("data"));
        var reader = param.reader;
        var routeID = param.route;
        var date = param.date;
        var chk = param.check;
        var c = 0;
        var route = "{{route('mrs.print', '?routeID=')}}" + routeID + "&date=" + date + "&check=" + chk;
        console.log(route);
        // xhr.open('GET','http://10.12.10.100:8082/api/v1/mrs_print?routeID=' +routeID+ '&date=' +date+ '&check=' +chk);
        xhr.open('GET', route, true);
        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var mrs = data.data;
                console.log(data);
                var output = " ";
                var output2 = " ";
                var j = 0;
                output += '<label style="margin-left:85%;position:absolute;margin-top:120%;">PAGE: 1' + '</label>';
                output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
                output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
                output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
                output += '<label style="font-size: 20px;">' + 'METER READING SHEET' + '</label><br>';
                output += '<label style="font-size:20px;"> For the Month of ' + data.date + '</label></center><br>';
                output += '<label style="font-weight:bold;"> AREA CODE :' + data.route[0].town_code.ac_id + ' ' + data.route[0].town_code.area_code.ac_name + '</label><br>';
                output += '<label style="font-weight:bold;"> TOWN CODE :' + data.route[0].town_code.tc_code + ' ' + data.route[0].town_code.tc_name + '</label><br>';
                output += '<label style="font-weight:bold;"> ROUTE CODE:' + data.route[0].rc_code + ' ' + data.route[0].rc_desc + '</label><br><br>';
                output += '<table style="font-size:10px;width:100%;margin:auto;height:100px"><tr>';
                output += '<th>Status</th>';
                output += '<th>Acct #</th>';
                output += '<th style = "width:50px;">Seq #</th>';
                output += '<th >Type</th>';
                output += '<th style = "text-align:left;width:200px;">Name</th>';
                output += '<th style="text-align:left">Meter No.</th>';
                output += '<th style = "width:20px;">Mult</th>';
                output += '<th>Pres. R.</th>';
                output += '<th>Read Date</th>';
                output += '<th>Remarks</th>';
                output += '</tr>';
                for(var i in mrs){
                    // var num = parseInt(i)+1;
                    var fullname = mrs[i].cm_full_name.slice(0,30);
                    if(mrs[i].mm_serial_no == null){
                            mrs[i].mm_serial_no = 0;
                        }
                    if(i > 0 && i%22 == 0){
                        j++;
                       
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output +='<div style="padding-top:20px;padding-left:10px;padding-right:10px"></div>';
                        output += '<label style="margin-left:85%;position:absolute;margin-top:120%;">PAGE:' + parseInt(j+1) + '</label>';
                        output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
                        output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
                        output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
                        output += '<label style="font-size: 20px;">' + 'METER READING SHEET' + '</label><br>';
                        output += '<label style="font-size:20px;"> For the Month of ' + data.date + '</label></center><br>';
                       
                        output += '<label style="font-weight:bold;"> AREA CODE:' + data.route[0].town_code.ac_id + ' ' + data.route[0].town_code.area_code.ac_name + '</label><br>';
                        output += '<label style="font-weight:bold;"> TOWN CODE:' + data.route[0].tc_id + ' ' + data.route[0].town_code.tc_name + '</label><br>';
                        output += '<label style="font-weight:bold;"> ROUTE CODE:' + data.route[0].rc_code + ' ' + data.route[0].rc_desc + '</label><br><br>';
                        output += '<table style="font-size:10px;width:100%;height:100px"><tr>';
                        output += '<th>Status</th>';
                        output += '<th>Acct #</th>';
                        output += '<th style = "width:50px;">Seq #</th>';
                        output += '<th>Type</th>';
                        output += '<th style = "text-align:left;width:200px;">Name</th>';
                        output += '<th style="text-align:left">Meter No.</th>';
                        output += '<th style = "width:20px;">Mult</th>';
                        output += '<th>Pres. R.</th>';
                        output += '<th>Read Date</th>';
                        output += '<th>Remarks</th>';
                        output += '</tr>';
                            '<td style = "width:5%;text-align:center;border-bottom:2px solid black">' + mrs[i].cm_con_status[0] +'</td>'+
                            '<td style = "width:5%;border-bottom:2px solid black">' + mrs[i].cm_account_no + '</td>'+
                            '<td style="text-align:center">' + mrs[i].cm_seq_no + '</td>'+
                            '<td style="text-align:center">' + mrs[i].ct_code + '</td>'+
                            '<td >' + fullname +'.' + '</td>'+
                            '<td >' + mrs[i].mm_serial_no + '</td>'+
                            '<td >' + mrs[i].cm_kwh_mult + '</td>'+
                            '<td style = "border-bottom:1px solid black">' + ' '+ '</td>'+
                            '<td style = "border-bottom:1px solid black">' + ' ' + '</td>'+
                            '<td style = "border-bottom:1px solid black">' + ' ' + '</td>'+
                            '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td style = "text-align:center;">' + mrs[i].cm_con_status[0] +'</td>';
                        output += '<td >' + mrs[i].cm_account_no + '</td>';
                        output += '<td style="text-align:center">' + mrs[i].cm_seq_no + '</td>';
                        output += '<td style="text-align:center">' + mrs[i].ct_code + '</td>';
                        output += '<td >' + fullname +'.' + '</td>';
                        output += '<td >' + mrs[i].mm_serial_no + '</td>';
                        output += '<td >' + mrs[i].cm_kwh_mult + '</td>';
                        output += '<td style = "border-bottom:1px solid black">' + ' ' + '</td>';
                        output += '<td style = "border-bottom:1px solid black">' + ' ' + '</td>';
                        output += '<td style = "border-bottom:1px solid black">' + ' ' + '</td>';
                        output += '</tr>';
                    }
                }       
                output += '<table style="font-size:12px">';
                output += '<tr><td>Total Accounts:' + data.Count+ '</td></tr>'; 
                output += '<tr><td style="padding-left:50px;">Reader:</td><td>' + reader +'</td></tr>';
                output += '<tr><td style="padding-left:50px;">Reader Signature:</td><td>______________________</td></tr>';
                // output += '<tr><td style="padding-left:50px;">Reading Date:</td><td>______________________</td></tr>';
                output += '<tr><td style="padding-left:50px;">Due Date:</td><td>______________________</td></tr>';
                output += '</table>';
            }
            else if(xhr.status == 404){
                    alert('No Consumer Found');
                    // window.close();
                 }

            document.querySelector('#printBody').innerHTML = output;
            window.print();
            // window.close();

        }
        xhr.send();
    }
    </script>
