@extends('layout.master')
@section('title', 'Summary of Inventory of Active PB/Route')
@section('content')

<p class="contentheader"> Summary of Inventory of Active PB/Route </p>
<div class="main">
    <table class="content-table">
        <tr>
            <td> 
                Area:
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#town" name="area" placeholder="Select Area" readonly>
            </td>
            <td> 
                &nbsp; Town:
            </td>
            <td> 
                <input type="text"class="input-Txt" href="#town" name="town" placeholder="Select Town" readonly>
            </td>
        </tr>
        <tr> 
            <td> 
                Billing Month:
            </td>
            <td> 
                <input type="month" name="billMonth">
            </td>
            <td> 
                &nbsp; Route:
            </td>
            <td> 
            <input type="text"class="input-Txt" href="#route" name="route" placeholder="Select Route" readonly>
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
