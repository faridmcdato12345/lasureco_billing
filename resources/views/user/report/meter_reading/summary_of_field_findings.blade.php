@extends('layout.master')
@section('title', 'Summary of Field Findings')
@section('content')
<p class="contentheader">Summary of Field Findings</p>
<div class="main">
<br><br>
    <table border="0" style = "height: 450px; margin-top: -3%;" class="content-table">
        <tr>
            <td class="thead">
                Area:
            </td>
            <td  class="input-td">
                <input type="text" href="#area" class="input-Txt" name="town" placeholder="Select Area" readonly>
            </td>
            <td class="thead">
                 Town:
            </td>
            <td class="input-td">
                <input type="text"  href="#town" class="input-Txt" name="town" placeholder="Select Town" readonly>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td class="thead">
                Period:
            </td>
            <td class="input-td">
                <input type="date" name="period">
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <div class="sumFFHeaderDiv">
                    <table border=0 class="sumFFHeader">
                        <tr>
                            <td>
                                &nbsp; Route
                            </td>
                            <td>
                                Book Number
                            </td>
                            <td>
                                Total Consumers
                            </td>
                            <td>
                                Uploaded By
                            </td>
                            <td>
                                Reader
                            </td>
                            <td>
                                Meter Reader Name
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="sumFFDiv">
                    <table border=0 class="sumFFTable">
                        <tr>
                            <td>
                                &nbsp; Route
                            </td>
                            <td>
                                Book Number
                            </td>
                            <td>
                                Total Consumers
                            </td>
                            <td>
                                Uploaded By
                            </td>
                            <td>
                                Reader
                            </td>
                            <td>
                                Meter Reader Name
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &nbsp; Route
                            </td>
                            <td>
                                Book Number
                            </td>
                            <td>
                                Total Consumers
                            </td>
                            <td>
                                Uploaded By
                            </td>
                            <td>
                                Reader
                            </td>
                            <td>
                                Meter Reader Name
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &nbsp; Route
                            </td>
                            <td>
                                Book Number
                            </td>
                            <td>
                                Total Consumers
                            </td>
                            <td>
                                Uploaded By
                            </td>
                            <td>
                                Reader
                            </td>
                            <td>
                                Meter Reader Name
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &nbsp; Route
                            </td>
                            <td>
                                Book Number
                            </td>
                            <td>
                                Total Consumers
                            </td>
                            <td>
                                Uploaded By
                            </td>
                            <td>
                                Reader
                            </td>
                            <td>
                                Meter Reader Name
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &nbsp; Route
                            </td>
                            <td>
                                Book Number
                            </td>
                            <td>
                                Total Consumers
                            </td>
                            <td>
                                Uploaded By
                            </td>
                            <td>
                                Reader
                            </td>
                            <td>
                                Meter Reader Name
                            </td>
                        </tr>
                        <tr>
                            <td>
                                &nbsp; Route
                            </td>
                            <td>
                                Book Number
                            </td>
                            <td>
                                Total Consumers
                            </td>
                            <td>
                                Uploaded By
                            </td>
                            <td>
                                Reader
                            </td>
                            <td>
                                Meter Reader Name
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <button style = "height:40px;">Print</button>
                <button style = "height:40px;" id="myBtn4" hidden>Print</button>
            </td>
        </tr>
    </table>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container').childNodes;
        c[1].style.color="blue";
     }
</script>
@endsection

