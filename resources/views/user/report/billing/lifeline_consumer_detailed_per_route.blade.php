@extends('layout.master')
@section('title', 'Lifeline Consumer - Detailed per Town')
@section('content')

<p class="contentheader">Lifeline Consumer - Detailed per Route</p>
<div class="main">
    <table style ="height:280px;" border="0" class="content-table">
    <tr>
        <tr>
            <td class="thead">
             Route Code:
            </td>
            <td  class="input-td">
                <input id="routeID" style="color:black;" type="text" class="input-Txt" href="#route" name="route" placeholder="Select Route" readonly>
            </td>
        </tr>
        <tr>
        <td class="thead">
            Billing Period:
            </td>
            <td class="input-td ">
                <input type = "month" onfocusout="billingPeriod(this)" name = "month">
            </td>
        </tr>
        <tr>
        <td class = "thead">
            </td>
            <td class = "thead">
            </td>
            <td colspan="3" class = "thead">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             Position
            </td>
        </tr>
        </table>
        <table style ="width:80%;height:300px;" border="0" class="content-table">
        <tr>

            <td class = "thead">
                Prepared By:
            </td>
            <td class = "input-td">
                <input type = "text" placeholder = "Prepared By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td class = "thead">
                Checked By:
            </td>
            <td class = "input-td">
                <input type = "text" placeholder = "Checked By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td class = "thead">
                Noted By:
            </td>
            <td class = "input-td">
                <input type = "text" placeholder = "Noted By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <button style="width:70px;margin-top:30px;height:40px;" onclick = "sendLifeLnCons()" id="printBtn" >Print</button>
            </td>
        </tr>
    </table>
</div>
<script>
	var lifeLineConsDetailedPerRouteToSend = new Object();
           var xhr = new XMLHttpRequest();
			xhr.open('GET','http://10.12.10.100:8082/api/v1/route',true);
			xhr.onload = function(){
				if(this.status == 200){
				var data = JSON.parse(this.responseText);
					var output = " ";
                    var output1 = " "; 
					var val = data.data;
					var val3 = data.meta['last_page'];
					var val2 = data.meta['current_page'];
					output += '<table style="text-align:left;width:100%;" border=1 class="modal-table" id="table1">';
					output +='<tr>' + '<th>' + 'Route Lookup' +  '+' + '</th>'+ '</tr>';
					for(var i in val){

                    output += '<tr>'+ '<td id = ' + val[i].route_code_id +  ' onclick="tdclick(this.id)" >' + val[i].route_code + '</td>';
                    output += '<td>' + val[i].route_desc + '</td>' + '</tr>'; }
				//for(var x = 1; x <= val2; x++){
					//var a = a+1;}
					var b = val2 +1;

					if(val2 <= 1){
                        output1 += '<tr>';
                        output1 += '<td>' + '<button id = "btn" onclick="s(this.value)" value=' + val2 +' hidden>' + 'Previous' + '</button>';}
					else{
					    output1 += '<td>' + '<button id = "btn" onclick="s(this.value)" value=' + val2 + '>' + 'Previous' + '</button>';
					}
                        output1 += '<input style="width:10%;" type="text" class="currentpage" value="' + val2 + '" >';
					if(val2 < val3){
					    output1 +=  '<button id = "btn" onclick="s(this.value)" value=' + b  +'  >' + 'Next' + '</button>';}
					else{
					    output1 += '<button id = "btn" onclick="s(this.value)" value=' + b  +' hidden>' + 'Next' + '</button>';
					}
					output += '</td>' + '</tr>'+'</table>';

					//output1 +=  a '>' +  a++ + '</button>';

				document.querySelector('.modaldiv1').innerHTML = output;
                document.querySelector('.pages1').innerHTML = output1;
			}
		}
		xhr.send();
/*------------------------------------------------END MODAL ROUTE ---------------------------------------------------------*/
/* -----------------------------------------------paginate click-----------------------------------------------------------*/
    function s(p){
			var xhr = new XMLHttpRequest();
			xhr.open('GET','http://10.12.10.100:8082/api/v1/route?page='+p,true);
			xhr.onload = function(){
				if(this.status == 200){
				var data = JSON.parse(this.responseText);
					var output = " ";
                    var output1 = " ";
					var val = data.data;
					var val3 = data.meta['last_page'];
					var val2 = data.meta['current_page'];
					output += '<table style="text-align:left;width:100%;" border=1 class="modal-table" id="table1">';
					output +='<tr>' + '<th>' + 'Route Lookup' +  '+' + '</th>'+ '</tr>';
					for(var i in val){
					output += '<tr>'+ '<td onclick="tdclick(this.id);" id=' + val[i].route_code_id +  '>' + val[i].route_code + '</td>';
                    output += '<td>' + val[i].route_desc + '</td>' + '</tr>'; }
				//for(var x = 1; x <= val2; x++){
					//var a = a+1;

					//output1 +=  a '>' +  a++ + '</button>';
						var b = val2 +1;
						var c = val2 - 1;
					if(val2 <= 1){
					output1 += '<tr>';
					output1 += '<td>' + '<button id = "btn" onclick="setTimeout(s(this.value),3000)" value=' + c +' hidden>' + 'Previous' + '</button>';}
					else{
					output1 += '<td>' + '<button id = "btn" onclick="setTimeout(s(this.value),3000)" value=' + c + '>' + 'Previous' + '</button>';
					}
					output1 += '<input style="width:10%;" type="text" class="currentpage" value="' + val2 + '" >';
					if(val2 >= val3){
					output1 +=  '<button id = "btn" onclick="setTimeout(s(this.value),3000)" value=' + b  +'  hidden>' + 'Next' + '</button>';}
					else{
					output1 += '<button id = "btn" onclick="setTimeout(s(this.value),3000)" value=' + b  +' >' + 'Next' + '</button>';
					}
					output += '</td>' + '</tr>'+'</table>';

				document.querySelector('.modaldiv1').innerHTML = output;
                document.querySelector('.pages1').innerHTML = output1;
			}
		}
		xhr.send();
	}
/* ----------------------------------------------- END Paginate-----------------------------------------------------*/
/* -----------------------------------------------tdclick-----------------------------------------------------------*/
    function tdclick(rID){
        // var routeDesc = document.querySelector('#routeID').value;
	    var hide =document.querySelector('#route');
        hide.style.display="none";
        //document.querySelector('#routeID').value = rID; //pashpash
        var routeDesc = document.getElementById(rID).nextElementSibling;
        document.querySelector('#routeID').value = routeDesc.innerHTML;
        lifeLineConsDetailedPerRouteToSend.route_id = rID;     
    }
	function billingPeriod(val){
		lifeLineConsDetailedPerRouteToSend.date = val.value;  
	}
/*---------------------------------------END PAGINATE------------------------------------------------- */
function search() {
					  var input, filter;
					  input = document.getElementById("myID");
					  filter = input.value.toUpperCase();
				if(filter.length > 0){
					var xhr = new XMLHttpRequest();
					xhr.open('GET','http://10.12.10.100:8082/api/v1/route/'+filter,true);
					xhr.onload = function(){
					 	if(this.status == 200){
							var data = JSON.parse(this.responseText);
								var output = " ";
								var val = data.data;
                                output += '<div style="overflow:scroll;height:300px;">';
                                output += '<table style="text-align:left;width:100%;height:20px;" border=1 class="modal-table" id="table1">';
								output +='<tr>' + '<th>' + 'Route Description' + '</th>' + '</tr>';
								for(var i in val){
                                output += '<tr>'+ '<td id = ' + val[i].route_code_id +  ' onclick="tdclick(this.id)" >' + val[i].route_code + '</td>';
                                output += '<td>' + val[i].route_desc + '</td>' + '</tr>';}
                                output += '</table></div>';
								}
					  		document.querySelector('.modaldiv1').innerHTML = output;
                              document.querySelector('.pages1').innerHTML = " ";
						}
							xhr.send();
					}
                else{
                    var xhr = new XMLHttpRequest();
			xhr.open('GET','http://10.12.10.100:8082/api/v1/route',true);
			xhr.onload = function(){
				if(this.status == 200){
				var data = JSON.parse(this.responseText);
					var output = " ";
                    var output1 = " ";
					var val = data.data;
					var val3 = data.meta['last_page'];
					var val2 = data.meta['current_page'];
					output += '<table style="text-align:left;width:100%;" border=1 class="modal-table" id="table1">';
					output +='<tr>' + '<th>' + 'Route Lookup' +  '+' + '</th>'+ '</tr>';
					for(var i in val){

                    output += '<tr>'+ '<td id = ' + val[i].route_code_id +  ' onclick="tdclick(this.id)" >' + val[i].route_code + '</td>';
                    output += '<td>' + val[i].route_desc + '</td>' + '</tr>'; }
				//for(var x = 1; x <= val2; x++){
					//var a = a+1;}
					var b = val2 +1;

					if(val2 <= 1){
                        output1 += '<tr>';
                        output1 += '<td>' + '<button id = "btn" onclick="s(this.value)" value=' + val2 +' hidden>' + 'Previous' + '</button>';}
					else{
					    output1 += '<td>' + '<button id = "btn" onclick="s(this.value)" value=' + val2 + '>' + 'Previous' + '</button>';
					}
                        output1 += '<input style="width:10%;" type="text" class="currentpage" value="' + val2 + '" >';
					if(val2 < val3){
					    output1 +=  '<button id = "btn" onclick="s(this.value)" value=' + b  +'  >' + 'Next' + '</button>';}
					else{
					    output1 += '<button id = "btn" onclick="s(this.value)" value=' + b  +' hidden>' + 'Next' + '</button>';
					}
					output += '</td>' + '</tr>'+'</table>';

					//output1 +=  a '>' +  a++ + '</button>';

				document.querySelector('.modaldiv1').innerHTML = output;
                document.querySelector('.pages1').innerHTML = output1;
			}
		}
		xhr.send();
                }
	}
/* ----------------------------------------------------------------------------------- */
function sendLifeLnCons(){
	var xhr = new XMLHttpRequest();
	var lcndr = "{{route('report.lifeline.consumer.detailed.per.route')}}";
	xhr.open('POST',lcndr,true);
	xhr.setRequestHeader("Accept", "application/json");
    xhr.setRequestHeader("Content-Type", "application/json");
	xhr.onload = function(){
		if(this.status == 200){
			var data = JSON.parse(this.responseText);
			var date = lifeLineConsDetailedPerRouteToSend.date;
				localStorage.setItem("date",JSON.stringify(date));
				localStorage.setItem("data3",JSON.stringify(data));
				var url = "{{route('lConsDetailedPerRoute')}}";
				window.open(url);
		}
	}
	xhr.send(JSON.stringify(lifeLineConsDetailedPerRouteToSend));
	localStorage.removeItem("date");
	localStorage.removeItem("data3");
}
</script>
@endsection
