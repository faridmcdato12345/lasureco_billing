@extends('layout.master')
@section('title', 'Sales per Type with Consumer Name')
@section('content')
<style>
    .container {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 1vw;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        color:white;
        font-weight:bold;
        margin-left:30px;
    }
    .container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0vw;
        width: 0vw;
    }
    .checkmark {
        position: absolute;
        top: 0;
        left:0;
        height: 1.5vw;
        width: 1.5vw;
        background-color: #eee;
    }
    .container:hover input ~ .checkmark {
        background-color: #ccc;
    }
    .container input:checked ~ .checkmark {
        background-color: #2196F3;
    }
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }
    .container input:checked ~ .checkmark:after {
        display: block;
    }
    .container .checkmark:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
    #townTable input {
        width: 91.5%;
    }
    #billTable {
        display: none;
        margin-top: -1%;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #areaTable {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #areaTable td{
        height: 40px;
        border-bottom: 1px #ddd solid;
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
    #areaInp {
        cursor: pointer;
        width: 94.5%;
    }
    #townTo {
        cursor: pointer;
        width: 82% !important;
    }
    #townFrom {
        cursor: pointer;
        width: 82% !important;
    }
    #printBtn {
        height: 45px;
        width: 7%;
        margin-top: 7%;
        font-size: 20px;
        margin-right: 7.2%;
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
    #routeCodeTable {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #routeCodeTable td{
        height: 40px;
        border-bottom: 1px #ddd solid;
    }
    #consType {
        width: 91.5%; 
        cursor: pointer; 
        color: black;
    }
    #period {
        cursor: pointer;
    }
</style>
<p class="contentheader">Sales per Type with Consumer Name</p>
<div class="main">
    <br>
    <table id="townTable" class="content-table">
        <tr>
            <td style="width: 15%;">
                Town Code From: 
            </td>
            <td  class="input-td">
                <input type="text" id="townFrom" placeholder="Select Town" onclick="showTown(x = 'townFrom')" readonly>
                <input type="text" id="townFromCode" hidden>
            </td>
            <td class="thead">
                To:
            </td>
            <td  class="input-td">
                <input type="text" id="townTo" placeholder="Select Town" onclick="showTown(x = 'townTo')" readonly>
                <input type="text" id="townToCode" hidden>
            </td>
        </tr>
        <tr><td> &nbsp; </td></tr><tr><td> &nbsp; </td></tr>
        <tr>
            <td>
                Consumer Type:
            </td>
            <td colspan=3>
                <select id="consType" disabled>
                    <option value="all"> All </option>
                    <option value="1"> Irrigation </option>
                    <option value="2"> Comm Water System </option>
                    <option value="3"> BAPA/MUPA </option>
                    <option value="4"> Industrial </option>
                    <option value="5"> Public Building </option>
                    <option value="6"> Streetlights </option>
                    <option value="7"> Commercial </option>
                    <option value="8"> Residential </option>
                </select>
            </td>
        </tr>
        <tr><td> &nbsp; </td></tr><tr><td> &nbsp; </td></tr>
        <tr>
            <td>
                Billing Period:
            </td>
            <td colspan=3>
                <input type="month" id="period" disabled> 
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <button id="printBtn" onclick="printSales()" disabled>Print</button>
            </td>
        </tr>
    </table>
    <input type="text" id="areaId" hidden>
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

<script>
    var page = 1;
    function showTown(x){
        var townWhere = x;
        var newPage = page;
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
                    output += "<tr onclick='selectTown(this)' id='" + town[a].town_code_id + "' class='tbody' areaId='" + town[a].area_code[0].area_id + "'";
                    output +=  "name='" + town[a].town_code_name +"' town='" + townWhere + "' code='" + town[a].town_code + "'> <td>&nbsp;&nbsp;" + town[a].town_code + "</td>";
                    output += "<td>" + town[a].town_code_name + "</td></tr>";
                }
                output += "</table>";

                output += "<table id='paginate'> <tr>";
                if(page == 1) {
                    output += "<td> <button id='" + newPage + "' class='prev' town='" + townWhere + "' button='prev' onclick='viewRates(this)' disabled> Prev </button> </td>";
                } else {
                    output += "<td> <button id='" + newPage + "' class='prev' town='" + townWhere + "' button='prev' onclick='viewRates(this)' enabled> Prev </button> </td>";
                } 
                output += "<td> <input type='number' value='" + newPage + "' readonly> </td>";
                if(page == lastPage) {
                    output += "<td> <button id='" + newPage + "' class='next' town='" + townWhere + "' button='next' onclick='viewRates(this)' disabled> Next </button> </td> </tr>";
                } else{
                    output += "<td> <button id='" + newPage + "' class='next' town='" + townWhere + "' button='next' onclick='viewRates(this)' enabled> Next </button> </td> </tr>";  
                }
                output += "</table>";

                document.querySelector('.townDiv').innerHTML= output;
            }
        }
        document.querySelector('#town').style.display = "block";
    }

    function viewRates(e){
        var pages = e.id;
        var town = e.getAttribute('town');
        var button = e.getAttribute('button');
        var newTown = town;
        if(button == "next"){
            page += 1;
            document.querySelector(".prev").disabled = false;
            showTown(town)
        } else if(button == "prev"){
            page = page - 1;
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
                                output += "<tr onclick='selectTown(this)' id='" + town[a].town_code_id + "' class='tbody' areaId='" + town[a].area_code[0].area_id + "'";
                                output +=  "name='" + town[a].town_code_name +"' town='" + townWhere + "' code='" + town[a].town_code + "'> <td>&nbsp;&nbsp;" + town[a].town_code + "</td>";
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
                                output += "<tr onclick='selectTown(this)' id='" + town[a].town_code_id + "' class='tbody' areaId='" + town[a].area_code[0].area_id + "'";
                                output +=  "name='" + town[a].town_code_name +"' town='" + townWhere + "' code='" + town[a].town_code + "'> <td>&nbsp;&nbsp;" + town[a].town_code + "</td>";
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
        var code = x.getAttribute('code');
        var areaId = x.getAttribute('areaId');
        
        if(town == "townFrom") {
            document.querySelector("#townFromCode").value = code;
            document.querySelector("#townFrom").value = code + " - " + name;
            document.querySelector("#town").style.display = "none";
            document.querySelector("#areaId").value = areaId;
            showBillTable()
        } else if(town == "townTo") { 
            document.querySelector("#townToCode").value = code;
            document.querySelector("#townTo").value = code + " - " + name;
            document.querySelector("#town").style.display = "none";
            document.querySelector("#areaId").value = areaId;
            showBillTable()
        }
    }

    function showBillTable(){
        var townFrom = document.querySelector("#townFromCode").value;
        var townTo = document.querySelector("#townToCode").value;

        if(townFrom !== "" && townTo !== "") {
            document.querySelector("#consType").disabled = false;
            document.querySelector("#period").disabled = false;
        } else {
            document.querySelector("#consType").disabled = true;
            document.querySelector("#period").disabled = true;
        }
    }

    var period = document.querySelector("#period");
    period.addEventListener("change", function(){
        if(period.value !== ""){
            document.querySelector("#printBtn").disabled = false;
        } else {
            document.querySelector("#printBtn").disabled = true;
        }
    })

    function printSales(){
        var townFromCode = document.querySelector("#townFromCode").value;
        var townToCode = document.querySelector("#townToCode").value;
        var billPeriod = document.querySelector("#period").value;
        var selected = document.querySelector("#consType").value;
        var areaId = document.querySelector("#areaId").value;

        const toSend = {
            "date": billPeriod,
            "town_code_from": townFromCode,
            "town_code_to": townToCode,
            "area_id": areaId,
            "selected": selected
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_sales_per_type_with_consumer_name")}}'
        window.open($url);
        location.reload();
    }
</script>
@endsection
