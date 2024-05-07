
<style>
    input {
        cursor: pointer;
    }
    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #areaCodeTable {
        width: 100%;
        height: auto;
        color: black;
        border: 1px #ddd solid;
    }
    #areaCodeTable td{
        height: 45px;
        border-bottom: 1px #ddd solid;
        padding: 15px;
    }
    #areaCodeTable tr:hover{
        transition: background 1s;
        background: gray;
    }
    .areaDiv {
        /* height: 400px; */
        padding-left: 15px;
        padding-right: 15px;
        margin: 15px;
    }
    #printBtn {
        margin-top: 10%;
        margin-right: 2.8%;
        display: none;
    }
    #paginate {
        width: 100%;
        margin: auto;
        margin-top: 0.5%;
    }
    #paginate button {
        background-color: royalblue;
        border-radius: 3px;
        height: 35px;
        width: 100%; 
    }
    #paginate input {
        margin: auto;
    }

</style>

<div id="areaCodes" class="modal">
    <div class="modal-content" style="margin-top: 10px; width: 70%; height: auto;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Area Lookup</h3>
            <span href = "#areaCodes" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            {{-- <div class="row" style="width: 95%; margin: auto">
                <input type="text" class="form-control input-sm p-3" id="searchArea" placeholder="Search Area" style="cursor: auto;">
            </div> --}}
            <div class="areaDiv"> </div>
        </div>
    </div>
</div>

<script>
      var page = 1;
    function showArea(a) {
        const xhr = new XMLHttpRequest();
        var newPage = page;
        var route = "{{route('index.area')}}";
        xhr.open('GET', route, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                document.querySelector('.areaDiv').style.height = "auto";
                document.querySelector('.areaDiv').style.borderBottom = "none";
                document.querySelector('.areaDiv').style.overflow  = "none";
                document.querySelector('.areaDiv').style.overflowY  = "hidden";
                var response = JSON.parse(this.responseText);
                var area = response.data;
                // var lastPage = response.meta.last_page;
                        
                var output = "<table id='areaCodeTable'>";
                output += "<tr id='thead'> <td> Area Code </td> <td> Area </td> </tr>";
                
                for(var a in area){
                    // console.log(area[a].area_name);
                    if(area[a].area_name != ""){
                        var areaName = area[a].area_name;
                        var areaId = area[a].area_id;
                        
                        output += "<tr onclick='setArea(this)' class='tbody' areaName='" + areaName + "' areaId='" + areaId + "'>";
                        output += "<td>" + areaId + "</td>";
                        output += "<td>" + areaName + "</td></tr>";
                    }
                }
                output += "</table>";
                // output += "<table id='paginate'> <tr>";
                // if(page == 1) {
                //     output += "<td> <button id='" + newPage + "' class='prev' button='prev' onclick='paginate(this)' disabled> Prev </button> </td>";
                // } else {
                //     output += "<td> <button id='" + newPage + "' class='prev' button='prev' onclick='paginate(this)' enabled> Prev </button> </td>";
                // } 
                // output += "<td> <input type='number' value='" + newPage + "' readonly> </td>";
                // if(page == lastPage) {
                //     output += "<td> <button id='" + newPage + "' class='next' button='next' onclick='paginate(this)' disabled> Next </button> </td> </tr>";
                // } else{
                //     output += "<td> <button id='" + newPage + "' class='next' button='next' onclick='paginate(this)' enabled> Next </button> </td> </tr>";  
                // }
                // output += "</table>";

                document.querySelector('.areaDiv').innerHTML= output;
            }
        }
        document.querySelector('#areaCodes').style.display = "block";
        // document.querySelector('#searchArea').focus();
    }

    // var searchArea = document.querySelector("#searchArea");
    // searchArea.addEventListener("change", function(){
    //     var xhr = new XMLHttpRequest();
    //     if(searchArea.value !== ""){
    //         var route = "{{route('search.route',['req'=>':par'])}}"
    //         xhr.open('GET', route.replace(':par', searchArea.value), true);
    //         xhr.send();
    //         xhr.onload = function(){
    //             if(this.status == 200){
    //                 var  response = JSON.parse(this.responseText);
    //                 var route = response.data;
                    
    //                 if(route != ""){
    //                     var output = "<table id='areaTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
    //                     output += "<td> Route Description </td> </tr>";
                        
    //                     var output = "<table id='areaCodeTable'> <tr id='thead'>";
    //                     output += "<td> Route Code </td> <td> Route </td> </tr>";
                        
    //                     for(var a in route){
    //                         // console.log(route[a].town_code);
    //                         if(route[a].town_code != ""){
    //                             var areaName = route[a].town_code[0].area_code[0].area_name;
    //                             var areaId = route[a].town_code[0].area_code[0].area_id;
    //                             var townName = route[a].town_code[0].town_code_name;
    //                             var townCodeId = route[a].town_code[0].town_code_id;
    //                             var townCode = route[a].town_code[0].town_code;
                                
    //                             output += "<tr onclick='setArea(this)' id='" + route[a].route_code_id + "' name='" + route[a].route_desc + "'";
    //                             output +=  "class='tbody' code='" + route[a].route_code +"' areaName='" + areaName + "' areaId='" + areaId + "'"; 
    //                             output += "townName='" + townName + "' townCodeId='" + townCodeId + "' townCode='" + townCode + "'>";
    //                             // output += "<td>&nbsp;&nbsp;" + areaName + "</td>";
    //                             // output += "<td>" + townName + "</td>";
    //                             output += "<td>" + route[a].route_code + "</td>";
    //                             output += "<td><div class='row'>" + route[a].route_desc + "</div>"+
    //                                     "<div class='row' style='font-size:10px'>("+ areaName +" / ("+ townCode +")-"+townName+")</div></td></tr>";
    //                         }
    //                     }
    //                     // var output = "<table id='areaTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
    //                     // output += "<td> Route Description </td> </tr>";
                        
    //                     // var output = "<table id='areaCodeTable'> <tr id='thead'> <td> &nbsp; Area </td>";
    //                     // output += "<td> Town </td> <td> Route Code </td> <td> Route </td> </tr>";
                        
    //                     // for(var a in route){
    //                     //     // console.log(route[a].town_code);
    //                     //     if(route[a].town_code != ""){
    //                     //         var areaName = route[a].town_code[0].area_code[0].area_name;
    //                     //         var areaId = route[a].town_code[0].area_code[0].area_id;
    //                     //         var townName = route[a].town_code[0].town_code_name;
    //                     //         var townCodeId = route[a].town_code[0].town_code_id;
    //                     //         var townCode = route[a].town_code[0].town_code;
                                
    //                     //         output += "<tr onclick='setArea(this)' id='" + route[a].route_code_id + "' name='" + route[a].route_desc + "'";
    //                     //         output +=  "class='tbody' code='" + route[a].route_code +"' areaName='" + areaName + "' areaId='" + areaId + "'"; 
    //                     //         output += "townName='" + townName + "' townCodeId='" + townCodeId + "' townCode='" + townCode + "'";
    //                     //         output += "><td>&nbsp;&nbsp;" + areaName + "</td>";
    //                     //         output += "<td>" + townName + "</td>";
    //                     //         output += "<td>" + route[a].route_code + "</td>";
    //                     //         output += "<td>" + route[a].route_desc + "</td></tr>";
    //                     //     }
    //                     // }
    //                     output += "</table>";
    //                     document.querySelector('.areaDiv').innerHTML= output;
    //                     document.querySelector('.areaDiv').style.height = "315px";
    //                     document.querySelector('.areaDiv').style.borderBottom = "1px solid #ddd";
    //                     document.querySelector('.areaDiv').style.overflowY  = "scroll";
    //                 } else {
    //                     var output = "<table style='color: black; margin: auto;'> <br> <br>"; 
    //                     output += "<tr> <td style='font-size: 25px; color: gray;'> No Route found! </td> </tr> </table>"; 
    //                     document.querySelector('.areaDiv').innerHTML= output;
    //                 }
    //             }
    //         }
    //     } else {
    //         showArea();
    //     }
    // });

    // function paginate(e){
    //     var pages = e.id;
    //     var button = e.getAttribute('button');

    //     if(button == "next"){
    //         page += 1;
    //         document.querySelector(".prev").disabled = false;
    //         showArea();
    //     } else if(button == "prev"){
    //         page = page - 1;
    //         showArea();
    //     }
    // }

    // function setArea(e){
    //     var routeCode = e.getAttribute('code');
    //     var routeName = e.getAttribute('name');
    //     var routeId = e.id;

    //     var route = routeCode + " - " + routeName;
    //     document.querySelector("#searchArea").value = "";
    //     document.querySelector('#routeInp').value = route;
    //     document.querySelector('#routeId').value = routeId;
    //     document.querySelector("#areaCodes").style.display = "none";
    //     document.querySelector("#billPeriod").disabled = false;
    // }
</script>