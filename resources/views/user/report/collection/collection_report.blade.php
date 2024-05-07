@extends('layout.master')
@section('title', 'Collection Report')
@section('content')

<style>
    select {
        color: black;
        cursor: pointer;
    }
    table tr {
        height: 80px;
    }
    #printButton {
        color: royalblue;
        background-color: white;
        float: right;
        height: 40px;
        margin-right: 2.5%;
        margin-top: -7%;
    }
    input {
        cursor: pointer;
    }
    #routeSelect {
        display: none;
    }
    ::-webkit-input-placeholder {
       text-align: left;
    }
    #routeDesc {
        width: 98%;
    }
</style>

<p class="contentheader">Collection Report</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td width="15%"> Bill Period </td>
            <td> 
                <input type="month" id="month" placeholder="Bill Period">
            </td>
        </tr>
        <tr>
            <td> Collection from </td>
            <td> 
                <input type="date" id="from" disabled>
            </td>
        </tr>
        <tr>
            <td> Collection to </td>
            <td> 
                <input type="date" id="to" disabled>
            </td>
        </tr>
        <tr>
            <td> Print by </td>
            <td> 
                <select id="select" disabled>
                    <option value="constype"> Consumer Type </option>
                    <option value="area"> Area </option>
                    <option value="town"> Town </option>
                    <option value="route"> Route </option>
                </select>
            </td>
        </tr>
    </table>
    <div id='inner' style="margin-top: -2%;">
        <table class='content-table'> 
            <tr> 
                <td width='15%'> Select </td>
                <td> 
                    <select id='location'> 
                        <option value="area"> Area </option>
                        <option value="town"> Town </option>
                        <option  value="route"> Route </option>
                    </select> 
                </td> 
            </tr>
        </table>
    </div>
    <table class="content-table">
        <tr>
            <td colspan=2> <button id="printButton" onclick="print()"> Print </button></td>
        </tr>
    </table>
</div>

@include('include.modal.routemodal')

<script>
    var month = document.querySelector('#month');
    var from = document.querySelector('#from');
    var to = document.querySelector('#to');
    var select = document.querySelector('#select');

    month.addEventListener('change', function(){
        if(month.value !== ""){
            from.disabled = false;
        } else {
            from.disabled = true;
        }
    })

    from.addEventListener('change', function(){
        if(from.value !== ""){
            to.disabled = false;
        } else {
            to.disabled = true;
        }
    })

    to.addEventListener('change', function(){
        if(to.value !== ""){
            document.querySelector('#printButton').disabled = false;
            document.querySelector('#select').disabled = false;
        } else {
            document.querySelector('#printButton').disabled = true;
            document.querySelector('#select').disabled = true;
        }
    })

    select.addEventListener('change', function(){
        var inner = "";
        if(select.value == "constype") {
            inner += "<table class='content-table'> <tr> <td width='15%'> Select </td>";
            inner += "<td> <select id='location'> <option value='area'> Area </option>";
            inner += "<option value='town'> Town </option>";
            inner += "<option value='route'> Route </option>";
            inner += "</select> </td> </tr>";
            document.querySelector('#inner').innerHTML = inner;
            document.querySelector('#printButton').style.marginTop = "-6%";
        } else if(select.value == "route"){
            inner += "<table class='content-table'> <tr> <td width='15%'> Select Route </td>";
            inner += "<td> <input type='text' id='routeDesc' onclick='showRoutes()' placeholder='Select Route' readonly> </td>";
            inner += "<td> <input type='text' id='routeId' hidden> </td> </tr>";
            document.querySelector('#inner').innerHTML = inner;
            document.querySelector('#printButton').style.marginTop = "-6%";
        } else {
            document.querySelector('#inner').innerHTML = "";
            document.querySelector('#printButton').style.marginTop = "2%";
        }
    })

    function setRoute(a){
        var routeId = a.id;
        var routeName = a.getAttribute('name');
        var routeCode = a.getAttribute('code');

        document.querySelector('#routeId').value = routeId;
        document.querySelector('#routeDesc').value = routeCode + ' - ' + routeName;

        document.querySelector('#routeCodes').style.display = "none";
    }

    function print(){
        var month = document.querySelector('#month').value;
        var from = document.querySelector('#from').value;
        var to = document.querySelector('#to').value;
        var select = document.querySelector('#select').value;
        var route = document.querySelector('#routeId');
        var location = "";
        
        
        if(route !== null){
            location = route.value;
            var routeDesc = document.querySelector('#routeDesc').value;
        } else {
            if((select == "area") || (select == "town")) {
                location = "";
            } else {
                location = document.querySelector('#location').value;
            }
        }   
        
        const toSend = {
            'bill_period': month,
            'date_from': from,
            'date_to': to,
            'select': select,
            'location': location,
            'route': routeDesc
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_collection_report")}}';
        window.open($url);
        // window.reload();
    }
</script>
@endsection
