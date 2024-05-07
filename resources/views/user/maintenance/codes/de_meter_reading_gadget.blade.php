@extends('layout.master')
@section('title', 'DE Meter Reading Gadget')
@section('content')

<p class="contentheader">MRD Code</p>
<div class="main">
    <div style="margin-top:30px;overflow-x:hidden;height:150px;width: 80%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
        <table  style="text-align:center;margin:auto;width: 100%;">
                <tr style="border-bottom:1px solid black;">
                    <th>
                        MRD Code
                    </th>
                    <th>
                       Meter Reader
                    </th>
                    <th>
                       Name
                    </th>
                    <th>
                      Serial No.
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                <tr style="background-color:white; color:black;border-bottom:1px solid black;">
                    <td>

                    </td>
                    <td>

                    </td>
                    <td>

                    </td>
                    <td>

                    </td>
                    <td>
                        <button class="modalBtn" style="background-color:rgb(23, 108, 191);color:white;" >Edit</button>
                        <button class="modalBtn" style="background-color:red;color:white;" >Delete</button>
                    </td>
                </tr>
        </table>
    </div>
    <table class="EMR-table" style="height:250px">
        <tr>
            <td> </td> <td> </td> <td> </td> <td> </td> <td> </td>
            <td><button id="printBtn" style="float:left"> Create</button>
                <button id="printBtn" style="float:left;"> Print</button></td>
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
