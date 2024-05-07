<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print General Detail Report </title>

</head>
<style media="print">
    @page {
      size: landscape;
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
        float: left;
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
    
    toSend.date_from = param.dateFrom;
    toSend.date_to = param.dateTo;
    toSend.town_id = param.townID;

    function getData(){
        var xhr = new XMLHttpRequest();
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var printChangedConsType = "{{route('change.constype')}}";
        xhr.open('POST', printChangedConsType, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                // getDate();
                var data = JSON.parse(this.responseText);
                var details = data.Details;

                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> SUMMARY OF CHANGED CONSUMER TYPE </label><br>';
                output += '<label style="font-size: 18px;">' + param.dateFrom + ' to ' + param.dateTo +'</label> </center><br>';
                output += '<table id="table" class="bot"><tr>';
                output += '<th class="left top bot"> Name </th>';
                output += '<th class="left top bot"> Address </th>';
                output += '<th class="left top bot"> Account No. </th>';
                output += '<th class="left top bot"> Old Info </th>';
                output += '<th class="left top bot"> New Info </th>';
                output += '<th class="left top bot"> Date Acted </th>';
                output += '<th class="left top bot"> Remarks </th>';
                output += '</tr>';

                for(var i in details){
                    if(i > 0 && i%25 == 0){
                        output +='<div class="page-break"></div>';
                        output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> SUMMARY OF CHANGED CONSUMER TYPE </label> <br>';
                        output += '<label style="font-size: 18px;">' + param.dateFrom + ' to ' + param.dateTo +'</label> </center><br>';
                        output += '<table id="table" class="bot"><tr>';
                        output += '<th class="left top bot"> Name </th>';
                        output += '<th class="left top bot"> Address </th>';
                        output += '<th class="left top bot"> Account No. </th>';
                        output += '<th class="left top bot"> Old Info </th>';
                        output += '<th class="left top bot"> New Info </th>';
                        output += '<th class="left top bot"> Date Acted </th>';
                        output += '<th class="left top bot"> Remarks </th>';
                        output += '<tr>';
                        output += '<td class="left">' + details[i].NAME + '</td>';
                        output += '<td class="left">' + details[i].ADDRESS + '</td>';
                        output += '<td class="left">' + details[i].ACCOUNT_NO + '</td>';
                        output += '<td class="left">' + details[i].OLD_INFO + '</td>';
                        output += '<td class="left">' + details[i].NEW_INFO + '</td>';
                        output += '<td class="left">' + details[i].DATE + '</td>';
                        output += '<td class="left">' + details[i].REMARKS + '</td>';
                        output += "</tr>";
                    } else {
                        output += '<tr>';
                        output += '<td class="left">' + details[i].NAME + '</td>';
                        output += '<td class="left">' + details[i].ADDRESS + '</td>';
                        output += '<td class="left">' + details[i].ACCOUNT_NO + '</td>';
                        output += '<td class="left">' + details[i].OLD_INFO + '</td>';
                        output += '<td class="left">' + details[i].NEW_INFO + '</td>';
                        output += '<td class="left">' + details[i].DATE + '</td>';
                        output += '<td class="left">' + details[i].REMARKS + '</td>';
                        output += '</tr>';
                    }
                }
                output += "</table>";

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Report Found');
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