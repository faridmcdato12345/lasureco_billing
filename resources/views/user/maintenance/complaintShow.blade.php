@extends('layout.master')
@section('title', 'Complaint List')
@section('stylesheet')
@include('include.style.consumer')
@endsection
@section('content')
@include('include.modal.consumer')
<style>
    img{
        width:100%;
        height:350px;
    }
</style>
<p class="contentheader">Complaint List</p>
<div class="container consumer-container" style="background: #f9f9f9;overflow:scroll;padding-top:1%">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table" style="font-size:10px" id="cons11">
                        <thead>
                            <tr>
                                <th>Fullname</th>
                                <th>Account No</th>
                                <th>Complaint_No</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Date</th>
                                <th>Finding</th>
                                <th>Recommendation</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <!-- <button class="btn btn-primary " onclick="printConsumers()"> Print </button> -->
                    <button class="btn btn-success" onclick="exportData1()">CSV </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="listInfo" class="modal">
        <div class="modal-content" style="height:450px;width: 80%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Information</h3>
            <button type="button" class="btn-close" onclick="listInfoClose();"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-6">
                <table style="width:100%" class="table">
                    <tr>
                        <td>Fullname: </td>
                        <td id= "fullname"></td>
                    </tr>
                    <tr>
                        <td>Address: </td>
                        <td id= "address"></td>
                    </tr>
                    <tr>
                        <td>Account Number: </td>
                        <td id= "accountnumber"></td>
                    <tr>
                        <td>Meter Number:</td>
                        <td id="meternumber"></td>
                    </tr>
                    <tr>
                        <td>Type: </td>
                        <td id= "constype"></td>
                    </tr>
                    </tr>
                </table>
                </div>
                <div style="background-color:black" class="col-6">
                <a onclick="downl()" target="_blank" id="dooo" title="ImageName">
                    <img alt="ImageName" id="sketch">
                </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="tosend" class="modal">
        <div class="modal-content" style="height:450px;width: 80%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Export Complaint List</h3>
            <button type="button" class="btn-close" onclick="tosendClose();"></button>
        </div>
        <div class="modal-body">
                <form action="{{route('export.complaint')}}" method="post">
                    @csrf
                    <label style="color:black" for="frmDate">From Date:</label>
                    <input type="date" class= "form-control" name="from_date">
                    <label style="color:black" for="to_date">To Date:</label>
                    <input type="date" class= "form-control" name="to_date"><br>
                    <button class="btn btn-primary form-control"  type="submit">submit</button>
                </form>
        </div>
    </div>
</div>
<div id="toupdate" class="modal">
        <div class="modal-content" style="height:450px;width: 80%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Export Complaint List</h3>
            <button type="button" class="btn-close" onclick="toupdateClose();"></button>
        </div>
        <div class="modal-body">
                <form action="{{route('update.complaint')}}" method="post">
                    @csrf
                    <input type="text" id="cid" class= "form-control" name="cid" hidden><br>
                    <label style="color:black" for="findings">Findings:</label>
                    <input type="text" id="finding" class= "form-control" name="finding" placeholder="enter findings"><br>
                    <label style="color:black" for="recommendation">Recommendation:</label>
                    <input type="text" class= "form-control" id="recommendation" name="recommendation" placeholder="enter recommendation"><br>
                    <label style="color:black" for="status">status</label>
                    <select name="status" id="status" class="form-control">
                        <option id="Done" value="0">Done</option>
                        <option id="On Process" value="1">On Process</option>
                    </select><br>
                    <button class="btn btn-primary form-control"  type="submit">submit</button>
                </form>
        </div>
    </div>
</div>
@include('include.script.complaint_list')
<script>
    var downl1;
    var newdats;
    var idCon;
    function text(a,b){
        var data2 = a.id;
        console.log(data2);
        idCon = b;
        console.log(idCon)
        var xhr = new XMLHttpRequest();
        var url = "{{route('info.show',['id'=>':id'])}}";
        url = url.replace(':id',b);
        xhr.open('GET', url, true);
        xhr.onload = function() {
            if(this.status == 200){
                
                var data = JSON.parse(this.responseText);
                console.log(data);
                data = data.data;
                document.querySelector('#listInfo').style.display="block";
                document.querySelector('#fullname').innerHTML = data.cm_full_name;
                document.querySelector('#address').innerHTML = data.cm_address;
                document.querySelector('#accountnumber').innerHTML = data.cm_account_no;
                document.querySelector('#constype').innerHTML = data.ct_desc;
                document.querySelector('#meternumber').innerHTML = data.mm_master;

                var newData = data2.split('@');
                idCon = newData[0];
                var imageroute = "{{asset('/images/:dd')}}";
                imageroute = imageroute.replace(":dd", newData[4]);
                downl1 = imageroute;
                newdats = newData[4];
                console.log(downl1)
                document.querySelector('#sketch').src = imageroute;
            }
        }
        xhr.send();
      
    }
    function text2(a,b){
        var ss = a.id.split('@');
        document.querySelector('#cid').value = ss[0];
        document.querySelector('#finding').value = ss[7];
        document.querySelector('#recommendation').value = ss[8];
        console.log(ss[7]);
        document.querySelector('#toupdate').style.display = "block";
        if(ss[6] == 1){
            s = 'On Process';
        }
        else{
            s="Done";
        }
        var stats = document.getElementById(s).selected = true;
        console.log(ss[5]);
    }
    function toupdateClose(){
        document.querySelector('#toupdate').style.display = "none";
    }
    function listInfoClose(){
        document.querySelector('#listInfo').style.display="none";
    }
    function text3(x,y){
        var data2 = x.id;
        console.log(x)
        console.log(y)
        var xhr = new XMLHttpRequest();
        var url = "{{route('info.show',['id'=>':id'])}}";
        url = url.replace(':id',y);
        xhr.open('GET', url, true);
        xhr.onload = function() {
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                data = data.data;
                var newData = data2.split('@');
                var imageroute = "{{asset('/images/:dd')}}";
                imageroute = imageroute.replace(":dd", newData[4]);
                
                

                console.log(document.querySelector('#dooo'));
                document.querySelector('#sketch').src = imageroute;

                var url = "{{route('print.complaint')}}";
                window.open(url);

                localStorage.setItem('newData',newData);
                localStorage.setItem('data',JSON.stringify(data));
            }
        }
        xhr.send();
        
    }
    function downl(){
        console.log(document.querySelector('#dooo'));
        console.log(idCon)
        var route = "{{route('print.sketch',['id'=>':id'])}}";
        var url = route.replace(':id',idCon);
        console.log(url);
        document.querySelector('#dooo').href = url;
        // document.querySelector('#dooo').download= newdats;
    }
    function exportData1(){
        document.querySelector('#tosend').style.display="block";
        console.log("{{route('export.complaint')}}");
    }
    function tosendClose(){
        document.querySelector('#tosend').style.display="none";
    }
</script>
@endsection
