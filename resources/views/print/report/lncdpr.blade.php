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
    <title>LIFELINE CONSUMER DETAILED PER ROUTE</title>
</head>
<style>
.page-break {
    page-break-after: always;
}
body section{
    margin: auto;
    width: 85%;
    text-align: center;
}
.contentA {
  display: flex;
  flex:1;
  color: #000;
}
.contentB {
  display: flex;
  flex:1;
  color: #000;
}
.contentC {
  display: flex;
  flex: 1;
}
</style>
<body>
    <div id = "tabs">

    </div>
</body>
<script>
   var data = JSON.parse(localStorage.getItem('data3'));
   var petsa = JSON.parse(localStorage.getItem('date'));
   petsa1 = petsa.slice(5);
   petsa2 = petsa.slice(0,4);
   const monthNames = [" ","January", "February", "March", "April", "May", "June",
                      "July", "August", "September", "October", "November", "December"
                      ];
   var arr = data.Lifeline_Consumer;
   var output = "";  
    output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
    output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
    output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
    output += '<label style="font-size: 20px;">' + 'LIFELINE CONSUMER DETAILED PER ROUTE' + '</label><br>';
    output += '<label style="font-size:20px;"> For the Month of ' + monthNames[parseInt(petsa1)] + ' ' + petsa2 + '</label></center><br>';
    output += '<label style="font-weight:bold;"> AREA CODE: ' + data.Area+ '</label><br>';
    output += '<label style="font-weight:bold;"> TOWN CODE: ' + data.Town+ '</label><br>';
    output += '<label style="font-weight:bold;"> ROUTE CODE: ' + data.Route + '</label><br><br>';
    output += '<table style="width:100%;">' +
                '<tr style = "border-bottom:0.5px dashed black;border-top:0.5px dashed black">' +
                '<th>' + 'ACCT NO.' + '</th>' +
                '<th>' + 'NAME' + '</th>' +
                '<th>' + 'KWH USED' + '</th>' +
                '<th>' + 'LFELNE-DISC' + '</th>' +
                '<th>' + 'BILL AMOUNT' + '</th>' +
                '</tr>';
    for(let x in arr){
        output += '<tr >' +
                '<td>' + arr[x].Account_No + '</td>' +
                '<td>' + arr[x].Name + '</td>' +
                '<td>' + arr[x].Kwh_Used + '</td>' +
                '<td>' + arr[x].LDISCOUNT + '</td>' +
                '<td>' + arr[x].Bill_Amount + '</td>' +
                '</tr>';
                
    }
    output += '<tr style = "border-top:0.5px dashed black" >' + 
            '<td>' + 'TOTAL CONSUMERS>>>' + '</td>' +
            '<td>' + data.Total.Total_Consumer + '</td>' +
            '<td>' + data.Total.Total_Kwh_Used+ '</td>' +
            '<td>' + data.Total.Total_LDiscount + '</td>' +
            '<td>' + data.Total.Total_Bill_Amount + '</td>' +
            '</tr>';
    output += '</table>';
    document.querySelector('#tabs').innerHTML =output;
    window.print();
</script>
</html>
