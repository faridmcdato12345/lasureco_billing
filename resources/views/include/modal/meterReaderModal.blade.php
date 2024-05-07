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
    #metReadTable {
        width: 100%;
        color: black;
        border: 1px #ddd solid;
    }
    #metReadTable td{
        height: 45px;
        border-bottom: 1px #ddd solid;
        cursor: pointer;
        padding: 15px;
    }
    #metReadTable th{
        height: 45px;
        border-bottom: 1px #ddd solid;
        background-color: #5B9BD5;
    }
    #metReadTable tr:hover{
        transition: background 1s;
        background: gray;
    }
    .meterReaderDiv {
        height: 250px;
        padding-left: 15px;
        padding-right: 15px;
        margin: 15px;
    }
</style>

<div id="meterReader" class="modal">
    <div class="modal-content" style="margin-top: 10px; width: 70%; height: auto;">
        <div class="modal-header" style="width: 100%; height: 60px;">
            <h3>Meter Reader Lookup</h3>
            <span href = "#meterReader" class="closes" id="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="row" style="width: 95%; margin: auto">
                <input type="text" class="form-control input-sm" id="searchReader" placeholder="Search Meter Reader" onchange="searchReader()" style="cursor: pointer;">
            </div>
            <div class="meterReaderDiv" style="overflow-y:scroll"> </div>
        </div>
    </div>
</div>


<script>
    function getMeterReaders(){

        var route = "{{route('show.meter.reader')}}";
        var xhr = new XMLHttpRequest();
        
		xhr.open('GET',route,true);
		// xhr.open('GET','http://10.12.10.100:8082/api/v1/employee/meter_reader',true);
        xhr.onload = function(){
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                var output = " ";
                output += '<table style="text-align:left;width:100%;" border=1" id="metReadTable">';
                output +='<tr>' + '<th style="color:white">' + 'Meter Reader' + '</th>'+ '</tr>';
            
                for(let i in data.data){
                    var name = data.data[i].gas_fnamesname;
                    output += '<tr onclick="setMeterReader(this)" id='+data.data[i].em_emp_no +' meterNames='+name+'>'+
                                '<td>'+ name + '</td>';
                    output += '</tr>';
                }
                    output += '</table>';
			}
            document.querySelector('.meterReaderDiv').innerHTML = output;
            document.querySelector('#meterReader').style.display = "block";
            document.querySelector('#searchReader').focus();

		}
        xhr.send();
    }

    // function setMeterReader(rowSelected){
	//     var hide = document.querySelector('#meterReader');
    //     hide.style.display="none";
    //     document.getElementById('#mR').value = rowSelected.childNodes[0].innerHTML;
    //     // document.getElementById('#mR').value = rowSelected.getAttribute('meterNames');
    //     storage.meterReader = rowSelected.id;
    // }

    function searchReader(){
        var input, filter, table, tr, td, i, txtValue,td2;
        input = document.getElementById("searchReader");
        filter = input.value.toUpperCase();
        table = document.getElementById("metReadTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
        // td = tr[i].getElementsByTagName("td")[1];
        // console.log(td);
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        console.log(1);
                    tr[i].style.display = "";
                    } else {
                        console.log(2);
                    tr[i].style.display = "none";
                    }
            }
        }
    }
</script>
</html>