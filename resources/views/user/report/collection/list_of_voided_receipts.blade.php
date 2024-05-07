@extends('layout.master')
@section('title', 'List of Voided Receipts')
@section('content')

<p class="contentheader">List of Voided Receipts</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td style="width: 18%;">
                Sub-Office:
            </td>
            <td>
                <input type="text" class="input-Txt" href="#town" name="subOffice" placeholder="Select Sub-Office" readonly>
            </td>
            <td colspan=2>
                <table>
                    <tr>
                        <td>
                            &nbsp; <input type="checkbox" name="allSubOffice" class="checkcheck">
                        </td>
                        <td>
                            &nbsp; All Sub Offices?
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                Collection Date From:
            </td>
            <td>
                <input type="date" name="collDateFrom">
            </td>
            <td>
                &nbsp; To:
            </td>
            <td>
                <input type="date" name="collDateTo">
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
        var c = document.querySelector('.dropdown-container2').childNodes;
        c[9].style.color="blue";
     }
</script>
@endsection
