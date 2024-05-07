@extends('layout.master')
@section('title', 'Lifeline Discount per Area')
@section('content')
<style>
    input {
        height: 50px;
        color: black;
        cursor: pointer;
        }
        #areaTable {
            margin-top: 2%;
        }
        table {
            width: 90%;
            margin: auto;
        }
        #areaName {
            width: 88.9%;
        }
        #billPeriodTable {
            display: none;
            margin-top: 7%;
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
        #areaTbl {
            width: 100%;
            color: black;
            border: 1px #ddd solid;
        }
        #areaTbl td{
            height: 50px;
            border-bottom: 1px #ddd solid;
        }
        #printBtn {
            float: right;
            margin-top: 10%;
            margin-right: 9%;
            display: none;
        }
        #billPeriod {
            margin-left: 1px;
            width: 90%;
        }
</style>
<p class="contentheader">Lifeline Discount per Area</p>
<div class="main">
    <table id="areaTable" class="content-table">
        <tr>
            <td style="width: 12%;">
                Area Code:
            </td>
            <td class="input-td">
                <input type="text" id="areaName"  onclick="showArea()" placeholder="Select Area" readonly>
                <input type="text" id="areaId" hidden>
            </td>
        </tr>
    </table>
    <table id="billPeriodTable" class="content-table">
        <tr>
            <td style="width: 12%;">
                Billing Period: &nbsp;&nbsp;&nbsp;
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

<div id="area" class="modal">
    <div class="modal-content" style="margin-top: 100px; width: 30%; height: 410px;">
        <div class="modal-header" style="width: 100%;">
            <h3>Area Lookup</h3>
            <span href = "#area" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="areaDiv"> </div>
        </div>
    </div>
</div>
<script>
    function showArea(){
        const xhr = new XMLHttpRequest();
        var areas = "{{route('index.area')}}";
        xhr.open('GET', areas, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                var response = JSON.parse(this.responseText);
                var area = response.data;
                var output = "<table id='areaTbl'> <tr id='thead'> <td> &nbsp;  Area ID </td>";
                output += "<td> Area </td> </tr>";
                
                for(var a in area){
                    output += "<tr onclick='selectArea(this)' id='" + area[a].area_id + "' name='" + area[a].area_name + "' class='tbody'>";
                    output += "<td>&nbsp;&nbsp;&nbsp;" + area[a].area_id + "</td>";
                    output += "<td>&nbsp;" + area[a].area_name + "</td></tr>";
                }
                output += "</table>";

                document.querySelector('.areaDiv').innerHTML= output;
            }
        }
        document.querySelector('#area').style.display = "block";
    }

    function selectArea(x){
        var areaname = x.getAttribute('name');

        document.querySelector("#areaId").value = x.id;
        document.querySelector("#areaName").value = areaname;

        document.querySelector("#area").style.display = "none";
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
        var areaId = document.querySelector('#areaId').value;
        var billDate = document.querySelector('#billPeriod').value;

        const toSend = {
            'areaId': areaId,
            'billPeriod': billDate
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_lifeline_discount_per_area")}}'
        window.open($url);
    }
</script>
@endsection
