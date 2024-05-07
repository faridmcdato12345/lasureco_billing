@extends('Layout.master')
@section('title', 'Enter Meter Readings')
@section('content')

@section('stylesheet')
    <style>
        body{
            overflow-y: hidden;
        }

        input{
            font-size: 0.90vw !important;
        }

        select {
            font-size: 0.90vw !important;
        }

        table tr th {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            z-index: 2;
            text-align: center;
            padding-left: 15px;
            padding-right: 15px;
            color: white;
            background: #0366fc;    
        }

        #tableFieldFinding tr:hover{
            transition: background 1s;
            background-color: #5B9BD5;
            cursor: pointer;
        }

        #consTable tr input:focus{
            background: rgb(232, 243, 137) !important;
            text-align: center;
        }

        #consTable tr:hover{
            transition: background 1s;
            background: gray;
            cursor: pointer;
        }

        .headLabel {
            background-color: #0366fc; 
            color: white;
            font-weight: bold;
            padding: 1%;
            margin-bottom: 5px;
            text-align: center;
            letter-spacing: 3px;
            font-size: 1.5vh;
            border-radius: 0px 0px 10px 10px;
        }

    </style>
@endsection

        


<p class="contentheader">Enter Meter Readings </p>
<div class="container" style="width: 90%">
  <div class="row">
    <div class="col-sm">
      <label for="">Route:</label>
      <input autocomplete="off" type="text" id="routeInp" onclick="showRoutes()" class="input-check form-control input-sm" href="#route" name="Route" placeholder="Select Route" disable>
      <input type="hidden" id="routeId">
    </div>
    <div class="col-sm">
        <label for="">Billing Period:</label>
        <input id="billingPeriod" class="input-check form-control input-sm" oninput="checkRates(this.value)" name="Billing Period" type="month" required>
    </div>
    <div class="col-sm">
        <label for="">Reading Date:</label>
        <input class="input-check form-control input-sm" id="readingDate" name="Reading Date" type="date">
    </div>
    <div class="col-sm">
        <label for="">Meter Reader:</label>
        <input autocomplete="off" id="#mR" type="text" class="input-check form-control input-sm"  onclick="getMeterReaders()" name="Meter Reader" placeholder="Select Meter Reader">
    </div>
    <div class="col-sm">
        <label for="">Consumers</label>
        <input type="button" value="view" class="btn btn-primary form-control input-sm " onclick="validateInputs()">
        <!-- <i class="fas fa-search"></i> -->
    </div>
  </div>
  <div id="msgs" class="alert alert-warning mt-1" role="alert" hidden>
    
  </div>
</div>
<hr style="margin: 5px">
<div class="container mb-5" style="width: 96%; font-size: 12px; background:white;" id="mainContent" hidden>
    <div class="row" style="text-align:left; color: black; background: white; height: 100%">
        <div class="col-6">
            <div class="row p-2">
                <div class="col-1 font-weight-bold">
                    NAME:
                </div>
                <div id="name" class="col-7">
                    -
                </div>
                <div class="col-1 font-weight-bold">
                    S/N:
                </div>
                <div id="serial" class="col-3">
                    -
                </div>
            </div>
            <div class="row p-2">
                <div class="col-1 font-weight-bold">
                    TYPE:
                </div>
                <div id="constype" class="col-3">
                    -
                </div>
                <div class="col-1 font-weight-bold">
                    STATUS:
                </div>
                <div id="status" class="col-3">
                    -
                </div>
                <div class="col-2 font-weight-bold">
                    MULTIPLIER:
                </div>
                <div id="multiplier" class="col-1">
                    -
                </div>
            </div>
 
            <div class="row">
                    <button id="btnLedger" class="btn btn-secondary col-sm m-2" style="font-size: 1.5vh; height: 4.2vh" onclick="consLedger()"> Consumer Ledger <label style="color:yellow">(Alt+L)</label></button>
                    <select class="col-sm m-2 form-select" style="font-size: 1.5vh; height: 4.2vh" id="toPrint">
                        <option value="0" selected>- select option -</option>
                        <option value="1">Edit List</option>
                        <option value="2">Unbilled and Unreaded List</option>
                    </select>
                    <button class="btn btn-primary col-2 m-2" onclick="print()" style="font-size: 1.5vh; height: 4.2vh">Print</button>
                    <!-- <button class="modal-button" href="#ConsumerDetails" style="color: white; background-color: rgb(23, 108, 191); margin-top:5px; font-size: 12px; border-radius: 3px;"> Consumer Details </button> -->
            </div>
            <!-- <hr> -->
            <div class="row" id="test1">
                <div id="consumers" style="overflow-y:scroll; height: 30vh; width: 98%">
                
                </div>
            </div>
            <!-- <hr> -->
            <div class="row m-1" style="vertical-align: center">
                <button id="btnRatesDtlChrge" type="button" class="btn btn-success col-6 p-2" style="font-size: 1em;" data-toggle="modal" data-target="#billRates" style="font-size: 85%; padding: 2px" disabled>
                    Rates & Detailed Charges <label style="color:yellow; ">(Alt+R)</label>
                </button>
                <button id="btnOverride" type="button" class="btn btn-warning col-6 p-2" style="font-size: 1em;" onclick="getFieldFindings(1)" data-toggle="modal" data-target="#override" style="font-size: 85%; padding: 2px" disabled>
                    Override <label style="color:red; " >(Alt+O)</label>
                </button>
            </div>
            <div class="row m-1" style="vertical-align: center">
                <button id="btnCancelRead" type="button" class="btn btn-danger col-6 p-2" style="font-size: 1em;" onclick="cancelRead()" style="font-size: 85%; padding: 2px" disabled>
                    Cancel Read <label style="color:yellow; ">(Alt+C)</label>
                </button>
                <button id="btnBulkOverride" type="button" onclick="getFieldFindings(2)" class="btn btn-primary col-6 p-2" style="font-size: 1em;" data-toggle="modal" data-target="#bulkOverride" style="font-size: 85%; padding: 2px" disabled>
                    Bulk Override <label style="color:yellow; ">(Alt+B)</label>
                </button>
            </div>
            <div class="row p-2">
                <input id="count" class="p-1 text-center" readonly>
            </div>
        </div>
        <div class="col-6" style="border-left: solid; border-right: solid" >
            <div class="row">
                <div class="col-sm" style="border-right: solid;">
                    <p class="headLabel">BASIC  CHARGE  INFO</p>
                    <table style="text-align: left" width="100%">
                        <tr>
                            <div class="row pb-1">
                                <div class="col-sm">Pres. Reading</div>
                                <div class="labels col-sm"><input class="form-control input-sm" id="presReadVal" type="text" placeholder="0" style="height: 20px" readonly></div>
                            </div>
                        </tr>
                        <tr>
                            <div class="row pb-1">
                                <div class="col-sm">Prev. Reading</div>
                                <div class="labels col-sm"><input class="form-control input-sm" id="prevReadVal" type="number" min="0" placeholder="0" style="height: 20px" ondblclick="readInput(this)" readonly></div>
                            </div>
                        </tr>
                        <tr>
                            <div class="row pb-1">
                                <div class="col-sm">KWH used</div>
                                <div class="labels col-sm"><input class="form-control input-sm" id="conKwhVal" type="text" placeholder="0" style="height: 20px" readonly></div>
                            </div>
                        </tr>
                        <tr>
                            <div class="row pb-1">
                                <div class="col-sm">Prev. Month KWH used</div>
                                <div class="labels col-sm"><input class="form-control input-sm" id="prevKwhVal" type="text" placeholder="0" style="height: 20px" readonly></div>
                            </div>
                        </tr>
                        <tr><td colspan="2"><label id="lifeline" style="color: white; display: block; text-align: center; font-size: 1.5vh; margin: 0px;">LIFE LINE</label></td></tr>
                    </table>
                </div>
                <div class="col-sm">
                    <p class="headLabel">DEMAND  CHARGE  INFO</p>
                    <table style="text-align: left" width="100%">
                        <tr>
                            <div class="row pb-1">
                                <div class="col-sm">Present Reading</div>
                                <div class="labels col-sm"><input class="form-control input-sm" id="presDmdReadVal" type="text" placeholder="0" style="height: 20px" readonly></div>
                            </div>
                        </tr>
                        <tr>
                            <div class="row pb-1">
                                <div class="col-sm">Previous Reading</div>
                                <div class="labels col-sm"><input class="form-control input-sm" id="prevDmdReadVal" type="text" placeholder="0" style="height: 20px" readonly></div>
                            </div>
                        </tr>
                        <tr>
                            <div class="row pb-1">
                                <div class="col-sm">Demand KWH Used</div>
                                <div class="labels col-sm"><input class="form-control input-sm" id="DmdKwhUVal" type="text" placeholder="0" style="height: 20px" readonly></div>
                            </div>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- <hr> -->
            <div class="row">
                <div class="col-sm" style="border-right: solid; padding-bottom: 2%">
                    <p class="headLabel">CHARGES</p>
                    <table style="text-align: left" width="100%">
                    <tr>
                        <div class="row pb-1">
                            <div class="col-sm">Generation Charges</div>
                            <div class="labels col-sm"><input class="form-control input-sm pr-0" id="genchrges" type="text" placeholder="0.00" style="height: 20px" readonly></div>
                        </div>
                    </tr>
                    <tr>
                        <div class="row pb-1">
                            <div class="col-sm">Transmission Charges</div>
                            <div class="labels col-sm"><input class="form-control input-sm pr-0" id="transchrges" type="text" placeholder="0.00" style="height: 20px" readonly></div>
                        </div>
                    </tr>
                    <tr>
                        <div class="row pb-1">
                            <div class="col-sm">Distribution Charges</div>
                            <div class="labels col-sm"><input class="form-control input-sm pr-0" id="distchrges" type="text" placeholder="0.00" style="height: 20px" readonly></div>
                        </div>
                    </tr>
                    <tr>
                        <div class="row pb-1">
                            <div class="col-sm">Government Charges</div>
                            <div class="labels col-sm"><input class="form-control input-sm pr-0" id="govtchrges" type="text" placeholder="0.00" style="height: 20px" readonly></div>
                        </div>
                    </tr>
                    <tr>
                        <div class="row pb-1">
                            <div class="col-sm">Other Charges/Adj's</div>
                            <div class="labels col-sm"><input class="form-control input-sm pr-0" id="otherchrges" type="text" placeholder="0.00" style="height: 20px" readonly></div>
                        </div>
                    </tr>
                    <tr>
                        <div class="row">
                            <div class="col-sm">Value Added Tax (VAT)</div>
                            <div class="labels col-sm"><input class="form-control input-sm pr-0" id="vat" type="text" placeholder="0.00" style="height: 20px" readonly></div>
                        </div>
                    </tr>
                    </table>
                </div>
                <div class="col-sm">
                    <p class="headLabel">WITH  HOLDING  TAX</p>
                    <table style="text-align: left" width="100%">
                        <tr>
                            <div class="row pb-1">
                                <div class="col-sm">LGU 5%</div>
                                <div class="labels col-sm"><input class="form-control input-sm" id="lgu5val" type="text" placeholder="0.00" style="height: 20px" readonly></div>
                            </div>
                        </tr>
                        <tr>
                            <div class="row pb-1">
                                <div class="col-sm">LGU 2%</div>
                                <div class="labels col-sm"><input class="form-control input-sm" id="lgu2val" type="text" placeholder="0.00" style="height: 20px" readonly></div>
                            </div>
                        </tr>
                    </table>
                    <p class="headLabel" style="margin-top: 10px">DISCOUNT</p>
                    <table style="text-align: left" width="100%">
                        <tr>
                            <div class="row pb-1">
                                <div class="col-sm">MASJEED</div>
                                <div class="labels col-sm"><input class="form-control input-sm" id="masjeedVal" type="number" onfocusout="discount(1)" placeholder="0.00" style="height: 20px"></div>
                            </div>
                        </tr>
                        <tr>
                            <div class="row">
                                <div class="col-sm">SENIOR CITIZEN</div>
                                <div class="labels col-sm"><input class="form-control input-sm" id="seniorVal" type="number" onfocusout="discount(2)" placeholder="0.00" style="height: 20px"></div>
                            </div>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- <hr> -->
            <div class="row">
                <div class="col-sm" style="padding-bottom: 2%; border-right: solid">
                    <p class="headLabel">CURRENT  BILLS</p>
                    <table style="text-align: left" width="100%">
                    <tr>
                        <div class="row pb-1">
                            <div class="col-sm">CURRENT BILLS</div>
                            <div class="labels col-sm"><input class="form-control input-sm" id="curbill" type="text" placeholder="0.00" style="height: 20px" readonly></div>
                        </div>
                    </tr>
                    <tr>
                        <div class="row pb-1">
                            <div class="col-sm">TOTAL ARREARS</div>
                            <div class="labels col-sm"><input class="form-control input-sm" id="totalArrears" type="text" placeholder="0.00" style="height: 20px" readonly></div>
                        </div>
                    </tr>
                    <tr>
                        <div class="row pb-1">
                            <div class="col-sm">SURCHARGE</div>
                            <div class="labels col-sm"><input class="form-control input-sm" id="surChrge" type="text" placeholder="0.00" style="height: 20px" readonly></div>
                        </div>
                    </tr>
                    <tr>
                        <div class="row pb-1">
                            <div class="col-sm">GOV'T SUBSIDY</div>
                            <div class="labels col-sm"><input class="form-control input-sm" id="subsidy" type="text" placeholder="0.00" style="height: 20px" readonly></div>
                        </div>
                    </tr>
                    <tr>
                        <div class="row pb-1">
                            <div class="col-sm" style="width: 50%;">E-WALLET AMOUNT</div>
                            <div class="labels col-sm"><input class="form-control input-sm" id="ewallet" type="text" placeholder="0.00" style="height: 20px" readonly></div>
                        </div>
                    </tr>
                    </table>
                </div>
                <div class="col-sm" style="padding-bottom: 2%;">
                    <table style="text-align: left" width="100%">
                    <tr>
                        <td colspan="2" style="text-align: center; padding-top: 40px;"><h5>TOTAL AMOUNT</h5></td>
                    </tr>
                    <tr>
                        <td class="labels"><input class="form-control input-sm" style="font-weight: bold; font-size: large !important" id="totalBill" type="text" placeholder="0.00" readonly></td>
                    </tr>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>
@include('include.modal.routemodal')
@include('include.modal.meterReaderModal')
@include('include.modal.consLedger')

<!-- Modal -->
<div class="modal fade" id="billRates" tabindex="-1" data-backdrop="false" role="dialog" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-xl" style="top: -20%">
    <div class="modal-content" style="height: auto">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">RATES AND DETAILED CHARGES</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="color: black">
            <div class="row" >
                <h5 name="consName" class="col-8" id="consName"></h5>
                <h5 name="consKwh" class="col-2" id="consKwh"></h5>
                <h5 name="consLifeline" class="col-2" id="consLifeline"></h5>
            </div>
            <div class="row mb-3">
                <label for="consName" class="col-8">Consumer Name</label>
                <label for="consKwh" class="col-2">KWH Consumed</label>
                <label for="consLifeline" class="col-2">Lifeline Discount</label>
            </div>
            <hr>
             <div class="row">
                 <div class="col-6" style="border-right: solid">
                     <div id="chrges1" class="row">
                     </div> 
                 </div>
                 <div class="col-6">
                     <div class="row" id="chrges2">
                     </div>
                 </div>
             </div>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="override" tabindex="-1" data-backdrop="false" data-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content" style="margin-top: 10px; width: auto; height: auto;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">OVERRIDE CONSUMER READING</h5>
        <button type="button" class="btn-close" data-dismiss="modal" onclick="document.onkeydown = checkKey" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="color: black">
             <div class="row">
                <h5 style="text-align: center">FIELD FINDINGS</h5>
                <div class="row"><input type="text" class="m-2 form-control input-sm" id="searchFieldFinding" autofocus placeholder="Search Field Findings" onchange="searchFf(1)"></div>
                <div id="fieldFinding" style="overflow-y:scroll; height: 200px;">

                </div>
             </div>
             <div class="row mt-5">
                <div id="fieldFinding2">

                </div>
             </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="overrideKWH()">Override</button>
        <button type="button" id="btnOverrideClose" class="btn btn-warning" data-dismiss="modal" onclick="document.onkeydown = checkKey">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="bulkOverride" tabindex="-1" data-backdrop="false" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content" style="margin-top: 10px; width: auto; height: auto;">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">BULK READING</h5>
          <button type="button" class="btn-close" data-dismiss="modal" onclick="document.onkeydown = checkKey" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="color: black">
                <div class="row">
                    <h5 style="text-align: center">FIELD FINDINGS</h5>
                    <div class="row"><input type="text" class="m-2 form-control input-sm" id="bulkSearchFF" autofocus placeholder="Search Field Findings" onchange="searchFf(2)"></div>
                    <div id="bulkFF" style="overflow-y:scroll; height: 200px;">

                    </div>
                </div>
                <div class="row mt-5">
                    <div id="bulkFF2">

                    </div>
                </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="bulkOverride()">Apply</button>
          <button type="button" id="btnOverrideClose" class="btn btn-warning" data-dismiss="modal" onclick="document.onkeydown = checkKey">Close</button>
        </div>
      </div>
    </div>
  </div>

<script>

var storage = new Object();
var storage2 = new Object();
var bulk = new Object();
bulk.ff = null;
storage2.stat = false;
storage2.stat2 = false;
storage2.stat3 = false;
var alert = document.getElementById('msgs');

document.getElementById('test1').addEventListener("keydown", function(event){
    // console.log(event.keyCode)
    if (event.altKey && event.keyCode == 67) {
        // console.log('cancel');
        document.getElementById("btnCancelRead").click();
    }else if(event.altKey && event.keyCode == 82){
        // console.log('detailed rates');
        document.getElementById('btnRatesDtlChrge').click();
    }else if(event.altKey && event.keyCode == 79){
        // console.log('override');
        document.getElementById('btnOverride').click();
    }else if(event.altKey && event.keyCode == 76){
        // console.log('ledger');
        document.getElementById('btnLedger').click();
    }else if(event.altKey && event.keyCode == 66){
        // console.log('bulk override');
        document.getElementById('btnBulkOverride').click();
    }
},true);

function setRoute(e){
    var routeCode = e.getAttribute('code');
    var routeName = e.getAttribute('name');
    var routeId = e.id;
    var route = routeCode + " - " + routeName;
    storage2.routeCode = routeCode;
    storage2.routeId = routeId;
    document.querySelector("#searchRoute").value = "";
    document.querySelector('#routeInp').value = route;
    document.querySelector('#routeId').value = routeId;
    document.querySelector("#routeCodes").style.display = "none";
}

function setMeterReader(rowSelected){
	    var hide = document.querySelector('#meterReader');
        hide.style.display="none";
        document.getElementById('#mR').value = rowSelected.childNodes[0].innerHTML;
        storage.meterReader = rowSelected.id;
        bulk.meterReader = rowSelected.id;
}

function print(){
    var print = parseInt(document.getElementById('toPrint').value);
    if(print == 1){
        const toSend = {
            'routeId': storage2.routeId,
            'billPeriod': storage2.billPeriod
        }
        // console.log("EDIT LIST 1",JSON.stringify(toSend));
        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_edit_billing_list")}}'
        window.open($url);
    }else if(print == 2){
        const toSend = {
            'month': storage2.billPeriod,
            'routeFrom': storage2.routeId,
            // 'routeTo': storage2.routeCode
        }

        localStorage.setItem('data', JSON.stringify(toSend));
        $url = '{{route("print_summary_of_unread_unbilled_consumer")}}'
        window.open($url);
    }
}
/*--------------------------------------END GET & SET METER READER------------------------------------------------------*/
/*--------------------------------------- GET CONSUMERS------------------------------------------------ */
function getconsumers(){
    if(storage.rateAvailable == 1){      
        xhr = new XMLHttpRequest;
        var route = "{{route('show.emr.consumers')}}";
        xhr.open('POST',route,true);
        xhr.setRequestHeader('Accept', 'Application/json');
        xhr.setRequestHeader('Content-Type', 'Application/json');

        var rID = document.querySelector('#routeId').value;
        var bp = document.querySelector('#billingPeriod').value;
        storage.billPeriod = bp;
        const prerequisite = {routeID:rID, billingPeriod:bp};
        var a = JSON.stringify(prerequisite);
        xhr.send(a);

       
        xhr.onload = function(){
            if(this.status == 200){
                var data2 = JSON.parse(this.responseText);
                var check = "";
                var output1 = "";
                output1 += '<table id="consTable" style="margin: 1%;">';
                output1 +="<tr id='th_0'><th style='width: 14%;'>Seq #</th><th>Acct#</th><th>Pres. Reading</th><th style='width: 12%'>Wrap</th><th style='width: 10%;'>Digit</th><th style='width: 15%;'>Dem. Reading</th><th>Exist</th></tr>";
                // console.log(data2.data);
                for(let i in data2.data){
                    acct_no = (data2.data[i].acctNo != null) ? (data2.data[i].acctNo).toString().slice(6) : 0;
                    output1 +=  '<tr id="row'+i+'" lgu2="'+data2.data[i].lgu2+'" lgu5="'+data2.data[i].lgu5+'" ondblclick="setConsumer(this.id)"><td style="padding: 5px;"><input class="form-control input-sm pr-0" type="number" name="seqno" value="'+data2.data[i].seqNo+'" readonly></td>';
                    output1 += '<td><input class="form-control input-sm" id="'+(data2.data[i].acctNo)+'" value="'+ acct_no +'" type="text" readonly>'+
                                    '<input id="'+data2.data[i].consID+'^'+
                                    data2.data[i].fullName+'^'+data2.data[i].status+'^'+
                                    data2.data[i].multi+'^'+data2.data[i].consType+'^'+
                                    data2.data[i].serial+'^'+data2.data[i].consType_id+'" type="hidden"></td>';
                    if(i == 0){
                        output1 += '<td id="start"><input class="form-control input-sm" id="presRead'+i+'" type="number" step="1" placeholder="0" value="'+data2.data[i].presRead+'"></td>';
                    }else{
                        output1 += '<td><input class="form-control input-sm" id="presRead'+i+'" type="number" step="1" placeholder="0" value="'+data2.data[i].presRead+'"></td>';
                    }
                    var selected = (data2.data[i].wrap > 0) ? '<option value="0">No</option><option value="1" selected>Yes</option>' : '<option value="0" selected>No</option><option value="1">Yes</option>';
                    
                    output1 += '<td><select class="form-control" id="row'+i+'" onchange="wrap(this.value, this.id)">'+
                            selected+'</select></td>'+
                        '<td><input class="form-control input-sm" type="number" min="0" maxlength="1"  placeholder="0" value="'+data2.data[i].digit+'"></td>'+
                        '<td><input class="form-control input-sm" type="number" step="0.01" id="presDem'+i+'" placeholder="0" value="'+data2.data[i].presDmd+'"></td>'+
                        '<td style="text-align: center"><input type="checkbox" id="'+data2.data[i].mrID+'" '+data2.data[i].exist+' disabled></td></tr>';
                        // '<td><input type="checkbox" id="'+data2.data[i].mrID+'" '+data2.data[i].exist+' disabled></td></tr>';
                }
                    output1 += '</table>';
            }
            
            document.querySelector('#consumers').innerHTML = output1;
            document.getElementById('count').value = 'COUNT : '+data2.count;
            nav(document.querySelector('#start'));
        }

    }
    
}
/*--------------------------------------- END GET CONSUMERS------------------------------------------------ */
function wrap(selected, rowid){
    storage.data = document.getElementById(rowid);
    var a = storage.data.childNodes;
    if(selected == 0 && a != null){
        // a[2].childNodes[0].value = null;
        a[4].childNodes[0].value = null;
    }
}
/*--------------------------------------- SET CONSUMERS AND BILL RATES ------------------------------------------------ */
function setConsumer(row){
    
    if(storage.rateAvailable == 1 && storage2.stat == false){
        storage.data = document.getElementById(row);
        storage.row = row;
        storage.lgu2 = document.getElementById(row).getAttribute('lgu2');
        storage.lgu5 = document.getElementById(row).getAttribute('lgu5');
        document.getElementById('btnRatesDtlChrge').disabled = false;
        document.getElementById('btnOverride').disabled = false;
        document.getElementById('btnCancelRead').disabled = false;
        document.getElementById('btnBulkOverride').disabled = false;
        storage2.stat = true;
        setTimeout(function() {
            storage2.stat = false;
        }, 1500);
        // console.log(storage.data.childNodes);
        set(storage.data.childNodes);
        var c_row = document.getElementById('start');
        // console.log("clicked row : ",document.getElementById(row).childNodes[2]);
        // console.log("row with start id : ",c_row)
        if(c_row != null){
            c_row.parentElement.style.backgroundColor = ''; 
            c_row.removeAttribute('id');
            document.getElementById(row).childNodes[2].setAttribute('id','start');
            nav(document.querySelector('#start'));
        }
    }
}
/*--------------------------------------- END SET CONSUMERS AND BILL RATES ------------------------------------------------ */

function set(data){
    // event.stopPropagation();
    storage.mrid = (data[6].childNodes[0].id == 'null') ? 0 : data[6].childNodes[0].id;
    // console.log("storage \n");
    // console.log(storage);
    // console.log(data);
    var xhr = new XMLHttpRequest();
    var route = "{{route('get.consumer.record',['mrid'=>':par'])}}";
    xhr.open('GET',route.replace(':par', storage.mrid), true);
    // xhr.open('GET','http://localhost:8000/api/v1/meter_register/getConsumerRecord/'+storage.mrid, true);
    xhr.send();

    xhr.onload = function(){

        if(this.status == 200){
            var res = JSON.parse(this.responseText);
        }

        var tempStore = data[1].childNodes[1].id.split('^');
        document.querySelector('#name').innerHTML = tempStore[1].replaceAll('_', ' ');
        document.querySelector('#serial').innerHTML = tempStore[5];
        document.querySelector('#constype').innerHTML = tempStore[4];
        document.querySelector('#status').innerHTML = tempStore[2];
        document.querySelector('#multiplier').innerHTML = 'x'+tempStore[3];
        
        var refresh = document.querySelectorAll('.labels');
        Array.from(refresh).forEach(function(refresh){
            if(refresh.childNodes[0].nodeName == 'INPUT'){
                refresh.childNodes[0].value = '';
            }else{
                refresh.innerHTML = '';
            }  
        });

        storage.acctNo = data[1].childNodes[0].id;
        storage.fullname = tempStore[1];
        storage.consType = tempStore[6];
        storage.presRead = (data[2].childNodes[0].value == '') ? 0 : Math.round(parseFloat(data[2].childNodes[0].value));
        storage.presDmd = (data[5].childNodes[0].value == '') ? 0 : parseFloat(data[5].childNodes[0].value);
        storage.addEnergy = (res.records == null) ? 0 : res.records['mr_add_energy'];
        storage.consumerID = tempStore[0];
        storage.multiplier = parseInt(tempStore[3]);
        storage.wrap = data[3].childNodes[0].value;
        storage.digits = (data[4].childNodes[0].value == 'null') ? 0 : parseInt(data[4].childNodes[0].value);
        storage.newPrevRead = 0;
        storage.prevEdit = 0;
        storage.prevRead = (res.records != null) ? res.records['mr_prev_reading'] : 0;
        storage.kwhUsed = (res.records != null) ? res.records['mr_kwh_used'] : 0;
        storage.ff = (res.records != null && res.records['ff_id'] > 0) ? res.records['ff_id'] : null;
        storage.newKwh = 0;
        if(res.records != null && storage.ff > 0 && storage.presRead == res.records['mr_pres_reading']){
            storage.override = 1;
        }else{
            storage.override = 0;
            storage.ff = null;
        }
        storage.dateReg = document.getElementById('readingDate').value;
        storage.mosqueDisc = document.getElementById('masjeedVal').value;;
        storage.seniorDisc = document.getElementById('seniorVal').value;;
        document.querySelector('#presReadVal').value = (storage.presRead).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
        document.querySelector('#presDmdReadVal').value = parseFloat(storage.presDmd);
        
        if(storage.rateAvailable == 1){
            // console.log('calling computeRates');
            computeRates();
        }
    }  
}

/*------------------------------------------------ SET BILL RATES --------------------------------------------------------- */
function computeRates(){
    var xhr = new XMLHttpRequest();
    var route = "{{route('compute.emr.rates')}}";
    xhr.open('POST',route,true);
    xhr.setRequestHeader('Accept', 'Application/json');
    xhr.setRequestHeader('Content-Type', 'Application/json');

    // console.log('To compute data');
    // console.log(JSON.stringify({storage}));

    xhr.send(JSON.stringify({storage}));   

    xhr.onload = function(){
        if(this.status == 200){
            var data2 = JSON.parse(this.responseText);
            
            if(data2.data != null){
                // console.log('data2');
                // console.log(data2.data);
                storage.rate = data2.data.rates;
                storage.prevDmd = parseFloat(data2.data.prevDmd);
                storage.dmdKwhUsed = parseFloat(data2.data.dmdKwhUsed);
                storage.lfln_disc = data2.data.lifeline_disc;
                storage.prevRead = data2.data.prevRead;
                document.querySelector('#prevKwhVal').value = (data2.data.prevKwh).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                document.querySelector('#prevReadVal').value = parseFloat(data2.data.prevRead);
                document.querySelector('#totalArrears').value = parseFloat(data2.data.totalArrears).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                document.querySelector('#ewallet').value = parseFloat(data2.data.ewallet).toFixed(2).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
            }else{
                console.log("Consumer do not have previous records!");
            }

            storage.kwhUsed = data2.data.kwhConsumed;
            
            var ll_percent = (data2.data.lifeline != 0) ? data2.data.lifeline : 0;

            document.getElementById('consName').innerHTML = (storage.fullname).replaceAll('_',' ');
            document.getElementById('consKwh').innerHTML = storage.kwhUsed;
            document.getElementById('consLifeline').innerHTML = ll_percent+"%";

            document.querySelector('#prevDmdReadVal').value = data2.data.prevDmd;
            document.querySelector('#DmdKwhUVal').value = data2.data.dmdKwhUsed;
            document.getElementById('conKwhVal').value = storage.kwhUsed;

            if(data2.data.kwhConsumed > 0 && data2.data.kwhConsumed < 26 && storage.consType == 8){
                document.getElementById('lifeline').style.background = 'red';
            }else{
                document.getElementById('lifeline').style.background = 'none';
            }

            document.querySelector('#genchrges').value = (data2.data.genchrges);
            document.querySelector('#transchrges').value = (data2.data.transchrges);
            document.querySelector('#distchrges').value = (data2.data.distchrges);
            document.querySelector('#otherchrges').value = (data2.data.otherchrges);
            document.querySelector('#govtchrges').value = (data2.data.govtchrges);
            document.querySelector('#vat').value = (data2.data.vat);

            if(data2.data.lgu7 > 0){
                document.querySelector('#lgu5val').value = (data2.data.lgu5);
                document.querySelector('#lgu2val').value = (data2.data.lgu2);
            }else if(data2.data.lgu2 > 0){
                document.querySelector('#lgu2val').value = (data2.data.lgu2);
            }else if(data2.data.lgu5 > 0){
                document.querySelector('#lgu5val').value = (data2.data.lgu5);
            }
            storage.curbill = parseFloat(data2.data.curbill);
            document.querySelector('#curbill').value = parseFloat(storage.curbill);
            document.querySelector('#totalBill').value = (data2.data.total+storage.curbill).toFixed(2);
     
        }else if(this.status == 404){
            Swal.fire(
                'NO RATE AVAILABLE',
                'This consumer type has no registered rate on this billing period!',
                'error'
            )
        //    console.log(JSON.parse(this.responseText));
                return;
        }

        var exist = document.getElementById(storage.row).childNodes[6].childNodes[0].hasAttribute('checked');
        // console.log("KWH USED AFTER COMPUTE");
        // console.log(storage.mrid);

        // if(storage.kwhUsed > 0 && storage2.stat2 == false){
        if(storage.kwhUsed >= 0 && storage2.stat2 == false){
            event.stopPropagation();
            if(storage.kwhUsed == 0){
                // Swal.fire(
                //     'CAUTION',
                //     'Kindly override the KWH Used!',
                //     'error'
                // )
            }
            else if(storage.mrid == 0 || storage.mrid == 'null'){
                insertUpdateMeterReg(0);
                // console.log('INSERT');
                // sleep(0.3);  
            }else if(storage.mrid > 0){
                insertUpdateMeterReg(1);
                // console.log('UPDATE');
                // sleep(0.3);
            }
        }

        if(data2.data.compute_stat == 0){
            showRates(data2);
        }

        setTimeout(function() {
            storage2.stat2 = false;
        }, 800);
    };
}
/*--------------------------------------- END SET BIlL RATES -----------------------------------------*/

/*--------------------------------------- INSERT METER REG -----------------------------------------*/
function insertUpdateMeterReg(action){
    
    var xhr = new XMLHttpRequest();
    var route = "";
    // console.log('-----insertUpdate function-----');
    // console.log(storage);
    if(action == 1){
        route = "{{route('update.emr')}}";
        xhr.open('POST',route,true);
        // xhr.open('POST','http://localhost:8000/api/v1/meter_register/update',true);
    }else if(action == 0){
        route = "{{route('save.emr')}}";
        xhr.open('POST',route,true);
        // xhr.open('POST','http://localhost:8000/api/v1/meter_register/save',true);
    }
    
    xhr.setRequestHeader('Accept', 'Application/json');
    xhr.setRequestHeader('Content-Type', 'Application/json');
	xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
    storage.userID = "{{Auth::user()->user_id}}";
    xhr.send(JSON.stringify({storage}));
    xhr.onload = function(){
        if(this.status == 200){
            var rr = JSON.parse(this.responseText);
            var a = document.getElementById(storage.row).childNodes[6].childNodes;   
            if(rr.action == 'insert'){
                a[0].setAttribute("id",rr.id);
                a[0].checked = true;
                storage.mrid = rr.id;
                // console.log(rr);
            }else{
                a[0].checked = true;
                // console.log(rr);
            }
            delete storage.rate;
            delete storage.acctNo;
        }
    }
}
/*--------------------------------------- END METER REG -----------------------------------------*/

/*--------------------------------------- SHOW RATES ----------------------------------------------- */
function showRates(data){
    event.stopPropagation();
    var output = "", output2 = "";

    output += '<h6 style="background: green; color:white">General Charges</h6>';
    output += '<label class="col-4">General System Charge</label>'+
              '<label class="col-4">'+data.uGenChrges.gsc+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uGenChrges.gsc_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Power Act Reduction</label>'+
              '<label class="col-4">'+data.uGenChrges.par+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uGenChrges.par_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Franchise & Beneficiary to Host</label>'+
              '<label class="col-4">'+data.uGenChrges.fbh+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uGenChrges.fbh_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">FOREX Adjustment Charge</label>'+
              '<label class="col-4">'+data.uGenChrges.fac+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uGenChrges.fac_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4" style="background: blue; color:white">SUB-TOTAL (GC)</label>'+
              '<label class="col-4" style="background: blue; color:white">'+(data.uGenChrges.rateSubTotal)+'</label>'+
              '<label class="col-4" style="background: blue; color:white">'+data.uGenChrges.pesoSubTotal+'</label>';
    
   
    output += '<h6 style="background: green; color:white">Transmission Charges</h6>';
    output += '<label class="col-4">Transmission System Charge</label>'+
              '<label class="col-4">'+data.uTransChrges.tsc+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uTransChrges.tsc_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Transmission Demand Charge</label>'+
              '<label class="col-4">'+data.uTransChrges.tdc+'/kW'+'</label>'+
              '<label class="col-4">'+data.uTransChrges.tdc_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">System Loss Charge</label>'+
              '<label class="col-4">'+data.uTransChrges.slc+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uTransChrges.slc_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4" style="background: blue; color:white">SUB-TOTAL (TC)</label>'+
              '<label class="col-4" style="background: blue; color:white">'+(data.uTransChrges.rateSubTotal)+'</label>'+
              '<label class="col-4" style="background: blue; color:white">'+data.uTransChrges.pesoSubTotal+'</label>';  

    output += '<h6 style="background: green; color:white">Distribution Charges</h6>';
    output += '<label class="col-4">Distribution System Charge</label>'+
              '<label class="col-4">'+data.uDistChrges.dsc+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uDistChrges.dsc_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Distribution Demand Charge</label>'+
              '<label class="col-4">'+data.uDistChrges.ddc+'/kW'+'</label>'+
              '<label class="col-4">'+data.uDistChrges.ddc_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Supply Fix Charge</label>'+
              '<label class="col-4">'+data.uDistChrges.sfc+'/cst'+'</label>'+
              '<label class="col-4">'+data.uDistChrges.sfc_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Supply System Charge</label>'+
              '<label class="col-4">'+data.uDistChrges.ssc+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uDistChrges.ssc_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Metering Fix Charge</label>'+
              '<label class="col-4">'+data.uDistChrges.mfc+'/cst'+'</label>'+
              '<label class="col-4">'+data.uDistChrges.mfc_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Metering System Charge</label>'+
              '<label class="col-4">'+data.uDistChrges.msc+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uDistChrges.msc_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4" style="background: blue; color:white">SUB-TOTAL (DC)</label>'+
              '<label class="col-4" style="background: blue; color:white">'+(data.uDistChrges.rateSubTotal)+'</label>'+
              '<label class="col-4" style="background: blue; color:white">'+data.uDistChrges.pesoSubTotal+'</label>';  
    
    output += '<h6 style="background: green; color:white">Other Charges</h6>';
    output += '<label class="col-4">Lifeline Disc./Subs.</label>'+
               '<label class="col-4">'+data.uOtherChrges.lds+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uOtherChrges.lds_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Senior Citizen Disc./Subs.</label>'+
              '<label class="col-4">'+data.uOtherChrges.scd+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uOtherChrges.scd_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Int. Class Cross Subs.</label>'+
              '<label class="col-4">'+data.uOtherChrges.iccs+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uOtherChrges.iccs_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">MCC CAPEX</label>'+
              '<label class="col-4">'+data.uOtherChrges.mcapex+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uOtherChrges.mcapex_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Loan Conodation</label>'+
              '<label class="col-4">'+data.uOtherChrges.lc+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uOtherChrges.lc_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Loan Condonation Fix</label>'+
              '<label class="col-4">'+data.uOtherChrges.lcf+'/cst'+'</label>'+
              '<label class="col-4">'+data.uOtherChrges.lcf_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4" style="background: blue; color:white">SUB-TOTAL (OC)</label>'+
              '<label class="col-4" style="background: blue; color:white">'+(data.uOtherChrges.rateSubTotal)+'</label>'+
              '<label class="col-4" style="background: blue; color:white">'+data.uOtherChrges.pesoSubTotal+'</label>';  

    output2 = '<h6 style="background: green; color:white">Government Charges</h6>';
    output2 += '<label class="col-4">Miss. Elect. (SPUG)</label>'+
              '<label class="col-4">'+data.uGovChrges.spug+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uGovChrges.spug_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Miss. Elect. (RED)</label>'+
              '<label class="col-4">'+data.uGovChrges.red+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uGovChrges.red_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Environment Charge</label>'+
              '<label class="col-4">'+data.uGovChrges.ec+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uGovChrges.ec_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Equality of Taxes & Royalty</label>'+
              '<label class="col-4">'+data.uGovChrges.etr+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uGovChrges.etr_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">NPC Stranded Cons. Cost</label>'+
              '<label class="col-4">'+data.uGovChrges.npcsc+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uGovChrges.npcsc_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">NPC Stranded Debt</label>'+
              '<label class="col-4">'+data.uGovChrges.npcsd+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uGovChrges.npcsd_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">FIT-ALL (Renew)</label>'+
              '<label class="col-4">'+data.uGovChrges.fitAll+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uGovChrges.fitAll_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4" style="background: blue; color:white">SUB-TOTAL (GC)</label>'+
              '<label class="col-4" style="background: blue; color:white">'+(data.uGovChrges.rateSubTotal)+'</label>'+
              '<label class="col-4" style="background: blue; color:white">'+data.uGovChrges.pesoSubTotal+'</label>';  

    output2 += '<h6 style="background: green; color:white">Value Added Tax</h6>';
    output2 += '<label class="col-4">Generation VAT</label>'+
              '<label class="col-4">'+data.uVat.genvat+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uVat.genvat_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Power Act Red. VAT</label>'+
              '<label class="col-4">'+data.uVat.parvat+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uVat.parvat_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Transmission System VAT</label>'+
              '<label class="col-4">'+data.uVat.tsvat+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uVat.tsvat_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Transmission Demand VAT</label>'+
              '<label class="col-4">'+data.uVat.tdvat+'/kW'+'</label>'+
              '<label class="col-4">'+data.uVat.tdvat_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">System Loss VAT</label>'+
              '<label class="col-4">'+data.uVat.slvat+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uVat.slvat_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Distribution System VAT</label>'+
              '<label class="col-4">'+data.uVat.dsvat+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uVat.dsvat_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Distribution Demand VAT</label>'+
              '<label class="col-4">'+data.uVat.ddvat+'/kW'+'</label>'+
              '<label class="col-4">'+data.uVat.ddvat_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Supply Fix VAT</label>'+
              '<label class="col-4">'+data.uVat.sfvat+'/cst'+'</label>'+
              '<label class="col-4">'+data.uVat.sfvat_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Supply Sys. VAT</label>'+
              '<label class="col-4">'+data.uVat.ssvat+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uVat.ssvat_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Metering Fix VAT</label>'+
              '<label class="col-4">'+data.uVat.mfvat+'/cst'+'</label>'+
              '<label class="col-4">'+data.uVat.mfvat_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Metering Sys. VAT</label>'+
              '<label class="col-4">'+data.uVat.msvat+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uVat.msvat_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Lfln Disc./Subs. VAT</label>'+
              '<label class="col-4">'+data.uVat.ldsvat+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uVat.ldsvat_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Loan Condo. VAT</label>'+
              '<label class="col-4">'+data.uVat.lcvat+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uVat.lcvat_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Loan Condo.Fixed VAT</label>'+
              '<label class="col-4">'+data.uVat.lcfvat+'/cst'+'</label>'+
              '<label class="col-4">'+data.uVat.lcfvat_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4">Other VAT</label>'+
              '<label class="col-4">'+data.uVat.othervat+'/kwh'+'</label>'+
              '<label class="col-4">'+data.uVat.othervat_peso.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+'</label>'+
              '<label class="col-4" style="background: blue; color:white">SUB-TOTAL (VAT)</label>'+
              '<label class="col-4" style="background: blue; color:white">'+(data.uVat.rateSubTotal)+'</label>'+
              '<label class="col-4" style="background: blue; color:white">'+data.uVat.pesoSubTotal+'</label>';
    
    var totalKwhCharge = 0;
    var totalFixedCharge = 0;
    var totalDemandCharge = 0;
    for(let i in data){
        if(data[i] != data['data']){
            // console.log("data - i = ",i);
            for(let i2 in data[i]){
                // console.log("data[i] - i2 = ",i2);
                if(i2.search("SubTotal") >= 0){
                    continue;
                }
                else if(i2 == 'sfc' || i2 == 'mfc' || i2 == 'lcfvat' || i2 == 'mfvat'){
                    totalFixedCharge += parseFloat(data[i][i2]);
                }else if(i2 == 'ddc' || i2 == 'tdc' || i2 == 'tdvat' || i2 == 'ddvat'){
                    totalDemandCharge += parseFloat(data[i][i2]);
                }else if(i2.search("_peso") == -1){
                    totalKwhCharge += parseFloat(data[i][i2]);
                }
            }
        }
    }

    // output2 += "<h6 class='col-9'>TOTAL KWH CHARGE : </h6><h6 class='col-3'>"+totalKwhCharge.toFixed(2).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+"</h6>"+
    output2 += "<h6 class='col-9'>TOTAL KWH CHARGE : </h6><h6 class='col-3'>"+totalKwhCharge.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+"</h6>"+
               "<h6 class='col-9'>TOTAL FIX CHARGE : </h6><h6 class='col-3'>"+totalFixedCharge.toFixed(2).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+"</h6>"+
               "<h6 class='col-9'>TOTAL DEMAND CHARGE : </h6><h6 class='col-3'>"+totalDemandCharge.toFixed(2).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",")+"</h6>";

    document.querySelector('#chrges1').innerHTML = output;
    document.querySelector('#chrges2').innerHTML = output2;
}
/*--------------------------------------- END SHOW RATES ------------------------------------------- */
/*--------------------------------------- FIELD FINDINGS ------------------------------------------- */
function getFieldFindings(choice){
    document.onkeydown = '';
    var output = '<table id="tableFieldFinding"><tr>';
    var selectedDiv = "#fieldFinding";
    var selectedDiv2 = "#fieldFinding2";
    if(choice == parseInt(2)){
        selectedDiv = "#bulkFF";
        selectedDiv2 = "#bulkFF2";
        output = '<table id="bTableFieldFinding"><tr>';
    }
    var xhr = new XMLHttpRequest();
    var route = "{{route('index.fieldfinding')}}";
    xhr.open('GET',route,true);
    xhr.onload = function(){
        if(this.status == 200){
            var data = JSON.parse(this.responseText);
            var no = 1;
            
            output += '<th class="col-2">No.</th><th class="col-2">Field Finding</th><th class="col-2">Action</th></tr>';
            for(let i in data.data){
                output += '<tr><td class="col-2">'+(no++)+'</td><td class="col-2">'+data.data[i].field_finding_desc+'</td>'+
                '<td class="col-2"><input class="ff" type="checkbox" onclick="ff(this.id)" id="'+data.data[i].field_finding_id+'"></td></tr>'
            }
            output += '</table>';
            document.querySelector(selectedDiv).innerHTML = output;

            if(choice == parseInt(2)){
                output = '<hr><div style="text-align: center">'+
                    '<input id="bulkKWH" type="number" step="0.01" class="mt-2" onkeydown="overrideBtnEnter()" placeholder="Input bulk override KWH"></div>';
            }else{
                output = '<div style="text-align: center">'+
                        '<input class="col btn btn-info" name="3ma" style="margin: 1px;" type="button" id="'+storage.consumerID+'" onclick="override3ma(this.id,1)" value="3 Months Average">'+
                        '<input class="col btn btn-info" name="do" style="margin: 1px;" type="button" id="'+storage.consumerID+'" onclick="readInput(newKWH)" value="Direct Override">';
                output += '<input id="newKWH" type="number" step="0.01" class="mt-2" placeholder="New KWH" onkeydown="overrideBtnEnter()" readonly></div>';
            }
            document.querySelector(selectedDiv2).innerHTML = output;
        }
    }
    xhr.send();
}
/*--------------------------------------- END FIELD FINDINGS ------------------------------------------- */
/*--------------------------------------- BULK READING ------------------------------------------- */
function bulkOverride(){
    bulk.rID = document.querySelector('#routeId').value;
    bulk.bp = document.querySelector('#billingPeriod').value;
    bulk.bKWH = document.querySelector('#bulkKWH').value;
    bulk.dateReg = document.getElementById('readingDate').value;
    bulk.userID = "{{Auth::user()->user_id}}";

    Swal.fire({
        title: 'Are you sure you want to run bulk override?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
    }).then((result) => {
        if (result.isConfirmed) {
            xhr = new XMLHttpRequest;
            var r = "{{route('bulk.read')}}";
            xhr.open('POST',r,true);
            xhr.setRequestHeader('Accept', 'Application/json');
            xhr.setRequestHeader('Content-Type', 'Application/json');
            xhr.send(JSON.stringify({bulk}));
            
            xhr.onload = function(){
                var resp = JSON.parse(this.responseText);
                if(this.status == 422){
                    Swal.fire(
                        resp['Message'],
                        "",
                        'error'
                    )
                }
                else if(this.status == 200){
                    validateInputs();
                    document.getElementById('bulkOverride').style.display = 'none';
                    if(resp['success'] > 0){
                        Swal.fire(
                            resp['Message'],
                            "",
                            'success'
                        )
                    }else{
                        Swal.fire(
                            resp['Message'],
                            "",
                            'error'
                        )
                    }
                }
            }
        }
    })
    
}
/*--------------------------------------- END BULK READING ------------------------------------------- */
function cancelRead(){
    Swal.fire({
        title: 'Are you sure you want to cancel this reading?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
    }).then((result) => {
        if (result.isConfirmed) {
            if(storage.mrid > 0){
                var xhr = new XMLHttpRequest();
                var route = "{{route('cancel.emr')}}";
                const tosend = JSON.stringify({mrid:storage.mrid});
                // console.log(tosend);
                xhr.open('DELETE',route,true);
                xhr.setRequestHeader('Accept', 'Application/json');
                xhr.setRequestHeader('Content-Type', 'Application/json');
                xhr.send(tosend);
                xhr.onload = function(){
                    if(this.status == 200){
                        // var data = JSON.parse(this.responseText);
                        storage.data = document.getElementById(storage.row); 
                        var data = storage.data.childNodes;
                        data[2].childNodes[0].value = 0;
                        data[3].childNodes[0].value = 0;
                        data[4].childNodes[0].value = 0;
                        data[5].childNodes[0].value = 0;
                        data[6].childNodes[0].checked = false;
                        data[6].childNodes[0].id = null;
                    }else{
                        console.log('Unable to cancel reading. Please contact your support.');
                    }
                }
            } 
        }
    });  
}


function searchFf(choice){
    var input, filter, table, tr, td, i, txtValue,td2;
    if(parseInt(choice) == 1){
        input = document.getElementById("searchFieldFinding");
        filter = input.value.toUpperCase();
        table = document.getElementById("tableFieldFinding");
    }else if(parseInt(choice) == 2){
        input = document.getElementById("bulkSearchFF");
        table = document.getElementById("bTableFieldFinding");
    }
    filter = input.value.toUpperCase();
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        td1 = tr[i].getElementsByTagName("td")[0];
        if (td || td1) {
                txtValue = td.textContent || td.innerText;
                txtValue1 = td1.textContent || td1.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1 || txtValue1.toUpperCase().indexOf(filter) > -1 ) {
                    // console.log(1);
                    tr[i].style.display = "";
                } else {
                    // console.log(2);
                    tr[i].style.display = "none";
                }
        }
    }
    input = "";
    table = "";
}
/*------------------------------------- END FIELD FINDINGS ----------------------------------------- */
/*--------------------------------------- OVERRIDE ------------------------------------------- */
function override3ma(consumerID, action){
    var xhr = new XMLHttpRequest();
    var route = "{{route('average.3months',['cons_id'=>':par'])}}";
    if(action == 1){
        xhr.open('GET',route.replace(':par',consumerID),true);
        // xhr.open('GET','http://localhost:8000/api/v1/field_finding/3MonthsAve/'+consumerID,true);
        xhr.onload = function(){
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                document.querySelector('#newKWH').value = data.TMAve;
                if(document.querySelector('#newKWH').hasAttribute('readonly') == false){
                    document.querySelector('#newKWH').setAttribute('readonly', 'readonly');
                }
            }
        }
        xhr.send();
    }
}

function readInput(te){ 
    if(te.id == 'newKWH'){
        document.getElementById(te.id).placeholder = 0;
        document.getElementById(te.id).removeAttribute('readonly');
        document.getElementById(te.id).focus();
    }else{
        document.getElementById(te.id).removeAttribute('readonly');
    } 
}
/*------------------------------------- END OVERRIDE ----------------------------------------- */
/*--------------------------------------- OVERRIDE KWH ------------------------------------------- */
function overrideKWH(){
    try {
        var kwh = parseFloat(document.getElementById('newKWH').value);
        kwh = (kwh == null || kwh == undefined || kwh <= 0 || kwh == isNaN) ? 0 : kwh;

        if(kwh > 0 && storage2.stat3 == false){
            document.getElementById('conKwhVal').value = Math.round(parseFloat(kwh));
            storage.override = 2;
            storage.newKwh = Math.round(parseFloat(kwh));
            computeRates();
            storage2.stat3 = true;
            document.getElementById('override').style.display = 'none'; 
            
            setTimeout(function(){
                document.onkeydown = checkKey;
            },500);

            setTimeout(function(){
                storage2.stat3 = false;
            },800);
        }else{
            Swal.fire(
                'Invalid!',
                'Fill up new Kwh!',
                'error'
            )
        }
    } catch (error) {
        
    }
}

function overrideBtnEnter(){
    try {
        var kwh = parseFloat(document.getElementById('newKWH').value);
        var bKwh = parseFloat(document.getElementById('bulkKWH').value);
        kwh = (kwh == null || kwh == undefined || kwh <= 0 || kwh == isNaN) ? 0 : kwh;
        bKwh = (bKwh == null || bKwh == undefined || bKwh <= 0 || bKwh == isNaN) ? 0 : bKwh;

        if(event.keyCode == 13 && kwh > 0){
            overrideKWH();
        }else if(event.keyCode == 13 && bKwh > 0){
            bulkOverride();
        }else if(event.keyCode == 13){
            Swal.fire(
                'Invalid!',
                'Fill up new Kwh!',
                'error'
            )
        } 
    } catch (error) {
        
    }
}

document.getElementById('prevReadVal').addEventListener("focusout",function(){
    var a = document.getElementById('prevReadVal');
    if(storage.kwhUsed != undefined){
        a.setAttribute('readonly','readonly');
        var prevRead = document.getElementById('prevReadVal').value;
        storage.newPrevRead = prevRead;
        storage.prevEdit = 1;
        // console.log('edit previous');
        computeRates(); 
    }
});

function validateInputs(){
    var count = 0;
    var s = document.querySelectorAll(".input-check");
    var er = "";
    storage.rateAvailable = parseInt(document.getElementById('billingPeriod').getAttribute('data-rate-available'));
    var vrd = validateReadDate();

    Array.from(s).forEach(function(e){
        if(e.type == 'month' && storage.rateAvailable != 1){
            er += "Invalid "+e.getAttribute('name')+"<br>";
            e.setAttribute("style","border-bottom: 5px solid red");
        }
        else if(e.type == 'date' && vrd != 1){
            er += "Invalid "+e.getAttribute('name')+"<br>";
            e.setAttribute("style","border-bottom: 5px solid red");
        }
        else if(e.value.length > 0){
            count++;
            e.setAttribute("style","border: none");
        }else{
            er += "Invalid "+e.getAttribute('name')+"<br>";
            e.setAttribute("style","border-bottom: 5px solid red");
        }
    });
   
    if(count == 4){
        alert.hidden = true;
        getconsumers();
        document.getElementById('mainContent').removeAttribute('hidden');
    }else{
        document.getElementById('msgs').innerHTML = er;
        alert.hidden = false;
        document.querySelector('#consumers').innerHTML = "";
    }
}

function ff(id){
    var fieldFinding = document.querySelectorAll('.ff');
    Array.from(fieldFinding).forEach(function(fieldFinding){
        if(fieldFinding.checked == true && fieldFinding.id != id){
            fieldFinding.checked = false;
        }
    });

    var check = document.getElementById(id).checked;
    if(check == true){
        storage.ff = id;
        bulk.ff = id;
    }else{
        storage.ff = "";
        bulk.ff = "";
    }
}

function checkRates(billPeriod){
    // if(billPeriod != ''){
        billPeriod = billPeriod.replaceAll('-','');
        storage2.billPeriod = billPeriod;
        var xhr = new XMLHttpRequest();
        var route = "{{route('check.billrates',['billPeriod'=>':par'])}}";
        xhr.open('GET',route.replace(':par',billPeriod),true);
        // xhr.open('GET','http://localhost:8000/api/v1/billing_rates/checkRate/'+billPeriod,true);
        xhr.send();
        xhr.onload = function(){
            if(this.status == 200){
                // console.log(billPeriod+ " status 200");
                var data = JSON.parse(this.responseText);
                if(data.msg != 1){
                    document.getElementById('billingPeriod').setAttribute("data-rate-available", 0);
                 }
                 else{
                    document.getElementById('billingPeriod').setAttribute("data-rate-available", 1);
                }
            }
        }
}

function validateReadDate(){
    var readDate = document.getElementById('readingDate').value;
    const date = new Date();
    const current = date.getFullYear()+""+("0" + (date.getMonth() + 1)).slice(-2)+""+("0" + date.getDate()).slice(-2);
    readDate = readDate.replaceAll('-','');

    if(parseInt(readDate) <= parseInt(current)){
        return 1;
    }else{
        return 2;
    }
}

function nextRow(i){
    // console.log("current row : ",i);
    var pres = 'presRead';
    var nextRow = pres+(parseInt(i)+1);
    // console.log("next row : ", nextRow);
    document.getElementById(nextRow).focus();

}

var start = null;
var c_row = null;

function nav(selected){
    start = selected;
    c_row = start.parentElement;
    start.childNodes[0].focus();
    c_row.style.backgroundColor = 'green';
}


function dotheneedful(sibling) {
    if (sibling != null) {
        var a = sibling.parentElement;
        if(a.id != 'th_0'){
            // console.log('dotheneedful - ')
            // console.log(sibling)
            start.childNodes[0].focus();
            start.parentElement.style.backgroundColor = '';
            start.removeAttribute('id');
            document.getElementById(sibling.parentElement.id).childNodes[2].setAttribute('id','start'); 
            sibling.childNodes[0].focus();
            sibling.parentElement.style.backgroundColor = 'green';
            start = sibling;
        }
    }
}

document.onkeydown = checkKey;

function checkKey(e) {
    try {
        e = e || window.event;
        if (e.keyCode == '38') {
            // up arrow
            e.preventDefault();
            var idx = start.cellIndex;
            var nextrow = start.parentElement.previousElementSibling;
            // console.log('up');
            if (nextrow != null) {
            var sibling = nextrow.cells[idx];
            dotheneedful(sibling);
            }
        } else if (e.keyCode == '40') {
            // down arrow
            e.preventDefault();
            var idx = start.cellIndex;
            var nextrow = start.parentElement.nextElementSibling;
            // console.log('down');
            if (nextrow != null) {
            var sibling = nextrow.cells[idx];
            dotheneedful(sibling);
            }
        } else if (e.keyCode == '37') {
            // left arrow
            var sibling = start.previousElementSibling;
            // console.log('left');
            dotheneedful(sibling);
        } else if (e.keyCode == '39') {
            // right arrow
            var sibling = start.nextElementSibling;
            // console.log('right');
            dotheneedful(sibling);
        } else if (e.keyCode == 13) {
            var c_row = start.parentElement;
            // console.log("row id : ",c_row.id);
            setConsumer(c_row.id);
        }
    } catch (error) {
        
    }
}

function discount(type){
    // if(type == 1){
    //     storage.mosqueDisc = document.getElementById('masjeedVal').value;
    //     console.log('11111111');
    //     console.table(storage);

    //     computeRates();
    // }else if(type == 2){
    //     storage.seniorDisc = document.getElementById('seniorVal').value;
    //     console.log('2');
    //     console.log(storage.seniorDisc);
    //     computeRates();
    // } 
}

</script>
@endsection