@extends('layout.master')
@section('title', 'Daily Collection Control List')
@section('content')

<p class="contentheader">Daily Collection Control List</p>
<div class="main">
    <table style ="height:100px;" border="0" class="content-table">
        <tr>
            <td class="input-td">
                <input style ="width:30%;" type="text" class="input-Txt" href="#accNo" name="area" value="Teller" readonly>
            </td>
        </tr>
    </table>
    <table style ="height:500px;width:70%;" border="0" class="content-table">
      <tr>
        <td class = "thead">
            Date Collected:
            <p style="margin-left:10px;display:inline;font-weight:bold">March 18, 2021</p>
        </td>
      </tr>
      <tr>
      <td class = "thead">
            Teller/Collector:
            <input type = "text" id="route" style="margin-left:10px;display:inline;width:25%;" readonly>
        </td>
      </tr>
      <td class = "thead">
            Total Collected:
            <p style="margin-left:10px;display:inline;font-weight:bold">0.00</p>
        </td>
      </tr>
      <td class = "thead">
            Total TOR Used:
            <p style="margin-left:10px;display:inline;font-weight:bold">0</p>
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
        var b = document.querySelector('#drpbtn');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container').childNodes;
        c[9].style.color="blue";
     }
</script>
@endsection
