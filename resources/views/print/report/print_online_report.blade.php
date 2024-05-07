<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Payment Report</title>
    <link rel="shortcut icon" href="/img/logo.png">
    <link rel="stylesheet" href="{{asset('css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/buttons.bootstrap.min.css')}}">
</head>
<style>
    body{
        padding: 20px 20px 20px 20px;
    }
    .page-break {
        page-break-after: always;
        font-weight: bold;
        margin-top: 2%;  
    }
    #content {
    display: table;
    }

    #pageFooter {
        display: table-footer-group;
    }

    #pageFooter:after {
        counter-increment: page;
        content: counter(page);
    }
    @media print{
        @page{
            size:letter portrait;
            margin:0;
        }
        div.divFooter {
            position: fixed;
            bottom: 0;   
        }
    }

</style>
<body>
<div id = "online_payment_report">


</div>

<script>
     var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();

    var pageNum = 0;
    var data = JSON.parse(localStorage.getItem('data'));
    var collectionreport = data.collection;
    var output="";
    output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
    output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
    output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
    output += '<label style="font-size: 20px;">' + 'Online Payment Report' + '</label></center><br>';
    // output += '<label style="font-size: 20px;">' + name + ' ' + yr +'</label><br></center><br>';
        output += '<table class="table" style="font-size:12px;width:100%">';
    output += '<tr>' + 
                '<th style="text-align:left;"> Account Number</th>' +
                '<th style="text-align:left;">Name </th>' +
                '<th style="text-align:left;">Reference No. </th>' +
                '<th style="text-align:left;">Payment Type </th>' +
                '<th style="text-align:left;">Transaction Date </th>' +
                '<th style="text-align:left;">Total Paid </th>';
    '</tr>';
    for(let i in collectionreport){

        if(i > 0 && i%20 == 0){
        pageNum += 1;
        output += '</table>';
        
        output += '<div class="page-break"> page ' + pageNum + '</div>';
        // output += '<div id="content"><div id="pageFooter">Page </div></div>';
        output += '<div style="padding:20px 20px 20px 20px"></div>';
        output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
        output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
        output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
        output += '<label style="font-size: 20px;">' + 'Online Payment Report' + '</label></center><br>';
        output += '<table class="table" style="font-size:12px;width:100%">';
        output += '<tr>' + 
                '<th style="text-align:left;"> Account Number</th>' +
                '<th style="text-align:left;">Name </th>' +
                '<th style="text-align:left;">Reference No. </th>' +
                '<th style="text-align:left;">Payment Type </th>' +
                '<th style="text-align:left;">Transaction Date </th>' +
                '<th style="text-align:left;">Total Paid </th>';
        '</tr>';
        output += '<tr>' +
               '<td>' + collectionreport[i].Account_No + '</td>' +  
               '<td>' + collectionreport[i].Name.slice(0,23)+ '</td>' +
               '<td>' + collectionreport[i].OR_No+ '</td>' +
               '<td>' + collectionreport[i].Payment_Type+ '</td>' +
               '<td>' + collectionreport[i].Date+ '</td>' +
               '<td>' + collectionreport[i].Total_Paid.toLocaleString("en-US", { minimumFractionDigits: 2 })+ '</td>' +
        '</tr>';
        
        }
        else{
            output += '<tr>' +
               '<td>' + collectionreport[i].Account_No + '</td>' +  
               '<td>' + collectionreport[i].Name.slice(0,23)+ '</td>' +
               '<td>' + collectionreport[i].OR_No+ '</td>' +
               '<td>' + collectionreport[i].Payment_Type+ '</td>' +
               '<td>' + collectionreport[i].Date+ '</td>' +
               '<td>' + collectionreport[i].Total_Paid.toLocaleString("en-US", { minimumFractionDigits: 2 })+ '</td>' +
        '</tr>';
        }
    }
    output += '</table>';
    output += '<table>'; 
        output += '<tr>' +
            '<td>' + 'Total Collection: ' + '</td>' +
            '<td>' +data.total_collection.toLocaleString("en-US", { minimumFractionDigits: 2 })+ '</td>' +   
        '</tr>';
        output += '</table><br>';
        output += '<div style="font-weight:bold"> page ' + (parseInt(pageNum)+1) + '</div>';
        output += '<div class="divFooter"><label style="font-size:12px;">Runtime: ' + date + " - " + time + '</label></div>';
    document.querySelector('#online_payment_report').innerHTML = output;
</script>
</body>
</html>

<!-- web route  -->

