@extends('layout.master')
@section('title', 'KWH Meter Installed')
@section('content')

<p class="contentheader">KWH Meter Installed</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Area:
            </td>
            <td class="input-td">
                <input class="input-Txt" hfre="#accNo" type="text" name="area" readonly>
            </td>
        </tr>
        <tr>
            <td  class="thead">
               Town:
            </td>
            <td class="input-td">
            <input class="input-Txt" hfre="#accNo" type="text" name="area" readonly>
            </td>
            <td style="width:15%;" class="thead">
               KWH Meter Brand:
            </td>
            <td style="width:35%;" class="input-td">
                <select name="Sort">
                    <option value="volvo">Both</option>
                    <option value="saab">Unposted</option>
                </select>
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
