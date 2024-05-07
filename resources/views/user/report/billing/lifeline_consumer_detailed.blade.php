@extends('layout.master')
@section('title', 'Lifeline Consumer – Detailed')
@section('content')

<style>
    input {
        color: black;
        cursor: pointer;
        width: 94%;
    }
    table {
        margin-top: 3.5%;
    }
    #billPeriodTable {
        display: none;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    .areaDiv {
        height: 250px;
    }
    #townTbl {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #townTbl td{
        height: 50px;
        border-bottom: 1px #ddd solid;
    }
    #printBtn {
        float: right;
        margin-top: 8%;
        margin-right: 5%;
        display: none;
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

<p class="contentheader">Lifeline Consumer – Detailed</p>
<div class="main">
    <table id="townTable" class="content-table">
        <tr>
            <td style="width: 12.1%;">
                Town Code: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="text" id="townName" onclick="showTown()" placeholder="Select Town" readonly>
                <input type="text" id="townId" hidden>
            </td>
        </tr>
    </table>
    <table id="billPeriodTable" class="content-table">
        <tr>
            <td style="width: 12.1%;">
                Billing Period: &nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="month" id="billPeriod">
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <button id="printBtn" onclick="printLifeLine()"> Print </button>
            </td>
        </tr>
    </table>
</div>

<div id="town" class="modal">
    <div class="modal-content" style="width: 30%; height: 485px;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Town Lookup</h3>
            <span href = "#town" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <table style="width: 100%; color: black; margin-top: -1%; margin-bottom: -3%;"> 
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
    window.onload=function(){
        var b = document.querySelector('#drpbtn2');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container2').childNodes;
        c[5].style.color="blue";
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
        document.querySelector("#billPeriodTable").style.display = "block";
    }

    document.querySelector("#billPeriod").addEventListener("change", function(){
        var billDate = document.querySelector("#billPeriod").value;

        if(billDate !== ""){
            document.querySelector("#printBtn").style.display = "block";
        } else {
            document.querySelector("#printBtn").style.display = "none";
        }
    })

    function printLifeLine(){
        var townId = document.querySelector('#townId').value;
        var billDate = document.querySelector('#billPeriod').value;

        const toSend = {
            'townId': townId,
            'billPeriod': billDate
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_lifeline_consumer_detailed")}}'
        window.open($url);
    }
</script>
@endsection
