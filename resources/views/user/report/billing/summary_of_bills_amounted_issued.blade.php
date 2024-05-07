@extends('layout.master')
@section('title', 'Summary of Bills - Amount/Issued')
@section('content')\

<style>
    table {
        width: 90%;
        margin: auto;
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
    #townTable {
        display: none;
        margin-top: 3%;
    }
    #billTable {
        display: none;
        margin-top: -1.5%;
    }
    #areaInp {
        cursor: pointer;
        width: 93.8%;    
        margin-left: -0.3%;
    }
    #townInp {
        cursor: pointer;   
    }

    #printBtn {
        margin-top: 7%;
        display: none;
        margin-right: 2.6%;
    }
    #month {
        cursor: pointer;
    }
    #townName {
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

<p class="contentheader">Summary of Bills - Amount/Issued</p>
<div class="main">
<br>
    <table class="content-table" style="margin-top: -2%;">
        <tr>
            <td style="width: 12%;">
               Area:
            </td>
            <td>
                <input type="text" id="areaInp" onclick="showArea()" placeholder="Select Area" readonly>
                <input type="text" id="areaId" hidden>
            </td>
        </tr>
    </table>

    <table class="content-table" id="townTable">
        <tr>
            <td style="width: 12%;">
               Town: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="text" id="townName" onclick="showTown()" placeholder="Select Town" readonly>
                <input type="text" id="townId" hidden>
            </td>
        </tr>
    </table>

    <table class="content-table" id="billTable">
        <tr>
            <td style="width: 12%;">
               Period: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="month" id="month">
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <button id="printBtn" onclick="printSummBill()">Print</button>
            </td>
        </tr>
    </table>
</div>

<div id="area" class="modal">
    <div class="modal-content" style="margin-top: 30px; width: 40%; height: 350px;">
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
    <div class="modal-content" style="width: 30%; height: 485px;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Town Lookup</h3>
            <span href = "#town" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <table style="width: 100%; color: black;"> 
                <tr>
                    <td>
                        Search
                    </td>
                    <td>
                        <input type="text" id="searchTown" name="searchTown" placeholder="Search Town" style="cursor: auto;">
                    </td>
                </tr>
            </table>
            <div class="townDiv"> </div>
        </div>
    </div>
</div>

<script>
    function showArea(){
        document.querySelector('#area').style.display = "block";
        const xhr = new XMLHttpRequest();
        var areas = "{{route('index.area')}}";
        xhr.open('GET', areas, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                var response = JSON.parse(this.responseText);
                var area = response.data;
                var output = "<table id='areaTable'> <tr id='thead'> <td> &nbsp;  Area Id </td>";
                output += "<td> Area Description </td> </tr>";
                
                for(var a in area){
                    output += "<tr onclick='selectArea(this)' id='" + area[a].area_id + "' class='tbody'";
                    output +=  "name='" + area[a].area_name +"'> <td>&nbsp;&nbsp;" + area[a].area_id + "</td>";
                    output += "<td>" + area[a].area_name + "</td></tr>";
                }
                output += "</table>";

                document.querySelector('.areaDiv').innerHTML= output;
            }
        }
    }

    function selectArea(x){
        var id = x.id;
        var name = x.getAttribute('name');
        
        document.querySelector('#areaId').value = id;
        document.querySelector('#areaInp').value = name;
        document.querySelector('#area').style.display = "none";

        document.querySelector('#townTable').style.display = "block";
    }

    var page = 1;
    function showTown(){
        const xhr = new XMLHttpRequest();
        var newPage = page;
        var towns = "{{route('index.town', '?page=')}}" + newPage;
        xhr.open('GET', towns, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                document.querySelector('.townDiv').style.overflowY  = "hidden";
                document.querySelector('.townDiv').style.height = "380px";
                document.querySelector('.townDiv').style.borderBottom = "none";
                document.querySelector('.townDiv').style.overflow  = "none";
                var response = JSON.parse(this.responseText);
                var town = response.data;
                var lastPage = response.meta.last_page;

                var output = "<table id='townTbl'> <tr id='thead'> <td> &nbsp;  Town Code </td>";
                output += "<td> Town </td> </tr>";
                
                for(var a in town){
                    output += "<tr onclick='selectTown(this)' id='" + town[a].town_code_id + "' name='" + town[a].town_code_name + "' class='tbody'";
                    output += "code='" + town[a].town_code + "'> <td>&nbsp;&nbsp;&nbsp;" + town[a].town_code + "</td>";
                    output += "<td>&nbsp;" + town[a].town_code_name + "</td></tr>";
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

                document.querySelector('.townDiv').innerHTML= output;
            }
        }
        document.querySelector('#town').style.display = "block";
    }

    var searchTown = document.querySelector("#searchTown");
    searchTown.addEventListener("change", function(){
        var xhr = new XMLHttpRequest();
        if(searchTown.value !== ""){
            var route = "{{route('search.town',['request'=>':par'])}}"
            xhr.open('GET', route.replace(':par', searchTown.value), true);
            xhr.send();
            xhr.onload = function(){
                if(this.status == 200){
                    var  response = JSON.parse(this.responseText);
                    var town = response.data;
                    console.log(town);
                    if(town != ""){
                        var output = "<table id='townTbl' style='height: 200px; overflow-y: scroll;'> <tr id='thead'> <td> &nbsp;  Town Code </td>";
                        output += "<td> Town </td> </tr>";
                        
                        for(var a in town){
                            output += "<tr onclick='selectTown(this)' id='" + town[a].town_code_id + "' name='" + town[a].town_code_name + "' class='tbody'";
                            output += "code='" + town[a].town_code + "'> <td>&nbsp;&nbsp;&nbsp;" + town[a].town_code + "</td>";
                            output += "<td>&nbsp;" + town[a].town_code_name + "</td></tr>";
                        }
                        output += "</table>";
                        document.querySelector('.townDiv').innerHTML= output;
                        document.querySelector('.townDiv').style.height = "340px";
                        document.querySelector('.townDiv').style.borderBottom = "1px solid #ddd";
                        document.querySelector('.townDiv').style.overflowY  = "scroll";
                    } else {
                        var output = "<table style='color: black; margin: auto;'> <br> <br>"; 
                        output += "<tr> <td style='font-size: 25px; color: gray;'> No Town found! </td> </tr> </table>"; 
                        document.querySelector('.townDiv').innerHTML= output;
                    }
                }
            }
        } else {
            showTown()
        }
    })

    function viewRates(e){
        var pages = e.id;
        var button = e.getAttribute('button');

        if(button == "next"){
            page += 1;
            document.querySelector(".prev").disabled = false;
            showTown()
        } else if(button == "prev"){
            page = page - 1;
            showTown()
        }
    }

    function selectTown(a){
        var townName = a.getAttribute('name');
        var townCode = a.getAttribute('code');
        var town = townCode + " - " + townName;

        document.querySelector("#townId").value = a.id;
        document.querySelector("#townName").value = town;

        document.querySelector("#town").style.display = "none";
        document.querySelector("#searchTown").value = "";
        document.querySelector("#billTable").style.display = "block";
    }

    month.addEventListener("change", function(){
        var billPeriod = month.value;

        if(billPeriod !== ""){
            document.querySelector("#printBtn").style.display = "block";
        } else {
            document.querySelector("#printBtn").style.display = "none";
        }
    })
    
    function printSummBill() {
        var townId = document.querySelector("#townId").value;
        var billPeriod = document.querySelector("#month").value;
        var area = document.querySelector("#areaInp").value;
        var town = document.querySelector("#townName").value;

        const toSend = {
            "town_id": townId,
            "date": billPeriod,
            "area": area,
            "town": town
        }
        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_summary_of_bills_amount_issued")}}'
        window.open($url);
        location.reload();
    }
</script>
@endsection
