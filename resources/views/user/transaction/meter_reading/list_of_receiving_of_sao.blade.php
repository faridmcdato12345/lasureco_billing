@extends('layout.master')
@section('title', 'List of Receiving of SAO')
@section('content')
<p class="contentheader">List of Receiving of SAO</p>
<div class="main">
    <br><br>
    <table border="0" style="height: 400px;" class="content-table">
        <tr>
        <td class="thead">
                Route Code:
            </td>
            <td  class="input-td">
                <input id="routeID" type="text" onclick="showRoutes()" name="route" placeholder="Select Route" readonly>
            </td>
            <td class="thead">
                &nbsp;&nbsp; Billing Period:
            </td>
            <td colspan="2">
                <input id="nobtninput" onfocusout="Bperiod(this)" type="month">
            </td>
        </tr>
        <tr>
            <td class="thead">
                Meter Reader:
            </td>
            <td class="input-td">
                <input name="meterID" type="text" onclick="getMeterReaders()" id="mR" name="meterReader" placeholder="Select Meter Reader" readonly>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <button style ="float:right;height:40px;" class="btn btn-light" onclick="listofsoa()">Print</button>
            </td>
        </tr>
    </table>
</div>
@include('include.modal.routemodal')
@include('include.modal.MeterReaderModal')
<script>
    var tosendListSoa = new Object();
        /*--------*/
        function setRoute(rID){

        tosendListSoa.rID = rID.id;
		console.log(rID);
		document.querySelector('#routeCodes').style.display = "none";
		document.querySelector('#routeID').value = rID.getAttribute('townname');
        //document.querySelector('#routeID').value = rID; //pashpash
        var xhr = new XMLHttpRequest();
		xhr.open('GET','http://10.12.10.100:8082/api/v1/mrs/'+rID.id,true);
        xhr.onload = function(){
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                var output = " ";
                var output1 = " ";
                var output2 = " ";
                var val = data.data.count;
                var val2 = data.data.rc_desc;
                var val3 = data.meter_reader;
                // output +=a;
                output1 += '<table style="text-align:left;width:100%;" border=1 id="table1">';
                output1 +='<tr>' + '<th>' + 'Meter Reader' + '</th>'+ '</tr>';
                for(let i in val3){
                    var val6 = val3[i].gas_fnamesname;
                    output1 += '<tr name="'+val3[i].gas_fnamesname+'" onclick="setMeterReader(this)" id="'+ val3[i].em_id +'">'+
                                '<td>'+ name + '</td>';
                    output1 += '</tr>';
                }
                document.querySelector('.meterReaderDiv').innerHTML = output1;
            // document.querySelector('#meterReader').style.display = "block";
            document.querySelector('#searchReader').focus();
		}
        xhr.send();
    }
}
    /*------*/
    /*-------*/
    function Bperiod(a){
        tosendListSoa.Bperiod=a.value;
    }
    function setMeterReader(elem){

console.log(elem);
    var mr = elem.childNodes[0].innerHTML;
    console.log(elem.getAttribute('meterNames'));
    tosendListSoa.mReader = mr;
    var hide =document.querySelector('#meterReader');
    hide.style.display="none";
    document.querySelector('#mR').value = mr;

    
}
	// function tdclick1(elem,d){
    //     var mr = elem.innerHTML;
	// 	var hide =document.querySelector('#meterreader');
    //     hide.style.display="none";
    //     tosendListSoa.mReader = mr;
    //     document.querySelector('#mR').value = mr;
	// }
    function listofsoa(){
        var listofsoaroute = "{{route('show.soa.list',['rc_id'=>':rID','billingPeriod'=>':Bperiod','mReader'=>':mReader'])}}";
        var listofsoaroute1 = listofsoaroute.replace(':rID',tosendListSoa.rID);
        var listofsoaroute2 = listofsoaroute1.replace(':Bperiod',tosendListSoa.Bperiod);
        var listofsoaroute3 = listofsoaroute2.replace(':mReader',tosendListSoa.mReader);
        console.log(listofsoaroute3);
        var xhr = new XMLHttpRequest();
		xhr.open('GET',listofsoaroute3,true);
		xhr.onload = function(){
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                var url = "{{route('listofsoa')}}";
				localStorage.setItem('data',JSON.stringify(data));
				window.open(url);
            }
        }
        xhr.send();
		localStorage.removeItem('data');
    }
</script>
@endsection

