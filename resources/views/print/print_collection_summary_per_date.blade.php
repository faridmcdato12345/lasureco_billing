<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <title> Print Sales vs Collection Report </title>
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
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
</body>
</html>

<script>
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();

    var storage = JSON.parse(localStorage.getItem("data"));
    var dates = JSON.parse(localStorage.getItem("dates"));
    var message = storage.message;

    function getData(){
        var output = "";

    for(var i in message){    
        output += '<center> <br> <label id="lasuhead"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br>';
        output += '<label style="font-size: 20px;"> PRINT COLLECTION SUMMARY PER DATE </label> <br>';
        output += '<label style="font-size: 18px;"> ' + getDate(dates.date_from) + ' - ' + getDate(dates.date_to) + '</label> <br><br>';
        output += '<table id="table"><tr>';
        output += '<th class="left top"> AR Date </th>';
        output += '<th class="left top"> AR Number </th>';
        output += '<th class="left top"> Teller </th>';
        output += '<th class="left top"> No. of PB </th>';
        output += '<th class="left top"> Amount </th>';
        output += '<th class="left top"> Non-PB </th>';
        output += '<th class="left top"> Amount </th>';
        output += '<th class="left top"> Total Collection </th>';
        output += '</tr>';
        
        var count = 1;

        for(var x in message[i]){
            if(message[i][x].date == i){
                count += 1; 
            }

            if(count > 2){
                output += '<tr><td class="left"> </td>';
                output += '<td class="left">' + message[i][x].ack_receipt; + '</td></tr>';
                output += '<td class="left">' + message[i][x].teller_name; + '</td></tr>';
                output += '<td class="left">' + message[i][x].num_bill_pb; + '</td></tr>';
                output += '<td class="left">' + message[i][x].amount_pb; + '</td></tr>';
                output += '<td class="left">' + message[i][x].num_bill_nb; + '</td></tr>';
                output += '<td class="left">' + message[i][x].amount_nb; + '</td></tr>';
                output += '<td class="left">' + message[i][x].total_collection; + '</td></tr>';
            } else {
                output += '<tr><td class="left top">' + message[i][x].date; + '</td>';
                output += '<td class="left top">' + message[i][x].ack_receipt; + '</td></tr>';
                output += '<td class="left top">' + message[i][x].teller_name; + '</td></tr>';
                output += '<td class="left top">' + message[i][x].num_bill_pb; + '</td></tr>';
                output += '<td class="left top">' + message[i][x].amount_pb; + '</td></tr>';
                output += '<td class="left top">' + message[i][x].num_bill_nb; + '</td></tr>';
                output += '<td class="left top">' + message[i][x].amount_nb; + '</td></tr>';
                output += '<td class="left top">' + message[i][x].total_collection; + '</td></tr>';
            }
        }
        output += '</table>';
    }
        
        document.querySelector('#printBody').innerHTML = output;
    }

    function getDate(x) {
        var date = x;
        
        var dateToSpell = "";
        var month = date.slice(5, 7);
        var year = date.slice(0, 4);
        var day = date.slice(8,10);

        if(month == "01") {
            dateToSpell = "January " + day + ", " + year;
        } else if(month == "02") {
            dateToSpell = "February " + day + ", " + year;
        } else if(month == "03") {
            dateToSpell = "March " + day + ", " + year;
        } else if(month == "04") {
            dateToSpell = "April " + day + ", " + year;
        } else if(month == "05") {
            dateToSpell = "May " + day + ", " + year;
        } else if(month == "06") {
            dateToSpell = "June " + day + ", " + year;
        } else if(month == "07") {
            dateToSpell = "July " + day + ", " + year;
        } else if(month == "08") {
            dateToSpell = "August " + day + ", " + year;
        } else if(month == "09") {
            dateToSpell = "September " + day + ", " + year;
        } else if(month == "10") {
            dateToSpell = "October " + day + ", " + year;
        } else if(month == "11") {
            dateToSpell = "November " + day + ", " + year;
        } else if(month == "12") {
            dateToSpell = "December " + day + ", " + year;
        }
        billdate = dateToSpell;

        return billdate;
    }
</script>