@extends('layout.master')
@section('title', 'Summary of Posted Non-Bill Collection')
@section('content')

<style>
    input {
        color: black;
    }
    #areaInput {
        width: 99%;
        cursor: pointer;
    }
    #areaTable {
       width: 100%;
       color: black;
       border: 1px #ddd solid;
    }
    #areaTable td {
        border-bottom: 1px #ddd solid;
        height: 40px;
        cursor: pointer;
    }
    #tableHead {
        height: 40px;
        color: white;
        background-color: #5B9BD5;
    }
    #mainTable {
        margin-left: 70px;
        height: 350px;
        width: 75%;
        margin-top: -12%;
    }
    #dateTR {
        margin-left: 70px;
        width: 90%;
        display: none;
    }
    #from {
        width: 98%;
    }
    #to {
        width: 98%;
    }
    #printBtn {
        float: right;
        margin-top: 12%;
        display: none; 
        border-radius: 3px;
    }
    #areaModal {
        margin-top: 30px; 
        width: 40%; 
        height: 350px;
    }
    .dateTD {
        width: 40%;
    }
</style>

<p class="contentheader">Summary of Posted Non-Bill Collection</p>
<div class="main">
<br><br><br><br>
    <table id="mainTable" class="content-table">
        <tr>
            <td style="width: 20%;">
                Office Description:
            </td>
            <td class="input-td">
                <input id = "areaInput" type="text" placeholder="Select Area" onclick="showAreas()">
                <input type="text" id="areaId" style="display: none;">
            </td>
        </tr>
    </table>
    <table id="dateTR" class="content-table">
        <tr>
            <td>
                Date Covered From: &nbsp;&nbsp;&nbsp;
            </td>
            <td class="dateTD">
                <input type="date" id="from" class="date">
            </td>
            <td class="dateTD">
                <input type="date" id="to" class="date">
            </td>
        </tr>
        <tr>
            <td colspan=3>
                <button id="printBtn" onclick="printSummNB()"> Print </button>
            </td>
        </tr>
    </table>
</div>
<div id="areas" class="modal">
    <div class="modal-content" id="areaModal">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Area Lookup</h3>
            <span href = "#areas" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="areaDiv"> </div>
        </div>
    </div>
</div>

<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container').childNodes;
        c[9].style.color="blue";
    }

    function showAreas(){
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'http://10.12.10.100:8082/api/v1/area?page=3');
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200){
                var output = "<table id='areaTable'> <tr id='tableHead'>";
                output += "<td> &nbsp; Area ID </td> <td> Area Description </td> </tr>";
                var response = JSON.parse(this.responseText);
                var area = response.data;
                
                for(var x in area){
                    output += "<tr onclick='selectArea(this)' desc='" + area[x].area_name + "' id='" + area[x].area_id + "'>";
                    output += "<td> &nbsp;&nbsp;" + area[x].area_id + "</td>";
                    output += "<td>" + area[x].area_name + "</td> </tr>";
                }
                document.querySelector('.areaDiv').innerHTML = output;
            }
        }
        
        document.querySelector('#areas').style.display = "block";
    }

    function selectArea(x){
        var desc = x.getAttribute('desc');
        document.querySelector('#areaInput').value = desc;
        document.querySelector('#areaId').value = x.id;

        document.querySelector('#areas').style.display = "none";
        document.querySelector('#dateTR').style.display = "block";
    }

    var dateTo = "";
    var dateFrom = "";

    var from = document.querySelector('#from');
    from.addEventListener("change", function showFromInput(){
        dateFrom = document.querySelector('#from').value;
        
        if(dateTo !== "" && dateFrom !== ""){
            document.querySelector('#printBtn').style.display = "block";
        } else {
            document.querySelector('#printBtn').style.display = "none";
        }
    })

    var to = document.querySelector('#to');
    to.addEventListener("change", function showToInput(){
        dateTo = document.querySelector('#to').value;
        
        if(dateTo !== "" && dateFrom !== ""){
            document.querySelector('#printBtn').style.display = "block";
        } else {
            document.querySelector('#printBtn').style.display = "none";
        }
    })

    function printSummNB(){
        var areaId = document.querySelector('#areaId').value;
        var from = document.querySelector('#from').value;
        var to = document.querySelector('#to').value;
        var areaName = document.querySelector('#areaInput').value;
        var token = document.querySelector('meta[name="csrf-token"]').content;
        
        const toSend = {
            'ac_id': areaId,
            'to': to,
            'from': from,
            'areaName': areaName
        }
        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_summary_nb_collection")}}'
        window.open($url);
    }
</script>
@endsection
