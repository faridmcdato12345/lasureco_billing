@extends('layout.master')
@section('title', 'General Detail Report')
@section('content')

<style> 
    #printBtn {
        float: right;
        height: 45px;
        margin-right: 17%;
    }
    #month {
        cursor: pointer;
    }

</style>

<p class="contentheader">General Detail Report</p>
<div class="main">
<br><br>
    <table class="content-table">
        <tr>
            <td style="width: 12%;">
                Town:
            </td>
            <td>
                <input type="text" id="townInp" onclick="showTown()" placeholder="Select Town" readonly>
                <input type="text" id="towntown" hidden>
                <input type="text" id="townId" hidden>
            </td>
        </tr>
        <tr>
            <td style="height: 70px;">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td>
                Bill Period:
            </td>
            <td>
                <input type="month" id="month" disabled>
            </td>
        </tr>
    </table>
    <br>
    <table class="content-table">
        <tr>  
            <td style="width: 17%;">
                <input type="radio" name="bill" id="onTime" value="1" checked disabled>
                <label for="onTime"> Billed On Time Only </label> 
            </td>
            <td>
                &nbsp;&nbsp;
                <input type="radio" name="bill" id="late" value="2" disabled>
                <label for="late"> Include Late Billings </label>
            </td>
            <td>
                <button id="printBtn" onclick="printGenDetails()"> Print </button>
            </td>
        </tr>
    </table>
</div>

@include('include.modal.townmodal')

<script>
    function selectTown(a){
        var townId = a.id;
        var townName = a.getAttribute('name');
        var townCode = a.getAttribute('code');

        document.querySelector('#towntown').value = townName;
        document.querySelector('#townInp').value = townCode + " - " + townName;
        document.querySelector('#townId').value = townId;
        document.querySelector('#month').disabled = false;
        document.querySelector('#month').focus();
        document.querySelector('#town').style.display = "none";
    }

    document.querySelector('#month').addEventListener("change", function(){
        var billPeriod = document.querySelector('#month');

        if(billPeriod.value !== ""){
            document.querySelector('#onTime').disabled = false;
            document.querySelector('#late').disabled = false;
            document.querySelector('#printBtn').disabled = false;
        } else {
            document.querySelector('#onTime').disabled = true;
            document.querySelector('#late').disabled = true;
            document.querySelector('#printBtn').disabled = true;
        }
    })

    function printGenDetails(){
        var townId = document.querySelector('#townId').value;
        var billPeriod = document.querySelector('#month').value;
        var town = document.querySelector("#towntown").value;

        var bill = document.getElementsByName('bill');
        for(i = 0; i < bill.length; i++) {
            if(bill[i].checked){
                var selected = bill[i].value;        
            }
        }

        const toSend = {
            'townId': townId,
            'billPeriod': billPeriod,
            'selected': selected,
            'town': town
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_general_detail_report")}}'
        window.open($url);
    }
</script>
@endsection
