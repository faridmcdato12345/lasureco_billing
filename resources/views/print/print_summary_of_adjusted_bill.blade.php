<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Print Summary of Adjusted Bill </title>

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
            /* font-size: 10px !important; */
        }
        table {
            /* margin:auto; */
            height:100px;
            font-size: 11px;
            width: 95%; 
            margin: auto;
        }
        th {
            height: 30px;
            border-bottom: 1px solid black;
            border-top: 1px solid black;
        }
        td {
            text-align: center;
            height: 20px;
        }
        tr{
            border-bottom: 1px solid gray;
        }
    </style>

    <body onload="getData()">
        <div id = "printBody"> </div>
    </body>

</html>

<script>
    var adjBP = localStorage.getItem("adjBP");
    localStorage.clear();

    function getData(){
        var xhr = new XMLHttpRequest();
        var route = "{{route('report.bill.adjustment.summary',['date_period'=>':par'])}}";
        xhr.open('GET', route.replace(':par',adjBP), true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                var output = "";
                var no = 1;
                let date = new Date(adjBP);
                var longDate = date.toLocaleString('en-US', {
                    year: 'numeric', // numeric, 2-digit
                    month: 'long', // numeric, 2-digit, long, short, narrow
                });

                // console.log(data.Adj_Bill[0]);
                // console.log(data.Total[0]);

                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> SUMMARY BILL ADJUSTMENT</label><br>';
                output += '<label style="font-size:20px;">' + longDate + '</label> </center> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th> No. </th>';
                output += '<th> Bill No. </th>';
                output += '<th> Account No. </th>';
                output += '<th> Name </th>';
                output += '<th> Yr./Month </th>';
                output += '<th> Increase KWHr </th>';
                output += '<th> Decrease KWHr </th>';
                output += '<th> Increase Amount </th>';
                output += '<th> Decrease Amount </th>';
                output += '<th> Adjusted by </th>';
                output += '<th> Date Adjusted </th>';
                output += '</tr>';
                
                for(let i in data.Adj_Bill){
                    if(i != 0 && i%50 == 0){
                        output += "<div class='page-break'></div>";
                    }else{
                        output += "<tr>";
                        output += "<td>"+(no++)+"</td>";
                        output += "<td>"+data.Adj_Bill[i].Bill_No+"</td>";
                        output += "<td>"+data.Adj_Bill[i].Account_No+"</td>";
                        output += "<td>"+data.Adj_Bill[i].Name+"</td>";
                        output += "<td>"+data.Adj_Bill[i].Year_Month+"</td>";
                        output += "<td>"+data.Adj_Bill[i].KWH_Increase+"</td>";
                        output += "<td>"+data.Adj_Bill[i].KWH_Decrease+"</td>";
                        output += "<td>"+data.Adj_Bill[i].Amount_Increase+"</td>";
                        output += "<td>"+data.Adj_Bill[i].Amount_Decrease+"</td>";
                        output += "<td>"+data.Adj_Bill[i].Adjusted_By+"</td>";
                        output += "<td>"+data.Adj_Bill[i].Adjusted_date+"</td>";
                        output += "</tr>";
                    }
                }

                output += '<tr>';
                output += '<th colspan="5"></th>';
                output += '<th> Total Increase KWHr </th>';
                output += '<th> Total Decrease KWHr </th>';
                output += '<th> Total Increase Amount </th>';
                output += '<th> Total Decrease Amount </th>';
                output += '<th> </th></tr>';
                output += "<tr>";
                output += "<td colspan='5' style='text-align: left;'>TOTAL NO. OF ADJUSTMENTS : "+(no-1)+"</td>";;
                output += "<td>"+data.Total.Total_KWH_Increase+"</td>";
                output += "<td>"+data.Total.Total_KWH_Decrease+"</td>";
                output += "<td>"+data.Total.Total_Amount_Increase+"</td>";
                output += "<td>"+data.Total.Total_Amount_Decrease+"</td>";
                output += "</tr>";
                output += "<tr><td colspan='4' style='text-align: left; padding-top: 2%;'>Prepared by:  {{Auth::user()->user_full_name}}</td></tr></table>"

                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Bills Found');
                window.close();
            }
        }
    }
</script>