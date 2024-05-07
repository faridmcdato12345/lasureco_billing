<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print General Summary Report </title>

</head>
<style media="print">
    @page {
      size: auto;
      margin: 2mm;
    }
</style>
<style>
    .page-break {
        page-break-after: always;
    }
    body {
        font-family: Calibri;
    }
    table {
        /* margin:auto; */
        height:100px;
        font-size: 17px;
        width: 97%; 
    }
    th {
        height: 60px;
    }
    td {
        text-align: center;
        height: 50px;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
    <br><br><br>
    <div id = "div1"> </div>
    <div id = "div2"> </div>
    <div id = "div3"> </div>
    <div id = "div4"> </div>
    <div id = "div5"> </div>
    <div id = "div6"> </div>
    <div id = "div7"> </div>
    <div id = "div8"> </div>
</div>
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    
    var billPeriod = param.billPeriod;
    var selected = param.selected;
    var areaId = param.areaId;
    var newSelected = "";

    if(selected == 1) {
        newSelected = "billed_on_time";
    } else if(selected == 2) {
        newSelected = "include_late_billing";
    } 

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.date = billPeriod;
        toSend.area_id = areaId;
        toSend.selected = newSelected;

        var toSendJSONed = JSON.stringify(toSend);
        var printGenSumm = "{{route('report.general.summary')}}";
        var token = document.querySelector('meta[name="csrf-token"]').content;
        xhr.open('POST', printGenSumm, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                getDate();
                var data = JSON.parse(this.responseText);
                var generalReport = data.General_Report;
                var consTypeDetails = data.Consumer_Type_Detail;
                var consType = data.Consumer_type;

                var output = " ";
                var output2 = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> GENERAL DETAIL REPORT </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th> Route </th>';
                output += '<th> Number of Cons </th>';
                output += '<th> Total KWh Used </th>';
                output += '<th> Bill Amount </th>';
                output += '</tr>';
                
                for(var i in generalReport){
                     if(i > 0 && i%20 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <br><br><br><br><br> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> GENERAL DETAIL </label><br>';
                        output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br>';
                        output += '<table id="table"><tr>';
                        output += '<th> Route </th>';
                        output += '<th> Number of Cons </th>';
                        output += '<th> Total KWh Used </th>';
                        output += '<th> Bill Amount </th>';
                        output += '<tr>';
                        output += '<td>' + i + ' ' + generalReport[i].Route_desc + '</td>';
                        output += '<td>' + generalReport[i].no_of_Cons + '</td>';
                        output += '<td>' + generalReport[i].Total_KWH_USED + '</td>';
                        output += '<td>' + generalReport[i].Bill_Amount + '</td>';
                        output += '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td>' + i + ' ' + generalReport[i].Route_desc + '</td>';
                        output += '<td>' + generalReport[i].no_of_Cons + '</td>';
                        output += '<td>' + generalReport[i].Total_KWH_USED + '</td>';
                        output += '<td>' + generalReport[i].Bill_Amount + '</td>';
                        output += '</tr>';
                    }
                }
                
                var number = -1;
                var divnum = 0;

                for(var i in consTypeDetails){
                    number += 1; 
                    var constype = consType[number].ct_desc;

                    divnum += 1;

                    if(Object.keys(consTypeDetails[number]).length == 0){
                        var genDetailsLength = Object.keys(generalReport).length;   
                        
                        var divrows = '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        divrows += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        divrows += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        divrows += '<label style="font-size: 20px;"> GENERAL DETAIL REPORT </label><br>';
                        divrows += '<label style="font-size:20px;">' + billdate + '</label> </center>';
                        divrows += '<label style="font-size:20px; float: left; margin: 40px;"> Consumer type: ' + constype + '</label>';
                        divrows += '<table id="table"><tr>';
                        divrows += '<th> Route </th>';
                        divrows += '<th> Number of Cons </th>';
                        divrows += '<th> Total KWh Used </th>';
                        divrows += '<th> Bill Amount </th>';
                        divrows += '</tr>';
                        
                        for(var i in generalReport){
                            if(i > 0 && i % 20 == 0){
                                divrows += '</table>';
                                divrows +='<div class="page-break"></div>';
                                divrows += '<center> <br><br><br><br><br> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                                divrows += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                                divrows += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                                divrows += '<label style="font-size: 20px;"> GENERAL DETAIL </label><br>';
                                divrows += '<label style="font-size:20px;">' + billdate + '</label> </center>';
                                divrows += '<label style="font-size:20px; float: left; margin: 40px;"> Consumer type: ' + constype + '</label>';
                                divrows += '<table id="table"><tr>';
                                divrows += '<th> Route </th>';
                                divrows += '<th> Number of Cons </th>';
                                divrows += '<th> Total KWh Used </th>';
                                divrows += '<th> Bill Amount </th>';
                                divrows += "<tr> <td>" + i + " " + generalReport[i].Route_desc + " </td>";
                                divrows += "<td> 0 </td>";
                                divrows += "<td> 0 </td>";
                                divrows += "<td> 0 </td></tr>";
                            }
                            else{
                                divrows += "<tr> <td>" + i + " " + generalReport[i].Route_desc + " </td>";
                                divrows += "<td> 0 </td>";
                                divrows += "<td> 0 </td>";
                                divrows += "<td> 0 </td></tr>";
                            }
                        }
                        divrows += "</table> <br>";

                        var divname = 'div' + divnum;
                        document.getElementById(divname).innerHTML = divrows;
                        divrows = "";
                        
                    } else {
                        var i=0;

                        var divrows = '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        divrows += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        divrows += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        divrows += '<label style="font-size: 20px;"> GENERAL DETAIL REPORT </label><br>';
                        divrows += '<label style="font-size:20px;">' + billdate + '</label> </center>';
                        divrows += '<label style="font-size:20px; float: left; margin: 40px;"> Consumer type: ' + constype + '</label>';
                        divrows += '<table id="table"><tr>';
                        divrows += '<th> Route </th>';
                        divrows += '<th> Number of Cons </th>';
                        divrows += '<th> Total KWh Used </th>';
                        divrows += '<th> Bill Amount </th>';
                        divrows += '</tr>';

                        for(var i in generalReport){
                            if(i > 0 && i % 20 == 0){
                                divrows += '</table>';
                                divrows +='<div class="page-break"></div>';
                                divrows += '<center> <br><br><br><br><br> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                                divrows += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                                divrows += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                                divrows += '<label style="font-size: 20px;"> GENERAL DETAIL </label><br>';
                                divrows += '<label style="font-size:20px;">' + billdate + '</label> </center>';
                                divrows += '<label style="font-size:20px; float: left; margin: 40px;"> Consumer type: ' + constype + '</label>';
                                divrows += '<table id="table"><tr>';
                                divrows += '<th> Route </th>';
                                divrows += '<th> Number of Cons </th>';
                                divrows += '<th> Total KWh Used </th>';
                                divrows += '<th> Bill Amount </th>';
                                divrows += "<tr>";
                                
                                if(consTypeDetails[number][i] !== undefined){
                                    var a=consTypeDetails[number][i];
                                    divrows += "<td>" + i + " " + a[constype].Route_desc + "</td>";
                                    divrows += "<td>" + a[constype].no_of_Cons + "</td>";
                                    divrows += "<td>" + a[constype].Total_KWH_USED + "</td>";
                                    divrows += "<td>" + a[constype].Bill_Amount + "</td>";
                                } else {
                                    divrows += "<td>" + i + " " + generalReport[i].Route_desc + "</td>";
                                    divrows += "<td> 0 </td>";
                                    divrows += "<td> 0 </td>";
                                    divrows += "<td> 0 </td>";
                                }
                                divrows += "</tr>";
                            }
                            else{
                                if(consTypeDetails[number][i] !== undefined){
                                    var a=consTypeDetails[number][i];
                                    divrows += "<td>" + i + " " + a[constype].Route_desc + "</td>";
                                    divrows += "<td>" + a[constype].no_of_Cons + "</td>";
                                    divrows += "<td>" + a[constype].Total_KWH_USED + "</td>";
                                    divrows += "<td>" + a[constype].Bill_Amount + "</td>";
                                } else {
                                    divrows += "<td>" + i + " " + generalReport[i].Route_desc + "</td>";
                                    divrows += "<td> 0 </td>";
                                    divrows += "<td> 0 </td>";
                                    divrows += "<td> 0 </td>";
                                }
                                divrows += "</tr>";
                            }
                        }
                        divrows += "</table>";
                        
                        var divname = 'div' + divnum;
                        document.getElementById(divname).innerHTML = divrows;
                        divrows = "";
                    }
                }


                divrows += "<tr>";
                            


                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Report Found');
                window.close();
            }
        }
    }

    function getDate() {
        var date = JSON.stringify(billPeriod);
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