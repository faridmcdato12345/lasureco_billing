@extends('layout.master')
@section('title', 'Create New Complaint')
@section('content')
<style>
.page-break{
    page-break-after: always;
}
.display{
    display:none;
}
.container {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 1vw;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    color:black;
    font-weight:bold;
    margin-left:30px;
}
td, th{
    text-align: left;
}
.container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0vw;
    width: 0vw;
}

.checkmark {
    position: absolute;
    top: 0;
    left:0;
    height: 1.5vw;
    width: 1.5vw;
    background-color: #eee;
}

.container:hover input ~ .checkmark {
    background-color: #ccc;
}

.container input:checked ~ .checkmark {
    background-color: #2196F3;
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.container input:checked ~ .checkmark:after {
    display: block;
}

.container .checkmark:after {
    left: 9px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}
</style>

<p class="contentheader">New Complaint</p>
<div class="main">
<br><br>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <button class="btn btn-success btn-sm ml-3" onclick="addCategory()">add category</button>
    <button class="btn btn-secondary btn-sm ml-2" onclick = "subCategoryModal()">add sub category</button>
    <form method="POST" enctype="multipart/form-data" action="{{route('complaint.store')}}">
        @csrf
        
    <input type="text" value="{{Auth::user()->user_id}}" name= "tellerid" hidden>
    <input type="text" id="cmidd" name= "cmid" hidden>
    <input type="text" id="categoryid" name= "catid" hidden>
    <input type="text" id="subcategoryid" name= "scatid" hidden>
    <table class="content-table" style="height: 400px;">     
        <tr>
            <td class="thead">
                &nbsp; Account No:
            </td>
            <td>
                <input type="text" style="color:black" id = "accNoID" class="form-control" onclick="showConsumerAcct()" name="account_number" placeholder="Select Account No." readonly required>
            </td>
        </tr>
        <tr>
            <td class="thead">
                &nbsp; Category
            </td>
        <td>
            <input type="text" onclick = "categoryModal()" id="category" name="category" class="form-control" placeholder="select category" readonly>
        </td></tr>
        <tr>
            <td class="thead">
                &nbsp; Sub Category
            </td>
        <td>
            <input type="text" onclick = "subcategoryModal()" id="subCategory" name="subcategory" class="form-control" placeholder="select sub category" readonly>
        </td></tr>
        <tr>
            <td></td>
            <td>
                <input type="file" id="imageT" class="form-control" name="image">
            </td>
        </tr>
    </table>
    <button style="margin-left:90%" class="btn btn-primary" type="submit">CREATE</button>
    </form>
    <!-- </form> -->
</div>
<div id="complaint_categoryModal" class="modal">
        <div class="modal-content" style="height:500px;width: 80%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Category</h3>
            <button type="button" class="btn-close" onclick="complaint_categoryClose();"></button>
        </div>
        <div class="modal-body">
            <table class="table" style="width:100%" id="complaint_category">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="complaint_subcategoryModal" class="modal">
        <div class="modal-content" style="height:500px;width: 80%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Sub Category</h3>
            <button type="button" class="btn-close" onclick="complaint_subcategoryClose();"></button>
        </div>
        <div class="modal-body">
            <table class="table" style="width:100%" id="complaint_subcategory">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sub Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="addCategory" class="modal">
        <div class="modal-content" style="height:200px;width: 80%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Add Category</h3>
            <button type="button" class="btn-close" onclick="addCategoryClose();"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="{{route('category.store')}}">
                @csrf
                <input class = "form-control mb-3" type="text" name="category" placeholder="category">
                <input class="form-control btn btn-primary" type="submit" value="submit">
            </form>
        </div>
    </div>
</div>
<div id="addSubCategory" class="modal">
        <div class="modal-content" style="height:200px;width: 80%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Add Sub Category</h3>
            <button type="button" class="btn-close" onclick="addSubCategoryClose();"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="{{route('subcategory.store')}}">
                @csrf
                <input class = "form-control mb-3" type="text" name="subcategory" placeholder="category">
                <input class="form-control btn btn-primary" type="submit" value="submit">
            </form>
        </div>
    </div>
</div>
@include('include.modal.consumerAcctModal')
<script>
    console.log(document.querySelector('#imageT'))
    function categoryModal(){
        console.log(1);
        document.querySelector('#complaint_categoryModal').style.display="block";
        console.log(document.querySelector('#imageT').value)
    }

    function complaint_categoryClose(){
        document.querySelector('#complaint_categoryModal').style.display="none";
    }

    function addCategory(){
        document.querySelector('#addCategory').style.display="block";
    }

    function addCategoryClose(){
        document.querySelector('#addCategory').style.display="none";
    }

    function subCategoryModal(){
        document.querySelector('#addSubCategory').style.display="block";
    }

    function addSubCategoryClose(){
        document.querySelector('#addSubCategory').style.display="none";
    }

    function setConsAcct(consumer){
        accName2 = consumer;
        var cmid = consumer.getAttribute('id');
        console.log(cmid);
        var aa;
        var accName3 = consumer.childNodes[2].innerHTML;
        
        if(accName3 != ''){
            aa = accName3.split('<br>');
        }
        var modalAccNo = document.querySelector('#consAcct');
        modalAccNo.style.display = "none";
        document.querySelector('#accNoID').value = aa[0];
        document.querySelector('#cmidd').value = cmid;
    }

    $(document).ready(function(){           
            $('#complaint_category').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('complaint.category1')}}",
            "columns": [
                { "data": "complaint_id" },
                { "data": "category" },
                { "data": "action"},
            ],
            
            "pageLength" : 4,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ],
            dom: 'Bfrtip',
            buttons: ['copyHtml5','excelHtml5','csvHtml5','pdfHtml5']
            
        });

        $('#complaint_subcategory').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{route('subcategory.show')}}",
            "columns": [
                { "data": "sub_category_id" },
                { "data": "sub_category" },
                { "data": "action"},
            ],
            
            "pageLength" : 4,
            "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            "order": [ [0, 'desc'] ],
            dom: 'Bfrtip',
            buttons: ['copyHtml5','excelHtml5','csvHtml5','pdfHtml5']
            
        });
        
    })

    function printConsumers(){
        $url = '{{route("print_pending_consumers")}}'
        window.open($url);
    }
    function text2(a,b){
        document.querySelector('#complaint_categoryModal').style.display="none";
       console.log(a , '-----', b);

       document.querySelector('#category').value = a.id;
       document.querySelector('#categoryid').value = b;

       console.log(document.querySelector('#categoryid').value);
    }
    function subcategoryModal(){
        document.querySelector('#complaint_subcategoryModal').style.display="block";
    }
    function complaint_subcategoryClose(){
        document.querySelector('#complaint_subcategoryModal').style.display="none";
    }
    function text(a,b){
        document.querySelector('#complaint_subcategoryModal').style.display="none";
       console.log(a , '-----', b);

       document.querySelector('#subCategory').value = a.id;
       document.querySelector('#subcategoryid').value = b;

       console.log(document.querySelector('#subcategoryid').value);
    }
</script>
@endsection
