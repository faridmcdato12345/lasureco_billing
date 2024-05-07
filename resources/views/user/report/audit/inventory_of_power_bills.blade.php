@extends('layout.master')
@section('title', 'Inventory of Power Bills')
@section('content')

<p class="contentheader"> Inventory of Power Bills </p>
<div class="main">
    <table class="content-table">
        <tr>
            <td> 
                Area:
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#area" name="area" placeholder="Select Area" readonly>
            </td>
            <td> 
                &nbsp; Town:
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#town" name="town" placeholder="Select Town" readonly>
            </td>
        </tr>
        <tr> 
            <td> 
                Status:
            </td> 
            <td> 
                <select name="status"> 
                    <option value="active"> Active </option>
                    <option value="inactive"> Inactive </option>
                </select>
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
                Checked By:
            </td> 
            <td> 
                <input type="text" name="checkBy" placeholder="Checked By">
            </td>
            <td colspan=2> 
                &nbsp; <input type="text" name="checkByPos" placeholder="Position">
            </td>
        </tr>
        <tr>
            <td> 
                Approve By:
            </td> 
            <td> 
                <input type="text" name="appBy" placeholder="Approved By">
            </td>
            <td colspan=2> 
                &nbsp; <input type="text" name="appByPos" placeholder="Position">
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
