@extends('layout.master')
@section('title', 'Change Acknowledgement Receipt')
@section('content')

<style>
</style>

<p class="contentheader"> Change Acknowledgement Receipt </p>
<div class="main">
    <br>
    <table class="content-table">
        <tr>
            <td width="25%">
                Acknowledgement Receipt
            </td>
            <td>
                <input type="text" id="AR" onchange="setARDetails()" placeholder="Original Acknowledgement Receipt" style="cursor: pointer;">
            </td>
        </tr>
        <tr> <td height="50px;"> &nbsp; </td> </tr>
        <tr>
            <td>
                Acknowledgement Receipt Date
            </td>
            <td>
                <input type="text" id="ARDate" readonly placeholder="Date of Acknowledgement">
            </td>
        </tr>
        <tr> <td height="50px;"> &nbsp; </td> </tr>
        <tr>
            <td>
                Total Collection
            </td>
            <td>
                <input type="text" id="totalCollection" placeholder="Total Collection" readonly>
            </td>
        </tr>
        <tr> <td height="50px;"> &nbsp; </td> </tr>
        <tr>
            <td>
                New Acknowledgement Receipt
            </td>
            <td>
                <input type="text" id="newAR" onkeyup="enablePrintBtn()" placeholder="New Acknowledgement Receipt" style="cursor: pointer;" disabled>
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <button id="printBtn" onclick="changeAR()" style="margin-top: 3.5%; margin-right: 2.3%;" disabled> Change </button>
            </td>
        </tr>
    </table>
</div>

<script>
    var auth = "{{Auth::user()->user_id}}";
    function setARDetails(){
        var xhr = new XMLHttpRequest();
        var AR = document.querySelector("#AR").value;
        
        if(AR !== ""){
            var setTotalCollectionAR = "{{route('set.collection.total.ar')}}";
            xhr.open("POST", setTotalCollectionAR, true);
            xhr.setRequestHeader("Accept", "application/json");
            xhr.setRequestHeader("Content-Type", "application/json");

            const toSend = {
                "orig_ackn_receipt": AR
            }

            const toSendJSONed = JSON.stringify(toSend);
            xhr.send(toSendJSONed);

            xhr.onload = function(){
                if(this.status == 200){
                    var data = JSON.parse(this.responseText);
                    var totalColl = data.Total_Collection;
                    
                    if( totalColl != 0){
                        document.querySelector("#ARDate").value = data.Date_posted;
                        document.querySelector("#totalCollection").value = totalColl.toLocaleString("en-US", { minimumFractionDigits: 2 });
                        document.querySelector("#newAR").disabled = false;
                        document.querySelector("#newAR").focus();
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            icon: 'error',
                            text: "Acknowledgement Receipt doesn't exist",
                            type: 'error'
                        })    
                        document.querySelector("#ARDate").value = "";
                        document.querySelector("#totalCollection").value = "";
                        document.querySelector("#newAR").value = "";
                        document.querySelector("#newAR").disabled = true;
                    }
                }
            }
        }
    }

    function enablePrintBtn(){
        var newAR = document.querySelector("#newAR").value;
        if(newAR !== ""){
            document.querySelector("#printBtn").disabled = false;
        } else {
            document.querySelector("#printBtn").disabled = true;
        }
    }

    function changeAR(){
        var xhr = new XMLHttpRequest();
        var oldAR = document.querySelector("#AR").value;
        var newAR = document.querySelector("#newAR").value;

        var changeAR = "{{route('update.ar')}}";
        xhr.open("POST", changeAR, true);
        xhr.setRequestHeader("Accept", "application/json");
        xhr.setRequestHeader("Content-Type", "application/json");

        const toSend = {
            "orig_ackn_receipt": oldAR,
            "new_ackn_receipt": newAR,
            "user_id": auth
        }

        const toSendJSONed = JSON.stringify(toSend);
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){
                Swal.fire({
                    title: 'Success!',
                    icon: 'success',
                    text: 'Successfully changed Acknowledge Receipt',
                    type: 'success'
                }).then(function(){ 
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    icon: 'error',
                    text: 'New Acknowledgement Receipt already exists!'
                }).then(function(){ 
                    location.reload();
                });
            }
        }
    }
</script>
@endsection
