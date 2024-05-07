@extends('layout.master')
@section('title', 'Print Delinquent Consumers')
@section('content')

<p class="contentheader">Print Delinquent Consumers</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td> 
                Route:
            </td>
            <td> 
                <input type="text" class="input-Txt" href="#route" name="route" placeholder="Select Route" readonly>
            </td>
            <td> 
                &nbsp; Billing Month: 
            </td>
            <td> 
                <input type="month" name="billingMonth">
            </td> 
        </tr>
        <tr> 
            <td> 
                Consumer Type: 
            </td>
            <td> 
                <select name="consType" style="border-radius: 3px;"> 
                    <option value="industrial"> Industrial </option>
                    <option value="residential"> Residential </option>
                </select>
            </td>
            <td> 
                &nbsp; Number of Results:
            </td>
            <td> 
                <input type="number" name="resultsNum" placeholder="0">
            </td>
        </tr>
        <tr> 
            <td> 
                Minimum Amount: 
            </td>
            <td> 
                <input type="number" name="minAmnt" placeholder="0.00">
            </td>
            <td colspan=2> 
                &nbsp; 
                <table> 
                    <tr>
                        <td> 
                            &nbsp; Sort:
                        </td> 
                        <td> 
                            &nbsp; <input type="radio" name="sortPDC" class="radio" value="amount" checked>
                        </td>
                        <td> 
                            &nbsp; Amount
                        </td>
                        <td> 
                            &nbsp; <input type="radio" name="sortPDC" class="radio" value="bills">
                        </td>
                        <td> 
                            &nbsp; Bills
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr> 
            <td colspan=4>
                <div style="float: right; margin-right: 8px;"> 
                    <table style="width: 200px; text-align: right;">
                        <tr>
                            <td>
                                <button class="modal-button" style="height: 40px; background-color: rgb(61, 65, 68); color: white;"> 
                                    Process
                                </button>
                            </td>
                            <td>
                                <button class="modal-button" style="height: 40px;"> 
                                    Print
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
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
