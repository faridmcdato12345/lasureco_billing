@extends('layout.master')
@section('title', 'Pole')
@section('content')

<style>
    .poleDiv {
        margin: auto;
        width: 97%;
        background-color: white;
    }
    .poleTable {
        width: 100%;
        color: black;
    }
    .poleTable td{
        height: 40px;
        border-bottom: 1px solid #ddd;
    }
    #thead{
        color: white;
        background-color: #5B9BD5;
    }
    #addBtn {
        margin-right: 25px; 
        height: 40px; 
        width: 60px;
    }
    #search {
        width: 250px;
        float: right;
    }
    .poleDetailsTable{
        width: 99%;
        margin: auto;
    }
    .poleDetailsTable td{
        height: 60px;
    }
</style>

<table style="width: 100%;">
    <tr>
        <td style="width: 80%;">
            <p class="contentheader">Pole Master</p>
        </td>
        <td style="width: 10%;">
            <input type="text" id="search" onkeyup="setTimeout(search(), 1000);" placeholder="Search Pole here...">
        </td>
        <td style="text-align: right; width: 10%;">
            <button class="modal-button" id="addBtn" href="#addAccntCode">
                Add
            </button>
        </td>
    </tr>
</table>
<div class="main">
    <div class="poleDiv">
        <table class="poleTable"></table>
    </div>
    <div style="margin: auto; width: 97%; margin-top: 20px;">
        <table class="poleDetailsTable">
            <tr>
                <td>
                    Description:
                </td>
                <td>
                    <input type="text" id="description" readonly>
                </td>
                <td>
                    &nbsp; Location:
                </td>
                <td>
                    <input type="text" id="location" readonly>
                </td>
            </tr>
            <tr>
                <td>
                    Wire Size:
                </td>
                <td>
                    <input type="text" id="wireSize" readonly>
                </td>
                <td>
                    &nbsp; Wire Type:
                </td>
                <td>
                    <input type="text" id="wireType" readonly>
                </td>
            </tr>
            <tr>
                <td>
                    Length:
                </td>
                <td>
                    <input type="text" id="length" readonly>
                </td>
            </tr>
        </table>
    </div>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn3');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container3').childNodes;
        c[5].style.color="blue";
    }

    var req = new XMLHttpRequest();
    req.open('GET', 'http://10.12.10.100:8082/api/v1/pole_master', true);
    req.send();

    req.onload = function(){
        if(this.status == 200){
            var response = JSON.parse(this.responseText);
            var poles = response.data;
            var output = "<tr id='thead'> <td> &nbsp; Pole ID </td> <td> Cor X </td> <td> Cor Y </td>";
            output += "<td> Type </td> <td> Status </td> <td> Config </td>";
            output += "<td> Phasing </td> <td> Height </td> <td> <Structure> </td> <td colspan=2 style='text-align: center;'> Action </td> </tr>";

            for(var b in poles){
                output += "<tr> <td class='pole'>&nbsp;&nbsp;" + poles[b].pole_master_id + "</td>";
                output += "<td>" + poles[b].pole_master_cor_x + "</td>";
                output += "<td>" + poles[b].pole_master_cor_y + "</td>";
                output += "<td>" + poles[b].pole_master_type + "</td>";
                output += "<td>" + poles[b].pole_master_status + "</td>";
                output += "<td>" + poles[b].pole_master_configuration + "</td>";
                output += "<td>" + poles[b].pole_master_phasing + "</td>";
                output += "<td>" + poles[b].pole_master_height + "</td>";
                output += "<td>" + poles[b].pole_master_structure + "</td>";
                output += "<td style='text-align: center;'> <button onclick='edit()'> Edit </button> </td>";
                output += "<td style='text-align: center;'> <button> Delete </button> </td> </tr>";
            }
            document.querySelector('#description').value = poles[0].pole_master_description;
            document.querySelector('#location').value = poles[0].pole_master_location;
            document.querySelector('#wireSize').value = poles[0].pole_master_wire_size;
            document.querySelector('#wireType').value = poles[0].pole_master_wire_type;
            document.querySelector('#length').value = poles[0].pole_master_length;
            document.querySelector('.poleTable').innerHTML = output;
        }
    }

    document.querySelector('.pole').addEventListener("click", function(){
        alert('Hey!');
    })


</script>
@endsection
