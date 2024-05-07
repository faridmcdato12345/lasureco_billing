@extends('layout.master')
@section('title', 'General Summary Report')
@section('content')

<style>
    table{
        margin: auto;
        width: 90%;
    }
    #areaName {
        cursor: pointer;
    }
    #billPeriodTable {
        margin-top: 3%;
        display: none;
    }
    #cutOffTable {
        margin-top: -2%;
        display: none;
    }
    #cutOffTable2 {
        margin-top: -3%;
        display: none;
    }
    #cutOff {
        margin-left: 4px;
        width: 96.7%;
    }
    #printBtnTbl {
        display: none;
        background-color: red;
    }
    #printButton { 
        display: none;
        float: right;
        margin-right: 9%;
        background-color: white;
        color: royalblue;
        height: 45px;
        width: 5%;
        border-radius: 2px;
    }
    #billPeriod {
        width: 97%;
        margin-left: 4px;
    }
    input {
        color: black;
        width: 95%;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    
    #areaTbl {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #areaTbl td{
        height: 50px;
        border-bottom: 1px #ddd solid;
    }
</style>

<p class="contentheader">General Summary Report</p>
<div class="main">
<br><br>
    <table class="content-table" style="margin-top: -4%;">
        <tr>
            <td style="width: 10%;">
                Area Code:
            </td>
            <td>
                <input type="text" id="areaName" onclick="showArea()" placeholder="Select Area" readonly>
                <input type="text" id="areaId" hidden>
            </td>
        </tr>
    </table>
    <table id="billPeriodTable" class="content-table">
        <tr>
            <td style="width: 10%;">
                Bill Period: &nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="month" id="billPeriod">
            </td>
        </tr>
    </table>
    <table id="cutOffTable" class="content-table">
        <tr>
            <td style="width: 10%;">
                Cut Off: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="date" id="cutOff">
            </td>
        </tr>
        <tr>
    </table>
    <table id="cutOffTable2">
            <td>
                <input type="radio" name="bill" id="onTime" value="1" checked>
                <label for="onTime"> Billed On Time Only </label> 
            </td>
            <td>
                &nbsp;&nbsp;
                <input type="radio" name="bill" id="late" value="2">
                <label for="late"> Include Late Billings </label>
            </td>
        </tr>
    </table>
    <button onclick="printGenDetails()" id="printButton"> Print </button> </div>
</div>

<div id="area" class="modal">
    <div class="modal-content" style="margin-top: 100px; width: 30%; height: 410px;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Area Lookup</h3>
            <span href = "#area" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="areaDiv"> </div>
        </div>
    </div>
</div>

<script>
    function showArea(){
        const xhr = new XMLHttpRequest();
        var showArea = "{{route('index.area')}}";
        xhr.open('GET', showArea, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                var response = JSON.parse(this.responseText);
                var area = response.data;
                var output = "<table id='areaTbl'> <tr id='thead'> <td> &nbsp;  Area ID </td>";
                output += "<td> Area </td> </tr>";
                
                for(var a in area){
                    output += "<tr onclick='selectArea(this)' id='" + area[a].area_id + "' name='" + area[a].area_name + "' class='tbody'>";
                    output += "<td>&nbsp;&nbsp;&nbsp;" + area[a].area_id + "</td>";
                    output += "<td>&nbsp;" + area[a].area_name + "</td></tr>";
                }
                output += "</table>";

                document.querySelector('.areaDiv').innerHTML= output;
            }
        }
        document.querySelector('#area').style.display = "block";
    }

    function selectArea(a){
        var areaName = a.getAttribute('name');
      
        document.querySelector("#areaId").value = a.id;
        document.querySelector("#areaName").value = areaName;

        document.querySelector("#area").style.display = "none";
        document.querySelector("#billPeriodTable").style.display = "block";
        document.querySelector("#billPeriod").focus();
    }

    document.querySelector('#billPeriod').addEventListener("change", function(){
        var billPeriod = document.querySelector('#billPeriod');

        if(billPeriod.value !== ""){
            document.querySelector('#cutOffTable').style.display = "block";
            document.querySelector('#cutOffTable2').style.display = "block";
            document.querySelector('#printButton').style.display = "block";
            document.querySelector('#cutOff').focus();
        } else {
            document.querySelector('#cutOffTable').style.display = "none";
            document.querySelector('#cutOffTable2').style.display = "none";
            document.querySelector('#printButton').style.display = "none";
        }
    })

    function printGenDetails(){
        var areaId = document.querySelector('#areaId').value;
        var billPeriod = document.querySelector('#billPeriod').value;

        var bill = document.getElementsByName('bill');
        for(i = 0; i < bill.length; i++) {
            if(bill[i].checked){
                var selected = bill[i].value;        
            }
        }

        const toSend = {
            'areaId': areaId,
            'billPeriod': billPeriod,
            'selected': selected,
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_general_summary_report")}}'
        window.open($url);
    }
</script>
@endsection
