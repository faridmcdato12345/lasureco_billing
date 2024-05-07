@extends('layout.master')
@section('title', 'Collection Report per Route')
@section('content')

<style>
    select {
        color: black;
        cursor: pointer;
    }
    #tabl tr {
        height: 90px;
    }
    /* #tabl {
        border-right: 1px solid #ddd;
    } */
    #printButton {
        color: royalblue;
        background-color: white;
        float: right;
        height: 40px;
        margin-right: 7%;
        margin-top: 3%;
    }
    input {
        cursor: pointer;
        width: 92%;
    }
    #routeSelect {
        display: none;
    }
    ::-webkit-input-placeholder {
       text-align: left;
    }
</style>

<p class="contentheader">Collection Report per Route</p>
<div class="main">
    <table class="content-table" id='tabl' style="margin-top: 1%;">
        <tr>
            <td width='13%'> Collection from </td>
            <td> 
                <input type="date" id="from">
            </td>
        </tr>
        <tr>
            <td> Collection to </td>
            <td> 
                <input type="date" id="to" disabled>
            </td>
        </tr>
        <tr>
            <td>
                Route:
            </td>
            <td>
                <input type="text" id="routeDesc" onclick="showRoutes()" placeholder="Select Route" readonly disabled>
                <input type="text" id="routeId" hidden>
            </td>
        </tr> 
        <tr>
            <td colspan=2> <button id="printButton" onclick="print()" disabled> Print </button></td>
        </tr>
    </table>
</div>

@include('include.modal.routemodal')

<script>
    var from = document.querySelector('#from');
    
    from.addEventListener('change', function(){
        if(from.value !== ""){
            document.querySelector("#to").disabled = false;
        } else {
            document.querySelector("#to").disabled = true;
        }
    })

    var to = document.querySelector('#to');
    
    to.addEventListener('change', function(){
        if(to.value !== ""){
            document.querySelector("#routeDesc").disabled = false;
        } else {
            document.querySelector("#routeDesc").disabled = true;
        }
    })
    

    function setRoute(a){
        var routeId = a.id;
        var routeName = a.getAttribute('name');
        var routeCode = a.getAttribute('code');

        document.querySelector('#routeId').value = routeId;
        document.querySelector('#routeDesc').value = routeCode + ' - ' + routeName;
        document.querySelector('#routeCodes').style.display = "none";
        document.querySelector('#printButton').disabled = false;
    }

    function print(){
        var from = document.querySelector('#from').value;
        var to = document.querySelector('#to').value;
        var route = document.querySelector('#routeId').value;
        
        const toSend = {
            'from': from,
            'to': to,
            'route': route
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        console.log(toSend);

        $url = '{{route("print_collection_report_per_route")}}';
        window.open($url);
        window.reload();
    }
</script>
@endsection
