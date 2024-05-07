@extends('layout.master')
@section('title', 'Uncollected Bills per Town')
@section('content')

<p class="contentheader"> Uncollected Bills per Town</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td colspan=2> 
                <select name="UBPTS" style="width: 70%;"> 
                    <option value="forTheMonth"> For the Month </option>
                    <option value="forTheMonth"> For the Year </option>
                </select>
            </td>
        </tr>
        <tr> 
            <td> 
                Area From: 
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#town" name="areaFrom" placeholder="Select Area" readonly>
            </td>
            <td> 
                &nbsp; Area To:
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#town" name="areaTo" placeholder="Select Area" readonly>
            </td>
        </tr>
        <tr> 
            <td> 
                Consumer Status: 
            </td>
            <td> 
                <select name="consStat"> 
                    <option value="active"> Active </option>
                    <option value="inactive"> Inactive </option>
                </select>
            </td>
            <td> 
                &nbsp; For the Month of:
            </td>
            <td> 
                <input type="month" name="FTMO">
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
                &nbsp; <input type="text" name="prepByPos" placeholder="Position">
            </td>
        </tr>
        <tr> 
            <td> 
                Noted By:
            </td>
            <td> 
                <input type="text" name="notedBy" placeholder="Noted By">
            </td>
            <td colspan=2> 
                &nbsp; <input type="text" name="notedByPos" placeholder="Position">
            </td>
        </tr>
        <tr> 
            <td colspan=4> 
                <button class="modal-button" style="height: 40px; float: right; margin-right: 5px;"> 
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
        c[20].style.color="blue";
     }
</script>
@endsection
