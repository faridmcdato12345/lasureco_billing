@extends('layout.master') 
@section('title', 'Master List')
@section('content')
<style>
    #printBtn {
        margin-right: 2.7%;
        margin-top: 5%;
    }
    #sort {
        color: black;
    }
    input {
        cursor: pointer;
    }
    select {
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
    }#thead {
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
</style>
<p class="contentheader">Master List</p>
<div class="main">
    <br>
    <table class="content-table">
        <tr>
            <td style="width: 15%;">
               Route Code From:
            </td>
            <td>
                <input type="text" id="routeFrom" placeholder="Route from" onclick="showRoutes(a = 'routeFrom')" readonly>
                <input type="text" id="rc_code_from" hidden>
            </td>
        </tr>
        <tr> <td style="height: 50px;"> &nbsp; </td> </tr>
        <tr>
            <td>
               Route Code To:
            </td>
            <td>
                <input type="text" id="routeTo" placeholder="Route to" onclick="showRoutes(a = 'routeTo')"  readonly disabled>
                <input type="text" id="rc_code_to" hidden>  
            </td>
        </tr>
        <tr> <td style="height: 50px;"> &nbsp; </td> </tr>
        <tr>
            <td>
               Option:
            </td>
            <td>
                <select id="sort" disabled>
                    <option value="all">All</option>
                    <option value="bigload">Big loads</option>
                    <option value="senior">Senior Citizens </option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan=2>
            <button id="printBtn" onclick="printMasterList()" disabled>Print</button>
            </td>
        </tr>
        <input type="text" id="town_code_id" hidden> 
    </table>
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
                    output +=  "route='" + routeWhere + "' name='" + route[a].route_code +"' townID = '" + route[a].town_code[0].town_code_id + "' desc='" + route[a].route_desc + "'> <td>&nbsp;&nbsp;" + route[a].route_code + "</td>";
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
                                output +=  "name='" + route[a].route_code +"' route='" + routeWhere + "' townID = '" + route[a].town_code[0].town_code_id + "' desc='" + route[a].route_desc + "'> <td>&nbsp;&nbsp;" + route[a].route_code + "</td>";
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
        var townID = x.getAttribute('townID');

        if(route == "routeFrom") {
            document.querySelector("#rc_code_from").value = name;
            document.querySelector("#routeFrom").value = name + " - " + desc;
            document.querySelector("#routeCodes").style.display = "none";
            document.querySelector("#routeTo").disabled = false;
            document.querySelector("#town_code_id").value = townID;
        } else if(route == "routeTo") { 
            document.querySelector("#rc_code_to").value = name;
            document.querySelector("#routeTo").value = name + " - " + desc;
            document.querySelector("#routeCodes").style.display = "none";
            document.querySelector("#sort").disabled = false;
            document.querySelector("#printBtn").disabled = false;
        }
    }  

    function printMasterList(){
        var townID = document.querySelector("#town_code_id").value;
        var routeFrom = document.querySelector("#rc_code_from").value;
        var routeTo = document.querySelector("#rc_code_to").value;
        var sort = document.querySelector("#sort");
        var option = sort.value;

        const toSend = {
            'townID': townID,
            'routeFrom': routeFrom,
            'routeTo': routeTo,
            'option': option
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_master_list")}}'
        window.open($url);
    } 
</script>
@endsection
