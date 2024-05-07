@extends('layout.master')
@section('title', 'Modify Consumer')
@section('content')

<p class="contentheader">Modify Consumer</p>
<div class="main">
    <div style="margin-top:30px;overflow-x:hidden;height:150px;width: 80%; margin-left: auto; margin-right: auto; color: white; background-color: #5B9BD5;">
        <table  style="margin:auto;width: 100%;">
                <tr style="border-bottom:1px solid black;">
                    <th>
                        Consumer Name
                    </th>
                    <th>
                        Account Number
                    </th>
                    <th>
                        Sequence Number
                    </th>
                    <th>
                        Meter S/N
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                <tr style="background-color:white; color:black;border-bottom:1px solid black;">
                    <td>
                        Aamino
                    </td>
                    <td>
                        38-3807-0058
                    </td>
                    <td>
                        58
                    </td>
                    <td>

                    </td>
                    <td>
                        <button style="background-color:rgb(23, 108, 191);color:white;" class="modalBtn modal-button" href="#AMASA">Select</button>
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
        c[1].style.color="blue";
    }
</script>
@endsection
