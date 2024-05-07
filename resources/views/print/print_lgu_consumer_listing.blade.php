<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <title> Print LGU Consumer Listing </title>
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
        width: 70%;
        font-size: 15px;
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

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date_from = param.area_from;
        toSend.date_to = param.area_to;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;

        var lguConsList = "{{route('report.accounting.lgu.list')}}";
        xhr.open('POST', lguConsList, true);

        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var info = data.info;

                var output = "";
                var totalAmount = 0;

                output += '<img id="logo" src="/img/logo.png">';
                output += "<br>";
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 21px;"> LGU CONSUMER LISTING </label></center> <br>';
                output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th class="left top bot"> Account </th>';
                output += '<th class="left top bot"> Consumer Name </th>';
                output += '</tr>';
            
                for(var i in info){
                    for(var x in info[i]){
                        if(x > 0 && x % 25 == 0){
                            output += '</table>';
                            output +='<div class="page-break"></div>';
                            output += '<img id="logo" src="/img/logo.png">';
                            output += "<br>";
                            output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                            output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                            output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                            output += '<label style="font-size: 21px;"> LGU CONSUMER LISTING </label></center> <br>';
                            output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                            output += '<table id="table"><tr></tr>';
                            output += '<th class="left top bot"> Account No </th>';
                            output += '<th class="left top bot"> Consumer Name </th>';
                            output += '<tr><td class="left" width="20%">' + info[i][x].account_no + '</td>';
                            output += '<td class="left" style="text-align: left !important;"> &nbsp;&nbsp;' + info[i][x].consumer_name + '</td>';
                            output += '</tr>';
                        }
                        else{
                            output += '<td class="left" width="20%">' + info[i][x].account_no + '</td>';
                            output += '<td class="left" style="text-align: left !important;"> &nbsp;&nbsp;' + info[i][x].consumer_name + '</td>';
                            output += '</tr>';
                        }
                    }
                    
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