@extends('layout.master')
@section('title', 'Reconnection Report')
@section('content')

<style>
    .br {
        height: 20px;
    }
    select {
        color: black;
        cursor: pointer;
    }
    input {
        cursor: pointer;
        text-align: left !important;
    }
    .printBtn {
        float: right;
        color: royalblue;
        background-color: white;
        margin-top: 4%;
        margin-right: 4.5%;
        height: 40px !important;
    }
    #selected {
         width: 27%;
    }
</style>

<p class="contentheader">Reconnection Report</p>
<div class="main" onload="watchSelected()">
    <table class="content-table">
        <br>
        <tr>
            <td colspan=2>
                <select id="selected">
                    <option value="Area"> Area </option>
                    <option value="Town"> Town </option>
                    <option value="Route"> Route </option>
                    <option value="All"> All </option>
                </select>
            </td>
        </tr>
        <tr><td class="br"></td></tr><tr><td class="br"></td></tr>
        <tr> 
            <td style="width: 15%;" id='selectBy'>
                <div class="selectText"> Area: </div>
            </td>
            <td> 
            <div class="selectInp">
                <input type="text" id="area" onclick="showArea()" placeholder="Select Area" readonly>
            </div>
            <input type="text" id="targetId" hidden>
            </td>
        </tr>
        <tr><td class="br"></td></tr>
        <tr> 
            <td> 
                Date from:
            </td>
            <td> 
                <input type="date" id="monthFrom" disabled>
            </td>
        </tr>
        <tr><td class="br"></td></tr>
        <tr> 
            <td> 
                Date to:
            </td>
            <td> 
                <input type="date" id="monthTo" disabled>
            </td>
        </tr>
        <tr>
            <td colspan=2> 
                <button class="printBtn" onclick="print()" disabled>
                    Print
                </button>
            </td>
        </tr>
    </table>
</div>  

@include('include.modal.areamodal')
@include('include.modal.townmodal')
@include('include.modal.routemodal')

<script>
    var selected = document.querySelector("#selected");
    selected.addEventListener("change", function(){
        document.querySelector(".selectText").innerHTML = selected.value + ":";

        var selectInp = document.querySelector(".selectInp");
        if(selected.value == "Area"){
            selectInp.innerHTML = '<input type="text" id="area" onclick="showArea()" placeholder="Select Area" readonly>';
            document.querySelector("#targetId").value = "";
            document.querySelector("#monthFrom").value = "";
            document.querySelector("#monthTo").value = "";
            document.querySelector("#monthTo").disabled = true;
            document.querySelector(".printBtn").disabled = true;
            enableDate();
        } else if(selected.value == "Town"){
            selectInp.innerHTML = '<input type="text" id="townInp" onclick="showTown()" placeholder="Select Town" readonly>';
            document.querySelector("#targetId").value = "";
            document.querySelector("#monthFrom").value = "";
            document.querySelector("#monthTo").value = "";
            document.querySelector("#monthTo").disabled = true;
            document.querySelector(".printBtn").disabled = true;
            enableDate();
        } else if(selected.value == "Route"){
            selectInp.innerHTML = '<input type="text" id="route" onclick="showRoutes()" placeholder="Select Route" readonly>';
            document.querySelector("#targetId").value = "";
            document.querySelector("#monthFrom").value = "";
            document.querySelector("#monthTo").value = "";
            document.querySelector("#monthTo").disabled = true;
            document.querySelector(".printBtn").disabled = true;
            enableDate();
        } else {
            selectInp.innerHTML = '<input type="text" readonly disabled>';
            document.querySelector("#targetId").value = "all";
            document.querySelector("#monthFrom").disabled = false;
        }
    })   
    
    function setArea(a) {
        var areaName = a.getAttribute('areaName');
        var areaId = a.getAttribute('areaId');

        document.querySelector("#area").value = areaName;
        document.querySelector("#targetId").value = areaId;
        document.querySelector("#areaCodes").style.display = "none";
        enableDate();
    }

    function selectTown(a) {
        var townName = a.getAttribute('name');
        var townCode = a.getAttribute('code');
        var townId = a.getAttribute('id');
        
        document.querySelector("#townInp").value = townCode + " - " + townName;
        document.querySelector("#targetId").value = townId;
        document.querySelector("#town").style.display = "none";
        enableDate();
    }

    function setRoute(a) {
        var routeId = a.getAttribute("id");
        var routeCode = a.getAttribute("code");
        var routeName = a.getAttribute("name");

        document.querySelector("#route").value = routeCode + " - " + routeName;
        document.querySelector("#targetId").value = routeId;
        document.querySelector("#routeCodes").style.display = "none";
        enableDate();
    }

    function enableDate(){
        var targetId = document.querySelector("#targetId").value;

        if(targetId !== ""){
            document.querySelector("#monthFrom").disabled = false;
        } else {
            document.querySelector("#monthFrom").disabled = true;
        }
    }

    var monthFrom = document.querySelector("#monthFrom");
    monthFrom.addEventListener("change", function(){
        if(monthFrom.value !== ""){
            document.querySelector("#monthTo").disabled = false;
        } else {
            document.querySelector("#monthTo").disabled = true;
        }
    })

    var monthTo = document.querySelector("#monthTo");
    monthTo.addEventListener("change", function(){
        if(monthTo.value !== ""){
            document.querySelector(".printBtn").disabled = false;
        } else {
            document.querySelector(".printBtn").disabled = true;
        }
    })

    function print(){
        var selected = document.querySelector("#selected").value;
        var id = document.querySelector("#targetId").value;
        var dateFrom = document.querySelector("#monthFrom").value;  
        var dateTo = document.querySelector("#monthTo").value;  
        

        const toSend = {
            'date_from': dateFrom,
            'date_to': dateTo,
            'selected': selected,
            'id': id
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_reconnection_report")}}';
        window.open($url);
    }
</script>
@endsection
