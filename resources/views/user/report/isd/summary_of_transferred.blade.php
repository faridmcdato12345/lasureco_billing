@extends('layout.master')
@section('title', 'Summary of Transferred')
@section('content')

<p class="contentheader">Summary of Transferred</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Area:
            </td>
            <td class="input-td">
                <input class="input-Txt" href="#accNo" type="text" name="area" readonly>
            </td>
            <td  class="thead">
               Town:
            </td>
            <td class="input-td">
            <input class="input-Txt" href="#accNo" type="text" name="area" readonly>
            </td>
        </tr>
        <tr>
            <td  class="thead">
               Transferred Date From:
            </td>
            <td class="input-td">
            <input type="month" name="month">
            </td>
            <td  class="thead">
            To:
            </td>
            <td class="input-td">
            <input type="month" name="month">
            </td>
        </tr>
        <tr>
            <td  class="thead">
            Printed By:
            </td>
            <td class="input-td">
            <input type="text" name="area" placeholder="Printed By"  readonly>
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
