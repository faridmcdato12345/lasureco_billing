@extends('layout.master')
@section('title', 'Summary of Consumer per KWH used')
@section('content')
<style>
    input {
        color: black;
        cursor: pointer;
        height: 50px;
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
    #townTable td{
        height: 40px;
        border-bottom: 1px #ddd solid;
    }
    .townDiv {
        height: 250px;
        margin-top: 1%; 
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
    #routeTable input {
        width: 91.5%;
    }
    .routeDiv {
        height: 250px;
        margin-top: 1%; 
    }
    #billPeriodTable {
        display: none;
    }
    #kwh {
        display: none;
        margin-top: -5%;
    }
    #printBtn {
        background-color: white; 
        height: 45px; 
        color: royalblue;
        display: none;
        margin-top: 7%;
        margin-right: -1%;
    }
    #routeTo {
        width: 97%;
    }
    #routeFrom {
        width: 97%;
        margin-left: 3px;
    }
    #kwhFrom {
        width: 97%;
    }
    #kwhTo {
        width: 99%;
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
<p class="contentheader"> Summary of Consumer per KWH used </p>
<div class="main">
    <table id="routeTable" class="content-table">
        <tr>
            <td style="width: 12%;">
                Route from: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            </td>
            <td style="width: 38%;">
                <input type="text" id="routeFrom" placeholder="Route from" onclick="showRoutes(a = 'routeFrom')" readonly>
                <input type="text" id="rc_code_from" hidden>
            </td>
            <td>
                Route to:
            </td>
            <td style="width: 38%;">
                <input type="text" id="routeTo" placeholder="Route to" onclick="showRoutes(a = 'routeTo')" readonly >
                <input type="text" id="rc_code_to" hidden>
            </td>
        </tr>
    </table>

    <table  id="billPeriodTable" class="content-table">
        <tr>
            <td style="width: 12.2%;"> 
               Bill Period: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td class="input-td"colspan=3>
                <input type="month" id="billPeriod">
            </td>
        </tr>
    </table>

    <table id="kwh" class="content-table">
        <tr>
            <td style="width: 12.5%;">
                KWH from: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td style="width: 38%;">
                <input type="number" id="kwhFrom" placeholder="KWH from" style="margin-left: 1px;">
            </td>
            <td style="width: 15%;">
                to:   
            </td>
            <td>
                <input type="number" id="kwhTo" placeholder="KWH to">
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <button id="printBtn" onclick="printPowerSales()"> Print </button>
            </td>
        </tr>
    </table>
</div>
    <div id="routeCodes" class="modal">
        <div class="modal-content" style="margin-top: 30px; width: 40%; height: 435px;">
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
    window.onload=function(){
        var b = document.querySelector('#drpbtn2');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container2').childNodes;
        c[5].style.color="blue";
    }
    
    var page = 1;    
    function showRoutes(a) {
        var routeWhere = a;
        var newPage = page;
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
                    output +=  "route='" + routeWhere + "' code='" + route[a].route_code +"' name='" + route[a].route_desc + "'> <td>&nbsp;&nbsp;" + route[a].route_code + "</td>";
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
                                output +=  "name='" + route[a].route_desc +"' route='" + routeWhere + "'> <td>&nbsp;&nbsp;" + route[a].route_code + "</td>";
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
                showRoutes(a = 'routeFrom')
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
                            output += '<input type="text" id="searchRouteFrom" value="' + searchRoute.value + '" onfocusout="search(this)" placeholder="Search Route" style="cursor: auto;">';
                            output += '</td> </tr> </table>';
                            output += "<div style='overflow-y: scroll; height: 270px; border-bottom: 1px solid #ddd;'>"; 
                            output += "<table id='routeCodeTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
                            output += "<td> Route Description </td> </tr> </table>";
                            output += "<table id='routeCodeTable'>";

                            for(var a in route){
                                output += "<tr onclick='selectRoute(this)' id='" + route[a].route_code_id + "' class='tbody'";
                                output +=  "name='" + route[a].route_code +"' route='" + routeWhere + "'> <td>&nbsp;&nbsp;" + route[a].route_code + "</td>";
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
                showRoutes(a = 'routeFrom')
            }
        }
    }

    function selectRoute(x){
        var id = x.id;
        var routeName = x.getAttribute('name');
        var routeCode = x.getAttribute('code');
        var route = x.getAttribute('route');
        
        if(route == "routeFrom") {
            document.querySelector("#rc_code_from").value = routeCode;
            document.querySelector("#routeFrom").value = routeCode + " - " + routeName;
            document.querySelector("#routeCodes").style.display = "none";
            showBillPeriodTable()
        } else if(route == "routeTo") { 
            document.querySelector("#rc_code_to").value = routeCode;
            document.querySelector("#routeTo").value = routeCode + " - " + routeName;
            document.querySelector("#routeCodes").style.display = "none";
            showBillPeriodTable()
        }
    } 

    function showBillPeriodTable() {
        var routeFromId = document.querySelector("#rc_code_from");
        var routeToId = document.querySelector("#rc_code_to");

        if(routeFromId.value !== "" && routeToId.value !== ""){
            document.querySelector("#billPeriodTable").style.display = "block";
        } else {
            document.querySelector("#billPeriodTable").style.display = "none";
        }
    }

    var billDate = document.querySelector("#billPeriod");
    billDate.addEventListener("change", function(){
        if(billDate.value !== ""){
            document.querySelector("#printBtn").style.display = "block";
        } else {
            document.querySelector("#printBtn").style.display = "none";
        }
    })

    function showPrintBtn(){
        var kwhFrom = document.querySelector("#kwhFrom").value;
        var kwhTo = document.querySelector("#kwhTo").value;

        if(kwhFrom !== "" && kwhTo !== ""){
            document.querySelector("#printBtn").style.display = "block";
        } else {
            document.querySelector("#printBtn").style.display = "none";
        }
    }

    function printPowerSales(){
        var townId = document.querySelector('#townId').value;
        var rcFrom = document.querySelector('#rc_code_from').value;
        var rcTo = document.querySelector('#rc_code_to').value;
        var date = document.querySelector('#billPeriod').value;
        var kwhFrom = document.querySelector('#kwhFrom').value;
        var kwhTo = document.querySelector('#kwhTo').value;

        const toSend = {
            'townId': townId,
            'rcFrom': rcFrom,
            'rcTo': rcTo,
            'date': date,
            'kwhFrom': kwhFrom,
            'kwhTo': kwhTo
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_summary_of_consumer_per_kwh_used")}}'
        window.open($url);
    }

    document.querySelector('#billPeriod').addEventListener('change', function(){
        var billPeriod = document.querySelector('#billPeriod').value;

        if(billPeriod !== ""){
            document.querySelector('#kwh').style.display = "block";
        } else {
            document.querySelector('#kwh').style.display = "none";
        }
    })

    document.querySelector("#kwhFrom").addEventListener("change", function(){
        showPrintBtn();
    }) 

    document.querySelector("#kwhTo").addEventListener("change", function(){
        showPrintBtn();
    }) 
</script>
@endsection
