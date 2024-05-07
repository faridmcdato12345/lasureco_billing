@extends('layout.master')
@section('title', 'Uploaded to Server')
@section('content')


<style>
    .printBtn {
        float: right; 
        color: royalblue;
        background-color: white; 
        height: 40px !important;
        margin-top: 5%;
        margin-right: 2.7%;
    }
    .mainTable {
        margin-left: 2%; 
    }
    input {
        height: 35px;
    }
    #listTbl {
        background-color: white;
        color: black;
        margin-top: 1% !important;
        width: 95%;
        margin: auto;
    }
    #listTable {
        overflow-y: scroll;
        height: 95%;
    }
    button {
        height: 35px;
    }
</style>

<p class="contentheader">UPLOADED TO SERVER</p>
<div class="main">
    <table class="mainTable">
        <tr> 
            <td> From &nbsp; </td>
            <td> <input type="date" id="monthFrom"> </td>
            <td> To &nbsp; </td>
            <td> <input type="date" id="monthTo"> </td>
            <td> <button type="button" class="btn btn-light" onclick="filter()"> Go </button> </td>
        </tr>
    </table>
    <div id="listTable"></div>
</div>
<script>
    window.addEventListener('load', (event) => {
        var xhr = new XMLHttpRequest();
        var uploadList = "{{route('uploaded.list')}}";
        xhr.open('GET', uploadList, true);

        xhr.send();

        xhr.onload = function(){
            if(xhr.status == 200){
                var output = "";
                var data = JSON.parse(this.responseText);
                var info = data.info;

                output += "<table class='table' id='listTbl'>";
                output += "<thead class='thead-light'>";
                output += "<tr><th> &nbsp;&nbsp;&nbsp; Teller </th>";
                output += "<th> Collection Date </th></tr></thead>";

                for(var x in info){
                    output += "<tr><td width='50%'> &nbsp;&nbsp;&nbsp;" + info[x].teller + "</td>";
                    output += "<td>" + info[x].collection_date + "</td></tr>";
                }
                output += "</table>";

                document.querySelector("#listTable").innerHTML = output;
                document.querySelector("#listTable").overflowY = "hidden";

            }
        }
    });

    function filter(){
        var toSend = new Object();
        var monthFrom = document.querySelector("#monthFrom").value;
        var monthTo = document.querySelector("#monthTo").value;
        var output = "";

        var xhr = new XMLHttpRequest();
        
        toSend.date_from = monthFrom;
        toSend.date_to = monthTo;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;

        var filterUpload = "{{route('uploaded.list.show')}}";
        xhr.open('POST', filterUpload, true);

        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                var output = "";
                var data = JSON.parse(this.responseText);
                var info = data.info;

                output += "<table class='table' id='listTbl'>";
                output += "<thead class='thead-light'>";
                output += "<tr><th> &nbsp;&nbsp;&nbsp; Teller </th>";
                output += "<th> Collection Date </th></tr></thead>";

                for(var x in info){
                    output += "<tr><td width='50%'> &nbsp;&nbsp;&nbsp;" + info[x].teller + "</td>";
                    output += "<td>" + info[x].collection_date + "</td></tr>";
                }
                output += "</table>";

                document.querySelector("#listTable").innerHTML = output;
            } else {
                alert("Not Uploaded!");
            }
        }
    }
</script>
@endsection
