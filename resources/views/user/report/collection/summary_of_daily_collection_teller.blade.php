@extends('layout.master')
@section('title', 'Summary of Daily Collection - Teller')
@section('content')

<p class="contentheader">Summary of Daily Collection - Teller</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td>
                <select name="dailyCollector">
                    <option value="teller">Teller</option>
                    <option value="saab">Saab</option>
                    <option value="mercedes">Mercedes</option>
                    <option value="audi">Audi</option>
                </select>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>
                Date Collected:
            </td>
            <td>
                <input type="date" name="dateCollFrom">
            </td>
            <td>
                &nbsp; To:
            </td>
            <td>
                <input type="date" name="dateCollTo">
            </td>
        </tr>
        <tr>
            <td>
                Prepared By:
            </td>
            <td>
                <input type="text" name="preparedBy" placeholder="Prepared By">
            </td>
            <td colspan=2>
                &nbsp; <input type="text" name="prepPos" placeholder="Position" style="width: 95%;">
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
                &nbsp; <input type="text" name="notedPrep" placeholder="Position" style="width: 95%;">
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <button style="height: 40px; float: right; margin-right: 8px;" class="modal-button">
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
        c[9].style.color="blue";
     }
</script>
@endsection
