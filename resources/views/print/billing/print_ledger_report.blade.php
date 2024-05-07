<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('/img/logo.png')}}">
    <link rel="stylesheet" href="{{asset('css/twitter-bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/buttons.bootstrap.min.css')}}">
    <title>Ledger Report</title>
</head>
<style>
@media print 
{
    @page {
      size: 8.5in 13in;
      margin:0.2in;
    }
    @page :footer {color: #fff }
    @page :header {color: #fff}
}
body{
    font-family:calibri;
    font-size:14px;
    padding:20px;
}
.page-break {
    page-break-after: always;
}
body section{
    width: 85%;
}
</style>
<body>
<div id = "tabs">

</div>
<script>
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();
    var dats = JSON.parse(localStorage.getItem('dataR'));
    var output = '';
    var j=0;
    var a=0;
    for(let i in dats.info){
        
        var wall = dats.info[i].ewallet_payment_details;
        output += '<label style="margin-left:85%;position:absolute;margin-top:120%;">PAGE:' + parseInt(j) + '</label>';
        output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
                output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
                output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
                output += '<label style="font-size: 20px;">' + 'Ledger Inquiry' + '</label><br></center>';
                output += '<label style="font-size:17px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += '<table  style="margin-left:1px;width:70%;font-family:calibri;font-size:14px;color:black;">';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Account Number:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].account_no+
                          '</td>' +
                          '<td class="thead">' +
                           'Type:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].cons_type+
                          '</td>' +
                         '</tr>';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Name:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].name.slice(0,35) +
                          '</td>' +
                          '<td class="thead">' +
                           'Status:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].status+
                          '</td>' +
                         '</tr>';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Address:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].address.slice(0,35)+
                          '</td>' +
                          '<td class="thead">' +
                           'MN:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].meter +
                          '</td>' +
                         '</tr>' +
                         '</table>';
                output += '<table style="border-bottom:1px solid black;width:100%;font-family:calibri;font-size:14px;color:black;">';
                output += '<tr style="color:black">' +
                          '<th style="font-size:12px">Yr/Mo</th>' +
                          '<th style="font-size:12px">Bill No.</th>' +
                          '<th style="font-size:12px">Pres R</th>' +
                          '<th style="font-size:12px">Prev R</th>' +
                          '<th style="font-size:12px"> KwH Used</th>' +
                          '<th style="font-size:12px">Bill Amount</th>' +
                          '<th style="font-size:12px">OR No</th>' +
                          '<th style="font-size:12px">OR Date</th>' +
                          '<th style="font-size:12px">OR Amount</th>' +
                          '<th style="font-size:12px">Posted</th>' +
                          '<th style="font-size:12px">Ewallet Applied</th>' +
                          '<th style="font-size:12px">Current Bill Bal</th>' +
                          '<th style="font-size:12px">Surchage</th>' +
                         '</tr>';
        for(let y in dats.info[i].pb_details){
            
            var pbbills = dats.info[i].pb_details[y];
            if(pbbills.or_no == null && pbbills.or_date == null){
              pbbills.or_no = '';
              pbbills.or_date = '';
              
            }
            else{
              pbbills.current_bill_bal = '';
            }
            var myr = pbbills.year_month.toString();
                var d =['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];
                var myr1 = myr.slice(0,4);
                var myr2 = myr.slice(4);
                    if(i > 0 && i%45 == 0){
                        
                output += '</table>';
                output +='<div class="page-break"></div>';
                output += '<label style="margin-left:85%;position:absolute;margin-top:120%;">PAGE:' + parseInt(j) + '</label>';
                output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
                output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
                output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
                output += '<label style="font-size: 20px;">' + 'Ledger Inquiry' + '</label><br></center>';
                output += '<label style="font-size:17px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += '<table  style="margin-left:1px;width:70%;font-family:calibri;font-size:14px;color:black;">';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Account Number:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].account_no+
                          '</td>' +
                          '<td class="thead">' +
                           'Type:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].cons_type+
                          '</td>' +
                         '</tr>';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Name:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].name.slice(0,35) +
                          '</td>' +
                          '<td class="thead">' +
                           'Status:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].status+
                          '</td>' +
                         '</tr>';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Address:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].address.slice(0,35)+
                          '</td>' +
                          '<td class="thead">' +
                           'MN:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].meter +
                          '</td>' +
                         '</tr>' +
                         '</table>';
                output += '<table style="border-bottom:1px solid black;width:100%;font-family:calibri;font-size:14px;color:black;">';
                output += '<tr style="color:black">' +
                          '<th style="font-size:12px">Yr/Mo</th>' +
                          '<th style="font-size:12px">Bill No.</th>' +
                          '<th style="font-size:12px">Pres R</th>' +
                          '<th style="font-size:12px">Prev R</th>' +
                          '<th style="font-size:12px"> KwH Used</th>' +
                          '<th style="font-size:12px">Bill Amount</th>' +
                          '<th style="font-size:12px">OR No</th>' +
                          '<th style="font-size:12px">OR Date</th>' +
                          '<th style="font-size:12px">OR Amount</th>' +
                          '<th style="font-size:12px">Posted</th>' +
                          '<th style="font-size:12px">Ewallet Applied</th>' +
                          '<th style="font-size:12px">Current Bill Bal</th>' +
                          '<th style="font-size:12px">Surchage</th>' +
                         '</tr>';                
                output += '<tr style="color:black">' +
                    '<td style="font-size:12px">' +d[parseInt(myr2)] +' '+ myr1 +  '</td>' +
                    '<td style="font-size:12px">' + pbbills.bill_no + '</td>' +
                    '<td style="font-size:12px">' + pbbills.pres_reading + '</td>' +
                    '<td style="font-size:12px">' + pbbills.prev_reading + '</td>' +
                    '<td style="font-size:12px">' + pbbills.kwh_used + '</td>' +
                    '<td style="font-size:12px">' + pbbills.bill_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td style="font-size:12px">' + pbbills.or_no + '</td>' +
                    '<td style="font-size:12px">' + pbbills.or_date + '</td>' +
                    '<td style="font-size:12px">' + pbbills.or_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td style="font-size:12px">' + pbbills.posted + '</td>' +
                    '<td style="font-size:12px">' + pbbills.ewallet_applied + '</td>' +
                    '<td style="font-size:12px">' + pbbills.current_bill_bal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td style="font-size:12px">' + pbbills.sur_charge + '</td>';              
                output += '</tr>';
                }
                else{
                output += '<tr style="color:black">' +
                    '<td style="font-size:12px">' +d[parseInt(myr2)] +' '+ myr1 +  '</td>' +
                    '<td style="font-size:12px">' + pbbills.bill_no + '</td>' +
                    '<td style="font-size:12px">' + pbbills.pres_reading + '</td>' +
                    '<td style="font-size:12px">' + pbbills.prev_reading + '</td>' +
                    '<td style="font-size:12px">' + pbbills.kwh_used + '</td>' +
                    '<td style="font-size:12px">' + pbbills.bill_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td style="font-size:12px">' + pbbills.or_no + '</td>' +
                    '<td style="font-size:12px">' + pbbills.or_date + '</td>' +
                    '<td style="font-size:12px">' + pbbills.or_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td style="font-size:12px">' + pbbills.posted + '</td>' +
                    '<td style="font-size:12px">' + pbbills.ewallet_applied + '</td>' +
                    '<td style="font-size:12px">' + pbbills.current_bill_bal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td style="font-size:12px">' + pbbills.sur_charge + '</td>';                
                output += '</tr>';
                    }
                }
                output += '</table>';
                output += '<div class="row">';
                output += '<div class="col-6">';
                output += '<label>Prepared By:&nbsp' + ' ' + '</label><br>';
                output += '<label>Position:&nbsp </label><br>';
                output += '</div>';
                output += '<div class="col-6" style="text-align:right">';
                output += '<table  style="width:100%">';
                output += '<tr><td>' + 'Ewallet Balance:' +'</td><td>' +dats.info[i].ewallet_balance.toLocaleString("en-US", { minimumFractionDigits: 2 })+'</td></tr>';
                output += '<tr><td>' + 'Total Unpaid:' +'</td><td>' +dats.info[i].total_unpaid.toLocaleString("en-US", { minimumFractionDigits: 2 })+'</td></tr>';
                output += '<tr><td>' + 'Surchage:' +'</td><td>' +dats.info[i].surcharge+'</td></tr>';
                output += '<tr><td>' + 'Reconnection Fee:' +'</td><td>' +dats.info[i].reconnection_fee+'</td></tr>';
                output += '<tr><td>' + 'Total:' +'</td><td>' +dats.info[i].total.toLocaleString("en-US", { minimumFractionDigits: 2 })+'</td></tr>';
                output += '</table>';
                output += '</div>';
                output += '</div><br>';
            output +='<div class="page-break"></div>';
            output += '<label style="margin-left:85%;position:absolute;margin-top:120%;">PAGE:' + parseInt(j) + '-B' + '</label>';
            output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
                output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
                output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
                output += '<label style="font-size: 20px;">' + 'Ledger Inquiry' + '</label><br></center>';
                output += '<label style="font-size:17px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += '<table  style="margin-left:1px;width:70%;font-family:calibri;font-size:14px;color:black;">';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Account Number:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].account_no+
                          '</td>' +
                          '<td class="thead">' +
                           'Type:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].cons_type+
                          '</td>' +
                         '</tr>';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Name:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].name.slice(0,35) +
                          '</td>' +
                          '<td class="thead">' +
                           'Status:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].status+
                          '</td>' +
                         '</tr>';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Address:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].address.slice(0,35)+
                          '</td>' +
                          '<td class="thead">' +
                           'MN:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            dats.info[i].meter +
                          '</td>' +
                         '</tr>' +
                         '</table>';
            output += '<label>Ewallet Payment Details</label>';
            // output +='<div class="page-break"></div>';
            output +='<table contenteditable = "true" style ="width:60%">';
            output += '<tr>' +
                        '<th>' +
                        'Ref. #' +
                        '</th>' +
                        '<th>' +
                        'E-wallet Payment' +
                        '</th>' +
                        '<th>' +
                        'OR No.' +
                        '</th>' +
                        '<th>' +
                        'OR Date' +
                        '</th>' +
                        // '<th>' +
                        // 'Status' +
                        // '</th>' +
                    '</tr>';    
                for(let z in wall){
                    if(wall[z].ewl_status == 'U' || wall[z].ewl_status == 'P'){
                    wall[z].ewl_status = '+';
                    }
                if(wall[z].ewl_status == 'A'){
                    wall[z].ewl_status = "-"; 
                    }
                output += '<tr style="background-color:white;color:black;">' +
                    '<td>' + ' ' + '</td>' +
                    '<td>' + wall[z].ewl_status + wall[z].ewl_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td>' + wall[z].ewl_or + '</td>' +
                    '<td>' + wall[z].ewl_or_date + '</td>' +
                    // '<td>' + wall[i].ewl_status + '</td>' +
                    '</tr>';
                }
                output += '</table>';
                output +='<div class="page-break"></div>';  
                ++j;
                   
        }
        document.querySelector('#tabs').innerHTML = output;
</script>
</body>
</html>
