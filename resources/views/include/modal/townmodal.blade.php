<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    table{
        margin: auto;
        width: 90%;
    }
    #billPeriodTable {
        display: none;
        margin-top: 3%;
    }
    input {
        color: black;
        width: 95%;
        cursor: pointer;
    }

    .townDiv{
        padding-left: 15px;
        padding-right: 15px;
        margin: 15px;
    }

    #thead {
        background-color: #5B9BD5;
        color: white;
    }
    .tbody {
        cursor: pointer;
    }
    #townTbl {
        width: 100%;
        height: auto;
        color: black;
        border: 1px #ddd solid;
    }
    #townTbl td{
        height: 45px;
        border-bottom: 1px #ddd solid;
        padding: 15px;
    }

    #townTbl tr:hover{
        transition: background 1s;
        background: gray;
    }

    #billPeriod {
        width: 97%;
        margin-left: 4px;
    }
    #cutOffTable {
        margin-top: -2%;
        display: none;
    }
    #cutOffTable2 {
        margin-top: -3%;
        display: none;
    }
    #cutOff {
        margin-left: 4px;
        width: 96.7%;
    }
    #printBtnTbl {
        display: none;
        background-color: red;
    }
    #printButton { 
        display: none;
        float: right;
        margin-right: 9%;
        background-color: white;
        color: royalblue;
        height: 45px;
        width: 5%;
        border-radius: 2px;
    }
    #paginate {
        width: 100%;
        margin: auto;
    }
    #paginate button {
        background-color: royalblue;
        border-radius: 3px;
        height: 35px;
        width: 100%; 
    }
</style>
<body>
    <div id="town" class="modal">
        <div class="modal-content" style="margin-top: 10px; width: 60%; height: auto;">
            <div class="modal-header" style="width: 100%; height: 60px;">
                <h3>Town Lookup</h3>
                <span href = "#town" class="closes" id="close">&times;</span>
            </div>
            <div class="modal-body">

                <div class="row" style="width: 95%; margin: auto">
                    <input type="text" class="form-control input-sm p-3" id="searchTown" name="searchTown" placeholder="Search Town" style="cursor: auto;">
                </div>
                <div class="townDiv"> </div>

            </div>
        </div>
    </div>
</body>
</html>

<script>
    var page = 1;
    function showTown(){
        const xhr = new XMLHttpRequest();
        var newPage = page;
        var route = "{{route('index.town','?page=')}}" + newPage;
        xhr.open('GET', route, true);
        xhr.send();

        xhr.onload = function(){
            if(this.status == 200) {
                document.querySelector('.townDiv').style.overflowY  = "hidden";
                document.querySelector('.townDiv').style.height = "auto";
                document.querySelector('.townDiv').style.borderBottom = "none";
                document.querySelector('.townDiv').style.overflow  = "none";
                var response = JSON.parse(this.responseText);
                var town = response.data;
                var lastPage = response.meta.last_page;

                var output = "<table id='townTbl'> <tr id='thead'> <td> &nbsp;  Town Code </td>";
                output += "<td> Town </td> </tr>";
                
                for(var a in town){
                    output += "<tr onclick='selectTown(this)' id='" + town[a].town_code_id + "' name='" + town[a].town_code_name + "' class='tbody'";
                    output += "code='" + town[a].town_code + "'> <td>&nbsp;&nbsp;&nbsp;" + town[a].town_code + "</td>";
                    // output += "<td>&nbsp;" + town[a].town_code_name + "</td></tr>";
                    output += "<td><div class='row'>" + town[a].town_code_name + "</div>"+
                                        "<div class='row' style='font-size:10px'>("+ town[a].area_code[0].area_name +")</div></td></tr>";
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

                document.querySelector('.townDiv').innerHTML= output;
            }
        }
        document.querySelector('#town').style.display = "block";
    }

    var searchTown = document.querySelector("#searchTown");
    searchTown.addEventListener("change", function(){
        var xhr = new XMLHttpRequest();
        if(searchTown.value !== ""){
            var route = "{{route('search.town',['request'=>':par'])}}"
            xhr.open('GET', route.replace(':par', searchTown.value), true);
            xhr.send();
            xhr.onload = function(){
                if(this.status == 200){
                    var  response = JSON.parse(this.responseText);
                    var town = response.data;
                    if(town != ""){
                        var output = "<table id='townTbl' style='height: auto; overflow-y: scroll;'> <tr id='thead'> <td> &nbsp;  Town Code </td>";
                        output += "<td> Town </td> </tr>";
                        
                        for(var a in town){
                            output += "<tr onclick='selectTown(this)' id='" + town[a].town_code_id + "' name='" + town[a].town_code_name + "' class='tbody'";
                            output += "code='" + town[a].town_code + "'> <td>&nbsp;&nbsp;&nbsp;" + town[a].town_code + "</td>";
                            // output += "<td>&nbsp;" + town[a].town_code_name + "</td></tr>";
                            output += "<td><div class='row'>" + town[a].town_code_name + "</div>"+
                                      "<div class='row' style='font-size:10px'>("+ town[a].area_code[0].area_name +")</div></td></tr>";
                
                        }
                        output += "</table>";
                        document.querySelector('.townDiv').innerHTML= output;
                        document.querySelector('.townDiv').style.height = "auto";
                        document.querySelector('.townDiv').style.borderBottom = "1px solid #ddd";
                        document.querySelector('.townDiv').style.overflowY  = "scroll";
                    } else {
                        var output = "<table style='color: black; margin: auto;'> <br> <br>"; 
                        output += "<tr> <td style='font-size: 25px; color: gray;'> No Town found! </td> </tr> </table>"; 
                        document.querySelector('.townDiv').innerHTML= output;
                    }
                }
            }
        } else {
            showTown();
        }
    })

    function paginate(e){
        var pages = e.id;
        var button = e.getAttribute('button');

        if(button == "next"){
            page += 1;
            document.querySelector(".prev").disabled = false;
            showTown();
        } else if(button == "prev"){
            page = page - 1;
            showTown();
        }
    }

    // function selectTown(a){
    //     var townName = a.getAttribute('name');
    //     var townCode = a.getAttribute('code');
    //     var town = townCode + " - " + townName;

    //     document.querySelector("#townId").value = a.id;
    //     document.querySelector("#townName").value = town;
    //     document.querySelector("#towntown").value = townName;
    //     document.querySelector("#town").style.display = "none";
    //     document.querySelector("#searchTown").value = "";
    //     document.querySelector("#billPeriodTable").style.display = "block";
    // }
</script>