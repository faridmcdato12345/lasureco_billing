@extends('layout.master')
@section('title', 'Summary of Bills - Additional')
@section('content')

<style>
    #maintable {
        margin: auto;
    }
    #routeInp {
        cursor: pointer;
        width: 93.5%;
    }
    #bookTable{
        margin-top: 3%;
        margin-left: 5%;
        display: none;
    }
    #bookTable td {
        height: 140px;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #routeTable {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #routeTable td{
        height: 40px;
        border-bottom: 1px #ddd solid;
    }
    input {
        color: black;
    }
    #printBtn {
        float: right;
        background-color: white;
        color: royalblue;
        margin-right: 2.5%;
        display: none; 
        height: 45px;
        width: 7%;
        font-size: 80%;
        margin-top: -2%;
    }
    #recap {
        height: 20px;
        width: 20px;
        cursor: pointer;
    }
    #recapDiv {
        display: none;
        margin-left: 2%;
        margin-top: -5%;
    }
    #bookNo {
        margin-left: 2px;
    }
    @media screen and (min-width:1361px) and (max-width: 1400px) {
        #bookTable {
            margin-top: -0.5%;
        }
        #bookTable td {
            height: 120px;
        }
    }
    #billPeriod {
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

<p class="contentheader">Summary of Bills - Additional</p>
<div class="main">
    <table id="maintable" class="content-table">
        <tr>
            <td> <input type="checkbox" name="checkbox"> 
                <label for="checkbox"> All </label>
            </td>
        </tr>
        <tr>
            <td style="width: 12%;">    
                Route
            </td>
            <td>
                <input type="text" id="routeInp" onclick="showRoutes()" placeholder="Select Route" readonly>
                <input type="text" id="routeId" style="display: none;">
            </td>
        </tr>
    </table>
    <table id="bookTable" class="content-table">
        <tr>
            <td style="width: 12%;">
                Billing Period &nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="month" id="billPeriod">
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <div id="recapDiv">
                    <input type="checkbox" id="recap" name="recapCheckbox">
                    &nbsp; <label for="recapCheckbox" id="recapLabel"> Recap only? </label>
                </div>
                <button id="printBtn" onclick="printSummBill()"> Print </button>
            </td>
        </tr>
    </table>
</div>

@include('include.modal.routemodal')

<script>
    function setRoute(x){
        var id = x.id;
        var name = x.getAttribute('name');
        var code = x.getAttribute('code');

        document.querySelector('#routeId').value = id;
        document.querySelector('#routeInp').value = code + " - " + name;
        document.querySelector('#routeCodes').style.display = "none";
        document.querySelector("#searchRoute").value = "";
        document.querySelector('#bookTable').style.display = "block";
    }

    document.querySelector('#billPeriod').addEventListener('change', function(){
        var billPeriod = document.querySelector('#billPeriod').value;

        if(billPeriod !== ""){
            document.querySelector('#printBtn').style.display = "block";
            document.querySelector('#recapDiv').style.display = "block";
        } else {
            document.querySelector('#printBtn').style.display = "none";
            document.querySelector('#recapDiv').style.display = "none";
        }
    })

    function printSummBill(){
        var routeId = document.querySelector('#routeId').value;
        var billPeriod = document.querySelector('#billPeriod').value;
        var recap = document.querySelector('#recap');
        var cap = "";

        if(recap.checked == true){
            cap = "Yes";
        } else {
            cap = "No";
        }
        const toSend = {
            'routeId': routeId,
            'billPeriod': billPeriod,
            'type': 'additional',
            'recap': cap
        }
        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_summary_of_bills_additional")}}'
        window.open($url);
    }

    var checkbox = document.querySelector("input[name=checkbox]");

    checkbox.addEventListener('change', function() {
    if (this.checked) {
        document.querySelector('#bookTable').style.display = "block";
        document.querySelector('#routeInp').disabled = true;
        document.querySelector('#routeInp').value = "";
        document.querySelector('#routeId').value = "all";
    } else {
        document.querySelector('#bookTable').style.display = "none";
        document.querySelector('#routeInp').disabled = false;
    }
    });
</script>
@endsection
