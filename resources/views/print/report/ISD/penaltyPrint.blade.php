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
    }
    .page-break {
        page-break-after: always;
        font-weight: bold;
        margin-top: 2%;  
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
        div.divFooter1 {
            position: fixed;
            bottom: 0;   
        }
        #select{
            display:none;
        }
    }

</style>
<body>
<div id = "penalty">


</div>


<script>
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();


    const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];
    const d = new Date();
    let name = month[d.getMonth()];
    let yr = d.getFullYear();
    var data = JSON.parse(localStorage.getItem('data'));
    // console.log(data);
    console.log(data.info);
     var output=""; 
            output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
            output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
            output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
            output += '<label style="font-size: 20px;">' + 'Penalty Report' + '</label><br>';
            output += '<label style="font-size: 20px;">' + name + ' ' + yr +'</label><br></center><br>';
            output += '<table style="font-size:10px;width:100%">';
            output += '<tr>' + 
                    '<th style="text-align:left;">Acct #</th>' +
                    '<th style="text-align:left;">Name </th>' +
                    '<th style="text-align:left;">Amount </th>' +
                    '<th style="text-align:left;">Teller </th>' +
                    '<th style="text-align:left;">Date </th>' +
            '</tr>';
            for(let i in data.info){
                if(i > 0 && i%50 == 0){
                    output += '</table>';
                    output += '<div class="page-break"> </div>';
                    output += '<div style="padding:20px 20px 20px 20px"></div>';
                    output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
                    output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
                    output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
                    output += '<label style="font-size: 20px;">' + 'Consumer Listing' + '</label><br>';
                    output += '<label style="font-size: 20px;">' + name + ' ' + yr +'</label><br></center><br>';
                    output += '<table style="font-size:10px;width:100%">';
                    output += '<tr>' + 
                            '<th style="text-align:left;">Acct #</th>' +
                            '<th style="text-align:left;">Name </th>' +
                            '<th style="text-align:left;">Amount </th>' +
                            '<th style="text-align:left;">Teller </th>' +
                            '<th style="text-align:left;">Date </th>' +
                    '</tr>';
                    output += '<tr>' +
                        '<td>'  +data.info[i].account_no+ '</td>' + 
                        '<td>'  +data.info[i].name + '</td>' + 
                        '<td>'  +data.info[i].amount + '</td>' + 
                        '<td>'  +data.info[i].teller + '</td>' + 
                        '<td>'  +data.info[i].date + '</td>' + 
                    '</tr>';
                }
                else{
                    output += '<tr>' +
                        '<td>'  +data.info[i].account_no+ '</td>' + 
                        '<td>'  +data.info[i].name + '</td>' + 
                        '<td>'  +data.info[i].amount + '</td>' + 
                        '<td>'  +data.info[i].teller + '</td>' + 
                        '<td>'  +data.info[i].date + '</td>' + 
                    '</tr>';
                }
            }

            output += '</table>';
            document.querySelector('#penalty').innerHTML = output;
</script>
</body>
</html>
