@extends('layout.master')
@section('title', 'Consumer Ledger Details')
@section('content')

<p class="contentheader">Consumer Ledger Inquiry</p>
<div class="main">
       <table border=0 class="EMR-table" style="color:white;height: 100px; width: 97%; margin: auto;">
       <tr>
            <td class="thead" style='width: 17%;'>
                &nbsp;&nbsp; Account Number:
            </td>
            <td  class="input-td">
            <input type="text" style="color:black" id = "accNoID" onclick="showConsumerAcct()" name="account_number" placeholder="Select Account No." readonly>
            </td>
        </tr>
       </table>
</div>
<div id="consLedger" class="modal">
        <div class="modal-content" style="width: 85%;height:750px;">
        <div class="modal-header" style="width: 100%;">
            <h3>Ledger Inquiry</h3>
        <button type="button" class="btn-close" onclick="ledgerClose();"></button>
        </div>
        <div class="modal-body">
                <div style ="height:640px;overflow-y:scroll">
                <hr><div id="ledgerData">
                </div>
                <button type="button" class="btn btn-primary" style="font-size:12px;" onclick="printledger();">Print</button>   
                </div> 
        </div>
        </div>
        </div>
</div>
<div id="aPayment" class="modal">
        <div class="modal-content" style="width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Ewallet Payment</h3>
            <button type="button" class="btn-close" onclick="aPaymentClose();"></button>
        </div>
        <div class="modal-body">
            <div class="modaldiv">
            <div style="border-bottom:1px;overflow-x:hidden;height:200px;width: 100%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
                <table border=1 style="width:100%">
                    <tr>
                        <td class = "thead">
                        Ref. #
                        </td>
                        <td class = "thead">
                        Trans. Amount
                        </td>
                        <td class = "thead">
                        YM
                        </td>
                        <td class = "thead">
                        OR No.
                        </td>
                    </tr>
                    <tbody id = "ewallAd">
                    
                    </tbody>    
                </table>
            </div>
            <hr>
            <label style="font-size:12px;color:black">Total Deposit Ewallet: <p style="font-size:12px;display:inline;color:black" id = "tde"></p></label><br>
            <label style="font-size:12px;color:black">Total Applied Ewallet: <p style="font-size:12px;display:inline;color:black" id = "tae"></p></label>
            </div>
        </div>
    </div>
</div>
<div id="integration" class="modal">
        <div class="modal-content" style="width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Integration</h3>
            <span class="closes" href="#integration">&times;</span>
        </div>
        <div class="modal-body">
            <div class="modaldiv">
                <table border=0 class="EMR-table" style="height: 100px;">
                    <tr>
                        <td class = "thead">
                            Reference #:
                        </td>
                        <td>
                            <input type="text" name="r#" value="0">
                        </td>
                        <td class = "thead">
                            Total Amount:
                        </td>
                        <td>
                            <input type="text" name="r#" value="0">
                        </td>
                    </tr>
                    <tr>
                        <td class = "thead">
                            Date:
                        </td>
                        <td>
                            <input type="date" name="d">
                        </td>
                        <td class = "thead">
                            Number of Months:
                        </td>
                        <td>
                            <input type="text" value="0">
                        </td>
                    </tr>
                    <tr>
                        <td class = "thead">
                            Start Year Month:
                        </td>
                        <td>
                            <input type="month" name="month">
                        </td>
                    </tr>
                    </table>
                    <div style="border-bottom:1px;overflow-x:hidden;height:100px;width: 100%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
                        <table border=1 style="width:100%">
                            <tr>
                                <td class = "thead">
                                    Ref. #
                                </td>
                                <td class = "thead">
                                    Trans. Amount
                                </td>
                                <td class = "thead">
                                    YM
                                </td>
                                <td class = "thead">
                                    Start
                                </td>
                                <td class = "thead">
                                    OR No.
                                </td>
                            </tr>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>
@include('include.modal.consumerAcctModal')
<script>
var cmid;
var ewalletid;
var consumerG;
var auth = "{{Auth::user()->user_full_name}}";
console.log(auth);
/* ------------------------ Account Modal ---------------------------------- */
// var xhr = new XMLHttpRequest();
// var consAccount = "{{route('select.consumer.account')}}";
// xhr.open('GET', consAccount, true);
// xhr.onload = function() {
//     if (this.status == 200) {
//         var data = JSON.parse(this.responseText);
//         var output = " ";
//         var output1 = " ";
//         var val = data.data;
//         var val3 = data.last_page;
//         var val2 = data.current_page;
        
//         output += '<table style="text-align:left;width:100%;" border=1 class="modal-table" id="table1">';
//         output += '<tr>' + '<th>' + 'Account #' + '</th>' + '<th>' + 'Consumer' + '</th></tr>';
//         for (var i in val) {
//             accName = val[i].cm_account_no;
//             var acc = val[i].cm_id;
//             va
//             output += '<tr>' + '<td id = ' + val[i].cm_id + ' onclick="tdclick(' + acc + ')" >' + val[i].cm_account_no + '</td>';
//             output += '<td>' + val[i].cm_full_name + '</td>' + '</tr>';
//         }
//         var b = val2 + 1;

//         if (val2 <= 1) {
//             output1 += '<tr>';
//             output1 += '<td>' + '<button id = "btn" onclick="s(this.value)" value=' + val2 + ' hidden>' + 'Previous' + '</button>';
//         } else {
//             output1 += '<td>' + '<button id = "btn" onclick="s(this.value)" value=' + val2 + '>' + 'Previous' + '</button>';
//         }
//         output1 += '<input style="width:10%;" type="text" class="currentpage" value="' + val2 + '" >';
//         if (val2 < val3) {
//             output1 += '<button id = "btn" onclick="s(this.value)" value=' + b + '  >' + 'Next' + '</button>';
//         } else {
//             output1 += '<button id = "btn" onclick="s(this.value)" value=' + b + ' hidden>' + 'Next' + '</button>';
//         }
//         output += '</td>' + '</tr>' + '</table>';
//         document.querySelector('.modaldiv2').innerHTML = output;
//         document.querySelector('.pages2').innerHTML = output1;
//     }
// }
// xhr.send();
/* ------------------------ End Account Modal ---------------------------------- */
/* -----------------------Pagination for account numbers-------------------------------------------- */
// function s(p) {
//     var xhr = new XMLHttpRequest();
//     var searchAccount = "{{route('select.consumer.account','?page=')}}" + p;
//     xhr.open('GET', searchAccount, true);
//     xhr.onload = function() {
//         if (this.status == 200) {
//             var data = JSON.parse(this.responseText);
//             var output = " ";
//             var output1 = " ";
//             var val = data.data;
//             var val3 = data.last_page;
//             var val2 = data.current_page;
//             output += '<table style="text-align:left;width:100%;" border=1 class="modal-table" id="table1">';
//             output += '<tr>' + '<th>' + 'Account #' + '</th>' + '<th>' + 'Consumer' + '</th></tr>';
//             for (var i in val) {
//                 var acc = val[i].cm_id;
//                 output += '<tr>' + '<td id = ' + val[i].cm_id + ' onclick="tdclick(' + acc + ')" >' + val[i].cm_account_no + '</td>';
//                 output += '<td>' + val[i].cm_full_name + '</tr>';
//             }
//             var b = val2 + 1;
//             var c = val2 - 1;
//             if (val2 <= 1) {
//                 output1 += '<tr>';
//                 output1 += '<td>' + '<button id = "btn" onclick="setTimeout(s(this.value),3000)" value=' + c + ' hidden>' + 'Previous' + '</button>';
//             } else {
//                 output1 += '<td>' + '<button id = "btn" onclick="setTimeout(s(this.value),3000)" value=' + c + '>' + 'Previous' + '</button>';
//             }
//             output1 += '<input style="width:10%;" type="text" class="currentpage" value="' + val2 + '" >';
//             if (val2 >= val3) {
//                 output1 += '<button id = "btn" onclick="setTimeout(s(this.value),3000)" value=' + b + '  hidden>' + 'Next' + '</button>';
//             } else {
//                 output1 += '<button id = "btn" onclick="setTimeout(s(this.value),3000)" value=' + b + ' >' + 'Next' + '</button>';
//             }
//             output += '</td>' + '</tr>' + '</table>';

//             document.querySelector('.modaldiv2').innerHTML = output;
//             document.querySelector('.pages2').innerHTML = output1;
//         }
//         else if(this.status == 422){
//             console.log(2);
//         }
//     }
//     xhr.send();
// }
/* ----------------- ENd Pagination of Account No. --------------------*/

/* ----------------- Search Account Name / Account Number ------------------- */
// function tearch() {
 
//     var input, filter;
//     input = document.getElementById("dearch");
//     filter = input.value.toUpperCase();
//     if (filter.length == 0) {
//         var xhr = new XMLHttpRequest();
//         var consaAccount = "{{route('select.consumer.account')}}";
//         xhr.open('GET', consaAccount, true);
//         xhr.onload = function() {
//             if (this.status == 200) {
//                 var data = JSON.parse(this.responseText);
//                 var output = " ";
//                 var output1 = " ";
//                 var val = data.data;
//                 var val3 = data.last_page;
//                 var val2 = data.current_page;
//                 output += '<table style="text-align:left;width:100%;" border=1 class="modal-table" id="table1">';
//                 output += '<tr>' + '<th>' + 'Account #' + '</th>' + '<th>' + 'Consumer' + '</th></tr>';
//                 for (var i in val) {
//                     var acc = val[i].cm_id;
//                     output += '<tr>' + '<td id = ' + val[i].cm_id + ' onclick="tdclick('+ acc + ')" >' + val[i].cm_account_no + '</td>';
//                     output += '<td>' + val[i].cm_full_name + '</tr>';
//                 }

//                 var b = val2 + 1;

//                 if (val2 <= 1) {
//                     output1 += '<tr>';
//                     output1 += '<td>' + '<button id = "btn" onclick="s(this.value)" value=' + val2 + ' hidden>' + 'Previous' + '</button>';
//                 } else {
//                     output1 += '<td>' + '<button id = "btn" onclick="s(this.value)" value=' + val2 + '>' + 'Previous' + '</button>';
//                 }
//                 output1 += '<input style="width:10%;" type="text" class="currentpage" value="' + val2 + '" >';
//                 if (val2 < val3) {
//                     output1 += '<button id = "btn" onclick="s(this.value)" value=' + b + '  >' + 'Next' + '</button>';
//                 } else {
//                     output1 += '<button id = "btn" onclick="s(this.value)" value=' + b + ' hidden>' + 'Next' + '</button>';
//                 }
//                 output += '</td>' + '</tr>' + '</table>';

//             }
//             document.querySelector('.modaldiv2').innerHTML = output;
//             document.querySelector('.pages2').innerHTML = output1;
//         }
//         xhr.send();
//     } else{
//         var xhr = new XMLHttpRequest();
//         var searchName = "{{route('search.consumer.name.account',['request'=>':req'])}}";
//         searchName = searchName.replace(':req',filter)
//         xhr.open('GET', searchName, true);
//         xhr.onload = function() {
//             if (this.status == 200) {
//                 var data = JSON.parse(this.responseText);
//                 var output = " ";
//                 var val = data;
//                 output += '<div style="overflow:scroll;height:270px;">';
//                 output += '<table style="text-align:left;width:100%;height:20px;" border=1 class="modal-table" id="table1">';
//                 output += '<tr>' + '<th>' + 'Account #' + '</th>' + '<th>' + 'Consumer' + '</th></tr>';
//                 for (var i in val) {
//                     var acc = val[i].cm_id;
//                     output += '<tr>' + '<td id = ' + val[i].cm_id + ' onclick="tdclick(' + acc + ')" >' + val[i].cm_account_no + '</td>';
//                     output += '<td>' + val[i].cm_full_name + '</tr>';
//                 }
//                 output += '</table></div>';
//             }
//             document.querySelector('.modaldiv2').innerHTML = output;

//         }
//         xhr.send();
//     }
// }
/* -----End of Search Account Name and Account Number --------------*/
/*-----------------TDCLICK -----------------------------------------*/
function setConsAcct(consumer){
    localStorage.removeItem('data');
    localStorage.removeItem('auth');
    consumerG = consumer;
    cmid = consumer.id;
    var dis = document.getElementById(consumer.id);
    document.querySelector('#accNoID').value = dis.innerHTML.slice(4,16);
    var modalD = document.querySelectorAll('.modal');
    document.querySelector('#consAcct').style.display = "none";
    modalD[0].style.display="block";
        var xhr = new XMLHttpRequest();
        var showCons = "{{route('show.consumer.ledger',['cmid'=>':id'])}}";
        showCons = showCons.replace(':id',consumer.id);
        xhr.open('GET', showCons, true);
        xhr.onload = function() {
            if (this.status == 200) {
                var data = JSON.parse(this.responseText);
                console.log(data.E_Wallet_Balance.ew_total_amount);
                localStorage.setItem('data', JSON.stringify(data));
                ewalletid = data.Consumer_Ewallet.ewallet_id;
                var pbbills = data.PB_Details;
                var output='';
                console.log(dis.innerHTML);
                output += '<table border=0 class="EMR-table" style="font-family:calibri;font-size:12px;color:black;height: 100px;">';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Account Number:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            '<input type="text" value="'+dis.innerHTML.slice(4,16)+'" style="font-family:calibri;font-size:12px;color:black" readonly>' +
                          '</td>' +
                          '<td class="thead">' +
                           'Type:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            '<input type="text" value="'+data.Consumer_Details[0].Consumer_Type+'" style="font-family:calibri;font-size:12px;color:black" readonly>' +
                          '</td>' +
                         '</tr>';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Name:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            '<input type="text"  value="'+data.Consumer_Details[0].Account_Name+'" style="font-family:calibri;font-size:12px;color:black" readonly>' +
                          '</td>' +
                          '<td class="thead">' +
                           'Status:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            '<input type="text" value="'+data.Consumer_Details[0].Status+'" style="font-family:calibri;font-size:12px;color:black" readonly>' +
                          '</td>' +
                         '</tr>';
                output += '<tr>' +
                          '<td class="thead">' +
                           'Address:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            '<input type="text" value="'+data.Consumer_Details[0].Address+'" style="font-family:calibri;font-size:12px;color:black" readonly>' +
                          '</td>' +
                          '<td class="thead">' +
                           'MN:' +
                           '</td>' +
                            '<td  class="input-td">' +
                            '<input type="text" value="'+data.Consumer_Details[0].Meter_Serial_No+'" style="font-family:calibri;font-size:12px;color:black" readonly>' +
                          '</td>' +
                         '</tr>' +
                         '</table>';
                if(data.Cons_Notify.length > 0){
                    output += '<table>';
                    output += '<tr><td style="font-size:15px;color:red">Note:</td></tr>';
                    for(let x in data.Cons_Notify){
                        output += '<tr><td style="color:red">'+ data.Cons_Notify[x].Remarks +'</td></tr>';
                    }
                    output += '</table>';
                }
                output += '<div style="height:190px;overflow-y:scroll">';
                output += '<table style="font-family:calibri;text-align:left;width:95%;" border=1 class="modal-table" id="table1">';
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
                          '<th style="font-size:12px">Adj. Date</th>' +
                          '<th style="font-size:12px">Adj. KWH Used</th>' +
                          '<th style="font-size:12px">Adj Bill Amt</th>' +
                          '<th style="font-size:12px">Current Bill Bal</th>' +
                          '<th style="font-size:12px">Surchage</th>' +
                         '</tr>';
                for(let i in pbbills){
                    var myr = pbbills[i].mr_date_year_month.toString();
                var d =['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];
                var myr1 = myr.slice(0,4);
                var myr2 = myr.slice(4);
                     if(pbbills[i].Collected_Not_Posted == 'NO'){
                        if(pbbills[i].Adj_KWH_Used == undefined){
                            pbbills[i].Adj_KWH_Used = " ";
                        }
                        output += '<tr style="color:black">' +
                        '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;;font-size:12px" onclick = "spugData('+pbbills[i].mr_id+ ',' +pbbills[i].mr_kwh_used+' );">' + d[parseInt(myr2)] +' '+ myr1 + '</td>' +
                        '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].mr_bill_no + '</td>';
                        // '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].mr_pres_reading + '</td>' +
                        // '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].mr_prev_reading + '</td>' +
                        // '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].mr_kwh_used + '</td>' +
                        if(pbbills[i].overide == "yes"){
                        output +='<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;color:red;font-size:12px">' + pbbills[i].mr_pres_reading + '</td>' +
                        '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;color:red;font-size:12px">' + pbbills[i].mr_prev_reading + '</td>' +
                        '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;color:red;font-size:12px">' + pbbills[i].mr_kwh_used + '</td>';
                        }else{
                        output +='<td style="font-size:12px">' + pbbills[i].mr_pres_reading + '</td>' +
                        '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].mr_prev_reading + '</td>' +
                        '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].mr_kwh_used + '</td>';    
                        }
                        output += '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].mr_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].or_no + '</td>' +
                        '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].or_date + '</td>' +
                        '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].or_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px">' + pbbills[i].Adj_Date + '</td>' +
                        // console.log(pbbills[i].Adj_Date);
                        '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px;font-size:12px">' + pbbills[i].Adj_KWH_Used + '</td>' +
                        '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px;font-size:12px">' + pbbills[i].Adj_Bill_Amt.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px;font-size:12px">' + pbbills[i].Current_Bill_Bal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                        '<td style="border-top:2px solid yellow;border-bottom:2px solid yellow;font-size:12px;font-size:12px">' + pbbills[i].Sur_Charge + '</td>';
                        
                output += '</tr>';
                     }
                     else{
                        if(pbbills[i].Adj_KWH_Used == undefined){
                            pbbills[i].Adj_KWH_Used = " ";
                        }
                        
                            output += '<tr style="color:black">' +
                            '<td style="font-size:12px" onclick = "spugData('+pbbills[i].mr_id+ ',' +pbbills[i].mr_kwh_used+' );">' + d[parseInt(myr2)] +' '+ myr1 + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].mr_bill_no + '</td>';
                            if(pbbills[i].overide == "yes"){
                            output +='<td style="color:red;font-size:12px">' + pbbills[i].mr_pres_reading + '</td>' +
                            '<td style="color:red;font-size:12px">' + pbbills[i].mr_prev_reading + '</td>' +
                            '<td style="color:red;font-size:12px">' + pbbills[i].mr_kwh_used + '</td>';
                            }else{
                            output +='<td style="font-size:12px">' + pbbills[i].mr_pres_reading + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].mr_prev_reading + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].mr_kwh_used + '</td>';    
                            }
                            output += '<td style="font-size:12px">' + pbbills[i].mr_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].or_no + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].or_date + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].or_amount.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].Adj_Date + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].Adj_KWH_Used + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].Adj_Bill_Amt.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].Current_Bill_Bal.toLocaleString("en-US", { minimumFractionDigits: 2 }) + '</td>' +
                            '<td style="font-size:12px">' + pbbills[i].Sur_Charge + '</td>';
                            
                            output += '</tr>';
                     }
                }
                output += '</table>';
                output += '</div>';
                output += '<input style="border:1px solid yellow" type="checkbox" disabled><label style="font-family:calibri;font-size:12px;color:black;display:inline">Collected but not Posted</label>&nbsp' +
                       '<input type="checkbox" disabled><label style="font-family:calibri;font-size:12px;color:black;display:inline">Collected and Posted or Unpaid</label>&nbsp';
                output += '<div class="row">';
                output += '<div class="col-3">';
                output += '<table style="font-family:calibri;font-size:12px;color:black;width:100%;">';
                output += '<tr>' +
                '<td>Meter Deposit:</td>' +
                '<td><input style="border:0px;font-family:calibri;font-size:12px;" id="meterD" type="text"></td>';
                '</tr>';
                output += '<tr>' +
                '<td>Senior Citizen Discount:</td>' +
                '<td><input style="border:0px;font-family:calibri;font-size:12px;" id ="bDate" type="text"></td>';
                '</tr>';
                output += '<tr>' +
                '<td>Bill Date:</td>' +
                '<td><input style="border:0px;font-family:calibri;font-size:12px;" id="sCD" type="text"></td>';
                '</tr>';
                output += '<tr>' +
                '<td>Bill Due Date:</td>' +
                '<td><input style="border:0px;font-family:calibri;font-size:12px;" id="bDDate" type="text"></td>';
                '</tr>';
                output += '</table>';
                output += '</div>';
                output += '<div class="col-6">';
                output += '<table style="font-family:calibri;font-size:12px;color:black;width:100%;">';
                output += '<tr>' +
                '<td>UC-ME SPUG:</td>' +
                '<td><input style="font-family:calibri;font-size:12px;" type="text" id="UC_ME"></td>' +
                '<td>Advance Payment:</td>' +
                '<td><input style="font-family:calibri;font-size:12px;" id="advPayment" type="text"></td>' +
                '</tr>';
                output += '<tr>' +
                '<td>UC-ME RED:</td>' +
                '<td><input style="font-family:calibri;font-size:12px;" id="red" type="text" ></td>' +
                '<td>E-VAT:</td>' +
                '<td><input style="font-family:calibri;font-size:12px;" id="vat" type="text" ></td>' +
                '</tr>';
                output += '<tr>' +
                '<td>UC-EC:</td>' +
                '<td><input style="font-family:calibri;font-size:12px;" id="uc_ec" type="text" ></td>' +
                '<td>Total Unpaid Integ:</td>' +
                '<td><input style="font-family:calibri;font-size:12px;" id="tuInteg" type="text" ></td>' +
                '</tr>';
                output += '<tr>' +
                '<td>UC-NPC SCC:</td>' +
                '<td><input style="font-family:calibri;font-size:12px;" id="scc" type="text" ></td>' +
                '<td>TSF Rental:</td>' +
                '<td><input style="font-family:calibri;font-size:12px;" id="tsfRental" type="text"></td>' +
                '</tr>';
                output += '</table>';
                output += '</div>';
                output += '<div class="col-3">';
                output += '<table style="font-family:calibri;font-size:12px;color:black;width:80%;">';
                output += '<tr>' +
                '<td>Ewallet Balance:</td>' +
                '<td><input style="font-family:calibri;font-size:12px;" type="text" value="'+data.E_Wallet_Balance.ew_total_amount.toLocaleString("en-US", { minimumFractionDigits: 2 })+'"></td>';
                '</tr>';
                output += '<tr>' +
                '<td>Total Unpaid Bills:</td>' +
                '<td><input style="font-family:calibri;font-size:12px;" type="text" value="'+data.Total_Unpaid_Bills.toLocaleString("en-US", { minimumFractionDigits: 2 })+'"></td>';
                '</tr>';
                output += '<tr>' +
                '<td>Reconnection Fee:</td>' +
                '<td><input style="font-family:calibri;font-size:12px;" type="text" value="'+data.Reconnection_FEE.toLocaleString("en-US", { minimumFractionDigits: 2 })+'"></td>';
                '</tr>';
                output += '<tr>' +
                '<td>Collectible:</td>' +
                '<td><input style="font-family:calibri;font-size:12px;" type="text" value="'+data.Collectible+'"></td>';
                '</tr>';
                output += '</table>';
                output += '<div style = "margin-bottom:5px;" ></div>';
                output += '<button onclick="aPayment2()" class = "btn btn-danger" style="width:50%;font-family:calibri;font-size:12px;margin-right:10px;">Ewallet Payment</button>';
                output += '<button onlick="" class = "btn btn-success" style="width:30%;font-family:calibri;font-size:12px">Integration</button>';
                output += '</div>';
                output += '</div>';
                
            }
            document.querySelector('#ledgerData').innerHTML = output;
        }
        xhr.send();
}
function ledgerClose(){
    var modalD = document.querySelectorAll('.modal');
    modalD[0].style.display="none";
}
/*-----------------ENd of TDCLICK-------------------------------------*/
/*--------------------- Spug -----------------------------------------*/
function spugData(mr_id,kwh) {
    var xhr = new XMLHttpRequest();
    var ledgerRates = "{{route('show.consumer.ledger.rates',':id')}}";
    ledgerRates = ledgerRates.replace(':id',mr_id);
    xhr.open('GET', ledgerRates, true);
    xhr.onload = function() {
        if (this.status == 200) {
            var data = JSON.parse(this.responseText);
            console.log(data);
            var data = data.Rates;
            var UC_ME = data[0].UC_ME_SPUG * kwh;
            var advPayment = data[0].Advance_Payment;
            var red = data[0].UC_ME_RED * kwh;
            var vat = data[0].E_VAT;
            var uc_ec = data[0].UC_EC * kwh;
            var tuInteg = data[0].Total_UnPaid_Integ;
            var scc = data[0].UC_NPC_SCC * kwh;
            var tsfRental = data[0].TSF_Rental;
            var meterD = data[0].Meter_Deposit;
            var bDate = data[0].Bill_Date;
            var sCD = data[0].Senior_Citizen_Discount;
            var bDDate = data[0].Bill_Due_Date;
            bDate = bDate.split(" ");
            bDDate = bDDate.split(" ");
            console.log(bDate[0]);
        }
        document.querySelector('#UC_ME').value = parseFloat(UC_ME).toFixed(2);
        document.querySelector('#advPayment').value = parseFloat(advPayment).toFixed(2);
        document.querySelector('#red').value = parseFloat(red).toFixed(2);
        document.querySelector('#vat').value = parseFloat(vat).toFixed(2);
        document.querySelector('#uc_ec').value = parseFloat(uc_ec).toFixed(2);
        document.querySelector('#tuInteg').value = parseFloat(tuInteg).toFixed(2);
        document.querySelector('#scc').value = parseFloat(scc).toFixed(2);
        document.querySelector('#tsfRental').value = parseFloat(tsfRental).toFixed(2);
        document.querySelector('#meterD').value = meterD;
        document.querySelector('#bDate').value = bDate[0];
        document.querySelector('#sCD').value = sCD;
        document.querySelector('#bDDate').value = bDDate[0];
    }
    xhr.send();
}
/*--------------------- end spug -------------------------------------*/
function aPayment2() {
    
    modalD = document.querySelectorAll(".modal");
    modalD[1].style.display="block";
    console.log(ewalletid);
    var xhr = new XMLHttpRequest();
    var ewalletLog = "{{route('get.ewallet',['id'=>':id'])}}";
    ewalletLog = ewalletLog.replace(':id', ewalletid)
    xhr.open('GET', ewalletLog, true);
    xhr.onload = function() {
        if (this.status == 200) {
            var data = JSON.parse(this.responseText);
            console.log(data);
            var wall = data.Ewallet_Log;
            console.log(data);
            var output = " ";
            var total = 0;
            var total1 = 0;
            if(data.additional != 0){
                output += '<tr style="background-color:white;color:black;">'; 
                output += '<td>' + 'EBS' + '</td>' +
                '<td>' + data.additional + '</td>'+
                '<td>' + ' ' + '</td>'+
                // '<td>' + ' ' + '</td>'+
                '<td>' + ' ' + '</td>'+
                '</tr>';
            }
            for (let i in wall) {
                if(wall[i].Status == 'U' || wall[i].Status == 'P'){
                        wall[i].Status = '+';
                    }
                if(wall[i].Status == 'A'){
                        wall[i].Status = "-"; 
                    }
                output += '<tr style="background-color:white;color:black;">' +
                    '<td>' + ' ' + '</td>' +
                    '<td>' + wall[i].Status + wall[i].Trans_Amount + '</td>' +
                    '<td>' + wall[i].Year_Month + '</td>' +
                    // '<td>' + wall[i].Status + '</td>' +
                    '<td>' + wall[i].OR_Num + '</td>' +
                    '</tr>';
                    if(wall[i].Status == '+'){
                    total += parseFloat(wall[i].Trans_Amount);
                    }
                    if(wall[i].Status == '-'){
                        total1 += parseFloat(wall[i].Trans_Amount); 
                    }
            }
            total = parseFloat(total) + parseFloat(data.additional);
            document.querySelector('#tde').innerHTML = total.toLocaleString("en-US", { minimumFractionDigits: 2 });
            document.querySelector('#tae').innerHTML = total1.toLocaleString("en-US", { minimumFractionDigits: 2 });
        }
        document.querySelector('#ewallAd').innerHTML = output;
    }
    xhr.send()

}

function aPaymentClose() {
    modalD = document.querySelectorAll(".modal");
    modalD[1].style.display = "none";
}
function printledger(){
    console.log(auth);
    Swal.fire({
        title: 'Do you want to continue?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
    }).then((result) => {
        if (result.isConfirmed) {
            $url = '{{route("cdinquiry")}}';
            $url1 = '{{route("cdinquiry1")}}';
            if(auth == 'ALI S. DIMALNA' || auth == 'ALI S. DIMALNA 99'){
            console.log(1)
            localStorage.setItem('auth',auth);
            window.open($url1);
            }
            else{
            console.log(2);
            localStorage.setItem('auth',auth); 
            window.open($url);  
            }
            
        setTimeout(function(){
            var consumer = consumerG;
            setConsAcct(consumer);
        },2000);
      }
      else if (result.isDenied) {
            Swal.fire('Cancelled', '', 'info')
        }
    })
}
</script>
@endsection
