@extends('layout.master')
@section('title', 'Delinquent')
@section('content')

<p class="contentheader">Delinquent</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td>
                <select class="form-select" style="width:auto;color:black" name=""  onchange= "selected(this)" id="select">
                    <option style="color:black" value="All" selected>All</option>
                    <option style="color:black" value="by_area">by_area</option>
                    <option style="color:black" value="by_town">by_town</option>
                    <option style="color:black" value="by_route">by_route</option>
                </select>
            </td>
            <td>
                {{-- <select class="form-select" style="width:auto;color:black" name=""  onchange= "selectedCons(this)" id="select">
                    <option style="color:black" value="All" selected>All</option>
                    <option style="color:black" value="by_area">by_area</option>
                    <option style="color:black" value="by_town">by_town</option>
                    <option style="color:black" value="by_route">by_route</option>
                </select> --}}
                <select class="form-select" style="width:auto;color:black" name=""  onchange= "selectedCons(this)" id="selectCons">
                    {{-- <option value="all" selected> All Consumer Types </option> --}}
                    <option disabled selected>select consumer type</option>
                    <option value="1"> IRRIGATION </option>
                    <option value="2"> COMMERCIAL WATER SYSTEM </option>
                    <option value="3"> BAPA </option>
                    <option value="4"> INDUSTRIAL </option>
                    <option value="5"> PUBLIC BUILDING </option>
                    <option value="6"> STREETLIGHTS </option>
                    <option value="7"> COMMERCIAL  </option>
                    <option value="8"> RESIDENTIAL  </option>
                </select>
            </td>
        </tr>
        <tr id="All">
            <td class="thead">Billing Period:</td>
            <td><input id = "monthT" type="month"></td>
            <td class="thead">Top:</td>
            <td><input id= "top" type="text"></td>
        </tr>
        <tr id="Area">

        </tr>
        <tr id="Town">

        </tr>
        <tr id="Route">

        </tr>
        <tr id="Bperiod"></tr>
        <tr>
            <td colspan="4">
                <button onclick="sendDelinquent()" style="float:right;width:70px;margin-top:30px;height:40px;" class="btn btn-primary" >Print</button>
            </td>
        </tr>
    </table>
</div>
@include('include.modal.townmodal')
@include('include.modal.routemodal')
<div id="officeCode" class="modal">
    <div class="modal-content" style="width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Office Codes</h3>
            <button type="button" onclick="closeOfficeCode();">X</button>
        </div>
        <div class="modal-body">
            <div class="modaldivOfficeCode">

            </div>
        </div>
    </div>
</div>
<script>
var tosend = new Object();
var xhr = new XMLHttpRequest();
var route = "{{route('index.area1')}}";
xhr.open('GET',route,true);
xhr.onload = function(){
    if(this.status == 200){
        var data = JSON.parse(this.responseText);
        var output = " ";
        var val = data.data;
        output += '<div style="overflow:scroll;height:270px;">';
        output += '<table style="color:black;text-align:left;width:100%;height:20px;" border=1 class="modal-table" id="table1">';
        for(var i in val){
            var areaname = val[i].area_name;
            output += '<tr> <td style="cursor:pointer;" onclick="tdclick(this);" id=' + val[i].area_id +  '>' + areaname + 
            '</td></tr>';
        }
        output += '</table></div>';	
    }
        document.querySelector('.modaldivOfficeCode').innerHTML = output;
}
xhr.send();

function openOfficeCode(){
        modalD = document.querySelector("#officeCode");
        modalD.style.display="block";
    }
function closeOfficeCode(){
    modalD =document.querySelector("#officeCode");
    modalD.style.display="none";
} 
function tdclick(area){
    console.log(area);
    modalD = document.querySelector("#officeCode");
    modalD.style.display="none";
    document.querySelector('#offCode').value = area.innerHTML;
    tosend.location_id = area.id;
}


    function selected(a){
        var output = '';
        var output1 = '';
        if(a.value == 'All'){
            delete tosend.location_id;
            output += '<td class="thead">Billing Period</td><td><input id="monthT" type="month"></td>' +
            '<td>Top:</td><td><input id="top" type="text">';
            document.querySelector('#All').innerHTML = '';
            document.querySelector('#Area').innerHTML = '';
            document.querySelector('#Town').innerHTML = '';
            document.querySelector('#Route').innerHTML = '';
            document.querySelector('#Bperiod').innerHTML = '';
            document.querySelector('#All').innerHTML = output;
            tosend.selected = "all";
        }
        else if(a.value == 'by_area'){
            output += '<td>Area:</td>' +
            '<td>' +
            '<input type="text" style="cursor:pointer" id="offCode" onclick="openOfficeCode()" placeholder="Select Area" readonly>' +
            '</td>' + 
            '<td>Billing Period:</td><td><input id="monthT" type="Month"></td>';
           
            output1 +='<td>Top:</td><td><input id = "top" type="text"></td>';

            document.querySelector('#All').innerHTML = '';
            document.querySelector('#Town').innerHTML = '';
            document.querySelector('#Route').innerHTML = '';
            document.querySelector('#Area').innerHTML = output;
            document.querySelector('#Bperiod').innerHTML = '';
            document.querySelector('#Bperiod').innerHTML = output1;
            tosend.selected = "area";
        }
        else if(a.value == 'by_town'){
            output += '<td>Town:</td>' +
            '<td>' +
            '<input type="text" style="cursor:pointer" id="TownCode" onclick="showTown()" placeholder="Select Town" readonly>' +
            '</td>' + 
            '<td>Billing Period:</td><td><input id="monthT" type="Month"></td>';
           
            output1 +='<td>Top:</td><td><input  id = "top" type="text"></td>';

            document.querySelector('#All').innerHTML = '';
            document.querySelector('#Town').innerHTML = output;
            document.querySelector('#Route').innerHTML = '';
            document.querySelector('#Area').innerHTML = '';
            document.querySelector('#Bperiod').innerHTML = '';
            document.querySelector('#Bperiod').innerHTML = output1;
            tosend.selected = "town";
        }
        else if(a.value == 'by_route'){
            output += '<td>Route:</td>' +
            '<td>' +
            '<input type="text" style="cursor:pointer" id="routeID" onclick="showRoutes()" placeholder="Select Route" readonly>' +
            '</td>' + 
            '<td>Billing Period:</td><td><input id="monthT" type="Month"></td>';
           
            output1 +='<td>Top:</td><td><input id = "top" type="text"></td>';

            document.querySelector('#All').innerHTML = '';
            document.querySelector('#Town').innerHTML = '';
            document.querySelector('#Route').innerHTML = output;
            document.querySelector('#Area').innerHTML = '';
            document.querySelector('#Bperiod').innerHTML = '';
            document.querySelector('#Bperiod').innerHTML = output1;
            tosend.selected = "route";
        }
    }  
function selectTown(towndata){
    document.querySelector('#town').style.display = "none";
    document.querySelector('#TownCode').value = towndata.getAttribute('name');
    console.log(document.querySelector('#monthT').value);
    tosend.location_id = towndata.id;
}  
function setRoute(rID){
    document.querySelector('#routeCodes').style.display = "none";
    console.log(rID);
    document.querySelector('#routeID').value = rID.getAttribute('name');
    tosend.location_id = rID.id;
    }
function selectedCons(a){
    // console.log(a.value);
    tosend.cons_id = a.value
}
function sendDelinquent(){
    Swal.fire({
        title: 'Do you want to continue?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
    }).then((result) => {
        if (result.isConfirmed) {
            if(typeof tosend.selected == 'undefined'){
                tosend.selected='all';
            }
            var bp = document.querySelector('#monthT').value;
            tosend.date_period = bp;
            var top = document.querySelector('#top').value;
            tosend.top = top;
            
            var route = "{{route('report.isd.delinquent')}}";
            var xhr = new XMLHttpRequest();
            xhr.open('POST',route,true);
            xhr.setRequestHeader('Content-type','application/json');
            xhr.setRequestHeader('Accept','application/json');
            xhr.send(JSON.stringify(tosend));
            xhr.onload = function(){
                if(this.status == 200){
                   var data = JSON.parse(this.responseText);
                   localStorage.setItem('data',JSON.stringify(data));
                   localStorage.setItem('info',JSON.stringify(tosend));
                   var loc = "{{route('PD')}}";
                   window.open(loc);
                }
                else{
                    console.log('error');
                }
            }
        }
    })
}
</script>
@endsection
