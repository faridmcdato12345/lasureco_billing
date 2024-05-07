@extends('layout.master')
@section('title', 'Summary of Bill Adjustment Detailed')
@section('content')

<style>
    table{
        margin: auto;
        width: 90%;
    }
    input {
        color: black;
        width: 95%;
        cursor: pointer;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #townTbl {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #townTbl td{
        height: 50px;
        border-bottom: 1px #ddd solid;
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


<p class="contentheader">Summary of Bill Adjustment Detailed</p>

<div class="m-5 p-5" style="background-color: white; height: 30%">

    <div class="row m-2" style="color:black">
        <div class="col-3 p-1">Town From:</div>
        <div class="col-3"><input class="form-control" onclick="showTown(this)" name="" type="button" id="town-m" value="Select Town"></div>
        <div class="col-3 p-1">Town To:</div>
        <div class="col-3"><input class="form-control" onclick="showTown(this)" name="" type="button" id="town-m2" value="Select Town"></div>
    </div>
    <div class="row m-2" style="color:black">
        <div class="col-3 p-1">Route From:</div>
        <div class="col-3"><input class="form-control" onclick="showTown(this)" name="" type="button" id="town-m" value="Select Town"></div>
        <div class="col-3 p-1">Route To:</div>
        <div class="col-3"><input class="form-control" onclick="showTown(this)" name="" type="button" id="town-m2" value="Select Town"></div>
    </div>
    <div class="row m-2" style="color:black">
        <div class="col-3 p-1">Adjustment Date From:</div>
        <div class="col-3"><input class="form-control" type="date" id="date1"></div>
        <div class="col-3 p-1">Adjustment Date To:</div>
        <div class="col-3"><input class="form-control" type="date" id="date2"></div>
    </div>
    
    <div class="row m-3">
        <div class="col-10"></div>
        <input class="btn btn-primary col-2 mt-3" type="button" value="Print" onclick="print()">
    </div>

</div>



<div id="town" class="modal">
    <div class="modal-content" style="margin-top: 100px; width: 30%; height: 430px;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Town Lookup</h3>
            <span href = "#town" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="townDiv"> </div>
        </div>
    </div>
</div>



<script>
    var storage = new Object();
    var page = 1;

    function showTown(button){
        const xhr = new XMLHttpRequest();
        var newPage = page;

        document.getElementById('town').setAttribute('data-button', button.id);
        console.log('show - '+ document.getElementById('town').getAttribute('data-button'));

        xhr.open('GET', 'http://10.12.10.100:8082/api/v1/town/?page=' + newPage);
        xhr.send();
        xhr.onload = function(){
            if(this.status == 200) {
                var response = JSON.parse(this.responseText);
                var town = response.data;
                var lastPage = response.meta.last_page;

                var output = "<table id='townTbl'> <tr id='thead'> <td> &nbsp;  Town Code </td>";
                output += "<td> Town </td> </tr>";
                
                for(var a in town){
                    output += "<tr onclick='selectTown(this)' id='" + town[a].town_code_id + "' name='" + town[a].town_code_name + "' class='tbody'";
                    output += "code='" + town[a].town_code + "'> <td>&nbsp;&nbsp;&nbsp;" + town[a].town_code + "</td>";
                    output += "<td>&nbsp;" + town[a].town_code_name + "</td></tr>";
                }
                output += "</table>";

                output += "<table id='paginate'> <tr>";
                if(page == 1) {
                    output += "<td> <button id='" + newPage + "' class='prev' button='prev' onclick='paginate(this)' disabled> Prev </button> </td>";
                } else {
                    output += "<td> <button id='" + newPage + "' class='prev' button='prev' onclick='paginate(this)' enabled> Prev </button> </td>";
                } 
                output += "<td> <input type='number' value='" + newPage + "' readonly> </td>";
                if(page == lastPage) {
                    output += "<td> <button id='" + newPage + "' class='next' button='next' onclick='paginate(this)' disabled> Next </button> </td> </tr>";
                } else{
                    output += "<td> <button id='" + newPage + "' class='next' button='next' onclick='paginate(this)' enabled> Next </button> </td> </tr>";  
                }
                output += "</table>";

                document.querySelector('.townDiv').innerHTML= output;
            }
        }
        document.querySelector('#town').style.display = "block";
    }

    function paginate(e){
        var pages = e.id;
        var button = e.getAttribute('button');
        var buttonName = document.getElementById('town').getAttribute('data-button');
        var sButton = document.getElementById(buttonName);

        if(button == "next"){
            page += 1;
            document.querySelector(".prev").disabled = false;
            showTown(sButton);
        } else if(button == "prev"){
            page = page - 1;
            showTown(sButton);
        }
    }

    function selectTown(a){
        var townName = a.getAttribute('name');
        var townCode = a.getAttribute('code');
        var town = townCode + " - " + townName;
        var button = document.getElementById('town').getAttribute('data-button');

        document.getElementById(button).value = town;
        document.getElementById(button).setAttribute('data-id', townCode);

        document.querySelector("#town").style.display = "none";
    }

    function print(){
       var url = "{{route('print_adjusted_bill_dtl')}}";
       var dateF = document.getElementById('date1').value;
       var dateT = document.getElementById('date2').value;
       var townF = document.getElementById('town-m').getAttribute('data-id');
       var townT = document.getElementById('town-m2').getAttribute('data-id');
       localStorage.setItem("dateF", dateF);
       localStorage.setItem("dateT", dateT);
       localStorage.setItem("townF", townF);
       localStorage.setItem("townT", townT);
       window.open(url);
   }

</script>

@endsection
