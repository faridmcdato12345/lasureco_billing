@extends('layout.master')
@section('title', 'Aging Report Active Bills per Area')
@section('stylesheet')
    <style>
        .br {
            height: 40px;
        }
        input, select {
            cursor: pointer;
            color: rgb(56, 56, 56);
        }
        #button {
            background-color:white;
            color:royalblue;
            float:right;
            height:40px;
            margin-top:4%;
            margin-right:2.5%;
        }
    </style>
@endsection
@section('content')
<p class="contentheader">Aging Report Active Bills per Area</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="kwh">
                    <label class="form-check-label" for="kwh">
                        kwh
                    </label>
                </div>
            </td>
        </tr>
        <tr><td height="30px"></td></tr>
        <tr>
            <td style="width: 15%;">
                Select:
            </td>
            <td>
                <select id="location">
                    <option value="all"> All </option>
                    <option value="area"> Area </option>
                    <option value="town"> Town </option>
                    <option value="route"> Route </option>
                </select>
            </td>
        </tr> 
        <tr class="br" id=></tr>
    </table>   
    
    <div id="tables"></div>

    <table class="content-table">                                                 
        <tr>                                                                      
            <td width=15%>                                                        
                Status:                                                           
            </td>                                                                 
            <td>                                                                  
                <select id="selected">                                            
                    <option value="active">Active</option>                        
                    <option value="inactive">Inactive</option>                    
                    <option value="both">Both</option>                            
                </select>                                                         
            </td>                                                                 
        </tr>                                                                     
        <tr> <td class="br"> </td> </tr>                                          
        <tr>                                                                      
            <td>                                                                  
                Bill Period:                                                      
            </td>                                                                 
            <td>                                                                  
                <input type="month" id="billPeriod">                              
            </td>                                                                 
        </tr>                                                                     
        <tr>                                                                      
            <td colspan=2>                                                        
                <button id="button" onclick="print()" disabled> Print </button>   
            </td>                                                                 
        </tr>                                                                     
    </table>                                                                      
</div>                                                                            
@include('include.modal.areamodal')                                               
@include('include.modal.townmodal')                                               
@include('include.modal.routemodal')                                              
<script>                                                                          
    function setRoute(a){                                                         
        var routeId = a.id;                                                       
        var routeDesc = a.getAttribute("name");                                   
        var routeCode = a.getAttribute("code");                                   
                                                                                  
        document.querySelector("#routeId").value = routeId;                       
        document.querySelector("#routeInp").value = routeCode + " - " + routeDesc;
                                                                                  
        document.querySelector("#routeCodes").style.display = "none";             
        document.querySelector("#selected").disabled = false;                     
        document.querySelector("#billPeriod").disabled = false;                   
    }                                                                             
                                                                                  
    function selectTown(a){                                                       
        var townId = a.id;                                                        
        var townDesc = a.getAttribute("name");                                    
        var townCode = a.getAttribute("code");                                    
                                                                                  
        document.querySelector("#townId").value = townId;                         
        document.querySelector("#townInp").value = townCode + " - " + townDesc;   
                                                                                  
        document.querySelector("#town").style.display = "none";                   
        document.querySelector("#selected").disabled = false;                     
        document.querySelector("#billPeriod").disabled = false;                   
    }                                                                             

    function setArea(a){                                                          
        var areaId = a.getAttribute("areaID");                                    
        var areaName = a.getAttribute("areaName");                                

        document.querySelector("#areaInp").value = areaName;                      
        document.querySelector("#areaId").value = areaId;                         

        document.querySelector("#areaCodes").style.display = "none";              
        document.querySelector("#selected").disabled = false;                     
        document.querySelector("#billPeriod").disabled = false;                   
    }
                                                                                  
    var billPeriod = document.querySelector("#billPeriod");                       
    billPeriod.addEventListener("change", function(){                             
        document.querySelector("#button").disabled = billPeriod.value === "";     
    })                                                                             
         
    document.querySelector("#location").addEventListener("change", function(){    
        var select = document.querySelector("#location").value;                    
        
        if(select == "area"){
            var output = "<table class='content-table' style='margin-top: -3.5%;'>";
            output += "<tr> <td width='15%'> Area: </td>";
            output += "<td> <input type='text' width='200%' id='areaInp' onclick='showArea()' placeholder='Select Area' readonly> </td>";
            output += "<td> <input type='text' id='areaId' hidden> </td> </tr> </table>";

            document.querySelector("#selected").disabled = true;                     
            document.querySelector("#billPeriod").disabled = true;
            document.querySelector("#tables").innerHTML = output;
        } else if(select == "town"){
            var output = "<table class='content-table' style='margin-top: -3.5%;'>";
            output += "<tr> <td width='15%'> Town: </td>";
            output += "<td> <input type='text' id='townInp' onclick='showTown()' placeholder='Select Town' readonly> </td>";
            output += "<td> <input type='text' id='townId' hidden> </td> </tr> </table>";

            document.querySelector("#selected").disabled = true;                     
            document.querySelector("#billPeriod").disabled = true;
            document.querySelector("#tables").innerHTML = output;
        } else if(select == "route"){
            var output = "<table class='content-table' style='margin-top: -3.5%;'>";
            output += "<tr> <td width='15%'> Route: </td>";
            output += "<td> <input type='text' id='routeInp' onclick='showRoutes()' placeholder='Select Route' readonly> </td>";
            output += "<td> <input type='text' id='routeId' hidden> </td> </tr> </table>";

            document.querySelector("#selected").disabled = true;                     
            document.querySelector("#billPeriod").disabled = true;
            document.querySelector("#tables").innerHTML = output;
        } else {
            document.querySelector("#tables").innerHTML = "";
            document.querySelector("#selected").disabled = false;  
            document.querySelector("#billPeriod").disabled = false; 
        }
    });

    function print(){       
        var location = document.querySelector("#location").value;
        var selected = document.querySelector("#selected").value;                 
        var billPeriod = document.querySelector("#billPeriod").value;             
        var choice = document.querySelector("#kwh");
        var kwh = "";
        var toSend = new Object();                                                
        var xhr = new XMLHttpRequest();

        if(location == "route") {
            var routeId = document.querySelector("#routeId").value;    
            toSend.id = routeId;    
        } else if(location == "area"){
            var areaId = document.querySelector("#areaId").value;
            toSend.id = areaId;
        } else if(location == "town"){
            var townId = document.querySelector("#townId").value;
            toSend.id = townId;
        }
                      
        kwh = choice.checked ? "yes" : "no";

        toSend.location = location;
        toSend.date1 = billPeriod;                                                
        toSend.selected = selected;
        toSend.kwh = kwh;                   
                                                                                  
        var toSendJSONed = JSON.stringify(toSend);                                
        // var token = document.querySelector('meta[name="csrf-token"]').content;    
        // var printAging = "{{route('reports.billing.aging')}}";                    
        // xhr.open('POST', printAging, true);                                       
        // xhr.setRequestHeader("Accept", "application/json");                       
		// xhr.setRequestHeader("Content-Type", "application/json");                 
		// xhr.setRequestHeader("Access-Control-Allow-Origin", "*");                 
        // xhr.setRequestHeader("X-CSRF-TOKEN", token);                              
                                                                                  
        // xhr.send(toSendJSONed);                                                   
               
        localStorage.setItem('data', toSendJSONed);             
        $url = '{{route("print.aging")}}'                                 
        window.open($url);     

        // xhr.onload = function(){                                                  
        //     if(xhr.status == 200){                 
        //         localStorage.setItem('data', toSendJSONed);             
        //         $url = '{{route("print.aging")}}'                                 
        //         window.open($url);                                                
        //     } else {                                                              
        //         Swal.fire({                                                       
        //             title: 'Notice!',                                             
        //             icon: 'error',                                                
        //             text: 'No Report found!'                                      
        //         }).then(function(){                                               
        //                 window.close();                                           
        //         });                                                               
        //     }                                                                     
        // }                                                                         
    }                                                                             
</script>                                                                         
@endsection
