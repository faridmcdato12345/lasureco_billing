@extends('layout.master')
@section('title', 'Accounting Codes')
@section('content')

<style>
    #accntable {
        text-align: center;
        width: 100%;
        background-color: white;
        color: black;
    }
    #accntable td {
        height: 40px;
        border-bottom: 1px solid #ddd;
    }
    #paginateBtns {
        width: 200px;
        float: left;
        margin-left: 4px;
        text-align: center;
    }
    #prev {
        width: 66.66px;
    }
    #next {
        width: 66.66px;
    }
    #pagetd {
        width: 66.66px;
    }
    button {
        border-radius: 3px;
        border: none;
    }
    #search {
        width: 250px;
        float: right;
    }
    .editButton {
        background-color: #5B9BD5; 
        color: white;
    }
    .deleteButton {
        background-color: rgb(238, 64, 53); 
        color: white;
    }
    #addBtn {
        margin-right: 25px; 
        height: 40px; 
        width: 60px;
    }
    .addTable {
        width: 100%;
    }
    .addTable tr {
        height: 70px;
    }
    .modalAddBtn {
        height: 40px;
        background-color: rgb(23, 108, 191);
        color: white;
        width: 65px;
        float: right;
        margin-right: 10px;
    }
    button {
        cursor: pointer;
    }
</style>

<table style="width: 100%;">
    <tr>
        <td style="width: 80%;">
            <p class="contentheader">Accounting Codes</p>
        </td>
        <td style="width: 10%;">
            <input type="text" id="search" onkeyup="setTimeout(search(), 1000);" placeholder="Search Code here...">
        </td>
        <td style="text-align: right; width: 10%;">
            <button class="modal-button" id="addBtn" href="#addAccntCode">
                Add
            </button>
        </td>
    </tr>
</table>

<div class="main">
    <div style="background-color: white; height: 443px; width: 97%; margin: auto;">
        <table id="accntable"> </table>
    </div>
    <table id="paginateBtns"> 
        <tr>
            <td id="prev"> </td>
            <!-- <td id="pagetd"> <input type="text" id="page" readonly> -->
            <td id="next"> </td>
        </tr>
    </table>
</div>

<div id="addAccntCode" class="modal">
	<div class="modal-content" style="width: 60%; height: 390px;">
        <div class="modal-header">
            <h3> Add Accounting Code </h3>
            <span href="#addAccntCode" class="closes">&times;</span>
        </div>
        <div class="modal-body" style="color: black;">
            <table class="addTable">
                <tr>
                    <td>
                        &nbsp; Code:
                    </td>
                    <td>
                        <input type="number" id="feeCode" placeholder="Fee Code">
                    </td>
                    <td>
                        &nbsp; Description:
                    </td>
                    <td>
                        <input type="text" id="feeDesc" placeholder="Fee Description">
                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp; Amount:
                    </td>
                    <td>
                        <input type="number" placeholder="Fee Amount" id="feeAmount">
                    </td>
                    <td>
                        &nbsp; Vatable:
                    </td>
                    <td>
                        <select id="feeVatable" style="height: 40px; width: 97%;">
                            <option value="1"> Yes </option>
                            <option value="0"> No </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp; Percent:
                    </td>
                    <td>
                        <input type="number" placeholder="Fee Percent" id="feePercent">
                    </td>
                </tr>
                <tr>
                    <td id="modalBtn" colspan=4>
                        <button class="modalAddBtn" onclick="addCode()"> Add </button>
                    </td>
                </tr>
            </table>
        </div>
	</div>
</div>

<div id="editAccntCode" class="modal">
	<div class="modal-content" style="width: 60%; height: 390px;">
        <div class="modal-header">
            <h3> Edit Accounting Code </h3>
            <span href="#editAccntCode" class="closes">&times;</span>
        </div>
        <div class="modal-body" style="color: black;">
            <table class="addTable">
                <tr>
                    <td>
                        &nbsp; Code:
                    </td>
                    <td>
                        <input type="number" id="editfeeCode" placeholder="Fee Code">
                    </td>
                    <td>
                        &nbsp; Description:
                    </td>
                    <td>
                        <input type="text" id="editfeeDesc" placeholder="Fee Description">
                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp; Amount:
                    </td>
                    <td>
                        <input type="number" placeholder="Fee Amount" id="editfeeAmount">
                    </td>
                    <td>
                        &nbsp; Vatable:
                    </td>
                    <td id="vatable"> </td>
                </tr>
                <tr>
                    <td>
                        &nbsp; Percent:
                    </td>
                    <td>
                        <input type="number" placeholder="Fee Percent" id="editfeePercent">
                    </td>
                </tr>
                <tr>
                    <td id="editBtn" colspan=4> </td>
                </tr>
            </table>
        </div>
	</div>
</div>

<script>
    var xhr = new XMLHttpRequest();
    var routefees = "{{route('index.fees')}}";
    xhr.open('GET', routefees, true);
    xhr.send();

    xhr.onload = function(){
        if(this.status == 200){
            var data = JSON.parse(this.responseText);
            console.log(data);
            var output = "<tr style='background-color: #5B9BD5; color: white;'> <td> Code </td> <td> Description </td> <td> Amount </td>";
            output += "<td> Vatable </td> <td> % </td> <td colspan=2> Action </td>";
            var fees = data.data;
            // var currentpage = data.meta['current_page'];
            // var lastpage = data.meta['last_page'];
            
            for(var i in fees){
                output += "<tr> <td>" + fees[i].fees_code + "</td>";
                output += "<td>" + fees[i].fees_desc + "</td>";
                output += "<td>" + fees[i].fees_amount + "</td>";
                if(fees[i].fees_vatable == 1){
                    output += "<td> Yes </td>";
                } else {
                    output += "<td> No </td>";
                }
                output += "<td>" + fees[i].fees_percent + "</td>";
                output += "<td> <button onclick='editCode(this)'";
                output += "code='" + fees[i].fees_code + "' desc='" + fees[i].fees_desc + "' ";
                output += "amount='" + fees[i].fees_amount + "' vatable='" + fees[i].fees_vatable + "' ";
                output += "percent='" + fees[i].fees_percent + "'";
                output += "class='editButton' id='" + fees[i].fees_id + "' > Edit </button> </td>";
                output += "<td> <button onclick='deleteCode(this)' class='deleteButton' id='" + fees[i].fees_id + "'> Delete </button> </td> </tr>";
            }
            document.querySelector('#accntable').innerHTML = output;
            
            // var increment = currentpage + 1;
            // var decrement = currentpage - 1;
           
            // if(currentpage > 1) {
            //     document.getElementById('prev').innerHTML = "<button type='button' style='height: 40px;' disabled> Prev </button>";
            // } else {
            //     var next = document.getElementById('next');
            //     document.getElementById('prev').innerHTML = "<button style='height: 40px;' type='button' disabled> Prev </button>";
            //     next.innerHTML = "<button id = '" + increment + "' style='height: 40px;' onclick='paginate(this.id)''> Next </button>";
            // }
            // document.getElementById('page').value = currentpage;
        }
    }

    // function paginate(x){
    //     var request = new XMLHttpRequest();
    //     var routefees2 = "{{route('index.fees', '?page=')}}" + x;
    //     request.open('GET', routefees2,true);
    //     request.send();

    //     request.onload = function() {
    //         if(this.status == 200){
    //             document.querySelector('#paginateBtns').style.display = "block";
    //             var data = JSON.parse(this.responseText);
    //             var currentpage = data.meta['current_page'];
    //             var lastpage = data.meta['last_page'];
    //             var decrement = currentpage - 1;
    //             var fees = data.data;
    //             var output = "<tr style='background-color: #5B9BD5; color: white;'> <td> Code </td> <td> Description </td> <td> Amount </td>";
    //             output += "<td> Vatable </td> <td> % </td> <td colspan=2> Action </td>";

    //             if(currentpage < lastpage){
    //                 if(currentpage >= 0){
    //                     document.getElementById('next').style.display = "block";
    //                     document.getElementById('prev').style.display = "block";
    //                     var next = document.getElementById('next');
    //                     next.innerHTML = "<button id = '" + (currentpage+1) + "' style='height: 40px;' onclick='paginate(this.id)''> Next </button>";
    //                     if(currentpage > 1) {
    //                         for(var i in fees){
    //                             output += "<tr> <td>" + fees[i].fees_code + "</td>";
    //                             output += "<td>" + fees[i].fees_desc + "</td>";
    //                             output += "<td>" + fees[i].fees_amount + "</td>";
    //                             if(fees[i].fees_vatable == 1) {
    //                                 output += "<td> Yes </td>";
    //                             } else {
    //                                 output += "<td> No </td>";
    //                             }
    //                             output += "<td>" + fees[i].fees_percent + "</td>";
    //                             output += "<td> <button class='editButton' onclick='editCode(this)'";
    //                             output += "code='" + fees[i].fees_code + "' desc='" + fees[i].fees_desc + "' ";
    //                             output += "amount='" + fees[i].fees_amount + "' vatable='" + fees[i].fees_vatable + "' ";
    //                             output += "percent='" + fees[i].fees_percent + "'";
    //                             output += "id='" + fees[i].fees_id + "' page='" + currentpage + "'> Edit </button> </td>";
    //                             output += "<td> <button onclick='deleteCode(this)' id='" + fees[i].fees_id + "' class='deleteButton'> Delete </button> </td> </tr>";
    //                         }
    //                         document.querySelector('#accntable').innerHTML = output;
                            
    //                         document.getElementById('prev').style.display = "block";
    //                         var prev = document.getElementById('prev');
    //                         prev.innerHTML = "<button id = '" + (currentpage-1) + "' style='height: 40px;' onclick='paginate(this.id)''> Prev </button>";
    //                     } else{
    //                         for(var i in fees){
    //                             output += "<tr> <td>" + fees[i].fees_code + "</td>";
    //                             output += "<td>" + fees[i].fees_desc + "</td>";
    //                             output += "<td>" + fees[i].fees_amount + "</td>";
    //                             if(fees[i].fees_vatable == 1) {
    //                                 output += "<td> Yes </td>";
    //                             } else {
    //                                 output += "<td> No </td>";
    //                             }
    //                             output += "<td>" + fees[i].fees_percent + "</td>";
    //                             output += "<td> <button class='editButton' onclick='editCode(this)'";
    //                             output += "code='" + fees[i].fees_code + "' desc='" + fees[i].fees_desc + "' ";
    //                             output += "amount='" + fees[i].fees_amount + "' vatable='" + fees[i].fees_vatable + "' ";
    //                             output += "percent='" + fees[i].fees_percent + "'";
    //                             output += "id='" + fees[i].fees_id + "' page='" + currentpage + "'> Edit </button> </td>";
    //                             output += "<td> <button onclick='deleteCode(this)' id='" + fees[i].fees_id + "' class='deleteButton'> Delete </button> </td> </tr>";
    //                         }
    //                         document.querySelector('#accntable').innerHTML = output;
    //                         document.getElementById('prev').innerHTML = "<button type='button' style='height: 40px;' disabled> Prev </button>";
    //                     }
    //                 }
    //             } else{
    //                 for(var i in fees){
    //                     output += "<tr> <td>" + fees[i].fees_code + "</td>";
    //                     output += "<td>" + fees[i].fees_desc + "</td>";
    //                     output += "<td>" + fees[i].fees_amount + "</td>";
    //                     if(fees[i].fees_vatable == 1) {
    //                         output += "<td> Yes </td>";
    //                     } else {
    //                         output += "<td> No </td>";
    //                     }
    //                     output += "<td>" + fees[i].fees_percent + "</td>";
    //                     output += "<td> <button class='editButton' onclick='editCode(this)'";
    //                     output += "code='" + fees[i].fees_code + "' desc='" + fees[i].fees_desc + "' ";
    //                     output += "amount='" + fees[i].fees_amount + "' vatable='" + fees[i].fees_vatable + "' ";
    //                     output += "percent='" + fees[i].fees_percent + "'";
    //                     output += "id='" + fees[i].fees_id + "' page='" + currentpage + "'> Edit </button> </td>";
    //                     output += "<td> <button onclick='deleteCode(this)' id='" + fees[i].fees_id + "' class='deleteButton'> Delete </button> </td> </tr>";
    //                 }
    //                 document.querySelector('#accntable').innerHTML = output;
    //                 document.getElementById('next').innerHTML = "<button type='button' style='height: 40px;' disabled> Next </button>";
    //                 var prev = document.getElementById('prev');
    //                 prev.innerHTML = "<button id = '" + (lastpage-1) + "' style='height: 40px;' onclick='paginate(this.id)''> Prev </button>";
    //             }
    //             document.getElementById('page').value = currentpage;
    //         }
    //     }
    // }

    function search(){
        var search = document.getElementById('search');
        var filter = search.value.toUpperCase();

        if(filter.length > 0 ) {
            var xhr = new XMLHttpRequest();
            var routefees3 = "{{route('search.fees', ['request' => ':par'])}}";
            var routefeesF = routefees3.replace(':par', filter);
            xhr.open('GET', routefeesF, true);
            xhr.send();

            xhr.onload = function(){
                if(this.status == 200){
                    var response = JSON.parse(this.responseText);
                    var data = response.data;
                    console.log(response);
                    if(data.length > 0){
                        var output = "<tr style='background-color: #5B9BD5; color: white;'> <td> Code </td> <td> Description </td> <td> Amount </td>";
                        output += "<td> Vatable </td> <td> % </td> <td colspan=2> Action </td> </tr>";
                        
                        for(var x in data){
                            output += "<tr> <td>" + data[x].fees_code + "</td>";
                            output += "<td>" + data[x].fees_desc + "</td>";
                            output += "<td>" + data[x].fees_amount + "</td>";
                            if(data[x].fees_vatable == 1){
                                output += "<td> Yes </td>";
                            } else{
                                output +="<td> No </td>";
                            }
                            output += "<td>" + data[x].fees_percent + "</td>";
                            output += "<td> <button class='editButton' onclick='editCode(this)'";
                            output += "code='" + data[x].fees_code + "' desc='" + data[x].fees_desc + "' ";
                            output += "amount='" + data[x].fees_amount + "' vatable='" + data[x].fees_vatable + "' ";
                            output += "percent='" + data[x].fees_percent + "' ";
                            output += "id='" + data[x].fees_id + "' page='1'> Edit </button> </td>";
                            output += "<td> <button class='deleteButton' onclick='deleteCode(this)' id='" + data[x].fees_id + "'> Delete </button> </td> </tr>";
                        }
                        document.querySelector('#paginateBtns').style.display = "none";
                        document.querySelector('#accntable').innerHTML = output;

                    } else {
                        var output = "<tr style='background-color: #5B9BD5; color: white;'> <td> Codes </td> <td> Description </td> <td> Amount </td>";
                        output += "<td> Vatable </td> <td> % </td> <td colspan=2> Action </td> </tr>";
                        output += "<tr> <td colspan='7'> No Search Results </td> </tr>";
                        document.querySelector('#accntable').innerHTML = output;
                    } 
                }
            }
        } else{
            location.reload();
        }
    }

    function addCode(){
        var xhr = new XMLHttpRequest();
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var routefees4 = "{{route('store.fees')}}";
        xhr.open("POST", routefees4);
		xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
		xhr.setRequestHeader("X-CSRF-TOKEN", token);

        var feeCode = document.querySelector('#feeCode').value;
        var feeDesc = document.querySelector('#feeDesc').value;
        var feeAmount = document.querySelector('#feeAmount').value;
        var feeVatable = document.querySelector('#feeVatable').value;
        var feePercent = document.querySelector('#feePercent').value;

        const toSend = {
            f_code: feeCode,
            f_description: feeDesc,
            f_amount: feeAmount,
            f_vatable: feeVatable,
            f_percent: feePercent
        }

        const toSendJSONed = JSON.stringify(toSend);
		xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(this.status == 201){
                alert('Sucessfully added code!');
                document.querySelector('#addAccntCode').style.display = "none";
                location.reload();
                document.querySelector('#feeCode').value = "";
                document.querySelector('#feeDesc').value = "";
                document.querySelector('#feeAmount').value = "";
                document.querySelector('#feeVatable').value = "";
                document.querySelector('#feePercent').value = "";
            }
        }
    }

    function editCode(e){
        var feeId = e.id;
        var feeCode = e.getAttribute('code');
        var feeDesc = e.getAttribute('desc');
        var feeAmount = e.getAttribute('amount');
        var feeVatable = e.getAttribute('vatable');
        var feePercent = e.getAttribute('percent');
        var page = e.getAttribute('page');

        document.querySelector('#editfeeCode').value = feeCode;
        document.querySelector('#editfeeDesc').value = feeDesc;
        document.querySelector('#editfeeAmount').value = feeAmount;
        document.querySelector('#editfeePercent').value = feePercent;
        
        if(feeVatable == 1) {
            var select = '<select id="editfeeVatable" style="height: 40px; width: 97%;">';
            select += '<option value="1" selected> Yes </option>';
            select += '<option value="0"> No </option> </select>';
            document.querySelector('#vatable').innerHTML = select;
        } else {
            var select = '<select id="editfeeVatable" style="height: 40px; width: 97%;">';
            select += '<option value="1"> Yes </option>';
            select += '<option value="0" selected> No </option> </select>';
            document.querySelector('#vatable').innerHTML = select;
        }

        var editBtn = document.querySelector('#editBtn');
        editBtn.innerHTML = '<button class="modalAddBtn" onclick="updateCode(this)" id="' + feeId + '" page="' + page + '"> Save </button>';

        document.querySelector('#editAccntCode').style.display = "block";
    }

    function updateCode(i){
        var page = i.getAttribute('page');
        const toSend = {
            f_code: document.querySelector('#editfeeCode').value,
            f_description: document.querySelector('#editfeeDesc').value,
            f_amount: document.querySelector('#editfeeAmount').value,
            f_vatable: document.querySelector('#editfeeVatable').value,
            f_percent: document.querySelector('#editfeePercent').value
        }
        
        const toSendJSONed = JSON.stringify(toSend);
		var token = document.querySelector('meta[name="csrf-token"]').content;

		const xhr = new XMLHttpRequest();
        var routefees5 = "{{route('update.fees',['id'=> ':id'])}}";
        var routefeesU = routefees5.replace(':id', i.id );
		xhr.open("PATCH", routefeesU);
		xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
		xhr.setRequestHeader('X-CSRF-TOKEN', token);
		xhr.send(toSendJSONed);
        
        xhr.onload = function(){
            if(this.status == 200){
                alert('Successfully Updated!');
                location.reload();
                document.querySelector("#editAccntCode").style.display = "none";
            }
        }
    }

    function deleteCode(x){
        var xhr = new XMLHttpRequest();
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var routefees6 = "{{route('delete.fees',['request' => ':id'])}}";
        var routefeesD = routefees6.replace(':id',x.id);
        xhr.open('DELETE', routefeesD);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
		xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send();

        xhr.onload = function(){
            if(this.status == 202){
                alert('Successfully deleted!');
                location.reload();
            }
        }
    }
</script>
@endsection
