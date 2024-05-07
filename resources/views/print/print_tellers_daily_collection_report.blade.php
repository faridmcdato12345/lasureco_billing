<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title> Tellers Daily Collection Report </title>

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
        font-family: Consolas;
        font-size: 14px;
    }
    table {
        /* margin:auto; */
        /* height:auto; */
        /* font-size: 17px; */
        width: 95%; 
        margin: auto;
    }
    #recapTable {
        /* font-size: 17px; */
        margin: auto;
    }
    th {
        /* height: 60px; */
        color: black;
        border-bottom: 1px solid black;
    }
    td {
        text-align: center;
        border-bottom: 1px solid gray;
    }
    #rcvTbl td {
        border-bottom: none !important;
    }
</style>

<body onload="getData()">
    <div id = "printBody"> </div>
</div>
</body>
</html>

<script>
    var toSend = new Object();
    var param = JSON.parse(localStorage.getItem("data"));
    var userid = param.teller_id;
    var billPeriod = param.date;
    var cutOff = param.cutOff;
    var teller = param.teller;
    var today = new Date();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var date = (today.getMonth()+1) + "/" + today.getDate() + "/" + today.getFullYear();

    function getData(){
        var xhr = new XMLHttpRequest();
        
        toSend.user_id = userid;
        toSend.date = billPeriod;
        toSend.cutoff = cutOff;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var route = "{{route('print.collection.teller.dcr')}}";
        xhr.open('POST', route, true);
        // xhr.open('POST', 'http://10.12.10.100:8082/api/v1/collections/teller/print/dcr');
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var data = JSON.parse(this.responseText);
                var details = data.Details;
                var totalAmount = data.Total_Amount;
                var totalBills = data.Totals;
                var recap = data.Recap;
                var cheque = data.Cheque;
                var void_detais = data.Void_detais;

                var output = "";
                var count = 0;
                
                output += '<label style="font-size:10px; float: right;"> Runtime: ' + date + " - " + time + '</label> <br>';
                output += '<center> <label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                output += "<label style='font-size: 20px;'> TELLER'S DAILY COLLECTION(UNPOSTED)</label><br>";
                output += '<label style="font-size:20px;">' + billPeriod + '</label> </center>';
                output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Teller/Collector: ' + teller + '</label> <br><br>';
                output += '<table id="table"><tr>';
                output += '<th> Account No. </th>';
                output += '<th> Name </th>';
                output += '<th> OR No </th>';
                output += '<th> Payment Type </th>';
                output += '<th> Year Month </th>';
                output += '<th> Amount </th>';
                output += '<th> E-Wallet Applied </th>';
                output += '<th> Total Paid </th>';
                output += '</tr>';
                
                for(var i in details){
                    var dcr = details[i];
                    var kimar = "";
                    var grandTotal = 0;

                    for(var x in dcr){
                        var paymentType = dcr[x];
                        
                        count += 1;

                        if(count > 0 && count % 34 == 0){
                            var toSplit = paymentType.Account_No;
                            const array = toSplit.split("@");
                            output += '</table>';
                            output +='<div class="page-break"></div>';
                            output += '<label style="font-size:10px; float: right;"> Runtime: ' + date + " - " + time + '</label>';
                            output += '<center> <br><br><label style="font-size: 24px; font-weight: bold"> LANAO DEL SUR ELECTRIC COOPERATIVE, INC. </label><br>';
                            output += '<label style="font-size: 18px"> Brgy. Gadongan, Marawi City, Philippines </label><br>';
                            output += '<label style="font-size: 15px"> teamlasureco@gmail.com </label><br><br><br>';
                            output += '<label style="font-size: 20px;"> SUMMARY OF UNPOSTED COLLECTION </label>';
                            output += '<label style="font-size:20px;">' + billPeriod + '</label> </center> <br>';
                            output += '<label style="font-size:18px;"> &nbsp;&nbsp;&nbsp; Teller/Collector: ' + teller + '</label> <br><br>';
                            output += '<table id="table"><tr>';
                            output += '<th> Account No. </th>';
                            output += '<th> Name </th>';
                            output += '<th> OR No </th>';
                            output += '<th> Payment Type </th>';
                            output += '<th> Year Month </th>';
                            output += '<th> Amount </th>';
                            output += '<th> E-Wallet Applied </th>';
                            output += '<th> Total Paid </th>';
                            output += '<tr>';

                            var toSplit = paymentType.Account_No;
                            const arr = toSplit.split("@");
                            if(toSplit != kimar){
                                output += "<td>" + arr[0] + "</td>";
                                output += "<td style='font-size:9px'>" + arr[1].slice(0,8) + "</td>";
                                output += "<td>" + arr[2] + "</td>";
                                
                                if(paymentType.Payment_Desc == null){
                                    output += '<td> E - Wallet Credit </td>';
                                } else {
                                    if(paymentType.Payment_Type !== "E-Wallet Deposit") {
                                        output += '<td>' + paymentType.Payment_Desc +" "+paymentType.Payment_Type + '</td>';
                                    } else {
                                        output += '<td>' + paymentType.Payment_Desc +' E-Deposit</td>';
                                    }
                                }
                                
                                output += '<td>' + (paymentType.Year_Month == null || paymentType.Year_Month == undefined ? '-' : paymentType.Year_Month) + '</td>';
                                output += '<td>' + paymentType.Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                                output += '<td>' + paymentType.EWallet_Applied.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                                output += '<td>' + totalAmount[toSplit].Total_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                                output += '</tr>';
                            } else {
                                output += "<td> </td>";
                                output += "<td> </td>";
                                output += "<td> </td>";
                                
                                if(paymentType.Payment_Desc == null){
                                    output += '<td> E - Wallet Credit </td>';
                                } else {
                                    if(paymentType.Payment_Type !== "E-Wallet Deposit") {
                                        output += '<td>' + paymentType.Payment_Desc +" "+paymentType.Payment_Type + '</td>';
                                    } else {
                                        output += '<td>' + paymentType.Payment_Desc +' E-Deposit</td>';
                                    }
                                }

                                output += '<td>' + (paymentType.Year_Month == null || paymentType.Year_Month == undefined ? '-' : paymentType.Year_Month) + '</td>';
                                output += '<td>' + paymentType.Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                                output += '<td>' + paymentType.EWallet_Applied.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                                output += '</tr>';
                            }
                            kimar = toSplit;
                        }
                        else{
                            var toSplit = paymentType.Account_No;
                            const array = toSplit.split("@");
                            output += '<tr>';
                            
                            if(toSplit != kimar){
                                output += "<td>" + array[0] + "</td>";
                                output += "<td style='font-size:10px;'>" + array[1].slice(0,8) + "</td>";
                                output += "<td>" + array[2] + "</td>";
                                
                                if(paymentType.Payment_Desc == null){
                                    output += '<td> E - Wallet Credit </td>';
                                } else {
                                    if(paymentType.Payment_Type !== "E-Wallet Deposit") {
                                        output += '<td>' + paymentType.Payment_Desc +" "+paymentType.Payment_Type + '</td>';
                                    } else {
                                        output += '<td>' + paymentType.Payment_Desc +' E-Deposit</td>';
                                    }
                                }

                                output += '<td>' + (paymentType.Year_Month == null || paymentType.Year_Month == undefined ? '-' : paymentType.Year_Month) + '</td>';
                                
                                if(paymentType.Amount != null) {
                                    output += '<td>' + paymentType.Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                                } else {
                                    output += '<td> 0.00 </td>';
                                }
                                
                                output += '<td>' + paymentType.EWallet_Applied.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                                output += '<td>' + totalAmount[toSplit].Total_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                                output += '</tr>';
                            } else {
                                output += "<td> </td>";
                                output += "<td> </td>";
                                output += "<td> </td>";
                                
                                if(paymentType.Payment_Desc == null){
                                    output += '<td> E - Wallet Credit (PB) </td>';
                                } else {
                                    if(paymentType.Payment_Type !== "E-Wallet Deposit") {
                                        output += '<td>' + paymentType.Payment_Desc +" "+paymentType.Payment_Type + '</td>';
                                    } else {
                                        output += '<td>' + paymentType.Payment_Desc +' E-Deposit</td>';
                                    }
                                }

                                output += '<td>' + (paymentType.Year_Month == null || paymentType.Year_Month == undefined ? '-' : paymentType.Year_Month) + '</td>';
                                
                                if(paymentType.Amount != null) {
                                    output += '<td>' + paymentType.Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                                } else {
                                    output += '<td> 0.00 </td>';
                                }
                                
                                output += '<td>' + paymentType.EWallet_Applied.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>';
                                output += '</tr>';
                            }
                            kimar = toSplit;
                        }
                    }
                }

                for(var a in totalAmount){
                    grandTotal += parseFloat(totalAmount[a].Total_Amount);
                }

                
                output += "</table><br><br><br>";
                output +='<div class="page-break"></div> <br> <br> <br>';
                output += "<table id='table'> <tr> <td style='font-weight: bold;'> Total Bills </td>";
                output += "<td>" +  totalBills.Total_Bills + "</td>";
                output += "<td style='font-weight: bold;'> Receipts </td> <td>" +  totalBills.Total_Receipt + "</td>";
                output += "<td style='font-weight: bold;'> Total Collections </td> <td>" + grandTotal.toLocaleString("en-US", { minimumFractionDigits: 2 })  + "</td> <tr> </table>";
                output += "<br><br><br>";
                output += "<table id='recapTable'> <tr> <th> Payment Group </th>";
                output += "<th> Involved OR </th>";
                output += "<th> Amount </td> </th>";

                
                if(recap["E-Wallet_Deposit"] !== undefined) {
                    if(recap["E-Wallet_Deposit"].Involved_OR != 0) {
                        output += "<tr> <td> E-Deposit </td>"; 
                        output += "<td>" + recap["E-Wallet_Deposit"].Involved_OR + "</td>"; 
                        output += "<td>" + recap["E-Wallet_Deposit"].Total_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>"; 
                    }
                }
                if(recap["ARREARS"] !== undefined){
                    output += "<tr> <td> Arrears </td>"; 
                    output += "<td>" + recap["ARREARS"].Involved_OR + "</td>"; 
                    output += "<td>" + recap["ARREARS"].Total_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td> </tr>"; 
                } 
                if(recap["CURRENT"] !== undefined) {
                    output += "<td> Current </td>"; 
                    output += "<td>" + recap["CURRENT"].Involved_OR + "</td>"; 
                    output += "<td>" + recap["CURRENT"].Total_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td></tr>"; 
                } 
                if(recap["Non_Bill"] !== undefined) {
                    output += "<td> Non-Bill </td>"; 
                    output += "<td>" + recap["Non_Bill"].Involved_OR + "</td>"; 
                    output += "<td>" + recap["Non_Bill"].Total_Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td></tr>"; 
                } 

                output += "</table><br><br><br>";
                if(cheque !== undefined){
                    output += "<table id='table' class='mt-4'>";
                    output += "<tr><th colspan='5'>CHECK DETAILS</th></tr><tr>";
                    for(let i in cheque){
                        output += "<td>" + cheque[i].Account_No + "</td>";
                        output += "<td>" + cheque[i].Cheque_Bank + "</td>";
                        output += "<td>" + cheque[i].OR + "</td>";
                        output += "<td>" + cheque[i].teller + "</td>";
                        output += "<td>" + cheque[i].Amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "</td></tr>";
                    }
                    output += "</table>";
                }
                output += "<br><br>";

                output += "<table id='rcvTbl'>";
                output += "<tr><td colspan='3' style='float: left;'> &nbsp;&nbsp; Checked By: </td> </tr>";
                output += "<tr> <td height='50px;'> &nbsp; </td> </tr>";
                output += "<tr> <td> ___________________ </td>";
                output += "<td> ________________________ </td>";
                output += "<td> ___________________ </td> </tr>";
                output += "<tr> <td> TELLER </td> ";
                output += "<td> RECEIVING CASHIER </td> ";
                output += "<td> AUDIT </td>";
                output += "</tr></table>";

                document.querySelector('#printBody').innerHTML = output;
				window.print();
            }
            else if(xhr.status == 422){
                alert('No Consumer Found');
                window.close();
            }
        }
    }
</script>