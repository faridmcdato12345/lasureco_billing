<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <title> Print Total Collection Report(Power Bill) </title>
</head>
<style media="print">
    @page {
        size: A4;
        margin: 0mm;
    }
    table {
        font-size: 12.5px !important;
        margin: auto;
    }
    th {
        font-weight: 400 !important;
    }
    .delete {
        display: none;
    }
    .action {
        display: none;
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
    .delete {
        background-color: rgb(221, 51, 51);
        color: white;
        border: 0px;
        height: 25px;
        cursor: pointer;
        border-radius: 2px;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
</body>
</html>

<script>
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();

    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));

    function getData(){
        var xhr = new XMLHttpRequest();
        
        if(param.selected == "all"){
            toSend.selected = param.selected;
            toSend.date = param.date;
        } else {
            toSend.selected = param.selected;
            toSend.date = param.date;
            toSend.location = param.location; 
        }

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;

        var totalCollection = "{{route('collection.report.powerbill')}}";
        xhr.open('POST', totalCollection, true);

        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var details = data.Details;

                var output = "";
                var num = 0;
                var totalCurrent = 0;
                var totalArrears = 0;
                var totalCollections = 0;
                var totalNonBill = 0;

                output += '<center> <br> <label id="lasuhead"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                output += '<label style="font-size: 20px;"> TOTAL COLLECTION REPORT (POWER BILL) </label> <br>';
                output += '<label style="font-size: 18px;">' + billdate + '</label> </center>';
                output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += '<table id="table"><tr>';
                
                if(param.selected == "all") {
                    output += '<th class="left top bot"> Area </th>';
                } else if(param.selected == "area") {
                    output += '<th class="left top bot"> Town </th>';
                } else {
                    output += '<th class="left top bot"> Route </th>';
                }
                output += '<th class="left top bot"> Total Current </th>';
                output += '<th class="left top bot"> Total Arrears </th>';
                output += '<th class="left top bot"> Total Collections </th>';
                output += '</tr>';
            
                for(var i in details){
                    num += 1;
                    if(num > 0 && num % 50 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <br> <label id="lasuhead"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
                        output += '<label style="font-size: 20px;"> TOTAL COLLECTION REPORT (POWER BILL)</label> <br>';
                        output += '<label style="font-size: 18px;">' + billdate + '</label> </center>';
                        output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                        output += '<table id="table"><tr>';
                        if(param.selected == "all") {
                            output += '<th class="left top bot"> Area </th>';
                        } else if(param.selected == "area") {
                            output += '<th class="left top bot"> Town </th>';
                        } else {
                            output += '<th class="left top bot"> Route </th>';
                        }
                        output += '<th class="left top bot"> Total Current </th>';
                        output += '<th class="left top bot"> Total Arrears </th>';
                        output += '<th class="left top bot"> Total Collections </th>';
                        output += '</tr><tr>';
                        if(param.selected == "all") {
                            output += '<td class="left" style="text-align: left !important;"> &nbsp;&nbsp;&nbsp;' + details[i].Area_Name + '</td>';
                        } else if(param.selected == "area") {
                            output += '<td class="left" style="text-align: left !important;"> &nbsp;&nbsp;&nbsp;' + details[i].Town_Code + ' - ' + details[i].Town_Name + '</td>';
                        } else {
                            output += '<td class="left" style="text-align: left !important;"> &nbsp;&nbsp;&nbsp;' + details[i].Route_Code + ' - ' + details[i].Route_Name + '</td>';
                        }
                        output += '<td class="left"> &nbsp;' + details[i].Total_Current.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left"> &nbsp;' + details[i].Total_Arrears.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left"> &nbsp;' + details[i].Total_Collection.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '</tr>';

                        totalCurrent += details[i].Total_Current;
                        totalArrears += details[i].Total_Arrears;
                        totalCollections += details[i].Total_Collection;
                    }
                    else{
                        output += '<tr>';
                        if(param.selected == "all") {
                            output += '<td class="left" style="text-align: left !important;"> &nbsp;&nbsp;&nbsp;' + details[i].Area_Name + '</td>';
                        } else if(param.selected == "area") {
                            output += '<td class="left" style="text-align: left !important;"> &nbsp;&nbsp;&nbsp;' + details[i].Town_Code + ' - ' + details[i].Town_Name + '</td>';
                        } else {
                            output += '<td class="left" style="text-align: left !important;"> &nbsp;&nbsp;&nbsp;' + details[i].Route_Code + ' - ' + details[i].Route_Name + '</td>';
                        }   
                        output += '<td class="left"> &nbsp;' + details[i].Total_Current.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left"> &nbsp;' + details[i].Total_Arrears.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td class="left"> &nbsp;' + details[i].Total_Collection.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '</tr>';

                        totalCurrent += details[i].Total_Current;
                        totalArrears += details[i].Total_Arrears;
                        totalCollections += details[i].Total_Collection;
                        totalNonBill += details[i].Total_Non_Bill;
                    }
                }
                output += "<tr> <td class='left top'> <b> Total </b> </td>";
                output += "<td class='left top'>" + totalCurrent.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalArrears.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + totalCollections.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr>";
                output += "</table>";

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                Swal.fire({
                    title: 'Notice!',
                    icon: 'error',
                    text: 'No Report found!'
                }).then(function(){ 
                    window.close();
                });
            }
        }
    }

    function getDate() {
        var date = JSON.stringify(param.date);
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
</script>