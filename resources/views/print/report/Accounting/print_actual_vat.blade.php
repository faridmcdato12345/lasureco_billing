<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actual VAT Collection</title>
    <link rel="shortcut icon" href="/img/logo.png">
    <link rel="stylesheet" href="{{asset('css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/buttons.bootstrap.min.css')}}">
</head>
<style>
    body{
        padding: 20px 20px 20px 20px;
        font-size:12.5px;
    }
    .page-break {
        page-break-after: always;
    }
    @media print{
        @page{
            size:legal landscape;
            margin:0;
        }
    }

</style>
<body>
<div id = "Delq">


</div>
<script>
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();

    var data = JSON.parse(localStorage.getItem('data'));
    var info = localStorage.getItem('info');
    var details = data.Details;
    var grandTotal = data.Grand_Total;
    console.log(data);
    console.log(details);
    console.log(info);
    var j=0;
    const month = ["","January","February","March","April","May","June","July","August","September","October","November","December"];
    var dateyr = info.slice(0,4);
    var dateday= info.slice(5,7);
    output = "";
    output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
    output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
    output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
    output += '<label style="font-size: 20px;">' + 'Actual VAT Collection' + '</label><br>';
    output += '<label style="font-size: 20px;">' + month[parseInt(dateday)] + ',' + ' ' + dateyr + '</label><br></center><br>';
    output += '<label style="font-size:17px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
    output += '<table class="table" style="width:100%">' +
    '<tr>' +
                '<th style="text-align:left">Code</th>' +
                '<th style="text-align:left">Description</th>' +
                '<th style="text-align:left">Gen.</th>' +
                '<th style="text-align:left">Trans. Sys.</th>' +
                '<th style="text-align:left">Sys. Loss</th>' +
                '<th style="text-align:left">Loan Condo. Fix</th>' +
                '<th style="text-align:left">Loan Condo. KWH</th>' +
                '<th style="text-align:left">Meter Fix</th>' +
                '<th style="text-align:left">Pow.Act.Red.</th>' +
                '<th style="text-align:left">Supp.Fix</th>' +
                '<th style="text-align:left">Supp.Sys.</th>' +
                '<th style="text-align:left">Lfln_disc_subs</th>' +
                '<th style="text-align:left">Dist.Sys.</th>' +
                '<th style="text-align:left">Meter Sys.</th>' +
                '<th style="text-align:left">Trans.Dem.</th>' +
                '<th style="text-align:left">Dist.Dem.</th>' +
                '<th style="text-align:left">Total</th>' +
            '</tr>';
    for(let i in details){
        if(i > 0 && i%30 == 0){
          
            output += '</table>';
            output +='<div class="page-break"></div>';
            output +='<div style="padding-top:20px;padding-left:10px;padding-right:10px"></div>';
            output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
            output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
            output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
            output += '<label style="font-size: 20px;">' + 'Actual VAT Collection' + '</label><br>';
            output += '<label style="font-size: 20px;">' + month[parseInt(dateday)] + ',' + ' ' + dateyr + '</label><br></center><br>';
            output += '<label style="font-size:17px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
            output += '<table class="table" style="width:100%">' +
            '<tr>' +
                '<th style="text-align:left;">Code</th>' +
                '<th style="text-align:left">Description</th>' +
                '<th style="text-align:left">Gen.</th>' +
                '<th style="text-align:left">Trans. Sys.</th>' +
                '<th style="text-align:left">Sys. Loss</th>' +
                '<th style="text-align:left">Loan Condo. Fix</th>' +
                '<th style="text-align:left">Loan Condo. KWH</th>' +
                '<th style="text-align:left">Meter Fix</th>' +
                '<th style="text-align:left">Pow.Act.Red.</th>' +
                '<th style="text-align:left">Supp.Fix</th>' +
                '<th style="text-align:left">Supp.Sys.</th>' +
                '<th style="text-align:left">Lfln_disc_subs</th>' +
                '<th style="text-align:left">Dist.Sys.</th>' +
                '<th style="text-align:left">Meter Sys.</th>' +
                '<th style="text-align:left">Trans.Dem.</th>' +
                '<th style="text-align:left">Dist.Dem.</th>' +
                '<th style="text-align:left">Total</th>' +
            '</tr>';
            output += '<tr>';
            output += '<td>'+details[i].Code+'</td>';
            output += '<td>'+details[i].Description+'</td>';
            output += '<td>'+details[i].Generation+'</td>';
            output += '<td>'+details[i].Transmission_System+'</td>';
            output += '<td>'+details[i].System_Loss+'</td>';
            output += '<td>'+details[i].Loan_Condonation_Fix+'</td>';
            output += '<td>'+details[i].Loan_Condonation_KWH+'</td>';
            output += '<td>'+details[i].Meter_Fix_Vat+'</td>';
            output += '<td>'+details[i].Power_Act_Red_Vat+'</td>';
            output += '<td>'+details[i].Supply_Fix_Vat+'</td>';
            output += '<td>'+details[i].Supply_Sys_Vat+'</td>';
            output += '<td>'+details[i].lfln_disc_subs_vat+'</td>';
            output += '<td>'+details[i].Distribution_System+'</td>';
            output += '<td>'+details[i].Meter_Sys_Vat+'</td>';
            output += '<td>'+details[i].Transmission_Demand+'</td>';
            output += '<td>'+details[i].Distribution_Demand+'</td>';
            output += '<td style="text-align:left">'+details[i].Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 })+'</td>';
            output += '</tr>';
        }
        else{
            output += '<tr >';
            output += '<td>'+details[i].Code+'</td>';
            output += '<td>'+details[i].Description+'</td>';
            output += '<td>'+details[i].Generation+'</td>';
            output += '<td>'+details[i].Transmission_System+'</td>';
            output += '<td>'+details[i].System_Loss+'</td>';
            output += '<td>'+details[i].Loan_Condonation_Fix+'</td>';
            output += '<td>'+details[i].Loan_Condonation_KWH+'</td>';
            output += '<td>'+details[i].Meter_Fix_Vat+'</td>';
            output += '<td>'+details[i].Power_Act_Red_Vat+'</td>';
            output += '<td>'+details[i].Supply_Fix_Vat+'</td>';
            output += '<td>'+details[i].Supply_Sys_Vat+'</td>';
            output += '<td>'+details[i].lfln_disc_subs_vat+'</td>';
            output += '<td>'+details[i].Distribution_System+'</td>';
            output += '<td>'+details[i].Meter_Sys_Vat+'</td>';
            output += '<td>'+details[i].Transmission_Demand+'</td>';
            output += '<td>'+details[i].Distribution_Demand+'</td>';
            output += '<td style="text-align:left">'+details[i].Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 })+'</td>';
            output += '</tr>';
        }
    }
    output += '<tr>';
            output += '<td>'+'Grand_Total'+'</td>';
            output += '<td>'+'>>>'+'</td>';
            output += '<td>'+grandTotal.Generation+'</td>';
            output += '<td>'+grandTotal.Transmission_System+'</td>';
            output += '<td>'+grandTotal.System_Loss+'</td>';
            output += '<td>'+grandTotal.Loan_Condonation_Fix+'</td>';
            output += '<td>'+grandTotal.Loan_Condonation_KWH+'</td>';
            output += '<td>'+grandTotal.Meter_Fix_Vat+'</td>';
            output += '<td>'+grandTotal.Power_Act_Red_Vat+'</td>';
            output += '<td>'+grandTotal.Supply_Fix_Vat+'</td>';
            output += '<td>'+grandTotal.Supply_Sys_Vat+'</td>';
            output += '<td>'+grandTotal.lfln_disc_subs_vat+'</td>';
            output += '<td>'+grandTotal.Distribution_System+'</td>';
            output += '<td>'+grandTotal.Meter_Sys_Vat+'</td>';
            output += '<td>'+grandTotal.Transmission_Demand+'</td>';
            output += '<td>'+grandTotal.Distribution_Demand+'</td>';
            output += '<td style="text-align:left">'+grandTotal.Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 })+'</td>';
            output += '</tr>';
    output += '</table><br><br><br>';

    output += '<center>';
    output += '<label>Checked By: </label><br><br><br>';
    output += '<table  class = "table" style="text-align:center;width:100%">';
    output += '<tr>';
    output += '<td><p style = "text-decoration:underline">NADJEHA HADJI AIMAN</p></td>';
    output += '<td><p style = "text-decoration:underline">SOHAYA D. MARANGIT</p></td>';
    output += '<td><p style = "text-decoration:underline">NORDJIANA D. DUCOL, DPA</p></td>';
    output += '</tr>';
    output += '</table>';
    output += '<label><<< END OF REPORT >>></label>';
    output += '</center>';
    document.querySelector('#Delq').innerHTML = output;
</script>
</body>
</html>

<!-- web route  -->

