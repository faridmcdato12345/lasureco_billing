@extends('layout.master')
@section('title', 'Increase in Consumption')
@section('content')

<style>
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #routeTable {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #routeTable td{
        height: 40px;
        border-bottom: 1px #ddd solid;
    }
    #meterTable {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #meterTable td{
        height: 40px;
        border-bottom: 1px #ddd solid;
    }
    #routeInp {
        width: 87%;
        cursor: pointer;
    }
    #meterInp {
        cursor: pointer;
    }
    #month {
        cursor: pointer;
    }
    #meterReaderTable {
        margin-top: 0%;
        display: none;
    }
    #billPeriodTable {
        margin-top: -4.5%;
        display: none;
    }
    #conskwhTable {
        margin-top: -4.5%;
        display: none;
    }
    #printBtn {
        background-color: white;
        color: royalblue;
        height: 45px;
        width: 6%;
        margin-top: 7%;
        margin-right: 1%;
        font-size: 20px;
    }
    #paginate {
        width: 100%;
        margin: auto;
    }
    #paginate button {
        background-color: royalblue;
        border-radius: 3px;
        height: 35px;
        width: 100%; 
    }
</style>

<p class="contentheader">Increase in Consumption</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td style="width: 14.6%;">
                Route:
            </td>
            <td class="input-td">
                <input  type="text" id="routeInp"  placeholder="Select Route" onclick="showRoutes()" readonly>
                <input type="text" id="routeId" style="display: none;">
            </td>
        </tr>
    </table>
    <table id="meterReaderTable" class="content-table">
        <tr>
            <td style="width: 16%;">
                Meter Reader: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td class="input-td">
                <input  type="text" id="meterInp" placeholder="Select Meter Reader" onclick="showMeter()" readonly>
           </td>
        </tr>
    </table>
    <table id="billPeriodTable" class="content-table">
        <tr>
            <td  style="width: 16%;">
                Billing Period: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td class="input-td">
                <input  type="month" id="month">
            </td>
        </tr>
    </table>
    <table id="conskwhTable" class="content-table">
        <tr>
            <td style="width: 16%;">
                Consumption KWH: 
            </td>
            <td>
                <input type="number" id="conskwhFrom" placeholder="From">
            </td>
            <td>
                <input type="number" id="conskwhTo" placeholder="To">
            </td>
        </tr>
        <tr>
            <td colspan=3>
            <button id="printBtn" onclick="printConskwh()">Print</button>
            </td>
        </tr>
    </table>
</div>

<div id="meterReader" class="modal">
    <div class="modal-content" style="margin-top: 30px; width: 40%; height: 350px;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Meter Reader Lookup</h3>
            <span href = "#meterReader" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="meterReaderDiv" style="overflow-y: scroll; height: 260px;"> </div>
        </div>
    </div>
</div>

@include('include.modal.routemodal')

<script>
    function setRouteFrom(x){
        var id = x.id;
        var name = x.getAttribute('name');
        var code = x.getAttribute('code');

        document.querySelector('#routeId').value = id;
        document.querySelector('#routeInp').value = code + " - " +name;
        document.querySelector('#routeCodes').style.display = "none";
        document.querySelector("#searchRoute").value = "";
        document.querySelector('#meterReaderTable').style.display = "block";
    }

    function showMeter(){
        document.querySelector('#meterReader').style.display = "block";
        const xhr = new XMLHttpRequest();
        var meterReader = "{{route('show.meter.reader')}}";
        xhr.open('GET', meterReader, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                var response = JSON.parse(this.responseText);
                var meter = response.data;
                var output = "<table id='meterTable'> <tr id='thead'> <td> &nbsp;  Meter Code </td>";
                output += "<td> Meter Reader </td> </tr>";
                
                for(var a in meter){
                    output += "<tr onclick='selectMeter(this)' id='" + meter[a].em_id + "' class='tbody'";
                    output +=  "name='" + meter[a].gas_fnamesname + "'> <td>&nbsp;&nbsp;" + meter[a].em_emp_no + "</td>";
                    output += "<td>" + meter[a].gas_fnamesname + "</td></tr>";
                }
                output += "</table>";

                document.querySelector('.meterReaderDiv').innerHTML= output;
            }
        }
    }

    function selectMeter(x){
        var id = x.id;
        var name = x.getAttribute('name');
        
        document.querySelector('#meterInp').value = name;
        document.querySelector('#meterReader').style.display = "none";

        document.querySelector('#billPeriodTable').style.display = "block";
    }

    var month = document.querySelector("#month");
    month.addEventListener("change", function(){
        var billPeriod = month.value;
        
        if(billPeriod !== ""){
            document.querySelector("#conskwhTable").style.display = "block";
        } else {
            document.querySelector("#conskwhTable").style.display = "none";
        }
    })

    function printConskwh(){
        var routeId = document.querySelector("#routeId").value;
        var meterReader = document.querySelector("#meterInp").value;
        var billPeriod = document.querySelector("#month").value;
        var kwhfrom = document.querySelector("#conskwhFrom").value;
        var kwhto = document.querySelector("#conskwhTo").value;

        const toSend = {
            'routeId': routeId,
            'meterReader': meterReader,
            'billPeriod': billPeriod,
            'kwhfrom': kwhfrom,
            'kwhto': kwhto
        }
        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_increase_in_consumption")}}'
        window.open($url);
        location.reload();
    }
</script>
@endsection
