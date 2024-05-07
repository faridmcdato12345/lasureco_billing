@extends('layout.master')
@section('title', 'Cashiers DCR')
@section('content')

<p class="contentheader">Cashier's DCR</p>
<div class="main">
    <table class="content-table">
        <tr> 
            <td style="width: 15%;"> 
                Collection Date:
            </td>
            <td> 
                <input type="date" name="CollDate">
            </td>
            <td></td>
        </tr>
        <tr> 
            <td> 
                Prepared By:
            </td>
            <td>  
                <input type="text" name="prepBy" placeholder="Prepared By">
            </td>
            <td> 
                &nbsp; <input type="text" name="prepByPos" placeholder="Position">
            </td>
        </tr>
        <tr> 
            <td> 
                Checked By:
            </td>
            <td>  
                <input type="text" name="checkBy" placeholder="Checked By">
            </td>
            <td> 
                &nbsp; <input type="text" name="checkByPos" placeholder="Position">
            </td>
        </tr>
        <tr> 
            <td> 
                Approved By:
            </td>
            <td>  
                <input type="text" name="appBy" placeholder="Approved By">
            </td>
            <td> 
                &nbsp; <input type="text" name="appByPos" placeholder="Position">
            </td>
        </tr>
        <tr> 
            <td colspan=4> 
                <button class="modal-button" style="float: right; margin-right: 5px; height: 40px;"> 
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
