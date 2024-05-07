@extends('layout.master')
@section('title', 'Print Summary of DCR - Teller')
@section('content')

<p class="contentheader">Print Summary of DCR - Teller</p>
<div class="main">
    <table style ="height:100px;" border="0" class="content-table">
        <tr>
            <td class="input-td">
            <select style="color:black;width:45%" onchange="selected(this.value)" id="selected">
                   <option value="unposted">Unposted</option>
                   <option value="posted">Posted</option>
            </select>
            </td>
        </tr>
    </table>
    <table style ="height:500px;width:90%;" border="0" class="content-table">
        <tr>
            <td class="thead">
                Teller:
            </td>
            <td class ="input-td">
            <input style="color:black;width:70%;cursor:pointer;" id = "teller" type="text" onclick="openTeller();"  placeholder="Select Tellers" readonly>
            </td>
        </tr>
        <tr>
            <td class="thead">
                Collection Date:
            </td>
            <td class ="input-td">
            <input style="color:black;width:70%;" onfocusout="colDate();" id = "colDate" type="date">
            </td>
        </tr>
        <tr>
            <td class="thead">
                TOR No. From:
            </td>
            <td class ="input-td">
            <input style="color:black;width:70%;" type="text" id = "torFrom"  onfocusout="torFrom();" name="area" placeholder="TOR No. From">
            </td>
            <td class="thead">
                TOR No. To:
            </td>
            <td class ="input-td">
            <input style="color:black;width:90%;" type="text" id = "torTo" onfocusout="torTo();" name="area" placeholder="TOR No. From">
            </td>
        </tr>
        <tr>
            <td class="thead">
                Total Collection:
            </td>
            <td class ="input-td">
            <input style="color:black;width:70%;" id = "totalC" type="text" readonly>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <button style="width:70px;margin-top:30px;height:40px;" onclick="tellerPrint();" id="printBtn" >Print</button>
            </td>
        </tr>
    </table>
</div>
<!-- start of modal -->
<div id="teller" class="modal">
    <div class="modal-content" style="width: 50%;">
        <div class="modal-header" style="width: 100%;">
            <h3>Tellers</h3>
            <button type="button" onclick="closeTeller();">X</button>
        </div>
        <div class="modal-body">
            <div class="modaldivTeller">

            </div>
        </div>
    </div>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container').childNodes;
        c[9].style.color="blue";
     }
     var tosend = new Object();
// Modal Teller
     function openTeller(){
        modalD = document.querySelectorAll(".modal");
        modalD[0].style.display="block";
     }
     function closeTeller(){
        modalD = document.querySelectorAll(".modal");
        modalD[0].style.display="none";
     }
// close Modal Teller

     var xhr = new XMLHttpRequest();
        xhr.open('GET','http://10.12.10.100:8082/api/v1/employee/teller',true);
        xhr.onload = function(){
            if(this.status == 200){
            var data = JSON.parse(this.responseText);
            var output = " ";
            var data = data.Tellers;
            output += '<div style="overflow:scroll;height:270px;">';
            output += '<table style="color:black;text-align:left;width:100%;height:20px;" border=1 class="modal-table" id="table1">';
            output += '<thead><tr>' +
                    '<th>Designation</th>' +
                    '<th>Fullname</th>' +
                    '</tr></thead><tbody>';
            for(i in data){
                output += '<tr><td id="'+data[i].em_emp_no+'" onclick="tdclickTeller(this)">' + data[i].gas_fnamesname + '</td>' +
                        '<td>' + data[i].ebs_designation + '</td>'+
                        '</tr>';
            }
            output += '<tbody></table></div>';
            document.querySelector('.modaldivTeller').innerHTML=output;
        }
    }
    xhr.send();
    /*-----------------------------------------------------------------------------------------------------*/
    function selected(value2){
        tosend.selected=value2;
     }

    function tdclickTeller(teller){
        modalD = document.querySelectorAll(".modal");
        modalD[0].style.display="none";
        document.querySelector('#teller').value = teller.innerHTML;
        tosend.teller_id = parseInt(teller.id);
    }

    function colDate(){
        var colDate = document.querySelector('#colDate').value;
        tosend.bill_date=colDate     
    }
    function torFrom(){
        var torFrom = document.querySelector('#torFrom').value;
        tosend.or_from = parseInt(torFrom);
    }
    function torTo(){
        var torTo = document.querySelector('#torTo').value;
        tosend.or_to = parseInt(torTo);

        var xhr = new XMLHttpRequest();
        xhr.open('POST','http://10.12.10.100:8082/api/v1/collections/total_amount/summary/DCR',true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onload = function(){
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                document.querySelector('#totalC').value = data.Total_Collection;
            }
        }
        xhr.send(JSON.stringify(tosend));
    }
    function tellerPrint(){
        var xhr = new XMLHttpRequest();
        xhr.open('POST','http://10.12.10.100:8082/api/v1/collections/print/summary/DCR',true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onload = function(){
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                if(localStorage.getItem('summary_dcr_data') === null){ //if empty
                    localStorage.setItem('summary_dcr_data',JSON.stringify(data))
                }
                else{
                    localStorage.removeItem('summary_dcr_data')
                }
            }
        }
        xhr.send(JSON.stringify(tosend));
    }
</script>
@endsection
