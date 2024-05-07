@extends('layout.master')
@section('title', 'Print Meter Reading Sheet')
@section('content')
<style>
.page-break{
    page-break-after: always;
}
.display{
    display:none;
}
.container {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 1vw;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    color:black;
    font-weight:bold;
    margin-left:30px;
}
td, th{
    text-align: left;
}
.container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0vw;
    width: 0vw;
}

.checkmark {
    position: absolute;
    top: 0;
    left:0;
    height: 1.5vw;
    width: 1.5vw;
    background-color: #eee;
}

.container:hover input ~ .checkmark {
    background-color: #ccc;
}

.container input:checked ~ .checkmark {
    background-color: #2196F3;
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.container input:checked ~ .checkmark:after {
    display: block;
}

.container .checkmark:after {
    left: 9px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}
</style>
<div id = "users">

</div>
<p class="contentheader">Print Meter Reading Sheet</p>
<div class="main">
<br><br>
    <table class="content-table" style="height: 400px;">

        <tr>
            <td class="thead">
                Route Code:
            </td>
            <td  class="input-td">
                <input id="routeID" style="color:black;" type="text" onclick="showRoutes()" name="route" placeholder="Select Route" readonly>
            </td>
            <td class="thead">
                Number of Consumer:
            </td>
            <td class="input-td">
                <input type="number" style="color:black;" id="consumer" name="consumer" class="consumer" placeholder="0">
            </td>
        </tr>
        <tr>
            <td class="thead">
                Meter Reader:
            </td>
            <td class="input-td">
                <input name="meterID" style="color:black;" type="text" onclick="getMeterReaders()" id="mR"  name="meterReader" placeholder="Select Acc. No." readonly>
            </td>
            <td>
                &nbsp; Billing Period:
            </td>
            <td>
                <input style="color:black;" id="billingPeriod" type="month" name="billingPeriod">
            </td>
        </tr>
        <tr>
            <td>
                <label style="color:white;" class="container">Without Previous Reading
                <input id="check" type="checkbox" onchange = "chk(this)">
                <span class="checkmark"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td id = "print" colspan="6">
            <button onclick="PMRSP();" style ="float:right;height:40px;" class="btn btn-light">Print</button>
            </td>
        </tr>
    </table>
    <!-- </form> -->
</div>
@include('include.modal.routemodal')
@include('include.modal.meterReaderModal')
<script>
    var chk1;
    var id;
 /* -----------------------------------API START------------------------------------------------*/
 function chk(a){
    if(a.checked == true){
        chk1 = 1
        console.log(chk1)
    }
    else{
        chk1 = 0;
        console.log(chk1)
    }
 }
/* -----------------------------------ROUTE TABLE----------------------------------------------*/
/*------------------------------------------------END MODAL ROUTE ---------------------------------------------------------*/
/* -----------------------------------------------paginate click-----------------------------------------------------------*/
/* ----------------------------------------------- END Paginate-----------------------------------------------------*/
/* -----------------------------------------------tdclick-----------------------------------------------------------*/
    function setRoute(rID){
        console.log(rID)
        id = rID.id;
        document.querySelector('#routeCodes').style.display = "none";
        console.log(rID.getAttribute('townname'));
        document.querySelector('#routeID').value=rID.getAttribute('name');
        // console.log(routeDesc);
        // routeDesc = ;
        // console.log(routeDesc);
        var mrsRoute = "{{route('get.consumer.count.meter.read',':id')}}";
        var mrsRoute = mrsRoute.replace(':id',rID.id);
        var xhr = new XMLHttpRequest();
		xhr.open('GET',mrsRoute,true);
        xhr.onload = function(){
            if(this.status == 200){
                console.log(1);
                var data = JSON.parse(this.responseText);
                var output = " ";
                var output1 = " ";
                var output2 = " ";
                var val = data.data.count;
                var val2 = data.data.rc_desc;
                var val3 = data.meter_reader;
                var a = document.querySelector('.consumer').value = val;
                output +=a;
                output1 += '<table style="font-family:calibri;font-size:16px;ptext-align:left;width:100%;" border=1 id="mrTable">';
                output1 +='<tr>' + '<th style="background-color: #5B9BD5;">' + 'Meter Reader' + '</th>'+ '</tr>';
                for(let i in val3){
                    var val6 = val3[i].gas_fnamesname;
                    output1 += '<tr onclick="setMeterReader(this)" id='+val3[i].em_id +' meterNames='+val3[i].gas_fnamesname+'>'+
                                '<td>'+ name + '</td>';
                    output1 += '</tr>';
                }
			}
            output1 += '</table>';
            document.querySelector('.consumer').innerHTML = output;
            document.querySelector('.modaldiv4').innerHTML = output1;
            // document.querySelector('.pages').innerHTML = output2;
		}
        xhr.send();
    }
/*---------------------------------------END PAGINATE------------------------------------------------- */
function setMeterReader(elem){

    console.log(elem);
        var mr = elem.getAttribute('meterNames');
	    var hide =document.querySelector('#meterReader');
        hide.style.display="none";
        document.querySelector('#mR').value = elem.childNodes[0].innerHTML;
    }
/*--------------------------------------tdclick1------------------------------------------------------*/

/*-------------------------------------END tdclick1----------------------------------------------------*/

/* -------------------------------------search-------------------------------------------------------- */
function myFunction() {
    var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("mreaderID");
        filter = input.value.toUpperCase();
        table = document.getElementById("mrTable");
        console.log(table);
        tr = table.getElementsByTagName("tr");
        console.log(filter);
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
/*---------------------------------------END SEARCH-----------------------------------------------------*/
/*--------------------------------------END API -------------------------------------------------------*/
function PMRSP(){
    var x = document.querySelector('#check');
        var y = 0;
        var mr = document.querySelector('#mR').value
        if(x.checked == true){
        var xhr = new XMLHttpRequest();
        var mrsRoute1 = "{{route('get.consumer.count.meter.read',':id')}}";
        var mrsRoute1 = mrsRoute1.replace(':id',id);
		xhr.open('GET',mrsRoute1,true);
        xhr.onload = function(){
			if(this.status == 200){
				var data = JSON.parse(this.responseText);
                var output = " ";
                var output1 = " ";
                var output2 = " ";
                var val = data.data.count;
                var val2 = data.data.rc_desc;
                var val3 = data.meter_reader;
                output1 += '<table style="font-family:calibri;font-size:16px;text-align:left;width:100%;" border=1 id="mrTable">';
                output1 +='<tr>' + '<th style="background-color: #5B9BD5;">' + 'Meter Reader' + '</th>'+ '</tr>';
                for(let i in val3){
                    var val6 = val3[i].gas_fnamesname;
                    output1 += '<tr>'+ '<td onclick="tdclick1(this,'+id+');" id = "'+ val3[i].em_id +'" >' + val6 + '</td>';
                    output1 += '</tr>';}
                    output1 += '</table>';
			}
                document.querySelector('#mR').innerHTML = output1;
                document.querySelector('.consumer').innerHTML = output;
                document.querySelector('.modaldiv4').innerHTML = output1;
                // document.querySelector('.pages').innerHTML = output2;
                $url = '{{route("MRSprint")}}';
                var reader = document.querySelector('#mR').value;
                var billingPeriod = document.querySelector('#billingPeriod').value;
                
                var routeID = id;
                var param = {'route':routeID, 'reader':reader, 'date':billingPeriod, 'check':1};
                console.log(param);
                localStorage.setItem('data', JSON.stringify(param));
                window.open($url);
                // location.reload();
            }
            xhr.send();
        }
        else{
            var xhr = new XMLHttpRequest();
            var mrsRoute1 = "{{route('get.consumer.count.meter.read',':id')}}";
            var mrsRoute1 = mrsRoute1.replace(':id',id);
		xhr.open('GET',mrsRoute1,true);
        xhr.onload = function(){
			if(this.status == 200){
				var data = JSON.parse(this.responseText);
                var output = " ";
                var output1 = " ";
                var output2 = " ";
                chk;
                var val = data.data.count;
                var val2 = data.data.rc_desc;
                var val3 = data.meter_reader;
                document.querySelector('.consumer').value = val;
                document.querySelector('#mR').value = mr;
                // output +=routeCode;
                // output += numConsumer;
                // output1 += meterreader;
                output1 += '<table style="font-family:calibri;font-size:16px;text-align:left;width:100%;" border=1 id="mrTable">';
                output1 +='<tr>' + '<th style="background-color: #5B9BD5;">' + 'Meter Reader' + '</th>'+ '</tr>';
                for(let i in val3){
                    var val6 = val3[i].gas_fnamesname;
                    output1 += '<tr>'+ '<td onclick="tdclick1(this,"'+id+'");" id = "'+ val3[i].em_id +'" >' + val6 + '</td>';
                    output1 += '</tr>';}
                    output1 += '</table>';
                    $url = '{{route("MRSprint1")}}';
                    var reader = document.querySelector('#mR').value;
                    var billingPeriod = document.querySelector('#billingPeriod').value;
                    var routeID = id;
                    var param = {'route':routeID, 'reader':reader, 'date':billingPeriod, 'check':0};
                    console.log(param);
                    localStorage.setItem('data', JSON.stringify(param));
                    window.open($url);
			}
                document.querySelector('#mR').innerHTML = output1;
                document.querySelector('.consumer').innerHTML = output;
                document.querySelector('.modaldiv4').innerHTML = output1;
                // document.querySelector('.pages').innerHTML = output2;
            }
            xhr.send();
        }
    // location.reload();
}
// function PMRSP1(meterID,routeID,chk){
//     $url = '{{route("MRSprint1")}}';
//     var reader = document.querySelector('#mR').value;
//     var billingPeriod = document.querySelector('#billingPeriod').value;
//     var param = {'route':routeID, 'reader':reader, 'date':billingPeriod, 'check':chk};
//     localStorage.setItem('data', JSON.stringify(param));
//     window.open($url);
//     location.reload();
// }
/*--------------------------------------------------SEARCH-----------------------------------------*/
</script>
@endsection
