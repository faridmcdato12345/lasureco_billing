@extends('layout.master')
@section('title', 'Transfer Collection')
@section('content')

<style>
    input {
        color: black;
    }
    .tellerDiv{
        color: black;
        height: 250px;
        overflow-y: scroll;
    }
    #tellerTable {
        width: 100%;
        border: 1px #ddd solid;
    }
    #tellerName {
        cursor: pointer;
        width: 95%;
        margin-left: 2px; 
    }
    #total {
        width: 308px;
    }
    #tellerTable td {
        height: 40px;
        border-bottom: 1px #ddd solid; 
    }
    #tellerTable .tellerRows{
        cursor: pointer;
    }
    .thead {
        background-color: #5B9BD5;
        color: white;
        cursor: none;
    }
    #mainTable {
        margin-left: 70px;
        height: 350px;
        width: 80%;
        margin-top: -12%;
    }
    #collDateRange {
        margin-left: 70px;
        height: 350px;
        width: 80%;
        display: none;
        margin-top: -8%;
    }
    #ORRangeTable {
        margin-left: 70px;
        height: 350px;
        width: 80%;
        display: none;
        margin-top: -24%;
    }
    #totalTable {
        margin-left: 70px;
        height: 350px;
        width: 80%;
        display: none;
        margin-top: -24%;
    }
    #transferBtn{
        float: right; 
        margin-right: -320px;
        border: none;
        border-radius: 3px;
        height: 40px;
        color: royalblue;
        background-color: white;
    }
    @media screen and (min-width:1681px) and (max-width: 1920px){
        #mainTable {
            margin-top: 4%;
            height: 60px;
        }
        #collDateRange {
            margin-top: 0%;
            height: 60px;
            margin-top: 7%;
        }
        #ORRangeTable {
            height: 60px;
            margin-top: 7%;
        }
        input {
            height: 50px;
        }
    }
    @media screen and (min-width:1361px) and (max-width: 1680px) {
        #mainTable {
            margin-top: 3%;
            height: 60px;
        }
        #collDateRange {
            margin-top: 7%;
            height: 60px;
        }
        #ORRangeTable {
            height: 60px;
            margin-top: 7%;
        }
    }
</style>

<p class="contentheader">Transfer Collection</p>
<div class="main">
    <table id="mainTable" class="content-table">
        <tr>
            <td style="width: 20%;">
                Account Teller:
            </td>
            <td> 
                <input type="text" id="tellerName" onclick="showTellers()" placeholder="Select Teller" readonly>
                <input type="text" id="tellerId" style="display: none;">
            </td>
        </tr>
    </table>
    <table id="collDateRange" class="content-table">
        <tr>
            <td style="width: 20%;">
                Collection Date Range:
            </td>
            <td> 
                &nbsp; <input type="date" id="collDatefrom">
            </td>
            <td>
                &nbsp; <input type="date" id="collDateTo">
            </td>
        </tr>
    </table>
    <table id="ORRangeTable" class="content-table">
        <tr>
            <td style="width: 20%;">
                Collection Date Range:
            </td>
            <td> 
                &nbsp; <input type="text" id="ORfrom" placeholder="OR From">
            </td>
            <td>
                &nbsp; <input type="text" id="ORto" placeholder="OR To">
            </td>
        </tr>
    </table>
    <table id="totalTable" class="content-table">
        <tr>
            <td style="width: 35.5%;">
                Total:
            </td>
            <td> 
                <input type="text" id="total" readonly>
            </td>
        </tr>
        <tr>
            <td colspan=3>
                <button id='transferBtn' onclick="transferCollection()"> Transfer </button>
            </td>
        </tr>
    </table>
</div>

<div id="tellers" class="modal">
    <div class="modal-content" style="margin-top: 30px; width: 40%; height: 350px;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Teller Lookup</h3>
            <span href = "#tellers" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="tellerDiv"> </div>
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

    function showTellers(){
        document.querySelector('#tellers').style.display = "block";

        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'http://10.12.10.100:8082/api/v1/employee/teller?page=1', true);
        xhr.send();
        
        xhr.onload = function(){
            if(this.status == 200){
                var response = JSON.parse(this.responseText);
                var tellers = response.Tellers;

                var output = "<table id='tellerTable'> <tr class='thead'> <td> &nbsp; Employee Number </td>";
                output += "<td> Employee Name </td> </tr>";


                for(var i in tellers){
                    output += "<tr class='tellerRows' onclick='selectTeller(this)' id='" + tellers[i].em_emp_no + "'"; 
                    output += "name='" + tellers[i].gas_fnamesname + "'> <td>&nbsp;&nbsp;" + tellers[i].em_emp_no + "</td>";
                    output += "<td>" + tellers[i].gas_fnamesname + "</td> </tr>";
                }
                output += "</table>";
                document.querySelector(".tellerDiv").innerHTML = output;
            }
        }
    }

    function selectTeller(x){
        var tellerId = x.id;
        var tellerName = x.getAttribute('name');

        document.querySelector('#tellerId').value = tellerId;
        document.querySelector('#tellerName').value = tellerName;
        document.querySelector('#collDateRange').style.display = "block";
        document.querySelector('#tellers').style.display = "none";
    }

    var collDateFrom = "";
    var collDateTo = "";

    var from = document.querySelector('#collDatefrom');
    from.addEventListener("change", function (){
        collDateFrom = document.querySelector('#collDatefrom').value;
        
        if(collDateFrom !== "" && collDateTo !== ""){
            document.querySelector('#ORRangeTable').style.display = "block";
        } else {
            document.querySelector('#totalTable').style.display = "none";
            document.querySelector('#ORRangeTable').style.display = "none";
        }
    })

    var to = document.querySelector('#collDateTo');
    to.addEventListener("change", function (){
        collDateTo = document.querySelector('#collDateTo').value;

        if(collDateFrom !== "" && collDateTo !== ""){
            document.querySelector('#ORRangeTable').style.display = "block";
        } else {
            document.querySelector('#totalTable').style.display = "none";
            document.querySelector('#ORRangeTable').style.display = "none";
        }
    })

    var ORFrom = "";
    var ORTo = "";

    var fromOR = document.querySelector('#ORfrom');
    fromOR.addEventListener('change', function(){
        ORFrom = document.querySelector('#ORfrom').value;

        if(ORFrom !== "" && ORTo !== ""){
            const toPost = {
                or_from: ORFrom,
                or_to: ORTo
            }
            const toPostJSONed = JSON.stringify(toPost);

            var xhr = new XMLHttpRequest();
            var token = document.querySelector('meta[name="csrf-token"]').content;
            xhr.open("POST", "http://10.12.10.100:8082/api/v1/collections/total_amount/cashier/post", true);
		    xhr.setRequestHeader("Accept", "application/json");
		    xhr.setRequestHeader("Content-Type", "application/json");
		    xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
		    xhr.setRequestHeader("X-CSRF-TOKEN", token);
            xhr.send(toPostJSONed);

            xhr.onload = function(){
                if(this.status == 200){
                    var response = JSON.parse(this.responseText);
                    var total = response.Total_Collection;
                    document.querySelector("#total").value = total;
                    document.querySelector('#totalTable').style.display = "block";
                } else {
                    document.querySelector('#totalTable').style.display = "none";
                    alert("No Collection with the entered data!");
                }
            }
        } else{
            document.querySelector('#totalTable').style.display = "none";
        }
    })

    var toOR = document.querySelector('#ORto');
    toOR.addEventListener('change', function(){
        ORTo = document.querySelector('#ORto').value;

        if(ORFrom !== "" && ORTo !== ""){
            const toPost = {
                or_from: ORFrom,
                or_to: ORTo
            }
            const toPostJSONed = JSON.stringify(toPost);

            var xhr = new XMLHttpRequest();
            var token = document.querySelector('meta[name="csrf-token"]').content;
            xhr.open("POST", "http://10.12.10.100:8082/api/v1/collections/total_amount/cashier/post", true);
		    xhr.setRequestHeader("Accept", "application/json");
		    xhr.setRequestHeader("Content-Type", "application/json");
		    xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
		    xhr.setRequestHeader("X-CSRF-TOKEN", token);
            xhr.send(toPostJSONed);

            xhr.onload = function(){
                if(this.status == 200){
                    var response = JSON.parse(this.responseText);
                    var total = response.Total_Collection;
                    var totalFloated = parseFloat(total).toFixed(2);
                    
                    document.querySelector("#total").value = totalFloated;
                    document.querySelector('#totalTable').style.display = "block";
                } else {
                    document.querySelector('#totalTable').style.display = "none";
                    alert("No Collection with the entered data!");
                }
            }
        } else{
            document.querySelector('#totalTable').style.display = "none";
        }
    })

    function transferCollection(){
        var tellerId = document.querySelector('#tellerId').value;
        var from = document.querySelector('#collDatefrom').value;
        var to = document.querySelector('#collDateTo').value;
        var orFrom = document.querySelector('#ORfrom').value;
        var orTo = document.querySelector('#ORto').value;

        const toSend = {
            teller_id: parseInt(tellerId),
            bill_date_from: from,
            bill_date_to: to,
            or_from: parseInt(orFrom),
            or_to: parseInt(orTo)
        }
        var toSendJSONed = JSON.stringify(toSend);

        var xhr = new XMLHttpRequest();
        var token = document.querySelector('meta[name="csrf-token"]').content;
        xhr.open("POST", "http://10.12.10.100:8082/api/v1/collections/transfer/or");
		xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
		xhr.setRequestHeader("X-CSRF-TOKEN", token);
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(this.status == 200){
                alert('Succesfully Transfered Collection!');
            }
        }
    }
</script>
@endsection
