@extends('layout.master')
@section('title', 'Print Meter Reading Sheet')
@section('content')
<div id = "users">
</div>
<p class="contentheader">Print Meter Reading Sheet</p>
<div class="main">
<br><br>
    <table class="content-table" style="height: 400px;">

        <tr>
            <td class="thead">
                Route Code:
            </td>
            <td  class="input-td">
                <input id="routeID" type="text" class="input-Txt" href="#route" name="route" placeholder="Select Route" readonly>
            </td>
            <td class="thead">
                Number of Consumer:
            </td>
            <td class="input-td">
                <input type="number" id="consumer" name="consumer" class="consumer" placeholder="0">
            </td>
        </tr>
        <tr>
            <td class="thead">
                Meter Reader:
            </td>
            <td class="input-td">
                <input name="meterID" type="text" class="input-Txt" id="mR" href="#meterreader" name="meterReader" placeholder="Select Acc. No." readonly>
            </td>
            <td>
                &nbsp; Billing Period:
            </td>
            <td>
                <input id="billingPeriod" type="month" name="billingPeriod">
            </td>
        </tr>
        <tr>
            <td>
                <label style="color:white;" class="container">With Previous Reading
                <input type="checkbox" id = "check1" value="0">
                <span class="checkmark"></span>
                </label>
            </td>
            <td>
                <label style="color:white;" class="container">Without Previous Reading
                <input id="check" type="checkbox" value="1">
                <span class="checkmark"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td id = "print" colspan="6">

            </td>
        </tr>
    </table>
    <!-- </form> -->
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container').childNodes;
        c[1].style.color="blue";
        }
/* -----------------------------------API START-------------------------------------------------*/
</script>
@endsection
