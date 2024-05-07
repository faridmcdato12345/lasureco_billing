@extends('layout.master')
@section('title', 'Summary of Voided O.R')
@section('content')

<p class="contentheader">Summary of Voided O.R</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td style="width:25%;" class="thead">
                Cashier:
            </td>
            <td class="input-td">
                <input style ="width:70%;" type="text" class="input-Txt" href="#route" name="area" value="Sitti Khairanie M. Magomnang" readonly>
            </td>
        </tr>
        <tr>
        <td class="thead">
            Collection Date:
        </td>
        <td class="input-td">
                <input style ="width:60.7%;" type="date" id="collectionDate">
        </td>
        </tr>
        <tr>
            <td class = "thead">
            </td>
            <td class = "thead">
            </td>
            <td class = "thead">
            Position
            </td>
        </tr>
        <tr>
            <td class = "thead">
                Prepared By:
            </td>
            <td class = "input-td">
                <input style = "width:60%;" type = "text" placeholder = "Prepared By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td class = "thead">
                Checked By:
            </td>
            <td class = "input-td">
                <input style = "width:60%;" type = "text" placeholder = "Checked By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td class = "thead">
                Noted By:
            </td>
            <td class = "input-td">
                <input style = "width:60%;" type = "text" placeholder = "Received By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <button style="width:70px;margin-top:30px;height:40px;" onclick="voidedOR();" id="printBtn" >Print</button>
            </td>
        </tr>
    </table>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container').childNodes;
        c[9].style.color="blue";
     }
     function voidedOR(){
        var cDate = document.querySelector('#collectionDate').value;
        var collectionDate = new Object();
        collectionDate.date_voided= cDate;
        if(typeof collectionDate.date_voided != 'undefined'){
            var xhr = new XMLHttpRequest();
            xhr.open('POST','http://10.12.10.100:8082/api/v1/collections/print/summary/voided',true);
            xhr.setRequestHeader("Accept", "application/json");
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onload = function(){
                if(this.status == 200){
                    var data = JSON.parse(this.responseText);
                }
            }
            xhr.send(JSON.stringify(collectionDate));
        }
     }   
</script>
@endsection
