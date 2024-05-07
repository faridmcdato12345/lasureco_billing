<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary Fit All</title>

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

    body section{
        margin: auto;
        width: 85%;
        text-align: center;
    }
</style>
<body onload="getData()">
<div id = "printBody">
</div>
</body>
</html>
<script>
    function getData(){
                var data = JSON.parse(localStorage.getItem('data'));
                var town = data.Summary_Fit_All;
                var areaname = data.Area_Name;
                var area = data.Summary_Fit_All[0].Area;
                var output = " ";
                var output2 = " ";
                var petsa = JSON.parse(localStorage.getItem('petsa'));
                petsa1 = petsa.slice(5);
                petsa2 = petsa.slice(0,4);
                const monthNames = [" ","January", "February", "March", "April", "May", "June",
                                    "July", "August", "September", "October", "November", "December"
                                    ];
                output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
                output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
                output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
                output += '<label style="font-size: 20px;">' + 'SUMMARY OF FIT ALL - BILLED' + '</label><br>';
                output += '<label style="font-size: 20px;">' + areaname + '</label><br>';
                output += '<label style="font-size:20px;"> For the Month of ' + monthNames[parseInt(petsa1)] + ' '+petsa2+ '</label></center><br>';
                output += '<table style="width:80%;margin:auto;height:100px"><tr>';
                output += '<th style = "border-bottom:2px solid blue">TOWN</th>';
                output += '<th style = "border-bottom:2px solid blue">KWH USED</th>';
                output += '<th style = "border-bottom:2px solid blue">AMOUNT</th>';
                output += '</tr>';
                
                output += '<tr >';
                output += '<td style="padding-bottom:10px;">' + areaname + '</td>';
                output += '</tr>';
                for(var i in town){
                    var t = town[i].Town;
                    var tc = town[i].Town_code;
                    var k = town[i].KWH_USED;
                    var a = town[i].Amount;
                        output += '<tr>';
                        output += '<td>'+ tc +' - '+t +'</td>';
                        output += '<td>' + k.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '<td>' + a.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                        output += '</tr>';
                }
                var total1 = data.Total[0].KWH_USED;
                var total2 = data.Total[0].Amount;
                output += '<tr>';
                output += '<td> </td>';
                output +='<td style="padding-top:10px;border-top:2px solid red;">' + total1.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                output += '<td style="padding-top:10px;border-top:2px solid red;">' + total2.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td></tr>';
                output += '</table>';
                
            document.querySelector('#printBody').innerHTML = output;
            window.print();
    }
    </script>
