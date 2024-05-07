@extends('layout.master')
@section('title', 'Collection for Month per Town')
@section('content')

<p class="contentheader">Collection for Month per Town</p>
<div class="main">
    <table class="content-table" id="CMT">
        <tr>
            <td style="width: 15%;">
                Area:
            </td>
            <td style="width: 40%;">
                <input type="text" href="#town" class="input-Txt" name="Area" placeholder="Select Area" readonly>
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
                Bill Period:
            </td>
            <td>
                <input type="month" name="billPeriod">
            </td>
            <td>
                &nbsp;
            </td>
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
            <td>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td>
                Prepared By:
            </td>
            <td>
                <input type="text" name="preparedBy" placeholder="Prepared By">
            </td>
            <td>
                <input type="text" name="preparedByPos" placeholder="Position">
            </td>
        </tr>
        <tr>
            <td>
                Checked By:
            </td>
            <td>
                <input type="text" name="checkedBy" placeholder="Checked By">
            </td>
            <td>
                <input type="text" name="checkedByPos" placeholder="Position">
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <button style="height: 40px;
                               float: right;
                               border: none;
                               border-radius: 5px;
                               margin-right: 12px;
                               color: rgb(23, 108, 191);
                               background-color: white;">
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
