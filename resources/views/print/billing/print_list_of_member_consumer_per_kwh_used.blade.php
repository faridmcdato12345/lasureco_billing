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
        var route = "{{route('per.kwh.used')}}";
        xhr.open('POST', route, true);
        xhr.setRequestHeader("Accept", "application/json");
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send(JSON.stringify(item));
        
        xhr.onload = function(){
            if(xhr.status == 200){ 
                var data = JSON.parse(this.responseText);
                var output = " ";
                    
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> List of Member-Consumer per KWH used </label><br>';
                output += '<label style="font-size: 20px;"> ' + item.from + ' - ' + item.to + ' </label><br>';
                output += '<label style="font-size:20px;">' + date + '</label> </center> <br>';
                if(item.location !== "all"){
                    output += '<label style="font-size: 20px;"> &nbsp;&nbsp;&nbsp;' + item.location[0].toUpperCase() + item.location.substr(1) + ': ' + item.input + '</label><br>';
                } else {
                    output += '<label style="font-size: 20px;"> &nbsp;&nbsp;&nbsp; All </label><br>';
                }
                output += '<table id="table"><tr>';
                    output += '<th class="left top bot"> <b> Account No. </th>';
                        output += '<th class="left top bot"> <b> Name </th>';
                        output += '<th class="left top bot"> <b> Constype </th>';
                        output += '<th class="left top bot"> <b> Address </th>';
                        output += '<th class="left top bot"> <b> Meter # </th>';
                        output += '<th class="left top bot"> <b> Pres Rdg. </th>';
                        output += '<th class="left top bot"> <b> Prev Rdg. </th>';
                        output += '<th class="left top bot"> <b> used </th>';
                        output += '<th class="left top bot"> <b> Amount </th>';
                output += '</tr>';
            
                var items = data.Message;
                var count = 0;

                for(var x in items){
                    if(x>0 && x%25==0){
                        count += 1;
                        output += '</table>';
                        output +='<div class="page-break"> Page ' + count + '</div>';
                        output += '<br><center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                        output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                        output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                        output += '<label style="font-size: 20px;"> List of Member-Consumer per KWH used </label><br>';
                        output += '<label style="font-size: 20px;"> ' + item.from + ' - ' + item.to + ' </label><br>';
                        output += '<label style="font-size: 20px;">' + date + '</label> </center> <br>';
                        if(item.location !== "all"){
                            output += '<label style="font-size: 20px;"> &nbsp;&nbsp;&nbsp;' + item.location[0].toUpperCase() + item.location.substr(1) + ': ' + item.input + '</label><br>';
                        } else {
                            output += '<label style="font-size: 20px;"> &nbsp;&nbsp;&nbsp; All </label><br>';
                        }
                        output += '<table id="table"><tr>';
                        output += '<th class="left top bot"> <b> Account No. </th>';
                        output += '<th class="left top bot"> <b> Name </th>';
                        output += '<th class="left top bot"> <b> Constype </th>';
                        output += '<th class="left top bot"> <b> Address </th>';
                        output += '<th class="left top bot"> <b> Meter # </th>';
                        output += '<th class="left top bot"> <b> Pres Rdg. </th>';
                        output += '<th class="left top bot"> <b> Prev Rdg. </th>';
                        output += '<th class="left top bot"> <b> used </th>';
                        output += '<th class="left top bot"> <b> Amount </th>';
                        output += '</tr> <tr>';
                        output += '<th class="left bot">' + Object.values(items)[x].Account_No + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].NAME + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].Constype + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].Address + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].Meter + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].PresRdg + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].PreVRdg + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].kWh_Used + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].Amount + '</th>';
                    } else {
                        output += '<tr><th class="left bot">' + Object.values(items)[x].Account_No + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].NAME + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].Constype + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].Address + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].Meter + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].PresRdng + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].PrevRdng + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].kWh_Used + '</th>';
                        output += '<th class="left bot">' + Object.values(items)[x].Amount + '</th>';
                    }        
                }
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