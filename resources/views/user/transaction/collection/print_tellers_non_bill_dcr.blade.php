@extends('layout.master')
@section('title', 'Print Tellers Non-Bill DCR')
@section('content')

<p class="contentheader">Print Tellers Non-Bill DCR</p>
<div class="main">
    <table style ="height:600px;" border="0" class="content-table">
        <tr>
            <td style="width:25%;" class="thead">
                Tellers:
            </td>
            <td class="input-td">
                <input style ="width:75%;" type="text" name="area" id = "accNo" value="Sitti Khairanie M. Magomnang" readonly>
            </td>
            <td class="td-btn">
                <button style="height:40px;" id="route" hidden>Select</button>
                <button style="height:40px;" id="mR" hidden>Select</button>
                <button style="height:40px;" id="myBtn4" hidden>Select</button>
            </td>
        </tr>
        <tr>
            <td class="thead">
                Collection Date:
            </td>
            <td class="input-td">
                <input style ="width:75%;" type="month">
        </td>
        </tr>
        <tr>
        <td class="thead">
            TOR No. From:
        </td>
        <td class="input-td">
                <input style ="width:75%;" type="text" readonly>
        </td>
        <td class="thead">
            TOR No. To:
        </td>
        <td class="input-td">
                <input style ="width:85%;" type="text" readonly>
        </td>
        </tr>
        <tr>
            <td class="thead">
                Total Collection:
            </td>
            <td class="input-td">
                <input style ="width:75%;" type="text" value="0.00" readonly>
             </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
        <td style="font-size:1vw;" class="thead" colspan="2" >
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                Prepared By:
                &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                <input style ="font-size:1vw;margin-left:2px;width:35%;height:35px;" type="text" name="area" placeholder="Prepared By:" readonly>
            </td>
        </tr>
        <tr>
        <td style="font-size:1vw;" class="thead" colspan="2" >
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                Teller:
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                <input style ="font-size:1vw;width:35%;height:35px;" type="text" name="area" value="Teller" readonly>
            </td>
        </tr>
        <tr>
        <td style="font-size:1vw;" class="thead" colspan="2" >
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                Checked & Received By:
                <input style ="font-size:1vw;width:35%;height:35px;" type="text" name="area" Placeholder="Received By:" readonly>
            </td>
        </tr>
        <tr>
        <td style="font-size:1vw;" class="thead" colspan="2" >
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                Position:
            &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                <input style ="font-size:1vw;width:35%;height:35px;" type="text" name="area" placeholder="Position" readonly>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <button style="width:70px;margin-top:30px;height:40px;" id="printBtn" >Print</button>
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
        c[9].style.color="blue";
     }
</script>
@endsection
