@extends('layout.master')
@section('title', 'Penalty')
@section('content')

<p class="contentheader">Penalty Report</p>
<div class="main">
    <table style ="height:500px;" border="0" class="content-table">
        <tr>
            <td  class="thead">
               Month:
            </td>
            <td class="input-td">
            <input type="month" id="mos" name="month">
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <button style="width:70px;margin-top:30px;height:40px;" onclick="penalty()" id="printBtn" >Print</button>
            </td>
        </tr>
    </table>
</div>
<script>
    function penalty(){
        localStorage.removeItem('data');
        var mos = document.querySelector('#mos').value;
        var data = new Object();
        data.date = mos;
        var url = '{{route("PP")}}';
        var route = "{{route('collection.penalty')}}";
        var xhr = new XMLHttpRequest();
        xhr.open('POST', route, true); 
        xhr.setRequestHeader("Accept", "application/json");
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onload = function() {
            if(this.status == 200){
                var info = JSON.parse(this.responseText);
               localStorage.setItem('data', JSON.stringify(info));
               window.open(url)
            }
            else if(this.status == 422){
                console.log(2);
            }
        }
        xhr.send(JSON.stringify(data));
    }
</script>
@endsection