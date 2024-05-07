@extends('layout.master')
@section('title', 'Tellers Collection')
@section('content')

<p class="contentheader">Teller's Collection</p>
<div class="main">
    <table class="content-table">
        <tr> 
            <td colspan=2> 
                <select name="posting"> 
                    <option value="unposted"> Unposted </option>
                    <option value="posted"> Posted </option>
                </select>
            </td>
        </tr>
        <tr> 
            <td style="width: 18%;"> 
                Teller:
            </td>
            <td> 
                <input type="text" class="input-Txt" name="teller" href="#town" placeholder="Select Teller" readonly>
            </td>
            <td> </td>
            <td> </td>
        </tr>
        <tr> 
            <td> 
                Date From:
            </td>
            <td> 
                <input type="date" name="dateFrom">
            </td>
            <td style="width: 10%;"> 
                &nbsp; Date To:
            </td>
            <td> 
                <input type="date" name="dateTo">
            </td>
        </tr>
        <tr style="height: 30px;"> 
            <td> </td>
            <td style="text-align: center;"> 
                CASH
            </td>
            <td colspan=2 style="text-align: center;"> 
                TOTAL
            </td>
        </tr>  
        <tr> 
            <td> 
                Collections:        
            </td>
            <td> 
                <input type="number" name="cashCollect" placeholder="0.00">
            </td>
            <td colspan=2> 
                <input type="number" name="cashCheck" placeholder="0.00" style="margin-left: 5px;">
            </td>
        </tr> 
        <tr> 
            <td> 
                Consumer Count:
            </td>
            <td> 
                <input type="number" name="consCount" placeholder="0">
            </td>
        </tr>
    </table>
</div>
<script>
    window.onload=function(){
        var b = document.querySelector('#drpbtn4');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container2').childNodes;
        c[16].style.color="blue";
     }
</script>
@endsection