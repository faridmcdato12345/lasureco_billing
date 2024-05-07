@extends('layout.master')
@section('title', 'Summary of Non-bill Collection')
@section('content')

<style>
    #prntBtn {
        float: right;
        color: royalblue;
        background-color: white;
        height: 40px;
        margin-top: 5%;
        margin-right: 2.7%;
    }
</style>

<p class="contentheader">Summary of Non-bill Collection</p>
<div class="main">
    <br><br>
    <table class="content-table">
        <tr>
            <td width="14%"> Date from: </td>
            <td> <input type="date" onchange="checkInputs()" id="from"> </td>
        </tr>
        <tr> <td height="60px"></td> </tr>
        <tr> 
            <td width="14%"> Date to: </td>
            <td> <input type="date" onchange="checkInputs()" id="to"> </td>
        </tr>
        <tr> 
            <td colspan=2> <button id="prntBtn" onclick="print()" disabled> Print </button> </td>
        </tr>
    </table>
</div>

<script>
    function checkInputs() {
        if(document.querySelector("#from").value !== "" && document.querySelector("#to").value !== ""){
            document.querySelector("#prntBtn").disabled = false;
        } else {
            document.querySelector("#prntBtn").disabled = true;
        }
    }
    
    function print(){
        var from = document.querySelector("#from").value;
        var to = document.querySelector("#to").value; 
        

        const toSend = {
            'from': from,
            'to': to
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_summary_of_non_bill_collection")}}';
        window.open($url);
    }
</script>
@endsection
