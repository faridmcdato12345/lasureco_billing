@extends('layout.master')
@section('title', 'Summary of Revenue/Town')
@section('content')

<style>
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #areaTable {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #areaTable td{
        height: 40px;
        border-bottom: 1px #ddd solid;
    }
    #areaInp {
        cursor: pointer;
        width: 92%;
    }
    #billTable {
        display: none;
        margin-top: 5%;
    }
    #printBtn {
        margin-top: 15%;
        margin-right: 2.5%;
        display: none;
    }
</style>

<p class="contentheader">Summary of Revenue/Town</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td style="width: 12%;">
                Area:
            </td>
            <td>
                <input type="text" id="areaInp" onclick="showArea()" placeholder="Select Area" readonly>
                <input type="text" id="areaId" hidden>
            </td>
        </tr>
    </table>
    <table id="billTable" class="content-table">
        <tr>
            <td style="width: 12.5%;">
                Billing Period: &nbsp;&nbsp;&nbsp;
            </td>
            <td>
                <input type="month" id="month" style="cursor: pointer;">
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <button id="printBtn" onclick="printRevenue()">Print</button>
            </td>
        </tr>
    </table>
</div>

<div id="area" class="modal">
    <div class="modal-content" style="margin-top: 30px; width: 40%; height: 350px;">
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
    window.onload=function(){
        var b = document.querySelector('#drpbtn2');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container2').childNodes;
        c[5].style.color="blue";
    }

    function showArea(){
        document.querySelector('#area').style.display = "block";
        const xhr = new XMLHttpRequest();
        var areas = "{{route('index.area')}}";
        xhr.open('GET', areas, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                var response = JSON.parse(this.responseText);
                var area = response.data;
                var output = "<table id='areaTable'> <tr id='thead'> <td> &nbsp;  Area Id </td>";
                output += "<td> Area Description </td> </tr>";
                
                for(var a in area){
                    output += "<tr onclick='selectArea(this)' id='" + area[a].area_id + "' class='tbody'";
                    output +=  "name='" + area[a].area_name +"'> <td>&nbsp;&nbsp;" + area[a].area_id + "</td>";
                    output += "<td>" + area[a].area_name + "</td></tr>";
                }
                output += "</table>";

                document.querySelector('.areaDiv').innerHTML= output;
            }
        }
    }

    function selectArea(x){
        var id = x.id;
        var name = x.getAttribute('name');
        
        document.querySelector('#areaId').value = id;
        document.querySelector('#areaInp').value = name;
        document.querySelector('#area').style.display = "none";

        document.querySelector('#billTable').style.display = "block";
    }

    var month = document.querySelector("#month");

    month.addEventListener("change", function(){
        var billPeriod = month.value;

        if(billPeriod !== ""){
            document.querySelector("#printBtn").style.display = "block";
        } else {
            document.querySelector("#printBtn").style.display = "none";
        }
    })

    function printRevenue() {
        var areaId = document.querySelector("#areaId").value;
        var month = document.querySelector("#month").value;

        const toSend = {
            "areaId": areaId,
            "date": month
        }
        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_summary_of_revenue_town")}}'
        window.open($url);
    }
</script>
@endsection
