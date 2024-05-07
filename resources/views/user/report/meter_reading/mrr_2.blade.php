@extends('layout.master')
@section('title', 'MMR Inquiry - 2')
@section('content')
<p class="contentheader">MMR Inquiry - 2</p>
<div class="main">
<br><br>
    <table border="0" style = "margin-left:70px;height:350px;width:80%;" class="content-table">
        <tr>
            <td class="thead">
               Month/Year:
            </td>
            <td class="input-td">
                <input type="month" name="month">
            </td>
            <td class="thead">
               Date Read:
            </td class="input-td">
            <td class="input-td">
                <input type="text" name="town" value= "March 19,2021" readonly>
            </td>
        </tr>
        <tr>
            <td class="thead">
               Area:
            </td>
            <td class="input-td">
                <input class="input-Txt" href="#accNo" type="text" name="town" value= "03 - Marawi City and">
            </td>
            <td class="thead">
               Town:
            </td class="input-td">
            <td class="input-td">
                <input class="input-Txt" href="#town" type="text" name="town" value= "03 - Marawi City and">
            </td>
        </tr>
        <tr>
            <td class="thead">
                Route:
            </td>
            <td class="input-td">
                <input class="input-Txt" href="#route" type="text" name="town" value="04 - MSU" readonly>
            </td>
            <td class="thead">
                Book:
            </td>
            <td class="input-td">
                <input  type="number" name="Book" value="1">
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <button style = "height:40px;" id="printBtn">Print</button>
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
        c[1].style.color="blue";
    }
</script>
@endsection
