@extends('layout.master')
@section('title', 'Summary of Newly Entered Consumers')
@section('content')

<style>
    .printBtn {
        margin-top: 5%;
        margin-right: 4.5%;
        color: royalblue;
        background-color: white;
        height: 40px !important;
        float: right;
    }
    #selected {
        cursor: pointer;
    }
    input {
        cursor: pointer;
    }
</style>

<p class="contentheader">Summary of Newly Entered Consumers</p>
<div class="main">
    <br>
    <table class="content-table">
        <tr>
            <td height="20px"> &nbsp; </td>
        </tr>
        <tr>
            <td width="15%">
               Date From:
            </td>
            <td>
                <input type="date" id="dateFrom">
            </td>
        </tr>
        <tr> <td height="57px"> &nbsp; </td> </tr>
        <tr>
            <td>
               Date To:
            </td>
            <td>
            <input type="date" id="dateTo" disabled>
            </td>
        </tr>
        <tr>
            <td colspan=2>
            <button class="printBtn" onclick="showFilterModal()" disabled>Print</button>
            </td>
        </tr>
    </table>
</div>
    
<div id="filterModal" class="modal">
    <div class="modal-content" style="margin-top: 10px; width: 30%; height: auto;">
        <div class="modal-header" style="width: 100%; height: 60px; background-color: white;">
            <h3 style="color: #6D6D64;">Print by</h3>;
            <span href="#filterModal" class="closes" id="close" style="color: #d72503;"> &times; </span>
        </div>
        <div class="modal-body">
            <br>
            <table style="margin: auto; width: 90%;">
                <tr>
                    <td>
                        <button id="all" class="btn btn-primary" onclick="printAllConsumers()" style="width: 100%;"> All </button>
                    </td>
                </tr>
                <tr> <td height="15px;"></td> </tr>
                <tr>
                    <td>
                        <button id="area" class="btn btn-primary" onclick="showArea()" style="width: 100%;"> Area </button>
                    </td>
                </tr>
                <tr> <td height="15px;"></td> </tr>
                <tr>
                    <td>
                        <button id="townId" class="btn btn-primary" onclick="showTown()" style="width: 100%;"> Town </button>
                    </td>
                </tr>
                <tr> <td height="15px;"></td> </tr>
                <tr>
                    <td>
                        <button id="route" class="btn btn-primary" onclick="showRoutes()" style="width: 100%;"> Route </button>
                    </td>
                </tr>
            </table>
            <br>
        </div>
    </div>
</div>

@include('include.modal.areamodal')
@include('include.modal.townmodal')
@include('include.modal.routemodal')

<script>
    var dateFrom = document.querySelector("#dateFrom");
    dateFrom.addEventListener("change", function(){
        if(dateFrom.value !== ""){
            document.querySelector("#dateTo").disabled = false;
        } else {
            document.querySelector("#dateTo").disabled = true;
        }
    })

    var dateTo = document.querySelector("#dateTo");
    dateTo.addEventListener("change", function(){
        if(dateTo.value !== ""){
            document.querySelector(".printBtn").disabled = false;
        } else {
            document.querySelector(".printBtn").disabled = true;
        }
    })

    function showFilterModal(){
        document.querySelector("#filterModal").style.display = "block";
    }

    function setArea(x){
        var areaId = x.getAttribute('areaId');
        
        const toStore = {
            "id": areaId,
            "filter": "area",
            "date_from": dateFrom.value,
            "date_to": dateTo.value
        }

        localStorage.setItem('data', JSON.stringify(toStore));

        $url = '{{route("print_summary_of_newly_entered_consumers")}}'
        window.open($url);
        location.reload();
    }

    function selectTown(x){
        var townId = x.id;
        
        const toStore = {
            "id": townId,
            "filter": "town",
            "date_from": dateFrom.value,
            "date_to": dateTo.value
        }

        localStorage.setItem('data', JSON.stringify(toStore));

        $url = '{{route("print_summary_of_newly_entered_consumers")}}'
        window.open($url);
        location.reload();
    }

    function setRoute(x){
        var routeId = x.id;
        
        const toStore = {
            "id": routeId,
            "filter": "route",
            "date_from": dateFrom.value,
            "date_to": dateTo.value
        }

        localStorage.setItem('data', JSON.stringify(toStore));

        $url = '{{route("print_summary_of_newly_entered_consumers")}}'
        window.open($url);
        location.reload();
    }

    function printAllConsumers(x){
        const toStore = {
            "filter": "all",
            "date_from": dateFrom.value,
            "date_to": dateTo.value
        }

        localStorage.setItem('data', JSON.stringify(toStore));

        $url = '{{route("print_summary_of_newly_entered_consumers")}}'
        window.open($url);
        location.reload();
    }

    
</script>
@endsection
