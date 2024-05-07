@extends('layout.master')
@section('title', 'Employee Master')
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
.contentA {
  display: flex;
  flex:1;
  color: #000;
  width:80%;
  margin:0 auto;
}
.contentB {
  display: flex;
  flex:3;
  color: #000;

}
.contentC {
  display: flex;
  flex: 1;
  color: white;
  margin-left:50px;
}
.contentD {
  display: flex;
  flex: 1;
  color: #000;
}
@media screen and (max-width: 500px){
    .content{
    	display:block;
    }
    table{
    	margin-bottom:10px;
    }
    .content3{
        margin-left:0;
    }
}
</style>
<p class="contentheader">Employee Master</p>
<div class="main">
    <div class="contentA">
        <div class="contentB">
            <div style="margin-top:30px;overflow-x:hidden;height:350px;width: 100%;color: white; background-color: #5B9BD5;">
                <table  border=0 style="text-align:center;margin:auto;width: 100%;">
                        <tr style="border-bottom:1px solid black;">
                            <th>
                                Employee ID
                            </th>
                            <th>
                                Employee Name
                            </th>
                            <th>
                                Code
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                        <tr style="background-color:white; color:black;border-bottom:1px solid black;">
                            <td>
                                9006
                            </td>
                            <td>
                                Abdulwally Bacarat
                            </td>
                            <td>
                                A.BACARAT
                            </td>
                            <td>
                                <button class="modalBtn" style="background-color:rgb(23, 108, 191);color:white;" >Edit</button>
                            </td>
                        </tr>
                </table>
            </div>
        </div>
        <div class="contentC">
            <table border=0 style="margin-left:-30px;margin-top:30px;width:100%;">
            <tr>
                <td>
                    <label class="container">Teller
                    <input type="checkbox">
                    <span class="checkmark"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="container">Collector
                    <input type="checkbox">
                    <span class="checkmark"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="container">Meter Reader
                    <input type="checkbox">
                    <span class="checkmark"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="container">DN Server
                    <input type="checkbox">
                    <span class="checkmark"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="container">Disconnector
                    <input type="checkbox">
                    <span class="checkmark"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="container">Lineman
                    <input type="checkbox">
                    <span class="checkmark"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="container">Inspection
                    <input type="checkbox">
                    <span class="checkmark"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="container">Cashier
                    <input type="checkbox">
                    <span class="checkmark"></span>
                    </label>
                </td>
            </tr>
            </table>
        </div>
    </div>
    <table class="EMR-table" style="width:80%;height:100px">
        <tr>
            <td>
            <button id="printBtn" > Create  </button></td>
        </tr>
    </table>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn3');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container3').childNodes;
        c[5].style.color="blue";
    }
</script>
@endsection
