@extends('layout.master')
@section('title', 'Summary of Bills - BAPA')
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
<p class="contentheader">Summary of Bills - BAPA</p>
<div class="main">
<br>
    <table border="0" class="content-table">
        <tr>
            <td class="thead">
                Area:
            </td>
            <td style="width:30%;" class="input-td">
                <input class="input-Txt" href="#accNo" type="text" name="area" value="03 - Marawi City Wide and" readonly>
            </td>

            <td class="thead">
             Town:
            </td>
            <td class="input-td" colspan=2>
                <input class="input-Txt" href="#town" type="text" value= "43 - Marawi City Wide and">
            </td>

        </tr>
        <tr>
            <td class="thead">
                Route:
            </td>
            <td style="width:30%;" class="input-td">
                <input class="input-Txt" href="#route" type="text" name="area" value="03 - Marawi City Wide and" readonly>
            </td>

            <td class="thead">
             Book #:
            </td>
            <td class="input-td" colspan=2>
                <input type="number" value= "01">
            </td>
        </tr>
        <tr>
        <td class="thead">
                Billing Period:
            </td>
            <td style="width:30%;" class="input-td">
                <input type="month" name="month" >
            </td>
            <td colspan=2>
                <label class="container">Recap Only
                <input type="checkbox">
                <span class="checkmark"></span>
                </label>
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
