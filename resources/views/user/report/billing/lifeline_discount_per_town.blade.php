@extends('layout.master')
@section('title', 'Lifeline Discount per Town')
@section('content')

<style>
   
    input {
        height: 50px;
        color: black;
        cursor: pointer;
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
        .townDiv {
            height: 250px;
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
            margin-top: 7%;
            margin-right: 2%;
            display: none;
        }
    #townTable {
        margin-top: 3%;
    }
    #billPeriodTable {
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

<p class="contentheader">Lifeline Discount per Town </p>
<div class="main">
    <table id="townTable" class="content-table">
        <tr>
            <td style="width: 12.5%;">
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
            <td style="width: 12.6%;">
                Billing Period: &nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td class="input-td">
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
    <div class="modal-content" style="margin-top: 100px; width: 30%; height: 500px; margin-top: -2%;">
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
        var newPage = page;
        const xhr = new XMLHttpRequest();
        var towns = "{{route('index.town', '?page=')}}" + newPage;
        xhr.open('GET', towns, true);
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
        document.querySelector("#searchTown").value = "";
        document.querySelector("#town").style.display = "none";
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

        $url = '{{route("print_lifeline_discount_per_town")}}'
        window.open($url);
    }
</script>
@endsection
