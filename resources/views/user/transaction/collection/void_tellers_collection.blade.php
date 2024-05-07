@extends('layout.master')
@section('title', 'Void Tellers Collection')
@section('content')

<p class="contentheader">Void Tellers Collection</p>
<div class="main">
    <table style ="height:600px;" border="0" class="content-table">
        <tr>
            <td class="thead">
                Teller:
            </td>
            <td class ="input-td">
            <input style="width:35%;cursor:pointer;" id = "teller" type="text" onclick="openTeller();"  placeholder="Select Tellers" readonly>
            </td>
        </tr>
        <tr>
        <td class="thead">
         Collection Date:
        </td>
        <td class="input-td">
                <input style ="width:35%;" onfocusout="dateVal(this.value)" type="date">
                
        </td>
        </tr>
        <tr>
            <td class="thead">
            TOR No.:
            </td>
            <td class="input-td">
                <input style ="width:35%;" type="text" onfocusout="torNo(this.value)">
            </td>
        </tr>
        <tr>
        <td style="width:25%;" class="thead">
                Accounts Number:
            </td>
            <td>
                <input type="text" style="width:35%;color:black" id = "accNoID" onclick="showConsumerAcct()" name="account_number" placeholder="Select Account No." readonly>
            </td>
        </tr>
        <tr>
        <td style="width:25%;" class="thead">
                Total Collection:
            </td>
            <td class="input-td">
                <input style ="width:40%;" type="text" id="totalCollection" value="0.00" readonly>
            </td>
        </tr>
    </table>
</div>
<div id="teller" class="modal">
    <div class="modal-content" style="width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Tellers</h3>
            <button type="button" onclick="closeTeller();">X</button>
        </div>
        <div class="modal-body">
            <div class="modaldivTeller">

            </div>
        </div>
    </div>
</div>
<div id="voidRemarks" class="modal">
        <div class="modal-content" style="width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>VOID OR</h3>
        <button type="button" class="btn-close" onclick="voidRemarksClose();"></button>
        </div>
        <div class="modal-body">
            <div class="voidRemarkDiv">
            <div class="form-group">
                <label style = "color:black;font-weight:bold" for="textar">Remarks</label>
                <textarea onfocusout= "textAre()" class="form-control" id="textar" rows="3" placeholder="..."></textarea>
                <button id= "disableto" onclick = "voidedData()" type="button" class="btn-primary" style="height:30px;margin-top:1%;width:50px;float:right">Void</button>
            </div>
            </div>
        </div>
    </div>
</div>
@include('include.modal.consumerAcctModal')
<script>
    var tellid;
    var tellname;
     var voidData = new Object();
     function openTeller(){
        modalD = document.querySelectorAll(".modal");
        modalD[0].style.display="block";
     }
     function closeTeller(){
        modalD = document.querySelectorAll(".modal");
        modalD[0].style.display="none";
     }  

     function tdclickTeller(teller){
        modalD = document.querySelectorAll(".modal");
        modalD[0].style.display="none";
        document.querySelector('#teller').value = teller.getAttribute('data')
        tellid = teller.id;
    }

     var xhr = new XMLHttpRequest();
     var route = "{{route('show.tellers')}}";
        xhr.open('GET',route,true);
        xhr.onload = function(){
            if(this.status == 200){
            var data = JSON.parse(this.responseText);
            console.log(data);
            var output = " ";
            var data = data.Tellers;
            output += '<div class="filter"><input type="text" style="width:100%" id="tellerSearch1" onkeyup="tellersearch();" placeholder="Search for Description..">';
            output += '<div style="overflow:scroll;height:270px;">';
            output += '<table style="color:black;text-align:left;width:100%;height:20px;" border=1 class="modal-table" id="table1">';
            output += '<thead><tr>' +
                    '<th>Designation</th>' +
                    '<th>Fullname</th>' +
                    '</tr></thead><tbody>';
            for(i in data){
                output += '<tr><td id="'+data[i].user_id+'" data="'+data[i].user_full_name+'" style="cursor:pointer" onclick="tdclickTeller(this)">' + data[i].emp_no + '</td>' +
                        '<td id="'+data[i].user_id+'" data="'+data[i].user_full_name+'" style="cursor:pointer" onclick="tdclickTeller(this)">' + data[i].user_full_name + '</td>'+
                        '</tr>';
            }
            output += '<tbody></table></div>';
            document.querySelector('.modaldivTeller').innerHTML=output;
        }
    }
    xhr.send();

    var d = " ";

var change = document.querySelector('#change')//.value = '0.00';
var cash=document.querySelector('#cash')//.value='0.00';
var discoID=document.querySelector('#discoID')//.value = d;
var dueID =document.querySelector('#dueID')//.value = d;
var aP =document.querySelector('#aP')//.value = d;
if(change,cash,discoID,dueID,aP){
    change.value='0.00';
    cash.value ='0.00';
    discoID.value=d;
    dueID.value=d;
    aP.value = d;
}
var xhr = new XMLHttpRequest();
        xhr.open('GET','http://10.12.10.100:8082/api/v1/consumer/accounts',true);
        xhr.onload = function(){
            if(this.status == 200){
            var data = JSON.parse(this.responseText);
                var output = " ";
                var output1 = " ";
                var val = data.data;
                var val3 = data.last_page;
                var val2 = data.current_page;
                output += '<table style="text-align:left;width:100%;" border=1 class="modal-table" id="table1">';
                output +='<tr>' + '<th>' + 'Account #' + '</th>'+ '<th>' + 'Consumer' + '</th></tr>';
                for(var i in val){
                var acc = val[i].cm_id;
                output += '<tr>'+ '<td id = ' + val[i].cm_id +  ' onclick="tdclick(this)" >' + val[i].cm_account_no + '</td>';
                output += '<td>' +val[i].cm_full_name + '</tr>'; }
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

            document.querySelector('.modaldiv2').innerHTML = output;
            document.querySelector('.pages2').innerHTML = output1;
        }
    }
    xhr.send();
/* ------------------------- End Consumers Account  ------------------ */

/* -----------------------Pagination for account numbers-------------------------------------------- */
     function s(p){
        var xhr = new XMLHttpRequest();
        xhr.open('GET','http://10.12.10.100:8082/api/v1/consumer/accounts?page='+p,true);
        xhr.onload = function(){
            if(this.status == 200){
            var data = JSON.parse(this.responseText);
            var output = " ";
                var output1 = " ";
                var val = data.data;
                var val3 = data.last_page;
                var val2 = data.current_page;
                output += '<table style="text-align:left;width:100%;" border=1 class="modal-table" id="table1">';
                output +='<tr>' + '<th>' + 'Account #' + '</th>'+ '<th>' + 'Consumer' + '</th></tr>';
                for(var i in val){
                var acc = val[i].cm_id;
                output += '<tr>'+ '<td id = ' + val[i].cm_id +  ' onclick="tdclick(this)" >' + val[i].cm_account_no + '</td>';
                output += '<td>' +val[i].cm_full_name + '</tr>'; }
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

            document.querySelector('.modaldiv2').innerHTML = output;
            document.querySelector('.pages2').innerHTML = output1;
        }
    }
    xhr.send();
}
/* ----------------- ENd Pagination of Account No. --------------------*/

/* ----------------- Search Account Name / Account Number ------------------- */
function tearch() {
    var d = " ";
    var change = document.querySelector('#change')//.value = '0.00';
    var cash=document.querySelector('#cash')//.value='0.00';
    var discoID=document.querySelector('#discoID')//.value = d;
    var dueID =document.querySelector('#dueID')//.value = d;
    var aP =document.querySelector('#aP')//.value = d;
    if(change,cash,discoID,dueID,aP){
        change.value='0.00';
        cash.value ='0.00';
        discoID.value=d;
        dueID.value=d;
        aP.value = d;
    }
    var input, filter;
    input = document.getElementById("dearch");
    filter = input.value.toUpperCase();
    if(filter.length == 0){
        var xhr = new XMLHttpRequest();
        xhr.open('GET','http://10.12.10.100:8082/api/v1/consumer/accounts',true);
        xhr.onload = function(){
        if(this.status == 200){
            var data = JSON.parse(this.responseText);
                var output = " ";
                var output1 = " ";
                var val = data.data;
                var val3 = data.last_page;
                var val2 = data.current_page;
                output += '<table style="text-align:left;width:100%;" border=1 class="modal-table" id="table1">';
                output +='<tr>' + '<th>' + 'Account #' + '</th>'+ '<th>' + 'Consumer' + '</th></tr>';
                for(var i in val){
                var acc = val[i].cm_id;
                output += '<tr>'+ '<td id = ' + val[i].cm_id +  ' onclick="tdclick(this)" >' + val[i].cm_account_no + '</td>';
                output += '<td>' +val[i].cm_full_name + '</tr>'; }

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

            }
            document.querySelector('.modaldiv2').innerHTML = output;
            document.querySelector('.pages2').innerHTML = output1;
        }
        xhr.send();
    }
    else{
        var d = " ";

        var change = document.querySelector('#change')//.value = '0.00';
        var cash=document.querySelector('#cash')//.value='0.00';
        var discoID=document.querySelector('#discoID')//.value = d;
        var dueID =document.querySelector('#dueID')//.value = d;
        var aP =document.querySelector('#aP')//.value = d;
        if(change,cash,discoID,dueID,aP){
            change.value='0.00';
            cash.value ='0.00';
            discoID.value=d;
            dueID.value=d;
            aP.value = d;
        }
        var xhr = new XMLHttpRequest();
        xhr.open('GET','http://10.12.10.100:8082/api/v1/consumer/searchByName/'+filter,true);
        xhr.onload = function(){
        if(this.status == 200){
                var data = JSON.parse(this.responseText);
                    var output = " ";
                    var val = data;
                    output += '<div style="overflow:scroll;height:270px;">';
                    output += '<table style="text-align:left;width:100%;height:20px;" border=1 class="modal-table" id="table1">';
                    output +='<tr>' + '<th>' + 'Account #' + '</th>'+ '<th>' + 'Consumer' + '</th></tr>';
                    for(var i in val){
                    var acc = val[i].cm_id;
                    output += '<tr>'+ '<td id = ' + val[i].cm_id +  ' onclick="tdclick(this)" >' + val[i].cm_account_no + '</td>';
                    output += '<td>' +val[i].cm_full_name + '</tr>'; }
                    output += '</table></div>';
                    }
        document.querySelector('.modaldiv2').innerHTML = output;

            }
                xhr.send();
    }
}
/* -----End of Search Account Name and Account Number --------------*/


function dateVal(val){
    voidData.s_bill_date=val;
}
function torNo(torNo){
    voidData.s_or = torNo;
}
function tdclick(cm){
    var modalAccNo = document.querySelector('#accNo');
    modalAccNo.style.display="none";
    document.querySelector('#accNoID').value = cm.innerHTML;
    voidData.cm_id = cm.id;
}
function setConsAcct(cons){
    document.querySelector('#consAcct').style.display = "none";
    console.log(document.querySelector('#consAcct'))
    var accName3 = cons.childNodes[0].innerHTML;
    document.querySelector('#accNoID').value = accName3;
    var acc = accName3.replaceAll('-','');
    voidData.cm_id = cons.id;
    var xhr = new XMLHttpRequest();
    var route1 = "{{route('show.void.amount')}}";
        xhr.open('POST',route1,true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onload = function(){
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                document.querySelector('#totalCollection').value = data.Total_Collection;
                var modalD=document.querySelectorAll('.modal');
                console.log(modalD);
                console.log(voidData);
                modalD[1].style.display="block";
            }
            else if(this.status == 422){
                Swal.fire({
                title: 'error!',
                text: '"No data to be Shown"',
                icon: 'error',
                confirmButtonText: 'close'
            })
            }
        }
        xhr.send(JSON.stringify(voidData));
}
function voidRemarksClose() {
    var modalD=document.querySelectorAll('.modal');
    console.log(modalD);
    modalD[1].style.display="none";
}
// function voidTheData(){
//     if(typeof voidData.s_bill_date != 'undefined' && typeof voidData.s_or != 'undefined' && 
//     typeof voidData.cm_id != 'undefined'){
//         if(voidData.s_or !== ''){
//             var xhr = new XMLHttpRequest();
//             var voidOR = "{{route('void.collection.transaction.or',['or'=>':or','teller'=>':teller','remark'=> ':remark'])}}";
//             xhr.open('DELETE','http://10.12.10.100:8082/api/v1/sales/void/collection',true);
//             xhr.setRequestHeader("Accept", "application/json");
//             xhr.setRequestHeader("Content-Type", "application/json");
//             xhr.onload = function(){
//                 if(this.status == 200){
//                     var data = JSON.parse(this.responseText);
//                     document.querySelector('#totalCollection').value = data.Total_Collection;
//                 }
//             }
//             xhr.send(JSON.stringify(voidData));
//         }
//         else{
//             alert('TOR Number must not Empty');
//         }
//     }
//     else{
//         alert('complete the form');
//     }
// }
function textAre() {
    var textAreaData = document.querySelector('#textar').value;
    if(textAreaData != ''){
        document.querySelector('#disableto').disabled = false;
    }
    else{
        textAreaData.value = '...';
    }
    console.log(voidData);
}
function voidedData() {
    Swal.fire({
        title: 'Do you want to continue?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
    }).then((result) => {
        if (result.isConfirmed) {
            var textAreaData = document.querySelector('#textar').value;
            var xhr = new XMLHttpRequest();           
            var voidOR = "{{route('void.or')}}";
            xhr.open('DELETE', voidOR, true);
            xhr.setRequestHeader("Accept", "application/json");
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onload = function() {
                if (this.status == 200) {
                    Swal.fire({
                    title: 'Success!',
                    text: '"OR has been Voided"',
                    icon: 'success',
                    confirmButtonText: 'close'
                    })
                location.reload();
                    }
                else if(this.status == 422){
                    var data = JSON.parse(this.responseText);
                    Swal.fire({
                    title: 'Error!',
                    text: data.Message,
                    icon: 'error',
                    confirmButtonText: 'close'
                    })
                }
            }
            xhr.send(JSON.stringify(voidData));   
        }
    })
}
function tellersearch(){
    var input, filter, table, tr, td, i, txtValue,td2;
    input = document.getElementById("tellerSearch1");
    filter = input.value.toUpperCase();
    table = document.getElementById("table1");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
    // td = tr[i].getElementsByTagName("td")[1];
    // console.log(td);
    td = tr[i].getElementsByTagName("td")[1];
        if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    console.log(1);
                tr[i].style.display = "";
                } else {
                tr[i].style.display = "none";
                }
            }
        }
    }
</script>
@endsection
