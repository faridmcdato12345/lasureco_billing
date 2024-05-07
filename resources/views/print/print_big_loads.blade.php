<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Summary of Sales - Big Load </title>

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
</div>
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    var billPeriod = param.billPeriod;

    function getData(){
        var xhr = new XMLHttpRequest();
        var route = "{{route('report.consumer.largeload', ['billPeriod'=>':par'])}}";
        xhr.open('GET', route.replace(':par',billPeriod),true);
        // xhr.open('GET', 'http://10.12.10.100:8082/api/v1/reports/billing/consLargeLoad/date/' + billPeriod);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        
        xhr.send();

        xhr.onload = function(){
            if(xhr.status == 200){
                
                getDate();
                var data = JSON.parse(this.responseText);
                var consLoad = data.Consumer_Large_Load;
                
                var output = " ";
                
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> SUMMARY OF SALES - BIG LOADS </label><br>';
                output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th> Type Consumer </th>';
                output += '<th> Count </th>';
                output += '<th> KWH Used </th>';
                output += '<th> Bill Amount </th>';
                output += '<th> Generation </th>';
                output += '<th> Transmission </th>';
                output += '<th> Distribution </th>';
                output += '<th> Sys.Loss </th>';
                output += '<th> Dist. Dem </th>';
                output += '<th> Trans. Dem </th>';
                output += '<th> L Cond KWH </th>';
                output += '<th> L Cond Fix </th>';
                output += '</tr>';
                
                for(var i in consLoad){
                     if(i > 0 && i % 20 == 0){
                        output += '</table>';
                        output +='<div class="page-break"></div>';
                        output += '<center> <br><br><br><br><br> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> DECREASE IN CONSUMPTION </label><br>';
                        output += '<label style="font-size:20px;">' + billdate + '</label> </center> <br>';
                        output += '<table id="table"><tr>';
                        output += '<th> Type Consumer </th>';
                        output += '<th> Count </th>';
                        output += '<th> KWH Used </th>';
                        output += '<th> Bill Amount </th>';
                        output += '<th> Generation </th>';
                        output += '<th> Transmission </th>';
                        output += '<th> Distribution </th>';
                        output += '<th> Sys.Loss </th>';
                        output += '<th> Dist. Dem </th>';
                        output += '<th> Trans. Dem </th>';
                        output += '<th> L Cond KWH </th>';
                        output += '<th> L Cond Fix </th>';
                        output += '<tr>';
                        output += '<td>' + consLoad[i].Type_Consumer + '</td>';
                        output += '<td>' + consLoad[i].Count + '</td>';
                        output += '<td>' + consLoad[i].KWH_Used + '</td>';
                        output += '<td>' + consLoad[i].Bill_amount + '</td>'; 
                        output += '<td>' + consLoad[i].Generation + '</td>'; 
                        output += '<td>' + consLoad[i].Transmission + '</td>'; 
                        output += '<td>' + consLoad[i].Distribution + '</td>'; 
                        output += '<td>' + consLoad[i].Sys_Loss + '</td>'; 
                        output += '<td>' + consLoad[i].Dist_Dem + '</td>'; 
                        output += '<td>' + consLoad[i].Trans_Dem + '</td>'; 
                        output += '<td>' + consLoad[i].L_Cond_KWH + '</td>'; 
                        output += '<td>' + consLoad[i].L_Cond_Fix + '</td>'; 
                        output += '</tr>';
                    }
                    else{
                        output += '<tr>';
                        output += '<td>' + consLoad[i].Type_Consumer + '</td>';
                        output += '<td>' + consLoad[i].Count + '</td>';
                        output += '<td>' + consLoad[i].KWH_Used + '</td>';
                        output += '<td>' + consLoad[i].Bill_amount + '</td>'; 
                        output += '<td>' + consLoad[i].Generation + '</td>'; 
                        output += '<td>' + consLoad[i].Transmission + '</td>'; 
                        output += '<td>' + consLoad[i].Distribution + '</td>'; 
                        output += '<td>' + consLoad[i].Sys_Loss + '</td>'; 
                        output += '<td>' + consLoad[i].Dist_Dem + '</td>'; 
                        output += '<td>' + consLoad[i].Trans_Dem + '</td>'; 
                        output += '<td>' + consLoad[i].L_Cond_KWH + '</td>'; 
                        output += '<td>' + consLoad[i].L_Cond_Fix + '</td>'; 
                        output += '</tr>';
                    }
                }

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Consumer Found');
                //window.close();
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