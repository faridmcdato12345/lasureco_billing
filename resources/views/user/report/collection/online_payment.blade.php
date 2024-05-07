@extends('layout.master')
@section('title', 'Online Payment Report')
@section('content')

<p class="contentheader">Online Payment Report</p>
<div class="main">
<table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
               Date From:
            </td>
            <td class="input-td">
            <input style ="cursor:pointer;"  id="dfrom" class="form-control" type="date">
            </td>
           
            <td  class="thead">
               Date To:
            </td>
            <td class="input-td">
            <input style ="cursor:pointer;" id="dto"  class="form-control" type="date">
            </td>  
        </tr>
        <tr>
            <td colspan="4">
                <button onclick="sendDate()" style="float:right;width:70px;margin-top:30px;height:40px;" class="btn btn-primary" >Print</button>
            </td>
        </tr>
    </table>
</div>
<script>
var tosend = new Object();
function sendDate(){
var xhr = new XMLHttpRequest();
var fdate = document.querySelector('#dfrom').value;
var tdate = document.querySelector('#dto').value;

tosend.date_from = fdate;
tosend.date_to = tdate;
var route = "{{route('print.collection.gcash.dcr')}}";

    xhr.open('POST', route, true);
    xhr.setRequestHeader("Accept", "application/json");
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.send(JSON.stringify(tosend));
    xhr.onload = function() {
        if(this.status == 200){
            var data = JSON.parse(this.responseText);
            $url = '{{route("onlinepayment")}}';
            localStorage.setItem('data', JSON.stringify(data));
            window.open($url);
        }
        else if(this.status == 422){
            var dats = JSON.parse(this.responseText);
            console.log(dats);
            Swal.fire({
                title: 'Error!',
                text: '"' + dats.Message + '"',
                icon: 'error',
                confirmButtonText: 'close'
            })  
        }
    }
}
</script>
@endsection