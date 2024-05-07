@extends('layout.master')
@section('title', 'LGU Consumer Listing')
@section('content')

<p class="contentheader">LGU Consumer Listing</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td> 
                Report Per:
            </td>  
            <td> 
                <select name="reportPer"> 
                    <option value="area"> Area </option>
                    <option value="town"> Town </option>
                    <option value="route"> Route </option>
                </select>
            </td>
            <td> </td>
            <td> </td>
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
            <td colspan=4> 
                <button class="modal-button" style="height: 40px; float: right; margin-right: 8px;">  
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
