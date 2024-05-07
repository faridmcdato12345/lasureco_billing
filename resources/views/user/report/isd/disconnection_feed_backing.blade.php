@extends('layout.master')
@section('title', 'Disconnection Notice FeedBacking')
@section('content')
<p class="contentheader">Disconnection Notice FeedBacking</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Disco Notice No.:
            </td>
            <td  class="input-td">
                <input type="text" value="000000000072" readonly>
            </td>
            <td style="width:15%" class="thead">
                Account No.:
            </td>
            <td class="input-td">
                <input type="text" class = "input-Txt" href="#accNo" name="area" value = "43-4301-0011-Acmad Gino" placeholder="Select Account" readonly>
            </td>
        </tr>
        <tr>
            <td  class="thead">
                Address:
            </td>
            <td class="input-td">
            <input type="text" value = "01 - Marawi City Wide and"  readonly>
            </td>
            <td  class="thead">
                Disconnector:
            </td>
            <td  class="input-td">
                <select name="Sort">
                    <option selected>Select Name</option>
                    <option value="volvo">dasdsadsa</option>
                    <option value="saab">Unposted</option>
                </select>
            </td>
        </tr>
        <tr>
            <td  class="thead">
                Date Generated:
            </td>
            <td class="input-td">
                <input type="month" name = "month">
            </td>
            <td  class="thead">
                Disco Date:
            </td>
            <td class="input-td">
                <input type="month" name = "month">
            </td>
        </tr>
        <tr>
            <td class="thead">
            Remarks:
            </td>
            <td>
               <textarea style="margin-top:70px;width:250px;height:100px;" placeholder="Remarks"></textarea>
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
