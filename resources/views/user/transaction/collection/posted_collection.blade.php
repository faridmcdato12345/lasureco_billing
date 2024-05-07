@extends('layout.master')
@section('title', 'Posted Collection')
@section('content')

<style>
    #printBtn {
        height: 45px;
        font-size: 18px;
        float: right;
        margin-right: 7.5%;
        border-radius: 3px;
        margin-top: 3%;
        display: none;
    }
    input {
        cursor: pointer;
    }
</style>

<p class="contentheader">Posted Collection</p>

<div class="main">
    <table class="content-table">
        <br>
        <tr>
            <td style="width: 13%;">
                Start Date: 
            </td>
            <td>
                <input type="date" id="startDate">
            </td>
        </tr>
        <tr><td style='height: 60px;'> &nbsp; </td></tr>
        <tr>
            <td style="width: 13%;">
                End Date: 
            </td>
            <td>
                <input type="date" id="endDate" disabled>
            </td>
        </tr>
    </table>
    <button id="printBtn" onclick="printPostedCollection()"> Print </button>
</div>

<script>
    var startDate = document.querySelector("#startDate");
    startDate.addEventListener("change", function(){
        if(startDate !== ""){
            document.querySelector("#endDate").disabled = false;
            document.querySelector("#endDate").focus();
        } else {
            document.querySelector("#endDate").disabled = true;
        }
    })

    var endDate = document.querySelector("#endDate");
    endDate.addEventListener("change", function(){
        if(endDate !== ""){
            document.querySelector("#printBtn").style.display = "block";
        } else {
            document.querySelector("#printBtn").style.display = "none";
        }
    })

    function printPostedCollection(){
        var startDate = document.querySelector("#startDate").value;
        var endDate = document.querySelector("#endDate").value;
       
        const toStore = {
            "startDate": startDate,
            "endDate": endDate
        }

        localStorage.setItem('data', JSON.stringify(toStore));

        $url = '{{route("print_posted_collection")}}'
        window.open($url);
    }
</script>
@endsection
