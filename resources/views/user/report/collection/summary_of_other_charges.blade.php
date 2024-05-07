@extends('layout.master')
@section('title', 'Summary of Other Charges')
@section('content')

<p class="contentheader">Summary of Collected/Billed Other Costs-TSF Rent</p>
<div class="main">
    <table border=0 class="content-table">
        <tr>
            <td colspan=4>
                <table border=0>
                    <tr>
                        <td style="width: 30px;">
                            <input type="radio" class="radio" name="chargeType" value="Sales" checked>
                        </td>
                        <td>
                            Sales
                        </td>
                        <td style="width: 30px;"> </td>
                        <td style="width: 30px;">
                            <input type="radio" class="radio" name="chargeType" value="Collections">
                        </td>
                        <td>
                            Collections
                        </td>
                    </tr>
                </table>
            </td>
        <tr>
            <td>
                Area Code:
            </td>
            <td>
                <input type="text" class="input-Txt" name="area" href="#town" placeholder="Select Area" readonly>
            </td>
            <td style="width: 15%;">
                &nbsp; Consumer Type:
            </td>
            <td>
                <select name="consType" style="border-radius: 3px; width: 74%;">
                    <option name="allCons"> All Consumer Types </option>
                    <option name="otherCons"> Other Consumer Types </option>
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
                &nbsp; <input type="text" name="prepByPos" placeholder="Position" style="width: 80%;">
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
                &nbsp; <input type="text" name="checkByPos" placeholder="Position" style="width: 80%;">
            </td>
        </tr>
        <tr>
            <td>
                Approved By:
            </td>
            <td>
                <input type="text" name="appBy" placeholder="Approved By">
            </td>
            <td colspan=2>
                &nbsp; <input type="text" name="appByPos" placeholder="Position" style="width: 80%;">
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
        var c = document.querySelector('.dropdown-container').childNodes;
        c[9].style.color="blue";
     }
</script>
@endsection
