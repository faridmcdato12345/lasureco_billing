@extends('layout.master')
@section('title', 'Summary of Universal Charges')
@section('content')
<style>
    #areaInp {
        cursor: pointer;
    }
    select {
        color: black;
        cursor: pointer;
    }
    #printButton {
        float: right;
        background-color: white;
        color: royalblue;
        height: 40px;
        margin-right: 2.5%; 
        margin-top: 5%;
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
<p class="contentheader">Summary of Universal Charges</p>
<div class="main">
    <br>
    <table class="content-table">
        <tr>
            <td colspan=4>
                <table>
                    <tr>
                        <td style="width: 30px;">
                            <input type="radio" class="radio" name="chargeType" value="sale" checked>
                        </td>
                        <td>
                            Sales
                        </td>
                        <td style="width: 30px;"> </td>
                        <td style="width: 30px;">
                            <input type="radio" class="radio" name="chargeType" value="collection">
                        </td>
                        <td>
                            Collections
                        </td>
                    </tr>
                </table>
            </td>
        <tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td style="width: 15%;">
                Area Code:
            </td>
            <td>
                <input type="text" id="areaInp" onclick="showArea()" placeholder="Select Area" readonly>
                <input type="text" id="areaId" hidden>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td>
                Consumer Type:
            </td>
            <td>
                <select id="consType" disabled>
                    <option value="all" selected> All Consumer Types </option>
                    <option value="1"> IRRIGATION </option>
                    <option value="2"> COMMERCIAL WATER SYSTEM </option>
                    <option value="3"> BAPA </option>
                    <option value="4"> INDUSTRIAL </option>
                    <option value="5"> PUBLIC BUILDING </option>
                    <option value="6"> STREETLIGHTS </option>
                    <option value="7"> COMMERCIAL  </option>
                    <option value="8"> RESIDENTIAL  </option>
                </select>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td>
                Bill Period: 
            </td>
            <td>
                <input type="month" id="month" disabled>
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <button id="printButton" onclick="printSumm()" disabled> Print </button>
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
            {{-- <div class="row" style="width: 95%; margin: auto">
                <input type="text" class="form-control input-sm p-3" id="searchArea" placeholder="Search Area" style="cursor: auto;">
            </div> --}}
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
                // var lastPage = response.meta.last_page;
                        
                var output = "<table id='areaCodeTable'>";
                output += "<tr id='thead'> <td> Area Codes </td> <td> Area </td> </tr>";
                output += "<tr> <td onclick='setArea(this)' colspan=2 style='text-align: center; cursor: pointer;' areaName='ALL AREA' areaId='all'> ALL AREA </td> </tr>";
                
                for(var a in area){
                    // console.log(area[a].area_name);
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

    function setArea(a){
        var areaId = a.getAttribute('areaId');
        var areaName = a.getAttribute('areaName');

        document.querySelector("#areaInp").value = areaName;
        document.querySelector("#areaId").value = areaId;
        document.querySelector("#areaCodes").style.display = "none";
        document.querySelector("#consType").disabled = false; 
        document.querySelector("#month").disabled = false; 
    }

    var month = document.querySelector("#month");
    month.addEventListener("change", function(){
        if(month.value !== ""){
            document.querySelector("#printButton").disabled = false;
        } else {
            document.querySelector("#printButton").disabled = true;
        }
    })

    function printSumm() {
        var areaId = document.querySelector("#areaId").value;
        var month = document.querySelector("#month").value;
        var consTypeSelect = document.querySelector("#consType");
        var consType = consTypeSelect.value;

        var chargeType = document.getElementsByName('chargeType');
        for(i = 0; i < chargeType.length; i++) {
            if(chargeType[i].checked){
                var selected = chargeType[i].value;        
            }
        }

        const toSend = {
            "areaId": areaId,
            "date": month,
            "constype": consType,
            "selected": selected
        }
        // alert(selected + " - " + consType);
        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_summary_of_universal_charges")}}'
        window.open($url);
        location.reload();
    }
</script>
@endsection
