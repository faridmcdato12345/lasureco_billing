@extends('layout.master')
@section('title', 'Unposted Collection')
@section('content')

<p class="contentheader">List of Unposted Collection</p>
<div class="main">
<br><br>
    <table style = "margin-left:70px;height:350px;width:80%;" border="0" class="content-table">
        <tr>
            <td class="thead">
                Office Code:
            </td>
            <td class="input-td">
                <input style ="width:50%;cursor:pointer;"  onclick="openOfficeCode();" id="offCode" name="area" placeholder="Select Office Code" readonly>

            </td>

        </tr>
        <tr>
        <td class="thead">
               Collection Date:
            </td>
            <td  class="input-td" >
                <input style ="width:50%;" type="date" id = "collectionDate">
            </td>
        </tr>
        <tr>

            <td colspan="4">
                <button style="height:40px;" id="mR" hidden>Select</button>
                <button style="height:40px;" id="route" hidden>Select</button>
                <button style="height:40px;" id="myBtn4" hidden>Select</button>
            <button style="width:70px;margin-top:30px;height:40px;" id="printBtn" onclick="sendDate();" >Print</button>
            </td>
        </tr>
    </table>
</div>
<div id="officeCode" class="modal">
    <div class="modal-content" style="width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Office Codes</h3>
            <button type="button" onclick="closeOfficeCode();">X</button>
        </div>
        <div class="modal-body">
            <div class="modaldivOfficeCode">

            </div>
        </div>
    </div>
</div>
<script>
    var auth = "{{Auth::user()->user_full_name}}";
    var areaname;
     var unpostedCollection = new Object();
     function openOfficeCode(){
        modalD = document.querySelectorAll(".modal");
        modalD[0].style.display="block";
     }
     function closeOfficeCode(){
        modalD = document.querySelectorAll(".modal");
        modalD[0].style.display="none";
     } 

    var xhr = new XMLHttpRequest();
    var route = "{{route('index.area1')}}";
	xhr.open('GET',route,true);
	xhr.onload = function(){
		if(this.status == 200){
			var data = JSON.parse(this.responseText);
			var output = " ";
			var val = data.data;
            output += '<div style="overflow:scroll;height:270px;">';
            output += '<table style="color:black;text-align:left;width:100%;height:20px;" border=1 class="modal-table" id="table1">';
			for(var i in val){
				var areaname = val[i].area_name;
				output += '<tr> <td style="cursor:pointer;" onclick="tdclick(this);" id=' + val[i].area_id +  '>' + areaname + 
                '</td></tr>';
			}
            output += '</table></div>';	
        }
            document.querySelector('.modaldivOfficeCode').innerHTML = output;
	}
	xhr.send();

/*--------------------------------------------------------------------------------------------------------------------------------*/
    function tdclick(area){
        modalD = document.querySelectorAll(".modal");
        modalD[0].style.display="none";
        document.querySelector('#offCode').value = area.innerHTML;
        name = area.innerHTML;
        unpostedCollection.ac_id = area.id;
    }
    function sendDate(){
        var cDate = document.querySelector('#collectionDate').value;
        unpostedCollection.bill_date = cDate;
        if(typeof unpostedCollection.ac_id != 'undefined' && typeof unpostedCollection.bill_date != 'undefined'){
                var xhr = new XMLHttpRequest();
                var r = "{{route('print.collection.list.unposted')}}";
                xhr.open('POST',r,true);
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Content-Type", "application/json");
                xhr.onload = function(){
                    if(this.status == 200){
                        var data = JSON.parse(this.responseText);
                        var url = "{{route('uncol')}}";
                        localStorage.setItem('auth',auth);
                        localStorage.setItem('areaname',name);
                        localStorage.setItem('data',JSON.stringify(data));
                        window.open(url);
                    }
                }
                xhr.send(JSON.stringify(unpostedCollection));
        }
        else{
            alert('complete the form');
        }
        setTimeout(function(){
            localStorage.removeItem('data');
            localStorage.removeItem('auth');
            localStorage.removeItem('areaname');
        },2500);
    }
</script>
@endsection
