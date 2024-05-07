@extends('layout.master')
@section('title', 'Consumer List per Transformer')
@section('content')

<p class="contentheader">Consumer List per Transformer</p>
<div class="main">
    <table style ="height:250px;" border="0" class="content-table">
        <tr>
            <td style = "width:20%" class="thead">
                Transformer Code:
            </td>
            <td class="input-td">
            <input style = "width:50%" type="text" class = "input-Txt" href="#accNo" name="month">
            </td>
        </tr>
    </table>
        <table style ="width:80%;height:100px;" border="0" class="content-table">
        <tr>
            <td class = "thead">
                Prepared By:
            </td>
            <td class = "input-td">
                <input type = "text" placeholder = "Prepared By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <button style="width:70px;margin-top:30px;height:40px;" id="printBtn" >Print</button>
            </td>
        </tr>
    </table>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn2');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container2').childNodes;
        c[21].style.color="blue";
    }
</script>
@endsection
