@extends('layout.master')
@section('title', 'List of Consumer Disconnection')
@section('content')

<style>
    select {
        cursor: pointer;
    }
    #areaDiv {
        display: block;
    }
    #townDiv {
        display: none;
    }
    #routeDiv {
        display: none;
    }
    input {
        cursor: pointer;
    }
</style>

<p class="contentheader">List of Consumer Disconnection</p>
<div class="main">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <select id="by" style="color: black; width: 25%;">
        <option value="area" selected>By Area</option>
        <option value="town">By Town</option>
        <option value="route">By Route</option>
    </select>
    <br>
    <div id='areaDiv'>
        <table class="content-table">
            <tr>
                <td style="width: 15%;"> Area </td>
                <td> 
                    <input type="text" id="areaInp" onclick="showArea()" placeholder="Select Area" readonly>
                    <input type="text" id="areaId" hidden>
                </td>
            </tr>
            
        </table>
    </div>
    <div id='townDiv'>
        <table class="content-table">
            <tr>
                <td style="width: 15%;"> Town </td>
                <td> 
                    <input type="text" id="townInp" onclick="showTown()" placeholder="Select Town" readonly>
                    <input type="text" id="townId" hidden>
                </td>
            </tr>
        </table>
    </div>
    <div id='routeDiv'>
        <table class="content-table">
            <tr>
                <td style="width: 15%;"> Route </td>
                <td> 
                    <input type="text" id="routeInp" onclick="showRoutes()" placeholder="Select Route">
                    <input type="text" id="routeId" hidden>
                </td>
            
        </table>
    </div>
    <div>
        <table class="content-table" style="margin-top: -8px;">
            <tr>
                <td style="width: 15%;"> Consumer Type</td>
                <td>
                    <select style="width:95%;color:black" name=""  onchange= "selectedCons(this)" id="selectCons">
                        <option disabled selected>select consumer type</option>
                        <option value="all">All</option>
                        <option value="1"> IRRIGATION </option>
                        <option value="2"> COMMERCIAL WATER SYSTEM </option>
                        <option value="3"> BAPA </option>
                        <option value="4"> INDUSTRIAL </option>
                        <option value="5"> PUBLIC BUILDING </option>
                        <option value="6"> STREETLIGHTS </option>
                        <option value="7"> COMMERCIAL  </option>
                        <option value="8"> RESIDENTIAL  </option>
                    </select>
                </td>
            </tr>
            <tr><td style="height: 30px;"> &nbsp;</td></tr>
            <tr> 
                <td style="width: 15%;"> Date </td>
                <td>
                    <input type="date" id="date" disabled>
                </td>
            </tr>
            
            <tr><td style="height: 30px;"> &nbsp;</td></tr>
            <tr>
                <td> Months Delayed </td>
                <td>
                    <input type="number" id="month" placeholder="Number of Months" disabled>
                </td>
            </tr>
            <tr><td style="height: 30px;"> &nbsp;</td></tr>
            <tr>
                <td>
                    Total Arrears
                </td>
                <td>
                    <input type="text" id="totalArrears" placeholder="Total Arrears" disabled>
                </td>
            </tr>
            <tr>
                <td colspan=2> 
                    <button style="background-color: white; 
                                   color: royalblue; 
                                   height: 40px; 
                                   float: right;
                                   margin-top: 3.5%;
                                   margin-right: 4.5%;"  
                                   class="printButton"
                                   onclick="print()" disabled> Print </button>
                </td>
            </tr>
        </table>
    </div>
</div>

@include('include.modal.areamodal')
@include('include.modal.townmodal')
@include('include.modal.routemodal')

<script>
    var selectCon;
    var by = document.querySelector("#by");
    by.addEventListener("change", function(){
    	if(by.value == "area") {
            document.querySelector('#areaDiv').style.display = "block";
            document.querySelector("#townDiv").style.display = "none";
            document.querySelector("#routeDiv").style.display = "none";
            document.querySelector("#date").disabled = true;
            document.querySelector(".printButton").disabled = true;
            document.querySelector("#date").value = "";
            document.querySelector("#month").value = "";
            document.querySelector("#totalArrears").value = "";
            document.querySelector("#areaInp").value = "";
            document.querySelector("#townInp").value = "";
            document.querySelector("#routeInp").value = "";
            document.querySelector("#areaId").value = "";
            document.querySelector("#townId").value = "";
            document.querySelector("#routeId").value = "";
        } else if(by.value == "town") {
            document.querySelector("#areaDiv").style.display = "none";
            document.querySelector("#townDiv").style.display = "block";
            document.querySelector("#routeDiv").style.display = "none";
            document.querySelector("#date").disabled = true;
            document.querySelector(".printButton").disabled = true;
            document.querySelector("#date").value = "";
            document.querySelector("#month").value = "";
            document.querySelector("#totalArrears").value = "";
            document.querySelector("#areaInp").value = "";
            document.querySelector("#townInp").value = "";
            document.querySelector("#routeInp").value = "";
            document.querySelector("#areaId").value = "";
            document.querySelector("#townId").value = "";
            document.querySelector("#routeId").value = "";
        } else if(by.value == "route"){
            document.querySelector("#areaDiv").style.display = "none";
            document.querySelector("#townDiv").style.display = "none";
            document.querySelector("#routeDiv").style.display = "block";
            document.querySelector("#date").disabled = true;
            document.querySelector(".printButton").disabled = true;
            document.querySelector("#date").value = "";
            document.querySelector("#month").value = "";
            document.querySelector("#totalArrears").value = "";
            document.querySelector("#areaInp").value = "";
            document.querySelector("#townInp").value = "";
            document.querySelector("#routeInp").value = "";
            document.querySelector("#areaId").value = "";
            document.querySelector("#townId").value = "";
            document.querySelector("#routeId").value = "";
        }
    })

    function setArea(a){
        var areaId = a.getAttribute('areaId');
        var areaName = a.getAttribute('areaName');

        document.querySelector('#areaInp').value = areaName;
        document.querySelector('#areaId').value = areaId;
        document.querySelector('#areaCodes').style.display = "none";
        document.querySelector('#date').disabled = false;
    }

    function selectTown(b){
        var townId = b.id;
        var townName = b.getAttribute('name');
        var townCode = b.getAttribute('code');

        document.querySelector("#townId").value = townId;
        document.querySelector("#townInp").value = townCode + " - " + townName;
        document.querySelector("#town").style.display = "none";
        document.querySelector('#date').disabled = false;
    }

    function setRoute(c){
        var routeId = c.id;
        var routeCode = c.getAttribute('code');
        var routeName = c.getAttribute('name');

        document.querySelector("#routeId").value = routeId;
        document.querySelector("#routeInp").value = routeCode + " - " + routeName;
        document.querySelector("#routeCodes").style.display = "none";
        document.querySelector("#date").disabled = false;
    }

    var date = document.querySelector("#date");
    date.addEventListener("change", function(){
        if(date.value !== ""){
            document.querySelector("#month").disabled = false;
            document.querySelector("#totalArrears").disabled = false;
        } else { 
            document.querySelector("#month").disabled = true;
            document.querySelector("#totalArrears").disabled = true;
            document.querySelector(".printButton").disabled = true;
            document.querySelector("#month").value = "";
            document.querySelector("#totalArrears").value = "";
        }
    })

    var month = document.querySelector("#month");
    month.addEventListener("keyup", function(){
        if(month.value !== ""){
            document.querySelector(".printButton").disabled = false;
        } else {
            document.querySelector(".printButton").disabled = true;
        }
    })
    function selectedCons(a){
        // var selectCon = document.querySelector('#selectCons').value
        selectCon = a.value;
        console.log(selectCon);
    }
    function print(){
        var selected = "";
        var location = "";
        var areaId = document.querySelector("#areaId");
        var townId = document.querySelector("#townId");
        var routeId = document.querySelector("#routeId");
        var date = document.querySelector("#date").value;
        var month = document.querySelector("#month").value;
        var totalArrears = document.querySelector("#totalArrears").value;
        var toSend = "";

        if(areaId.value !== ""){
            location = areaId.value;
            selected = "area"
        } else if(townId.value !== ""){
            location = townId.value;
            selected = "town";
        } else if(routeId.value !== ""){
            location = routeId.value;
            selected = "route";
        }

        if(totalArrears !== ""){
            toSend = {
                "date": date,
                "month": month,
                "amount": totalArrears,
                "selected": selected,
                "constype": selectCon,
                "location": location
            }
        } else {
            toSend = {
                "date": date,
                "month": month,
                "selected": selected,
                "constype": selectCon,
                "location": location
            }
        }
        

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_list_of_consumer_for_disconnection")}}'
        window.open($url);
    }
</script>
@endsection
