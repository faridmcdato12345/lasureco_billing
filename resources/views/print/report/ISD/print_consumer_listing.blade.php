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
<!-- <div id = "select">
    <select id="consType" style="font-size:20px;width:100%;height:50px;" onchange="consType(this)">
        <option selected disabled>Select Option</option>
        <option value="ALL">ALL</option>
        <option value="RES">RESIDENTIAL</option>
        <option value="COM">COMMERCIAL</option>
        <option value="STL">STREETLIGHTS</option>
        <option value="PUB">PUBLIC BUILDING</option>
        <option value="IND">INDUSTRIAL</option>
        <option value="BAP/MUP">BAPA / MUPA</option>
        <option value="CWS">COMM WATER SYSTEM</option>
        <option value="IRR">IRRIGATION</option>
    </select>
    <select style="font-size:20px;width:100%;height:50px;" onchange=awit(this) id="withMeter">
        <option selected disabled>Select Option</option>
        <option value="1">With Meter</option>
        <option value="2">Without Meter</option>
    </select>

<br>
</div> -->
<div id = "listingCons">


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
    var dListing = data.Consumer_Listing;

    
    
    // function awit(a){
        var output=""; 
        var pageNum = 0;
        if(data.Meter == 1){
            output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
            output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
            output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
            output += '<label style="font-size: 20px;">' + 'Consumer Listing' + '</label><br>';
            output += '<label style="font-size: 20px;">' + name + ' ' + yr +'</label><br></center><br>';
            // output += '<label style="font-size:17px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label>';
            output += '<label style="font-weight:bold;"> AREA CODE:' + data.Area_Code + '</label><br>';
            output += '<label style="font-weight:bold;"> TOWN CODE:' + data.Town_Code + '</label><br>';
            output += '<label style="font-weight:bold;"> ROUTE CODE:' + data.Route_Code + '</label><br><br>';

            output += '<table style="font-size:10px;width:100%">';
            output += '<tr>' + 
                    '<th style="text-align:left;">Acct #</th>' +
                    '<th style="text-align:left;">Name </th>' +
                    '<th style="text-align:left;">Type </th>' +
                    '<th style="text-align:left;">Address </th>' +
                    '<th style="text-align:left;">Status </th>' +
                    '<th style="text-align:left;">Meter No.</th>' +
                    '<th style="text-align:left;">Brand</th>' +
                    '<th style="text-align:left;">KWH Mult</th>' +
                    '<th style="text-align:left;">KW Mult</th>' +
            '</tr>';
            for(let i in dListing ){
                if(dListing[i].Address == null){
                    dListing[i].Address = 'N/A';
                }
                // console.log(dListing[i]);
                if(i > 0 && i%40 == 0){
                    pageNum += 1;
                    output += '</table>';
                    output += '<div class="page-break"> page ' + pageNum + '</div>';
                    output += '<div style="padding:20px 20px 20px 20px"></div>';
                    output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
                    output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
                    output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
                    output += '<label style="font-size: 20px;">' + 'Consumer Listing' + '</label><br>';
                    output += '<label style="font-size: 20px;">' + name + ' ' + yr +'</label><br></center><br>';
                    output += '<label style="font-weight:bold;"> AREA CODE:' + data.Area_Code + '</label><br>';
                    output += '<label style="font-weight:bold;"> TOWN CODE:' + data.Town_Code + '</label><br>';
                    output += '<label style="font-weight:bold;"> ROUTE CODE:' + data.Route_Code + '</label><br><br>';
                    output += '<table style="font-size:10px;width:100%">';
                    output += '<tr>' + 
                            '<th style="text-align:left;" >Acct #</th>' +
                            '<th style="text-align:left;">Name </th>' +
                            '<th style="text-align:left;">Type </th>' +
                            '<th style="text-align:left;">Address </th>' +
                            '<th style="text-align:left;">Status </th>' +
                            '<th style="text-align:left;">Meter No.</th>' +
                            '<th style="text-align:left;">Brand</th>' +
                            '<th style="text-align:left;">KWH Mult</th>' +
                            '<th style="text-align:left;">KW Mult</th>' +
                    '</tr>';

                
                    output += '<tr>' +
                        '<td>' + dListing[i].Account_No + '</td>' +  
                        '<td>' + dListing[i].Name.slice(0,23)+ '</td>' +
                        '<td>' + dListing[i].Type+ '</td>' +
                        '<td>' + dListing[i].Address.slice(0,45)+ '</td>' +
                        '<td style="text-align:left;">' + dListing[i].Status+ '</td>' +
                        '<td>' + dListing[i].Meter_No+ '</td>' +
                        '<td  style="text-align:left;">' + dListing[i].Brand+ '</td>' +
                        '<td  style="text-align:left;">' + dListing[i].KWH_Mult+ '</td>' +
                        '<td  style="text-align:left;">' + dListing[i].KW_Mult+ '</td>' +
                    '</tr>';
                
                }else{
                    output += '<tr>' +
                        '<td>' + dListing[i].Account_No + '</td>' +  
                        '<td>' + dListing[i].Name.slice(0,23)+ '</td>' +
                        '<td>' + dListing[i].Type+ '</td>' +
                        '<td>' + dListing[i].Address.slice(0,45)+ '</td>' +
                        '<td style="text-align:left;">' + dListing[i].Status+ '</td>' +
                        '<td>' + dListing[i].Meter_No+ '</td>' +
                        '<td style="text-align:left;">' + dListing[i].Brand+ '</td>' +
                        '<td style="text-align:left;">' + dListing[i].KWH_Mult+ '</td>' +
                        '<td style="text-align:left;">' + dListing[i].KW_Mult+ '</td>' +
                    '</tr>';
                }
            }

            output += '</table>';
            output += '<table>'; 
            output += '<tr>' +
                '<td>' + 'Total Consumer: ' + '</td>' +
                '<td>' +data.Total_Consumer+ '</td>' +   
            '</tr>';
            output += '</table><br>';
            output += '<div style="font-weight:bold"> page ' + (parseInt(pageNum)+1) + '</div>';
            output += '<div class="divFooter"><label style="font-size:12px;">Runtime: ' + date + " - " + time + '</label></div>';
        }
        else{
            output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
            output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
            output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
            output += '<label style="font-size: 20px;">' + 'Consumer Listing' + '</label><br>';
            output += '<label style="font-size: 20px;">' + name + ' ' + yr +'</label><br></center><br>';
            // output += '<label style="font-size:17px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label>';
            output += '<label style="font-weight:bold;"> AREA CODE:' + data.Area_Code + '</label><br>';
            output += '<label style="font-weight:bold;"> TOWN CODE:' + data.Town_Code + '</label><br>';
            output += '<label style="font-weight:bold;"> ROUTE CODE:' + data.Route_Code + '</label><br><br>';

            output += '<table style="font-size:10px;width:100%">';
            output += '<tr>' + 
                    '<th style="text-align:left;">Acct #</th>' +
                    '<th style="text-align:left;">Name </th>' +
                    '<th style="text-align:left;">Type </th>' +
                    '<th style="text-align:left;">Address </th>' +
            '</tr>';
            for(let i in dListing ){
                if(dListing[i].Address == null){
                    dListing[i].Address = 'N/A';
                }
                console.log(dListing[i]);
                if(i > 0 && i%40 == 0){
                pageNum += 1;
                output += '</table>';
                output += '<div class="page-break"> page ' + pageNum + '</div>';
                output += '<div style="padding:20px 20px 20px 20px"></div>';
                output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
                output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
                output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
                output += '<label style="font-size: 20px;">' + 'Consumer Listing' + '</label><br>';
                output += '<label style="font-size: 20px;">' + name + ' ' + yr +'</label><br></center><br>';
                output += '<label style="font-weight:bold;"> AREA CODE:' + data.Area_Code + '</label><br>';
                output += '<label style="font-weight:bold;"> TOWN CODE:' + data.Town_Code + '</label><br>';
                output += '<label style="font-weight:bold;"> ROUTE CODE:' + data.Route_Code + '</label><br><br>';
                output += '<table style="font-size:10px;width:100%">';
                output += '<tr>' + 
                        '<th style="text-align:left;" >Acct #</th>' +
                        '<th style="text-align:left;">Name </th>' +
                        '<th style="text-align:left;">Type </th>' +
                        '<th style="text-align:left;">Address </th>' +
                '</tr>';
                output += '<tr>' +
                    '<td>' + dListing[i].Account_No + '</td>' +  
                    '<td>' + dListing[i].Name.slice(0,23)+ '</td>' +
                    '<td>' + dListing[i].Type+ '</td>' +
                    '<td>' + dListing[i].Address.slice(0,45)+ '</td>' +
                '</tr>';
                
                }
                else{
                output += '<tr>' +
                    '<td>' + dListing[i].Account_No + '</td>' +  
                    '<td>' + dListing[i].Name.slice(0,23)+ '</td>' +
                    '<td>' + dListing[i].Type+ '</td>' +
                    '<td>' + dListing[i].Address.slice(0,45)+ '</td>' +
                '</tr>';
                }
            }
            output += '</table>';
            output += '<table>'; 
            output += '<tr>' +
                '<td>' + 'Total Consumer: ' + '</td>' +
                '<td>' +data.Total_Consumer+ '</td>' +   
            '</tr>';
            output += '</table><br>';
            output += '<div style="font-weight:bold"> page ' + (parseInt(pageNum)+1) + '</div>';
            output += '<div class="divFooter"><label style="font-size:12px;">Runtime: ' + date + " - " + time + '</label></div>';
        }
        document.querySelector('#listingCons').innerHTML = output;
        window.print();
    // }
    
</script>
</body>
</html>

<!-- web route  -->

