@extends('layout.master')
@section('title', 'Print Consumption by Bracket per Town')
@section('content')
<p class="contentheader">Print Consumption by Bracket per Town</p>
<div class="main" style="margin-top: -2%;">
<br><br>
    <table border=0 class="content-table">
        <tr style="height: 47px;">
            <td>
                Area From:
            </td>
            <td>
                <input type="text" class="input-Txt" href="#route" name="routeTo" placeholder="Route From" readonly>
            </td>
            <td>
                &nbsp; Area To:
            </td>
            <td>
                <input type="text" style="margin-left: -47%; width: 140%;" class="input-Txt" href="#route" name="routeFrom" placeholder="Route To" readonly>
            </td>
        </tr>
        <tr style="height: 47px;">
            <td>
                Consumer Type:
            </td>
            <td>
                <select name="conType">
                    <option value="Irrigation">Irrigation</option>
                    <option value="saab">Saab</option>
                    <option value="mercedes">Mercedes</option>
                    <option value="audi">Audi</option>
                </select>
            </td>
            <td colspan=2 rowspan=2>
                <table border=0 style="margin-left: 2%;">
                    <tr>
                        <td style="width: 10px;">
                            <input type="checkbox" class="checkcheck">
                        </td>
                        <td>
                            &nbsp; All Types
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="height: 47px;">
            <td>
                Billing Period:
            </td>
            <td>
                <input type="date" name="bilingPeriod">
            </td>
            <td colspan=2> </td>
        </tr>
        <tr>
            <td colspan=2>
                <table class="PCBPRTable" border=0>
                    <tr>
                        <td style="width: 36.5%;">
                            1st Bracket
                        </td>
                        <td>
                            <input type="number" name="1stBracketA" placeholder="0.00">
                        </td>
                        <td>
                            <input type="number" name="1stBracketB" placeholder="0.00">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            2nd Bracket
                        </td>
                        <td>
                            <input type="number" name="1stBracketA" placeholder="0.00">
                        </td>
                        <td>
                            <input type="number" name="1stBracketB" placeholder="0.00">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            3rd Bracket
                        </td>
                        <td>
                            <input type="number" name="1stBracketA" placeholder="0.00">
                        </td>
                        <td>
                            <input type="number" name="1stBracketB" placeholder="0.00">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            4th Bracket
                        </td>
                        <td>
                            <input type="number" name="1stBracketA" placeholder="0.00">
                        </td>
                        <td>
                            <input type="number" name="1stBracketB" placeholder="0.00">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            5th Bracket
                        </td>
                        <td>
                            <input type="number" name="1stBracketA" placeholder="0.00">
                        </td>
                        <td>
                            <input type="number" name="1stBracketB" placeholder="0.00">
                        </td>
                    </tr>
                </table>
            </td>
            <td colspan=2>
                <table class="PCBPRTable" border=0>
                    <tr>
                        <td style="width: 26%;">
                            6th Bracket
                        </td>
                        <td>
                            <input type="number" name="1stBracketA" placeholder="0.00">
                        </td>
                        <td>
                            <input type="number" name="1stBracketB" placeholder="0.00">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            7th Bracket
                        </td>
                        <td>
                            <input type="number" name="1stBracketA" placeholder="0.00">
                        </td>
                        <td>
                            <input type="number" name="1stBracketB" placeholder="0.00">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            8th Bracket
                        </td>
                        <td>
                            <input type="number" name="1stBracketA" placeholder="0.00">
                        </td>
                        <td>
                            <input type="number" name="1stBracketB" placeholder="0.00">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            9th Bracket
                        </td>
                        <td>
                            <input type="number" name="1stBracketA" placeholder="0.00">
                        </td>
                        <td>
                            <input type="number" name="1stBracketB" placeholder="0.00">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            10th Bracket
                        </td>
                        <td>
                            <input type="number" name="1stBracketA" placeholder="0.00">
                        </td>
                        <td>
                            <input type="number" name="1stBracketB" placeholder="0.00">
                        </td>
                    </tr>
                </table>
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
                <input type="text" name="checkedBy" placeholder="Prepared By">
            </td>
            <td>
                <input type="text" name="checkedByPos" placeholder="Position">
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <button style="height: 40px; float: right;">
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
        c[1].style.color="blue";
    }
</script>
@endsection
