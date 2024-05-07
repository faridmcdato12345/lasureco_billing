@extends('layout.master')
@section('title', "Cashier's DCR")
@section('content')

<style>
    table{
        width: 90%;
    }
    #mainTable {
        margin-top: 2%;
    }
    
    #cashierTable {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #collDate {
        margin-top: 3%;
    }
    #totalCollTbl {
        margin-top: 3%;
    }
    #signatoryTable {
        margin-top: 2%;
    }
    .date {
        width: 90%;
    }
    #cashierTable td{
        height: 40px;
        border-bottom: 1px #ddd solid;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #collDate{
        display: none;
    }
    #totalCollTbl {
        display: none;
    }
    #signatoryTable {
        display: none;
    }
    #signatoryTable td{
        height: 65px;
    }
    #signatoryTable input{
        width: 376px;
    }
    #totalCollection {
        width: 367%;
    }
    .secondTd {
        margin-left: 17px;
    }
    input {
        color: black;
    }
    #collDate input {
        width: 95%;
    }
    #collDateTD {
        width: 42.55%;
    }
</style>

<p class="contentheader">Print Cashier's DCR</p>
<div class="main">
    <table id="mainTable" class="content-table">
        <tr>
            <td style="width:15%;" class="thead">
                Cashier:
            </td>
            <td class="input-td">
                <input type="text" id="cashierInp" onclick="showCashiers()" placeholder="Select Cashier" style="cursor: pointer;">
                <input type="text" id="cashierId" style="display: none;">
            </td>
        </tr>
    </table>
    <table id="collDate" class="content-table">
        <tr>
            <td>
                Collection Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td id="collDateTD"> 
                <input type="date" id="collDatefrom">
            </td>
            <td id="collDateTD">
                <input type="date" id="collDateTo"> 
            </td>
        </tr>
    </table>

    <table id="totalCollTbl" class="content-table">
        <tr>
            <td style="width: 39.8%;">
                Total Collection:
            </td>
            <td>
                <input type="text" id="totalCollection" readonly>
            </td>
        </tr>
        <tr>
            <td colspan=3>
                <button onclick="printDCR()"> Print </button>
            </td>
        </tr>
    </table>
</div>

<div id="cashiers" class="modal">
    <div class="modal-content" style="margin-top: 30px; width: 40%; height: 350px;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Cashier Lookup</h3>
            <span href = "#cashiers" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="cashierDiv"> </div>
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

    function showCashiers(){
        document.querySelector('#cashiers').style.display = "block";
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'http://10.12.10.100:8082/api/v1/employee/cashier', true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                var response = JSON.parse(this.responseText);
                var emp = response.Cashiers;
                var output = "<table id='cashierTable'> <tr id='thead'> <td> &nbsp;  Employee Number </td>";
                output += "<td> Employee </td> </tr>";
                
                for(var a in emp){
                    output += "<tr onclick='selectCashier(this)' id='" + emp[a].em_emp_no + "' class='tbody'";
                    output +=  "name='" + emp[a].gas_fnamesname +"'> <td>&nbsp;&nbsp;" + emp[a].em_emp_no + "</td>";
                    output += "<td>" + emp[a].gas_fnamesname + "</td></tr>";
                }
                output += "</table>";

                document.querySelector('.cashierDiv').innerHTML= output;
            }
        }
    }

    function selectCashier(x){
        var id = x.id;
        var name = x.getAttribute('name');
        
        document.querySelector('#cashierId').value = id;
        document.querySelector('#cashierInp').value = name;
        document.querySelector('#cashiers').style.display = "none";

        document.querySelector('#collDate').style.display = "block";
    }

    var collDateFrom = document.querySelector('#collDatefrom');
    collDateFrom.addEventListener('change', function(){
        showTotalColl();
    })

    var collDateTo = document.querySelector('#collDateTo');
    collDateTo.addEventListener('change', function(){
        showTotalColl();
    })

    function showTotalColl(){
        var from = document.querySelector('#collDatefrom').value;
        var to = document.querySelector('#collDateTo').value;

        if(from !== "" && to !== ""){
            setTotalCollection();
        } else {
            document.querySelector('#totalCollTbl').style.display = "none";
        }
    }

    function setTotalCollection(){
        var from = document.querySelector('#collDatefrom').value;
        var to = document.querySelector('#collDateTo').value;

        const toSend = new Object();
        toSend.bill_date_from = from;
        toSend.bill_date_to = to;
        console.log(toSend);
        var toSendJSONed = JSON.stringify(toSend);

        var xhr = new XMLHttpRequest();
        var token = document.querySelector('meta[name="csrf-token"]').content;
        xhr.open('POST', 'http://10.12.10.100:8082/api/v1/collections/cashier/dcr/total_collection', true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        console.log(toSendJSONed);
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(this.status == 200){
                var response = JSON.parse(this.responseText);
                var totalCollection = response.Total_Collection;
                document.querySelector('#totalCollection').value = totalCollection;
                document.querySelector('#totalCollTbl').style.display = "block"; 
                document.querySelector('#signatoryTable').style.display = "block";
            } else {
                alert('No Cashier DCR');
                document.querySelector('#totalCollTbl').style.display = "none";
            }
        }
    }

    function printDCR(){
        var from = document.querySelector('#collDatefrom').value;
        var to = document.querySelector('#collDateTo').value;
        var cashier = document.querySelector('#cashierInp').value;

        const toSend = {
            'from': from,
            'to': to,
            'cashier': cashier
        }
        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_cashiers_dcr")}}'
        window.open($url);
    }
</script>
@endsection
