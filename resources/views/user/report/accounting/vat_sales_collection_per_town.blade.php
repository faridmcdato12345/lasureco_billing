@extends('layout.master')
@section('title', 'Summary of Universal Charges')
@section('content')
<style>
    select {
        color: black;
        cursor: pointer;
    }
    input {
        cursor: pointer;
    }
    #filter {
        width: 25%;
    }
    #printButton {
        float: right;
        color: royalblue;
        background-color: white;
        height: 40px;
        margin-right: 2.7%;
        margin-top: 2%;
    }
</style>
<p class="contentheader" onload="checkInputs()">VAT Sales Collection per Town</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td> 
                <select id="filter">
                    <option value="sales"> Sales </option>
                    <option value="collections"> Collections </option>
                </select> 
            </td>
        </tr>
    </table>
    <br>
    <div class="vat-table">
        <table class="content-table">
            <tr>
                <td width="12%"> Area: </td>
                <td> 
                    <input type="text" id="areaInp" onclick="showArea()" placeholder="Select Area" readonly>
		            <input type="text" id="areaId" hidden>
                </td>
            </tr>
            <tr> <td height="50px"></td> </tr>
            <tr>
                <td> Bill Period: </td>
                <td>
                    <input type="month" id="billPeriod">
                </td>
            </tr>
            <tr><td height="50px"></td></tr>
            <tr>
                <td colspan=2>
                    <button id='printButton' onclick='print()'> Print </button>
                </td>
            </tr>
        </table>
    </div>
</div>

@include('include.modal.areamodal')

<script>
    var filter = document.querySelector("#filter");
    filter.addEventListener("change", function(){
        if(filter.value == "sales"){
            var output = '<table class="content-table">';
            output += '<tr>';
            output += '<td width="12%"> Area: </td>'
            output += '<td> <input type="text" id="areaInp" onclick="showArea()" placeholder="Select Area" readonly>';
	        output += '<input type="text" id="areaId" hidden>';
            output += '</td></tr>';
            output += '<tr> <td height="50px"></td> </tr>';
            output += '<tr> <td> Bill Period: </td>';
            output += '<td> <input type="month" id="billPeriod"> </td>';
            output += '</tr> <tr> <td height="50px"></td> </tr>';
	        output += '<tr> <td colspan=2> <button id="printButton" onclick="print()"> Print </button> </td> </tr>';
	        output += '</table>';
            document.querySelector(".vat-table").innerHTML = output;
        } else {
            var output = '<table class="content-table">';
            output += '<tr>';
            output += '<td width="12%"> Area: </td>'
            output += '<td> <input type="text" id="areaInp" onclick="showArea()" placeholder="Select Area" readonly>';
	        output += '<input type="text" id="areaId" hidden>';
            output += '</td></tr>';
            output += '<tr> <td height="50px"></td> </tr>';
            output += '<tr> <td> Date from: </td>';
            output += '<td> <input type="date" id="dateFrom"> </td> </tr>';
            output += '<tr><td height="50px"></td></tr>';
            output += '<tr> <td> Date to: </td>';
            output += '<td> <input type="date" id="dateTo"> </td></tr> ';
            output += '<tr> <td height="50px"></td> </tr>';
	        output += '<tr> <td colspan=2> <button id="printButton" onclick="print()"> Print </button> </td> </tr>';
	        output += '</table>';
            document.querySelector(".vat-table").innerHTML = output;
        }
    })

	function setArea(a){
		var areaId = a.getAttribute('areaId');
		var areaName = a.getAttribute('areaName');

		document.querySelector('#areaInp').value = areaName;
		document.querySelector('#areaId').value = areaId;

		document.querySelector('#areaCodes').style.display = "none";
	}

	function print(){
		var areaId = document.querySelector('#areaId').value;
        var selected = document.querySelector("#filter").value;
        const toSend = new Object();

        if(selected == "collections") {
            var dateFrom = document.querySelector("#dateFrom").value;
            var dateTo = document.querySelector("#dateTo").value;

            toSend.area_id = areaId;
            toSend.selected = selected;
            toSend.date_from = dateFrom;
            toSend.date_to = dateTo;
        } else {
            var billPeriod = document.querySelector("#billPeriod").value;

            toSend.area_id = areaId;
            toSend.selected = selected;
            toSend.billPeriod = billPeriod;
        }

        $url = '{{route("print_vat_sales_collection_per_town")}}';
        localStorage.setItem('data', JSON.stringify(toSend));

        window.open($url);
	}
</script>
@endsection
