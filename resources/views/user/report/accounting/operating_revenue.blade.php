@extends('layout.master')
@section('title', 'Operating Revenue')
@section('content')

<style>
    #printBtn {
        float: right; 
        margin-right: 2.8%; 
        height: 40px;
        margin-top: 5%;
    }
    input {
        cursor: pointer;
    }
    .printBtn {
        background-color: white;
        color: royalblue;
        float: right;
        height: 40px !important;
        margin-top: 5%;
        margin-right: 2.6%;
    }
</style>

<p class="contentheader">Operating Revenue</p>
<div class="main">
    <br>
    <table class="content-table">
        <tr> 
            <td width="15%"> 
                Area Code: 
            </td>
            <td> 
                <input type="text" id="areaInp" onclick="showArea()" placeholder="Select Area" readonly>
                <input type="text" id="areaId" hidden>
            </td>
        </tr>
        <tr><td height="50px"> &nbsp; </td></tr>
        <tr>
            <td> 
                Consumer Type:
            </td>
            <td> 
                <select id="consType" style="color: black; cursor: pointer;"> 
                    <option value="all"> All </option>
                    <option value="1"> Irrigation </option>
                    <option value="2"> Comm Water System </option>
                    <option value="3"> BAPA/MUPA </option>
                    <option value="4"> Industrial </option>
                    <option value="5"> Public Building </option>
                    <option value="6"> Streetlights </option>
                    <option value="7"> Commercial </option>
                    <option value="8"> Residental </option>
                </select>
            </td>
        </tr>
        <tr><td height="50px"> &nbsp; </td></tr>
        <tr> 
            <td> 
                Month:
            </td>
            <td> 
                <input type="month" id="month" disabled>
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <button class="printBtn" onclick="print()" disabled> Print  </button>
            </td>
        </tr>
    </table>
</div>

@include('include.modal.areamodal')

<script>
    function setArea(a){
        var areaId = a.getAttribute('areaId');
        var areaName = a.getAttribute('areaName');

        document.querySelector("#areaInp").value = areaName;
        document.querySelector("#areaId").value = areaId;

        document.querySelector("#areaCodes").style.display = "none";
        document.querySelector("#month").disabled = false;
    }

    var month = document.querySelector("#month");
    month.addEventListener("change", function(){
        if(month.value !== ""){
            document.querySelector(".printBtn").disabled = false;
        } else {
            document.querySelector(".printBtn").disabled = true;
        }
    })

    function print() {
        var areaId = document.querySelector("#areaId").value;
        var consType = document.querySelector("#consType").value;
        var billPeriod = document.querySelector("#month").value;

        const toSend = {
            "bp": billPeriod,
            "area_id": areaId,
            "cons_type": consType
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_operating_revenue")}}'
        window.open($url);
    }
</script>
@endsection
