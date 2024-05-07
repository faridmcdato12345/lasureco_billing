<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Vat Sales per Town </title>

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
        border-right: 0.75px dashed;
        border-bottom: 0.75px dashed;
        font-size: 17px;
        width: 97%; 
    }
    .top {
        border-top: 0.75px dashed;
        text-align: center;
    }
    .bot {
        border-bottom: 0.75px dashed;
        text-align: center;
    }
    .left {
        border-left: 0.75px dashed;
        text-align: center;
    }
    .right {
        border-right: 0.75px dashed;
        text-align: center;
    }
    th {
        height: 60px;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
</div>
</body>
</html>

<script>
    var toSend = new Object();

    var param = JSON.parse(localStorage.getItem("data"));

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.area_id = param.area_id;
        toSend.selected = param.selected;
        toSend.date_period = param.billPeriod;
        toSend.date_from = param.date_from;
        toSend.date_to = param.date_to;
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var vatSalesPerTown = "{{route('report.sales.vat.per.town')}}";
        xhr.open('POST', vatSalesPerTown, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var summaryEVAT = data.Summary_EVAT;
                var total = data.Total;
                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> VAT SALES COLLECTION PER TOWN </label><br>';
                output += '<table id="table"><tr>';
                output += '<th class="top left bot"> Town </th>';
                output += '<th class="top left bot"> Generation </th>';
                output += '<th class="top left bot"> Trans. Sys. </th>';
                output += '<th class="top left bot"> Dist. Sys. </th>';
                output += '<th class="top left bot"> Line Loss </th>';
                output += '<th class="top left bot"> Others </th>';
                output += '<th class="top left bot"> Trans. Dem. </th>';
                output += '<th class="top left bot"> Dist. Dem. </th>';
                output += '<th class="top left bot"> LCondo KWH </th>';
                output += '<th class="top left bot"> LCondo FIX </th>';
                output += '<th class="top left bot"> Power Act VAT </th>';
                output += '<th class="top left bot"> Sup. Fix VAT </th>';
                output += '<th class="top left bot"> Sup. Sys. VAT </th>';
                output += '<th class="top left bot"> Meter Fix VAT </th>';
                output += '<th class="top left bot"> Meter Sys VAT </th>';
                output += '<th class="top left bot"> Lifeline VAT </th>';
                output += '<th class="top left bot"> Total </th>';
                output += '</tr>';
                
                for(var i in summaryEVAT){
                    if(i > 0 && i % 20 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> VAT SALES COLLECTION PER TOWN  </label><br>';
                        output += '<table id="table"><tr>';
                        output += '<th class="top left bot"> Town </th>';
                        output += '<th class="top left bot"> Generation </th>';
                        output += '<th class="top left bot"> Trans. Sys. </th>';
                        output += '<th class="top left bot"> Dist. Sys. </th>';
                        output += '<th class="top left bot"> Line Loss </th>';
                        output += '<th class="top left bot"> Others </th>';
                        output += '<th class="top left bot"> Trans. Dem. </th>';
                        output += '<th class="top left bot"> Dist. Dem. </th>';
                        output += '<th class="top left bot"> LCondo KWH </th>';
                        output += '<th class="top left bot"> LCondo FIX </th>';
                        output += '<th class="top left bot"> Power Act VAT </th>';
                        output += '<th class="top left bot"> Sup. Fix VAT </th>';
                        output += '<th class="top left bot"> Sup. Sys. VAT </th>';
                        output += '<th class="top left bot"> Meter Fix VAT </th>';
                        output += '<th class="top left bot"> Meter Sys VAT </th>';
                        output += '<th class="top left bot"> Lifeline VAT </th>';
                        output += '<th class="top left bot"> Total </th>';
                        output += '</tr> <tr>';
                        output += '<td class="left">' + summaryEVAT[i].Town_Name +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Generation_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Trans_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Dist_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Line_Loss_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Others_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Trans_Dem_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Dist_Dem_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].LCondo_Kwh_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].LCondo_Fix_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Power_Act_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Supply_Fix_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Supply_Sys_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Meter_Fix_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Meter_Sys_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].LifeLine_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td class="left">' + summaryEVAT[i].Town_Name +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Generation_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Trans_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Dist_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Line_Loss_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Others_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Trans_Dem_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Dist_Dem_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].LCondo_Kwh_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].LCondo_Fix_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Power_Act_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Supply_Fix_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Supply_Sys_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Meter_Fix_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Meter_Sys_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].LifeLine_Vat.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '<td class="left">' + summaryEVAT[i].Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</td>';
                        output += '</tr> <tr>';
                    }
                }

                output += "<td class='top left'> <b> Sub-Total </b> </td>";
                output += "<td class='top left'>" + total[0].Generation_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + total[0].Trans_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + total[0].Dist_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + total[0].Line_Loss_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + total[0].Others_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + total[0].Trans_Dem_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + total[0].Dist_Dem_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + total[0].LCondo_Kwh_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + total[0].LCondo_Fix_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + total[0].Power_Act_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + total[0].Supply_Fix_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + total[0].Supply_Sys_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + total[0].Meter_Fix_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + total[0].Meter_Sys_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + total[0].LifeLine_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='top left'>" + total[0].Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Consumer Found');
                //window.close();
            }
        }
    }
</script>