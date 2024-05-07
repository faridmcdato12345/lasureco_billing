@extends('layout.master')
@section('title', 'Aging Report Active Bills per Area')
@section('stylesheet')
    <style>
        .br {
            height: 40px;
        }
        input, select {
            cursor: pointer;
            color: black;
        }
        #printBtn {
            margin-top: 5%;
            margin-right: 3%;
        }
    </style>
@endsection
@section('content')
<p class="contentheader">Aging Report Active Bills per Area</p>
<div class="main">
    <br><br>
    <table class="content-table">
        <tr>
            <td style="width: 15%;">
                Route:
            </td>
            <td>
                <input type="text" id="routeInp" onclick="showRoutes()" placeholder="Select Route" readonly>
                <input type="text" id="routeId" hidden>
            </td>
        </tr>
        <tr> <td class="br"> </td> </tr>
        <tr>
            <td>
                Status:
            </td>
            <td>
                <select id="selected" disabled>
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
                <input type="month" id="billPeriod" disabled>                     
            </td>                                                                 
        </tr>                                                                     
        <tr>                                                                      
            <td colspan=2>                                                        
                <button id="printBtn" onclick="print()" disabled> Print </button> 
            </td>                                                                 
        </tr>                                                                     
    </table>                                                                      
</div>                                                                            
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
                                                                                  
    var billPeriod = document.querySelector("#billPeriod");                       
    billPeriod.addEventListener("change", function(){                             
        if(billPeriod.value !== ""){                                              
            document.querySelector("#printBtn").disabled = false;                 
        } else {                                                                  
            document.querySelector("#printBtn").disabled = true;                  
        }                                                                         
    })                                                                            
                                                                                  
    function print(){                                                             
        var routeId = document.querySelector("#routeId").value;                   
        var selected = document.querySelector("#selected").value;                 
        var billPeriod = document.querySelector("#billPeriod").value;             
                                                                                  
        var toSend = new Object();                                                
        var xhr = new XMLHttpRequest();                                           
                                                                                  
        toSend.route_id = routeId;                                                
        toSend.date1 = billPeriod;                                                
        toSend.selected = selected;                                               
                                                                                  
        var toSendJSONed = JSON.stringify(toSend);                                
        var token = document.querySelector('meta[name="csrf-token"]').content;    
        var printAging = "{{route('reports.billing.aging')}}";                    
        xhr.open('POST', printAging, true);                                       
        xhr.setRequestHeader("Accept", "application/json");                       
		xhr.setRequestHeader("Content-Type", "application/json");                 
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");                 
        xhr.setRequestHeader("X-CSRF-TOKEN", token);                              
                                                                                  
        xhr.send(toSendJSONed);                                                   
                                                                                  
        xhr.onload = function(){                                                  
            if(xhr.status == 200){                 
                var route = document.querySelector("#routeInp").value;                               
                const toSend = {                                                  
                    'route_id': routeId,                                          
                    'date1': billPeriod,                                          
                    'selected': selected,
                    'route': route                                     
                }                                                                 
                                                                                  
                localStorage.setItem('data', JSON.stringify(toSend));             
                $url = '{{route("print.aging")}}'                                 
                window.open($url);                                                
            } else {                                                              
                Swal.fire({                                                       
                    title: 'Notice!',                                             
                    icon: 'error',                                                
                    text: 'No Report found!'                                      
                }).then(function(){                                               
                        window.close();                                           
                });                                                               
            }                                                                     
        }                                                                         
    }                                                                             
</script>                                                                         
@endsection                                                                       
