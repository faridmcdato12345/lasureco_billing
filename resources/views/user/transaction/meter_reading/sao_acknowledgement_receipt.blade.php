@extends('layout.master')
@section('title', 'SAO Acknowledgement Receipt')
@section('content')
<p class="contentheader">SAO Acknowledgement Receipt</p>
<div class="main">
    <br><br>
    <table style="height:400px;" class="content-table">
        <tr>
            <td class="thead">
                Route Code:
            </td>
            <td  class="input-td">
                <input id="routeID" type="text" onclick="showRoutes();" name="route" placeholder="Select Route" readonly>
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
                <input name="meterID" type="text"  id="mR" onclick="getMeterReaders()" name="meterReader" placeholder="Select Meter Reader" readonly>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <button onclick="soaakc()" style ="float:right;height:40px;" class="btn btn-light">Print</button>
            </td>
        </tr>
    </table>
</div>
@include('include.modal.routemodal')
@include('include.modal.meterReaderModal')
<script>
     var tosendListSoa = new Object();
        /*--------*/
        function setRoute(rID){
        tosendListSoa.rID = rID.id;
        id = rID.id;
        document.querySelector('#routeCodes').style.display = "none";
        console.log(rID.getAttribute('name'));
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
                // var a = document.querySelector('.consumer').value = val;
                // output +=a;
                output1 += '<table style="font-family:calibri;font-size:16px;ptext-align:left;width:100%;" border=1 id="mrTable">';
                output1 +='<tr>' + '<th style="background-color: #5B9BD5;">' + 'Meter Reader' + '</th>'+ '</tr>';
                for(let i in val3){
                    var val6 = val3[i].gas_fnamesname;
                    output1 += '<tr name="'+val3[i].gas_fnamesname+'" onclick="setMeterReader(this)" id="'+ val3[i].em_id +'">'+
                                '<td>'+ name + '</td>';
                    output1 += '</tr>';
                }
			}
            output1 += '</table>';
            document.querySelector('.meterReaderDiv').innerHTML = output1;
            // document.querySelector('#meterReader').style.display = "block";
            document.querySelector('#searchReader').focus();
            // document.querySelector('.pages').innerHTML = output2;
		}
        xhr.send();
    }
    /*------*/
    function setMeterReader(elem){

console.log(elem);
    var mr = elem.childNodes[0].innerHTML;
    console.log(elem.getAttribute('meterNames'));
    tosendListSoa.mReader = mr;
    var hide =document.querySelector('#meterReader');
    hide.style.display="none";
    document.querySelector('#mR').value = mr;

    
}
    /*-------*/
    function Bperiod(a){
        tosendListSoa.Bperiod=a.value;
    }
	// function tdclick1(elem,d){
    //     var mr = elem.innerHTML;
    //     tosendListSoa.mReader = mr;
	//     var hide =document.querySelector('#meterreader');
    //     hide.style.display="none";
    //     document.querySelector('#mR').value = mr;
	// }
    function soaakc(){
        var listofsoaroute = "{{route('consumer.account.receipt.soa',['rc_id'=>':rID','billingPeriod'=>':Bperiod','mReader'=>':mReader'])}}";
        var listofsoaroute1 = listofsoaroute.replace(':rID',tosendListSoa.rID);
        var listofsoaroute2 = listofsoaroute1.replace(':Bperiod',tosendListSoa.Bperiod);
        var listofsoaroute3 = listofsoaroute2.replace(':mReader',tosendListSoa.mReader);
        console.log(listofsoaroute3);
        var xhr = new XMLHttpRequest();
		xhr.open('GET',listofsoaroute3,true);
		xhr.onload = function(){
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                 console.log(data);
                var url = "{{route('soaakc')}}";
				localStorage.setItem('data',JSON.stringify(data));
				window.open(url);
            }
        }
        xhr.send();
		localStorage.removeItem('data');
    }
</script>
@endsection
