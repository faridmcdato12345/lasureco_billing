@extends('layout.master')
@section('title', 'Summary of Sales - Unbundled')
@section('content')

<style>
    .mainTable {
        margin-top: -3%;
    }
    input {
        cursor: pointer;
    }
    select {
        cursor: pointer;
    }
    #areaTable {
        display: block;
    }
    #townTable {
        display: none;
    }
    #routeTable {
        display: none;
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
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
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
    #townTbl {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #townTbl td{
        height: 40px;
        border-bottom: 1px #ddd solid;
    }
    #townTo {
        cursor: pointer;
    }
    #townFrom {
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
    #billTable {
        display: none;
    }
    #printBtn {
        background-color: white;
        color: royalblue;
        height: 45px; 
        border-radius: 3px;
        width: 6%;
        margin-right: 7%;
        display: none;
    }
</style>

<p class="contentheader">Summary of Sales - Unbundled</p>
<div class="main">
<br>
    <table class="content-table mainTable">
        <tr>
            <td>
                <input type="text" id="byWhat" hidden>
                <select id="by" style="color: black; width: 25%;">
                    <option value="area" selected>By Area</option>
                    <option value="town">By Town</option>
                    <option value="route">By Route</option>
                </select>
            </td>
        </tr>
    </table>

    <table id="areaTable" class="content-table">
        <tr>
            <td style="width: 15%;">
                Area From: 
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="text" id="areaNameFrom" onclick="showArea(this)" placeholder="Select Area from" readonly>
                <input type="text" id="areaFromId" hidden>
            </td>
            <td style="width: 15%; text-align: center;">
                Area To:
            </td>
            <td>
                <input type="text" id="areaNameTo" onclick="showArea(this)" placeholder="Select Area to" readonly>
                <input type="text" id="areaToId" hidden>
            </td>
        </tr>
    </table>

    <table id="townTable" class="content-table">
        <tr>
            <td style="width: 15%;">
                Town From:
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="text" id="townFrom" placeholder="Select Town" onclick="showTown(x = 'townFrom')" readonly>
                <input type="text" id="townFromId" hidden>
            </td>
            <td style="width: 15%; text-align: center;">
                Town To:
            </td>
            <td>
                <input type="text" id="townTo" placeholder="Select Town" onclick="showTown(x = 'townTo')" readonly>
                <input type="text" id="townToId" hidden>
            </td>
        </tr>
    </table>

    <table id="routeTable" class="content-table">
        <tr>
            <td style="width: 15%;">
                Route From:
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="text" id="routeFrom" placeholder="Select Route from" onclick="showRoutes(a = 'routeFrom')" readonly>
                <input type="text" id="rc_code_from" hidden>
            </td>
            <td style="width: 15%; text-align: center;">
                Route To:
            </td>
            <td>
                <input type="text" id="routeTo" placeholder="Route to" onclick="showRoutes(a = 'routeTo')"  readonly>
                <input type="text" id="rc_code_to" hidden>
            </td>
        </tr>
    </table>

    <table class="content-table" id="billTable">
        <tr>
            <td style="width: 15%;">
                Bill Period:
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="month" id="month" style="width: 98%;">
            </td>
        </tr>
    </table>
    <button id="printBtn" onclick="printBills()"> Print </button>
    
    <div id="area" class="modal">
        <div class="modal-content" style="margin-top: 100px; width: 30%; height: 300px;">
            <div class="modal-header" style="width: 100%; height: 60px;">
                <h3>Area Lookup</h3>
                <span href = "#area" class="closes" id="close">&times;</span>
            </div>
            <div class="modal-body">
                <div class="areaDiv"> </div>
            </div>
        </div>
    </div>

    <div id="town" class="modal">
        <div class="modal-content" style="margin-top: 30px; width: 40%; height: 415px; margin-top: -0.5%;">
            <div class="modal-header" style="width: 100%; height: 60px;">
                <h3>Town Lookup</h3>
                <span href = "#town" class="closes" id="close">&times;</span>
            </div>
            <div class="modal-body">
                <div class="townDiv"> </div>
            </div>
        </div>
    </div>

    <div id="routeCodes" class="modal">
        <div class="modal-content" style="margin-top: 30px; width: 40%; height: 420px;">
            <div class="modal-header" style="width: 100%; height: 60px;">
                <h3>Route Lookup</h3>
                <span href = "#routeCodes" class="closes" id="close">&times;</span>
            </div>
            <div class="modal-body">
                <div class="routeDiv"> </div>
            </div>
        </div>
    </div>
    
</div>
<script>
    function checkAreaInputs(){
        var areaFrom = document.querySelector("#areaFromId").value;
        var areaTo = document.querySelector("#areaToId").value;

        if(areaFrom !== "" && areaTo !== ""){
            document.querySelector("#billTable").style.display = "block";
            document.querySelector("#byWhat").value = "byArea";
        } else {
            document.querySelector("#billTable").style.display = "none";
        }
    } 

    function checkTownInputs(){
        var townFrom = document.querySelector("#townFromId").value;
        var townTo = document.querySelector("#townToId").value;

        if(townFrom !== "" && townTo !== ""){
            document.querySelector("#billTable").style.display = "block";
            document.querySelector("#byWhat").value = "byTown";
        } else {
            document.querySelector("#billTable").style.display = "none";
        }
    }

    function checkRouteInputs(){
        var routeFrom = document.querySelector("#rc_code_from").value;
        var routeTo = document.querySelector("#rc_code_to").value;

        if(routeFrom !== "" && routeTo !== ""){
            document.querySelector("#billTable").style.display = "block";
            document.querySelector("#byWhat").value = "byRoute";
        } else{
            document.querySelector("#billTable").style.display = "none";
        }
    }

    var by = document.querySelector("#by");
    by.addEventListener("change", function(){
    	if(by.value == "area") {
            document.querySelector("#billTable").style.display = "none";
            document.querySelector("#month").value = "";
            document.querySelector("#printBtn").style.display = "none";
            document.querySelector("#areaTable").style.display = "block";
            document.querySelector("#townTable").style.display = "none";
            document.querySelector("#routeTable").style.display = "none";
            document.querySelector("#townFromId").value = "";
            document.querySelector("#townFrom").value = "";
            document.querySelector("#townToId").value = "";
            document.querySelector("#townTo").value = "";
            document.querySelector("#rc_code_from").value = "";
            document.querySelector("#routeFrom").value = "";
            document.querySelector("#rc_code_to").value = "";
            document.querySelector("#routeTo").value = "";
        } else if(by.value == "town") {
            document.querySelector("#billTable").style.display = "none";
            document.querySelector("#month").value = "";
            document.querySelector("#printBtn").style.display = "none";
            document.querySelector("#areaTable").style.display = "none";
            document.querySelector("#townTable").style.display = "block";
            document.querySelector("#routeTable").style.display = "none";
            document.querySelector("#areaNameFrom").value = "";
            document.querySelector("#areaFromId").value = "";
            document.querySelector("#areaNameTo").value = "";
            document.querySelector("#areaToId").value = "";
            document.querySelector("#rc_code_from").value = "";
            document.querySelector("#routeFrom").value = "";
            document.querySelector("#rc_code_to").value = "";
            document.querySelector("#routeTo").value = "";
        } else if(by.value == "route"){
            document.querySelector("#billTable").style.display = "none";
            document.querySelector("#month").value = "";
            document.querySelector("#printBtn").style.display = "none";
            document.querySelector("#areaTable").style.display = "none";
            document.querySelector("#townTable").style.display = "none";
            document.querySelector("#routeTable").style.display = "block";
            document.querySelector("#areaNameFrom").value = "";
            document.querySelector("#areaFromId").value = "";
            document.querySelector("#areaNameTo").value = "";
            document.querySelector("#areaToId").value = "";
            document.querySelector("#townFromId").value = "";
            document.querySelector("#townFrom").value = "";
            document.querySelector("#townToId").value = "";
            document.querySelector("#townTo").value = "";
        }
    })

    function showArea(x){
        var locateArea = x.id;
        const xhr = new XMLHttpRequest();
        var areas = "{{route('index.area')}}";
        xhr.open('GET', areas, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                var response = JSON.parse(this.responseText);
                var area = response.data;
                var output = "<table id='areaTbl'> <tr id='thead'> <td> &nbsp;  Area ID </td>";
                output += "<td> Area </td> </tr>";
                
                for(var a in area){
                    output += "<tr onclick='selectArea(this)' id='" + area[a].area_id + "' name='" + area[a].area_name + "' class='tbody'";
                    output += "area = '" + locateArea + "'><td>&nbsp;&nbsp;&nbsp;" + area[a].area_id + "</td>";
                    output += "<td>&nbsp;" + area[a].area_name + "</td></tr>";
                }
                output += "</table>";

                document.querySelector('.areaDiv').innerHTML= output;
            }
        }
        document.querySelector('#area').style.display = "block";
    }

    function selectArea(a){
        var areaId = a.id;
        var areaName = a.getAttribute('name');
        var area = a.getAttribute('area');

        if(area == "areaNameFrom"){
            document.querySelector("#areaNameFrom").value = areaName;
            document.querySelector("#areaFromId").value = areaId;
            document.querySelector("#area").style.display = "none";
        } else {
            document.querySelector("#areaNameTo").value = areaName;
            document.querySelector("#areaToId").value = areaId;
            document.querySelector("#area").style.display = "none";
        }
        checkAreaInputs()
    }

    var routePage = 1;    
    function showRoutes(a) {
        var routeWhere = a;
        var newPage = routePage;
        const xhr = new XMLHttpRequest();
        var routeCode = a.id;

        var routes = "{{route('index.route', '?page=')}}" + newPage;
        xhr.open('GET', routes, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                var response = JSON.parse(this.responseText);
                var route = response.data;
                var lastPage = response.meta.last_page;

                if(routeWhere == "routeFrom"){
                    var output = '<table style="width: 100%; color: black;">'; 
                    output += '<tr> <td> Search </td> <td>';
                    output += '<input type="text" id="searchRouteFrom" route="' + routeWhere + '" onfocusout="searchRoute(this)" placeholder="Search Route" style="cursor: auto;">';
                    output += '</td> </tr> </table>';
                    output += "<table id='routeCodeTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
                    output += "<td> Route </td> </tr>";
                } else {
                    var output = '<table style="width: 100%; color: black;">'; 
                    output += '<tr> <td> Search </td> <td>';
                    output += '<input type="text" id="searchRouteTo" route="' + routeWhere + '" onfocusout="searchRoute(this)" placeholder="Search Route" style="cursor: auto;">';
                    output += '</td> </tr> </table>';
                    output += "<table id='routeCodeTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
                    output += "<td> Route </td> </tr>";
                }
                    
                for(var a in route){
                    output += "<tr onclick='selectRoute(this)' id='" + route[a].route_code_id + "' class='tbody'";
                    output +=  "route='" + routeWhere + "' name='" + route[a].route_code +"' desc='" + route[a].route_desc + "'> <td>&nbsp;&nbsp;" + route[a].route_code + "</td>";
                    output += "<td>" + route[a].route_desc + "</td></tr>";
                }
                output += "</table>";

                output += "<table id='paginate'> <tr>";
                if(routePage == 1) {
                    output += "<td> <button id='" + newPage + "' class='prev' route='" + routeWhere + "' button='prev' onclick='paginateRoute(this)' disabled> Prev </button> </td>";
                } else {
                    output += "<td> <button id='" + newPage + "' class='prev' route='" + routeWhere + "' button='prev' onclick='paginateRoute(this)' enabled> Prev </button> </td>";
                } 
                output += "<td> <input type='number' value='" + newPage + "' readonly> </td>";
                if(routePage == lastPage) {
                    output += "<td> <button id='" + newPage + "' class='next' route='" + routeWhere + "' button='next' onclick='paginateRoute(this)' disabled> Next </button> </td> </tr>";
                } else{
                    output += "<td> <button id='" + newPage + "' class='next' route='" + routeWhere + "' button='next' onclick='paginateRoute(this)' enabled> Next </button> </td> </tr>";  
                }
                output += "</table>";

                document.querySelector('.routeDiv').innerHTML= output;
            }
        }
        document.querySelector('#routeCodes').style.display = "block";
    }

    function paginateRoute(e){
        var pages = e.id;
        var route = e.getAttribute('route');
        var button = e.getAttribute('button');
        var newRoute = route;
        if(button == "next"){
            routePage += 1;
            document.querySelector(".prev").disabled = false;
            showRoutes(route)
        } else if(button == "prev"){
            routePage = routePage - 1;
            showRoutes(route)
        }
    }

    function searchRoute(a){
        var routeWhere = a.getAttribute("route");
        var routeLocate = a.id;
        var searchRoute = document.getElementById(routeLocate);
        const xhr = new XMLHttpRequest();

        if(routeLocate == "searchRouteFrom"){
            if(searchRoute.value !== ""){
                var route = "{{route('search.route',['req'=>':par'])}}"
                xhr.open('GET', route.replace(':par', searchRoute.value), true);
                xhr.send();
                xhr.onload = function(){
                    if(this.status == 200){
                        var  response = JSON.parse(this.responseText);
                        var route = response.data;
                        
                        if(route != ""){
                            var output = '<table style="width: 100%; color: black;">'; 
                            output += '<tr> <td> Search </td> <td>';
                            output += '<input type="text" id="searchRouteFrom" value="' + searchRoute.value + '" onfocusout="searchRoute(this)" placeholder="Search Route" style="cursor: auto;">';
                            output += '</td> </tr> </table>';
                            output += "<div style='overflow-y: scroll; height: 270px; border-bottom: 1px solid #ddd;'>"; 
                            output += "<table id='routeCodeTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
                            output += "<td> Route Description </td> </tr> </table>";
                            output += "<table id='routeCodeTable'>";

                            for(var a in route){
                                output += "<tr onclick='selectRoute(this)' id='" + route[a].route_code + "' class='tbody'";
                                output +=  "name='" + route[a].route_code +"' route='" + routeWhere + "' desc='" + route[a].route_desc + "'> <td>&nbsp;&nbsp;" + route[a].route_code + "</td>";
                                output += "<td>" + route[a].route_desc + "</td></tr>";
                            }
                            output += "</table>";
                            document.querySelector('.routeDiv').innerHTML= output;
                            document.querySelector('.routeDiv').style.height = "280px";
                            document.querySelector('.routeDiv').style.borderBottom = "1px solid #ddd";
                        } else {
                            var output = '<table style="width: 100%; color: black;">'; 
                            output += '<tr> <td> Search </td> <td>';
                            output += '<input type="text" id="searchRouteFrom" value="' + searchRoute.value + '" onfocusout="searchRoute(this)" placeholder="Search Route" style="cursor: auto;">';
                            output += '</td> </tr> </table>';
                            output += "<table style='color: black; margin: auto;'> <br> <br>"; 
                            output += "<tr> <td style='font-size: 25px; color: gray;'> No Route found! </td> </tr> </table>"; 
                            document.querySelector('.routeDiv').innerHTML= output;
                        }
                    }
                }
            } else {
                showRoutes('routeFrom')
            }
        } else if(routeLocate == "searchRouteTo") {
            if(searchRoute.value !== ""){
                var route = "{{route('search.route',['req'=>':par'])}}"
                xhr.open('GET', route.replace(':par', searchRoute.value), true);
                xhr.send();
                xhr.onload = function(){
                    if(this.status == 200){
                        var  response = JSON.parse(this.responseText);
                        var route = response.data;
                        
                        if(route != ""){
                            var output = '<table style="width: 100%; color: black;">'; 
                            output += '<tr> <td> Search </td> <td>';
                            output += '<input type="text" id="searchRouteTo" value="' + searchRoute.value + '" onfocusout="searchRoute(this)" placeholder="Search Route" style="cursor: auto;">';
                            output += '</td> </tr> </table>';
                            output += "<div style='overflow-y: scroll; height: 270px; border-bottom: 1px solid #ddd;'>"; 
                            output += "<table id='routeCodeTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
                            output += "<td> Route Description </td> </tr> </table>";
                            output += "<table id='routeCodeTable'>";

                            for(var a in route){
                                output += "<tr onclick='selectRoute(this)' id='" + route[a].route_code + "' class='tbody'";
                                output +=  "name='" + route[a].route_code +"' route='" + routeWhere + "'  desc='" + route[a].route_desc + "'> <td>&nbsp;&nbsp;" + route[a].route_code + "</td>";
                                output += "<td>" + route[a].route_desc + "</td></tr>";
                            }
                            output += "</table>";
                            document.querySelector('.routeDiv').innerHTML= output;
                            document.querySelector('.routeDiv').style.height = "280px";
                            document.querySelector('.routeDiv').style.borderBottom = "1px solid #ddd";
                        } else {
                            var output = '<table style="width: 100%; color: black;">'; 
                            output += '<tr> <td> Search </td> <td>';
                            output += '<input type="text" id="searchRouteFrom" value="' + searchRoute.value + '" onfocusout="searchRoute(this)" placeholder="Search Route" style="cursor: auto;">';
                            output += '</td> </tr> </table>';
                            output += "<table style='color: black; margin: auto;'> <br> <br>"; 
                            output += "<tr> <td style='font-size: 25px; color: gray;'> No Route found! </td> </tr> </table>"; 
                            document.querySelector('.routeDiv').innerHTML= output;
                        }
                    }
                }
            } else {
                showRoutes('routeTo')
            }
        }
    }

    function selectRoute(x){
        var id = x.id;
        var name = x.getAttribute('name');
        var route = x.getAttribute('route');
        var desc = x.getAttribute('desc');
        
        if(route == "routeFrom") {
            document.querySelector("#rc_code_from").value = name;
            document.querySelector("#routeFrom").value = name + " - " + desc;
            document.querySelector("#routeCodes").style.display = "none";
        } else if(route == "routeTo") { 
            document.querySelector("#rc_code_to").value = name;
            document.querySelector("#routeTo").value = name + " - " + desc;
            document.querySelector("#routeCodes").style.display = "none";
        }
        checkRouteInputs()
    } 

    var townPage = 1;
    function showTown(x){
        var townWhere = x;
        var newPage = townPage;
        const xhr = new XMLHttpRequest();
        var towns = "{{route('index.town', '?page=')}}" + newPage;
        xhr.open('GET', towns, true);
        xhr.send();

        
        xhr.onload = function(){
            if(this.status == 200) {
                var response = JSON.parse(this.responseText);
                var town = response.data;
                var lastPage = response.meta.last_page;

                if(townWhere == "townFrom"){
                    var output = '<table style="width: 100%; color: black;">'; 
                    output += '<tr> <td> Search </td> <td>';
                    output += '<input type="text" id="searchTownFrom" town="' + townWhere + '" onfocusout="search(this)" placeholder="Search Town" style="cursor: auto;">';
                    output += '</td> </tr> </table>';
                    output += "<table id='routeCodeTable'> <tr id='thead'> <td> &nbsp;  Town Code </td>";
                    output += "<td> Route </td> </tr>";
                } else {
                    var output = '<table style="width: 100%; color: black;">'; 
                    output += '<tr> <td> Search </td> <td>';
                    output += '<input type="text" id="searchTownTo" town="' + townWhere + '" onfocusout="search(this)" placeholder="Search Town" style="cursor: auto;">';
                    output += '</td> </tr> </table>';
                    output += "<table id='routeCodeTable'> <tr id='thead'> <td> &nbsp;  Town Code </td>";
                    output += "<td> Route </td> </tr>";
                }
                
                for(var a in town){
                    output += "<tr onclick='selectTown(this)' id='" + town[a].town_code + "' class='tbody'";
                    output +=  "town='" + townWhere + "' name='" + town[a].town_code_name +"'> <td>&nbsp;&nbsp;" + town[a].town_code + "</td>";
                    output += "<td>" + town[a].town_code_name + "</td></tr>";
                }
                output += "</table>";

                output += "</table>";

                output += "<table id='paginate'> <tr>";
                if(townPage == 1) {
                    output += "<td> <button id='" + newPage + "' class='prev' town='" + townWhere + "' button='prev' onclick='paginateTown(this)' disabled> Prev </button> </td>";
                } else {
                    output += "<td> <button id='" + newPage + "' class='prev' town='" + townWhere + "' button='prev' onclick='paginateTown(this)' enabled> Prev </button> </td>";
                } 
                output += "<td> <input type='number' value='" + newPage + "' readonly> </td>";
                if(townPage == lastPage) {
                    output += "<td> <button id='" + newPage + "' class='next' town='" + townWhere + "' button='next' onclick='paginateTown(this)' disabled> Next </button> </td> </tr>";
                } else{
                    output += "<td> <button id='" + newPage + "' class='next' town='" + townWhere + "' button='next' onclick='paginateTown(this)' enabled> Next </button> </td> </tr>";  
                }
                output += "</table>";

                document.querySelector('.townDiv').innerHTML= output;
            }
        }
        document.querySelector('#town').style.display = "block";
    }

    function paginateTown(e){
        var pages = e.id;
        var town = e.getAttribute('town');
        var button = e.getAttribute('button');
        var newTown = town;
        if(button == "next"){
            townPage += 1;
            document.querySelector(".prev").disabled = false;
            console.log(newTown);
            showTown(town)
        } else if(button == "prev"){
            townPage = townPage - 1;
            showTown(town)
        }
    }
    
    function search(a){
        var townWhere = a.getAttribute("town");
        var townLocate = a.id;
        var searchTown = document.getElementById(townLocate);
        const xhr = new XMLHttpRequest();

        if(townLocate == "searchTownFrom"){
            if(searchTown.value !== ""){
                var route = "{{route('search.town',['request'=>':par'])}}"
                xhr.open('GET', route.replace(':par', searchTown.value), true);
                xhr.send();
                xhr.onload = function(){
                    if(this.status == 200){
                        var  response = JSON.parse(this.responseText);
                        var town = response.data;
                        
                        if(town != ""){
                            var output = '<table style="width: 100%; color: black;">'; 
                            output += '<tr> <td> Search </td> <td>';
                            output += '<input type="text" id="searchTownFrom" value="' + searchTown.value + '" onfocusout="search(this)" placeholder="Search Town" style="cursor: auto;">';
                            output += '</td> </tr> </table>';
                            output += "<div style='overflow-y: scroll; height: 270px; border-bottom: 1px solid #ddd;'>"; 
                            output += "<table id='routeCodeTable'> <tr id='thead'> <td> &nbsp;  Town Code </td>";
                            output += "<td> Town Description </td> </tr> </table>";
                            output += "<table id='routeCodeTable'>";

                            for(var a in town){
                                output += "<tr onclick='selectTown(this)' id='" + town[a].town_code + "' class='tbody'";
                                output +=  "name='" + town[a].town_code_name +"' town='" + townWhere + "'> <td>&nbsp;&nbsp;" + town[a].town_code + "</td>";
                                output += "<td>" + town[a].town_code_name + "</td></tr>";
                            }
                            output += "</table>";
                            document.querySelector('.townDiv').innerHTML= output;
                            document.querySelector('.townDiv').style.height = "280px";
                            document.querySelector('.townDiv').style.borderBottom = "1px solid #ddd";
                        } else {
                            var output = '<table style="width: 100%; color: black;">'; 
                            output += '<tr> <td> Search </td> <td>';
                            output += '<input type="text" id="searchTownFrom" value="' + searchTown.value + '" onfocusout="search(this)" placeholder="Search Town" style="cursor: auto;">';
                            output += '</td> </tr> </table>';
                            output += "<table style='color: black; margin: auto;'> <br> <br>"; 
                            output += "<tr> <td style='font-size: 25px; color: gray;'> No Town found! </td> </tr> </table>"; 
                            document.querySelector('.townDiv').innerHTML= output;
                        }
                    }
                }
            } else {
                showTown(a = 'townFrom')
            }
        } else if(townLocate == "searchTownTo") {
            if(searchTown.value !== ""){
                var route = "{{route('search.town',['request'=>':par'])}}"
                xhr.open('GET', route.replace(':par', searchTown.value), true);
                xhr.send();
                xhr.onload = function(){
                    if(this.status == 200){
                        var  response = JSON.parse(this.responseText);
                        var town = response.data;
                        
                        if(town != ""){
                            var output = '<table style="width: 100%; color: black;">'; 
                            output += '<tr> <td> Search </td> <td>';
                            output += '<input type="text" id="searchTownTo" value="' + searchTown.value + '" onfocusout="search(this)" placeholder="Search Town" style="cursor: auto;">';
                            output += '</td> </tr> </table>';
                            output += "<div style='overflow-y: scroll; height: 270px; border-bottom: 1px solid #ddd;'>"; 
                            output += "<table id='routeCodeTable'> <tr id='thead'> <td> &nbsp;  Town Code </td>";
                            output += "<td> Town Description </td> </tr> </table>";
                            output += "<table id='routeCodeTable'>";

                            for(var a in town){
                                output += "<tr onclick='selectTown(this)' id='" + town[a].town_code + "' class='tbody'";
                                output +=  "name='" + town[a].town_code_name +"' town='" + townWhere + "'> <td>&nbsp;&nbsp;" + town[a].town_code + "</td>";
                                output += "<td>" + town[a].town_code_name + "</td></tr>";
                            }
                            output += "</table>";
                            document.querySelector('.townDiv').innerHTML= output;
                            document.querySelector('.townDiv').style.height = "280px";
                            document.querySelector('.townDiv').style.borderBottom = "1px solid #ddd";
                        } else {
                            var output = '<table style="width: 100%; color: black;">'; 
                            output += '<tr> <td> Search </td> <td>';
                            output += '<input type="text" id="searchTownTo" value="' + searchTown.value + '" onfocusout="search(this)" placeholder="Search Town" style="cursor: auto;">';
                            output += '</td> </tr> </table>';
                            output += "<table style='color: black; margin: auto;'> <br> <br>"; 
                            output += "<tr> <td style='font-size: 25px; color: gray;'> No Town found! </td> </tr> </table>"; 
                            document.querySelector('.townDiv').innerHTML= output;
                        }
                    }
                }
            } else {
                showTown(a = 'townTo')
            }
        }
    }
    
    function selectTown(x){
        var id = x.id;
        var name = x.getAttribute('name');
        var town = x.getAttribute('town');
        
        if(town == "townFrom") {
            document.querySelector("#townFromId").value = id;
            document.querySelector("#townFrom").value = id + " - " + name;
            document.querySelector("#town").style.display = "none";
        } else if(town == "townTo") { 
            document.querySelector("#townToId").value = id;
            document.querySelector("#townTo").value = id + " - " + name;
            document.querySelector("#town").style.display = "none";
        }
        checkTownInputs()
    }

    var billPeriod = document.querySelector("#month");
    billPeriod.addEventListener("change", function(){
        if(billPeriod.value !== ""){
            document.querySelector("#printBtn").style.display = "block";
        } else {
            document.querySelector("#printBtn").style.display = "none";
        }
    })

    function printBills(){
        var byWhat = document.querySelector("#byWhat").value;
        var billPeriod = document.querySelector("#month").value;
        var codeFrom = "";
        var codeTo = "";
        
        if(byWhat == "byArea"){
            codeFrom = document.querySelector("#areaFromId").value;
            codeTo = document.querySelector("#areaToId").value;
        } else if(byWhat == "byTown"){
            codeFrom = document.querySelector("#townFromId").value;
            codeTo = document.querySelector("#townToId").value;
        } else if(byWhat == "byRoute"){
            codeFrom = document.querySelector("#rc_code_from").value;
            codeTo = document.querySelector("#rc_code_to").value;
        }

        const toSend = {
            'code': byWhat,
            'date': billPeriod,
            'Code_From': codeFrom,
            'Code_To': codeTo
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_summary_of_bills_unbundled")}}'
        window.open($url);
    }
</script>
@endsection
