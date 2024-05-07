<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/twitter-bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/buttons.bootstrap.min.css')}}">
    <title>Summary of Unposted Collection</title>

</head>
<style media="print">
    @page {
      size: 8.5in 13in;
      margin:0.2in;
    }
    @page :footer {color: #fff }
    @page :header {color: #fff}
</style>
<style>
    .page-break {
        page-break-after: always;
    }

    body section{
        margin: auto;
        width: 85%;
        text-align: center;
        font-family: calibri;
    }
</style>

<body onload="getData()">
<div id = "printBody">
    
</div>
</body>
</html>
<script>
    function getData(){
        var d = JSON.parse(localStorage.getItem('data'));
        var auth = localStorage.getItem('auth');
        var name = localStorage.getItem('areaname');
        console.log(auth);
                var output = " ";
                output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
                output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
                output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
                output += '<label style="font-size: 20px;">' + 'Summary of Unposted Collection' + '</label><br>'
                output += '<label style="font-size: 20px;">' + d.date + '</label></center><br>';
                output += '<label style="font-weight:bold;"> Office Code: ' + name + '</label><br>';
                
                output += '<div style="border-style:groove"></div>';
                output += '<div class="row">';
                output += '<div class="col">';
                output += '<label style="font-weight:bold">Teller/Collector</label><br>';
                output += '<table>'
                for(let i in d.Unposted_Bill){
                    output += '<tr><td>' + d.Unposted_Bill[i].Teller_Collector + '</td></tr>';
                    console.log(d.Unposted_Bill[i].Teller_Collector);
                }
                output += '</table>';
                output += '</div>';
                output += '<div class="col">';
                output += '<label style="font-weight:bold">Amount Collected:</label>';
                output += '<table>'
                for(let i in d.Unposted_Bill){
                    output += '<tr><td>' + d.Unposted_Bill[i].Amount_Collected.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td></tr>';
                    console.log(d.Unposted_Bill[i].Amount_Collected);
                }
                output += '</table>';
                output += '</div>';
                output += '</div><br>';
                output += '<div style="border-style:groove"></div><br>';
                output += '<div class="row">';
                output += '<div class="col">';
                output += '<label>Grand Total:</label>';
                output += '</div>';
                output += '<div class="col">';
                output += '<label>' + d.Total_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) +'</label>';
                output += '</div>';
                output += '</div><br>';
                output += '<div style="border:1px dashed black"></div><br>';
                output += '<div class="row">';
                output += '<div class="col">';
                output += '<label>Prepared By:&nbsp' + auth + '</label>';
                output += '</div>';
                output += '</div><br>';
                
                document.querySelector('#printBody').innerHTML = output;
    }
    </script>
