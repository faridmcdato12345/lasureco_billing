<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Cashiers DCR Collection  </title>

</head>
<style media="print">
    @page {
        size: auto;
        margin-top: 0mm;
    }
    table {
        font-size: 13px !important;
        margin: auto;
    }
</style>
<style>
    #charges {
        height: 50px; 
        width: 24%;
    }
    .page-break {
        page-break-after: always;
    }
    body {
        font-family: Consolas;
    }
    table {
        font-size: 15px;
        margin: auto;
        width: 97%;
        border-right: 0.75px dashed;
        border-bottom: 0.75px dashed;
    }
    td {
        text-align: center;
    }
    .left {
        border-left: 0.75px dashed;
    }
    .right {
        border-right: 0.75px dashed;
    }
    .bot {
        border-bottom: 0.75px dashed;
    }
    .top {
        border-top: 0.75px dashed;
    }
    #breakline {
        display: none;
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

    if(localStorage !== "") {
        var param = JSON.parse(localStorage.getItem("data"));
    }

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.bill_date = param.bill_date; 

        var toSendJSONed = JSON.stringify(toSend);

        var token = document.querySelector('meta[name="csrf-token"]').content;
        var cashiersDcr = "{{route('accounting.dcr.cashier')}}";
        xhr.open('POST', cashiersDcr, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var info = data.info;
                var date = param.bill_date; 

                var year = date.slice(0,4);
                var month = date.slice(5,7);
                var day = date.slice(8,10)

                var output = "";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> SUMMARY OF DAILY COLLECTION  </label><br>';
                output += '<label style="font-size: 18px;">' + months[month - 1] + ' ' + day + ', ' + year + '</label></center>';
                output += '<label style="font-size:16px; float: right; margin-right: 1.5%;"> Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += '<table class="bot"><tr>';
                output += '<th class="left top bot"> Teller </th>';
                output += '<th class="left top bot"> Total Amount </th>';
                output += "<th class='left top bot'> Current </th>";
                output += '<th class="left top bot"> Arrears </th>';
                output += '<th class="left top bot"> Surcharge </th>';
                output += '<th class="left top bot"> Others </th>';
                output += '<tr>';

                for(var x in info){
                    output += "<tr> <td class='left'>" + info[x].teller_name + "</td>";
                    output += "<td class='left'>" + info[x].total_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + info[x].current.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + info[x].arrears.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + info[x].surcharge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + info[x].others.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td></tr>";
                }
                
                output += "<tr> <td class='left top'> <b> Grand Total </b> </td>";
                output += "<td class='left top'>" + data.grand_total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + data.total_current.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + data.total_arrears.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + data.total_surcharge.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "<td class='left top'>" + data.total_others.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                output += "</tr></table>";

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Collection Found');
                window.close();
            }
        }
    }
</script>