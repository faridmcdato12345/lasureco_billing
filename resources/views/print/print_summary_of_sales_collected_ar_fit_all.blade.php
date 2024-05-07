s<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <title> Print Vat Sales per Consumer type  </title>

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
    var dates = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();

    var toSend = new Object();

    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    var param = JSON.parse(localStorage.getItem("data"));

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date_period = param.date_period; 
        toSend.selected = param.selected; 
        toSend.area_id = param.area_id; 

        var toSendJSONed = JSON.stringify(toSend);

        var token = document.querySelector('meta[name="csrf-token"]').content;
        var fitAll = "{{route('report.collection.sales.fitall')}}";
        xhr.open('POST', fitAll, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var info = data.Summary_Fit_All;
                var total = data.Total;
                var output = "";
                var date = param.date_period;
                var year = date.slice(0,4);
                var month = date.slice(5,7);
                
                output += "<br>";
                output += '<center> <label id="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label id="emailText"> teamlasureco@gmail.com </label><br><br><br>';
                
                if(param.selected == "sales") {
                    output += '<label style="font-size: 20px;">  SUMMARY OF FIT ALL - BILLED  </label> <br>';
                } else {
                    output += '<label style="font-size: 20px;">  SUMMARY OF FIT ALL - COLLECTED  </label> <br>';
                }

                output += '<label style="font-size: 18px;">' + months[month - 1] + ', ' + year + '</label> <br> </center>';
                output += '<label style="font-size:16px; float: right; margin-right: 1.5%;"> Runtime: ' + dates + " - " + time + '</label> <br><br>';
                output += '<label style="font-size:18px; margin-left: 2%;"> Area: ' + data.Area_Name + '</label> <br>';
                output += '<table id="table">';
                output += '<tr><th class="left top bot"> Town </th>';
                output += '<th class="left top bot"> KWH Used </th>';
                output += '<th class="left top bot"> Amount </th>';
                output += '</tr>';
                
                for(var i in info){
                    console.log(info);
                    output += "<tr><td class='left bot' style='text-align: left !important; width: 33.33%;'>";
                    output += "&nbsp;&nbsp; " + info[i].Town_code + " - " + info[i].Town + "</td>";
                    output += "<td class='left bot'>" + info[i].KWH_USED.toLocaleString("en-US") + "</td>";
                    output += "<td class='left bot'>" + info[i].Amount.toLocaleString("en-US") + "</td>";
                    output += "</tr>";
                }
                output += "<tr><td class='left bot'> <b> Total </b> </td>";
                output += "<td class='left bot'>" + total[0].KWH_USED.toLocaleString("en-US") + "</td>"; 
                output += "<td class='left bot'>" + total[0].Amount.toLocaleString("en-US") + "</td>";
                output += "</tr></table>";

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
</script>