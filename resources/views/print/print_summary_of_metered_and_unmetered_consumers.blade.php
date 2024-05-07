<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <title> Print Summary of Metered and Unmetered Consumers </title>
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
    div.divFooter {
        position: fixed;
        bottom: 0;   
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
    .lasuText {
        font-size: 24px; 
        font-weight: bold; 
        margin-left: -95px;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
    <div id="numBar"> </div>
    <br>
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
        
        toSend.selected = param.selected;
        toSend.location = param.location;
        toSend.filtered = param.filtered;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;

        var summMetUnmet = "{{route('summary.cons.meter')}}";
        xhr.open('POST', summMetUnmet, true);

        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var info = data.Info;

                var output = "";
                var num = 0;
                var totalCons = 0;
                var totalMetered = 0;
                var totalUnmetered = 0;

                output += '<img id="logo" src="/img/logo.png"> <br>';
                output += '<center> <br> <label id="lasuhead" class="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px; margin-left: -95px;"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px; margin-left: -95px;"> teamlasureco@gmail.com </label><br><br>';
                output += '<label style="font-size: 20px;"> METERED AND UNMETERED CONSUMERS </label> </center> <br>';
                // document.querySelector("#runtime").innerHTML = '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label>';
                output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp;&nbsp;' + param.locationName  + '</label> <br>';
                output += '<table id="table"><tr>';

                if(param.selected == "area") {
                    output += '<th class="left top bot"> Town </th>';
                    if(param.locationName == "Area III" || param.locationName == "Area II") {
                        document.querySelector('#numBar').innerHTML = "<br> Number of Town: <b>" + (info.length-1) + "</b>";
                    } else {
                        document.querySelector('#numBar').innerHTML = "<br> Number of Town: <b>" + info.length + "</b>";
                    }
                } else {
                    output += '<th class="left top bot"> Route </th>';
                    document.querySelector('#numBar').innerHTML = "<br> Number of Baranggays: <b>" + info.length + "</b>";
                }
                 
                output += '<th class="left top bot"> Consumers </th>';
                output += '<th class="left top bot"> Metered Consumer </th>';
                output += '<th class="left top bot"> Unmetered Consumer </th>';
                output += '</tr>';
            
                var counter = "";
                var meterer = "";
                var umeterer = "";

                for(var i in info){
                    num += 1;
                    if(num > 0 && num % 45 == 0){
                        output += '</table>';
                        output +='<div class="page-break"> </div>';
                        output += '<img id="logo" src="/img/logo.png"> <br>';
                        output += '<center> <br> <label id="lasuhead" class="lasuText"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px; margin-left: -95px;"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px; margin-left: -95px;"> teamlasureco@gmail.com </label><br><br>';
                        output += '<label style="font-size: 20px;"> METERED AND UNMETERED CONSUMERS </label> </center> <br>';
                        // document.querySelector("#runtime").innerHTML = '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label>';
                        output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp;&nbsp;' + param.locationName  + '</label> <br>';
                        output += '<table id="table"><tr>';
                        output += '<th class="left top bot"> Route </th>';
                        output += '<th class="left top bot"> Number of Consumers </th>';
                        output += '<th class="left top bot"> Number of Metered Consumer </th>'; 
                        output += '<th class="left top bot"> Number of Unmetered Consumer </th></tr><tr>';
                        
                        if(param.selected == "area") {
                            if(info[i].Town_Code !== "42") {
                                if(info[i].Town_Code !== "44"){
                                    output += '<td class="left" style="text-align: left !important;"> &nbsp;&nbsp;&nbsp;' + info[i].Town_Code + " - " + info[i].Town_Name + '</td>';
                                }
                            }
                        } else {
                            output += '<td class="left" style="text-align: left !important;"> &nbsp;&nbsp;&nbsp;' + info[i].Route_Code + " - " + info[i].Route_Name + '</td>';
                        }

                        if(param.locationName == "Area III" && info[i].Town_Code == "43"){
                            counter = info[i].Consumer_Count + info[num].Consumer_Count;
                            meterer = info[i].Metered_Count + info[num].Metered_Count;
                            unmeterer = info[i].Unmetered_Count + info[num].Unmetered_Count;
                        } else if (param.locationName == "Area II" && info[i].Town_Code == "39"){
                            var length = info.length - 1;
                            counter = info[i].Consumer_Count + info[length].Consumer_Count;
                            meterer = info[i].Metered_Count + info[length].Metered_Count;
                            unmeterer = info[i].Unmetered_Count + info[length].Unmetered_Count;
                        } else {
                            counter = info[i].Consumer_Count;
                            meterer = info[i].Metered_Count;
                            unmeterer = info[i].Unmetered_Count;
                        }

                        if(param.selected == "area") {
                            if(info[i].Town_Code !== "42"){
                                if(info[i].Town_Code !== "44"){
                                    output += '<td class="left"> &nbsp;' + counter.toLocaleString("en-US") + '</td>';
                                    output += '<td class="left"> &nbsp;' + meterer.toLocaleString("en-US") + '</td>';
                                    output += '<td class="left"> &nbsp;' + unmeterer.toLocaleString("en-US") + '</td>';
                                    output += '</tr>';
                                }
                            }
                        } else {
                            output += '<td class="left"> &nbsp;' + counter.toLocaleString("en-US") + '</td>';
                            output += '<td class="left"> &nbsp;' + meterer.toLocaleString("en-US") + '</td>';
                            output += '<td class="left"> &nbsp;' + unmeterer.toLocaleString("en-US") + '</td>';
                            output += '</tr>';
                        }

                        totalCons += info[i].Consumer_Count;
                        totalMetered += info[i].Metered_Count;
                        totalUnmetered += info[i].Unmetered_Count;
                    }
                    else{
                        output += '<tr>';
                            
                        if(param.selected == "area") {
                            if(info[i].Town_Code !== "42") {
                                if(info[i].Town_Code !== "44"){
                                    output += '<td class="left" style="text-align: left !important;"> &nbsp;&nbsp;&nbsp;' + info[i].Town_Code + " - " + info[i].Town_Name + '</td>';
                                }
                            }
                        } else {
                            output += '<td class="left" style="text-align: left !important;"> &nbsp;&nbsp;&nbsp;' + info[i].Route_Code + " - " + info[i].Route_Name + '</td>';
                        }

                        if(param.locationName == "Area III" && info[i].Town_Code == "43"){
                            counter = info[i].Consumer_Count + info[num].Consumer_Count;
                            meterer = info[i].Metered_Count + info[num].Metered_Count;
                            unmeterer = info[i].Unmetered_Count + info[num].Unmetered_Count;
                        } else if (param.locationName == "Area II" && info[i].Town_Code == "39"){
                            var length = info.length - 1;
                            counter = info[i].Consumer_Count + info[length].Consumer_Count;
                            meterer = info[i].Metered_Count + info[length].Metered_Count;
                            unmeterer = info[i].Unmetered_Count + info[length].Unmetered_Count;
                        } else {
                            counter = info[i].Consumer_Count;
                            meterer = info[i].Metered_Count;
                            unmeterer = info[i].Unmetered_Count;
                        }

                        if(param.selected == "area") {
                            if(info[i].Town_Code !== "42"){
                                if(info[i].Town_Code !== "44"){
                                    output += '<td class="left"> &nbsp;' + counter.toLocaleString("en-US") + '</td>';
                                    output += '<td class="left"> &nbsp;' + meterer.toLocaleString("en-US") + '</td>';
                                    output += '<td class="left"> &nbsp;' + unmeterer.toLocaleString("en-US") + '</td>';
                                    output += '</tr>';
                                }
                            }
                        } else {
                            output += '<td class="left"> &nbsp;' + counter.toLocaleString("en-US") + '</td>';
                            output += '<td class="left"> &nbsp;' + meterer.toLocaleString("en-US") + '</td>';
                            output += '<td class="left"> &nbsp;' + unmeterer.toLocaleString("en-US") + '</td>';
                            output += '</tr>';
                        }

                        totalCons += info[i].Consumer_Count;
                        totalMetered += info[i].Metered_Count;
                        totalUnmetered += info[i].Unmetered_Count;
                    }
                }
                output += "<tr> <td class='left top'> <b> Total </b> </td> <td class='left top'>" +  totalCons.toLocaleString("en-US") + "</td>";
                output += "<td class='left top'>" + totalMetered.toLocaleString("en-US") + "</td>";
                output += "<td class='left top'>" + totalUnmetered.toLocaleString("en-US") + "</td></tr>";
                output += "</table>";
                output += '<div class="divFooter"><label style="font-size:12px;">Runtime: ' + date + " - " + time + '</label></div>';
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
        // window.print();
    }
</script>