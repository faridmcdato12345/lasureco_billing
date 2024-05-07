@extends('layout.master')
@section('title', 'Advance Power Bill Payment Applied to Bill')
@section('content')

<style>
    #printBtn{
        width: 70px; 
        margin-top: 7.5%;
        margin-right: 2.9%;
        height: 40px;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    
    #areaTbl {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #areaTbl td{
        height: 50px;
        border-bottom: 1px #ddd solid;
    }
    #areaInp {
        cursor: pointer;
    }
</style>

<p class="contentheader">Advance Power Bill Payment Applied to Bill</p>
<div class="main">
    <br>
    <table class="content-table">
        <tr>
            <td style="width:12%;">
                Area Code:
            </td>
            <td>
                <input type="text" id="areaInp" onclick="showArea()" placeholder="Select Area" readonly>
                <input type="text" id="areaId" hidden>
            </td>
        </tr>
    </table>
    <br><br><br>
    <table class="content-table">
        <tr>
            <td style="width:12%;">
                Billing Period:
            </td>
            <td>
                <input type="month" id="month" disabled>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <button id="printBtn" onclick="print()" disabled>Print</button>
            </td>
        </tr>
    </table>
</div>

<div id="area" class="modal">
    <div class="modal-content" style="margin-top: 100px; width: 30%; height: 410px;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Area Lookup</h3>
            <span href = "#area" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="areaDiv"> </div>
        </div>
    </div>
</div>

<script>
    function showArea(){
        const xhr = new XMLHttpRequest();
        var areas = "{{route('index.area')}}";
        xhr.open('GET', areas, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                var response = JSON.parse(this.responseText);
                var area = response.data;
                var output = "<table id='areaTbl'> <tr id='thead'> <td> &nbsp;  Area ID </td>";
                output += "<td> Area </td> </tr>";
                
                for(var a in area){
                    output += "<tr onclick='selectArea(this)' id='" + area[a].area_id + "' name='" + area[a].area_name + "' class='tbody'>";
                    output += "<td>&nbsp;&nbsp;&nbsp;" + area[a].area_id + "</td>";
                    output += "<td>&nbsp;" + area[a].area_name + "</td></tr>";
                }
                output += "</table>";

                document.querySelector('.areaDiv').innerHTML= output;
            }
        }
        document.querySelector('#area').style.display = "block";
    }

    function selectArea(a){
        var areaName = a.getAttribute('name');
      
        document.querySelector("#areaId").value = a.id;
        document.querySelector("#areaInp").value = areaName;
        document.querySelector("#area").style.display = "none";
        document.querySelector("#month").disabled = false;
    }

    var billMonth = document.querySelector("#month");
    billMonth.addEventListener("change", function(){
        if(billMonth.value !== ""){
            document.querySelector("#printBtn").disabled = false;
        } else {
            document.querySelector("#printBtn").disabled = true;
        }
    })

    function print(){
        var areaId = document.querySelector("#areaId").value;
        var billMonth = document.querySelector("#month").value;

        const toSend = {
            'area_id': areaId,
            'date': billMonth
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_advance_power_bill_applied_to_bill")}}'
        window.open($url);
    }
</script>
@endsection
