<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Collection Report on VAT per date </title>

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
        margin:auto;
        font-size: 15px;
        width: 97%; 
        border-top: 1px dashed;
        border-right: 1px dashed;
        border-bottom: 1px dashed;
    }
    th {
        text-align: center;
        font-weight: 700;
    }.top {
        border-top: 1px dashed;
        text-align: center;
    }
    .bot {
        border-bottom: 1px dashed;
        text-align: center;
    }
    .left {
        border-left: 1px dashed;
        text-align: center;
    }
    .right {
        border-right: 1px dashed;
        text-align: center;
    }
    #revenueTable {
        margin-left: 5%;
        width: 33%;
    }
    #revenueTable th {
        height: 30px;
        text-align: left !important;
    }
    #evatTable {
        width: 50%;
        margin-left: 5%;
    }
    #evatTable th {
        height: 30px;
        text-align: left !important;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
</div>
</body>
</html>

<script>
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();

    var toSend = new Object();

    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    var param = JSON.parse(localStorage.getItem("data"));
    
    var date_from = param.date_from;
    var date_to = param.date_to;

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date_from = date_from;
        toSend.date_to = date_to;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var collectionVat = "{{route('collection.vat')}}";
        xhr.open('POST', collectionVat, true);
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

                var dateFrom = param.date_from;
                var year = dateFrom.slice(0,4);
                var month = dateFrom.slice(5,7);
                var day = dateFrom.slice(8,10)
                var dateTo = param.date_to;
                var yearTo = dateTo.slice(0,4);
                var monthTo = dateTo.slice(5,7);
                var dayTo = dateTo.slice(8,10)

                var totalGenVat = 0;
                var totalTransSYs = 0;
                var totalSysLoss = 0;
                var totalDistSys = 0;
                var totalOthers = 0;
                var totalDistDem = 0;
                var totalTransDem = 0;
                var totalLCondKwh = 0;
                var totalLCondFix = 0;
                var totalMtrfixvat = 0;
                var totalMtrsysvat = 0;
                var totalParvat = 0;
                var totalSupplyfixvat = 0;
                var totalSupsysvat = 0;
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> COLLECTION REPORT ON VAT PER DATE </label><br>';
                output += '<label style="font-size: 16px;">' + months[month - 1] + ' ' + day + ', ' + year;
                output += ' - ' + months[monthTo - 1] + ' ' + dayTo + ', ' + yearTo + '</label> <br> </center>';
                output += '<label style="font-size:16px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th class="bot left"> Date </th>';
                output += '<th class="bot left"> Generation </th>';
                output += '<th class="bot left"> Trans. Sys. </th>';
                output += '<th class="bot left"> Sys. Loss </th>';
                output += '<th class="bot left"> Dist. Sys. </th>';
                output += '<th class="bot left"> Others </th>';
                output += '<th class="bot left"> Dist. Dem. </th>';
                output += '<th class="bot left"> Trans. Dem. </th>';
                output += '<th class="bot left"> LCond KWH </th>';
                output += '<th class="bot left"> LCond FIX </th>';
                output += '<th class="bot left"> Meter Fix VAT </th>';
                output += '<th class="bot left"> Meter Sys VAT </th>';
                output += '<th class="bot left"> Power Act Red VAT </th>';
                output += '<th class="bot left"> Supp Fix VAT </th>';
                output += '<th class="bot left"> Sup Sys VAT </th> </tr> <tr>';
            
                for(var j in info){
                    if(j > 0 && j % 49 == 0) {
                        output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> COLLECTION REPORT ON VAT PER DATE </label><br>';
                        output += '<label style="font-size: 16px;">' + months[month - 1] + ' ' + day + ', ' + year;
                        output += ' - ' + months[monthTo - 1] + ' ' + dayTo + ', ' + yearTo + '</label> <br> </center>';
                        output += '<label style="font-size:16px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                        output += '<table id="table"><tr>';
                        output += '<th class="bot left"> Date </th>';
                        output += '<th class="bot left"> Generation </th>';
                        output += '<th class="bot left"> Trans. Sys. </th>';
                        output += '<th class="bot left"> Sys. Loss </th>';
                        output += '<th class="bot left"> Dist. Sys. </th>';
                        output += '<th class="bot left"> Others </th>';
                        output += '<th class="bot left"> Dist. Dem. </th>';
                        output += '<th class="bot left"> Trans. Dem. </th>';
                        output += '<th class="bot left"> LCond KWH </th>';
                        output += '<th class="bot left"> LCond FIX </th>';
                        output += '<th class="bot left"> Meter Fix VAT </th>';
                        output += '<th class="bot left"> Meter Sys VAT </th>';
                        output += '<th class="bot left"> Power Act Red VAT </th>';
                        output += '<th class="bot left"> Supp Fix VAT </th>';
                        output += '<th class="bot left"> Sup Sys VAT </th> </tr> <tr>';
                        output += '<td class="left">' + j + '</td>';
                        output += '<td class="left">' + info[j].genvat.toLocaleString("en-US") + '</td>';
                        totalGenVat +=  info[j].genvat;
                        output += '<td class="left">' + info[j].transvat.toLocaleString("en-US") + '</td>';
                        totalTransSYs += info[j].transvat;
                        output += '<td class="left">' + info[j].syslossvat.toLocaleString("en-US") + '</td>';
                        totalSysLoss += info[j].syslossvat;
                        output += '<td class="left">' + info[j].distsysvat.toLocaleString("en-US") + '</td>';
                        totalDistSys += info[j].distsysvat;
                        output += '<td class="left">' + info[j].others_vat.toLocaleString("en-US") + '</td>';
                        totalOthers += parseFloat(info[j].others_vat);
                        output += '<td class="left">' + info[j].distdemvat.toLocaleString("en-US") + '</td>';
                        totalDistDem += info[j].distdemvat;
                        output += '<td class="left">' + info[j].transdemvat.toLocaleString("en-US") + '</td>';
                        totalTransDem += info[j].transdemvat;
                        output += '<td class="left">' + info[j].loancondvat.toLocaleString("en-US") + '</td>';
                        totalLCondKwh += info[j].loancondvat;
                        output += '<td class="left">' + info[j].loancondifixvat.toLocaleString("en-US") + '</td>';
                        totalLCondFix += info[j].loancondifixvat;
                        output += '<td class="left">' + info[j].mtrfixvat.toLocaleString("en-US") + '</td>';
                        totalMtrfixvat += info[j].mtrfixvat;  
                        output += '<td class="left">' + info[j].mtrsysvat.toLocaleString("en-US") + '</td>';
                        totalMtrsysvat += info[j].mtrsysvat;
                        output += '<td class="left">' + info[j].parvat.toLocaleString("en-US") + '</td>';
                        totalParvat += info[j].parvat;
                        output += '<td class="left">' + info[j].supplyfixvat.toLocaleString("en-US") + '</td>';
                        totalSupplyfixvat += info[j].supplyfixvat;
                        output += '<td class="left">' + info[j].supsysvat.toLocaleString("en-US") + '</td>';
                        totalSupsysvat += info[j].supsysvat;
                        output += '</tr>';
                    } else { 
                        output += '<tr> <td class="left">' + j + '</td>';
                        output += '<td class="left">' + info[j].genvat.toLocaleString("en-US") + '</td>';
                        totalGenVat +=  info[j].genvat;
                        output += '<td class="left">' + info[j].transvat.toLocaleString("en-US") + '</td>';
                        totalTransSYs += info[j].transvat;
                        output += '<td class="left">' + info[j].syslossvat.toLocaleString("en-US") + '</td>';
                        totalSysLoss += info[j].syslossvat;
                        output += '<td class="left">' + info[j].distsysvat.toLocaleString("en-US") + '</td>';
                        totalDistSys += info[j].distsysvat;
                        output += '<td class="left">' + info[j].others_vat.toLocaleString("en-US") + '</td>';
                        totalOthers += parseFloat(info[j].others_vat);
                        output += '<td class="left">' + info[j].distdemvat.toLocaleString("en-US") + '</td>';
                        totalDistDem += info[j].distdemvat;
                        output += '<td class="left">' + info[j].transdemvat.toLocaleString("en-US") + '</td>';
                        totalTransDem += info[j].transdemvat;
                        output += '<td class="left">' + info[j].loancondvat.toLocaleString("en-US") + '</td>';
                        totalLCondKwh += info[j].loancondvat;
                        output += '<td class="left">' + info[j].loancondifixvat.toLocaleString("en-US") + '</td>';
                        totalLCondFix += info[j].loancondifixvat;
                        output += '<td class="left">' + info[j].mtrfixvat.toLocaleString("en-US") + '</td>';
                        totalMtrfixvat += info[j].mtrfixvat;  
                        output += '<td class="left">' + info[j].mtrsysvat.toLocaleString("en-US") + '</td>';
                        totalMtrsysvat += info[j].mtrsysvat;
                        output += '<td class="left">' + info[j].parvat.toLocaleString("en-US") + '</td>';
                        totalParvat += info[j].parvat;
                        output += '<td class="left">' + info[j].supplyfixvat.toLocaleString("en-US") + '</td>';
                        totalSupplyfixvat += info[j].supplyfixvat;
                        output += '<td class="left">' + info[j].supsysvat.toLocaleString("en-US") + '</td>';
                        totalSupsysvat += info[j].supsysvat;
                        output += '</tr>';
                    }                   
                }

                output += "<tr> <td class='top left'> <b> Total </b> </td>";
                output += "<td class='top left'>" + totalGenVat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + totalTransSYs.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + totalSysLoss.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + totalDistSys.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + totalOthers.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + totalDistDem.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + totalTransDem.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + totalLCondKwh.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + totalLCondFix.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + totalMtrfixvat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + totalMtrsysvat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + totalParvat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + totalSupplyfixvat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + totalSupsysvat.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr>";
                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                // alert('No Sales Found');
                // window.close();
            }
        }
    }
</script>