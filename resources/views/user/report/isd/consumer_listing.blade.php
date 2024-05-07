@extends('layout.master')
@section('title', 'Consumer Listing')
@section('content')

<style>
    input {
        cursor: pointer;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #routeCodeTable {
        width: 100%;
        height: auto;
        color: black;
        border: 1px #ddd solid;
    }
    #routeCodeTable td{
        height: 45px;
        border-bottom: 1px #ddd solid;
        padding: 15px;
    }
    #routeCodeTable tr:hover{
        transition: background 1s;
        background: gray;
    }
    .routeDiv {
        /* height: 400px; */
        padding-left: 15px;
        padding-right: 15px;
        margin: 15px;
    }
    #paginate {
        width: 100%;
        margin: auto;
        margin-top: 0.5%;
    }
    #paginate button {
        background-color: royalblue;
        border-radius: 3px;
        height: 35px;
        width: 100%; 
    }
     #paginate input {
        margin: auto;
     }

</style>
<p class="contentheader">Consumer Listing</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td>Status</td>
            <td><select class="form-control" onchange="statusType()" style="width:90%;color:black"  id="statusType">
                <option value="1">All</option>
                <option value="2">Active</option>
                <option value="0">Disconnected</option>
                <option value="3">Inoperative</option>
            </select></td>
        </tr>
        <tr>
            <td  class="thead">
               Town:
            </td>
            <td class="input-td">
            <input style ="cursor:pointer;width:90%"  class="form-control" onclick="showTown();" id="TownCode" name="area" placeholder="Select Town" readonly>
            </td>
           
            <td  class="thead">
               Route Code:
            </td>
            <td class="input-td">
                <input style="color:black;width:90%"  class="form-control" id="town-m" data = "routeFrom" type="text" onclick="showRoutes1(this)" name="route" placeholder="Select Route" readonly>
            </td>  
        </tr>
        <tr>
            <td  class="thead">
               Meter:
            </td>
            <td class="input-td">
                <select class="form-control" style="font-size:20px;color:black;width:90%" onchange=awit(this) id="withMeter">
                    <option selected disabled>Select Option</option>
                    <option value="1">With Meter Details</option>
                    <option value="2">Without Meter Details</option>
                </select>
            </td>
           
            <td  class="thead">
               Consumer Type:
            </td>
            <td class="input-td">
                <select id="consType" class="form-control" style="font-size:20px;color:black;width:90%" onchange="consType(this)">
                    <option selected disabled>Select Option</option>
                    <option value="ALL">ALL</option>
                    <option value="RES">RESIDENTIAL</option>
                    <option value="COM">COMMERCIAL</option>
                    <option value="STL">STREETLIGHTS</option>
                    <option value="PUB">PUBLIC BUILDING</option>
                    <option value="IND">INDUSTRIAL</option>
                    <option value="BAP/MUP">BAPA / MUPA</option>
                    <option value="CWS">COMM WATER SYSTEM</option>
                    <option value="IRR">IRRIGATION</option>
                </select>
            </td>  
        </tr>
        <tr>
            <td colspan="4">
                <button onclick="sendListing()" style="float:right;width:70px;margin-top:30px;height:40px;" class="btn btn-primary" >Print</button>
            </td>
        </tr>
    </table>
</div>
<div id="officeCode" class="modal"> 
    <div class="modal-content" style="width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Town Lookup</h3>
            <button type="button" onclick="closeOfficeCode();">X</button>
        </div>
        <div class="modal-body">
            <div class="modaldivOfficeCode">

            </div>
        </div>
    </div>
</div>
<div id="routeCodes" class="modal">
    <div class="modal-content" style="margin-top: 10px; width: 70%; height: auto;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Route Lookup</h3>
            <span href = "#routeCodes" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="row" style="width: 95%; margin: auto">
                <input type="text" class="form-control input-sm p-3" id="searchRoute" placeholder="Search Route" style="cursor: auto;">
            </div>
            <div class="routeDiv"> </div>
        </div>
    </div>
</div>

@include('include.modal.townmodal')
<script>
var page = 1;
var routeFROMTO;
var globe;
var toSend = new Object();
var consTypes = "ALL";
var withMeter = 0;

function consType(selectedConsType){
    toSend.consType = selectedConsType.value;
}

function awit(a){
    toSend.withMeter = a.value;
}

/* ROUTE MODAL */
/*SHOW ROUTE MODAL*/
function showRoutes1(a) {
    globe = a;
    routeFROMTO = a.getAttribute('data');
    const xhr = new XMLHttpRequest();
    var newPage = page;
    var route = "{{route('index.route', '?page=')}}" + newPage;
    xhr.open('GET', route, true);
    xhr.send();

    xhr.onload = function(){
        if(this.status == 200) {
            document.querySelector('.routeDiv').style.height = "auto";
            document.querySelector('.routeDiv').style.borderBottom = "none";
            document.querySelector('.routeDiv').style.overflow  = "none";
            document.querySelector('.routeDiv').style.overflowY  = "hidden";
            var response = JSON.parse(this.responseText);
            var route = response.data;
            var lastPage = response.meta.last_page;
            
            var output = "<table id='routeTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
                    output += "<td> Route Description </td> </tr>";
                    
                    var output = "<table id='routeCodeTable'> <tr id='thead'>";
                    output += "<td> Route Code </td> <td> Route </td> </tr>";
                    
                    for(var a in route){
                        // console.log(route[a].town_code);
                        if(route[a].town_code != ""){
                            var areaName = route[a].town_code[0].area_code[0].area_name;
                            var areaId = route[a].town_code[0].area_code[0].area_id;
                            var townName = route[a].town_code[0].town_code_name;
                            var townCodeId = route[a].town_code[0].town_code_id;
                            var townCode = route[a].town_code[0].town_code;
                            
                            output += "<tr onclick='setRoute(this)' id='" + route[a].route_code_id + "' name='" + route[a].route_desc + "'";
                            output +=  "class='tbody' code='" + route[a].route_code +"' areaName='" + areaName + "' areaId='" + areaId + "'"; 
                            output += "townName='" + townName + "' townCodeId='" + townCodeId + "' townCode='" + townCode + "'>";
                            // output += "<td>&nbsp;&nbsp;" + areaName + "</td>";
                            // output += "<td>" + townName + "</td>";
                            output += "<td>" + route[a].route_code + "</td>";
                            output += "<td><div class='row'>" + route[a].route_desc + "</div>"+
                                    "<div class='row' style='font-size:10px'>("+ areaName +" / ("+ townCode +")-"+townName+")</div></td></tr>";
                        }
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

            document.querySelector('.routeDiv').innerHTML= output;
        }
    }
    document.querySelector('#routeCodes').style.display = "block";
    document.querySelector('#searchRoute').focus();
}
/*SEARCH ROUTE MODAL*/
var searchRoute = document.querySelector("#searchRoute");
searchRoute.addEventListener("change", function(){
    var xhr = new XMLHttpRequest();
    if(searchRoute.value !== ""){
        var route = "{{route('search.route',['req'=>':par'])}}"
        xhr.open('GET', route.replace(':par', searchRoute.value), true);
        xhr.send();
        xhr.onload = function(){
            if(this.status == 200){
                var  response = JSON.parse(this.responseText);
                var route = response.data;
                if(route != ""){
                    var output = "<table id='routeTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
                    output += "<td> Route Description </td> </tr>";
                    
                    var output = "<table id='routeCodeTable'> <tr id='thead'>";
                    output += "<td> Route Code </td> <td> Route </td> </tr>";
                    
                    for(var a in route){
                        
                        if(route[a].town_code != ""){
                            var areaName = route[a].town_code[0].area_code[0].area_name;
                            var areaId = route[a].town_code[0].area_code[0].area_id;
                            var townName = route[a].town_code[0].town_code_name;
                            var townCodeId = route[a].town_code[0].town_code_id;
                            var townCode = route[a].town_code[0].town_code;
                            
                            output += "<tr onclick='setRoute(this)' id='" + route[a].route_code_id + "' name='" + route[a].route_desc + "'";
                            output +=  "class='tbody' code='" + route[a].route_code +"' areaName='" + areaName + "' areaId='" + areaId + "'"; 
                            output += "townName='" + townName + "' townCodeId='" + townCodeId + "' townCode='" + townCode + "'>";
                            output += "<td>" + route[a].route_code + "</td>";
                            output += "<td><div class='row'>" + route[a].route_desc + "</div>"+
                                    "<div class='row' style='font-size:10px'>("+ areaName +" / ("+ townCode +")-"+townName+")</div></td></tr>";
                        }
                    }
                    output += "</table>";
                    document.querySelector('.routeDiv').innerHTML= output;
                    document.querySelector('.routeDiv').style.height = "315px";
                    document.querySelector('.routeDiv').style.borderBottom = "1px solid #ddd";
                    document.querySelector('.routeDiv').style.overflowY  = "scroll";
                } else {
                    var output = "<table style='color: black; margin: auto;'> <br> <br>"; 
                    output += "<tr> <td style='font-size: 25px; color: gray;'> No Route found! </td> </tr> </table>"; 
                    document.querySelector('.routeDiv').innerHTML= output;
                }
            }
        }
    } else {
        var a = globe;
        showRoutes1(a);
    }
})
/*PAGINATE ROUTE MODAL*/
function paginate(e){
    var pages = e.id;
    var button = e.getAttribute('button');

    if(button == "next"){
        page += 1;
        document.querySelector(".prev").disabled = false;
        var a = globe;
        showRoutes1(a);
    } else if(button == "prev"){
        var a = globe;
        page = page - 1;
        showRoutes1(a);
    }
}
/* END OF ROUTE MODAL*/

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
    modalD = document.querySelectorAll(".modal");
    modalD[0].style.display="block";
}

function closeOfficeCode(){
    modalD = document.querySelectorAll(".modal");
    modalD[0].style.display="none";
} 

function tdclick(area){
        modalD = document.querySelectorAll(".modal");
        modalD[0].style.display="none";
        document.querySelector('#offCode').value = area.innerHTML;
    }
function selectTown(towndata){
    document.querySelector('#town').style.display = "none";
    document.querySelector('#TownCode').value = towndata.getAttribute('name');
    toSend.tc_id = towndata.id;
}

/* SET ROUTE */
function setRoute(rID){
	    document.querySelector('#routeCodes').style.display = "none";
        console.log(rID);
        document.getElementById('routeCodes').setAttribute('data-button',rID.getAttribute('code'));
        
        console.log('show - '+ document.getElementById('town-m').getAttribute('data'));
        var button = document.getElementById('routeCodes').getAttribute('data-button');
        var button1 = document.getElementById('routeCodes').getAttribute('data-button');
        
        // document.getElementById(button).value = rID.getAttribute('name');
        // document.getElementById(button).setAttribute('data-id', rID.id);
        
        // console.log(document.getElementById(from.id))
        var routeFrom = document.querySelector('#town-m');
        routeFrom.value = rID.getAttribute('name');
        toSend.rc_from = button;
        if(typeof toSend.statustype == 'undefined'){
            toSend.statustype = 1;
            console.log(toSend.statustype)
        }
    }
/* END OF SET ROUTE */
function statusType(){
    var stype = document.querySelector('#statusType').value;
    toSend.statustype= stype;
    console.log(toSend);
}
/* SEND DATA TO SERVER */
function sendListing(){
    // console.log(JSON.stringify(toSend));
    Swal.fire({
        title: 'Do you want to continue?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
    }).then((result) => {
        if (result.isConfirmed) {
            var routee = "{{route('consumer.list')}}";
            console.log(routee);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', routee, true);
            xhr.setRequestHeader("Accept", "application/json");
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.send(JSON.stringify(toSend));
            xhr.onload = function(){
                if(this.status == 200){
                   var data = JSON.parse(this.responseText);
                   localStorage.setItem('data', JSON.stringify(data));
                    var toprint = "{{route('PCL')}}";
                    window.open(toprint);
                }
            }
        }
    })
}
/* END OF SEND DATA TO SERVER */
</script>
@endsection


