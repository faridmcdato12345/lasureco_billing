@extends('layout.master')
@section('title', 'MRR Inquiry per Consumer - 2')
@section('content')

<style>
    #accountInp {
        cursor: pointer;
    }
    .account {
        height: 355px;
        overflow-x: hidden;
        border: 1px solid #ddd;
        margin-top: 2.3%;
    }
    #searchTable {
        color: black;
        width: 99%;
        margin: auto;
        text-align: center;
    }
    #searchTable td {
        height: 45px;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
    }

    .thead {
        color: white;
        background-color: royalblue;
        cursor: none;
    }

    #monthTo {
        cursor: pointer;
    }

    #monthFrom {
        cursor: pointer;
    }

    .MMRPC2HeaderDiv {
        height: 325px;
        background-color: white;
        margin-top: 1%;
        overflow-x: hidden;
    }

    #printBtn{
        height: 40px;
        margin-top: 0.5%;
        margin-right: -0.2%;
        display: none;
    }

    .MMRPC2HeaderTable {
        color: black;
    }

    .MMRPC2HeaderTable td {
        height: 50px;
        border-bottom: 1px solid #ddd;
    }
</style>

<p class="contentheader">MRR Inquiry per Consumer - 2</p>
<div class="main">
<br><br>
    <table class="content-table" style="margin-top: -2%;">
        <tr style="height: 40px;">
            <td style="width: 14%;">
               Account Number:
            </td>
            <td class="input-td">
                <input type="text" id="accountInp" onclick="showAccounts()" placeholder="Select Account" readonly>
                <input type="text" id="accountId" hidden>
            </td>
            <td>
               Month Range:
            </td class="input-td">
            <td class="input-td">
                <input type="month" id="monthFrom">
            </td>
            <td>
                To
            </td>
            <td>
                <input type="month" id="monthTo">
            </td>
        </tr>
        <tr>
            <td colspan=6>
                <div class="MMRPC2HeaderDiv">
                c</div>
            </td>
        </tr>
        <tr style="height: 42px;">
            <td colspan=6>
                <button id="printBtn" onclick="printMRR()" id="printBtn">Print</button>
            </td>
        </tr>
    </table>
</div>

<div id="accounts" class="modal">
    <div class="modal-content" style="margin-top: 30px; width: 40%; height: 510px;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Accounts Lookup</h3>
            <span href = "#accounts" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <input type="text" id="searchAccount" onkeyup="searchAccounts()" placeholder="Search Accounts">
            <div class="account">
                <div class="accountDiv"> </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showAccounts(){
        document.querySelector('#accounts').style.display = "block";
    }

    function searchAccounts() {
        var search = document.querySelector("#searchAccount");
        var account = search.value;

        if(account !== ""){
            if(account.length >= 3){
                const xhr = new XMLHttpRequest();
                var accounts = "{{route('search.consumer.name.account',['request'=>':request'])}}";
                var newAccount = accounts.replace(':request', account);
                xhr.open('GET', newAccount, true);
                xhr.send();

                xhr.onload = function(){
                    if(this.status == 200){
                        var response = JSON.parse(this.responseText);
                        var route = response.data;

                        if(response != ""){
                            var output = "<table id='searchTable'> <tr class='thead'> <td> Account Number </td> <td> Account Name </td> </tr>";

                            for(var x=0; x < response.length; x++){
                                output += "<tr onclick='selectAccount(this)' id='" + response[x].cm_id + "'";
                                output += "number='" + response[x].cm_account_no + "' name='" + response[x].cm_full_name + "'> <td>" + response[x].cm_account_no + "</td>";
                                output += "<td>" + response[x].cm_full_name + "</td> </tr>";
                            } 
                            output += "</table>";

                            document.querySelector(".accountDiv").innerHTML = output;
                        } else {
                            var output = "<table style='color: black; width: 100%;'> <br> <br> <tr>"; 
                            output += "<td style='text-align: center; font-size: 27px;'> No Account found! </td> </tr> </table>";
                            document.querySelector(".accountDiv").innerHTML = output;
                        }
                    }
                } 
            } else {
                document.querySelector(".accountDiv").innerHTML = "";
            }
        } 
    }

    function selectAccount(x){
        var accountId = x.id;
        var accountNumber = x.getAttribute("number");
        var accountName = x.getAttribute("name");
        var account = accountNumber + " - " + accountName;
        
        document.querySelector("#accountInp").value = account;
        document.querySelector("#accountId").value = accountId;
        document.querySelector("#accounts").style.display = "none";

        checkInputs();
    }

    var dateFrom = document.querySelector("#monthFrom");
    var dateTo = document.querySelector("#monthTo");

    dateFrom.addEventListener("change", function(){
        checkInputs();
    })

    dateTo.addEventListener("change", function(){
        checkInputs();
    })

    function checkInputs(){
        var billDateFrom = dateFrom.value;
        var billDateTo = dateTo.value;

        if(billDateFrom !== "" && billDateTo !== "" && accountId.value !== ""){
            const toSend = {
                "date_period_from": billDateFrom,
                "date_period_to": billDateTo,
                "cons_id": accountId.value
            }

            var toSendJSONed = JSON.stringify(toSend);

            var token = document.querySelector('meta[name="csrf-token"]').content;
            const xhr = new XMLHttpRequest();
            var mrrInqCons2 = "{{route('report.meter.reading.inquiry.consumer2')}}";
            xhr.open('POST', mrrInqCons2, true);
            xhr.setRequestHeader("Accept", "application/json");
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
            xhr.setRequestHeader("X-CSRF-TOKEN", token);

            xhr.send(toSendJSONed);

            xhr.onload = function(){
                if(this.status == 200){
                    var data = JSON.parse(this.responseText);
                    var cons_inq = data.MRR_CONS_INQ;

                    var output = '<table class="MMRPC2HeaderTable"> <tr class="thead">';
                    //output += "<td> &nbsp; Date Read </td>";
                    output += "<td> &nbsp;&nbsp;&nbsp; Bill No. </td>";
                    output += "<td> Yr./Mo. </td>";
                    output += "<td> Stat </td>";
                    output += "<td> Pres Rdng </td>";
                    output += "<td> Prev Rdng </td>";
                    output += "<td> KWH Used </td>";
                    output += "<td> Curr. Amt Due </td>";
                    output += "<td> Total Amt Due </td> </tr>";

                    for(var a in cons_inq){
                        //output += "<tr> <td>" + cons_inq[a].Date_Read + "</td>";
                        output += "<td> &nbsp;&nbsp;&nbsp;" + cons_inq[a].Bill_No + "</td>";
                        output += "<td>" + cons_inq[a].Yr_Mo + "</td>";
                        output += "<td>" + cons_inq[a].Status + "</td>";
                        output += "<td>" + cons_inq[a].Pres_Reading + "</td>";
                        output += "<td>" + cons_inq[a].Prev_Reading + "</td>";
                        output += "<td>" + cons_inq[a].KWH_Used + "</td>";
                        output += "<td>" + cons_inq[a].Curr_Amount_Due + "</td>";
                        output += "<td>" + cons_inq[a].Total_Amount_Due + "</td> </tr>";
                    }
                    output += "</table>";
                    document.querySelector(".MMRPC2HeaderDiv").innerHTML = output;
                    document.querySelector("#printBtn").style.display = "block";
                }
            }
        } else {
            document.querySelector(".MMRPC2HeaderDiv").innerHTML = "";
            document.querySelector("#printBtn").style.display = "none";
        }
    }

    function printMRR() {
        var accountId = document.querySelector("#accountId").value;
        var monthFrom = document.querySelector("#monthFrom").value;
        var monthTo  = document.querySelector("#monthTo").value;

        const toSend = {
            "monthFrom": monthFrom,
            "monthTo": monthTo,
            "accountId": accountId
        }
        
        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_mrr_inquiry_per_consumer_2")}}'
        window.open($url);
        location.reload();
    }
</script>
@endsection
