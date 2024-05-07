@extends('layout.master')
@section('title', 'Uncollected Bills per Consumer')
@section('content')

<p class="contentheader"> Uncollected Bills per Consumer </p>
<div class="main">
    <table class="content-table">
        <tr>
            <td colspan=2> 
                <select name="UBPCS" style="width: 70%;"> 
                    <option value="forTheMonth"> For the Month </option>
                    <option value="forTheYear"> For the Year </option>
                </select>
            </td>
        </tr>
        <tr> 
            <td> 
                Route From: 
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#route" name="routeFrom" placeholder="Select Route" readonly>
            </td>
            <td> 
                &nbsp; Route To:
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#route" name="routeTo" placeholder="Select Route" readonly>
            </td>
        </tr>
        <tr> 
            <td> 
                Consumer Status:
            </td>
            <td> 
                <select name="consStat"> 
                    <option value="active"> Active </option>
                    <optionv value="inactive"> Inactive </option>
                </select>
            </td>
            <td colspan=2> 
                <table> 
                    <tr> 
                        <td> 
                            &nbsp; <input type="checkbox" name="incSpecialAccs" class="checkcheck">
                        </td>
                        <td> 
                            &nbsp; Include Special Accounts
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr> 
            <td> 
                For the Month of:
            </td>
            <td> 
                <input type="month" name="FTMO">
            </td>
            <td> 
                &nbsp; Cutoff Date:
            </td>
            <td> 
                <input type="date" name="cutOffDate">
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
