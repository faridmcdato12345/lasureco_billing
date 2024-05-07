@extends('layout.master')
@section('title', 'Summary of Unread/Unbilled Consumers')
@section('content')

<style>
    #routeTable input {
        width: 92.5%;
        cursor: pointer;
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
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #routeCodeTable {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #routeCodeTable td{
        height: 40px;
        border-bottom: 1px #ddd solid;
    }
    .routeDiv {
        height: 250px;
        margin-top: 1%; 
    }
    .meterDiv {
        height: 315px;
        overflow-y: scroll;
        margin-top: 1%; 
        border-bottom: 1px solid #ddd;
    }
    #month {
        cursor: pointer;
    }
    #printBtn {
        float: right;
        background-color: white;
        color: royalblue;
        border-radius: 3px; 
        margin-right: 2.8%;  
        margin-top: 6%;
    }
    #meterInp {
        cursor: pointer;
    }
    #routeFrom {
        width: 97% !important;
    }
</style>

<p class="contentheader">Summary of Unread/Unbilled Consumers</p>
<div class="main">
    <table id="routeTable" class="content-table">
        <tr>
            <td style="width: 13%;">
                Route: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td id="routeFromInp">
                <input type="text" id="routeFrom" placeholder="Select Route" onclick="showRoutes()" readonly>
                <input type="text" id="rc_code_from" hidden>
            </td>
        </tr>
    </table>
    <table class="content-table">
        <tr>
            <td style="width: 13%;">
                Billing Period:
            </td>
            <td colspan=3>
                <input type="month" id="month" disabled>
            </td>
        </tr>
        <tr>
            <td style="height: 60px;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td>
                Meter Reader:
            </td>
            <td colspan=3>
                <input type="text" id="meterInp" onclick="showMeterReaders()" placeholder="Select Meter Reader" readonly>
                <input type="text" id="meterCode" hidden>
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <button id="printBtn" onclick="printConsumer()" disabled> Print </button>
            </td>
        </tr>
    </table>
</div>

<div id="meterReader" class="modal">
    <div class="modal-content" style="margin-top: 30px; width: 40%; height: 420px;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Meter Reader Lookup</h3>
            <span href = "#meterReader" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="meterDiv"> </div>
        </div>
    </div>
</div>

@include('include.modal.routemodal')

<script>
    function setRoute(x){
        var id = x.id;
        var name = x.getAttribute('name');
        var code = x.getAttribute('code');
        
        document.querySelector("#rc_code_from").value = id;
        document.querySelector("#routeFrom").value = code + " - " + name;
        document.querySelector("#routeCodes").style.display = "none";
        document.querySelector("#month").disabled = false;
    }  

    function showMeterReaders(){
        const xhr = new XMLHttpRequest();

        var meterReader = "{{route('show.meter.reader')}}";
        xhr.open('GET', meterReader, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                var response = JSON.parse(this.responseText);
                var meterReader = response.data;

                var output = "<table id='routeCodeTable'> <tr id='thead'> <td> &nbsp;  Emp Number </td> <td> Meter Reader </td> </tr>";
                
                for(var a in meterReader){
                    output += "<tr onclick='selectMeter(this)' id='" + meterReader[a].em_emp_no + "' class='tbody'";
                    output +=  "name='" + meterReader[a].gas_fnamesname +"'> <td>&nbsp;&nbsp;" + meterReader[a].em_emp_no + "</td>";
                    output += "<td>" + meterReader[a].gas_fnamesname + "</td></tr>";
                }
                output += "</table>";

                document.querySelector('.meterDiv').innerHTML= output;
            }
        }
        document.querySelector('#meterReader').style.display = "block";
    }

    function selectMeter(x){
        var meterCode = x.id;
        var meterReader = x.getAttribute('name');

        document.querySelector("#meterInp").value = meterCode + " - " + meterReader;
        document.querySelector("#meterCode").value = meterCode;
        document.querySelector("#meterReader").style.display = "none";
    }

    function printConsumer(){
        var routeFrom = document.querySelector("#rc_code_from").value;
        var billPeriod = document.querySelector("#month").value;

        const toSend = {
            'month': billPeriod,
            'routeFrom': routeFrom
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_summary_of_unread_unbilled_consumer")}}'
        window.open($url);
    }

    var month = document.querySelector("#month");
    month.addEventListener("change", function(){
        if(month.value !== "") {
            document.querySelector("#printBtn").disabled = false;
        } else {
            document.querySelector("#printBtn").disabled = true;
        }
    })
</script>
@endsection
