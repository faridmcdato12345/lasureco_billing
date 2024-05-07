<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print kWh Report </title>

</head>
<style media="print">
    @page {
      margin: 0mm;
      size: 8.5in 13in !important;
      size: portrait;
    }
    table {
        font-size: 12.5px !important;
    }
    th {
        font-weight: 400 !important;
    }
	div.divFooter {
        position: fixed;
        bottom: 0;   
    }
    .page-break {
        display: block !important;
        margin-top: 10% !important;
    }
    .lastPage {
        display: block !important;
    }
</style>
<style>
    .page-break {
        page-break-after: always;
        margin-left: 2%;
        display: none;
    }
    body {
        font-family: Consolas;
    }
    #table {
        float: left;
        margin-left: 2% !important;
    }
    table {
        width: 95%;
        font-size: 15px;
        border-right: 0.75px dashed;
    }
    .left{
        border-left: 0.75px dashed;
        text-align: center;
        font-weight: 400;
    }
    .right{
        border-right: 0.75px dashed;
        text-align: center;
        font-weight: 400;
    }
    .bot{
        border-bottom: 0.75px dashed;
        text-align: center;
        font-weight: 400;
    }
    .top{
        border-top: 0.75px dashed;
        text-align: center;
        font-weight: 400;
    }
    .lastPage {
        float: left; 
        margin-left: 2%;
        display: none;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
</body>
</html>

{{-- @section('scripts') --}}
<script>
    var item = JSON.parse(localStorage.getItem("data"));
    var xhr = new XMLHttpRequest();

    console.log(item);
    const month = {}
    function getData(){
        const month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        var mon = month[parseInt(item.billperiod.slice(5,7)) - 1];
        var date = mon + ' ' + item.billperiod.slice(0,4);

        var token = document.querySelector('meta[name="csrf-token"]').content;
        var route = "{{route('kwh.report')}}";
        xhr.open('POST', route, true);
        xhr.setRequestHeader("Accept", "application/json");
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send(JSON.stringify(item));
        var today = new Date();
        var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();
        xhr.onload = function(){
            if(xhr.status == 200){ 
                var data = JSON.parse(this.responseText);
                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> KWH REPORT </label><br>';
                output += '<label style="font-size:20px;">' + date + '</label> </center> <br>';
                output += '<label style="font-size: 20px;"> &nbsp;&nbsp;&nbsp;' + item.location[0].toUpperCase() + item.location.substr(1) + ': ' + item.input + '</label><br>';
                output += '<label style="font-size:20px;">&nbsp;&nbsp;&nbsp;Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th class="left top bot"> <b> Account No. </th>';
                output += '<th class="left top bot"> <b> Name</th>';
                output += '<th class="left top bot"> <b> Meter </th>';
                output += '<th class="left top bot"> <b> kWh used </th>';
                output += '<th class="left top bot"> <b> Amount </th>';
                output += '</tr>';
            
                var items = data.Message;
                var count = 0;

                for(var x in items){
                    if(x>0 && x%40==0){
                        count += 1;
                        output += '</table>';
                        output +='<div class="page-break"> Page ' + count + '</div>';
                        output += '<br><center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> KWH REPORT </label><br>';
                        output += '<label style="font-size: 20px;">' + date + '</label> </center> <br>';
                        output += '<label style="font-size: 20px;"> &nbsp;&nbsp;&nbsp;' + item.location[0].toUpperCase() + item.location.substr(1) + ': ' + item.input + '</label><br>';
                        output += '<label style="font-size:20px;">&nbsp;&nbsp;&nbsp;Runtime: ' + date + " - " + time + '</label> <br><br>';
                        output += '<table id="table"><tr>';
                        output += '<th class="left top bot"> <b> Account No. </th>';
                        output += '<th class="left top bot"> <b> Name </th>';
                        output += '<th class="left top bot"> <b> Meter </th>';
                        output += '<th class="left top bot"> <b> kWh used </th>';
                        output += '<th class="left top bot"> <b> Amount </th>';
                        output += '</tr> <tr>';
                        output += '<th class="left bot">' + Object.values(items)[x].Account_No + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].NAME + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].Meter + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].kWh_Used + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].Amount + '</th>';
                    } else {
                        output += '<tr><th class="left bot">' + Object.values(items)[x].Account_No + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].NAME + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].Meter + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].kWh_Used + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].Amount + '</th></tr>';
                    }             
                }
                output += "</table>";
                output += "<table id='table' style='width:25%;border-right: 0px;font-size:15px;'>";
                output += "<tr>" +
                    "<td>Total # Cons.</td>" +
                    "<td>" +data.total_consumer.toLocaleString()+ "</td>"+
                "</tr>";
                output += "<tr style='padding-right:7px;'>" +
                    "<td>Total kwh Used</td>" +
                    "<td>" +data.total_kwh_used.toLocaleString()+ "</td>"+
                "</tr>";
                output += "<tr>" +
                    "<td>Total Amount</td>" +
                    "<td>" +data.total_amount.toLocaleString("en-US", { minimumFractionDigits: 2 })+ "</td>"+
                "</tr>";
                output += "</table>";
                output += "<div class='lastPage'> Page " + (count+1)  + "</div>";
                document.querySelector("#printBody").innerHTML = output;
            } else {
                alert("No Report found!");
            }
        }
    }
</script>
{{-- @endsection --}}