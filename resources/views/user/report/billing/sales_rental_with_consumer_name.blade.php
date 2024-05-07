@extends('layout.master')
@section('title', 'Sales Rental with Consumer Name')
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
<p class="contentheader">Sales Rental with Consumer Name</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
                Area From:
            </td>
            <td  class="input-td">
                <input type="text" class="input-Txt" href="#accNo" name="area" value="01 - District" readonly>
            </td>
        </tr>
        <tr>
            <td class="thead">
                Town Code From:
            </td>
            <td  class="input-td">
                <input type="text" class="input-Txt" href="#accNo" name="area" value="01 - District" readonly>
            </td>
            <td class="thead">
                Town Code To:
            </td>
            <td  class="input-td">
                <input type="text" class="input-Txt" href="#accNo" name="area" value="01 - District" readonly>
            </td>
        </tr>
        <tr>
        <td class="thead">
                Consumer Type:
            </td>
        <td class="input-td">
            <select style = "border-radius:5px;" name="" id=""><option disabled selected>By Year Month</option></select>
            </td>
            <td colspan=1>
                <label class="container">All Types
                <input type="checkbox">
                <span class="checkmark"></span>
                </label>
            </td>
        </tr>
        <tr>
        <td class="thead">
                Billing Period:
            </td>
            <td  class="input-td">
                <input type="month" name="period">
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
        c[5].style.color="blue";
    }
</script>
@endsection
