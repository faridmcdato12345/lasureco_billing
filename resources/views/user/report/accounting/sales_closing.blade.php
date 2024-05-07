@extends('layout.master')
@section('title', 'Sales Closing')
@section('content')

<style>
    #proceedBtn {
        float: right;
        color: royalblue;
        background-color: white;
        height: 40px;
        margin-top: 5%;
        margin-right: 2.7%;
    }
    input {
        cursor: pointer;
    }
</style>

<p class="contentheader">Closing of Sales</p>
<div class="main">
    <br><br>
    <table class="content-table">
        <tr>
            <td style="width: 15%;"> 
                Closing Period:
            </td>
            <td> 
                <input type="month" id="month">
            </td>
        </tr>
        <tr> 
            <td colspan=2> 
                <button id='proceedBtn' onclick='closeSales()' disabled> 
                    Proceed
                </button>
            </td>
        </tr>
    </table>
</div>
<script>
    var month = document.querySelector("#month");
    month.addEventListener("change", function(){
        if(month.value !== ""){
            document.querySelector("#proceedBtn").disabled = false;
        } else {
            document.querySelector("#proceedBtn").disabled = true;
        }
    })

    function closeSales(){
        var month = document.querySelector("#month").value;

        var toSend = new Object();
        var xhr = new XMLHttpRequest();

        toSend.date_period = month;
        
        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var closeSales = "{{route('sales.closing')}}";
        xhr.open('POST', closeSales, true);
        xhr.setRequestHeader("Accept", "application/json");
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);

        xhr.onload = function(){
            if(xhr.status == 200){ 
                Swal.fire({
                    title: 'Success!',
                    icon: 'success',
                    text: 'Sales successfully close',
                    type: 'success'
                }).then(function(){ 
                    location.reload();
                });
            } else {
                var data = JSON.parse(this.responseText);
                var message = data.info;
                Swal.fire({
                    title: 'Error!',
                    icon: 'error',
                    text: message
                })
            }
        }
    }
</script>
@endsection
