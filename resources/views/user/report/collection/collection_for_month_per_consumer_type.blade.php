@extends('layout.master')
@section('title', 'Collection for Month per Consumer Type')
@section('content')

<p class="contentheader">Collection for Month per Consumer Type</p>
<div class="main">
    <table border=0 class="content-table">
        <tr>
            <td>
                Area Code:
            </td>
            <td>
                <input type="text" class="input-Txt" href="#town" placeholder="Select Area" readonly>
            </td>
            <td>
                <table>
                    <tr>
                        <td style="width: 30px;">
                            <input type="checkbox" name="allAreas" class="checkcheck">
                        </td>
                        <td>
                            &nbsp; All Areas?
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                Billing Period:
            </td>
            <td>
                <input type="month" name="billingPeriod">
            </td>
            <td> </td>
        </tr>
        <tr>
            <td>
                Date Collected:
            </td>
            <td>
                <input type="date" name="dateCollFrom">
            </td>
            <td>
                <input type="date" name="dateCollTo">
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <button style="float: right;
                               border: none;
                               border-radius: 5px;
                               color: rgb(23, 108, 191);
                               margin-right: 12px;">
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
