<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vat Per Consumer Type</title>

</head>
<style media="print">
    @page {
      size: A4 landscape;
      margin: 2mm;
    }
</style>
<style>
   body section{
        margin: auto;
        width: 85%;
        text-align: center;
    }
    td{
        padding-bottom: 5px;
    }
</style>
<body onload="getData()">
<center> <label style="font-size: 24px; font-weight: bold">LANAO DEL SUR ELECTRIC COOPERATIVE, INC.</label><br>
<label style="font-size: 18px">Brgy. Gadongan, Marawi City, Philippines</label><br>
<label style="font-size: 15px">teamlasureco@gmail.com</label><br><br><br>
<label style="font-size: 20px;">Vat Per Consumer Type</label><br>
<label style="font-size: 20px;"></label><br>
<label id ="month" style="font-size:20px;"> </label></center><br>
<table style = "width:100%;">
<thead id = "tUlo">

</thead>
<tbody id = "printBody" >
</tbody>
</table>
</body>
</html>
<script>
    function getData(){
                var data = JSON.parse(localStorage.getItem('data'));
                var output = " ";
                var output2 =" ";
                var petsa = JSON.parse(localStorage.getItem('petsa'));
                petsa1 = petsa.slice(5);
                petsa2 = petsa.slice(0,4);
                const monthNames = [" ","January", "February", "March", "April", "May", "June",
                                    "July", "August", "September", "October", "November", "December"
                                    ];
                document.querySelector('#month').innerHTML = "For the Month of" + ' ' + monthNames[parseInt(petsa1)] + ' ' + petsa2;
                output2 += '<tr>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">CONSUMER TYPE</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue"> # OF CONS</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">GENVAT</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">TRANSVAT</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">SYS.LOSSVAT</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">DISTVAT</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">VAT-OTHERS</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">TRANS.DEM.</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">DIST.DEM.</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">LCOND.KWH</th>' + 
                    '<th style = "font-size:12px;border-bottom:2px solid blue">LCOND. FIX</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">POW ACT VAT</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">Supp Sys VAT</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">Meter Fix VAT</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">Meter Sys VAT</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">LFLN. VAT</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">TOTAL AMOUNT</th>' +
                    '<th style = "font-size:12px;border-bottom:2px solid blue">KWH USED</th>' +  
                '</tr>';
               var sec = data.Summary_EVAT_Constype;                
                let x;
                for(let i in sec){
                    output += '<tr><td style="font-weight:bold">' + i + '</td></tr>';
                    // output += '<tr><td></td></tr>';
                    // output += '<tr><td></td></tr>';
                    for(let ii in sec[i]){
                        output += '<tr class="ddd">';
                            output += '<td>' + sec[i][ii][0].Consumer_Type + '</td>'; 
                            output += '<td>' + sec[i][ii][0].Consumer_Count + '</td>';
                            output += '<td>' + sec[i][ii][0].Generation_Vat.toLocaleString("en-US") + '</td>'; 
                            output += '<td>' + sec[i][ii][0].Trans_Vat.toLocaleString("en-US") + '</td>'; 
                            output += '<td>' + sec[i][ii][0].Dist_Vat.toLocaleString("en-US") + '</td>'; 
                            output += '<td>' + sec[i][ii][0].Line_Loss_Vat.toLocaleString("en-US") + '</td>'; 
                            output += '<td>' + sec[i][ii][0].Others_Vat.toLocaleString("en-US") + '</td>'; 
                            output += '<td>' + sec[i][ii][0].Trans_Dem_Vat.toLocaleString("en-US") + '</td>'; 
                            output += '<td>' + sec[i][ii][0].Dist_Dem_Vat.toLocaleString("en-US") + '</td>'; 
                            output += '<td>' + sec[i][ii][0].LCondo_Kwh_Vat.toLocaleString("en-US") + '</td>'; 
                            output += '<td>' + sec[i][ii][0].LCondo_Fix_Vat.toLocaleString("en-US") + '</td>'; 
                            output += '<td>' + sec[i][ii][0].Power_Act_Vat.toLocaleString("en-US") + '</td>'; 
                            output += '<td>' + sec[i][ii][0].Supply_Sys_Vat.toLocaleString("en-US") + '</td>'; 
                            output += '<td>' + sec[i][ii][0].Meter_Fix_Vat.toLocaleString("en-US") + '</td>'; 
                            output += '<td>' + sec[i][ii][0].Meter_Sys_Vat.toLocaleString("en-US") + '</td>'; 
                            output += '<td>' + sec[i][ii][0].LifeLine_Vat.toLocaleString("en-US") + '</td>'; 
                            output += '<td>' + sec[i][ii][0].Total.toLocaleString("en-US") + '</td>'; 
                            output += '<td>' + sec[i][ii][0].KWH_Used.toLocaleString("en-US") + '</td>';
                            output += '</tr>';          
                    }
                    // output += '<tr><td></td></tr>';
                    // output += '<tr><td></td></tr>';
                    output += '<tr><td style="font-weight:bold;">SUBTOTAL</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].Consumer_Count_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].Generation_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].Trans_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].Dist_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].Line_Loss_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].Others_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].Trans_Dem_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].Dist_Dem_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].LCondo_Kwh_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].LCondo_Fix_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].Power_Act_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].Supply_Sys_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].Meter_Fix_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].Meter_Sys_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].LifeLine_Vat_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    output += '<td style="border-bottom:1px dashed;border-top:1px dashed">' + data.Total[i].KWH_Used_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                    
                    output += '</tr>';
                    output += '<tr><td> </td></tr>';
                }
                var gtotal =  data.Grand_Total2;
                output += '<tr><td style="font-weight:bold;border-bottom:1px dashed;">GRAND TOTAL</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.Consumer_Count_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.Generation_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.Trans_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.Dist_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.Line_Loss_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.Others_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.Trans_Dem_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.Dist_Dem_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.LCondo_Kwh_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.LCondo_Fix_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.Power_Act_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.Supply_Sys_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.Meter_Fix_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.Meter_Sys_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.LifeLine_Vat_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="font-weight:bold;border-bottom:1px dashed;">' + data.Grand_Total2.KWH_Used_Grand_Total.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +

                        '</tr>';
                output += '<tr><td> </td></tr>';
                output += '<tr><td> </td></tr>';
                output += '<tr><td > </td></tr>';
            document.querySelector('#tUlo').innerHTML = output2;
            document.querySelector('#printBody').innerHTML = output;
            window.print();
    }
    </script>
