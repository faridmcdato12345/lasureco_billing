@extends('layout.master')
@section('title', 'Power Sales per Route')
@section('content')

<style>
    input {
        color: black;
        cursor: pointer;
        font-size: 25px;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #townTable {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
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
    #printBtn {
        background-color: white; 
        height: 45px; 
        color: royalblue;
        display: none;
        margin-top: 4p%;
        margin-right: 2.5%;
    }
    @media screen and (min-width:1361px) and (max-width: 1400px) {
        #printBtn {
            margin-top: 3.5%;
        }    
    }
    #routeTable input {
        width: 94%;
    }
    #billPeriodTable {
        display: none;
        margin-top: -1%;
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

<p class="contentheader">Power Sales per Route</p>
<div class="">
<br><br>
    <table id="routeTable" class="content-table">
        <tr>
            <td style="width: 12.2%;">
                Route from: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td id="routeFromInp">
                <input type="text" id="routeFrom" placeholder="Route from" onclick="showRoutes(a = 'routeFrom')" readonly>
                <input type="text" id="rc_code_from" hidden>
            </td>
            <td style="width: 10%;">
                &nbsp; Route to:    
            </td>
            <td id="routeToInp">
                <input type="text" id="routeTo" placeholder="Route to" onclick="showRoutes(a = 'routeTo')"  readonly>
                <input type="text" id="rc_code_to" hidden>
            </td>
        </tr>
    </table>

    <table id="billPeriodTable" class="content-table">
        <tr>
            <td style="width: 12.1%;">
               Bill Period:  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
            </td class="input-td">
            <td class="input-td"colspan=3>
                <input type="month" id="billPeriod">
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <button id="printBtn" onclick="printPowerSales()"> Print </button>
            </td>
        </tr>
    </table>

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
    var page = 1;    
    function showRoutes(a) {
        var routeWhere = a;
        var newPage = page;
        const xhr = new XMLHttpRequest();
        var routeCode = a.id;
        var routes = "{{route('index.route','?page=')}}" + newPage;
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
                    output += '<input type="text" id="searchRouteFrom" route="' + routeWhere + '" onfocusout="search(this)" placeholder="Search Route" style="cursor: auto;">';
                    output += '</td> </tr> </table>';
                    output += "<table id='routeCodeTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
                    output += "<td> Route </td> </tr>";
                } else {
                    var output = '<table style="width: 100%; color: black;">'; 
                    output += '<tr> <td> Search </td> <td>';
                    output += '<input type="text" id="searchRouteTo" route="' + routeWhere + '" onfocusout="search(this)" placeholder="Search Route" style="cursor: auto;">';
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
                if(page == 1) {
                    output += "<td> <button id='" + newPage + "' class='prev' route='" + routeWhere + "' button='prev' onclick='viewRates(this)' disabled> Prev </button> </td>";
                } else {
                    output += "<td> <button id='" + newPage + "' class='prev' route='" + routeWhere + "' button='prev' onclick='viewRates(this)' enabled> Prev </button> </td>";
                } 
                output += "<td> <input type='number' value='" + newPage + "' readonly> </td>";
                if(page == lastPage) {
                    output += "<td> <button id='" + newPage + "' class='next' route='" + routeWhere + "' button='next' onclick='viewRates(this)' disabled> Next </button> </td> </tr>";
                } else{
                    output += "<td> <button id='" + newPage + "' class='next' route='" + routeWhere + "' button='next' onclick='viewRates(this)' enabled> Next </button> </td> </tr>";  
                }
                output += "</table>";

                document.querySelector('.routeDiv').innerHTML= output;
            }
        }
        document.querySelector('#routeCodes').style.display = "block";
    }

    function viewRates(e){
        var pages = e.id;
        var route = e.getAttribute('route');
        var button = e.getAttribute('button');
        var newRoute = route;
        if(button == "next"){
            page += 1;
            document.querySelector(".prev").disabled = false;
            showRoutes(route)
        } else if(button == "prev"){
            page = page - 1;
            showRoutes(route)
        }
    }

    function search(a){
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
                            output += '<input type="text" id="searchRouteFrom" value="' + searchRoute.value + '" onfocusout="search(this)" placeholder="Search Route" style="cursor: auto;">';
                            output += '</td> </tr> </table>';
                            output += "<div style='overflow-y: scroll; height: 270px; border-bottom: 1px solid #ddd;'>"; 
                            output += "<table id='routeCodeTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
                            output += "<td> Route Description </td> </tr> </table>";
                            output += "<table id='routeCodeTable'>";

                            for(var a in route){
                                output += "<tr onclick='selectRoute(this)' id='" + route[a].route_code_id + "' class='tbody'";
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
                            output += '<input type="text" id="searchRouteFrom" value="' + searchRoute.value + '" onfocusout="search(this)" placeholder="Search Route" style="cursor: auto;">';
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
                            output += '<input type="text" id="searchRouteTo" value="' + searchRoute.value + '" onfocusout="search(this)" placeholder="Search Route" style="cursor: auto;">';
                            output += '</td> </tr> </table>';
                            output += "<div style='overflow-y: scroll; height: 270px; border-bottom: 1px solid #ddd;'>"; 
                            output += "<table id='routeCodeTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
                            output += "<td> Route Description </td> </tr> </table>";
                            output += "<table id='routeCodeTable'>";

                            for(var a in route){
                                output += "<tr onclick='selectRoute(this)' id='" + route[a].route_code_id + "' class='tbody'";
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
                            output += '<input type="text" id="searchRouteFrom" value="' + searchRoute.value + '" onfocusout="search(this)" placeholder="Search Route" style="cursor: auto;">';
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
            showBillPeriodTable()
        } else if(route == "routeTo") { 
            document.querySelector("#rc_code_to").value = name;
            document.querySelector("#routeTo").value = name + " - " + desc;
            document.querySelector("#routeCodes").style.display = "none";
            showBillPeriodTable()
        }
    }  

    function showBillPeriodTable() {
        var routeFromId = document.querySelector("#rc_code_from");
        var routeToId = document.querySelector("#rc_code_to");

        if(routeFromId.value !== "" && routeToId.value !== ""){
            document.querySelector("#billPeriodTable").style.display = "block";
            document.querySelector("#billPeriod").focus();
        } else {
            document.querySelector("#billPeriodTable").style.display = "none";
        }
    }

    document.querySelector("#billPeriod").addEventListener("change", function(){
        var billPeriod = document.querySelector("#billPeriod");

        if(billPeriod !== ""){
            document.querySelector("#printBtn").style.display = "block";
        } else {
            document.querySelector("#printBtn").style.display = "none";
        }
    })

    function printPowerSales(){
        var routeFrom = document.querySelector('#rc_code_from').value;
        var routeTo = document.querySelector('#rc_code_to').value;
        var billPeriod = document.querySelector('#billPeriod').value;
       
        const toSend = {
            'routeFrom': routeFrom,
            'routeTo': routeTo,
            'billPeriod': billPeriod
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_power_sales_per_route")}}'
        window.open($url);
    }
</script>
@endsection
