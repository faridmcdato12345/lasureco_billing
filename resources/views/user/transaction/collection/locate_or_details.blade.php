@extends('layout.master')
@section('title', 'Locate OR Details')
@section('content')

<style>
    #mainTable{
        margin: auto;
        width: 90%;
        margin-top: 5%;
    }
    #accountInp {
        cursor: pointer;
    }
    #accountTable {
        width: 100%;
        color: black;
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
    #accountName {
        display: none;
    }
    #locateBtn {
        margin-top: 230px;
        float: right;
        margin-right: 80px;
        height: 40px;
        border: none;
        border-radius: 3px;
        background-color: white;
        color: royalblue;
        display: none;
    }
    .orDiv {
        border: 1px #ddd solid;
        height: 400px;
        overflow-y: scroll;
    }
    .orTable {
        width: 100%;
        color: black;
    }
    .orThead {
        background-color: #5B9BD5;
        color: white;
        text-align: center;
    }
    .orTable td {
        height: 50px;
        border-bottom: 1px #ddd solid;
    }
    .orTbody {
        text-align: center;
    }
    #accountModal {
        margin-top: 30px; 
        width: 50%; 
        height: 400px;
    }
    input {
        color: black;
    }
    @media screen and (min-width:1681px) and (max-width: 1920px) {
        #mainTable {
            font-size: 22px;
        }
        input {
            height: 50px;
            font-size: 22px;
        }
        #accountName {
            margin-top: 15px;
        }
        #locateBtn {
            margin-top: 10%;
            width: 6%;
            height: 45px;
            margin-top: 30%;
        }
        #accountModal {
            width: 40%;
            height: 450px;
            margin-top: 90px;
            font-size: 20px;
        }
        .accountDiv {
            font-size: 17px;
        }
    }
</style>

<p class="contentheader"> Locate Consumer per OR </p>
<div class="main">
    <table id="mainTable">
        <tr>
            <td style="width: 12%;">
                &nbsp;&nbsp; Account:
            </td>
            <td style='width: 35%;'> 
                <input type="text" id="accountInp" onclick="showAccounts()" placeholder="Select Account" readonly>
            </td>
            <td>
                <p id='accountName'> </p>
                <input type="text" id="cmid" style="display: none;">
            </td>
        </tr>
        <tr>
            <td colspan=3>
                <button id='locateBtn' onclick='locateOR()'> Locate </button>
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

<div id="orDetails" class="modal">
    <div class="modal-content" style="margin-top: 10px; width: 80%; height: 500px;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>OR Details</h3>
            <span href = "#orDetails" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="orDiv"> </div>
        </div>
    </div>
</div>

<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container').childNodes;
        c[9].style.color="blue";
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
                        output += "' name='" + response[i].cm_full_name + "' cmid='" + response[i].cm_id + "'> <td> &nbsp;&nbsp;" + response[i].cm_account_no;
                        output += "</td> <td>" + response[i].cm_full_name + "</td></tr>";
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

        document.querySelector('#cmid').value = cmid;
        document.querySelector('#accountInp').value = id;
        document.querySelector('#accountName').innerHTML = name;
        document.querySelector('#accountName').style.display = "block";
        document.querySelector('#accounts').style.display = "none";
        document.querySelector('#search').value = "";
        document.querySelector('.accountDiv').style.display = "none";
        showLocateBtn();
    }

    function showLocateBtn(){
        var inp = document.querySelector('#accountInp');

        if(inp !== ""){
            document.querySelector('#locateBtn').style.display = "block";
        } else {
            document.querySelector('#locateBtn').style.display = "none";
        }
    }

    document.querySelector('#close').addEventListener('click', function(){
        document.querySelector('#search').value = "";
        document.querySelector('.accountDiv').style.display = "none";
    })

    function locateOR(){
        var id = document.querySelector('#cmid').value;

        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'http://10.12.10.100:8082/api/v1/locate/consumer/or/' + id, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                var response = JSON.parse(this.responseText);
                var orDetails = response.Consumer_OR_Details;
                var advancePB = response.Advance_Power_Bill;

                var output = "<table class='orTable'> <tr class='orThead'> <td> &nbsp; TOR No </td>";
                output += "<td> OR Date </td> <td> Account Number </td>";
                output += "<td> Consumer Name </td> <td> Fee Code </td>";
                output += "<td> Description </td> <td> Period </td> <td> OR Amount </td> </tr>";

                for(var x in orDetails){
                    output += "<tr class='orTbody'> <td>" + orDetails[x].s_or_num + "</td>";
                    output += "<td>" + orDetails[x].s_bill_date + "</td>";
                    output += "<td>" + orDetails[x].cm_account_no + "</td>";
                    output += "<td>" + orDetails[x].cm_full_name + "</td>";
                    output += "<td>" + orDetails[x].f_code + "</td>";
                    output += "<td>" + orDetails[x].f_description + "</td>";
                    output += "<td>" + orDetails[x].mr_date_year_month + "</td>";
                    output += "<td>" + orDetails[x].s_or_amount + "</td></tr>";
                }

                for(var x in advancePB){
                    output += "<tr class='orTbody'> <td>" + advancePB[x].s_or_num + "</td>";
                    output += "<td>" + advancePB[x].s_bill_date + "</td>";
                    output += "<td>" + advancePB[x].cm_account_no + "</td>";
                    output += "<td>" + advancePB[x].cm_full_name + "</td>";
                    output += "<td>" + advancePB[x].f_code + "</td>";
                    output += "<td>" + advancePB[x].f_description + "</td>";
                    output += "<td>" + advancePB[x].mr_date_year_month + "</td>";
                    output += "<td>" + advancePB[x].e_wallet_added + "</td></tr>";
                }
                output += "</table>";
                document.querySelector('.orDiv').innerHTML = output;
                document.querySelector('#orDetails').style.display = "block";
            } else {
                alert('No OR to locate');
            }
        }
    }
</script>
@endsection
