@extends('layout.master')
@section('title', 'Routes Code')
@section('content')

<style>
	.contentA {
		display: flex;
		flex: 1;
		width: 97%;
		margin: auto;
		height: 490px;
		background: white;
		color: #000;
		border: 2px solid #ddd;
	}
	.contentB {
		display: flex;
		flex:1;
		background: white;
		color: #000;
	}
	.contentC {
		display: flex;
		flex: 1;
		background: white;
		color: #000;
	}
	.contentD {
		display: flex;
		flex: 1;
		background: white;
		color: #000;
		width: 500px;
	}
	.add{
		color:red;
	}
	.view{
		display: block;
	}
	.closemo{
		display: none;
	}
	.remove{
		color:black;
	}
	#routedesc {
		display: none;
		width: 334px;
		color: white;
		background-color: #5B9BD5;
	}
	.routediv {
		width: 100%;
		border-right: 1px solid #ddd;
	}
	#areadiv {
		display: none;
	}
	#towndiv {
		display: none;
	}
	.routetables {
		width: 100%;
		margin-top: -0.2%;
		font-size: 14.5px;
	}
	.routetables td {
		height: 70px;
		border-bottom: 1px solid #ddd;
		cursor: pointer;
		background-color: white;
	}
	#tbldiv {
		height: 420px; 
		display: none;
	}
	.add {
		font-size: 14.7px;
		color: rgb(23, 108, 191);
	}
	.filter {
		/* background-color: #5B9BD5; */
	}
	.addCodes {
		margin: auto;
		border: none;
		border-radius: 3px;
		background-color:rgb(23, 108, 191);
		color: white;
		margin-right: 10px;
	}
	.editButt {
		border: none;
		border-radius: 2px;
		background-color: #5B9BD5;
		color: white;
		margin-right: 10px;
		width: 90%;
	}
	.editTownButt {
		border: none;
		border-radius: 2px;
		background-color: #5B9BD5;
		color: white;
	}
	.editRouteButt {
		border: none;
		border-radius: 3px;
		background-color: #5B9BD5;
		color: white;
		margin-right: 4px;
	}
	#errorMessage {
		color: #ff0000;
		display: none;
	}
	.addErrorMessage {
		display: none;
		color: #ff0000;
		float: left;
	}
	.addErrorMessageTown {
		display: none;
		color: #ff0000;
		float: left;
	}
	.addErrorMessageRoute {
		display: none;
		color: #ff0000;
		float: left;
	}
	.saveBtn {
		border: none;
		border-radius: 3px;
		background-color:rgb(23, 108, 191);
		color: white;
		float: right;
		height: 40px;
		width: 20%;
	}
	#editModal {
		width: 30%;
		height: 220px;
		margin-top: 5%;
	}
	.addTownStyle {
		margin-top: 15px; 
		margin-right: 7px; 
		border: none; 
		border-radius: 3px;
		height: 37px;
		width: 17%;
		background-color: rgb(23, 108, 191);
		color: white;
	}
	.addRouteStyle {
		margin-top: 15px; 
		margin-right: 7px; 
		border: none; 
		border-radius: 3px;
		height: 37px;
		width: 17%;
		background-color: rgb(23, 108, 191);
		color: white;
	}
	.editBtn {
		border: none; 
		border-radius: 3px; 
		background-color: #5B9BD5; 
		color: white;
	}
	@media screen and (min-width:1681px) and (max-width: 1920px) { 
		.main {
			height: 700px;
		}
		.contentA {
			height: 100%;
		}
	}
</style>
<p class="contentheader">Routes Codes </p>

<div class="main">
	<div class="contentA">
		<div class="contentB">
			<div class="routediv" id="areadiv">
				<div style="height: 68px; background-color: #5B9BD5; color: white;">
					&nbsp;&nbsp; <b> Area Description </b>
					<button class="modal-button" id="routeCodesAddBtn" href="#areamodal" style="margin-left: 115px; background-color: white; color:rgb(23, 108, 191); margin-top: 10px;"> Add </button>
				</div>
				<table class="routetables" id="areatable" style="height: 86.5% !important;"> </table>
			</div>
		</div>
        <div class="contentC">
			<div class="routediv" id="towndiv">
				<div style="height: 68px; background-color: #5B9BD5; color: white;">
					<table style="width: 100%;"> 
						<tr>
							<td>
								<span id="townsearch"></span>
							</td>
							<td rowspan="2" style="width: 20%;">
								<button class="modal-button" id="routeCodesAddBtn" href="#townmodal" style="width: 100%; background-color: white; color:rgb(23, 108, 191); margin-left: -15px; margin-top: -2px;"> Add </button>
							</td>
						</tr>
						<tr>
							<td>
								&nbsp; <b> Town Description </b>
							</td>
						</tr>
					</table>
				</div>
				<div id="towntablediv" style="overflow-y: none;">
					<table class="routetables" id="towntable"> </table>
				</div>
			</div>
		</div>
		<div class="contentD">
			<div class="filter">
				<div id="routedesc" style="width: 100%;">
					<table style="width: 100%; height: 67px;">
						<tr>
							<td>
								<b> Route Description </b>
							</td>
							<td>
								<span id="routesearchinp"></span>
							</td>
							<td>
								<button class="modal-button" id="routeCodesAddBtn" href="#routemodal" style="width: 65px; 
								 																			 height: 40px; 
																											 background-color:white; 
																											 color: rgb(23, 108, 191);
																											 margin-bottom: -1000px;"> Add </button>
							</td>
						</tr>
					</table>
				</div>
				<div id="tbldiv">
					<table id="tbl" style="width: 100%;"> </table>
				</div>
			</div>
		</div>

    </div>
</div>

<div id="areamodal" class="modal">
	<div class="modal-content" style="width: 30%; height: 220px; margin-top: 5%;">
		<div class="modal-header" style="width: 100%;">
			<h3>Add Area</h3>
			<span href="#areamodal" class="closes">&times;</span>
		</div>
		<div class="modal-body">
			<table style="width: 100%; color: black; margin-top: 5px;">
				<tr>
					<td style="width: 23%;">
						New Area:
					</td>
					<td>
						<input type="text" name="area" placeholder="New Area" class="areaInput">
					</td>
				</tr>
				<tr>
					<td colspan=2 style="text-align: right;">
						<span class="addErrorMessage"> </span>
						<button onclick= "addArea()"
								style="margin-top: 15px;
									   margin-right: 7px;
									   border: none;
									   border-radius: 3px;
									   height: 37px;
									   width: 17%;
									   background-color: rgb(23, 108, 191);
									   color: white;">
							Add
						</button>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

<div id="townmodal" class="modal">
	<div class="modal-content" style="width: 30%; height: 280px; margin-top: 5%;">
		<div class="modal-header" style="width: 100%;">
			<h3>Add Town</h3>
			<span href="#townmodal" class="closes">&times;</span>
		</div>
		<div class="modal-body">
			<table style="width: 100%; color: black; margin-top: 5px;">
				<tr>
					<td style="width: 24%;">
						Town Name:
					</td>
					<td>
						<input type="text" name="area" class="towninput" placeholder="Town Name">
					</td>
				</tr>
				<tr><td> &nbsp; </td></tr>
				<tr>
					<td>
						Town Code:
					</td>
					<td>
						<input type="number" id="townCode" placeholder="Town Code">
					</td>
				</tr>
				<tr>
					<td colspan=2 style="text-align: right;">
						<span class="addErrorMessageTown"> </span>
						<span class="addTownButton"> </span>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

<div id="routemodal" class="modal">
	<div class="modal-content" style="width: 30%; height: 255px; margin-top: 5%;">
		<div class="modal-header" style="width: 100%;">
			<h3>Add Route</h3>
			<span href="#routemodal" class="closes">&times;</span>
		</div>
		<div class="modal-body">
			<table style="width: 100%; color: black; margin-top: 5px;">
				<tr>
					<td style="width: 30%;">
						Route Code:
					</td>
					<td>
						<input type="number" class="routeCode" placeholder="Route Code">
					</td>
				</tr>
				<tr>
					<td>
						Route Desc:
					</td>
					<td>
						<input type="text" class="routeDesc" placeholder="Route Description">
					</td>
				</tr>
				<tr>
					<td colspan=2 style="text-align: right;">
						<span class="addErrorMessageRoute"> </span>
						<span class="addRouteButton"> </span>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

<div id="editCode" class="modal">
	<div class="modal-content" id="editModal">
	<div class="modal-header" style="width: 100%;">
		<h3>Edit <span id="SLP"> </span></h3>
		<span href="#editCode" class="closes">&times;</span>
	</div>
	<div class="modal-body" style="color: black;">
		<table id="codename" style="width: 100%;"> </table>
		<table style="width: 100%; float: right; margin-right: 10px; margin-top: 15px;">
			<tr>
				<td>
					<span class="editButton"> </span>
				</td>
			</tr>
			<tr>
				<td>
					<p id="errorMessage"> </p>
				</td>
			</tr>
		</table>
	</div>
	</div>
</div>

<script>
    //Area Table
	var xhr = new XMLHttpRequest();
	var route = "{{route('index.area')}}";
	xhr.open('GET', route, true);

	xhr.onload = function(){
		if(this.status == 200){
			var hide = document.querySelector('.content4');
			var data = JSON.parse(this.responseText);
			var output = " ";
			var val = data.data;
			var val3 = data.meta['last_page'];
			var val2 = data.meta['current_page'];

			document.querySelector('#areadiv').style.display = "block";

			for(var i in val){
				var areaname = val[i].area_name;
				output += '<tr> <td onclick="tdclick(this.id);" id=' + val[i].area_id +  '> &nbsp;&nbsp;&nbsp;' + areaname + '</td>';
				output +='<td onclick="editCode(this)" id="' + areaname + '" height="area" width="' + val[i].area_id +'"> <button class="editButt"> Edit </button></td>';
				output +='<td onclick="deleteCode(this)" id="' + val[i].area_id + '" height="area"> <button class="editButt" style="background-color: rgb(238, 64, 53); color: white;"> Delete </button> </td> </tr>';
			}
			output += '<tr> <td> </td><td> </td><td> </td> </tr>';

			var b = val2 +1;

			if(val2 <= 1){
				output += '<tr> <td> <button class="routeCodesBtns" onclick="s(this.value)" value=' + val2 +' hidden> Previous </button>';
			}
			else{
				output += '<td> &nbsp;&nbsp;&nbsp; <button class="routeCodesBtns" onclick="s(this.value)" value=' + val2 + '> Previous </button>';
			}

			output += '<input type="text" class="currentpage" value="' + val2 + '" readonly>';

			if(val2 > val3){
				output +=  '<button class="routeCodesBtns" onclick="s(this.value)" value=' + b  +'  > Next </button>';
			}
			else{
				output += '<button class="routeCodesBtns" onclick="s(this.value)" value=' + b  +' hidden> Next </button>';
			}
			output += '</td></tr>';

			document.querySelector('#areatable').innerHTML = output;
        }
		else {
			alert('No Server!');
		}
	}
	xhr.send();
	//End Area Table


	//Town Table
	function tdclick(a){
		document.querySelector('#townsearch').innerHTML = '<input type="text" id="townsearchinp" name="' + a + '" onchange="searchTown()" placeholder="Search for Town..." style="margin-left: 10px; width: 200px;">';
		document.querySelector(".addTownButton").innerHTML = "<button class='addTownStyle' id='" + a +"' onclick='addTown(this)'> Add </buton>";
		var x = document.getElementById('areatable');
		var y = x.getElementsByTagName('td');
		for(let i =0; i < y.length; i++){
			if(y[i].id === a){
				y[i].classList.add('add');
			}
			else{
				y[i].classList.remove('add');
			}
		}
		var xhr = new XMLHttpRequest();
		var hide = document.querySelector('.contentD');
		var town = "{{route('show.towns',['id'=>':id'])}}"
    	xhr.open('GET', town.replace(':id', a), true);
		xhr.send();

		xhr.onload = function(){
			var hide = document.querySelector('.contentD');
          	hide.style.visibility="hidden";
				
			if(this.status == 200){
				var data = JSON.parse(this.responseText);
				var output = " ";
				var val = data.data;
				var bal = a;
				var val3 = data.meta['last_page'];
				var val2 = data.meta['current_page'];

				document.querySelector('#towndiv').style.display =  "block";
				
				if(val.length !== 0) {
					for(var i in val){
						output += '<tr> <td onclick="tdclick1(this.id);" id=' + val[i].town_code_id +  '> &nbsp;&nbsp;&nbsp;' + val[i].town_code_name + '</td>';
						output +='<td onclick="editCode(this)" id="' + val[i].town_code_name + '" data="' + a + '" height="town" width="' + val[i].town_code_id + '" code="' + val[i].town_code + '" style="width: 50px;"> <button class="editTownButt" style="float: right;"> Edit </button> &nbsp; </td>'; 
						output +='<td onclick="deleteCode(this)" id="' + val[i].town_code_id + '" height="town" width="' + a + '" style="width: 50px;"> <button class="editTownButt" style="margin: 10px; background-color: rgb(238, 64, 53); color: white;"> Delete </button> </td> </tr>';
					}
						
					var b = val2 +1;
							
					if(val2 <= 1){
						output += '<tr> <td> <button class = "routeCodesBtns" onclick="s(this.value)" value=' + val2 +' hidden> Previous </button> &nbsp;&nbsp;&nbsp;';
						output += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';	
					}
					else{
						output += '<td> <button class = "routeCodesBtns" onclick="s(this.value)" value=' + val2 + '> Previous </button>';
					}
							
					output += '<input type="text" class="currentpage" value="' + val2 + '" readonly>';
						
					if(val2 > val3){
						output +=  '<button class="routeCodesBtns" onclick="s(this.value)" value=' + b  +'  hidden> Next </button>';
					}
					else{
						output += '<button id="' + a + '" class="routeCodesBtns" onclick="s(this.value)" value=' + b  +'> Next </button>';	
					}
					output += '</td> </tr> <button id="area"  value=' + a  +' hidden> </button>';	
					var towntable = document.querySelector('#towntable');
					towntable.innerHTML = output;
					towntable.style.textAlign = "left";
					document.querySelector('#tbl').innerHTML = "";
				} else {
					var towntable = document.querySelector('#towntable');
					towntable.innerHTML = "<tr> <td> No Town! </td> </tr>";
					towntable.style.textAlign = "center";
					towntable.style.height = "86.5%";
				}
			}
		}
	}
	//End Town Table
	
	//Paginate Town Table
	function s(p){
		var bal = document.querySelector('#area');
		var bal2 = bal.value;
		var u = document.getElementById('users');
		var xhr = new XMLHttpRequest();
		var town = "{{route('show.towns', ['id'=>':id'])}}" + '?page=' + p;
    	xhr.open('GET', town.replace(':id', bal2), true);
		xhr.send();

		xhr.onload = function(){
			if(this.status == 200){
				var data = JSON.parse(this.responseText);
				var output = " ";
				var val = data.data;
				var val3 = data.meta['last_page'];
				var val2 = data.meta['current_page'];

				for(var i in val){
					output += '<tr> <td onclick="tdclick1(this.id);" id=' + val[i].town_code_id +  '> &nbsp;&nbsp;&nbsp;' + val[i].town_code_name + '</td>';
					output +='<td onclick="editCode(this)" id="' + val[i].town_code_name + '" data="' + bal2 + '" height="town" width="' + val[i].town_code_id + '" code="' + val[i].town_code + '" style="width: 50px;"> <button class="editTownButt" style="float: right;"> Edit </button> &nbsp; </td>'; 
					output +='<td onclick="deleteCode(this)" id="' + val[i].town_code_id + '" width="' + bal2 + '" height="town" style="width: 50px;"> <button class="editTownButt" style="margin: 10px; background-color: rgb(238, 64, 53); color: white;"> Delete </button> </td> </tr>';
				}

				var b = val2 +1;
				var c = val2 - 1;

				if(val2 <= 1){
					output += '<tr> <td> <button class = "routeCodesBtns" onclick="s(this.value)" value=' + c +' hidden> Previous </button>';
				}
				else{
					output += '<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <button class = "routeCodesBtns" onclick="s(this.value)" value=' + c + '> Previous </button>';
				}

				output += '<input type="text" class="currentpage" value="' + val2 + '" readonly>';

				if(val2 >= val3){
					output +=  '<button class="routeCodesBtns" onclick="s(this.value)" value=' + b  +'  hidden> Next </button>';
				}
				else{
					output += '<button class="routeCodesBtns" onclick="s(this.value)" value=' + b  +' > Next </button>';
				}
				output += '</td> </tr> <button id = "area"  value=' + bal2  +' hidden> </button>';
				document.querySelector('#towntable').innerHTML = output;
				document.querySelector('#tbl').innerHTML = "";
			}
		}
		
	}
	//End Paginate Town Table


	//Start Route Table
	function tdclick1(td){
		document.querySelector("#routesearchinp").innerHTML = '<input type="text" id="myInput" name="' + td + '" onchange="myFunction()" placeholder="Search for Route..">';
		document.querySelector(".addRouteButton").innerHTML = "<button class='addRouteStyle' id='" + td + "' onclick='addRoute(this)'> Add </button>";
		var x = document.getElementById('towntable');
		var y = x.getElementsByTagName('td');
		for(let i =0; i < y.length; i++){
			if(y[i].id === td){
				y[i].classList.add('add');
			}
			else{
				y[i].classList.remove('add');
			}
		}

		var xhr = new XMLHttpRequest();
		var hide = document.querySelector('.contentD');
        hide.style.visibility="visible";
		var route = "{{route('show.routes',['id'=>':id'])}}"
    	xhr.open('GET', route.replace(':id', td), true);
		xhr.send();

		xhr.onload = function(){
			if(this.status == 200){
				var data = JSON.parse(this.responseText);
				var val = data.data;
				var output = " ";
				var val3 = data.meta['last_page'];
				var val2 = data.meta['current_page']; 
				
				document.getElementById('myInput').style.display = "block";
				document.getElementById('routedesc').style.display = "block";
				document.getElementById('tbldiv').style.display = "block"; 

				if(val.length !==0) {
					var towncode = val[0].town_code[0].town_code_id;
				
					for(var i in val){
						output += '<tr> <td> &nbsp;&nbsp;&nbsp;' + val[i].route_desc + '</td>';
						output += '<td onclick="editCode(this)" id="' + val[i].route_code_id + '" height="' + val[i].route_desc + '" width="' + towncode + '" routeCode="' + val[i].route_code + '"> <button class="editBtn"> Edit </button> </td>';
						output += '<td onclick="deleteCode(this)" id="' + val[i].route_code_id + '" height="' + towncode + '"> <button class="editTownButt" style="margin: 10px; background-color: rgb(238, 64, 53); color: white;"> Delete </button> </td> </tr>';
					}
						
					var b = val2 +1;
					var c = val2 - 1;
							
					if(val2 <= 1){
						output += '<tr> <td> <button class="routeCodesBtns" onclick="s1(this.value)" value=' + c +' hidden> Previous </button>';
						output += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					}
					else{
						output += '<td> <button class="routeCodesBtns" onclick="s1(this.value)" value=' + c + '> Previous </button>';	
					}

					output += '<input type="text" class="currentpage" value="' + val2 + '"readonly>';
							
					if(val2 >= val3){
						output +=  '<button class="routeCodesBtns" onclick="s1(this.value)" value=' + b + 'hidden> Next </button>';
					}
					else{
						output += '<button class="routeCodesBtns" onclick="s1(this.value)" value=' + b + '> Next </button>';	
					}
							
					output += '</td> </tr> <button id = "town"  value=' + td  +' hidden> </button>';
					var routetable = document.querySelector('#tbl');
					routetable.innerHTML = output;
					routetable.style.textAlign = "left";
				} else {
					var routetable = document.querySelector('#tbl');
					routetable.innerHTML = "<tr> <td> No Route! </td> </tr>";
					routetable.style.textAlign = "center";
					routetable.style.height = "100%";
				}
			} 
		}
	}
	//End Route Table


	//Paginate Route Table
	function s1(p){
		var bal = document.querySelector('#town');
		var bal2 = bal.value;
		var xhr = new XMLHttpRequest();
		var route = "{{route('show.routes',['id'=>':id'])}}" + "?page=" + p;
    	xhr.open('GET', route.replace(':id', bal2), true);
		
		xhr.onload = function() {
			if(this.status == 200){
				var data = JSON.parse(this.responseText);
				var output = " ";
				var val = data.data;
				var val3 = data.meta['last_page'];
				var val2 = data.meta['current_page'];

				for(var i in val){
					output += '<tr> <td> &nbsp;&nbsp;&nbsp;' + val[i].route_desc + '</td>';
					output += '<td onclick="editCode(this)" id="' + val[i].route_code_id + '" height="' + val[i].route_desc + '" width="' + bal2 + '" routeCode="' + val[i].route_code + '"> <button class="editBtn"> Edit </button> </td>';
					output += '<td onclick="deleteCode(this)" id="' + val[i].route_code_id + '" height="' + bal2 + '"> <button class="editTownButt" style="margin: 10px; background-color: rgb(238, 64, 53); color: white;"> Delete </button> </td> </tr>';
				}
				
				var b = val2 + 1;
				var c = val2 - 1;
		 			
				if(val2 <= 1){
					output += '<tr> <td> <button id="btn" onclick="s1(this.value)" value=' + c + ' hidden> Previous </button>';
					output += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				} else{
					output += '<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					output += '<button class="routeCodesBtns" id="btn" onclick="s1(this.value)" value=' + c + '> Previous </button>';	
				}

				output += '<input style="width:10%;" type="text" class="currentpage" value="' + val2 + '"readonly>';
					
				if(val2 >= val3){
					output +=  '<button id="btn" onclick="s1(this.value)" value=' + b  +' hidden> Next </button>';
				} else{
					output += '<button class="routeCodesBtns" id="btn" onclick="s1(this.value)" value=' + b  +'> Next </button>';	
				}
				output += '</td> </tr> </table>';
				output += '<button id="town"  value=' + bal2  +' hidden> </button>';
				document.querySelector('#tbl').innerHTML = output;
			}	
		}
		xhr.send();
	}
	//End Paginate Route Table


	//Search Route
	function myFunction() {
		var input, filter;
		input = document.getElementById("myInput");
		filter = input.value.toUpperCase();
		var town = input.name;

		if(filter.length > 0){
			var xhr = new XMLHttpRequest();
			var searchRoute = "{{route('search.route.by.town.id', ['id'=>':id', 'req'=>':req'])}}";
			var newSearchRoute = searchRoute.replace(':id', town);
			var newSearchRoute2 = newSearchRoute.replace(':req', filter);
			xhr.open('GET',newSearchRoute2,true);	
			xhr.send();		
			xhr.onload = function(){
				if(this.status == 200){
					var search = JSON.parse(this.responseText);
					var output = " ";
					
					if(search.length > 0) {
						for(var i in search){
							output += '<tr> <td> &nbsp;&nbsp;&nbsp;' + search[i].rc_desc + '</td>';
							output += '<td onclick="editCode(this)" id="' + search[i].rc_code + '" height="' + search[i].rc_desc + '" width="' + town + '" routeId ="' + search[i].rc_id + '"> <button class="editBtn"> Edit </button> </td>';
							output += '<td onclick="deleteCode(this)" id="' + search[i].rc_code + '" height="' + town + '"> <button class="editTownButt" style="margin: 10px; background-color: rgb(238, 64, 53); color: white;"> Delete </button> </td> </tr>';
						}
					} else {
						output += "<tr> <td> No Search Results! </td> </tr>";
					}
				}
				output += '<button id = "town"  value=' + town  +' hidden>' + '</button>';
				document.querySelector('#tbl').innerHTML = output;			
				document.getElementById('tbldiv').style.overflowY = "scroll";
			}
			
		} else{
			var a = document.querySelector('.currentpage');
			var cpage = a.value;
			var b = document.querySelector('#town').value;
			var xhr = new XMLHttpRequest();
			var route = "{{route('show.routes',['id'=>':id'])}}" + "?page=1"
    		xhr.open('GET', route.replace(':id', town), true);
			//xhr.open('GET','http://10.12.10.100:8082/api/v1/route/town/1?page=1',true);
			
			xhr.onload = function(){
				document.getElementById('tbldiv').style.overflowY = "hidden";
				if(this.status == 200){
					var data = JSON.parse(this.responseText);
					var output = " ";
					var val = data.data;
					var val3 = data.meta['last_page'];
					var val2 = data.meta['current_page']; 
						
					if(data.length > 0){	
						for(var i in val){
							output += '<tr> <td> &nbsp;&nbsp;&nbsp;' + val[i].route_desc + '</td>';
							output += '<td onclick="editCode(this)" id="' + val[i].route_code_id + '" height="' + val[i].route_desc + '"  width="' + bal2 + '"> <button class="editBtn"> Edit </button> </td>';
							output += '<td onclick="deleteCode(this)" id="' + val[i].route_code_id + '" height="' + bal2 + '"> <button class="editTownButt" style="margin: 10px; background-color: rgb(238, 64, 53); color: white;"> Delete </button> </td> </tr>'; 
						}
						
						var b = val2 +1;
						var c = val2 - 1;
							
						if(val2 <= 1){
							output += '<tr> <td> <button class="routeCodesBtns" onclick="s1(this.value)" value=' + c +' hidden> Previous </button>';
							output += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						}
						else{
							output += '<td> <button class="routeCodesBtns" onclick="s1(this.value)" value=' + c + '> Previous </button>';	
						}

						output += '<input type="text" class="currentpage" value="' + val2 + '"readonly>';
								
						if(val2 >= val3){
							output +=  '<button class="routeCodesBtns" onclick="s1(this.value)" value=' + b + 'hidden> Next </button>';
						}
						else{
							output += '<button class="routeCodesBtns" onclick="s(this.value)" value=' + b + '> Next </button>';	
						}

						output += '</td> </tr> <button id = "town"  value=' + bal2  +' hidden> </button>';
						document.querySelector('#tbl').innerHTML = output;
					}
				}	
			}
			xhr.send();
		}
	}
	//End Search Route

	//Search Town
	function searchTown() {
		var town = document.getElementById("townsearchinp");
		var filter = town.value.toUpperCase();
		var area = town.name;
		var innerHTML = " ";
		
		if(filter.length > 0) {
			request = new XMLHttpRequest();
			var searchTown = "{{route('search.town.by.area.id', ['id'=>':id', 'req'=>':req'])}}";
			var newSearchTown = searchTown.replace(':id', area);
			var newSearchTown2 = newSearchTown.replace(':req', filter);
			request.open('GET', newSearchTown2, true);
			request.send();
			request.onload = function() {
				if(this.status == 200) {
					var response = JSON.parse(this.responseText);
					
					if(response.length > 0) {
						for(var x in response) {
							innerHTML += '<tr> <td> &nbsp;&nbsp;&nbsp;' + response[x].tc_name + '</td>';
							innerHTML +='<td onclick="editCode(this)" id="' + response[x].tc_name + '" data="' + area + '" height="town" width="' + response[x].tc_code + '" style="width: 50px;"> <button class="editTownButt" style="float: right;"> Edit </button> &nbsp; </td>';
							innerHTML +='<td onclick="deleteCode(this)" id="' + response[x].tc_code + '" height="town" width="' + area + '" style="width: 50px;"> <button class="editTownButt" style="margin: 10px; background-color: rgb(238, 64, 53); color: white;"> Delete </button> </td> </tr>';
						}
					} else {
						innerHTML = "No Search Result!";
						document.querySelector('#towntablediv').style.overflowY = "hidden";
					}
					document.querySelector('#towntable').innerHTML = innerHTML;
					document.querySelector('#towntablediv').style.overflowY = "scroll";
					document.querySelector('#towntablediv').style.height = "417px";
				}
			}
		} else {
			var xhr = new XMLHttpRequest();
			var town = "{{route('show.towns',['id'=>':id'])}}" + "?page=1";
    		xhr.open('GET', town.replace(':id', area), true);
			xhr.send();
			
			xhr.onload = function(){
				if(this.status == 200){
					document.querySelector('#towntablediv').style.overflowY = "hidden";
					var town = JSON.parse(this.responseText);
					var output = " ";
					var towndata = town.data;
					var val3 = town.meta['last_page'];
					var val2 = town.meta['current_page']; 
				
					for(var i in towndata){
						output += '<tr> <td onclick="tdclick1(this.id);" id=' + towndata[i].town_code_id +  '> &nbsp;&nbsp;&nbsp;' + towndata[i].town_code_name + '</td>';
						output += '<td onclick="editCode(this)" id="' + towndata[i].town_code_name + '" data="' + area + '" height="town" width="' + towndata[i].town_code_id + '" style="width: 50px;"> <button class="editTownButt" style="float: right;"> Edit </button> &nbsp; </td>'; 
						output += '<td onclick="deleteCode(this)" id="' + towndata[i].town_code_id + '" width="' + area + '" height="town" style="width: 50px;"> <button class="editTownButt" style="margin: 10px; background-color: rgb(238, 64, 53); color: white;"> Delete </button> </td> </tr>';
					}

					var b = val2 + 1;
					var c = val2 - 1;
					
					if(val2 <= 1){
						output += '<tr> <td> <button id="btn" onclick="s1(this.value)" value=' + c + ' hidden> Previous </button>';
						output += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					} else{
						output += '<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						output += '<button class="routeCodesBtns" id="btn" onclick="s1(this.value)" value=' + c + '> Previous </button>';	
					}

					output += '<input style="width:20%;" type="text" class="currentpage" value="1" readonly>';
						
					if(val2 >= val3){
						output +=  '<button id="btn" onclick="s1(this.value)" value=' + b  +' hidden> Next </button>';
					} else{
						output += '<button class="routeCodesBtns" onclick="s(this.value)" value=' + b  +'> Next </button>';	
					}
					output += '<button id="area"  value=' + area  +' hidden> </button>';
					var towntable = document.querySelector('#towntable');
					towntable.innerHTML = output;
				}	
			}
		}
	}
	//End Search Town

	function editCode(a) {
		var codediv = " ";
		var code = a.height;
		var area = a.getAttribute("data");
		var townCode = a.getAttribute("code");
		var routeCode = a.getAttribute('routeCode');
	
		if(code == "area") {
			document.getElementById('SLP').innerHTML = "Area";
			document.getElementById('editCode').style.display = "block";
			var codediv = '<table> <tr> <td> Area: </td> <td> <input type="text" class="areaname" value="' + a.id + '"> </td> </tr> </table>';
			document.getElementById('codename').innerHTML = codediv;
			document.querySelector('.editButton').innerHTML = "<button onclick='updateCode(this)' class='saveBtn' id='" + a.width + "' name='area'> Save </buton>";
			document.getElementById('editModal').style.height = "220px";
		} else if (code == "town") {
			document.getElementById('SLP').innerHTML = "Town";
			document.getElementById('editCode').style.display = "block";
			var codediv = '<table> <tr> <td> Town: </td> <td> <input type="text" class="townname" value="' + a.id + '"> </td> </tr> <tr> <td> &nbsp;';
			codediv += '</td> </tr> <tr> <td> Town Code: </td> <td> <input type="number" class="townCode" value="' + townCode + '"> </td> </tr> </table>';
			document.getElementById('codename').innerHTML = codediv;
			document.querySelector('.editButton').innerHTML = "<button onclick='updateCode(this)' class='saveBtn' id='" + a.width + "' name='town' data='" + area + "'> Save </button>";
			document.getElementById('editModal').style.height = "280px";
		} else {
			document.getElementById('SLP').innerHTML = "Route";
			document.getElementById('editCode').style.display = "block";
			var codediv = '<table> <tr> <td> Route Description: </td> <td> <input type="text" class="routename" value="' + a.height + '"> </td> </tr>';
			codediv += '<tr> <td> Route Code: </td> <td> <input type="number" id="routeCode" value="' + a.id + '"> </td> </tr> </table>';
			document.getElementById('codename').innerHTML = codediv;
			var routeId = a.getAttribute('routeId');
			document.querySelector('.editButton').innerHTML = "<button onclick='updateCode(this)' class='saveBtn' id='" + routeId + "' name='" + a.width + "' routeCode='" + routeCode + "'> Save </button>";		
			document.getElementById('editModal').style.height = "250px";
		}
	}

	function updateCode(x) {
		var newID = x.id;
		if(x.name == "area") {
			var newArea = document.querySelector('.areaname').value;
			const toSend = {
				ac_name: newArea
			};
			const toSendJSONed = JSON.stringify(toSend);
			var token = document.querySelector('meta[name="csrf-token"]').content;
			const xhr = new XMLHttpRequest();
			var updateArea = "{{route('update.area', ['id'=>':id'])}}";
			var newUpdateArea = updateArea.replace(':id', x.id);
			xhr.open("PATCH", newUpdateArea, true);
			xhr.setRequestHeader("Accept", "application/json");
			xhr.setRequestHeader("Content-Type", "application/json");
			xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
			xhr.setRequestHeader('X-CSRF-TOKEN', token);

			xhr.send(toSendJSONed);

			xhr.onload = function() {
				if(this.status == 200) {
					var responseText = JSON.parse(this.responseText);
					var response = responseText.data;
					location.reload();
				} else {
					var responseText = JSON.parse(this.responseText);
					var errorMessage = responseText.errors.ac_name;
					document.getElementById('errorMessage').innerHTML = errorMessage;
					document.querySelector('.areaname').style.borderColor = "#ff0000";
					document.querySelector('#errorMessage').style.display = "block";
				}
			}
		} else if(x.name == "town") {
			var area = x.getAttribute("data");

			var newTown = document.querySelector('.townname').value;
			var newTownCode = document.querySelector('.townCode').value;
			
			const toSend = {
				tc_name: newTown,
				tc_code: newTownCode
			}
			const toSendJSONed = JSON.stringify(toSend);
			var token = document.querySelector('meta[name="csrf-token"]').content;

			const xhr = new XMLHttpRequest();
			var updateTown = "{{route('update.town', ['id'=>':id'])}}";
			var newUpdateTown = updateTown.replace(':id', x.id);
			xhr.open("PATCH", newUpdateTown, true);
			xhr.setRequestHeader("Accept", "application/json");
			xhr.setRequestHeader("Content-Type", "application/json");
			xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
			xhr.setRequestHeader('X-CSRF-TOKEN', token);
			xhr.send(toSendJSONed);

			xhr.onload = function() {
				if(this.status == 200) {
					var responseText = JSON.parse(this.responseText);
					var response = responseText.data;
					Swal.fire({
						title: 'Success!',
						icon: 'success',
						text: responseText.message + " Town",
						type: 'success'
					})
					//alert(responseText.message);
					modal = document.querySelector('#editCode');
                	modal.style.display = "none";
					tdclick(area);
					document.querySelector(".towntablediv").style.overflowY = "none";
				} else {
					var responseText = JSON.parse(this.responseText);
					var errorMessage = responseText.errors.tc_name;
					document.getElementById('errorMessage').innerHTML = errorMessage;
					document.querySelector('.townname').style.borderColor = "#ff0000";
					document.querySelector('#errorMessage').style.display = "block";
				}
			}
		}
		else {
			var routename = document.querySelector('.routename').value;
			var routeCode = document.querySelector('#routeCode').value;

			const toSend = {
				rc_code: routeCode,
				rc_desc: routename
			}
			const toSendJSONed = JSON.stringify(toSend);
			var token = document.querySelector('meta[name="csrf-token"]').content;

			const xhr = new XMLHttpRequest();
			var updateRoute = "{{route('update.route', ['id'=>':id'])}}";
			var newUpdateRoute = updateRoute.replace(':id', x.id);
			xhr.open("PATCH", newUpdateRoute, true);
			xhr.setRequestHeader("Accept", "application/json");
			xhr.setRequestHeader("Content-Type", "application/json");
			xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
			xhr.setRequestHeader("Access-Control-Allow-Methods", "*");
			xhr.setRequestHeader("Access-Control-Allow-Credentials", "True");
			xhr.setRequestHeader('X-CSRF-TOKEN', token);
			xhr.send(toSendJSONed);

			xhr.onload = function() {
				if(this.status == 200) {
					var responseText = JSON.parse(this.responseText);
					var response = responseText.data;
					Swal.fire({
						title: 'Success!',
						icon: 'success',
						text: responseText.message + ' Route',
						type: 'success'
					})
					tdclick1(x.name);
					
					modal = document.querySelector('#editCode');
                	modal.style.display = "none";
					document.querySelector('.routeDesc').value = "";
                	document.querySelector('.routeCode').value = "";
					document.querySelector('.myInput').value = "";
				}
			}
		}
	}
	

	function addArea() {
		const request = new XMLHttpRequest();
		var token = document.querySelector('meta[name="csrf-token"]').content;
		var storeArea = "{{route('store.area')}}";
		request.open("POST", storeArea, true);
		request.setRequestHeader("Accept", "application/json");
		request.setRequestHeader("Content-Type", "application/json");
		request.setRequestHeader("Access-Control-Allow-Origin", "*");
		request.setRequestHeader("X-CSRF-TOKEN", token);

		var area = document.querySelector('.areaInput').value;

		const toSend = {
			ac_name: area
		};

		const toSendJSONed = JSON.stringify(toSend);
		request.send(toSendJSONed);

		request.onload = function() {
			if(this.status == 201) {
				alert('Successfully Added Area!');
				location.reload();
			} else {
				var responseText = JSON.parse(this.responseText);
				var errorMessage = responseText.errors.ac_name;
				document.querySelector('.addErrorMessage').innerHTML = errorMessage;
				document.querySelector('.areaInput').style.borderColor = "#ff0000";
				document.querySelector('.addErrorMessage').style.display = "block";
			}
		}
	}

	function addTown(b) {
		const request = new XMLHttpRequest();
		var token = document.querySelector('meta[name="csrf-token"]').content;
		var storeTown = "{{route('store.town')}}";
		request.open('POST', storeTown, true);
		request.setRequestHeader("Accept", "application/json");
		request.setRequestHeader("Content-Type", "application/json");
		request.setRequestHeader("Access-Control-Allow-Origin", "*");
		request.setRequestHeader("X-CSRF-TOKEN", token);

		var town = document.querySelector('.towninput').value;
		var townCode = document.querySelector('#townCode').value;

		const toSend = {
			ac_id: b.id,
			tc_name: town,
			tc_code: townCode
		};
		const toSendJSONed = JSON.stringify(toSend);
		request.send(toSendJSONed);

		request.onload = function() {
			if(this.status == 201) {
				Swal.fire({
					title: 'Success!',
					icon: 'success',
					text: 'Successfully Added Town',
					type: 'success'
				})
				modal = document.querySelector('#townmodal');
				modal.style.display = "none";
				document.querySelector('.towninput').value = "";
				tdclick(b.id).reload();
			} else {
				var responseText = JSON.parse(this.responseText);
				var errorMessage = responseText.errors.tc_name;
				document.querySelector('.addErrorMessageTown').innerHTML = errorMessage;
				document.querySelector('.towninput').style.borderColor = "#ff0000";
				document.querySelector('.addErrorMessageTown').style.display = "block";
			}
		}
	}

	function addRoute(c) {
		const request = new XMLHttpRequest();
		var token = document.querySelector('meta[name="csrf-token"]').content;
		var storeRoute = "{{route('store.route')}}";
		request.open('POST', storeRoute, true);
		request.setRequestHeader("Accept", "application/json");
		request.setRequestHeader("Content-Type", "application/json");
		request.setRequestHeader("Access-Control-Allow-Origin", "*");
		request.setRequestHeader("X-CSRF-TOKEN", token);

		var routeCode = document.querySelector('.routeCode').value;
		var routeDesc = document.querySelector('.routeDesc').value;
		const toSend = {
			tc_id: c.id,
			rc_code: routeCode,
			rc_desc: routeDesc
		};
		const toSendJSONed = JSON.stringify(toSend);
		request.send(toSendJSONed);
		request.onload = function() {
			if(this.status == 201) {
				Swal.fire({
					title: 'Success!',
					icon: 'success',
					text: 'Successfully Added Route',
					type: 'success'
				})
				modal = document.querySelector('#routemodal');
                modal.style.display = "none";
				document.querySelector('.routeDesc').value = "";
                document.querySelector('.routeCode').value = "";
				tdclick1(c.id).reload();
			} else {
				var responseText = JSON.parse(this.responseText);
				document.querySelector('.addErrorMessageRoute').innerHTML = "Please Fillup required Input fields!";
				document.querySelector('.routeCode').style.borderColor = "#ff0000";
				document.querySelector('.routeDesc').style.borderColor = "#ff0000";
				document.querySelector('.addErrorMessageRoute').style.display = "block";
			}
		}
	}

	function deleteCode(j) {
		if(j.height == "area"){
			const request = new XMLHttpRequest();
			var token = document.querySelector('meta[name="csrf-token"]').content;
			request.open("DELETE", "http://10.12.10.100:8082/api/v1/area/" + j.id);
			request.setRequestHeader("Accept", "application/json");
			request.setRequestHeader("Content-Type", "application/json");
			request.setRequestHeader("Access-Control-Allow-Origin", "*");
			request.setRequestHeader("X-CSRF-TOKEN", token);

			request.send();

			request.onload = function() {
				if(this.status == 202) {
					alert('Successfully Deleted!');
					location.reload();
				} 
			}
		} 
		else if(j.height == "town") {
			const request = new XMLHttpRequest();
			var token = document.querySelector('meta[name="csrf-token"]').content;

			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.isConfirmed) {
					var destroy = "{{route('delete.town', ['id'=>':id'])}}";
					var newDestroy = destroy.replace(':id', j.id);
					request.open("DELETE", newDestroy, true);
					request.setRequestHeader("Accept", "application/json");
					request.setRequestHeader("Content-Type", "application/json");
					request.setRequestHeader("Access-Control-Allow-Origin", "*");
					request.setRequestHeader("X-CSRF-TOKEN", token);

					request.send();

					request.onload = function() {
						if(this.status == 202) {
							Swal.fire(
								'Deleted!',
								'Town has been deleted.',
								'success'
							)
							tdclick(j.width);
						} 
					}
				}
			})
			
			
		} else {
			const request = new XMLHttpRequest();
			var token = document.querySelector('meta[name="csrf-token"]').content;

			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.isConfirmed) {
					var deleteRoute = "{{route('delete.route', ['id'=>':id'])}}";
					var newDeleteRoute = deleteRoute.replace(':id', j.id);
					request.open("DELETE", newDeleteRoute, true);
					request.setRequestHeader("Accept", "application/json");
					request.setRequestHeader("Content-Type", "application/json");
					request.setRequestHeader("Access-Control-Allow-Origin", "*");
					request.setRequestHeader("X-CSRF-TOKEN", token);
					
					request.send();
					
					request.onload = function() {
						if(this.status == 202) {
							Swal.fire(
								'Deleted!',
								'Route has been deleted.',
								'success'
							)
							var town = j.height;
							tdclick1(town);
						} 
					}
				}
			})
		}
	}
</script>
@endsection
