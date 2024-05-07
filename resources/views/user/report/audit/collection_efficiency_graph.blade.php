@extends('layout.master')
@section('title', 'Collection Efficiency - Graph')
@section('content')

<p class="contentheader">Collection Efficiency - Graph</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                For the Month of:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
        </tr>
        <tr>
            <td  class="thead">
            Prepared By:
            </td>
            <td class="input-td">
            <input type="text" name="area" placeholder="Prepared By"  readonly>
            </td>
            <td colspan=2>
            <input style="width:70%;"class="input-Txt" hfre="#route" type="text" name="area" placeholder="Position" readonly>
            </td>
        </tr>
        <tr>
            <td  class="thead">
            Checked By:
            </td>
            <td class="input-td">
            <input type="text" name="area" placeholder="Checked By"  readonly>
            </td>
            <td colspan=2>
            <input style="width:70%;" type="text" name="area" placeholder="Position" readonly>
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
        c[17].style.color="blue";
    }
</script>
@endsection
