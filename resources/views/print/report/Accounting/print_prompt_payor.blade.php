<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    body{
        padding: 20px 20px 20px 20px;
        font-size:12px;
    }
    .page-break {
        page-break-after: always;
    }
    @media print{
        @page{
            size:letter landscape;
            margin:0;
        }
    }

</style>
<body>
<div id = "Delq">


</div>

<script>
    var name = "{{Auth::user()->user_full_name}}";
    var j=0;
    var data = JSON.parse(localStorage.getItem('data'));
    var dats = data.Top_Prompt_Payors
    var info = JSON.parse(localStorage.getItem('info'));
    console.log(dats);
    output = '';
    const month = ["","January","February","March","April","May","June","July","August","September","October","November","December"];
    var dateyr = info.date_period.slice(0,4);
    var dateday= info.date_period.slice(5,7);
    output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
    output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
    output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
    output += '<label style="font-size: 20px;">' + 'TOP ' + info.top + ' Prompt Payors' + '</label><br>';
    output += '<label style="font-size: 20px;">' + month[parseInt(dateday)] + ',' + ' ' + dateyr + '</label><br></center><br>';

    output += '<table style="width:100%">' +
            '<tr>' +
                '<th>No.</th>' +
                '<th style="text-align:left">Account#</th>' +
                '<th style="text-align:left">Name</th>' +
                '<th style="text-align:left">Consumer Type</th>' +
                '<th style="text-align:left">Area</th>' +
                '<th style="text-align:left">Date Paid</th>' +
                '<th style="text-align:right">Total Amount</th>' +
            '</tr>';
    for(let i in dats){
        const month = ["","January","February","March","April","May","June","July","August","September","October","November","December"];
        var dateyr = info.date_period.slice(0,4);
        var dateday= info.date_period.slice(5,7);
        j++;
        if(i> 0 && i%30 == 0){
            output += '</table>';
            output +='<div class="page-break"></div>';
            output +='<div style="padding-top:20px;padding-left:10px;padding-right:10px"></div>';
            output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
            output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
            output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
            output += '<label style="font-size: 20px;">' + 'TOP ' + info.top + ' Prompt Payors' + '</label><br>';
            output += '<label style="font-size: 20px;">' + month[parseInt(dateday)] + ',' + ' ' + dateyr + '</label><br></center><br>';

            output += '<table style="width:100%">' +
                '<tr>' +
                    '<th>No.</th>' +
                    '<th style="text-align:left">Account#</th>' +
                    '<th style="text-align:left">Name</th>' +
                    '<th style="text-align:left">Consumer Type</th>' +
                    '<th style="text-align:left">Area</th>' +
                    '<th style="text-align:left">Date Paid</th>' +
                    '<th style="text-align:right">Total Amount</th>' +
                '</tr>';
            output += '<tr>';
            output += '<td>'+(parseInt(j))+'</td>';
            output += '<td>'+dats[i].Customer_Account_No+'</td>';
            output += '<td>'+dats[i].Name_Of_Customer+'</td>';
            output += '<td>'+dats[i].Type_Of_Customer+'</td>';
            output += '<td>'+dats[i].Customer_Area+'</td>';
            const m = ["","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec"];
            var dateyr1 = dats[i].Date_Paid.slice(0,4);
            var datemn = dats[i].Date_Paid.slice(5,7);
            var datem = m[parseInt(datemn)];
            var dateday = dats[i].Date_Paid.slice(8,10);
            output += '<td>'+datem + ' ' + dateday + ', '  +dateyr1+'</td>';
            output += '<td style="text-align:right">'+dats[i].Amount.toLocaleString("en-US", { minimumFractionDigits: 2 })+'</td>';
            output += '</tr>';
        }
        else{
            output += '<tr>';
            output += '<td>'+(parseInt(j))+'</td>';
            output += '<td>'+dats[i].Customer_Account_No+'</td>';
            output += '<td>'+dats[i].Name_Of_Customer+'</td>';
            output += '<td>'+dats[i].Type_Of_Customer+'</td>';
            output += '<td>'+dats[i].Customer_Area+'</td>';
            const m = ["","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec"];
            var dateyr1 = dats[i].Date_Paid.slice(0,4);
            var datemn = dats[i].Date_Paid.slice(5,7);
            console.log(datemn);
            var datem = m[parseInt(datemn)];
            var dateday = dats[i].Date_Paid.slice(8,10);
            output += '<td>'+datem + ' ' + dateday + ', '  +dateyr1+'</td>';
            output += '<td style="text-align:right">'+dats[i].Amount.toLocaleString("en-US", { minimumFractionDigits: 2 })+'</td>';
            output += '</tr>';    
        }
    }
    output += '</table><br>';
    
    output += '<table>' +
        '<tr>' +
            '<td>Prepared:' + ' ' + name  +  '</td>'; 
        '</tr>'
    output += '</table>';
    document.querySelector('#Delq').innerHTML = output;
    window.print();
</script>
</body>
</html>

<!-- web route  -->

