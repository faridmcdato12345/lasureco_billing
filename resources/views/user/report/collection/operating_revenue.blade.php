@extends('layout.master')
@section('title', 'Operating Revenue')
@section('content')

<p class="contentheader">Operating Revenue</p>
<div class="main">
    <table class="content-table">
        <tr> 
            <td> 
                Area Code: 
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#route" name="town" placeholder="Select Area" readonly>
            </td>
            <td> 
                &nbsp; Consumer Type:
            </td>
            <td> 
                <select name="consType" style="border-radius: 3px;"> 
                    <option value="All Consumer Types"> All Consumer Types </option>
                    <option value="Other Consumer Types"> Other Consumer Types </option>
                </select>
            </td>
        </tr>
        <tr> 
            <td> 
                Month:
            </td>
            <td> 
                <input type="month" name="month">
            </td>
        </tr>
        <tr> 
            <td> 
                Prepared By:
            </td>
            <td> 
                <input type="text" name="prepBy" placeholder="Prepared By">
            </td>
            <td colspan=2> 
                &nbsp; <input type="text" name="prepByPos" placeholder="Designation">
            </td>
        </tr>
        <tr> 
            <td> 
                &nbsp; Noted By:
            </td> 
            <td> 
                <input type="text" name="notedBy" placeholder="Noted By">
            </td>
            <td colspan=2> 
                &nbsp; <input type="text" name="notedByPos" placeholder="Designation">
            </td>
        </tr>
        <tr> 
            <td colspan=4> 
                <button class="modal-button" style="float: right; margin-right: 7px; height: 40px;"> 
                    Print
                </button>
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
        c[16].style.color="blue";
     }
</script>
@endsection
