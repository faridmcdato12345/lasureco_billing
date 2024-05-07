@extends('layout.master')
@section('title', 'Lifeline Consumer - Detailed per Town')
@section('content')

<style>
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #townTbl {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #townTbl td{
        height: 45px;
        border-bottom: 1px #ddd solid;
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
    #printBtn {
        float: right;
        background-color: white;
        color: royalblue;
        border-radius: 3px;
        height: 40px;
        margin-right: 2.9%;
        margin-top: 7%;
    }
</style>

<p class="contentheader">Lifeline Consumer - Detailed per Town</p>
<div class="main">
    <br>
    <table class="content-table">
        <tr>
            <td style="width: 12%;">
                Town:
            </td>
            <td>
                <input type="text" id="townName" onclick="showTown()" placeholder="Select Town" readonly>
                <input type="text" id="townId" hidden>
            </td>
        </tr>
        <tr>
    </table> <br> <br>
    <table class="content-table">
        <tr>
            <td style="width: 12%;">
                Billing Period:
            </td>
            <td>
                <input type="month" id="month" disabled>
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <button id="printBtn" onclick="printLifeline()" disabled> Print </button>
            </td>
        </tr>
    </table>
</div>

<div id="town" class="modal">
    <div class="modal-content" style="width: 45%; height: 445px; margin-top: -100%;">
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
    var page = 1;
    function showTown(){
        const xhr = new XMLHttpRequest();
        var newPage = page;
        xhr.open('GET', 'http://10.12.10.100:8082/api/v1/town/?page=' + newPage);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                document.querySelector('.townDiv').style.overflowY  = "hidden";
                document.querySelector('.townDiv').style.height = "360px";
                document.querySelector('.townDiv').style.borderBottom = "none";
                document.querySelector('.townDiv').style.overflow  = "none";
                var response = JSON.parse(this.responseText);
                var town = response.data;
                var lastPage = response.meta.last_page;

                var output = "<table id='townTbl'> <tr id='thead'> <td> Area </td> ";
                output += "<td> &nbsp;  Town Code </td> <td> Town </td> </tr>";
                
                for(var a in town){
                    output += "<tr onclick='selectTown(this)' id='" + town[a].town_code_id + "' name='" + town[a].town_code_name + "' class='tbody'";
                    output += "code='" + town[a].town_code + "' area=" + town[a].area_code[0].area_id + "> <td>" + town[a].area_code[0].area_name + "</td>";
                    output += "<td>&nbsp;&nbsp;&nbsp;" + town[a].town_code + "</td>";
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
                    if(town != ""){
                        var output = "<table id='townTbl' style='height: 200px; overflow-y: scroll;'> <tr id='thead'> <td> Area Name </td>";
                        output += "<td> &nbsp;  Town Code </td> <td> Town </td> </tr>";
                        
                        for(var a in town){
                            output += "<tr onclick='selectTown(this)' id='" + town[a].town_code_id + "' name='" + town[a].town_code_name + "' class='tbody'";
                            output += "code='" + town[a].town_code + "' area=" + town[a].area_code[0].area_id +">";
                            output += "<td>" + town[a].area_code[0].area_name + "</td>";
                            output += "<td>&nbsp;&nbsp;&nbsp;" + town[a].town_code + "</td>";
                            output += "<td>&nbsp;" + town[a].town_code_name + "</td></tr>";
                        }
                        output += "</table>";
                        document.querySelector('.townDiv').innerHTML= output;
                        document.querySelector('.townDiv').style.height = "315px";
                        document.querySelector('.townDiv').style.borderBottom = "1px solid #ddd";
                        document.querySelector('.townDiv').style.overflowY  = "scroll";
                    } else {
                        var output = "<table style='color: black;'> <br> <br>"; 
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
        var area = a.getAttribute('area');

        document.querySelector("#townId").value = a.id;
        document.querySelector("#townName").value = town;
        document.querySelector("#town").style.display = "none";
        document.querySelector("#searchTown").value = "";
        document.querySelector("#month").disabled = false;
    }

    var month = document.querySelector("#month");
    month.addEventListener("change", function(){
        if(month.value !== ""){
            document.querySelector("#printBtn").disabled = false;
        } else {
            document.querySelector("#printBtn").disabled = true;
        }
    })

    function printLifeline(){
        var townId = document.querySelector("#townId").value;
        var date = document.querySelector("#month").value;

        const toSend = {
            'townId': townId,
            'date': date,
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_lifeline_consumer_detailed_per_town")}}'
        window.open($url);
    }
</script>
@endsection
