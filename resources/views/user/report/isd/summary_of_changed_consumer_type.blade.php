@extends('layout.master')
@section('title', 'Summary of Changed Consumer Type')
@section('content')

<style>
    #printBtn {
        width: 70px;
        margin-top: 5%;
        height: 40px;
        margin-right: 4.4%;
    }
    #date {
        cursor: pointer;
    }
</style>

<p class="contentheader">Summary of Changed Consumer Type</p>
<div class="main">
    <br>
    <table class="content-table">
        <tr>
            <td style='width: 12%;'>
                Town:
            </td>
            <td>
                <input type="text" id="townInp" onclick="showTown()" placeholder="Select Town" readonly>
                <input type="text" id="townId" hidden>
            </td>
        </tr>
        <tr> <td style='height: 40px;'> &nbsp; </td> </tr>
        <tr>
            <td>
                Date From:
            </td>
            <td>
                <input type="date" id="dateFrom" disabled>
            </td>
        </tr>
        <tr> <td style='height: 40px;'> &nbsp; </td> </tr>
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
                <button id="printBtn" onclick="print()" disabled>Print</button>
            </td>
        </tr>
    </table>
</div>

@include('include.modal.townmodal');

<script>
    function selectTown(a){
        var townId = a.id;
        var townName = a.getAttribute('name');
        var townCode = a.getAttribute('code');

        document.querySelector('#townId').value = townId;
        document.querySelector('#townInp').value =  townCode + " - " + townName;
        document.querySelector('#town').style.display = "none";
        document.querySelector('#dateFrom').disabled = false;
    }

    var dateFrom = document.querySelector('#dateFrom');
    dateFrom.addEventListener("change", function(){
        if(dateFrom.value !== ""){
            document.querySelector('#dateTo').disabled = false;
        }
    })

    var dateTo = document.querySelector('#dateTo');
    dateTo.addEventListener("change", function(){
        if(dateTo.value !== ""){
            document.querySelector('#printBtn').disabled = false;
        }
    })

    function print(){
        var townId = document.querySelector('#townId').value;
        var dateFrom = document.querySelector("#dateFrom").value;
        var dateto = document.querySelector('#dateTo').value;

        const toSend = {
            'townID': townId,
            'dateFrom': dateFrom,
            'dateTo': dateto
        }
        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_summary_of_changed_consumer_type")}}'
        window.open($url);
    }
</script>
@endsection
