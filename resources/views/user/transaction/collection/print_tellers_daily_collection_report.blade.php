@extends('layout.master')
@section('title', 'Print Tellers Daily Collection')
@section('content')

<style>
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #tellerTbl {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #tellerTbl td{
        height: 50px;
        border-bottom: 1px #ddd solid;
    }
    .tellerDiv {
        height: 301px;
        overflow-x: hidden;
    }
    #tellerInp {
        cursor: pointer;
    }
    /* #collectTable {
        margin-top: 3%;
    } */
    #cutOffTable {
        display: none;
    }
    #printBtn {
        width: 7%;
        height: 40px;
        margin-top: 5%;
        margin-right: 2.9%;
        display: none;
    }
    #cutOff {
        cursor: pointer;
    }
    #EwallMsg {
        display: none;
    }
</style>

<p class="contentheader">Print Tellers Daily Collection</p>
<div class="main" onload="setTellerName()">
    <table class="content-table">
        <tr>
            <td style="width: 15%;">
                Teller:
            </td>
            <td class="input-td">
                <input type="text" onclick="showTeller()" value="{{Auth::user()->user_full_name}}" id="tellerInp" placeholder="Select Teller" readonly>
            </td>
        </tr>
    </table>
    <table id="collectTable" class="content-table">
        <tr>
            <td style="width: 15%;">
                Collection Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="date" id="month">
                
            </td>
        </tr>
    </table>
    <table id="cutOffTable" class="content-table">
        <tr>
            <td style="width: 15%;">
                Cutoff Collection: &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="text" id="cutOffColl" style="width: 92.5%;" readonly>
            </td>
            <td style="width: 15%;">
                Total Collection:
            </td>
            <td>
                <input type="text" id="totalColl" style="width: 92.5%;" readonly>
                <input type="text" id="cutOffvalue" hidden>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <div id="printButton"></div>
            </td>
        </tr>
    </table>
    <input type="text" id="tellerId" value="{{Auth::user()->user_id}}" hidden>
    <input type="text" id="trueId" value="{{Auth::user()->user_id}}" hidden>
    <input type="text" id="tellerName" value="{{Auth::user()->user_full_name}}" hidden>
</div>

<div id="teller" class="modal">
    <div class="modal-content" style="margin-top: 100px; width: 30%; height: 435px; margin-top: -1%;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Teller Lookup</h3>
            <span href = "#teller" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div>
                <table style="width: 100%; color: black;">
                    <tr>
                        <td>
                            Search
                        </td>
                        <td>
                            <input type="text" id="search" placeholder="Search Teller">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="tellerDiv"> </div>
        </div>
    </div>
</div>

<script>
    var cutOff = "";
    function showTeller(){
        const request = new XMLHttpRequest();
        var showTellers = "{{route('show.tellers')}}";
        request.open('GET', showTellers, true);
        request.send();

        request.onload = function(){
            if(this.status == 200) {
                document.querySelector("#search").value="";
                var response = JSON.parse(this.responseText);
                var teller = response.Tellers;
                var output = "<table id='tellerTbl'> <tr id='thead'> <td> &nbsp;  Emp No </td>";
                output += "<td> Teller </td> </tr>";
                
                for(var a in teller){
                    output += "<tr onclick='selectTeller(this)' id='" + teller[a].user_id + "' name='" + teller[a].user_full_name + "'";
                    output += "code='" + teller[a].emp_no + "' class='tbody'> <td>&nbsp;&nbsp;&nbsp;" + teller[a].emp_no + "</td>";
                    output += "<td>&nbsp;" + teller[a].user_full_name + "</td></tr>";
                }
                output += "</table>";

                document.querySelector('.tellerDiv').innerHTML= output;
            }
        }
        document.querySelector('#teller').style.display = "block";
    }

    var search = document.querySelector("#search");
    search.addEventListener("change", function(){
        
        if(search.value.length > 0){ 
            const request = new XMLHttpRequest();
            var toSearch = search.value;
            var route = "{{route('search.tellers',['search'=>':par'])}}"
            request.open('GET', route.replace(':par', toSearch), true);
            request.send();

            request.onload = function(){
                if(this.status == 200) { 
                    var response = JSON.parse(this.responseText);
                    var teller = response.Tellers;
                    
                    if(teller != ""){
                        var output = "<table id='tellerTbl'> <tr id='thead'> <td> &nbsp;  Emp No </td>";
                        output += "<td> Teller </td> </tr>";
                        
                        for(var a in teller){
                            output += "<tr onclick='selectTeller(this)' id='" + teller[a].user_id + "' name='" + teller[a].user_full_name + "'";
                            output += "code='" + teller[a].emp_no + "' class='tbody'> <td>&nbsp;&nbsp;&nbsp;" + teller[a].emp_no + "</td>";
                            output += "<td>&nbsp;" + teller[a].user_full_name + "</td></tr>";
                        }
                        output += "</table>";

                        document.querySelector('.tellerDiv').innerHTML= output;
                    } else {
                        var output = "<table style='color: black; width: 100%;'>"; 
                        output += "<br><br><tr> <td style='text-align: center; font-size: 22px;'>";
                        output += "No Teller Found! </td> </tr> </table>";
                        document.querySelector('.tellerDiv').innerHTML = output;
                    }
                }
            }
        } else {
            showTeller();
        }
    })

    function selectTeller(a){
        var tellerId = a.id;
        var tellerName = a.getAttribute('name');
        var tellerCode = a.getAttribute('code');

        document.querySelector("#tellerInp").value = tellerCode + " - " + tellerName;
        document.querySelector("#tellerId").value = tellerId;
        document.querySelector("#tellerName").value = tellerName;
        document.querySelector("#teller").style.display = "none";
        document.querySelector("#collectTable").style.display = "block";
    }

    var billMonth = document.querySelector("#month");
    billMonth.addEventListener("change", function(){
        if(billMonth.value !== "") {
            var xhr = new XMLHttpRequest();
            
            var user = document.querySelector("#tellerId").value;

            const toSend = {
                "user_id": user,
                "date": billMonth.value
            }
            var toSendJSONed = JSON.stringify(toSend);

            var token = document.querySelector('meta[name="csrf-token"]').content;
            var checkTransact = "{{route('check.pay.bills.CutOff')}}";
            xhr.open('POST', checkTransact, true);
            xhr.setRequestHeader("Accept", "application/json");
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
            xhr.setRequestHeader("X-CSRF-TOKEN", token);
            
            xhr.send(toSendJSONed);

            xhr.onload = function(){
                if(this.status == 200){ 
                    
                    const toPost = {
                        "user_id": user,
                        "date": billMonth.value,
                        "cutoff": 1
                    }
                    var JSONed = JSON.stringify(toPost);

                    var req = new XMLHttpRequest();
                    var token = document.querySelector('meta[name="csrf-token"]').content;
                    var setAmount = "{{route('set.collection.teller.dcr.amount')}}";
                    req.open('POST', setAmount, true);
                    req.setRequestHeader("Accept", "application/json");
                    req.setRequestHeader("Content-Type", "application/json");
                    req.setRequestHeader("Access-Control-Allow-Origin", "*");
                    req.setRequestHeader("X-CSRF-TOKEN", token);

                    req.send(toSendJSONed);

                    req.onload = function(){
                        if(this.status == 200){
                            var data = JSON.parse(this.responseText);
                            var cutOffAmnt = data.Cut_Off_Amount;
                            var totalCollAmnt = data.Total_Collection_Amount;
                            var eWall = data.Ewallet_Collection_Amount;
                            
                            if(totalCollAmnt > 0 && cutOffAmnt > 0){
                                document.querySelector("#cutOffColl").value = cutOffAmnt.toLocaleString("en-US", { minimumFractionDigits: 2 });
                                document.querySelector("#totalColl").value = totalCollAmnt.toLocaleString("en-US", { minimumFractionDigits: 2 });
                                document.querySelector("#cutOffvalue").value = 0;
                                var print = "<button id='printBtn' onclick='printDCR()'> Reprint </button>";
                                document.querySelector("#printButton").innerHTML = print; 
                                document.querySelector("#printBtn").style.display = "block";
                                document.querySelector("#cutOffTable").style.display = "block";
                            } else {
                                document.querySelector("#cutOffColl").value = cutOffAmnt.toLocaleString("en-US", { minimumFractionDigits: 2 });
                                document.querySelector("#totalColl").value = eWall.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "   (E-Wallet Only)";
                                document.querySelector("#cutOffvalue").value = 0;
                                var print = "<button id='printBtn' onclick='printDCR()'> Reprint </button>";
                                document.querySelector("#printButton").innerHTML = print; 
                                document.querySelector("#printBtn").style.display = "block";
                                document.querySelector("#cutOffTable").style.display = "block";
                            }
                            
                        } 
                    }
                } else{
                    const toPost = {
                        "user_id": user,
                        "date": billMonth.value,
                        "cutoff": 0
                    }
                    var toPostJSONed = JSON.stringify(toPost);

                    var req = new XMLHttpRequest();
                    var token = document.querySelector('meta[name="csrf-token"]').content;
                    var setAmount = "{{route('set.collection.teller.dcr.amount')}}";
                    req.open('POST', setAmount, true);
                    req.setRequestHeader("Accept", "application/json");
                    req.setRequestHeader("Content-Type", "application/json");
                    req.setRequestHeader("Access-Control-Allow-Origin", "*");
                    req.setRequestHeader("X-CSRF-TOKEN", token);

                    req.send(toPostJSONed);

                    req.onload = function(){
                        if(this.status == 200){
                            var data = JSON.parse(this.responseText);
                            var cutOffAmnt = data.Cut_Off_Amount;
                            var totalCollAmnt = data.Total_Collection_Amount;
                            var eWall = data.Ewallet_Collection_Amount;

                            if(cutOffAmnt > 0 && totalCollAmnt > 0) {
                                document.querySelector("#cutOffColl").value = 0.00000;
                                document.querySelector("#totalColl").value = 0.00000;
                                document.querySelector("#cutOffvalue").value = 1;

                                var trueUser = document.querySelector("#trueId").value;
                                if(trueUser !== '10') {
                                    var print = '<input type="checkbox" id="cutOff">';
                                    print += '<label for="cutOff"> &nbsp; Cut Off Collection </label>';
                                    print += "<button id='printBtn' onclick='printDCR()'> Print </button>";
                                    document.querySelector("#printButton").innerHTML = print; 
                                }
                                
                                
                                document.querySelector("#cutOffTable").style.display = "block";

                                var cutOff = document.querySelector("#cutOff");
                                cutOff.addEventListener("click", function(){
                                    if(cutOff.checked == true){
                                        document.querySelector("#totalColl").value = totalCollAmnt.toLocaleString("en-US", { minimumFractionDigits: 2 });
                                        document.querySelector("#printBtn").style.display = "block";
                                    } else {
                                        document.querySelector("#totalColl").value = 0.00000;
                                        document.querySelector("#printBtn").style.display = "none";
                                    }
                                })
                            } else {
                                if(eWall > 0){
                                    document.querySelector("#cutOffColl").value = 0.00000;
                                    document.querySelector("#totalColl").value = 0.00000;
                                    document.querySelector("#cutOffvalue").value = 1;
                                    var print = '<input type="checkbox" id="cutOff">';
                                    print += '<label for="cutOff"> &nbsp; Cut Off Collection </label>';
                                    print += "<button id='printBtn' onclick='printDCR()'> Print </button>";
                                    document.querySelector("#printButton").innerHTML = print;
                                    document.querySelector("#cutOffTable").style.display = "block";

                                    var cutOff = document.querySelector("#cutOff");
                                    cutOff.addEventListener("click", function(){
                                        if(cutOff.checked == true){
                                            document.querySelector("#totalColl").value = eWall.toLocaleString("en-US", { minimumFractionDigits: 2 }) + "  (E-Wallet only)";
                                            document.querySelector("#printBtn").style.display = "block";
                                        } else {
                                            document.querySelector("#totalColl").value = 0.00000;
                                            document.querySelector("#printBtn").style.display = "none";
                                        }
                                    })
                                } else {
                                    Swal.fire({
                                        title: 'Caution!',
                                        text: '"No Collection found"',
                                        icon: 'error',
                                        confirmButtonText: 'Ok'
                                    })
                                }
                                document.querySelector("#tellerId").value = user;
                            }
                        } 
                    }
                }
            }
        } else {
            document.querySelector("#cutOffTable").style.display = "none";
        }
    })

    function printDCR(){
        var cutOff = document.querySelector("#cutOffvalue").value;
        var userId = document.querySelector("#tellerId").value;
        var date = document.querySelector("#month").value;
        var teller = document.querySelector("#tellerName").value;
        
        const toSend = {
            "teller_id": userId,
            "date": date,
            "cutOff": cutOff,
            "teller": teller,
            "user_id": "{{Auth::user()->user_id}}"
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_tellers_daily_collection_report")}}';
        window.open($url);
        window.reload();
    }
</script>
@endsection