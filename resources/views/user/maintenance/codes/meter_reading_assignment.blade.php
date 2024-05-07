@extends('layout.master')
@section('title', 'Meter Reading Assignment')
@section('content')

<p class="contentheader">Meter Reading Assignment</p>
<div class="main">
    <div style="margin-top:30px;overflow-x:hidden;height:150px;width: 80%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
        <table  style="margin:auto;width: 100%;">
                <tr style="text-align:center;" style="border-bottom:1px solid black;">
                    <th>
                        Code
                    </th>
                    <th>
                        Description
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                <tr style="text-align:center;background-color:white; color:black;border-bottom:1px solid black;">
                    <td>
                        1
                    </td>
                    <td>
                        Defective
                    </td>
                    <td>
                    <button class="modalBtn" style="background-color:rgb(23, 108, 191);color:white;">Edit</button>
                        <button class="modalBtn" style="background-color:red;color:white;">Delete</button>
                    </td>
                </tr>
        </table>
    </div>
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
