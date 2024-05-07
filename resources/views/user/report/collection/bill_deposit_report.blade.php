@extends('layout.master')
@section('title', 'Bill Depsosit Report')
@section('content')

<p class="contentheader">Bill Deposit Report</p>
<div class="main">
    <table class="content-table">
        <tr style="height: 10%;"> 
            <td> </td>
        </tr>
        <tr style="height: 20%;"> 
            <td> 
                <select name="sort"> 
                    <option value="byUserDate"> By User Date </option>
                    <option value="other"> Other </option>
                </select>
            </td>
        </tr>
        <tr style="height: 20%;"> 
            <td> 
                Turn-On Date: 
            </td>
            <td> 
                <input type="date" name="dateFrom">
            </td>
            <td> 
                &nbsp; <input type="date" name="dateTo">
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
        c[16].style.color="blue";
     }
</script>
@endsection
