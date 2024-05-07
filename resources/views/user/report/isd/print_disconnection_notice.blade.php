@extends('layout.master')
@section('title', 'Print Disconnection Notice')
@section('content')
<style>
.container {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 1vw;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  color:white;
  font-weight:bold;
  margin-left:30px;
}

.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0vw;
  width: 0vw;
}

.checkmark {
  position: absolute;
  top: 0;
  left:0;
  height: 1.5vw;
  width: 1.5vw;
  background-color: #eee;
}

.container:hover input ~ .checkmark {
  background-color: #ccc;
}

.container input:checked ~ .checkmark {
  background-color: #2196F3;
}

.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

.container input:checked ~ .checkmark:after {
  display: block;
}

.container .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
</style>
<p class="contentheader">Print Disconnection Notice</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Area:
            </td>
            <td  class="input-td">
            <input type="text" class = "input-Txt" href="#accNo" name="area" placeholder="Select Area" readonly>
            </td>
            <td style="width:15%" class="thead">
                Town:
            </td>
            <td class="input-td">
            <input type="text" class = "input-Txt" href="#town" name="area" placeholder="Select Town" readonly>
            </td>
        </tr>
        <tr>
            <td  class="thead">
                Route:
            </td>
            <td class="input-td">
            <input type="text" class = "input-Txt" href="#route" name="area" placeholder="Select Route" readonly>
            </td>
        </tr>
        <tr>
        <td  class="thead">
                Account No. From:
            </td>
            <td  class="input-td">
            <input type="text" class = "input-Txt" href="#accNo" name="area" placeholder="Select Account" readonly>
            </td>
            <td style="width:15%" class="thead">
                 Account No. To:
            </td>
            <td class="input-td">
            <input type="text" class = "input-Txt" href="#accNo" name="area" placeholder="Select Account" readonly>
            </td>
        </tr>
        <tr>
            <td  class="thead">
                Current Period:
            </td>
            <td class="input-td">
                <input type="month" name = "month">
            </td>
            <td  class="thead">
                Minimum No. of Bills:
            </td>
            <td class="input-td">
                <input type="text" name = "minimum" value="2">
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <label class="container">Include Special Accounts
                <input type="checkbox">
                <span class="checkmark"></span>
                </label>
            </td>
            <td colspan=2>
            <input style = "width:20px;height:20px;" type="radio" class="radio" name="y" value="y" id="y" />Printer
            <input style = "width:20px;height:20px;" type="radio" class="radio" name="z" value="z" id="z" />PDF
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
        var b = document.querySelector('#drpbtn2');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container2').childNodes;
        c[25].style.color="blue";
    }
</script>
@endsection
