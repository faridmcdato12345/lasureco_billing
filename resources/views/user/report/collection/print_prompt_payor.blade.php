@extends('layout.master')
@section('title', 'Print Prompt Payor')
@section('content')

<p class="contentheader">Print Prompt Payor</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td>
                Route:
            </td>
            <td>
                <input type="text" class="input-Txt" href="#town" name="area" placeholder="Select Area" readonly>
            </td>
            <td>
                &nbsp; Billing Month:
            </td>
            <td>
                <input type="month" name="billMonth">
            </td>
        </tr>
        <tr>
            <td>
                Consumer Type:
            </td>
            <td>
                <select name="consType">
                    <option name="industrial"> Industrial </option>
                    <option name="commercial"> Commercial </option>
                </select>
            </td>
            <td>
                &nbsp; Number of Results:
            </td>
            <td>
                <input type="number" name="resNum" placeholder="0">
            </td>
        </tr>
        <tr>
            <td>
                Minimum Amount:
            </td>
            <td>
                <input type="number" name="minAmnt" placeholder="0">
            </td>
            <td colspan=2>
                <table border=0 style="margin-left: 8px;">
                    <tr>
                        <td style="width: 60px;">
                            Sort:
                        </td>
                        <td>
                            <input type="radio" name="amount" class="radio" checked>
                        </td>
                        <td>
                            Amount
                        </td>
                        <td style="width: 30px;"> </td>
                        <td>
                            <input type="radio" name="amount" class="radio">
                        </td>
                        <td>
                            Days
                        </td>
                    </tr>
                </table>
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
