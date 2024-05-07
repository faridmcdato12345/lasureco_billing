@extends('layout.master')
@section('title', 'Bill Cancellation')
@section('content')

<style>
    #accountTable {
        width: 100%;
        color: black;
    }
    #accountInp {
        cursor: pointer;
    }
    #accountTable td{
        height: 50px;
        border-bottom: 1px #ddd solid;
    }
    .accountDiv {
        height: 250px;
        overflow-y: scroll;
        margin-top: 20px;
        border: 1px #ddd solid;
        display: none;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #mainTable td {
        height: 60px;
    }
    #billDateTbl {
        display: none;
        margin-top: 2%;
        width: 95%;
        margin: auto;
    }
    #billPeriod {
        margin-left: 15%;
        width: 387.5%;
    }
    #mainTable {
        width: 95%;
        margin: auto;
        margin-top: -2%;
    }
    #billAmntTbl {
        margin-top: 7%;
        display: none;
        width: 95%;
        margin: auto;
    }
    #proceedBtn {
        float: right;
        border: none;
        border-radius: 3px;
        height: 40px;
        background-color: white;
        color: royalblue;
        margin-top: 30%;
    }
    input {
        color: black;
    }
    @media screen and (min-width:1681px) and (max-width: 1920px) {
        #accountInp {
            width: 1000px;
            margin-left: -3px;
        }
        #billAmount {
            width: 1000px;
            margin-left: 70px;
        }
        #billDateTbl {
            margin-top: 1.5%;
        }
        #billAmntTbl {
            margin-top: -1.5%;
        }
        #billPeriod {
            width: 462%;
            margin-left: 23%;
        }
        #proceedBtn {
            margin-top: 7%;
            height: 45px;
            width: 10%;
        }
        .main {
            margin-left: 35px;
        }
    }
    @media screen and (min-width:1601px) and (max-width: 1680px) {
        #accountInp {
            width: 950px;
          
        }
        #billAmount {
            width: 947px;
            margin-left: 50px;
        }
        #billDateTbl {
            margin-top: 1.5%;
        }
        #billAmntTbl {
            margin-top: -1.5%;
        }
        #billPeriod {
            width: 437%;
            margin-left: 17.5%;
        }
        #proceedBtn {
            margin-top: 7%;
            height: 45px;
            width: 10%;
        }
        .main {
            margin-left: 35px;
        }
    }
    @media screen and (min-width:1441px) and (max-width: 1600px) {
        #accountInp {
            width: 850px;
            margin-left: -3px;
        }
        #billAmount {
            width: 850px;
            margin-left: 46px;
        }
        #billDateTbl {
            margin-top: 1.5%;
        }
        #billAmntTbl {
            margin-top: -1.5%;
        }
        #billPeriod {
            width: 392%;
        }
        #proceedBtn {
            margin-top: 6.5%;
        }
    }
    @media screen and (min-width:1401px) and (max-width: 1440px) {
        #accountInp {
            width: 842px;
            margin-left: -3px;
        }
        #billAmount {
            width: 842px;
            margin-left: 46px;
        }
        #proceedBtn {
            margin-top: 6.5%;
        }
        #billDateTbl {
            margin-top: 1.5%;
        }
        #billAmntTbl {
            margin-top: -1.5%;
        }
    }
    @media screen and (min-width:1361px) and (max-width: 1400px) {
        #accountInp {
            width: 840px;
            margin-left: -2px;
        }
        #billAmount {
            width: 836px;
            margin-left: 46px;
        }
        #billDateTbl {
            margin-top: 2%;
        }
        #billAmntTbl {
            margin-top: -1%;
        }
        #proceedBtn {
            margin-top: 6%;
        }
    }
    @media screen and (min-width:1336px) and (max-width: 1360px) {
        #billAmount {
            width: 835px;
            margin-left: 45px;
        }
        #accountInp {
            width: 835px;
        }
        #proceedBtn {
            margin-top: 7%;
        }
    }
    @media screen and (min-width: 1281px) and (max-width: 1335px){
        #billAmount {
            width: 835px;
            margin-left: 45px;
        }
        #accountInp {
            width: 835px;
        }
        #proceedBtn {
            margin-top: 7.5%;
        }
    }
    @media screen and (max-width: 1280px) {
        #billAmount {
            width: 835px;
            margin-left: 45px;
        }
        #proceedBtn {
            margin-top: 10%;
        }
    }
</style>
@include('include.modal.consumerAcctModal')
<p class="contentheader">Bill Cancellation</p>
<div class="main">
<br><br>
    <table id="mainTable" class="content-table">
        <tr>
            <td style="width: 13%;">
                Account:
            </td>
            <td>
                <input type="text" id="accountInp" onclick="showAccounts()" placeholder="Select Account" readonly>
                <input type="text" id="cmid" style="display: none;">
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <p id="address"></p>
            </td>
        </tr>
    </table>
    <table id="billDateTbl" class="content-table">
        <tr>
            <td>
                Billing Period:
            </td>
            <td>
                <input type="month" id="billPeriod">
            </td>
        </tr>
    </table>
    <table id="billAmntTbl" class="content-table">
        <tr>
            <td>
                Bill Amount:
            </td>
            <td>
                <input type="text" id="billAmount" readonly>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button id="proceedBtn" onclick="cancelBill()"> Proceed </button>
            </td>
        </tr>
    </table>
</div>

<div id="accounts" class="modal">
    <div class="modal-content" style="margin-top: 30px; width: 50%; height: 400px;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Account Lookup</h3>
            <span href = "#accounts" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <table style="width: 100%; color: black;">
                <tr>
                    <td style="width: 15%;">
                        &nbsp; Account:
                    </td>
                    <td>
                        <input type="text" onchange="search()" id="search" placeholder="Search Account">
                    </td>
                </tr>
            </table>
            <div class="accountDiv"> </div>
        </div>
    </div>
</div>

<script>
    function showAccounts(){
        document.querySelector('#accounts').style.display = "block";
    }

    function search(){
        var account = document.querySelector('#search').value;

        if(account.length > 0){
            var xhr = new XMLHttpRequest();
            var search = "{{route('search.consumer.name.account',['request'=>':account'])}}";
            search=search.replace(':account',account);
            xhr.open('GET', search, true);
            xhr.send();

            xhr.onload = function(){
                if(this.status == 200){
                    document.querySelector('.accountDiv').style.display = "block";
                    var response = JSON.parse(this.responseText);
                    var data = response.element;

                    var accountTable = document.querySelector('#accountDiv');
                    var output = "<table id='accountTable'> <tr id='thead'>";
                    output += "<td style='width: 35%;'> &nbsp; Account Number </td> <td> Account Name </td> </tr>";

                    for(var i in response){
                        output += "<tr class='tbody' onclick='selectAccount(this)' id='" + response[i].cm_account_no;
                        output += "' name='" + response[i].cm_full_name + "' cmid='" + response[i].cm_id + "' address='" + response[i].cm_address + "'>";
                        output += "<td> &nbsp;&nbsp;" + response[i].cm_account_no + "</td> <td>" + response[i].cm_full_name + "</td></tr>";
                    }
                    output += "</table>";
                    
                    document.querySelector('.accountDiv').innerHTML = output;
                } else {
                    document.querySelector('.accountDiv').style.display = "none";
                }
            }
        } else {
            document.querySelector('.accountDiv').style.display = "none";
        }
    }

    function selectAccount(x) {
        var id = x.id;
        var name = x.getAttribute('name');
        var cmid = x.getAttribute('cmid');
        var address = x.getAttribute('address');
        var acc = id + " - " + name;

        document.querySelector('#cmid').value = cmid;
        document.querySelector('#accountInp').value = acc;
        document.querySelector('#accounts').style.display = "none";
        document.querySelector('#address').innerHTML = address;
        document.querySelector('#address').style.display = "block";
        document.querySelector('#billDateTbl').style.display = "block";
        document.querySelector('#search').value = "";
        document.querySelector('.accountDiv').style.display = "none";
    }

    document.querySelector('#billPeriod').addEventListener('change', function(){
        var date = document.querySelector('#billPeriod').value;
        
        if(date !== "") {
            setBillCollection();
        } else {
            document.querySelector('#billAmntTbl').style.display = "none";
        }
    })

    function setBillCollection(){
        var cmid = document.querySelector('#cmid').value;
        var billPeriod = document.querySelector("#billPeriod").value;

        var req = new XMLHttpRequest();
        var user_id = {{Auth::user()->user_id}};
        var cancelBill = "{{route('set.cancel.billing.amount',['id'=>':par', 'date'=>':par2'])}}";
        var newCancelBill = cancelBill.replace(':par', cmid);
        var newCancelBill2 = newCancelBill.replace(':par2', billPeriod);
        req.open('GET', newCancelBill2, true);
        req.send();

        req.onload = function(){
            if(this.status == 200){
                var response = JSON.parse(this.responseText);
                var amount = response.Amount;

                document.querySelector('#billAmount').value = amount;
                document.querySelector('#billAmntTbl').style.display = "block";
            } else {
                Swal.fire({
                    title: 'Error!',
                    icon: 'error',
                    text: 'No record found!'
                })
                document.querySelector('#billAmntTbl').style.display = "none";
            }
        }
    }

    function cancelBill(){
        var cmid = document.querySelector('#cmid').value;
        var billPeriod = document.querySelector("#billPeriod").value;
        var user_id = {{Auth::user()->user_id}};
        var req = new XMLHttpRequest();
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var cancelBill = "{{route('cancel.billing',['id'=>':par', 'date'=>':par2', 'user_id'=>':par3'])}}";
        var newCancelBill = cancelBill.replace(':par', cmid);
        var newCancelBill2 = newCancelBill.replace(':par2', billPeriod);
        var newCancelBill3 = newCancelBill2.replace(':par3', user_id);
        req.open('DELETE', newCancelBill3, true);
        req.setRequestHeader("Accept", "application/json");
		req.setRequestHeader("Content-Type", "application/json");
		req.setRequestHeader("Access-Control-Allow-Origin", "*");
		req.setRequestHeader("X-CSRF-TOKEN", token);
        req.send();

        req.onload = function(){
            if(this.status == 200){
                Swal.fire({
                    title: 'Success!',
                    icon: 'success',
                    text: 'Bill successfully canceled',
                    type: 'success'
                }).then(function(){ 
                    location.reload();
                });
            }      
        }
    }
</script>
@endsection
