@extends('layout.master')
@section('title', 'Renumber Consumer Sequence')
@section('content')

<style>
    #printBtn {
        margin-top: 7%;
        margin-right: 2.8%;
    }
</style>

<p class="contentheader"> Renumber Consumer Sequence </p>
<div class="main">
    <table class="content-table" style="margin-top: 5%;">
        <tr>
            <td style="width: 16%;">
                Account:
            </td>
            <td>
                <input type="text" id="accountInp" onclick="showConsumerAcct()" placeholder="Select Account" readonly>
                <input type="text" id="accountId" hidden>
            <td>
        </tr>
        <tr>
            <td>
                Current Sequence Number:
            </td>
            <td>
                <input type="number" id="cseqNumber" placeholder="Seq No" readonly>
            </td>
        </tr>
        <tr>
            <td>
                New Sequence Number:
            </td>
            <td>
                <input type="number" id="seqNumber" placeholder="Seq No" disabled>
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <button id="printBtn" onclick="renumber()" disabled>Renumber</button>
            </td>
        </tr>
    </table>
</div>

@include('include.modal.consumerAcctModal')

<script>
    function setConsAcct(a){
        var consId = a.id;
        var accName3 = a.childNodes[2].innerHTML;
        var consName = a.getAttribute('consName');
        var acctNo = a.getAttribute('accNo');
        console.log(a);
        var seqNo = a.getAttribute('class');
        if(accName3 != ''){
            aa = accName3.split('<br>');
        }
        document.querySelector("#cseqNumber").value = seqNo; 
        if(aa[0] != ' '){
            document.querySelector("#accountInp").value = acctNo + " - " + aa[0];
        }
        else{
            document.querySelector("#accountInp").value = acctNo + " - " + 'N/A';    
        }
        document.querySelector("#accountId").value = consId; 
        document.querySelector("#consAcct").style.display = "none";
        document.querySelector("#seqNumber").disabled = false;
    }

    var seqNo = document.querySelector("#seqNumber");
    seqNo.addEventListener("keyup", function(){
        if(seqNo.value !== ""){
            document.querySelector("#printBtn").disabled = false;
        } else {
            document.querySelector("#printBtn").disabled = true;
        }
    })

    function renumber(){
        var consId = document.querySelector("#accountId").value;
        var seqNumber = document.querySelector("#seqNumber").value;

        var toSend = new Object();
        var xhr = new XMLHttpRequest();

        toSend.cons_id = consId;
        toSend.seq_number = seqNumber;

        var toSendJSONed = JSON.stringify(toSend);
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var renumber = "{{route('set.seq.number')}}";
        xhr.open('POST', renumber, true);
        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);
        
        xhr.send(toSendJSONed);
        
        xhr.onload = function(){
            if(this.status == 200){
                Swal.fire({
                    title: 'Success!',
                    icon: 'success',
                    text: 'Successfully Renumbered Consumer Sequence',
                    type: 'success'
                }).then(function(){ 
                    location.reload();
                });
            }     
        }
    }
</script>
@endsection
