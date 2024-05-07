<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Master List </title>

</head>
<style media="print">
    @page {
      size: auto;
      margin: 2mm;
    }
</style>
<style>
    .page-break {
        page-break-after: always !important;
    }
    body {
        font-family: Consolas
    }
    table {
        margin: auto;
        font-size: 15px;
        width: 97%;
	    border-right: 0.75px dashed;
	    border-bottom: 0.75px dashed;
        page-break-after: always !important;
    }
    .left {
        border-left: 0.75px dashed;
        text-align: center;
    }
    .right{
        border-right: 0.75px dashed;
        text-align: center;
    }
    .top{
        border-top: 0.75px dashed;
        text-align: center;
    }
    .bot{
	    border-bottom: 0.75px dashed;
	    text-align: center;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
</div>
</body>
</html>

<script>
    var toSend = new Object();

    if(localStorage !== "") {
        var param = JSON.parse(localStorage.getItem("data"));

        toSend.town_code_id = param.townID;
        toSend.route_code_from = param.routeFrom;
        toSend.route_code_to = param.routeTo;
        toSend.option = param.option;
    }
    function getData(){
        var xhr = new XMLHttpRequest();
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var masterList = "{{route('master.list')}}";
        xhr.open('POST', masterList, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                localStorage.clear();
                var data = JSON.parse(this.responseText);
                var masterList = data.Message;
                var output = " ";
               
                for(var a in masterList){
                    output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                    output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                    output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                    output += '<label style="font-size: 20px;"> MASTERLIST  </label> </center> <br>';
                    output += "<table> <tr>";
                    output += "<td class='left top bot'> Series </td>";
                    output += "<td class='left top bot'> Name </td>";
                    output += "<td class='left top bot'> Meter Number </td>";
                    output += "<td class='left top bot'> Consumer Type </td>";
                    output += "<td class='left top bot'> Membership Fee </td>";
                    output += "<td class='left top bot'> Date Approved/Confirmed </td> </tr>";
                    output += '<label style="font-size:17px;"> &nbsp;&nbsp;' + a + '</label> <br><br>';
                    
                    for(var x in masterList[a]){
                        if(x>0 && x%35==0){
                            // output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                            // output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                            // output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                            // output += '<label style="font-size: 20px;"> MASTERLIST  </label> </center> <br>';
                            output += "</table>";
                            output += "<div class='page-break'></div>";
                            output += "<table> <tr>";
                            output += "<td class='left top bot'> Series </td>";
                            output += "<td class='left top bot'> Name </td>";
                            output += "<td class='left top bot'> Meter Number </td>";
                            output += "<td class='left top bot'> Consumer Type </td>";
                            output += "<td class='left top bot'> Membership Fee </td>";
                            output += "<td class='left top bot'> Date Approved/Confirmed </td> </tr>";
                            output += '<label style="font-size:17px;"> &nbsp;&nbsp;' + a + '</label> <br><br>';
                            output += "<tr> <td class='left'>" + masterList[a][x].Series + "</td>";
                            output += "<td class='left'>" + masterList[a][x].Name + "</td>";
                            output += "<td class='left'>" + masterList[a][x].Meter_Number + "</td>";
                            output += "<td class='left'>" + masterList[a][x].Type_Of_Consumer + "</td>";
                            output += "<td class='left'>" + masterList[a][x].Membership_Fee + "</td>";
                            output += "<td class='left'>" + masterList[a][x].Date_Approved_Confirmed + "</td> </tr>";
                        } else {
                            output += "<tr> <td class='left'>" + masterList[a][x].Series + "</td>";
                            output += "<td class='left'>" + masterList[a][x].Name + "</td>";
                            output += "<td class='left'>" + masterList[a][x].Meter_Number + "</td>";
                            output += "<td class='left'>" + masterList[a][x].Type_Of_Consumer + "</td>";
                            output += "<td class='left'>" + masterList[a][x].Membership_Fee + "</td>";
                            output += "<td class='left'>" + masterList[a][x].Date_Approved_Confirmed + "</td> </tr>";
                        }
                        
                        
                    }
                    output += "</table> </br>";
                }
				
                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Reports Found');
                //window.close();
            }
        }
    }

    const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];

    const d = new Date();
    let name = month[d.getMonth()];
    let year = d.getFullYear()
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