@extends('layout.master')
@section('title', 'Summary of Energy Sales per Consumer Type')
@section('content')
<style>
    
    tr {
        cursor: pointer;
    }
    input {
        cursor: pointer;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #areaCodeTable {
        width: 100%;
        height: auto;
        color: black;
        border: 1px #ddd solid;
    }
    #areaCodeTable td{
        height: 45px;
        border-bottom: 1px #ddd solid;
        padding: 15px;
    }
    #areaCodeTable tr:hover{
        transition: background 1s;
        background: gray;
    }
    .areaDiv {
        /* height: 400px; */
        padding-left: 15px;
        padding-right: 15px;
        margin: 15px;
    }
    #printBtn {
        margin-top: 10%;
        margin-right: 2.8%;
        display: none;
    }
    #paginate {
        width: 100%;
        margin: auto;
        margin-top: 0.5%;
    }
    #paginate button {
        background-color: royalblue;
        border-radius: 3px;
        height: 35px;
        width: 100%; 
    }
    #paginate input {
        margin: auto;
    }
</style>

<p class="contentheader">Summary of Energy Sales per Consumer Type</p>
<div class="main">
    <br>
    <table class="content-table">
        <tr>
            <td>
                Area:
            </td>
            <td>
                <input type="text" id="areaInp" onclick="showArea()" placeholder="Select Area" readonly>
                <input type="text" id="areaId" hidden>
            </td>
        </tr>
        <tr> <td height="55px;"> &nbsp; </td> </tr>
        <tr>
            <td style = "width:15%;">
                Billing Period:
            </td>
            <td>
                <input type="month" id="month" disabled>
            </td>
        </tr>
        <tr>
            <td colspan=2>
            <button style='background-color: white; color: royalblue; height: 40px; float: right; margin-top: 5%; margin-right: 2.5%;'
                    onclick="printSales()"
                    class="printBtn" disabled> Print </button>
            </td>
        </tr>
    </table>
</div>

<div id="areaCodes" class="modal">
    <div class="modal-content" style="margin-top: 10px; width: 70%; height: auto;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Area Lookup</h3>
            <span href = "#areaCodes" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="areaDiv"> </div>
        </div>
    </div>
</div>

<script>
    var page = 1;
    function showArea(a) {
        const xhr = new XMLHttpRequest();
        var newPage = page;
        var route = "{{route('index.area')}}";
        xhr.open('GET', route, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                document.querySelector('.areaDiv').style.height = "auto";
                document.querySelector('.areaDiv').style.borderBottom = "none";
                document.querySelector('.areaDiv').style.overflow  = "none";
                document.querySelector('.areaDiv').style.overflowY  = "hidden";
                var response = JSON.parse(this.responseText);
                var area = response.data;
                        
                var output = "<table id='areaCodeTable'>";
                output += "<tr id='thead'> <td> Area Code </td> <td> Area </td> </tr>";
                output += "<tr onclick='setArea(this)' areaName='ALL AREA' areaId='all'> <td colspan=2> <center> ALL AREA </center> </td> </tr>";

                for(var a in area){
                    if(area[a].area_name != ""){
                        var areaName = area[a].area_name;
                        var areaId = area[a].area_id;
                        
                        output += "<tr onclick='setArea(this)' class='tbody' areaName='" + areaName + "' areaId='" + areaId + "'>";
                        output += "<td>" + areaId + "</td>";
                        output += "<td>" + areaName + "</td></tr>";
                    }
                }
                output += "</table>";
                document.querySelector('.areaDiv').innerHTML= output;
            }
        }
        document.querySelector('#areaCodes').style.display = "block";
    }

    function setArea(x){
        var areaId = x.getAttribute('areaId');
        var areaName = x.getAttribute('areaName');

        document.querySelector("#areaInp").value = areaName;
        document.querySelector("#areaId").value = areaId;
        document.querySelector("#areaCodes").style.display = "none";
        document.querySelector("#month").disabled = false;
        document.querySelector("#month").focus();
    }

    var month = document.querySelector("#month");
    month.addEventListener("change", function(){
        if(month.value !== "") {
            document.querySelector(".printBtn").disabled = false
        } else {
            document.querySelector(".printBtn").disabled = true;
        }
    })

    function printSales(){
        var month = document.querySelector('#month').value;
        var areaId = document.querySelector("#areaId").value;
        
        const toSend = {
            'location': areaId,
            'date': month
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_summary_of_Energy_Sales_per_Consumer_Type")}}'
        window.open($url);
    }
</script>
@endsection
