<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <title> Collection Summary </title>
</head>
<style media="print">
    @page {
        size: Legal landscape;
        margin: 0mm;
    }
    table {
        font-size: 12px !important;
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
    #logo {
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
        width: 100%;
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
    #numBar {
        display: block;
    }
    #logo {
		height: 70px;
		width: 70px;
        float: left;
        margin-top: 22px;
        margin-left: 25px;
	}
    #lasuText {
        font-size: 24px; 
        font-weight: bold; 
        margin-left: -90px;
    }
    #dateText {
        font-size: 18px; 
        margin-left: -100px;
    }
    #emailText {
        font-size: 15px; 
        margin-left: -100px;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
    <br>
    <p id="numBar"> </p>
</body>
</html>

<script>
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();

    var toSend = new Object();

    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    var param = JSON.parse(localStorage.getItem("data"));
    var selected = param.selected;
    function getData(){
        var xhr = new XMLHttpRequest();
        var apiName = "";

        if(selected == "perAR") {
            apiName = "{{route('report.collection.per.ar')}}";
        } else {
            apiName = "{{route('report.collection.per.constype')}}";
        }

        toSend.date_from = param.date_from;
        toSend.date_to = param.date_to;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;

        xhr.open('POST', apiName, true);

        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var selected = Object.getOwnPropertyNames(data)[0];
                
                
                var dateFrom = param.date_from;
                var year = dateFrom.slice(0,4);
                var month = dateFrom.slice(5,7);
                var day = dateFrom.slice(8,10)
                var dateTo = param.date_to;
                var yearTo = dateTo.slice(0,4);
                var monthTo = dateTo.slice(5,7);
                var dayTo = dateTo.slice(8,10)

                if(selected == "infoAR"){
                    var output = "";
                    var totalNoPB = 0;
                    var totalAmountPB = 0;
                    var totalNonPB = 0;
                    var totalAmountNB = 0;
                    var totalCollected = 0;

                    output += '<img id="logo" src="/img/logo.png">';
                    output += "<br>";
                    output += '<center> <label id="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                    output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                    output += '<label id="emailText"> teamlasureco@gmail.com </label><br><br><br>';
                    output += "<label style='font-size: 20px;'> COLLECTION SUMMARY PER ACKNOWLEDGEMENT RECEIPT </label> <br>";
                    output += '<label style="font-size: 18px;">' + months[month - 1] + ' ' + day + ', ' + year;
                    output += ' - ' + months[monthTo - 1] + ' ' + dayTo + ', ' + yearTo + '</label> <br> </center>';
                    output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                    output += '<table id="table"><tr>';
                    output += '<th class="left top bot"> AR Date </th>';
                    output += '<th class="left top bot"> Acknowledgement Receipt No. </th>';
                    output += '<th class="left top bot"> Teller/Collector </th>';
                    output += '<th class="left top bot"> No. of PB </th>';
                    output += '<th class="left top bot"> Amount </th>';
                    output += '<th class="left top bot"> Non-PB </th>';
                    output += '<th class="left top bot"> Amount </th>';
                    output += '<th class="left top bot"> Total Collection </th> </tr>';

                    var collection = Object.values(data)[0];

                    for(var x in collection){
                        output += '<tr> <td class="left" style="vertical-align:top;" rowspan=' + Object.keys(collection[x]).length + '>' + x + '</td>';

                        for(var i in collection[x]){
                            output += '<td class="left">' + i + '</td>';
                            output += '<td class="left">' + collection[x][i].teller + '</td>';
                            output += '<td class="left">' + collection[x][i].no_pb.toLocaleString("en-US") + '</td>';
                            totalNoPB += collection[x][i].no_pb;
                            output += '<td class="left">' + collection[x][i].amount_pb.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            totalAmountPB += collection[x][i].amount_pb;
                            output += '<td class="left">' + collection[x][i].no_nb.toLocaleString("en-US") + '</td>';
                            totalNonPB += collection[x][i].no_nb;
                            output += '<td class="left">' + collection[x][i].amount_nb.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            totalAmountNB += collection[x][i].amount_nb;
                            output += '<td class="left">' + collection[x][i].total_collection.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                            totalCollected += collection[x][i].total_collection;
                            output += '</tr>';
                        }
                        output += "<tr><td class='top'></td><td class='top'></td><td class='top'></td><td class='top'></td>";
                        output += "<td class='top'></td><td class='top'></td><td class='top'></td><td class='top'></td></tr>";
                    }
                    output += "<tr> <td class='left' colspan=3 style='text-align: right';> <b> Total &nbsp;&nbsp; </b> </td>";
                    output += "<td class='left'>" + totalNoPB.toLocaleString("en-US") + "</td>";
                    output += "<td class='left'>" + totalAmountPB.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + totalNonPB.toLocaleString("en-US") + "</td>";
                    output += "<td class='left'>" + totalAmountNB.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left'>" + totalCollected.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "</tr> </table>";
                } 
                else {
                    var output = "";
                    var totalNoPB = 0;
                    var totalAmountPB = 0;
                    var totalNonPB = 0;
                    var totalAmountNB = 0;
                    var totalCollected = 0;

                    output += '<img id="logo" src="/img/logo.png">';
                    output += "<br>";
                    output += '<center> <label id="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                    output += '<label id="dateText"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                    output += '<label id="emailText"> teamlasureco@gmail.com </label><br><br><br>';
                    output += "<label style='font-size: 20px;'> COLLECTION SUMMARY PER CONSUMER TYPE </label> <br>";
                    output += '<label style="font-size: 18px;">' + months[month - 1] + ' ' + day + ', ' + year;
                    output += ' - ' + months[monthTo - 1] + ' ' + dayTo + ', ' + yearTo + '</label> <br> </center>';
                    output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                    output += '<table id="table"><tr>';
                    output += '<th class="left top bot"> Consumer Type </th>';
                    output += '<th class="left top bot"> No of PB </th>';
                    output += '<th class="left top bot"> Amount </th>';
                    output += '<th class="left top bot"> Non-PB </th>';
                    output += '<th class="left top bot"> Amount </th>';
                    output += '<th class="left top bot"> Total Collected </th> </tr>';

                    var collection = Object.values(data)[0];

                    for(var x in collection){
                        output += "<td class='left'>" + collection[x].consumer_type + "</td>";
                        output += '<td class="left">' + collection[x].no_pb.toLocaleString("en-US") + '</td>';
                        totalNoPB += collection[x].no_pb;
                        output += '<td class="left">' + collection[x].amount_pb.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        totalAmountPB += collection[x].amount_pb;
                        output += '<td class="left">' + collection[x].no_nb.toLocaleString("en-US") + '</td>';
                        totalNonPB += collection[x].no_nb;
                        output += '<td class="left">' + collection[x].amount_nb.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        totalAmountNB += collection[x].amount_nb;
                        output += '<td class="left">' + collection[x].total_collection.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        totalCollected += collection[x].total_collection;
                        output += "</tr>";
                    }
                    output += "<tr> <td class='left top'> <b> Total </b> </td>";
                    output += "<td class='left top'>" + totalNoPB.toLocaleString("en-US") + "</td>";
                    output += "<td class='left top'>" + totalAmountPB.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + totalNonPB.toLocaleString("en-US") + "</td>";
                    output += "<td class='left top'>" + totalAmountNB.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "<td class='left top'>" + totalCollected.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td>";
                    output += "</tr> </table>";
                }

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