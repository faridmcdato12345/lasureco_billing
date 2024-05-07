@extends('layout.master')
@section('title', "TELLER's DCR")
@section('content')

<style>
    body {font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;}

    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    /* Style the buttons inside the tab */
    .tab button {
        background-color: inherit ;
        float: left ;
        border: none ;
        outline: none ;
        cursor: pointer ;
        padding: 14px 20px ;
        transition: 0.3s ;
        font-size: 17px ;
        color: black !important;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: rgb(49, 6, 241);
        font-weight: bold;
        transition: 0.3s;
        color: white !important;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: rgb(49, 6, 241);
        color: white !important
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 30px;
        border: 1px solid #ccc;
        border-top: none;
        background-color: white;
        color: black !important;

    }
</style>

<p class="contentheader">TELLER'S DCR</p>

<div class="main m-3">
        <div class="row p-1">
            <select name="" id="" class="form-control m-2 col-3" style="width: 15%">
                <option value="">Unposted</option>
                <option value="">Posted</option>
            </select>
            <select name="" id="" class="form-control m-2 col-3" style="width: 15%">
                <option value="">Power Bill (PB)</option>
                <option value="">Non-Bill (NB)</option>
            </select>
        </div>
      <div class="tab">
        <button class="tablinks" onclick="opentTab(event, 'sdcr')">Summary of DCR</button>
        <button class="tablinks" onclick="opentTab(event, 'dcr')">Daily Collection Report (DCR)</button>
      </div>
      
      <div id="sdcr" class="tabcontent">
        <h5>SUMMARY OF DAILY COLLECTION</h5>
        <p>London is the capital city of England.</p>
      </div>
      
      <div id="dcr" class="tabcontent">
        <h5>DAILY COLLECTION REPORT</h5>
        <p>Paris is the capital of France.</p> 
      </div>

</div>

<div id="cashiers" class="modal">
    <div class="modal-content" style="margin-top: 30px; width: 40%; height: 350px;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h5>Cashier Lookup</h5>
            <span href = "#cashiers" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="cashierDiv"> </div>
        </div>
    </div>
</div>

<script>
    function opentTab(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");

        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>
@endsection
