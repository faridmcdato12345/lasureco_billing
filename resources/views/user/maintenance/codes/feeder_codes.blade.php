@extends('layout.master')
@section('title', 'Feeder Codes')
@section('content')

<style>
    .substationTable{
        width: 99%; 
        margin: auto;
        font-size: 17px;
        border: 1px solid #ddd;
    }
    .substationTable td{
        height: 50px;
        border-bottom: 1px solid #ddd;
        cursor: pointer;
    }
    .substationTable td:hover{
        background-color: #5B9BD5;
        color: white;
    }
    .addFeederTable{
        width: 99%;
        margin: auto;
    }
    .addFeederTable td {
        height: 60px;
    }
    #feederTable{
        width: 100%;
        color: black;
    }
    #feederTable td{
        height: 50px;
        border-bottom: 1px solid #ddd;
    }
    #tablehead{
        background-color: #5B9BD5;
        color: white;
    }
    #input{
        width: 200px;
    }   
    .searchFeeder{
        display: none;
    }
    .addFeeder {
        display: none;
    }
    .addFeederBtn {
        border: none;
        border-radius: 3px;
        height: 40px;
        width: 80%;
        background-color: white;
        color: royalblue;
    }
    .searchFeederInp {
        width: 55%;
        float: right;
        margin-right: 5px;
    }
    .addFeederAddBtn {
        float: right;
        border: none;
        border-radius: 3px;
        height: 40px;
        color: white;
        background-color: royalblue;
        width: 15%;
        margin-right: 2%;
    }
    .editBtn {
        border: none;
        border-radius: 3px;
        height: 35px;
        background-color: rgb(23, 108, 191);
        color: white;
        width: 50%;
    }
    .deleteBtn {
        border: none;
        border-radius: 3px;
        height: 35px;
        background-color: rgb(238, 64, 53);
        color: white;
        width: 40%;
    }
    .editFeederSaveBtn {
        border: none;
        border-radius: 3px;
        height: 40px;
        background-color: rgb(23, 108, 191);
        color: white;
        float: right;
        width: 15%;
        margin-right: 2%; 
    }
</style>

<p class="contentheader">Feeder Codes</p>
<div class="main">
    <table style="width: 97%; margin: auto;">
        <tr style="height: 50px;">
            <td style="color: white; width: 12%;">
                &nbsp;&nbsp;&nbsp; Substation:
            </td>
            <td>
                <input type="text" id="input" class="input-Txt" href="#substation" placeholder="Select Substation Code" readonly>
            </td>
            <td>
                <span class="searchFeeder"></span>
            </td>
            <td>
                <span class="addFeeder"></span>
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <div style="background-color: white; height: 435px; width: 97%; margin: auto;">
                    <table id="feederTable"> 
                        <tr id="tablehead"> 
                            <td> 
                                &nbsp; Feeder Code 
                            </td> 
                            <td> 
                                Feeder Description 
                            </td>   
                            <td colspan=2 style="text-align: center;"> 
                                Action 
                            </td> 
                        </tr>
                        <tr>
                            <td colspan=4 style="text-align: center; color: #ddd; height: 350px; border-bottom: none; font-size: 50px;">
                                Please Select Substation
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>

<div id="substation" class="modal">
	<div class="modal-content" style="width: 35%; height: 350px; margin-top: 40px;">
        <div class="modal-header" style="height: 65px;">
            <h3> Substation Lookup </h3>
            <span href="#substation" class="closes">&times;</span>
        </div>
        <div class="modal-body" style="color: black;">
            <table class="substationTable" border=1> </table>
        </div>
	</div>
</div>

<div id="addFeeder" class="modal">
	<div class="modal-content" style="width: 35%; height: 280px; margin-top: 47px;">
        <div class="modal-header" style="height: 65px;">
            <h3> Add Feeder </h3>
            <span href="#addFeeder" class="closes">&times;</span>
        </div>
        <div class="modal-body" style="color: black;">
            <table class="addFeederTable"> 
                <tr>
                    <td>
                        Feeder Code:
                    </td>
                    <td>
                        <input type="text" id="fCode" placeholder="Feeder Code">
                    </td>
                </tr>
                <tr>
                    <td>
                        Feeder Description: 
                    </td>
                    <td>
                        <input type="text" id="fDesc" placeholder="Feeder Description">
                    </td>
                </tr>
                <tr>
                    <td colspan=2>
                        <span id="addFeederBtnSpan"> </span>
                    </td>
                </tr>
            </table>
        </div>
	</div>
</div>

<div id="editFeeder" class="modal">
	<div class="modal-content" style="width: 35%; height: 280px; margin-top: 47px;">
        <div class="modal-header" style="height: 65px;">
            <h3> Edit Feeder </h3>
            <span href="#editFeeder" class="closes">&times;</span>
        </div>
        <div class="modal-body" style="color: black;">
            <table class="addFeederTable"> 
                <tr>
                    <td>
                        Feeder Code:
                    </td>
                    <td>
                        <input type="text" id="newfCode" placeholder="Feeder Code">
                    </td>
                </tr>
                <tr>
                    <td>
                        Feeder Description: 
                    </td>
                    <td>
                        <input type="text" id="newfDesc" placeholder="Feeder Description">
                    </td>
                </tr>
                <tr>
                    <td colspan=2>
                        <span id="editFeederBtnSpan"> </span>
                    </td>
                </tr>
            </table>
        </div>
	</div>
</div>

<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn3');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container3').childNodes;
        c[5].style.color="blue";
    }
    
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://10.12.10.100:8082/api/v1/substation', true);
    xhr.send();

    xhr.onload = function(){
        if(this.status == 200){
            output = "";
            var response = JSON.parse(this.responseText);
            var substation = response.data;

            for(var x in substation){
                output += "<tr> <td onclick='showFeeder(this)' name='" + substation[x].sub_desc + "' id='" + substation[x].sub_id; 
                output += "'> &nbsp;&nbsp;" + substation[x].sub_desc + " substation</td> </tr>";
            }
            document.querySelector('.substationTable').innerHTML = output;
        }
    }

    function showFeeder(x){
        var req = new XMLHttpRequest();
        var subId = x.id;
        req.open('GET', 'http://10.12.10.100:8082/api/v1/feeder/' + subId, true);
        req.send();
        var output ="<tr id='tablehead'> <td> &nbsp; Feeder Code </td> <td> Feeder Description </td> <td colspan=2 style='text-align: center;'> Action </td> </tr>";
        var subName = x.getAttribute('name');
        
        req.onload = function(){
            if(this.status == 200){
                var searchFeederInp = document.querySelector('.searchFeeder');
                var addFeederButton = document.querySelector('.addFeeder');

                searchFeederInp.innerHTML = "<input type='text' class='searchFeederInp' name='" + subName + "' id='" + subId + "' onkeyup='setTimeout(searchFeeder(), 7000);' placeholder='Search Feeder Here...'>";
                addFeederButton.innerHTML = "<button class='addFeederBtn' onclick='showAddFeeder(this)' id='" + subId + "' name='" + subName + "'> Add </button>";
                
                searchFeederInp.style.display = "block";
                addFeederButton.style.display = "block";
                
                var respo = JSON.parse(this.responseText);
                var feeder = respo.data;
                
                for(var x in feeder){
                    output += "<tr> <td>&nbsp;&nbsp;&nbsp;" + feeder[x].feeder_code + "</td>";
                    output += "<td>&nbsp;" + feeder[x].feeder_description + "</td>";
                    output += "<td> <button class='editBtn' onclick='editFeeder(this)' id='" + feeder[x].feeder_code_id + "'";
                    output += "code='" + feeder[x].feeder_code + "' desc='" + feeder[x].feeder_description + "'> Edit </button> </td>";
                    output += "<td> <button class='deleteBtn' onclick='deleteFeeder(this)' id='" + feeder[x].feeder_code_id + "'> Delete </button> </td> </tr>";
                }
                document.querySelector('#feederTable').innerHTML = output;
                document.querySelector('#input').value = subName;
                document.querySelector('#substation').style.display = "none";
            }
        }
    }

    function searchFeeder(){
        var searchFeeder = document.querySelector('.searchFeederInp');
        var feeder = searchFeeder.value;
        var feederId = searchFeeder.id; 
        var innerHTML = "<tr id='tablehead'> <td> &nbsp; Feeder Code </td> <td> Feeder Description </td> <td colspan=2 style='text-align: center;'> Action </td> </tr>";
        
        if(feeder.length > 0){
            var xhreq = new XMLHttpRequest();
            xhreq.open('GET', 'http://10.12.10.100:8082/api/v1/feeder/substation/' + feederId + '/search/' + feeder, true);
            xhreq.send();

            xhreq.onload = function(){
                if(this.status == 200){
                    var respo = JSON.parse(this.responseText);
                    
                    if(respo.length > 0){
                        for(var i in respo){
                            innerHTML += "<tr> <td>&nbsp;&nbsp;&nbsp;" + respo[i].fc_code + "</td>";
                            innerHTML += "<td>&nbsp;" + respo[i].fc_desc + "</td>";
                            innerHTML += "<td> <button class='editBtn' onclick='editFeeder(this)' id='" + respo[i].fc_id + "'";
                            innerHTML += "code='" + respo[i].fc_code + "' desc='" + respo[i].fc_desc + "'> Edit </button> </td>";
                            innerHTML += "<td> <button class='deleteBtn' onclick='deleteFeeder(this)' id='" + respo[i].fc_id + "'> Delete </button> </td> </tr>";
                        }
                        document.querySelector('#feederTable').innerHTML = innerHTML;
                    } else{
                        document.querySelector('#feederTable').innerHTML = "<tr> <td> No Search Results </td> </tr>";
                    }
                }
            }
        } else{
            var asd = document.querySelector('.searchFeederInp');
            var subName = asd.name;
            document.querySelector('#input').value = subName;
            showFeeder(asd);
        }
    }

    function showAddFeeder(j){
        document.querySelector('#addFeeder').style.display = "block";
        var addFeederBtn = document.querySelector('#addFeederBtnSpan');
        var output = '<button class="addFeederAddBtn" id="' + j.id + '" name="' + j.name + '" onclick="addFeeder(this)"> Add </button>';
        addFeederBtn.innerHTML = output;
    }

    function addFeeder(a){
        var feederId = a.id;
        var fCode = document.querySelector('#fCode').value;
        var fDesc = document.querySelector('#fDesc').value;

        var request = new XMLHttpRequest();
		var token = document.querySelector('meta[name="csrf-token"]').content;
		request.open("POST", "http://10.12.10.100:8082/api/v1/feeder");
		request.setRequestHeader("Accept", "application/json");
		request.setRequestHeader("Content-Type", "application/json");
		request.setRequestHeader("Access-Control-Allow-Origin", "*");
		request.setRequestHeader("X-CSRF-TOKEN", token);    

        const toSend = {
            sc_id: feederId,
            fc_code: fCode,
            fc_desc: fDesc
        }

        const toSendJSONed = JSON.stringify(toSend);
		request.send(toSendJSONed);

        request.onload = function(){
            if(this.status == 201){
                alert('Successfully Added!');
                var feeds = document.querySelector('.addFeederAddBtn');
                document.querySelector("#addFeeder").style.display = "none";   
                document.querySelector('#input').value = a.name;
                showFeeder(feeds);
            }
        }
    }

    function editFeeder(b) {
        var feederId = b.id;
        var feederCode = b.getAttribute('code');
        var feederDesc = b.getAttribute('desc');

        document.querySelector('#newfCode').value = feederCode;
        document.querySelector('#newfDesc').value = feederDesc;

        document.querySelector('#editFeeder').style.display ="block";
        document.querySelector('#editFeederBtnSpan').innerHTML = "<button class='editFeederSaveBtn' onclick='updateFeeder(this)' id='" + feederId + "'> Save </button>";
    }

    function updateFeeder(x){
        var feederId = x.id;
        var feederCode = document.querySelector('#newfCode').value;
        var feederDesc = document.querySelector('#newfDesc').value;

        const toPatch = {
            fc_code: feederCode,
            fc_desc: feederDesc
        }

        const toPatchJSONed = JSON.stringify(toPatch);
        var token = document.querySelector('meta[name="csrf-token"]').content;

        var req = new XMLHttpRequest();
        req.open('PATCH', 'http://10.12.10.100:8082/api/v1/feeder/' + feederId, true);
        req.setRequestHeader("Accept", "application/json");
		req.setRequestHeader("Content-Type", "application/json");
		req.setRequestHeader("Access-Control-Allow-Origin", "*");
		req.setRequestHeader("Access-Control-Allow-Methods", "*");
		req.setRequestHeader("Access-Control-Allow-Credentials", "True");
		req.setRequestHeader('X-CSRF-TOKEN', token);
		req.send(toPatchJSONed);

        req.onload = function(){
            if(this.status == 200){
                alert('Successful');
                var feeder = document.querySelector('.addFeederAddBtn');
                document.querySelector('#editFeeder').style.display = "none";
                showFeeder(feeder);
            }
        }
    }

    function deleteFeeder(a){
        var feederId = a.id;
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var xhr = new XMLHttpRequest();
        xhr.open('DELETE', 'http://10.12.10.100:8082/api/v1/feeder/' + feederId, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
		xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send();
        var id = document.querySelector('.addFeederBtn');
        alert('Successfullly Deleted!');
        showFeeder(id);
    }
</script>
@endsection
