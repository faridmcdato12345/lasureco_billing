@extends('layout.master')
@section('title', 'Consumer Data')
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
<p class="contentheader">Consumer Count Per KWH Used</p>
<div class="main">
<br>
    <table border="0" class="content-table">
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
                Billing Period:
            </td>
            <td class="input-td">
                <input type="month" name="month" >
            </td>
            <td class="thead">
                Group 1 Max:
            </td>
            <td class="input-td">
                <input type="Text" name="month" value = "100" >
            </td>
        </tr>
        <tr>
            <td class="thead">
                Group 1 Interval:
            </td>
            <td class="input-td">
                <input type="Text" name="month" value = "100" >
            </td>
            <td class="thead">
                Group 2 Max:
            </td>
            <td class="input-td">
                <input type="Text" name="month" value = "100" >
            </td>
        </tr>
        <tr>
        <td class="thead">
                Group 2 Interval:
            </td>
            <td class="input-td">
                <input type="Text" name="month" value = "100" >
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
    
</script>
@endsection
