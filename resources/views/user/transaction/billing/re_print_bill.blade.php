@extends('layout.master')
@section('title', 'Re - Print Bill')
@section('content')
<p class="contentheader">Re - Print Bill</p>
<div class="main">
    <br><br>
    <table border="0" style ="margin-left:70px;width:80%;height:350px;" class="content-table">
        <tr>
        <td>
            <select style="color:black" name="" selected="selected" onchange= "selected(this)" id="select">
            <option style="color:black" value="by_sequence">by sequence</option>
            <option style="color:black" value="by_account">by account</option>
            </select>
        </td>
        </tr>
        <tr>
        <td class="thead">
                Billing Period:
            </td>
            <td class="input-td">
                <input id="nobtninput"  style="color:black;" onfocusout="datesend(this)" class = "dateP" type="month">
            </td>
            <td class="thead">
                Route Code:
            </td>
            <td class="input-td">
                <input id="routeID" style="color:black;" type="text" onclick="showRoutes();" name="route" placeholder="Select Route" readonly>
            </td>
        </tr>
        <tr id="seqFrom">
            <td class="thead" >
                Sequence No. From:
            </td>
            <td class="input-td">
                <input type="number" style="color:black" id = "accNoID" onfocusout="tdclick1(this)"  name="account_number">
            </td>
        </tr>
        <tr id="seqTo">
            <td>
               Sequence No. To:
            </td>
            <td class="input-td">
                <input type="number" style="color:black" id = "accNoID2" onfocusout="tdclick2(this)"  name="account_number">
            </td>
        </tr>
        <tr id="showAcct">
        </tr>
        <tr >
            <td colspan="6">
            <button style ="height:40px;float:right" class="downloading btn btn-light" onclick="sendDataPowerBill()" >Print</button>
            </td>
        </tr>
    </table>
</div>
@include('include.modal.routemodal')
@include('include.modal.consumerAcctModal')
<script>

var reprintTosend = new Object();
/* ----------------------------------------------- */
/*------------------------------------------------END MODAL ROUTE ---------------------------------------------------------*/
/* -----------------------------------------------paginate click-----------------------------------------------------------*/
/* ----------------------------------------------- END Paginate-----------------------------------------------------*/
/* -----------------------------------------------tdclick-----------------------------------------------------------*/
    function setRoute(rID){
        console.log(rID);
        document.querySelector('#routeCodes').style.display = "none";
        document.querySelector('#routeID').value = rID.getAttribute('name');
        reprintTosend.Route_Id = rID.id;
    }
/*---------------------------------------END PAGINATE------------------------------------------------- */
function selected(a){
    var output = '';
    var output1 = '';
   if(a.value == 'by_account'){
       output += '<td>' +
               'Account No.:' +
            '</td>' +
            '<td class="input-td">' +
            '<input type="text" style="color:black" id="consumerN" onclick="showConsumerAcct()" name="account_number" readonly>' +
            '</td>';

            document.querySelector('#showAcct').innerHTML = output;
            document.querySelector('#seqFrom').innerHTML = '';
            document.querySelector('#seqTo').innerHTML = '';
   }
   else{
    output += '<td>' +
               'Sequence No. From:' +
            '</td>' +
            '<td class="input-td">' +
            ' <input type="number" style="color:black" id = "accNoID" onfocusout="tdclick1(this)"  name="account_number">' +
            '</td>';
    output1 += '<td>' +
               'Sequence No. To:' +
            '</td>' +
            '<td class="input-td">' +
            ' <input type="number" style="color:black" id = "accNoID2" onfocusout="tdclick2(this)"  name="account_number">' +
            '</td>';

            document.querySelector('#showAcct').innerHTML = '';
            document.querySelector('#seqFrom').innerHTML = output;
            document.querySelector('#seqTo').innerHTML = output1;
   }
   reprintTosend.selected = a.value;
}
/* ----------------------------------------------------------------------------------- */

function tdclick1(seq1) {
        reprintTosend.seq_from = seq1.value;
    }
/*----------------------------------------------*/

/* ----------------- ENd Pagination of Account No. --------------------*/
/*-----------------------------------------------------------------------------*/
function tdclick2(seq2) {
        reprintTosend.seq_to = seq2.value;
        console.log(document.querySelector('#select').value);
        if(typeof reprintTosend.selected == 'undefined'){
            var sel = document.querySelector('#select').value;
           reprintTosend.selected = sel;
        }
        console.log(reprintTosend);
    if(parseInt(reprintTosend.seq_from) > parseInt(reprintTosend.seq_to)){
        Swal.fire({
                    title: 'Error!',
                    text: '"Sequence Number To" must be less than to "Sequence Number From"',
                    icon: 'error',
                    confirmButtonText: 'close'
                });
        document.querySelector('.downloading').disabled = true;       
    }
    else{
        document.querySelector('.downloading').disabled = false; 
    }
}
/*-----------------------------------------------------------------------------*/
function setConsAcct(cons){
    delete reprintTosend.seq_to;
    delete reprintTosend.seq_from;
    document.querySelector('#consAcct').style.display = "none";
    var accName3 = cons.childNodes[0].innerHTML;
    document.querySelector('#consumerN').value = accName3;
    var acc = accName3.replaceAll('-','');
    console.log(acc)
    reprintTosend.account = acc;
}
/*-----------------------------------------------------------------------------*/
function datesend(d){
    reprintTosend.Date = d.value;
}
/*-----------------------------------------------------------------------------*/
function sendDataPowerBill(){
        Swal.fire({
            title: 'Do you want to continue?',
            showDenyButton: true,
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                var xhr = new XMLHttpRequest();
                var reprintPB = "{{route('reprint.powerbill')}}";
                xhr.open('POST',reprintPB,true);
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Content-Type", "application/json");
                xhr.onload = function(){
                    if(this.status == 200){
                        var data = JSON.parse(this.responseText);
                        localStorage.setItem("data3",JSON.stringify(data));
                        var url = "{{route('powerbill')}}";
                        window.open(url);
                    }
                    else if(this.status == 422){
                        Swal.fire({
                            title: 'Error!',
                            text: '"No Record Found"',
                            icon: 'error',
                            confirmButtonText: 'close'
                        });
                    }
                }
                xhr.send(JSON.stringify(reprintTosend));
            }
        });
}
/*-----------------------------------------------------------------------------*/
</script>
@endsection
