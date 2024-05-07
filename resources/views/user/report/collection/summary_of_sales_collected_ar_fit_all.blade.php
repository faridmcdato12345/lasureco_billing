@extends('layout.master')
@section('title', 'Summary of Sales Collected - Fit Allowance')
@section('content')

<p class="contentheader">Summary of Sales Collected - Fit Allowance</p>
<div class="main">
    <table class="content-table" style="height:250px;">
        <tr>
            <td colspan=4> 
                <table> 
                    <tr> 
                        <td> 
                            <input type="radio" name="summBy" onchange = "mymymy(this)" value="sales" class="radio">
                        </td>
                        <td> 
                            &nbsp; Sales 
                        </td>
                        <td> 
                            &nbsp; <input type="radio" onchange = "mymymy(this)" value="collection" name="summBy" class="radio">
                        </td>
                        <td> 
                            &nbsp; Collections
                        </td>
                    </tr>
                </table>
            </td> 
        </tr>
        <tr>
            <td> 
                Area Code:
            </td>
            <td>
                <input type="text" style="border-radius:3px;height:35px;margin-left:10px;color:black"  id="area1" class="input-Txt" href="#area" placeholder="Select Area" readonly>
            </td>
            <td> 
                &nbsp; Month Billed:
            </td>
            <td> 
                <input type="month" name="monthBilled" onfocusout = "dateRRR(this)">
            </td>
        </tr>
        <!-- <tr> 
            <td> 
                Prepared By:
            </td>
            <td> 
                <input type="text" name="prepBy" placeholder="Prepared By">
            </td>
            <td colspan=2> 
                &nbsp; <input type="text" name="prepByPos" placeholder="Position">
            </td> 
        </tr> -->
        <!-- <tr> 
            <td> 
                Checked By:
            </td>
            <td> 
                <input type="text" name="checkBy" placeholder="Checked By">
            </td>
            <td colspan=2> 
                &nbsp; <input type="text" name="checkByPos" placeholder="Position">
            </td> 
        </tr>
        <tr> 
            <td> 
                Approved By:
            </td>
            <td> 
                <input type="text" name="appBy" placeholder="Approved By">
            </td>
            <td colspan=2> 
                &nbsp; <input type="text" name="appByPos" placeholder="Position">
            </td> 
        </tr> -->
        <tr> 
            <td colspan=4> 
                <button class="printBtn" onclick="toprint()" style="height: 40px; float: right; margin-right: 5px;"> 
                    Print
                </button>
            </td>
        </tr>
    </table>
</div>
<script>
    var tosend = new Object();
    var xhr = new XMLHttpRequest();
	xhr.open('GET','{{route("index.area")}}',true);

	xhr.onload = function(){
		if(this.status == 200){
			var hide = document.querySelector('.content4');
			var data = JSON.parse(this.responseText);
			var output = " ";
			var val = data.data;
			var val3 = data.meta['last_page'];
			var val2 = data.meta['current_page'];
            output += '<table style="text-align:left;width:100%;" border=1 class="modal-table" id="table1">';
            output += '<tr><th style="color:black;">Area Codes</th></tr>';
			for(var i in val){
				var areaname = val[i].area_name;
				output += '<tr> <td style="cursor:pointer;color:black" onclick="tdclick(this);" id=' + val[i].area_id +  '> ' + areaname + '</td></tr>';
				
			}
			output += '</td></tr>';
            output += '</table>';

			document.querySelector('.modaldivArea').innerHTML = output;
        }
		else {
			alert('No Server!');
		}
	}
	xhr.send();

    function tdclick(c){
        tosend.area_id = c.id;
        modalD = document.querySelectorAll(".modal");
        modalD[4].style.display="none";
        document.querySelector('#area1').value = c.innerHTML;
    }
    function mymymy(a){
        tosend.selected = a.value;
    }
    function dateRRR(b){
        tosend.date_period = b.value;
    }
    function toprint(){
        var xhr = new XMLHttpRequest();
        var fitall = "{{route('report.collection.sales.fitall')}}";
        xhr.open('POST',fitall,true);
        xhr.setRequestHeader("Accept", "application/json");
        xhr.setRequestHeader("Content-Type", "application/json");    
        xhr.onload = function(){
            if(this.status == 200){
                var url = '{{route("SFitAll")}}';
                var data = JSON.parse(this.responseText);
                var date = tosend.date_period;
				localStorage.setItem("petsa",JSON.stringify(date));
                localStorage.setItem('data',JSON.stringify(data));
                window.open(url);
            }
            else if(this.status == 422){
                alert('No Record Found');
            }
        }
        xhr.send(JSON.stringify(tosend));
        localStorage.removeItem('petsa');
        localStorage.removeItem('data');
    }
</script>
@endsection
