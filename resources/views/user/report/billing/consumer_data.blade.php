@extends('layout.master')
@section('title', 'Consumer Data')
@section('content')
<p class="contentheader">Consumer Data</p>

<style>
    #routeInp {
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
    input {
        color: black;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #bookNo {
        width: 97%;
    }
    #billPeriod {
        cursor: pointer;
    }
    #printBtn {
        background-color: white;
        color: royalblue;
        display: none; 
        border-radius: 3px;
        margin-right: 7.7%;
    }
    radio {
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

<div class="main">
    <br><br>
    <table class="content-table">
        <tr>
            <td style="width: 12%;">    
                Route
            </td>
            <td>
                <input type="text" id="routeInp" onclick="showRoutes()" placeholder="Select Route" readonly>
                <input type="text" id="routeId" style="display: none;">
            </td>
        </tr>
        <tr> <td style="height: 60px;"> &nbsp; </td></tr>
        <tr>
            <td>
                Billing Period
            </td>
            <td>
                <input type="month" id="billPeriod" disabled>
            </td>
        </tr>
        <tr> <td style="height: 50px;"> &nbsp; </td></tr>
        <tr>
            <td colspan=2>
                <table>
                    <tr>
                        <td>
                            <input type="radio" name="bill" id="active" value="1" checked>
                            <label for="active"> Active </label> 
                        </td>
                        <td>
                            &nbsp;&nbsp;
                            <input type="radio" name="bill" id="disconnected" value="2">
                            <label for="disconnected"> Disconnected </label>
                        </td>
                        <td>
                            &nbsp;&nbsp;
                            <input type="radio" name="bill" id="both" value="3">
                            <label for="both"> Both </label>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <button id="printBtn" onclick="printConsData()"> Print </button>
</div>

<div id="routes" class="modal">
    <div class="modal-content" style="margin-top: 30px; width: 40%; height: 430px;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Route Lookup</h3>
            <span href = "#routes" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <table style="width: 100%; color: black;"> 
                <tr>
                    <td>
                        Search
                    </td>
                    <td>
                        <input type="text" id="searchRoute" name="searchTown" placeholder="Search Route" style="cursor: auto;">
                    </td>
                </tr>
            </table>
            <div class="routeDiv"> </div>
        </div>
    </div>
</div>

<script>
    var page = 1;
    function showRoutes(){
        var newPage = page;
        document.querySelector('#routes').style.display = "block";
        const xhr = new XMLHttpRequest();
        var routes = "{{route('index.route', '?page=')}}" + newPage;
        xhr.open('GET', routes, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                document.querySelector('.routeDiv').style.overflowY  = "hidden";
                document.querySelector('.routeDiv').style.borderBottom = "none";
                document.querySelector('.routeDiv').style.height = "300px";
                var response = JSON.parse(this.responseText);
                var route = response.data;
                var lastPage = response.meta.last_page;

                var output = "<table id='routeTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
                output += "<td> Route Description </td> </tr>";
                
                for(var a in route){
                    output += "<tr onclick='selectRoute(this)' id='" + route[a].route_code_id + "' class='tbody'";
                    output +=  "name='" + route[a].route_desc +"' code='" + route[a].route_code + "'> <td>&nbsp;&nbsp;" + route[a].route_code + "</td>";
                    output += "<td>" + route[a].route_desc + "</td></tr>";
                }
                output += "</table>";

                output += "<table id='paginate'> <tr>";
                if(page == 1) {
                    output += "<td> <button id='" + newPage + "' class='prev' button='prev' onclick='viewRates(this)' disabled> Prev </button> </td>";
                } else {
                    output += "<td> <button id='" + newPage + "' class='prev' button='prev' onclick='viewRates(this)' enabled> Prev </button> </td>";
                } 
                output += "<td> <input type='number' value='" + newPage + "' readonly> </td>";
                if(page == lastPage) {
                    output += "<td> <button id='" + newPage + "' class='next' button='next' onclick='viewRates(this)' disabled> Next </button> </td> </tr>";
                } else{
                    output += "<td> <button id='" + newPage + "' class='next' button='next' onclick='viewRates(this)' enabled> Next </button> </td> </tr>";  
                }
                output += "</table>";

                document.querySelector('.routeDiv').innerHTML= output;
            }
        }
    }

    var searchRoute = document.querySelector("#searchRoute");
    searchRoute.addEventListener("change", function(){
        var xhr = new XMLHttpRequest();
        if(searchRoute.value !== ""){
            var route = "{{route('search.route',['req'=>':par'])}}"
            xhr.open('GET', route.replace(':par', searchRoute.value), true);
            xhr.send();
            xhr.onload = function(){
                if(this.status == 200){
                    var  response = JSON.parse(this.responseText);
                    var route = response.data;
                    if(route != ""){
                        var output = "<table id='routeTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
                        output += "<td> Route Description </td> </tr>";
                        
                        for(var a in route){
                            output += "<tr onclick='selectRoute(this)' id='" + route[a].route_code_id + "' class='tbody'";
                            output +=  "name='" + route[a].route_desc +"' code='" + route[a].route_code + "'> <td>&nbsp;&nbsp;" + route[a].route_code + "</td>";
                            output += "<td>" + route[a].route_desc + "</td></tr>";
                        }
                        output += "</table>";
                        document.querySelector('.routeDiv').innerHTML= output;
                        document.querySelector('.routeDiv').style.height = "290px";
                        document.querySelector('.routeDiv').style.borderBottom = "1px solid #ddd";
                        document.querySelector('.routeDiv').style.overflowY  = "scroll";
                    } else {
                        document.querySelector('.routeDiv').style.overflowY  = "hidden";
                        document.querySelector('.routeDiv').style.borderBottom = "none";
                        var output = "<table style='color: black; margin: auto;'> <br> <br>"; 
                        output += "<tr> <td style='font-size: 25px; color: gray;'> No Route found! </td> </tr> </table>"; 
                        document.querySelector('.routeDiv').innerHTML= output;
                    }
                }
            }
        } else {
            showRoutes()
        }
    })

    function viewRates(e){
        var pages = e.id;
        var button = e.getAttribute('button');
        
        if(button == "next"){
            page += 1;
            document.querySelector(".prev").disabled = false;
            showRoutes()
        } else if(button == "prev"){
            page = page - 1;
            showRoutes()
        }
    }

    function selectRoute(x){
        var id = x.id;
        var name = x.getAttribute('name');
        var code = x.getAttribute('code');

        document.querySelector('#routeId').value = id;
        document.querySelector('#routeInp').value = code + " - " + name;
        document.querySelector('#routes').style.display = "none";
        document.querySelector("#billPeriod").disabled = false;
    }

    document.querySelector('#billPeriod').addEventListener('change', function(){
        var billPeriod = document.querySelector('#billPeriod').value;

        if(billPeriod !== ""){
            document.querySelector('#printBtn').style.display = "block";
        } else {
            document.querySelector('#printBtn').style.display = "none";
        }
    })

    function printConsData(){
        var routeId = document.querySelector('#routeId').value;
        var billPeriod = document.querySelector('#billPeriod').value;

        var bill = document.getElementsByName('bill');
        for(i = 0; i < bill.length; i++) {
            if(bill[i].checked){
                var selected = bill[i].value;        
            }
        }

        const toSend = {
            'routeId': routeId,
            'billPeriod': billPeriod,
            'selected': selected
        }
        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_consumer_data")}}'
        window.open($url);
    }
</script>
@endsection
