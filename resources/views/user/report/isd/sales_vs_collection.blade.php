@extends('layout.master')
@section('title', 'Sales vs Collection Report')
@section('content')

<style>
    #printDkna {
        color: royalblue;
        background-color: white;
        height: 40px;   
        float: right;
        margin-top: 5%;
        margin-right: 2%;    
    }
    #selected {
        cursor: pointer;
    }
    input {
        cursor: pointer;
    }
    #townTable {
        display: none;
    }
    #areaTownWidth {
        width: 15% !important;
    }
    #townCodeName {
        width: 97.5% !important;
    }
    #area {
        width: 97.5% !important;
    }
</style>

<p class="contentheader">Sales vs Collection Report</p>
<div class="main">
    <br>
    <table class="content-table">
        <tr>
            <td> <input type="checkbox" id="selected" value="all"> &nbsp; All </td>
        </tr>
        <tr>
            <td height="20px"> &nbsp; </td>
        </tr>
        <tr>
            <td width="15%">
               Print By:
            </td>
            <td>
                <select id="printBy" style="color: black; cursor: pointer"> 
                    <option value="area"> Area </option>
                    <option value="town"> Town </option>
                </select>
            </td>
        </tr>
    </table>
    <table class="content-table" id="areaTable">
        <tr>
            <td id="areaTownWidth">
                Area: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="text" id="area" placeholder="Select Area" onclick="showArea()" readonly>
                <input type="text" id="areaId" hidden>
            </td>
        </tr>
    </table>
    <table class="content-table" id="townTable">
        <tr>
            <td id="areaTownWidth">
                Town: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="text" id="townCodeName" placeholder="Select Town" onclick="showTown()" readonly>
                <input type="text" id="townId" hidden>
            </td>
        </tr>
    </table>
    <table class="content-table">
        <tr>
            <td width="15%">
               Billing Period:
            </td>
            <td>
            <input type="month" id="billPeriod" disabled>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <button id="printDkna" onclick="print()" disabled>Print</button>
            </td>
        </tr>
    </table>
</div>

@include('include.modal.areamodal')
@include('include.modal.townmodal')

<script>
    var byWhat = "";
    var printBy = document.querySelector("#printBy");
    printBy.addEventListener("change", function(){
        byWhat = printBy.value;
        
        if(byWhat == "area"){
            document.querySelector("#areaTable").style.display = "block";
            document.querySelector("#townTable").style.display = "none";
            document.querySelector("#areaTable").style.marginTop = "3.3%";
            document.querySelector("#areaTable").style.marginBottom = "-3.3%";
            document.querySelector("#area").value = "";
            document.querySelector("#areaId").value = "";
            document.querySelector("#billPeriod").disabled = true;
            document.querySelector("#billPeriod").value = "";
        } else {
            document.querySelector("#areaTable").style.display = "none";
            document.querySelector("#townTable").style.display = "block";
            document.querySelector("#townTable").style.marginTop = "3.3%";
            document.querySelector("#townTable").style.marginBottom = "-3.3%";
            document.querySelector("#townCodeName").value = "";
            document.querySelector("#townId").value = "";
            document.querySelector("#billPeriod").disabled = true;
            document.querySelector("#billPeriod").value = "";
        }
    })

    var check = "";

    var selected = document.querySelector("#selected");
    selected.addEventListener("change", function(){
        check = selected.checked;
        
        if(check == true) {
            document.querySelector("#billPeriod").disabled = false;
            document.querySelector("#billPeriod").value = "";
            document.querySelector("#area").value = "";
            document.querySelector("#area").disabled = true;
            document.querySelector("#areaId").value = ""; 
            document.querySelector("#townCodeName").value = "";
            document.querySelector("#townCodeName").disabled = true;
            document.querySelector("#townId").value = "";
            document.querySelector("#printDkna").disabled = true;
        } else {
            document.querySelector("#area").disabled = false;
            document.querySelector("#townCodeName").disabled = false;
            document.querySelector("#billPeriod").disabled = true;
            document.querySelector("#billPeriod").value = "";
            document.querySelector("#printDkna").disabled = true;
        }
    })

    function setArea(a){
        var areaId = a.getAttribute("areaId");
        var areaName = a.getAttribute("areaName");
        document.querySelector("#area").value = areaId + " - " + areaName;
        document.querySelector("#areaId").value = areaId;
        document.querySelector("#areaCodes").style.display = "none";
        document.querySelector("#billPeriod").disabled = false;
    }

    function selectTown(x) {
        var townId = x.id;
        var townCode = x.getAttribute("code");
        var townName = x.getAttribute("name");
        document.querySelector("#townCodeName").value = townCode + " - " + townName;
        document.querySelector("#townId").value = townId;
        document.querySelector("#town").style.display = "none";
        document.querySelector("#billPeriod").disabled = false;
    }

    var billPeriod = document.querySelector("#billPeriod");
    billPeriod.addEventListener("change", function(){
        if(billPeriod.value !== ""){
            document.querySelector("#printDkna").disabled = false;
        } else {
            document.querySelector("#printDkna").disabled = true;
        }
    })

    function print(){
        if(check == true) {
            var billPeriod = document.querySelector("#billPeriod").value;
            
            const toSend = {
                'selected': selected.value,
                'date': billPeriod
            }
            localStorage.setItem('data', JSON.stringify(toSend));
        } else {
            var printBy = document.querySelector("#printBy").value;
            var billPeriod = document.querySelector("#billPeriod").value;
            var location = "";
            var townId = document.querySelector("#townId");
            var areaId = document.querySelector("#areaId");
            
            if(townId.value !== ""){
                location = townId.value;
            }

            if(areaId.value !== ""){
                location = areaId.value;
            }

            const toSend = {
                'selected': printBy,
                'date': billPeriod,
                'location': location
            }
            localStorage.setItem('data', JSON.stringify(toSend));
        } 

        $url = '{{route("print_sales_vs_collection")}}'
        
        window.open($url);
        window.location.reload();
    }
</script>
@endsection
