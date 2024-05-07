@extends('layout.master')
@section('title', 'Sales Closing')
@section('content')

<p class="contentheader">Closing of Sales</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td style="width: 15%;"> 
                Closing Period:
            </td>
            <td> 
                <input type="month" name="closePeriod" style="width: 60%;">
            </td>
        </tr>
        <tr> 
            <td colspan=4> 
                <button class="modal-button" style="height: 40px; float: right; margin-right: 5px;"> 
                    Proceed
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
