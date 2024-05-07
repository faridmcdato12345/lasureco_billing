<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Billing Edit List </title>

</head>
<style media="print">
    @page {
      margin: 2mm;
      size: landscape;
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
        font-size: 17px;
        width: 100%;
    }
    th {
        /* height: 60px; */
        font-size: 12px;
        text-align: left;
    }
    td {
        /* text-align: center; */
        /* height: 50px; */
        font-size: 12px;
        margin: 0px;
        padding: 0px;
    }
    .top {
        border-top: 0.75px dashed;
    }
    .bottom {
        border-bottom: 0.75px dashed;
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
    console.log(date + ' ' + time);
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    
    var billPeriod = param.billPeriod;
    var routeId = param.routeId; 

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.bill_period = billPeriod;
        toSend.route_id = routeId;
        console.log("EDIT LIST 2",JSON.stringify(toSend));
        var toSendJSONed = JSON.stringify(toSend);
        
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var printBillList = "{{route('print.edit.bill.list')}}";
        xhr.open('POST', printBillList, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var Bill_List = data.Bill_List;

                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@ymail.com </label><br><br>';
                output += '<label style="font-size:16px; margin-top: -6%; float: left;"> DATE PRINTED: ' + date + '</label>  <br>';
                output += '<label style="font-size:16px; margin-top: -6%; float: left;"> TIME PRINTED: ' + time + '</label>  ';
                output += '<label style="font-size: 20px;"> BILLING EDIT LIST </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center>';
                
                output += '<label style="font-size:16px;"> AREA: ' + data.Area + '</label>  <br>';
                output += '<label style="font-size:16px;"> TOWN: ' + data.Town + '</label>  <br>';
                output += '<label style="font-size:16px;"> ROUTE:' + data.Route + '</label>  <br>';
                output += '<label style="font-size:16px;"> METER READER:' + data.meter_reader + '</label>  <br><br>';
                output += '<table id="table"><tr>';
                output += '<td colspan=3 class=top> </td>';
                output += '<td colspan=3 style="text-align: center;" class=top> --ENERGY READING-- </td>';
                output += '<td colspan=3 style="text-align: center;" class=top> --DEMAND READING-- </td>';
                output += '<td colspan=10 class=top></td>';
                output += '</tr>';
                output += '<tr>';
                output += '<th class=bottom> Account </th>';
                output += '<th class=bottom> Consumer Name </th>';
                output += '<th class=bottom> TYPE </th>';
                output += '<th class=bottom> Present </th>';
                output += '<th class=bottom> Previous </th>';
                output += '<th class=bottom> KWH </th>';
                output += '<th class=bottom> Present </th>';
                output += '<th class=bottom> Previous </th>';
                output += '<th class=bottom> KW </th>';
                output += '<th class=bottom> Mult </th>';
                output += '<th class=bottom> TSF Rent </th>';
                output += '<th class=bottom> Energy Charge </th>';
                output += '<th class=bottom> M/Arrears </th>';
                output += '<th class=bottom> Surcharge </th>';
                output += '<th class=bottom> Total Due </th>';
                output += '<th class=bottom> Reading Date </th>';
                output += '<th class=bottom> Prev Month </th>';
                output += '<th class=bottom> Difference </th>';
                output += '</tr>';
                
                for(var i in Bill_List){
                    var a = Bill_List[i].Account_No.toString();
                    var a1 = a.slice(0,2);
                    var a2 = a.slice(2,6);
                    var a3 = a.slice(6,10);

                    console.log(Bill_List[i]);
                     if(i > 0 && i%40 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <br><br><br><br><br> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> BILLING EDIT LIST </label><br>';
                        output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br>';
                        output += '<table id="table"><tr>';
                        output += '<td colspan=3 class=top> </td>';
                        output += '<td colspan=3 style="text-align: center;" class=top> --ENERGY READING-- </td>';
                        output += '<td colspan=3 style="text-align: center;" class=top> --DEMAND READING-- </td>';
                        output += '<td colspan=10 class=top></td>';
                        output += '</tr>';
                        output += '<tr>';
                        output += '<th class=bottom> Account </th>';
                        output += '<th class=bottom> Consumer Name </th>';
                        output += '<th class=bottom> TYPE </th>';
                        output += '<th class=bottom> Present </th>';
                        output += '<th class=bottom> Previous </th>';
                        output += '<th class=bottom> KWH </th>';
                        output += '<th class=bottom> Present </th>';
                        output += '<th class=bottom> Previous </th>';
                        output += '<th class=bottom> KW </th>';
                        output += '<th class=bottom> Mult </th>';
                        output += '<th class=bottom> TSF Rent </th>';
                        output += '<th class=bottom> Energy Charge </th>';
                        output += '<th class=bottom> M/Arrears </th>';
                        output += '<th class=bottom> Surcharge </th>';
                        output += '<th class=bottom> Total Due </th>';
                        output += '<th class=bottom> Reading Date </th>';
                        output += '<th class=bottom> Prev Month </th>';
                        output += '<th class=bottom> Difference </th>';
                        output += '<tr>';
                        output += '<td>' + a1 + '-' + a2 + '-' + a3 + '</td>';
                        output += '<td>' + Bill_List[i].Consumer_Name.slice(0,20) + '</td>';
                        output += '<td>' + Bill_List[i].Type + '</td>';
                        output += '<td>' + Bill_List[i].Kwh_Present_Reading + '</td>';
                        output += '<td>' + Bill_List[i].Kwh_Previous_Reading + '</td>';
                        output += '<td>' + Bill_List[i].KWH + '</td>';
                        output += '<td>' + Bill_List[i].Dem_Present_Reading.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td>' + Bill_List[i].Dem_Previous_Reading.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td>' + Bill_List[i].Dem_KWH.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td>' + Bill_List[i].MULT + '</td>';
                        output += '<td>' + Bill_List[i].TSF_RENT + '</td>';
                        output += '<td>' + Bill_List[i].ENERGY_CHARGES.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td>' + Bill_List[i].M_ARREARS.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td>' + Bill_List[i].SURCHARGE + '</td>';
                        output += '<td>' + Bill_List[i].TOTAL_DUE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td>' + Bill_List[i].READING_DATE + '</td>';
                        output += '<td>' + Bill_List[i].Prev_Month + '</td>';
                        output += '<td>' + Bill_List[i].Difference + '</td>';
                        output += '</tr>';
                        output += '<tr>' 
                        
                    }
                    else{
                        output += '<tr>';
                        output += '<td>' + a1 + '-' + a2 + '-' + a3  + '</td>';
                        output += '<td>' + Bill_List[i].Consumer_Name.slice(0,20)+ '</td>';
                        output += '<td>' + Bill_List[i].Type + '</td>';
                        output += '<td>' + Bill_List[i].Kwh_Present_Reading + '</td>';
                        output += '<td>' + Bill_List[i].Kwh_Previous_Reading + '</td>';
                        output += '<td>' + Bill_List[i].KWH + '</td>';
                        output += '<td>' + Bill_List[i].Dem_Present_Reading.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td>' + Bill_List[i].Dem_Previous_Reading.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td>' + Bill_List[i].Dem_KWH.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td>' + Bill_List[i].MULT + '</td>';
                        output += '<td>' + Bill_List[i].TSF_RENT + '</td>';
                        output += '<td>' + Bill_List[i].ENERGY_CHARGES.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td>' + Bill_List[i].M_ARREARS.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td>' + Bill_List[i].SURCHARGE + '</td>';
                        output += '<td>' + Bill_List[i].TOTAL_DUE.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td>' + Bill_List[i].READING_DATE + '</td>';
                        output += '<td>' + Bill_List[i].Prev_Month + '</td>';
                        output += '<td>' + Bill_List[i].Difference + '</td>';
                        output += '</tr>';
                    }
                }
                output += '<tr style="height: 30px;"> <td colspan=10> </td>';
                output += '<td style="border-top: 0.75px dashed;"> 0 </td>';
                output += '<td style="border-top: 0.75px dashed;">' + data.Grand_Total_Energy.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                output += '<td style="border-top: 0.75px dashed;">' + data.Grand_Total_M_Arrears.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                output += '<td style="border-top: 0.75px dashed;"> 0 </td>';
                output += '<td style="border-top: 0.75px dashed;">' + data.Grand_Due.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                output += '</table> <br>';
                output += '<label style="font-size:16px;"> TOTAL ACC0UNTS: ' + data.Total_Account + '</label> <br><br>';
                output += '<label style="font-size:16px;"> CHECKED BY: </label> <br><br>';
                output += '<label style="font-size:16px;"> DATE: </label> <br><br>';
                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Report Found');
                window.close();
            }
        }
    }

    function getDate() {
        var date = JSON.stringify(billPeriod);
        var dateToSpell = "";
        var month = date.slice(6, 8);
        var year = date.slice(1, 5);

        if(month == "01") {
            dateToSpell = "January, " + year;
        } else if(month == "02") {
            dateToSpell = "February, " + year;
        } else if(month == "03") {
            dateToSpell = "March, " + year;
        } else if(month == "04") {
            dateToSpell = "April, " + year;
        } else if(month == "05") {
            dateToSpell = "May, " + year;
        } else if(month == "06") {
            dateToSpell = "June, " + year;
        } else if(month == "07") {
            dateToSpell = "July, " + year;
        } else if(month == "08") {
            dateToSpell = "August, " + year;
        } else if(month == "09") {
            dateToSpell = "September, " + year;
        } else if(month == "10") {
            dateToSpell = "October, " + year;
        } else if(month == "11") {
            dateToSpell = "November, " + year;
        } else if(month == "12") {
            dateToSpell = "December, " + year;
        }

        billdate = dateToSpell;
    }

    // setTimeout(function() {
    //     localStorage.clear();
    // }, 2000);

</script>