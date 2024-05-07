@extends('layout.master')
@section('title', 'Actual VAT Collection')
@section('content')

<p class="contentheader">Actual VAT Collection</p>
<div class="main">
    <table class="content-table">
        <tr> 
            <td> 
                Billing Period:
            </td>
            <td> 
                <input type="date" name="billingPeriod">
            </td>
            <td> 
                &nbsp; VAT Type:
            </td>
            <td> 
                <select name="VATType"> 
                    <option value="generation"> Generation </option>
                    <option value="others"> Others </option>
                </select>
            </td>
        </tr>
        <tr> 
            <td> </td>
            <td> </td>
            <td colspan=2> 
                <table> 
                    <tr> 
                        <td> 
                            &nbsp; <input type="checkbox" name="allTypes" class="checkcheck">
                        </td>
                        <td> 
                            &nbsp; All Types
                        </td>
                    </tr>
                </table>
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
