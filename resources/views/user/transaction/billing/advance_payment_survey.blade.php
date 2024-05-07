@extends('layout.master')
@section('title', 'Double Payment Entry')
@section('content')
<style>
    input {
        color: black;
    }
    textarea::placeholder {
    font-size: 12px;
    }
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
    #address {
        display: none;
        margin-top: 2%; 
        margin-left: 1%;
    }
    #date {
        width: 93.6%;
    }
    #amountDateTable {
        margin-top: 3%;
        display: none;
    }
    #ORNumTable {
        margin-top: 7%;
        display: none;
    }
    #amountDateTable input {
        margin-left: 1.5px;
    }
    #ORNumTable input {
        margin-left: 1.5px;
    }
    #mainTable {
        margin-top: 2%;
    }
    #mainTable td {
        height: 60px;
    }
    #proceedBtn {
        float: right; 
        display: none;
        margin-right: 7.5%; 
        margin-top: 22%;
        border: none;
        border-radius: 3px;
        height: 40px;
        background-color: white;
        color: royalblue;
    }
    @media screen and (min-width:1681px) and (max-width: 1920px) {
        #proceedBtn {
            height: 45px;
            width: 20%;
        }
    }
    @media screen and (min-width:1601px) and (max-width: 1680px) {
        #proceedBtn {
            height: 45px;
            width: 20%;
        }
    }
    @media screen and (min-width: 1280px) and (max-width: 1600px) {
        #proceedBtn {
            height: 42px;
            width: 20%;
        }
    }
    @media screen and (min-width:1280px) and (max-width: 1920px) {
        #accountInp {
            width: 90%;
        }
        #amountDateTable {
            width: 90%;
        }
        #ORNumTable {
            width: 90%;
        }
        #rcvAmnt {
            width: 80%;
        }
        #date {
            width: 80%;
        }
        #orNum {
            width: 80%;
        }
        #remarks {
            width: 80%;
        }
        #ORNumTable {
            margin-top: -2.5%;
        }
        #proceedBtn {
            margin-right: 20%;
        }
    }  
        
</style>
<p class="contentheader">Double Payment Entry</p>
<div class="main">
    <table class="content-table" id="mainTable">
        <tr>
            <td style="width: 21%;">
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
    <table id="amountDateTable" class="content-table">
        <tr>
            <td id="AdPayAmnt">
                Advance Payment Amount: &nbsp;
            </td>
            <td style="width: 35%;">
                <input type="number" id="rcvAmnt" placeholder="Amount">
            </td>
            <td>
                &nbsp; OR Date: &nbsp;
            </td>
            <td style="width: 35%;">
                <input type="date" id="date">
            </td>
        </tr>
    </table>
    <table id="ORNumTable" class="content-table">
        <tr>
            <td style="width: 20.9%;">
                OR Number: &nbsp;
            </td>
            <td style="width: 35%;">
                <input type="number" id="orNum" placeholder="OR Number">
            </td>
            <td>
                &nbsp; Remarks: &nbsp;
            </td>
            <td style="width: 35%;">
                <input type="text" id="remarks" style="margin-left: -0.2px;" placeholder="Remarks">
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td> <button id="proceedBtn" onclick="enterEWallet()"> Proceed </button> </td>
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
                        <input type="text" onkeyup="setTimeout(search(), 1000);" id="search" placeholder="Search Account">
                    </td>
                </tr>
            </table>
            <div class="accountDiv"> </div>
        </div>
    </div>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container').childNodes;
        c[5].style.color="blue";
    }

    function showAccounts(){
        document.querySelector('#accounts').style.display = "block";
    }

    function showORNumTable(){
        var rcvAmnt = document.querySelector('#rcvAmnt').value;
        var date = document.querySelector('#date').value;

        if(rcvAmnt !== "" && date !== ""){
            document.querySelector('#ORNumTable').style.display = "block";
        } else {
            document.querySelector('#ORNumTable').style.display = "none";
        }
    }
    
    document.querySelector('#rcvAmnt').addEventListener('change', function(){
        showORNumTable();
    })

    document.querySelector('#date').addEventListener('change', function(){
        showORNumTable();
    })

    function search(){
        var account = document.querySelector('#search').value;

        if(account.length > 0){
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'http://10.12.10.100:8082/api/v1/consumer/searchByName/' + account, true);
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
        document.querySelector('#amountDateTable').style.display = "block";
        document.querySelector('#search').value = "";
        document.querySelector('.accountDiv').style.display = "none";
    }

    document.querySelector('#orNum').addEventListener('change', function(){
        var or = document.querySelector('#orNum').value;
        if(or !== ""){
            document.querySelector('#proceedBtn').style.display = "block";
        } else {
            document.querySelector('#proceedBtn').style.display = "none";
        }
    })

    function enterEWallet(){
        var cmid = document.querySelector('#cmid').value;
        var date = document.querySelector('#date').value;
        var rcvAmnt = document.querySelector('#rcvAmnt').value;
        var or = document.querySelector('#orNum').value;
        var remarks = document.querySelector('#remarks').value;

        const toSend = {
            cons_id: cmid,
            ewallet_amount: rcvAmnt,
            ewallet_or_date: date,
            ewallet_or: or,
            ewallet_remarks:remarks
        } 
        const toSendJSONed = JSON.stringify(toSend);

        var xhr = new XMLHttpRequest();
        var token = document.querySelector('meta[name="csrf-token"]').content;
        xhr.open("POST", "http://10.12.10.100:8082/api/v1/ewallet/entry", true);
		xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
		xhr.setRequestHeader("X-CSRF-TOKEN", token);
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(this.status == 201){
                alert("Succesful Entry of E-Wallet Amount");
                location.reload();
            } else if(this.status == 422) {
                var errorMessage = JSON.parse(this.responseText);

                alert(errorMessage.Message);
            }
        }
    }
</script>
@endsection
