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
    <title>Ledger Inquiry</title>
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

    var data = JSON.parse(localStorage.getItem('data'));
    var auth = localStorage.getItem('auth');
    console.log(data);
                var cmDetails = data.Consumer_Details;
                var pbbills = data.PB_Details;
                var wall = data.E_Wallet_Payments;
                var output = " ";
                var output2 = " ";
                var acctNo = data.Consumer_Details[0].Account_No;
                var acct = acctNo.toString();
                    var a = acct.slice(0,2);
                    var b = acct.slice(2,6);
                    var c = acct.slice(6,10);
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
                             a+'-'+b+'-'+c +
                          '</td>' +
                          '<td class="thead">' +
                           'Type:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            data.Consumer_Details[0].Consumer_Type+
                          '</td>' +
                         '</tr>';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Name:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            data.Consumer_Details[0].Account_Name.slice(0,35) +
                          '</td>' +
                          '<td class="thead">' +
                           'Status:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            data.Consumer_Details[0].Status+
                          '</td>' +
                         '</tr>';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Address:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            data.Consumer_Details[0].Address.slice(0,35)+
                          '</td>' +
                          '<td class="thead">' +
                           'MN:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            data.Consumer_Details[0].Meter_Serial_No +
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
                for(let i in pbbills){
                    var myr = pbbills[i].mr_date_year_month.toString();
                var d =['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];
                var myr1 = myr.slice(0,4);
                var myr2 = myr.slice(4);
                    if(i > 0 && i%45 == 0){
                        
                output += '</table>';
                output +='<div class="page-break"></div>';
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
                             a+'-'+b+'-'+c +
                          '</td>' +
                          '<td class="thead">' +
                           'Type:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            data.Consumer_Details[0].Consumer_Type+
                          '</td>' +
                         '</tr>';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Name:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            data.Consumer_Details[0].Account_Name.slice(0,35) +
                          '</td>' +
                          '<td class="thead">' +
                           'Status:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            data.Consumer_Details[0].Status+
                          '</td>' +
                         '</tr>';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Address:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            data.Consumer_Details[0].Address.slice(0,35)+
                          '</td>' +
                          '<td class="thead">' +
                           'MN:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            data.Consumer_Details[0].Meter_Serial_No +
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
                    '<td style="font-size:12px">' + pbbills[i].mr_bill_no + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].mr_pres_reading + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].mr_prev_reading + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].mr_kwh_used + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].mr_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].or_no + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].or_date + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].or_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].Posted + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].E_Wallet_Applied.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].Current_Bill_Bal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].Sur_Charge + '</td>';              
                output += '</tr>';
                }
                else{
                output += '<tr style="color:black">' +
                    '<td style="font-size:12px">' + d[parseInt(myr2)] +' '+ myr1 + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].mr_bill_no + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].mr_pres_reading + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].mr_prev_reading + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].mr_kwh_used + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].mr_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].or_no + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].or_date + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].or_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].Posted + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].E_Wallet_Applied.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].Current_Bill_Bal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td style="font-size:12px">' + pbbills[i].Sur_Charge + '</td>';              
                output += '</tr>';
                    }
                }
                output += '</table>';
                output += '<div class="row">';
                output += '<div class="col-6">';
                output += '<label>Prepared By:&nbsp' + auth + '</label><br>';
                output += '<label>Position:&nbsp </label><br>';
                output += '</div>';
                output += '<div class="col-6" style="text-align:right">';
                output += '<table  style="width:100%">';
                output += '<tr><td>' + 'Ewallet Balance:' +'</td><td>' +data.E_Wallet_Balance.ew_total_amount.toLocaleString("en-US", { minimumFractionDigits: 2 })+'</td></tr>';
                output += '<tr><td>' + 'Total Unpaid:' +'</td><td>' +data.Total_Unpaid_Bills.toLocaleString("en-US", { minimumFractionDigits: 2 })+'</td></tr>';
                output += '<tr><td>' + 'Surchage:' +'</td><td>' +'0.00'+'</td></tr>';
                output += '<tr><td>' + 'Reconnection Fee:' +'</td><td>' +data.Reconnection_FEE+'</td></tr>';
                output += '<tr><td>' + 'Total:' +'</td><td>' +data.Total_Unpaid_Bills.toLocaleString("en-US", { minimumFractionDigits: 2 })+'</td></tr>';
                output += '</table>';
                output += '</div>';
                output += '</div><br>';

            output += '<label>Ewallet Payment Details</label>';
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
                console.log(data.additional)
                if(data.additional != 0){
                output += '<tr style="background-color:white;color:black;">';
                output += '<td>EBS</td>'+
                '<td>'+data.additional+'</td>'+
                '<td> </td>' +
                '<td> </td>';
                output += '</tr>';
                }
                
            for (let i in wall) {
                if(wall[i].ewl_status == 'U' || wall[i].ewl_status == 'P'){
                    wall[i].ewl_status = '+';
                    }
                if(wall[i].ewl_status == 'A'){
                    wall[i].ewl_status = "-"; 
                    }
                if(i > 0 && i%20 == 0){
                output += '</table>';
                output +='<div class="page-break"></div>';
                output +='<center> <label style="font-size: 24px; font-weight: bold">' + 'LANAO DEL SUR ELECTRIC COOPERATIVE, INC.' + '</label><br>';
                output += '<label style="font-size: 18px">' + 'Brgy. Gadongan, Marawi City, Philippines' + '</label><br>';
                output += '<label style="font-size: 15px">' + 'teamlasureco@gmail.com' + '</label><br><br><br>';
                output += '<label style="font-size: 20px;">' + 'Ledger Inquiry' + '</label><br></center>';
                output += '<label style="font-size:17px;"> &nbsp;&nbsp;&nbsp; Runtime: ' + date + " - " + time + '</label> <br><br>';
                output += '<table><tr>' +
                        '<td class="thead">' +
                        'Account Number:' +
                        '</td>' +
                        '<td  class="input-td">' +
                        '<input type="text" value="'+data.Consumer_Details[0].Account_No+'" style="font-family:calibri;font-size:12px;color:black" readonly>' +
                        '</td>' +
                        '<td class="thead">' +
                        'Name:' +
                        '</td>' +
                        '<td  class="input-td">' +
                        '<input type="text" value="'+data.Consumer_Details[0].Account_Name+'" style="font-family:calibri;font-size:12px;color:black" readonly>' +
                        '</td>' +
                        '</tr></table><br>';
                output += '<label>Ewallet Payment Details</label>';
                output +='<table style ="width:60%">';
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
                        '</tr>';
                output += '<tr style="background-color:white;color:black;">' +
                    '<td>' + ' ' + '</td>' +
                    '<td>' + wall[i].ewl_status + wall[i].ewl_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td>' + wall[i].ewl_or + '</td>' +
                    '<td>' + wall[i].ewl_or_date + '</td>' +
                    // '<td>' + wall[i].ewl_status + '</td>' +
                    '</tr>';
                }
                else{
                    output += '<tr style="background-color:white;color:black;">' +
                    '<td>' + ' ' + '</td>' +
                    '<td>' + wall[i].ewl_status + wall[i].ewl_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                    '<td>' + wall[i].ewl_or + '</td>' +
                    '<td>' + wall[i].ewl_or_date + '</td>' +
                    // '<td>' + wall[i].ewl_status + '</td>' +
                    '</tr>';
                }
            }
            // output += '</table>';
            document.querySelector('#tabs').innerHTML = output;
            window.print();

</script>
</body>
</html>