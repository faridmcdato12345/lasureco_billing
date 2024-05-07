@extends('layout.master')
@section('title', 'Summary of Revenue - Consumer Type')
@section('content')
<style>
.container {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 1vw;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  color:white;
  font-weight:bold;
  margin-left:30px;
}

.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0vw;
  width: 0vw;
}

.checkmark {
  position: absolute;
  top: 0;
  left:0;
  height: 1.5vw;
  width: 1.5vw;
  background-color: #eee;
}

.container:hover input ~ .checkmark {
  background-color: #ccc;
}

.container input:checked ~ .checkmark {
  background-color: #2196F3;
}

.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

.container input:checked ~ .checkmark:after {
  display: block;
}

.container .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
input {
  cursor: pointer;
}
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #areaTable {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #areaTable td{
        height: 40px;
        border-bottom: 1px #ddd solid;
    }
    #billFromTable {
      display: none;
    }
    #billToTable {
      display: none;
      margin-top: -5%;
    }
    #areaInp {
      margin-left: -0.5%;
      width: 92.5%;
    }
    #billTo {
      width: 95.5%;
    }
    #printBtn {
      margin-top: 6%;
      margin-right: 3.6%;
      display: none;
    }
</style>
<p class="contentheader">Summary of Revenue - Consumer Type</p>
<div class="main">
<br>
    <table class="content-table" style="margin-top: -3%;">
      <tr>
        <td style="width: 14%;">
          Area:
        </td>
        <td>
          <input type="text" id="areaInp" placeholder="Select Area" onclick="showArea()" readonly>
          <input type="text" id="areaId" hidden>
        </td>
      </tr>
    </table>
    <table id="billFromTable" class="content-table">
      <tr>
        <td style="width: 14%;">
          Billing From: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <td>
          <input type="month" id="billFrom">
        </td>
      </tr>
    </table>
    <table id="billToTable" class="content-table">
        <tr>
          <td style="width: 14%;">
            Billing To: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp
          </td>
          <td>
            <input type="month" id="billTo">
          </td>
        </tr>
        <tr>
          <td colspan=2>
            <button id="printBtn" onclick="printSumm()">Print</button>
          </td>
        </tr>
    </table>
</div>

@include('include.modal.areamodal')

<script>
  function setArea(a){
    var areaName = a.getAttribute('areaName');
    var areaId = a.getAttribute('areaId');

    document.querySelector("#areaInp").value = areaName;
    document.querySelector("#areaId").value = areaId;

    document.querySelector("#areaCodes").style.display = "none";
    document.querySelector("#billFromTable").style.display = "block";
  }

  var billFrom = document.querySelector("#billFrom");

  billFrom.addEventListener("change", function(){
    var billPeriodFrom = billFrom.value;

    if(billPeriodFrom !== ""){
      document.querySelector("#billToTable").style.display = "block";
    } else {
      document.querySelector("#billToTable").style.display = "none";
    }
  })

  var billTo = document.querySelector("#billTo");

  billTo.addEventListener("change", function(){
    var billPeriodTo = billTo.value;

    if(billPeriodTo !== ""){
      document.querySelector("#printBtn").style.display = "block";
    } else {
      document.querySelector("#printBtn").style.display = "none";
    }
  })

  function printSumm() {
    var areaId = document.querySelector("#areaId").value;
    var billFrom = document.querySelector("#billFrom").value;
    var billTo = document.querySelector("#billTo").value;
    
    const toSend = {
      "areaId": areaId,
      "billFrom": billFrom,
      "billTo": billTo,
    }
    localStorage.setItem('data', JSON.stringify(toSend));

    $url = '{{route("print_summary_of_revenue_consumer_type")}}'
    window.open($url);
    location.reload();
  }
</script>
@endsection
