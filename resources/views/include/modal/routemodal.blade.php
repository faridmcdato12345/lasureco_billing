
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
    #routeCodeTable {
        width: 100%;
        height: auto;
        color: black;
        border: 1px #ddd solid;
    }
    #routeCodeTable td{
        height: 45px;
        border-bottom: 1px #ddd solid;
        padding: 15px;
    }
    #routeCodeTable tr:hover{
        transition: background 1s;
        background: gray;
    }
    .routeDiv {
        /* height: 400px; */
        padding-left: 15px;
        padding-right: 15px;
        margin: 15px;
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

<div id="routeCodes" class="modal">
    <div class="modal-content" style="margin-top: 10px; width: 70%; height: auto;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Route Lookup</h3>
            <span href = "#routeCodes" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="row" style="width: 95%; margin: auto">
                <input type="text" class="form-control input-sm p-3" id="searchRoute" placeholder="Search Route" style="cursor: auto;">
            </div>
            <div class="routeDiv"> </div>
        </div>
    </div>
</div>

<script>
    var page = 1;
    function showRoutes(a) {
        const xhr = new XMLHttpRequest();
        var newPage = page;
        var route = "{{route('index.route', '?page=')}}" + newPage;
        xhr.open('GET', route, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                document.querySelector('.routeDiv').style.height = "auto";
                document.querySelector('.routeDiv').style.borderBottom = "none";
                document.querySelector('.routeDiv').style.overflow  = "none";
                document.querySelector('.routeDiv').style.overflowY  = "hidden";
                var response = JSON.parse(this.responseText);
                var route = response.data;
                var lastPage = response.meta.last_page;
                
                var output = "<table id='routeTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
                        output += "<td> Route Description </td> </tr>";
                        
                        var output = "<table id='routeCodeTable'> <tr id='thead'>";
                        output += "<td> Route Code </td> <td> Route </td> </tr>";
                        
                        for(var a in route){
                            // console.log(route[a].town_code);
                            if(route[a].town_code != ""){
                                var areaName = route[a].town_code[0].area_code[0].area_name;
                                var areaId = route[a].town_code[0].area_code[0].area_id;
                                var townName = route[a].town_code[0].town_code_name;
                                var townCodeId = route[a].town_code[0].town_code_id;
                                var townCode = route[a].town_code[0].town_code;
                                
                                output += "<tr onclick='setRoute(this)' id='" + route[a].route_code_id + "' name='" + route[a].route_desc + "'";
                                output +=  "class='tbody' code='" + route[a].route_code +"' areaName='" + areaName + "' areaId='" + areaId + "'"; 
                                output += "townName='" + townName + "' townCodeId='" + townCodeId + "' townCode='" + townCode + "'>";
                                // output += "<td>&nbsp;&nbsp;" + areaName + "</td>";
                                // output += "<td>" + townName + "</td>";
                                output += "<td>" + route[a].route_code + "</td>";
                                output += "<td><div class='row'>" + route[a].route_desc + "</div>"+
                                        "<div class='row' style='font-size:10px'>("+ areaName +" / ("+ townCode +")-"+townName+")</div></td></tr>";
                            }
                        }
                output += "</table>";
                output += "<table id='paginate'> <tr>";
                if(page == 1) {
                    output += "<td> <button id='" + newPage + "' class='prev' button='prev' onclick='paginate(this)' disabled> Prev </button> </td>";
                } else {
                    output += "<td> <button id='" + newPage + "' class='prev' button='prev' onclick='paginate(this)' enabled> Prev </button> </td>";
                } 
                output += "<td> <input type='number' value='" + newPage + "' readonly> </td>";
                if(page == lastPage) {
                    output += "<td> <button id='" + newPage + "' class='next' button='next' onclick='paginate(this)' disabled> Next </button> </td> </tr>";
                } else{
                    output += "<td> <button id='" + newPage + "' class='next' button='next' onclick='paginate(this)' enabled> Next </button> </td> </tr>";  
                }
                output += "</table>";

                document.querySelector('.routeDiv').innerHTML= output;
            }
        }
        document.querySelector('#routeCodes').style.display = "block";
        document.querySelector('#searchRoute').focus();
    }

    var searchRoute = document.querySelector("#searchRoute");
    searchRoute.addEventListener("change", function(){
        var xhr = new XMLHttpRequest();
        if(searchRoute.value !== ""){
            var route = "{{route('search.route',['req'=>':par'])}}"
            xhr.open('GET', route.replace(':par', searchRoute.value), true);
            xhr.send();
            xhr.onload = function(){
                if(this.status == 200){
                    var  response = JSON.parse(this.responseText);
                    var route = response.data;
                    if(route != ""){
                        var output = "<table id='routeTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
                        output += "<td> Route Description </td> </tr>";
                        
                        var output = "<table id='routeCodeTable'> <tr id='thead'>";
                        output += "<td> Route Code </td> <td> Route </td> </tr>";
                        
                        for(var a in route){
                           
                            if(route[a].town_code != ""){
                                var areaName = route[a].town_code[0].area_code[0].area_name;
                                var areaId = route[a].town_code[0].area_code[0].area_id;
                                var townName = route[a].town_code[0].town_code_name;
                                var townCodeId = route[a].town_code[0].town_code_id;
                                var townCode = route[a].town_code[0].town_code;
                                
                                output += "<tr onclick='setRoute(this)' id='" + route[a].route_code_id + "' name='" + route[a].route_desc + "'";
                                output +=  "class='tbody' code='" + route[a].route_code +"' areaName='" + areaName + "' areaId='" + areaId + "'"; 
                                output += "townName='" + townName + "' townCodeId='" + townCodeId + "' townCode='" + townCode + "'>";
                                // output += "<td>&nbsp;&nbsp;" + areaName + "</td>";
                                // output += "<td>" + townName + "</td>";
                                output += "<td>" + route[a].route_code + "</td>";
                                output += "<td><div class='row'>" + route[a].route_desc + "</div>"+
                                        "<div class='row' style='font-size:10px'>("+ areaName +" / ("+ townCode +")-"+townName+")</div></td></tr>";
                            }
                        }
                        // var output = "<table id='routeTable'> <tr id='thead'> <td> &nbsp;  Route Code </td>";
                        // output += "<td> Route Description </td> </tr>";
                        
                        // var output = "<table id='routeCodeTable'> <tr id='thead'> <td> &nbsp; Area </td>";
                        // output += "<td> Town </td> <td> Route Code </td> <td> Route </td> </tr>";
                        
                        // for(var a in route){
                        //     // console.log(route[a].town_code);
                        //     if(route[a].town_code != ""){
                        //         var areaName = route[a].town_code[0].area_code[0].area_name;
                        //         var areaId = route[a].town_code[0].area_code[0].area_id;
                        //         var townName = route[a].town_code[0].town_code_name;
                        //         var townCodeId = route[a].town_code[0].town_code_id;
                        //         var townCode = route[a].town_code[0].town_code;
                                
                        //         output += "<tr onclick='setRouteFrom(this)' id='" + route[a].route_code_id + "' name='" + route[a].route_desc + "'";
                        //         output +=  "class='tbody' code='" + route[a].route_code +"' areaName='" + areaName + "' areaId='" + areaId + "'"; 
                        //         output += "townName='" + townName + "' townCodeId='" + townCodeId + "' townCode='" + townCode + "'";
                        //         output += "><td>&nbsp;&nbsp;" + areaName + "</td>";
                        //         output += "<td>" + townName + "</td>";
                        //         output += "<td>" + route[a].route_code + "</td>";
                        //         output += "<td>" + route[a].route_desc + "</td></tr>";
                        //     }
                        // }
                        output += "</table>";
                        document.querySelector('.routeDiv').innerHTML= output;
                        document.querySelector('.routeDiv').style.height = "315px";
                        document.querySelector('.routeDiv').style.borderBottom = "1px solid #ddd";
                        document.querySelector('.routeDiv').style.overflowY  = "scroll";
                    } else {
                        var output = "<table style='color: black; margin: auto;'> <br> <br>"; 
                        output += "<tr> <td style='font-size: 25px; color: gray;'> No Route found! </td> </tr> </table>"; 
                        document.querySelector('.routeDiv').innerHTML= output;
                    }
                }
            }
        } else {
            showRoutes()
        }
    })

    function paginate(e){
        var pages = e.id;
        var button = e.getAttribute('button');

        if(button == "next"){
            page += 1;
            document.querySelector(".prev").disabled = false;
            showRoutes();
        } else if(button == "prev"){
            page = page - 1;
            showRoutes();
        }
    }

    // function setRouteFrom(e){
    //     var routeCode = e.getAttribute('code');
    //     var routeName = e.getAttribute('name');
    //     var routeId = e.id;

    //     var route = routeCode + " - " + routeName;
    //     document.querySelector("#searchRoute").value = "";
    //     document.querySelector('#routeInp').value = route;
    //     document.querySelector('#routeId').value = routeId;
    //     document.querySelector("#routeCodes").style.display = "none";
    //     document.querySelector("#billPeriod").disabled = false;
    // }
</script>
