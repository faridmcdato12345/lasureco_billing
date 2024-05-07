@extends('layout.master')
@section('title', 'Summary of Changed Meter')
@section('content')

<p class="contentheader">Summary of Changed Meter</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Area:
            </td>
            <td class="input-td">
                <input type="text" class = "input-Txt" href="#accNo" name="area" placeholder="Select Area" readonly>
            </td>
            <td  class="thead">
                Town:
            </td>
            <td class="input-td">
                <input type="text" class = "input-Txt" href="#town" name="area" placeholder="Select Town" readonly>
            </td>
        </tr>
        <tr>
            <td  class="thead">
                Change Date From:
            </td>
            <td class="input-td">
                <input type="month" name = "month">
            </td>
            <td  class="thead">
                To:
            </td>
            <td class="input-td">
                <input type="month" name = "month">
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
            Noted By:
            </td>
            <td class="input-td">
            <input type="text" name="area" placeholder="Noted By"  readonly>
            </td>
            <td colspan=2>
            <input style="width:70%;" type="text" name="area" placeholder="Position" readonly>
            </td>
        </tr>
        <tr>
            <td  class="thead">
            Approved By:
            </td>
            <td class="input-td">
            <input type="text" name="area" placeholder="Approved By"  readonly>
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
        c[25].style.color="blue";
    }
</script>
@endsection
