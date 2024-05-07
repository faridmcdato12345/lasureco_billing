@extends('layout.master')
@section('title', 'Summary of Metered and Unmetered Consumers')
@section('content')

<style>
    #date {
        cursor: pointer;
    }
    .printBtn {
        background-color: white;
        color: royalblue; 
        height: 40px !important;
        float: right;
        margin-right: 4.5%;
        margin-top: 3%;
    }
    #townTable {
        display: none;
        width: 90%;
        margin: auto;
    }
    #areaTable {
        width: 90%;
        margin: auto;
    }
</style>

<p class="contentheader"> Summary of Metered and Unmetered Consumers </p>
<div class="main">
    <table class="content-table">
        <br>
        <tr>
            <td width="12.4%">
                Print By:
             </td>
             <td>
                <select id="printBy" style="color: black; cursor: pointer; width: 95%;"> 
                    <option value="area"> Area </option>
                    <option value="town"> Town </option>
                </select>
             </td>
        </tr>
    </table>
    <table id="areaTable">
        <tr>
            <td style='width: 12.5%;'>
                Area: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="text" id="areaInp" onclick="showArea()" placeholder="Select Area" readonly>
                <input type="text" id="areaId" hidden>
            </td>
        </tr>
    </table>
    <table id="townTable">
        <tr>
            <td style='width: 12.4%;'>
                Town: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="text" id="townInp" onclick="showTown()" placeholder="Select Town" readonly>
                <input type="text" id="townId" hidden>
            </td>
        </tr>
    </table>
    <br><br>
    <table class="content-table">
	    <tr>
            <td width="12.4%">
                Status:
            </td>
            <td colspan=3>
                <select class="status" style="color: black; cursor: pointer; width: 95%;" disabled> 
                    <option value="all"> All </option>
                    <option value="active"> Active </option>
                    <option value="disco"> Disconnected </option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <button class="printBtn" onclick="print()" disabled>Print</button>
            </td>
        </tr>
    </table>
</div>

@include('include.modal.areamodal');
@include('include.modal.townmodal');

<script>
    var byWhat = "";
    var printBy = document.querySelector("#printBy");
    printBy.addEventListener("change", function(){
    byWhat = printBy.value;
    console.log('onchange');
        if(byWhat == "area"){
            console.log('area');
            document.querySelector("#areaTable").style.display = "block";
            document.querySelector("#townTable").style.display = "none";
            document.querySelector("#areaInp").value = "";
            document.querySelector("#areaId").value = "";
            document.querySelector(".printBtn").disabled = true;
        } else {
            console.log('town');
            document.querySelector("#areaTable").style.display = "none";
            document.querySelector("#townTable").style.display = "block";
            document.querySelector("#townInp").value = "";
            document.querySelector("#townId").value = "";
            document.querySelector(".printBtn").disabled = true;
        }
    });

    function setArea(a){
        var areaId = a.getAttribute('areaId');
        var areaName = a.getAttribute('areaName');

        document.querySelector('#areaId').value = areaId;
        document.querySelector('#areaInp').value =  areaName;
        document.querySelector('#areaCodes').style.display = "none";
        document.querySelector(".printBtn").disabled = false;
        document.querySelector(".status").disabled = false;
    }
    
    function selectTown(a){
        var townId = a.id;
        var townCode = a.getAttribute('code');
        var townName = a.getAttribute('name');

        document.querySelector("#townInp").value = townCode + " - " + townName;
        document.querySelector("#townId").value = townId;
        document.querySelector("#town").style.display = "none";
        document.querySelector(".status").disabled = false;
        document.querySelector(".printBtn").disabled = false;
    }

    var areaId = document.querySelector("#areaId");
    var townId = document.querySelector("#townId");
    var locationName = "";

    if(areaId.value == "" && townId.value == ""){
        document.querySelector(".printBtn").disabled = true;
    } else {
        document.querySelector(".printBtn").disabled = false;
    }

    function print(){
        var selected = document.querySelector("#printBy").value;
        var filtered = document.querySelector(".status").value;
        var location = "";

        if(selected == "area"){
            location = document.querySelector("#areaId").value;
            locationName = document.querySelector("#areaInp").value;
        } else {
            location = document.querySelector("#townId").value;
            locationName = document.querySelector("#townInp").value;
        }
        
        const toStore = {
            'selected' : selected,
            'location' : location,
            'filtered' : filtered,
            'locationName' : locationName
        }
        localStorage.setItem('data', JSON.stringify(toStore));

        $url = '{{route("print_summary_of_metered_and_unmetered_consumers")}}'
        window.open($url);
    }
</script>
@endsection
