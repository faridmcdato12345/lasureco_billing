@extends('layout.master')
@section('title', 'System Loss per District')
@section('content')

<p class="contentheader">System Loss per District</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>

            <td  style="width:15%" class="thead">
                Year/Month:
            </td>
            <td class="input-td">
                <input style="width:50%" type="month" name="month">
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
