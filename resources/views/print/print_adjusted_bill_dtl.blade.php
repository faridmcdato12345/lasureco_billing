<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Summary Bill Adjustment </title>

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
            height:100px;
            font-size: 10px;
            width: 95%; 
            margin: auto;
        }
        th {
            /* height: 30px; */
            border: 1px solid black;
            /* border-top: 1px solid black; */
        }
        td {
            text-align: center;
            vertical-align:top;
        }
        tr{
            border-bottom: 1px solid black !important;
        }
    </style>

    <body onload="getData()">
        <div id = "printBody"> </div>
    </body>

</html>

<script>
    var dateF = localStorage.getItem("dateF");
    var dateT = localStorage.getItem("dateT");
    var townF = localStorage.getItem("townF");
    var townT = localStorage.getItem("townT");

    const dataSend = {town_from:townF, town_to:townT, date_from:dateF, date_to:dateT};
    
    localStorage.clear();

    function getData(){
        var xhr = new XMLHttpRequest();
        var route = "{{route('report.bill.adjustment.detailed.summary')}}";
        xhr.open('POST',route, true);
        xhr.setRequestHeader('Accept', 'Application/json');
        xhr.setRequestHeader('Content-Type', 'Application/json');
        xhr.send(JSON.stringify(dataSend));

        xhr.onload = function(){
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                console.log(data);
                var output = "";
                var no = 1;
                let date = new Date(dateF);
                let date2 = new Date(dateT);
                var longDate = date.toLocaleString('en-US', {
                    year: 'numeric', // numeric, 2-digit
                    month: 'long', // numeric, 2-digit, long, short, narrow
                    day: 'numeric'
                });
                var longDate2 = date2.toLocaleString('en-US', {
                    year: 'numeric', // numeric, 2-digit
                    month: 'long', // numeric, 2-digit, long, short, narrow
                    day: 'numeric'
                });

                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += '<label style="font-size: 20px;"> SUMMARY BILL ADJUSTMENT-DTL</label><br>';
                output += '<label style="font-size:20px;">' + longDate +" - "+ longDate2 + '</label> </center> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th> No. </th>';
                output += '<th> Account No. </th>';
                output += '<th> Bill Period </th>';
                output += '<th> Bill No. </th>';
                output += '<th> OLD KWH<hr>NEW KWH<hr>DIFF KWH</th>';
                output += '<th> GC-GENSYS <hr> GC-PAR </th>';
                output += '<th> GC-FBH <hr> GC-FOREX </th>';
                output += '<th> TC-TRANSYS <hr> TC-TRANSDEM </th>';
                output += '<th> TC-SYSLOSS <hr> DC-DISTSYS </th>';
                output += '<th> DC-DISTDEM <hr> DC-SUPFIX </th>';
                output += '<th> DC-SUPSYS <hr> DC-METFIX </th>';
                output += '<th> DC-METSYS <hr> OC-LFEDISC </th>';
                output += '<th> OC-LFESUBD <hr> OC-SRDISC </th>';
                output += '<th> OC-SRSUBS <hr> OC-ICCS </th>';
                output += '<th> OC-CAPEX <hr> OC-LOANCOND </th>';
                output += '<th> OC-LCONFIX <hr> UC-ME-SPUG </th>';
                output += '<th> UC-ME-RED <hr> UC-ENVI </th>';
                output += '<th> UC-ETR <hr> UC-NPC-SCC </th>';
                output += '<th> UC-NPC-SD <hr> UC-FIT-ALL </th>';
                output += '<th> VAT <hr> PWR SUBS</th>';

                output += '<th> PPAREFD <hr> PPD </th>';
               
                output += '<th> BILL AMOUNT </th>';
                output += '</tr>';
                
                for(let i in data.Summar_Adjustment_Detail){

                    var oldkwh = data.Summar_Adjustment_Detail[i].OLD_KWH;
                    var newkwh = data.Summar_Adjustment_Detail[i].NEW_KWH;
                    var diffkwh = oldkwh-newkwh;
                    var amount = 0;
                    var temp = 0;

                    output +="<tr>"+
                        "<td>"+(no++)+"</td>"+
                        "<td>"+data.Summar_Adjustment_Detail[i].ACCOUNT_NO +"</td>"+
                        "<td>"+data.Summar_Adjustment_Detail[i].YEAR_MONTH +"</td>"+
                        "<td>"+data.Summar_Adjustment_Detail[i].BILL_NUMBER +"</td>"+
                        "<td>"+data.Summar_Adjustment_Detail[i].OLD_KWH+" <br> "+data.Summar_Adjustment_Detail[i].NEW_KWH+" <br> "+ data.Summar_Adjustment_Detail[i].DIFF_KWH+"</td>"+
                        "<td>"+(oldkwh*data.Summar_Adjustment_Detail[i].GEN_SYS).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].GEN_SYS).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].GEN_SYS).toFixed(2)+"<br><hr>"+
                               (oldkwh*data.Summar_Adjustment_Detail[i].PAR).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].PAR).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].PAR).toFixed(2)+"<br>"+
                        "</td>"+
                        "<td>"+(oldkwh*data.Summar_Adjustment_Detail[i].GEN_FRA).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].GEN_FRA).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].GEN_FRA).toFixed(2)+"<br><hr>"+
                               (oldkwh*data.Summar_Adjustment_Detail[i].GEN_FRX).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].GEN_FRX).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].GEN_FRX).toFixed(2)+"<br>"+
                        "</td>"+
                        "<td>"+(oldkwh*data.Summar_Adjustment_Detail[i].TRANS_SYS).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].TRANS_SYS).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].TRANS_SYS).toFixed(2)+"<br><hr>"+
                               (oldkwh*data.Summar_Adjustment_Detail[i].TRANS_DEM).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].TRANS_DEM).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].TRANS_DEM).toFixed(2)+"<br>"+
                        "</td>"+
                        "<td>"+(oldkwh*data.Summar_Adjustment_Detail[i].LINELOSS).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].LINELOSS).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].LINELOSS).toFixed(2)+"<br><hr>"+
                               (oldkwh*data.Summar_Adjustment_Detail[i].DIST_SYS).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].DIST_SYS).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].DIST_SYS).toFixed(2)+"<br>"+
                        "</td>"+
                        "<td>"+(oldkwh*data.Summar_Adjustment_Detail[i].DIST_DEM).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].DIST_DEM).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].DIST_DEM).toFixed(2)+"<br><hr>"+
                               (oldkwh*data.Summar_Adjustment_Detail[i].SUP_FIX).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].SUP_FIX).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].SUP_FIX).toFixed(2)+"<br>"+
                        "</td>"+
                        "<td>"+(oldkwh*data.Summar_Adjustment_Detail[i].SUP_SYS).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].SUP_SYS).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].SUP_SYS).toFixed(2)+"<br><hr>"+
                               (data.Summar_Adjustment_Detail[i].METER_FIX).toFixed(2)+"<br>"+
                               (data.Summar_Adjustment_Detail[i].METER_FIX).toFixed(2)+"<br>"+
                               "-<br>"+
                        "</td>"+
                        "<td>"+(oldkwh*data.Summar_Adjustment_Detail[i].METER_SYS).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].METER_SYS).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].METER_SYS).toFixed(2)+"<br><hr>"+
                               (oldkwh*data.Summar_Adjustment_Detail[i].LIFE_DISC).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].LIFE_DISC).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].LIFE_DISC).toFixed(2)+"<br>"+
                        "</td>"+
                        "<td>"+(oldkwh*data.Summar_Adjustment_Detail[i].LIFE_SUBD).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].LIFE_SUBD).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].LIFE_SUBD).toFixed(2)+"<br><hr>"+
                               (oldkwh*data.Summar_Adjustment_Detail[i].SR_DISC).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].SR_DISC).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].SR_DISC).toFixed(2)+"<br>"+
                        "</td>"+
                        "<td>"+(oldkwh*data.Summar_Adjustment_Detail[i].SR_SUBS).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].SR_SUBS).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].SR_SUBS).toFixed(2)+"<br><hr>"+
                               (oldkwh*data.Summar_Adjustment_Detail[i].ICCS).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].ICCS).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].ICCS).toFixed(2)+"<br>"+
                        "</td>"+
                        "<td>"+(oldkwh*data.Summar_Adjustment_Detail[i].CAPEX).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].CAPEX).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].CAPEX).toFixed(2)+"<br><hr>"+
                               (oldkwh*data.Summar_Adjustment_Detail[i].LOANCOND).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].LOANCOND).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].LOANCOND).toFixed(2)+"<br>"+
                        "</td>"+
                        "<td>"+(data.Summar_Adjustment_Detail[i].LCONFIX).toFixed(2)+"<br>"+
                               (data.Summar_Adjustment_Detail[i].LCONFIX).toFixed(2)+"<br>"+
                               "-<br><hr>"+
                               (oldkwh*data.Summar_Adjustment_Detail[i].UCME_SPUG).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].UCME_SPUG).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].UCME_SPUG).toFixed(2)+"<br>"+
                        "</td>"+
                        "<td>"+(oldkwh*data.Summar_Adjustment_Detail[i].UCME_RED).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].UCME_RED).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].UCME_RED).toFixed(2)+"<br><hr>"+
                               (oldkwh*data.Summar_Adjustment_Detail[i].ENVI).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].ENVI).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].ENVI).toFixed(2)+"<br>"+
                        "</td>"+
                        "<td>"+(oldkwh*data.Summar_Adjustment_Detail[i].ETR).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].ETR).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].ETR).toFixed(2)+"<br><hr>"+
                               (oldkwh*data.Summar_Adjustment_Detail[i].NPC_SCC).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].NPC_SCC).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].NPC_SCC).toFixed(2)+"<br>"+
                        "</td>"+
                        "<td>"+(oldkwh*data.Summar_Adjustment_Detail[i].NPC_SD).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].NPC_SD).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].NPC_SD).toFixed(2)+"<br><hr>"+
                               (oldkwh*data.Summar_Adjustment_Detail[i].FITALL).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].FITALL).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].FITALL).toFixed(2)+"<br>"+
                        "</td>"+
                        "<td>"+(oldkwh*data.Summar_Adjustment_Detail[i].VAT_ALL).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].VAT_ALL).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].VAT_ALL).toFixed(2)+"<br><hr>"+
                               (oldkwh*data.Summar_Adjustment_Detail[i].PWR_SUBS).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].PWR_SUBS).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].PWR_SUBS).toFixed(2)+"<br>"+
                        "</td>"+
                        "<td>"+(oldkwh*data.Summar_Adjustment_Detail[i].PPAREFD).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].PPAREFD).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].PPAREFD).toFixed(2)+"<br><hr>"+
                               (oldkwh*data.Summar_Adjustment_Detail[i].PPD).toFixed(2)+"<br>"+
                               (newkwh*data.Summar_Adjustment_Detail[i].PPD).toFixed(2)+"<br>"+
                               (diffkwh*data.Summar_Adjustment_Detail[i].PPD).toFixed(2)+"<br>"+
                        "</td>"+
                        "<td>"+(data.Summar_Adjustment_Detail[i].BILL_AMOUNT).toFixed(2)+"<br>"+
                               (data.Summar_Adjustment_Detail[i].BILL_AMOUNT).toFixed(2)+"<br>"+
                               (data.Summar_Adjustment_Detail[i].BILL_AMOUNT).toFixed(2)+"<br>"+
                        "</td>"+
                    "</tr>";
                }
                output += "</table>"
                document.querySelector('#printBody').innerHTML = output;
            }
            else if(xhr.status == 422){
                alert('No Consumer Found');
                window.close();
            }
        }
    }
</script>