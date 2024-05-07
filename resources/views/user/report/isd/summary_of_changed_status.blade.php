@extends('layout.master')
@section('title', 'Summary of Changed Status')
@section('content')

<style>
    #printBtn {
        width: 70px;
        margin-top: 5%;
        margin-right: 4.5%;
        height: 40px;
    }
    input {
        cursor: pointer;
    }
</style>

<p class="contentheader">Summary of Changed Status</p>
<div class="main">
    <br>
    <table class="content-table">
        <tr>
            <td colspan=2>
                <select id="selected" style="width: 25%; color: black; cursor: pointer;">
                    <option value="0"> Disconnection </option>
                    <option value="1"> Reconnection </option>
                    <option value="2"> New Connection </option>
                </select>
            </td>
        </tr>
        <tr height='50px'><td>&nbsp;</td></tr>
        <tr>
            <td width='12%'>
                Date From:
            </td>
            <td>
                <input type="date" id="dateFrom">
            </td>
        </tr>
        <tr height='50px'><td>&nbsp;</td></tr>
        <tr>
            <td>
                Date To:
            </td>
            <td>
                <input type="date" id="dateTo" disabled>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <button id="printBtn" onclick="printChangeStat()" disabled>Print</button>
            </td>
        </tr>
    </table>
</div>

@include('include.modal.townmodal')

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
            document.querySelector("#printBtn").disabled = false;
        } else {
            document.querySelector("#printBtn").disabled = true;
        }
    })

    function printChangeStat(){
        var dateFrom = document.querySelector("#dateFrom").value;
        var dateTo = document.querySelector("#dateTo").value; 

        const toSend = {
            'selected': document.querySelector("#selected").value,
            'dateFrom': dateFrom,
            'dateTo': dateTo
        }
        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_summary_of_changed_status")}}'
        window.open($url);
    }
</script>
@endsection
