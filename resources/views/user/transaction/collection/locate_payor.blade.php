@extends('layout.master')
@section('title', 'Locate Payor')
@section('content')

<p class="contentheader">Locate Payor</p>
<div class="main">
    <table style ="width:80%;height:400px;" border="0" class="content-table">
        <tr>
            <td style="width:10%;" class="thead">
                Account Number:
            </td>
            <td class="input-td" style="width:30%;">
                <input type="text" name="area" class="input-Txt" href="#accNo" value="10-1001-0002" readonly><br>
                <p style="font-family:italic;font-size:1.2vw;display:flex;position:absolute;">
                <br>Lombos Mithaman</p>
            </td>
            <td class="td-btn">
            </td>
        </tr>
        <tr>
            <td style="width:20%;" class="thead">
                Payor Account:
            </td>
            <td class="input-td" style="width:30%;">
                <input type="text" name="area" class="input-Txt" href="#route" value="10-1001-0002" readonly><br>
                <p style="font-family:italic;font-size:1.2vw;display:flex;position:absolute;">
                <br>Esnaira Montilan</p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <button style="height:40px;" id="printBtn">Print</button>
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
</script>
@endsection
