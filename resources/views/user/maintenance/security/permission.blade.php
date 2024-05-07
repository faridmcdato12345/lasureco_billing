@extends('layout.master')
@section('stylsheet')
<style>
    .div-search{
        position: fixed;
        display: block;
        width: 74.1%;
        padding: 1%;
        background: #f9f9f9;
    }
    .permission-container{
        padding-top: 7%;
    }
    .permission-container li{
        list-style: none;
    }
</style>
@endsection
@section('title', 'Accounting Codes')
@section('content')
<table style="width: 100%;">
    <tr>
        <td style="width: 80%;">
            <p class="contentheader">Permission</p>
        </td>
    </tr>
</table>
<div class="container" style="display: block;height:400px;max-height:500px;background:#f9f9f9;color:black;padding:1%;overflow-y:scroll;padding:0%">
    <div class="div-search">
        <input type="search" name="" id="searchbar" onkeyup="searchPermission()" placeholder="Search permission here...">
    </div>
    <ul class="permission-container">

    </ul>
</div>
@endsection
@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded",function(){
        var b = document.querySelector('#drpbtn3');
        b.classList.add('active');
        b.style.color="blue";
        var c = document.querySelector('.dropdown-container3').childNodes;
        c[13].style.color="blue";
        let sideMenuLength = document.querySelectorAll('.side-menu').length
        const permissions = []
        for (let index = 0; index < sideMenuLength; index++) {
            let name = document.querySelectorAll('.side-menu')[index].innerText
            let n = name.toLowerCase()
            permissions.push(n.replace(/ +/g,"_"))
            let container = document.querySelector('.permission-container')
            container.innerHTML += '<li>'+n.replace(/ +/g,"_")+'</li>' 
        }
        searchPermission = () => {
            let input = document.getElementById('searchbar').value
            input=input.toLowerCase();
            let x = document.getElementsByClassName('permission-list');
            for (i = 0; i < permissions.length; i++) { 
                if (!permissions[i].includes(input)) {
     
                }
                else {
                    let container = document.querySelector('.permission-container')
                    container.innerHTML += '<p>'+input+'</p>'               
                }
            }
        }
    })

    
</script>
@endsection
