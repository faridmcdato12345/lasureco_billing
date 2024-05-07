@extends('layout.master')
@section('title', 'Collected Surcharge Report')
@section('content')

<p class="contentheader">Report On Collected Surcharge</p>
<div class="main">
    <table class="content-table">
        <tr>
            <td style="width: 20%;">
                Sub-Office:
            </td>
            <td>
                <input type="text" class="input-Txt" href="#town" name="subOffice" placeholder="Select Sub-Office" readonly>
            </td>
            <td>
                <table>
                    <tr>
                        <td>
                            &nbsp; <input type="checkbox" name="perSubOffice" class="checkcheck">
                        </td>
                        <td>
                            &nbsp; Per Sub-Office?
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                Month Collected:
            </td>
            <td>
                &nbsp; <input type="month" name="monthColl">
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
