@extends('layout.master')
@section('title', "List of Member-Consumer's Billed on Average/Override")
@section('content')

<style>
    input, select {
        color: black;
        cursor: pointer;
    }
    .printButton {
        float: right;
        color: royalblue;
        background-color: white;
        height: 40px !important;
        margin-top: 5%;
        margin-right: 2.5%;
    }
    .locInp {
        width: 97%;
    }
</style>

<p class="contentheader">List of Member-Consumer's Billed on Average/Override</p>
<div class="main">
    <br>
    <table class="content-table">
        <tr>
            <td width="15%">
                Group by:
            </td>
            <td>
                <select id="groupBy">
                    <option value="all"> All </option>
                    <option value="area"> Area </option>
                    <option value="town"> Town </option>
                    <option value="route"> Route </option>
                </select>
            </td>
        </tr>
        <tr><td height="40px"></td></tr>
        <tr id="locTR"></tr>
        <tr><td height="40px"></td></tr>
        <tr>
            <td>
                Bill Period:
            </td>
            <td>
                <input type="month" id="billPeriod">
            </td>
        </tr>
        <tr><td height="40px"></td></tr>
        <tr>
            <td colspan=2>
                <button class="printButton" disabled> Print </button>
            </td>
        </tr>
    </table>
</div>

@include('include.modal.areamodal')
@include('include.modal.townmodal')
@include('include.modal.routemodal')

<script>
    var toSend = new Object();

    $(document).ready(function(){ 
        var innerHTML = "";
        innerHTML += "<td> Area: </td>";
        innerHTML += "<td> <input type='text' id='areaInpt' class='locInp' placeholder='Select Area' readonly disabled> </input>";
        innerHTML += "<input type='text' id='areaId' hidden> </td>";

        $(document).on('change', '#groupBy', function(){
            var loc = $(this).val();

            if(loc == "area"){
                innerHTML = "<td> Area: </td> <td> <input type='text' id='areaInpt' class='locInp' placeholder='Select Area' readonly> </input> ";
                innerHTML += "<input type='text' id='areaId' hidden> </td>";
                $('#locTR').html(innerHTML);
                document.querySelector('#billPeriod').disabled = true;
            } else if(loc == "town"){
                innerHTML = "<td> Town: </td> <td> <input type='text' id='townInpt' class='locInp' placeholder='Select Town' readonly> </input>";
                innerHTML += "<input type='text' id='townid' hidden> </td>";
                $('#locTR').html(innerHTML);
                document.querySelector('#billPeriod').disabled = true;
            } else if(loc == "route") {
                innerHTML = "<td> Route: </td> <td> <input type='text' id='routeInpt' class='locInp' placeholder='Select Route' readonly> </input>";
                innerHTML += "<input type='text' id='routeid' hidden> </td>";
                $('#locTR').html(innerHTML);
                document.querySelector('#billPeriod').disabled = true;
            } else {
                var area = document.querySelector("#areaInpt");
                var town = document.querySelector("#townInpt");
                var route = document.querySelector("#routeInpt");
                document.querySelector('#billPeriod').disabled = false;

                if(area != null){
                    area.value = "";
                    area.disabled = true;
                } else if(town != null){
                    town.value = "";
                    town.disabled = true;
                } else if(route != null){
                    route.value = "";
                    route.disabled = true;
                }
            }
        });

        $('#locTR').html(innerHTML);
        let billPeriod = $('#billPeriod').val();
    });

    $(document).on('click', '.locInp', function(){  
        var loc = $(this).attr('id');

        if(loc == "areaInpt"){
            showArea();
            document.querySelector('.printButton').disabled = true;
            document.querySelector('#billPeriod').disabled = true;
            document.querySelector('#billPeriod').value = "";
        } else if(loc == "townInpt") {
            showTown();
            document.querySelector('.printButton').disabled = true;
            document.querySelector('#billPeriod').disabled = true;
            document.querySelector('#billPeriod').value = "";
        } else if(loc == "routeInpt") {
            showRoutes();
            document.querySelector('.printButton').disabled = true;
            document.querySelector('#billPeriod').disabled = true;
            document.querySelector('#billPeriod').value = "";
        }
    });

    function setArea(x){
        var areaName = x.getAttribute('areaName');
        var areaId = x.getAttribute('areaId');
        toSend.input = areaName;
        $('#areaInpt').val(areaName);
        $('#areaId').val(areaId);
        $('#areaCodes').css('display', 'none');
        document.querySelector('#billPeriod').disabled = false;
    }

    function selectTown(x) {
        var id = x.id;
        var desc = x.getAttribute('name');
        var code = x.getAttribute('code');
        toSend.input = code + " - " + desc;
        $('#townInpt').val(code + " - " + desc);
        $('#townid').val(id);
        $('#town').css('display', 'none');
        $("billPeriod").removeAttr('disabled');
        document.querySelector('#billPeriod').disabled = false;
    }

    function setRoute(x){
        var id = x.id;
        var desc = x.getAttribute('name');
        var code = x.getAttribute('code');
        toSend.input = code + " - " + desc;
        $('#routeInpt').val(code + " - " + desc);
        $('#routeid').val(id);
        $('#routeCodes').css('display', 'none'); 
        document.querySelector('#billPeriod').disabled = false;
    }    

    $(document).on('change', '#billPeriod', function(){
        var billperiod = $(this).val();

        document.querySelector('.printButton').disabled = (billperiod !== "") ? false : true;
    });    

    $(document).on('click', '.printButton', function(){
        var id = "";
        var location = $('#groupBy').val();
        var billPeriod = $('#billPeriod').val();

        if(location == "area"){
            id = $('#areaId').val()
        } else if(location == "town"){
            id = $('#townid').val();
        } else if(location == "route"){
            id = $('#routeid').val()
        }

        toSend.id = id;
        toSend.location = location;
        toSend.billperiod = billPeriod;

        localStorage.setItem('data', JSON.stringify(toSend));             
        $url = '{{route("print_list_of_member_consumer_per_kwh_used")}}'                                 
        window.open($url);  
    });
</script>
@endsection
