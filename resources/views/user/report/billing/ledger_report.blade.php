@extends('Layout.master')
@section('title', 'Ledger Report')
@section('content')
<style>
.blink {
animation: blink-animation 1s steps(5, start) infinite;
-webkit-animation: blink-animation 1s steps(5, start) infinite;
}
@keyframes blink-animation {
to {
    visibility: hidden;
}
}
@-webkit-keyframes blink-animation {
to {
    visibility: hidden;
}
}
body{
    background-color: white !important;
}
label{
    font: size 12px;
    font-family: calibri;
}
.contentA {
display: flex;
flex:1;
color: #000;
}
.contentB {
display: flex;
flex:1;
color: #000;
}
.contentC {
display: flex;
flex: 5;
color: white;
margin-left:100px;
}
.contentD {
  display: flex;
  flex: 1;
  color: #000;
}
.col2{
    display: flex;
  flex: 1;
  color: #000;
}
.col1{
    display: flex;
  flex: 1;
  color: #000;
}
input[type=number] {
    -moz-appearance:textfield;
    -webkit-appearance: none;
    appearance: textfield;
}
@media screen and (max-width: 500px){
    .contentA{
    	display:block;
    }
    table{
    	margin-bottom:10px;
    }
    .contentC{
        margin-left:0;
    }
}
@media screen and (max-width: 1366px){
    .modal-content{
        height:450px;
    }
    button{
        font-size:12px;
        font-family:calibri;
    }
}

</style>
<p class="contentheader">Ledger Report </p>
<div class="main" id="main">
<br><br>
    <table class="content-table">
        <tr>
        <td class="thead">
                Route Code:
            </td>
            <td  class="input-td">
                <input id="routeID" class= "form-control" style="color:black;" type="text" onclick="showRoutes()" name="route" placeholder="Select Route" readonly>
            </td>
        </tr>
        <tr>
            <td style="height: 100px;"> &nbsp; </td>
        </tr>
        <tr>
            <td>
                Account From:
            </td>
            <td>
                <input type="number" onfocusout="seqFrom()" class="form-control" id="seqFromVal">
            </td>
            <td>
                Account to:
            </td>
            <td>
                <input type="number" onfocusout="seqTo()" class="form-control" id="seqToVal">
            </td>
        </tr>
        <tr>
            <td style="height: 50px;"> &nbsp; </td>
        </tr>
        <tr>
            <td colspan=4>
                <button id="printBtn" onclick="printLedger()"> Print </button>
            </td>
        </tr>
    </table>
</div>
@include('include.modal.routemodal')
<script>
    var toSend = new Object();
     function setRoute(rID){
        console.log(rID)
        id = rID.id;
        toSend.route_id = parseInt(id);
        document.querySelector('#routeCodes').style.display = "none";
        console.log(rID.getAttribute('townname'));
        document.querySelector('#routeID').value=rID.getAttribute('name');
        // console.log(routeDesc);
        // routeDesc = ;
        // console.log(routeDesc);
        var mrsRoute = "{{route('get.consumer.count.meter.read',':id')}}";
        var mrsRoute = mrsRoute.replace(':id',rID.id);
        var xhr = new XMLHttpRequest();
		xhr.open('GET',mrsRoute,true);
        xhr.onload = function(){
            if(this.status == 200){
                console.log(1);
			}
            // document.querySelector('.pages').innerHTML = output2;
		}
        xhr.send();
    }
    function seqFrom(){
        var seqFromVal = document.querySelector('#seqFromVal').value;
        toSend.seq_from = parseInt(seqFromVal);
    }
    function seqTo(){
        var seqToVal = document.querySelector('#seqToVal').value;
        toSend.seq_to = parseInt(seqToVal);
    }
    function printLedger(){
            if(typeof toSend.seq_to != 'undefined' && typeof toSend.seq_from != 'undefined' && typeof toSend.route_id != 'undefined'){
                    if(parseInt(toSend.seq_to - toSend.seq_from)<=99){
                    var ledgerRoute = "{{route('ledger.per.route')}}";
                    console.log(ledgerRoute);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST',ledgerRoute,true);
                    xhr.setRequestHeader("Accept", "application/json");
                    xhr.setRequestHeader("Content-Type", "application/json");
                    xhr.onload = function(){
                        if(this.status == 200){
                            var url = "{{route('PLR')}}";
                            var data = JSON.parse(this.responseText);
                            localStorage.setItem('dataR',JSON.stringify(data))
                            window.open(url);
                        }
                        else if(this.status == 422){
                            alert('No Consumer Found');
                        }
                        // document.querySelector('.pages').innerHTML = output2;
                    }
                    // console.log(toSend)
                    xhr.send(JSON.stringify(toSend));
                }
                    else{
                        alert('Account Max of 100 only');
                    }
            } 
            else{
                alert('Fill up all form');
            }  
        }
</script>
@endsection
