@extends('layout.master')
@section('title', 'Collection Report on VAT - PB')
@section('content')

<p class="contentheader">Collection Report on VAT - PB</p>
<div class="main">
    <table border=0 class="content-table">
        <tr>
            <td style="width: 15%;">
                Area Code:
            </td>
            <td>
                <input type="text" class="input-Txt" href="#town" name="area" placeholder="Select Area" readonly>
            </td>
            <td colspan=2>
                <table>
                    <tr>
                        <td>
                            <input type="checkbox" name="allAreas" class="checkcheck">
                        </td>
                        <td>
                            &nbsp; All Areas
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                Covered Period:
            </td>
            <td>
                <input type="date" name="coveredPeriodFrom">
            </td>
            <td>
                &nbsp; To:
            </td>
            <td>
                <input type="date" name="coveredPeriodTo">
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <button class="modal-button" style="float: right; height: 40px; margin-right: 10px;">
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
