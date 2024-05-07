@extends('layout.master')
@section('title', 'Summary of Collected Sr. Citizen per Town')
@section('content')

<p class="contentheader">Summary of Collected Sr. Citizen per Town</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td> 
                Billing Month From:
            </td>
            <td> 
                <input type="month" name="billMonFrom">  
            </td>
            <td> 
                &nbsp; Billing Month To:
            </td>
            <td> 
                <input type="month" name="billMonTo">
            </td>
        </tr>
        <tr> 
            <td> 
                Collection Date from:
            </td>
            <td> 
                <input type="date" name="collDateFrom"> 
            </td>
            <td colspan=2> 
                &nbsp; <input type="date" name="collDateTo">
            </td>
        </tr>
        <tr> 
            <td> 
                Print Report:
            </td>
            <td> 
                <select name="prinReport"> 
                    <option value="byDateRage"> By Date Range </option>
                    <option value="others"> Others </option>
                </select>
            </td>
            <td colspan=2> 
                <table> 
                    <tr> 
                        <td> 
                            &nbsp; <input type="radio" class="radio" name="SCSCpT" value="Subsidy" checked>
                        </td>
                        <td> 
                            &nbsp; Subsidy
                        </td>
                        <td> 
                            &nbsp; <input type="radio" class="radio" name="SCSCpT" value="Discount">
                        </td>
                        <td> 
                            &nbsp; Discount
                        </td>
                    </tr>
                </table>
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
        c[16].style.color="blue";
     }
</script>
@endsection
