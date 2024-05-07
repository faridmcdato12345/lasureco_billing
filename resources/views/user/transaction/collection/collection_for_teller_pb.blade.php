@extends('Layout.master')
@section('title', 'Collection Teller - PB')
@section('content')
<style>
.blink {
animation: blink-animation 1s steps(5, start) infinite;
-webkit-animation: blink-animation 1s steps(5, start) infinite;
}
@keyframes blink-animation {
to {
    visibility: hidden;
}
}
@-webkit-keyframes blink-animation {
to {
    visibility: hidden;
}
}
body{
    background-color: white !important;
}
label{
    font: size 12px;
    font-family: calibri;
}
.contentA {
display: flex;
flex:1;
color: #000;
}
.contentB {
display: flex;
flex:1;
color: #000;
}
.contentC {
display: flex;
flex: 5;
color: white;
margin-left:100px;
}
.contentD {
  display: flex;
  flex: 1;
  color: #000;
}
.col2{
    display: flex;
  flex: 1;
  color: #000;
}
.col1{
    display: flex;
  flex: 1;
  color: #000;
}
input[type=number] {
    -moz-appearance:textfield;
    -webkit-appearance: none;
    appearance: textfield;
}
@media screen and (max-width: 500px){
    .contentA{
    	display:block;
    }
    table{
    	margin-bottom:10px;
    }
    .contentC{
        margin-left:0;
    }
}
@media screen and (max-width: 1366px){
    .modal-content{
        height:450px;
    }
    button{
        font-size:12px;
        font-family:calibri;
    }
}

</style>
<p class="contentheader">Collection Teller - PB </p>
<p id = "tellid" style="display:none">{{Auth::user()->user_id}}</p>
<div class="main" id="main">
    <table border=0 class="EMR-table" style="color:white;height: 100px;">
       <tr style="height: 45px;">
            <td style="color:yellow;" class="thead">
                &nbsp; TOR No.:
            </td>
            <td>
                <input style="color:red;width:97%;text-align:left" id="torVal" type = "text" readonly>
            </td>
            <td><button onclick="addToConsent()" class="btn btn-success" id="addTC" style="width:40%;" disabled>Consent</button></td>
            <td>
                <input style="color:black;width: 95%;" id="nobtninput" type = "text" class ="datePicker">
            </td>
       </tr>
        <tr style="height: 45px;">

            <td class="thead">
                &nbsp; Account No:
            </td>
            <td>
                <input type="text" style="color:black" id = "accNoID" onclick="showConsumerAcct()" name="account_number" placeholder="Select Account No." readonly>
            </td>
            <td>
                &nbsp; Status:
            </td>
            <td>
                <input style="color:black;width: 95%;" class = "status" id="nobtninput" name="readingDate" type="text" >
            </td>
        </tr>
        <tr style="height: 45px;">
            <td class="rightTxtTD">
                &nbsp; Address:
            </td>
            <td>
                <input style="color:black;" type="text" name="book" class = "address" id="nobtninput">
            </td>
            <td>
                &nbsp; MN:
            </td>
            <td>
                <input style="color:black;width: 95%;" type="text" id = "MN" class="input-Txt" >
            </td>
        </tr>
        <tr style="height: 45px;">
            <td>
                &nbsp; Type:
            </td>
            <td>
                <input style="color:black" id="nobtninput" class = "TypeC" type="text">
            </td>
            <td>
                <label style="color:white;"><input id="aaaa" onchange="aaa()" type="checkbox">
                Disable Print?
                </label>
            </td>
        </tr>
    </table>
            <div style="font-family:calibri;font-size:12px;overflow-x:hidden;height:250px;width: 100%; margin-left: auto; margin-right: auto; color: white; background-color: white;">
            <table class="table" style="width:100%">
                <thead>
                    <tr style="border-bottom:1px solid white;">
                        <th>Type</th>
                        <th>Description</th>
                        <th>KWH Used</th>
                        <th>Bill Balance</th>
                        <th>Surchage</th>
                        <th>LGU 2%</th>
                        <th>LGU 5%</th>
                        <th>BAPA Disc</th>
                        <th>Senior Disc</th>
                        <th>Partial</th>
                        <th>Amount Due</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="dataCons">

                </tbody>
            </table>
        </div>
        <table style="color:white;margin-top:10px;width:70%">
            <tr>
                <th>Disco Date:</th>
                <td><input class = "form-control" type="text" style="color:black;" id = "discoID" readonly></td>
                <th >Due Date:</th>
                <td><input class = "form-control" type="text" style="color:black;" id = "dueID" readonly></td>
                {{-- <td><button style="font-size:12px" class="btn btn-primary ml-1"  onclick="end()">end session</button></td> --}}
                <td><button style="font-size:12px" class="btn btn-primary ml-1"  onclick="consLedger()">Ledger Inquiry</button></td>
<!-- feb 1,22 --><td><button style="font-size:12px" class="btn btn-primary ml-1"  onclick="createConsumer()">Create Consumer</button></td>
                <td><button style="font-size:12px" class="btn btn-primary ml-1"  onclick="consentList()">Consent List</button></td> 
            </tr>
        </table>
        <hr style="border: 1px solid white;">
    <!-- <div style="margin-top:5px;width:80%;" class="contentA"> -->
        <!-- <div  class="contentB" style="border:1px solid red"> -->
                <div class="row" style="width:100%">
                <div class="col-3">
                    <label style="color:white">Total Arrears:</label>
                    <input  class = "form-control"  id = "totalArrears" type="text" placeholder="0.00" readonly></td>
                    <label style="color:white;">Amount to be Paid:</label>
                    <input class = "form-control"  id = "aP" type="number" lang="en-US" placeholder="0.00" readonly>
                    <label style="color:red;">Cash:</label>
                    <input class = "form-control"  onfocusout="cashInput();" type="number" formatter="currency" step="0.01" id="cash" placeholder="0.00" required>
                    <label id = "needcash"></label>
                </div>
                    <div class = "col-3">
                        <label class="ml-4" style="color:red;" id = "toHide"><input class="form-check-input"  id = "ewalletTo" onchange="ewall()" type="checkbox">
                        Use E-Wallet 
                        </label>
                        <input  class = "form-control" id="ewalletPay" placeholder="0.00" type="text" readonly>
                        <label style="color:white">E-Wallet Balance:</label>
                        <input class = "form-control"  type="text" id = "e-walletB" placeholder="0.00" readonly>
                        <label class="ml-4" style="color:white" >
                            <input class="form-check-input" id ="eLet" onchange = "eLet11()" type="checkbox">
                            Dept. Change to E-wallet
                        </label>
                        <input  class = "form-control" type="number" onchange="changeCh()" id = "change"  placeholder="0.00">      
                        <label id="e-walletCre" style="display:none;color:white">E-Wallet Credit:</label>
                        <input class = "form-control"  style="display:none" type="text" id = "e-walletCredit" placeholder="0.00" readonly>
                    </div>
                    <div class="col-2">
                        <label class="mt-2">Total Collection:<input id = "tCollection" class="form-control" type="text" placeholder="0.00" readonly></label>
                        <label >OR Count Used:<input id="orCount" class="form-control" type="number" readonly></label>
                        <label >Void Count:<input id="voidCount" class="form-control" type="number" readonly></label>
                    </div>
                    <div class="mt-4 col-4 notifySyt" style ="height:185px;opacity: 0.9;border-radius:15px 15px">
                    
                    <label class="labelR mt-2" style="display:none"><span style="color:red"class="blink">Note:</span></label>
                        <div id="notify">
                            <table class="notifyI">
                                
                            </table>
                        </div>
                    </div>
                </div>
        <!-- </div> -->
    <!-- </div> -->
    <hr style="border:1px solid white;"><br>
    <div class = "row" style="margin-top:-2.2%;">
    <div class="col">
        <button  class = "chequeDisabled form-control"  onclick="cPayment()">Cheque Payment</button>
    </div>
    <div class="col" id="save">
        <button type="submit" class="collectionV form-control" onclick="data_send()">Print OR</button>
    </div>
    <div class="col">
        <button   class="form-control" onclick="vCollection()">Void OR</button>
    </div>
    <div class="col">
        <button  class="modal-button form-control" href="#nbill">
         Non-Bill
        </button>
    </div>
    <div class="col">
        <button class="form-control" onclick="cListoftheDay()">Collection of the Day</button>
    </div>
    <div class="col">
        <button onclick = "ewalletPayOnly()" id="ewallPayDeposit" class="form-control">E-wallet Deposit</button>
    </div>
</div>


<div id="CListfortheDay" class="modal">
        <div class="modal-content" style="width: 80%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Collection List for the Day</h3>
            <button type="button" class="btn-close" onclick="cListoftheDayClose();"></button>
        </div>
        <div class="modal-body">
            <div class="cDayBody">

            </div>
        </div>
    </div>
</div>

<div id="cpayment" class="modal">
    <div class="modal-content" style="width: 50%;height:425px">
    <div class="modal-header" style="width: 100%;">
        <h3>Cheque Payment</h3>
        <button type="button" class="btn-close" onclick="cpaymentClose();"></button>
    </div>
    <div class="modal-body" style="color: black">
            {{-- <form> --}}
                <table class = "EMR-table" style="height: 250px;">
                    <tr>
                        <td class ="thead">Cheque No.</td>
                        <td><input type="text" id = "chequeNo" name= "chequeNo" autocomplete="chequeNo"></td>
                        <td class = "thead">Bank</td>
                        <td><input type="text" id = "bank" name= "bank" autocomplete="bank"></td>
                    </tr>
                    <tr>
                        <td class ="thead">Bank Account No.</td>
                        <td><input type="text" id = "bankAccNo" name="bankAccNo" autocomplete="bankAccNo"></td>
                        <td class = "thead">Account Name</td>
                        <td><input type="text" id = "bankAccName" name="bankAccName" autocomplete="bankAccName"></td>
                    </tr>
                    <tr>
                        <td class ="thead">Cheque Date</td>
                        <td><input type="date" id = "chequeDate" ></td>
                        <td class = "thead">Cash</td>
                        <td><input type="number" id = "cashAmount" onfocusout = "cashStep()" step="0.01" placeholder="0.00"></td>
                    </tr>
                    <tr>
                        <td class = "thead">Amount</td>
                        <td><input type="text" id = "chequeAmount" onfocusout="chequeSend()"></td>
                        <td>
                            <label  style="color:red;"><input id="useECheque" onchange="ewallCheque()" type="checkbox">
                            Use E-wallet
                            
                            </label>
                        </td>
                        <td>
                        <input type="text" id="chequeA" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold"><label id = "ewalletBal" for=""></label></td>
                    </tr>
                </table>
            {{-- </form> --}}
                <hr>
                <table style = "float:right;">
                    <tr>
                        <td><button style="float:right"  class="chequePrint btn btn-primary" onclick="sendCheque()">Print OR</button></td>
                    </tr> 
                </table>
            </div> 
    </div>
</div>

<div id="nbill" class="modal">
    <div class="modal-content" style="width: 50%;height:450px">
    <div class="modal-header" style="width: 100%;">
        <h3>Non Bill</h3>
        <span href="#nbill" class="closes">&times;</span>
    </div>
    <div class="modal-body" style="color: black;width:100%;height:600px">
    
                <div style="overflow-x:hidden;height:300px;width: 100%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
                <div class="filter"><input type="text" style="width:100%" id="nonbillsearch" onkeyup="nonbillsearch();" placeholder="Search for Description..">
                    <table  id="nonBillSearch" style="width: 100%;">
                            <tr style="border-bottom:1px solid black;">
                                <td>
                                    No.
                                </td>
                                <td>
                                    Fee Code
                                </td>
                                <td>
                                    Desc
                                </td>
                                <td>
                                    Amount
                                </td>
                                <td>
                                    Vat
                                </td>
                                <td>
                                    Action
                                </td>
                            </tr>
                            <tbody id = "dataNonBill">

                            </tbody>
                        </table>
                </div></div>
                <hr>
            </div>
    </div>
</div>

<div id="consLedger" class="modal">
        <div class="modal-content" style="width: 85%;height:650px;">
        <div class="modal-header" style="width: 100%;">
            <h3>Ledger Inquiry</h3>
        <button type="button" class="btn-close" onclick="ledgerClose();"></button>
        </div>
        <div class="modal-body">
                <div style ="height:540px;overflow-y:scroll">
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
                        <!-- <td class = "thead">
                        Start
                        </td> -->
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
                                <!-- <td class = "thead">
                                    Start
                                </td> -->
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
<div id="voidCollection" class="modal">
        <div class="modal-content" style="width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>VOID OR</h3>
        <button type="button" class="btn-close" onclick="vCollectionClose();"></button>
        </div>
        <div class="modal-body">
            <div class="voidDiv">
            <div style="border-bottom:1px;overflow-x:scroll;height:350px;width: 100%; margin-left: auto; margin-right: auto; color: white;">
            <table style = "color:#000;text-align:left;width:100%;" border=1 class="modal-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Account Number</th>
                        <th>OR Number</th>
                        <th>Payee</th>
                        <th>Total Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id = "voidDataCol">

                </tbody>
            </table>
            </div>
            </div>
        </div>
    </div>
</div>
<div id="voidRemarks" class="modal">
        <div class="modal-content" style="width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>VOID OR</h3>
        <button type="button" class="btn-close" onclick="voidRemarksClose();"></button>
        </div>
        <div class="modal-body">
            <div class="voidRemarkDiv">
            <div class="form-group">
                <form onsubmit = "return voidedData(event)">
                    <label style = "color:black;font-weight:bold" for="textar">Remarks</label>
                    <textarea class="form-control" id="textar" rows="3" placeholder="..." required></textarea>
                    <button id= "disableto" type="submit" class="btn-primary" style="height:30px;margin-top:1%;width:50px;float:right">Void</button>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
<div id="torModal" class="modal">
        <div class="modal-content" style="height:190px;width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Enter TOR</h3>
        <button type="button" class="btn-close" onclick="torModalClose();"></button>
        </div>
        <div class="modal-body">
            <div class="voidRemarkDiv">
            <form onsubmit="return torSend(event)">
            <div class="form-group">
                <input style="margin:auto;color:black" class="form-control" type = "text" onkeypress="return onlyNumberKey(event)" minlength="7" maxlength="7" id = "torNo" required>
                <button  type="submit" id="torbutton" class="btn btn-primary mt-1" style="float:right">Enter</button>
            </div>
            </form>
            </div>
        </div>
    </div>
</div>
<div id="ewalletPayOnly" class="modal">
        <div class="modal-content" style="height:250px;width: 40%;">
        <div class="modal-header" style="width: 100%;">
            <h3>E-wallet Deposit</h3>
            <button type="button" class="btn-close" onclick="ewalletPayOnlyClose();"></button>
        </div>
        <div class="modal-body">
            <div class="ePayBody">
            <div class="container" style="margin:auto;width:80%;">
            <form onsubmit="return ewayDep(event)">
                <label style="color:black">E-wallet to Deposit:</label>
                <input class="form-control" type="number" step="0.01" id = "ed" min="100" placeholder="0.00" required>
                <button type="submit" class="btn btn-primary mt-2" style="float:right">Deposit</button>
            </form>
            </div>
            </div>
        </div>
    </div>
</div>
<div id="consentList" class="modal">
        <div class="modal-content" style="width: 80%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Consent List</h3>
            <button type="button" class="btn-close" onclick="consentListClose();"></button>
        </div>
        <div class="modal-body">
            <div class="consentListBody">

            </div>
        </div>
    </div>
</div>
<!-- <div id="selectOR" class="modal">
    <div class="modal-content" style="width: 85%;height:215px;">
    <div class="modal-header" style="width: 100%;">
        <h3>Select OR</h3>
    <button type="button" class="btn-close" onclick="selectORClose();"></button>
    </div>
        <div class="modal-body">
                <select style="color:black" class="form-control" id="selectOption">
                <option value = "oldOR">Old OR</option>
                <option value = "newOR">New OR</option>
                </select><br>
                <button class="form-control btn btn-primary " onclick="printOptionOR()">submit</button>
        </div>
    </div>
</div> -->
<!-- <div id="chequeSelectOR" class="modal">
    <div class="modal-content" style="width: 85%;height:215px;">
    <div class="modal-header" style="width: 100%;">
        <h3>Select OR</h3>
    <button type="button" class="btn-close" onclick="chequeSelectORClose();"></button>
    </div>
        <div class="modal-body">
                <select style="color:black" class="form-control" id="selectOption2">
                <option value = "oldOR">Old OR</option>
                <option value = "newOR">New OR</option>
                </select><br>
                <button class="form-control btn btn-primary " onclick="printOptionOR2()">submit</button>
        </div>
    </div>
</div> -->
<!-- <div id="ewalletSelectOR" class="modal">
    <div class="modal-content" style="width: 85%;height:215px;">
    <div class="modal-header" style="width: 100%;">
        <h3>Select OR</h3>
    <button type="button" class="btn-close" onclick="ewalletSelectORClose();"></button>
    </div>
        <div class="modal-body">
                <select style="color:black" class="form-control" id="selectOption3">
                <option value = "oldOR">Old OR</option>
                <option value = "newOR">New OR</option>
                </select><br>
                <button class="form-control btn btn-primary " onclick="printOptionOR3()">submit</button>
        </div>
    </div>
</div> -->
<!-- <script src="{{asset('js/api_collection.js')}}"></script> -->
@include('include.modal.consumerAcctModal')
@include('include.script.api_collection')
@include('include.modal.consumer')
@include('include.script.consumer')
<script>
    var tor;
    var sec_num;
    var name = "{{Auth::user()->user_id}}";
    var user_id = "{{Auth::user()->user_id}}";

    var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        var lastTwoDigits = yyyy % 100;
        
    function formatId(user_id) {
    
    if (user_id < 100) {
        user_id = String(user_id).padStart(3, '0');
    }
    return user_id;
    }
    function formatId2(seq_num) {
    
    if (seq_num < 1000) {
        sec_num = String(sec_num).padStart(4, '0');
    }
    return sec_num;
    }
    function checkLastOR(){
        var route = "{{route('check.latest.OR',['id'=>':id'])}}"
        var route2 = route.replace(':id',user_id);
        xhr.open('GET', route2, true);
        xhr.onload = function() {
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
            
                var data2= parseInt(data.data)
                sec_num = data2 + 1;
                var dats = formatId2(sec_num);
                // console.log(dats);
                var tr1 = formatId(user_id) + '-' + mm + dd + lastTwoDigits + '-' +dats;
                // var str = tr1;
                // var numericPart = str.split('-').pop();
                // var numericValue = parseInt(numericPart, 10);
                // console.log(numericValue);
                            
                tr1 = sessionStorage.setItem('TOR', tr1)
                var test = sessionStorage.getItem('TOR');
                document.querySelector('#torVal').value = test
            }
            else if(this.status == 404){
                var data = JSON.parse(this.responseText);
                // var str = '041-200324-0016';
                // var result = str.replace(/-/g, '');
                // console.log(result);
                tr1 = sessionStorage.setItem('TOR', data.data)
                var test = sessionStorage.getItem('TOR');
                document.querySelector('#torVal').value = test
            }
        }
        xhr.send();
    }
    window.onload = function() {
        
        checkLastOR();
        setTimeout(torModal, 300);
    };
    function torModal(){
        var xhr = new XMLHttpRequest();
            var ctotalroute = "{{route('show.collection.per.teller',['id'=>':par'])}}";  
            xhr.open('GET', ctotalroute.replace(':par', name), true); 
            xhr.send();
            xhr.onload = function() {
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                console.log(data);
                if(data.Total_Collection != 0){
                    document.querySelector('#tCollection').value = data.Total_Collection.toLocaleString("en-US", { minimumFractionDigits: 2 });
                }
                else{
                    document.querySelector('#tCollection').value = '';
                    document.querySelector('#tCollection').placeholder = '0.00';
                }
                if(data.Void_count != 0){
                    document.querySelector('#voidCount').value = data.Void_count;
                }
                else{
                    document.querySelector('#voidCount').value = '';
                }
                document.querySelector('#orCount').value = parseInt(data.OR_No_Used) + parseInt(data.Void_count);
                    }
                } 
    }
    function onlyNumberKey(evt) {
          // Only ASCII character in that range allowed
          var ASCIICode = (evt.which) ? evt.which : evt.keyCode
          if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true; 
      }
    function end(){
        sessionStorage.clear();
        location.reload();
    }
    
    function nonbillsearch(){
    var input, filter, table, tr, td, i, txtValue,td2;
    input = document.getElementById("nonbillsearch");
    filter = input.value.toUpperCase();
    table = document.getElementById("nonBillSearch");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
    // td = tr[i].getElementsByTagName("td")[1];
    // console.log(td);
    td = tr[i].getElementsByTagName("td")[2];
        if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    console.log(1);
                tr[i].style.display = "";
                } else {
                    console.log(2);
                tr[i].style.display = "none";
                }
            }
        }
    }

    function ewalletPayOnly(){
        delete accounts.change;
        var change = document.querySelector('#change');
        change.value = '';
        change.placeholder = '0.00';
        var cash = document.querySelector('#cash');
        cash.value = '';
        cash.placeholder = '0.00';
        if(document.querySelector('.collectionV1') != null){
        document.querySelector('.collectionV1').disabled = true;
        }
        if(document.querySelector('.collectionV') != null){
            document.querySelector('.collectionV').disabled = true;
        }
        modalD = document.querySelectorAll('.modal');
        console.log(modalD);
        document.querySelector('#ewalletPayOnly').style.display="block";
    }
    function ewalletPayOnlyClose(){
        modalD = document.querySelectorAll('.modal');
        modalD[8].style.display="none";
        document.querySelector('#ewalletPayOnly').style.display="none";
    }
    function ewayDep(event){
        event.preventDefault();
        Swal.fire({
        title: 'Do you want to continue?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        }).then((result) => {
        if (result.isConfirmed) {
        consumer = accName2;
        modalD = document.querySelectorAll('.modal');
        modalD[8].style.display="none"; 
        datasendED.cons_type_id = vonsumer.ct_id;
        datasendED.cons_id = consumer.id;
        var ornum1 = sessionStorage.getItem('TOR');
            var ornum2 = ornum1.replace(/-/g, '');
            // console.log(ornum2);
            // tor.or_no = ;
        datasendED.or_amount =  parseFloat(document.querySelector('#ed').value);
        datasendED.or_num = ornum2;
        datasendED.user_id = parseInt(name);
        console.log(datasendED);
        var xhr = new XMLHttpRequest();
        var depostEwall = "{{route('deposit.ewallet')}}";
            xhr.open('POST', depostEwall, true);
            xhr.setRequestHeader("Accept", "application/json");
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onload = function() {
                if(this.status == 200){
                    
                    var data2=JSON.parse(this.responseText);
                    var res = new Object();
                    var date_paid = data2.Date_Paid;
                    var ta = data2.Total_Arrears_Amount; 
                    res.Date_Paid = date_paid;
                    res.Total_Arrears = ta;
                    // tor1 = parseInt(sessionStorage.getItem('TOR')) + 1;
                    // sessionStorage.setItem("TOR",tor1);
                    checkLastOR();
                    torModal();
                    // Kimar start
                    // let url = "{{route('ewalletOR')}}";
                    let url = '{{route("newEwalletOR")}}';
                    // Kimar end
                    localStorage.setItem('res', JSON.stringify(res));
                    localStorage.setItem('accountInfo', JSON.stringify(accounts));
                    localStorage.setItem('data',JSON.stringify(datasendED));
                    window.open(url);
                }
                else if(this.status == 422){
                    // tor1 = parseInt(sessionStorage.getItem('TOR'));
                    // sessionStorage.setItem("TOR",tor1);
                    checkLastOR();
                    torModal();
                    console.log(2);
                }
            }
            xhr.send(JSON.stringify(datasendED));
            document.querySelector('#ed').value = '';
            document.querySelector('#ed').placeholder = '0.00';
            setConsAcct(consumer);
            }
        })
        return false; 
    }
    // feb 2, 2022
    function createConsumer(){
        document.querySelector('#createConsumer').style.display="block";
        document.querySelector('#fordesign').style.width = '90%';

        document.querySelector('#createConsumer').style.padding = '0';
    }
    function printOptionOR(){
        var a = document.querySelector('#selectOption').value;
        if(a == 'oldOR'){
            $url = '{{route("PBOR")}}';
            window.open($url);
        }
        else{
            $url = '{{route("newOR")}}';
            window.open($url); 
        }
        // document.querySelector('#selectOR').style.display="none";
    }
</script>
@endsection
