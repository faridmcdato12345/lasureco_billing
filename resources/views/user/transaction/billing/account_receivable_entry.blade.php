@extends('layout.master')
@section('title', 'Accountable Receivable Entry')
@section('content')

<style>
    input {
        color: black;
    }
    #mainTable {
        margin-top: 7%;
    }
    #mainTable td {
        height: 60px;
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
    #rcvTbl {
        display: none;
        margin-top: 5%;
    }
    #proceedBtn {
        border: none;
        border-radius: 3px;
        height: 40px;
        background-color: white;
        color: royalblue;
        float: right;
        margin-right: 1.5%;
        margin-top: 10%;
        display: none;
    }
    #accountModal {
        margin-top: 30px; 
        width: 50%; 
        height: 400px;
    }
    #rcvAmnt {
        margin-left: 2%;
    }
    @media screen and (min-width:1601px) and (max-width: 1920px) {
        #proceedBtn {
            width: 9%;
            height: 45px;
        }
    }
</style>

<p class="contentheader">Accountable Receivable Entry</p>
<div class="main">
    <table class="content-table" id="mainTable">
        <tr>
            <td style="width: 15.8%;">
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
    <table class="content-table" id="rcvTbl">
        <tr>
            <td style="width: 15%;">
                Receivable Amount:
            </td>
            <td>
                <input type="number" id="rcvAmnt" placeholder="Amount">
            </td>
        </tr>
        <tr>
            <td></td>
            <td> 
                <button id="proceedBtn" onclick="enterReceivable()"> Proceed </button>
            </td>
        </tr>
    </table>
</div>

<div id="accounts" class="modal">
    <div class="modal-content" id="accountModal">
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
        document.querySelector('#rcvTbl').style.display = "block";
        document.querySelector('#search').value = "";
        document.querySelector('.accountDiv').style.display = "none";
    }

    document.querySelector('#rcvAmnt').addEventListener('change', function(){
        var rcvAmnt = document.querySelector('#rcvAmnt').value;

        if(rcvAmnt !== ""){
            document.querySelector('#proceedBtn').style.display = "block";
        } else {
            document.querySelector('#proceedBtn').style.display = "none";
        }
    })

    function enterReceivable(){
        var cmid = document.querySelector('#cmid').value;
        var rcvAmnt = document.querySelector('#rcvAmnt').value;

        const toSend = {
            cm_id: cmid,
            rec_amount: rcvAmnt,
            user_id: {{Auth::user()->user_id}}
        } 
        const toSendJSONed = JSON.stringify(toSend);

        var xhr = new XMLHttpRequest();
        var token = document.querySelector('meta[name="csrf-token"]').content;
        xhr.open("POST", "http://10.12.10.100:8082/api/v1/consumer/accountable/amount", true);
		xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
		xhr.setRequestHeader("X-CSRF-TOKEN", token);
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(this.status == 200){
                alert("Succesful Entry of Accountable Amount");
                location.reload();
            }
        }
    }
</script>
@endsection
