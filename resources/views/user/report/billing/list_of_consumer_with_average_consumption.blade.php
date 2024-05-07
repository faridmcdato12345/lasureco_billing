@extends('layout.master')
@section('title', 'List of Consumer with Average Consumption')
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
<p class="contentheader">List of Consumer with Average Consumption</p>
<div class="main">
<br>
    <table border="0" style = "height:200px;" class="content-table">
        <tr>
        <td colspan=6>
                <label class="container">All Areas
                <input type="checkbox">
                <span class="checkmark"></span>
                </label>
            </td>
        </tr>
        <tr>
        <td class="thead">
            Area:
            </td>
            <td  class="input-td">
             <input type="text" class="input-Txt" href= "#accNo" name="area" placeholder="Select Area" >
            </td>
            <td class="thead">
            Town:
            </td>
            <td class="input-td">
                <input type="text" class="input-Txt" href= "#town" name="town" placeholder="Select Town" >
            </td>
        </tr>
        <tr>
            <td class="thead">
            Route From:
            </td>
            <td  class="input-td">
             <input type="text" class="input-Txt" href= "#route" name="area" placeholder="Select Area" >
            </td>
            <td class="thead">
            Route To:
            </td>
            <td class="input-td">
                <input type="text" class="input-Txt" href= "#route" name="town" placeholder="Select Town" >
            </td>
        </tr>
        <tr>
        <td class="thead">
                Billing Period:
            </td>
            <td class="input-td">
                <input type="month" name="month" >
            </td>
            <td class="thead">
                KWH Used:
            </td>
            <td class="input-td">
                <select name="Sort">
                    <option value="volvo">Average</option>
                    <option value="saab">Unposted</option>
                </select>
            </td>

        </tr>
        </table>
        <table style ="width:80%;height:300px;" border="0" class="content-table">
        <tr>

            <td class = "thead">
                Prepared By:
            </td>
            <td class = "input-td">
                <input type = "text" placeholder = "Prepared By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td class = "thead">
                Noted By:
            </td>
            <td class = "input-td">
                <input type = "text" placeholder = "Noted By" readonly>
            </td>
            <td class = "input-td">
                <input type = "text" readonly>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <button style="height:40px;" id="printBtn">Print</button>
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
        c[5].style.color="blue";
    }
</script>
@endsection
