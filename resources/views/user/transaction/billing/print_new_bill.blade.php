@extends('layout.master')
@section('title', 'Print New Bills')
@section('content')
<p class="contentheader">Print New Bills</p>
<div class="main">
    <br><br>
    <table border="0" style ="margin-left:70px;width:80%;height:350px;" class="content-table">
        <tr>
        <td class="thead">
                Route Code:
            </td>
            <td  class="input-td">
                <input id="routeID" style="color:black;" type="text" onclick="showRoutes()" name="route" placeholder="Select Route" readonly>
            </td>
            <td class="thead">
             Billing Period:
            </td>
            <td class="input-td">
                <input id="nobtninput"  style="color:black;" class = "dateP" type="month">
            </td>
        </tr>
        <tr>
            <td class="thead">
               Due Date:
            </td>
            <td colspan="1">
                <input id="nobtninput"  style="color:black;" type="text" class="dueDate">
            </td>
            <td class="thead" style="font-size:12px;">
                days from Current Date:
            </td>
        </tr>
        <tr>
            <td class="thead">
               Disco. Date:
            </td>
            <td  style="color:black;" class = "input-td">
                <input id="nobtninput" type="text" onfocusout="sendPowerBData()" class ="discoDate">
            </td>
            <td class="thead" style="font-size:12px;">
                days from Due Date:
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <button style ="float:right;height:40px;" class="downloading btn btn-light" onclick="sendDataPowerBill()">Print</button>
            </td>
        </tr>
    </table>
</div>
@include('include.modal.routemodal')
<script>
    var powerBillToSend = new Object();
/*------------------------------------------------END MODAL ROUTE ---------------------------------------------------------*/
/* -----------------------------------------------paginate click-----------------------------------------------------------*/
/* ----------------------------------------------- END Paginate-----------------------------------------------------*/
/* -----------------------------------------------tdclick-----------------------------------------------------------*/
    function setRoute(rID){
	    document.querySelector('#routeCodes').style.display = "none";
        document.querySelector('#routeID').value = rID.getAttribute('name');
        powerBillToSend.Route_Id = rID.id;     
    }
/*---------------------------------------END PAGINATE------------------------------------------------- */
/* ----------------------------------------------------------------------------------- */
function sendPowerBData(){
    var dueDate = document.querySelector('.dueDate').value;
    var discoDate = document.querySelector('.discoDate').value;
    var dateP = document.querySelector('.dateP').value;
    // powerBillToSend.Due_Date = dueDate;    
    // powerBillToSend.Disc_Date = discoDate;    
    // powerBillToSend.Date = dateP;      
}

function sendDataPowerBill(){
    Swal.fire({
            title: 'Do you want to continue?',
            showDenyButton: true,
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                var dueDate = document.querySelector('.dueDate').value;
                var discoDate = document.querySelector('.discoDate').value;
                var dateP = document.querySelector('.dateP').value;
                powerBillToSend.Due_Date = dueDate;    
                powerBillToSend.Disc_Date = discoDate;    
                powerBillToSend.Date = dateP;  
                if(typeof localStorage.getItem('reprint') != 'undefined'){
                    localStorage.removeItem('reprint');
                }
                var xhr = new XMLHttpRequest();
                var printPB = "{{route('print.powerbill')}}";
                xhr.open('POST',printPB,true);
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Content-Type", "application/json");
                xhr.onload = function(){
                    if(this.status == 200){
                        var data = JSON.parse(this.responseText);
                        localStorage.setItem("data3",JSON.stringify(data));
                        var url = "{{route('powerbill')}}";
                            window.open(url);
                    }
                    else if(this.status == 422){
                        Swal.fire({
                            title: 'Error!',
                            text: '"No Record Found"',
                            icon: 'error',
                            confirmButtonText: 'close'
                        });
                    }
                }
                xhr.send(JSON.stringify(powerBillToSend));
            }
        });
}
</script>
@endsection
