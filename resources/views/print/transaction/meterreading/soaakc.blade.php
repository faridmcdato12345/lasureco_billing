<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOA Acknowledgement Receipt</title>

</head>
<style media="print">
    @page {
      size: letter;
      margin: 0;
    }
</style>
<style>
    body{
        font-family:calibri;
        font-size:18px;
        padding-left:30px;
        padding-right:30px;
        padding-top:250px;
    }
    .page-break {
        page-break-after: always;
    }

    body section{
        margin: auto;
        width: 85%;
        text-align: center;
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
            var data = JSON.parse(localStorage.getItem('data'));
            console.log(data);
            var j = 0;
                var output = " ";
                var output2 = " ";
                output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
                output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
                output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
                output += '<label style="font-size: 20px;">' + 'SOA of Aknowledgement Receipt' + '</label><br>';
                output += '<label style="font-size:20px;"> For the Month of ' + data.Date + '</label></center><br>';
                output += '<label style="font-weight:bold;">TOWN CODE<p style="display:inline;margin-left:7.2px;">:</p>' + ' '+ data.Consumers[0].tc_code  + ' ' + data.Consumers[0].tc_name  + '</label><br>';
                output += '<label style="font-weight:bold;"> ROUTE CODE:' + ' ' + data.Consumers[0].rc_code  + ' ' + data.Consumers[0].rc_desc  + '</label><br><br>';
                output += '<table style="width:100%;margin:auto;height:100px"><tr>';
                output += '<th style = "border-bottom:2px solid blue">SEQ#</th>';
                output += '<th style = "border-bottom:2px solid blue">Account#</th>';
                output += '<th style = "border-bottom:2px solid blue">Name</th>';
                output += '<th style = "border-bottom:2px solid blue">Type</th>';
                output += '<th style = "border-bottom:2px solid blue">M/Serial#</th>';
                output += '<th style = "border-bottom:2px solid blue">Remarks</th>';
                output += '</tr>';
                for(var i in data.Consumers){
                    if(i > 0 && i%20 == 0){
                        j++;
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<div style="padding-top:50px;padding-left:50px;padding-right:50px"></div>';
                        output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
                        output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
                        output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
                        output += '<label style="font-size: 20px;">' + 'METER READING SHEET' + '</label><br>';
                        output += '<label style="font-size:20px;"> For the Month of ' + data.Date + '</label></center><br>';
                        output += '<label>page:' + parseInt(j+1) + '</label><br>';
                        output += '<label style="font-weight:bold;"> TOWN CODE:' + data.Consumers[0].tc_code  + ' ' + data.Consumers[0].tc_name  + '</label><br>';
                        output += '<label style="font-weight:bold;"> ROUTE CODE:' + data.Consumers[0].rc_code  + ' ' + data.Consumers[0].rc_desc  + '</label><br><br>';
                        output += '<table style="width:100%;margin:auto;height:100px"><tr>';
                        output += '<th style = "border-bottom:2px solid blue">SEQ#</th>';
                        output += '<th style = "border-bottom:2px solid blue">Account#</th>';
                        output += '<th style = "border-bottom:2px solid blue">Name</th>';
                        output += '<th style = "border-bottom:2px solid blue">Type</th>';
                        output += '<th style = "border-bottom:2px solid blue">M/Serial#</th>';
                        output += '<th style = "border-bottom:2px solid blue">Remarks</th>';
                        output += '</tr>';
                        output += '<tr>'+
                            '<td style = "text-align:center;width:5%;border-bottom:2px solid black">' + data.Consumers[i].cm_seq_no+ '</td>'+
                            '<td style = "width:5%;border-bottom:2px solid black">' + data.Consumers[i].cm_account_no + '</td>'+
                            '<td style = "width:20%;border-bottom:2px solid black">' + data.Consumers[i].cm_full_name + '</td>'+ 
                            '<td style = "text-align:center;width:5%;border-bottom:2px solid black">' + data.Consumers[i].ct_code + '</td>'+                       
                            '<td style = "width:5%;border-bottom:2px solid black">' + data.Consumers[i].mm_serial_no + '</td>'+                            
                            '<td style = "border-bottom:2px solid black">' + ' '+ '</td>'+
                            '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td style = "text-align:center;width:5%;border-bottom:2px solid black">' + data.Consumers[i].cm_seq_no+ '</td>';
                        output += '<td style = "width:5%;border-bottom:2px solid black">' + data.Consumers[i].cm_account_no  + '</td>';
                        output += '<td style = "width:20%;border-bottom:2px solid black">' + data.Consumers[i].cm_full_name + '</td>';
                        output += '<td style = "text-align:center;width:5%;border-bottom:2px solid black">' + data.Consumers[i].ct_code+ '</td>'; 
                        output += '<td style = "width:5%;border-bottom:2px solid black">' + data.Consumers[i].mm_serial_no + '</td>';
                        output += '<td style = "width:5%;border-bottom:2px solid black">' + ' '+ '</td>';
                        output += '</tr>';
                    }
                }
                output += '<table>';
                output += '<tr>';
                output += '<td>' + 'Meter Reader:' + '</td>';
                output += '<td>' + data.Meter_Readers + '</td>';
                output += '</tr>';
                output += '<tr>';
                output += '<td>' + 'Date Read:' + '</td>';
                output += '<td style = "border-bottom:2px solid black">' + ' ' + '</td>';
                output += '</tr>';
                output += '<tr>';
                output += '<td>' + 'Total Cons:' + '</td>';
                output += '<td>' + data.Consumers.length + '</td>';
                output += '</tr>';
                output += '</table>';
            document.querySelector('#printBody').innerHTML = output;
            window.print();

    }
    </script>
