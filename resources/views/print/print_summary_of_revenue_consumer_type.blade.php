<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Summary of Revenue Consumer Type </title>

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
        font-family: Consolas;
        border-right: 0.75px dashed;
        border-bottom: 0.75px dashed;
    }
    th {
        height: 60px;
    }
    td {
        text-align: center;
    }
    .top {
        border-top: 0.75px dashed;
        text-align: center !important;
    } 
    .bot {
        border-bottom: 0.75px dashed;
        text-align: center !important; 
    }
    .left {
        border-left: 0.75px dashed;
        text-align: center !important;
    }
    .right {
        border-right: 0.75px dashed;
        text-align: center !important; 
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

        var area_id = param.areaId;
        var billFrom = param.billFrom;
        var billTo = param.billTo;
    }

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.area_id = area_id;
        toSend.Date_From = billFrom;
        toSend.Date_To = billTo;
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var sumRevPerConsType = "{{route('report.revenue.per.constype')}}";
        xhr.open('POST', sumRevPerConsType, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var consType = data.Cons_type;

                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> SALES PER CONSUMER TYPE </label><br>';
                output += '<label style="font-size: 18px;"> From <b>' + billFrom + '</b> To <b>' + billTo + '</b> </label> </center> <br>';
                output += '<table id="table"><tr>';
                output += '<th class="left top bot"> Consumer Type </th>';
                output += '<th class="left top bot"> Count </th>';
                output += '<th class="left top bot"> KWH Used </th>';
                output += '<th class="left top bot"> Bill Amount </th>';
                output += '<th class="left top bot"> Gen Sys </th>';
                output += '<th class="left top bot"> Trans Sys </th>';
                output += '<th class="left top bot"> Dist Sys </th>';
                output += '<th class="left top bot"> Sup Sys </th>';
                output += '<th class="left top bot"> Met Sys </th>';
                output += '<th class="left top bot"> Line Loss </th>';
                output += '<th class="left top bot"> CAPEX </th>';
                output += '<th class="left top bot"> LL Subs </th>';
                output += '<th class="left top bot"> LL Disc </th>';
                output += '<th class="left top bot"> UC-ME SPUG </th>';
                output += '<th class="left top bot"> UC-ME RED </th>';
                output += '<th class="left top bot"> UC-ENVI </th>';
                output += '<th class="left top bot"> UC-SCC </th>';
                output += '<th class="left top bot"> UC-SD </th>';
                output += '<th class="left top bot"> FIT ALL </th>';
                output += '<th class="left top bot"> SC-DISC </th>';
                output += '<th class="left top bot"> SC-SUBS </th>';
                output += '</tr>';
                
                for(var i in consType){
                    if(i > 0 && i % 20 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> SUMMARY OF REVENUE PER TOWN </label><br>';
                        output += '<label style="font-size:20px;"> From  To </label> </center> <br>';
                        output += '<table id="table"><tr>';
                        output += '<th class="left top bot"> Consumer Type </th>';
                        output += '<th class="left top bot"> Count </th>';
                        output += '<th class="left top bot"> KWH Used </th>';
                        output += '<th class="left top bot"> Bill Amount </th>';
                        output += '<th class="left top bot"> Gen Sys </th>';
                        output += '<th class="left top bot"> Trans Sys </th>';
                        output += '<th class="left top bot"> Dist Sys </th>';
                        output += '<th class="left top bot"> Sup Sys </th>';
                        output += '<th class="left top bot"> Met Sys </th>';
                        output += '<th class="left top bot"> Line Loss </th>';
                        output += '<th class="left top bot"> CAPEX </th>';
                        output += '<th class="left top bot"> LL Subs </th>';
                        output += '<th class="left top bot"> LL Disc </th>';
                        output += '<th class="left top bot"> UC-ME SPUG </th>';
                        output += '<th class="left top bot"> UC-ME RED </th>';
                        output += '<th class="left top bot"> UC-ENVI </th>';
                        output += '<th class="left top bot"> UC-SCC </th>';
                        output += '<th class="left top bot"> UC-SD </th>';
                        output += '<th class="left top bot"> FIT ALL </th>';
                        output += '<th class="left top bot"> SC-DISC </th>';
                        output += '<th class="left top bot"> SC-SUBS </th>';
                        output += '</tr> <tr>';
                        output += '<td class="left">' + consType[i].Cons_Type +'</td>';
                        output += '<td class="left">' + consType[i].Consumer_Count_Total.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Kwh_Used.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Bill_Amount.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Generation.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Trans.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Dist.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Supply_Fix.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Metering_Fix.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].LineLoss.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Capex.toLocaleString("en-US") +'</td>';
                        output += '<td class="left"> </td>';
                        output += '<td class="left"> </td>';
                        output += '<td class="left">' + consType[i].UC_ME_SPUG.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].UC_ME_RED.toLocaleString("en-US") +'</td>';
                        output += '<td class="left"> </td>';
                        output += '<td class="left"> </td>';
                        output += '<td class="left"> </td>';
                        output += '<td class="left">' + consType[i].PPA_Refund.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].SR_Discount.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].SR_Subsidy.toLocaleString("en-US") +'</td>';
                        output += '<td> </td>';
                        output += '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td class="left">' + consType[i].Cons_Type +'</td>';
                        output += '<td class="left">' + consType[i].Consumer_Count_Total.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Kwh_Used.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Bill_Amount.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Generation.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Trans.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Dist.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Supply_Fix.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Metering_Fix.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].LineLoss.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].Capex.toLocaleString("en-US") +'</td>';
                        output += '<td class="left"> </td>';
                        output += '<td class="left"> </td>';
                        output += '<td class="left">' + consType[i].UC_ME_SPUG.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].UC_ME_RED.toLocaleString("en-US") +'</td>';
                        output += '<td class="left"> </td>';
                        output += '<td class="left"> </td>';
                        output += '<td class="left"> </td>';
                        output += '<td class="left">' + consType[i].PPA_Refund.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].SR_Discount.toLocaleString("en-US") +'</td>';
                        output += '<td class="left">' + consType[i].SR_Subsidy.toLocaleString("en-US") +'</td>';
                        output += '<td> </td>';
                        output += '</tr>';
                    }
                }
                 
                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Reports Found');
                window.close();
            }
        }
    }

    // function getDate() {
    //     var date = JSON.stringify(billPeriod);
    //     var dateToSpell = "";
    //     var month = date.slice(6, 8);
    //     var year = date.slice(1, 5);

    //     if(month == "01") {
    //         dateToSpell = "January, " + year;
    //     } else if(month == "02") {
    //         dateToSpell = "February, " + year;
    //     } else if(month == "03") {
    //         dateToSpell = "March, " + year;
    //     } else if(month == "04") {
    //         dateToSpell = "April, " + year;
    //     } else if(month == "05") {
    //         dateToSpell = "May, " + year;
    //     } else if(month == "06") {
    //         dateToSpell = "June, " + year;
    //     } else if(month == "07") {
    //         dateToSpell = "July, " + year;
    //     } else if(month == "08") {
    //         dateToSpell = "August, " + year;
    //     } else if(month == "09") {
    //         dateToSpell = "September, " + year;
    //     } else if(month == "10") {
    //         dateToSpell = "October, " + year;
    //     } else if(month == "11") {
    //         dateToSpell = "November, " + year;
    //     } else if(month == "12") {
    //         dateToSpell = "December, " + year;
    //     }

    //     billdate = dateToSpell;
    // }
</script>