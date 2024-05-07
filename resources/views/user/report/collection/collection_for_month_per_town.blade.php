@extends('layout.master')
@section('title', 'Collection for Month per Town')
@section('content')

<style>
    #printButton {
        float: right;
        color: royalblue;
        background-color: white;
        height: 40px;
        margin-right: 2.5%;
        margin-top: 3%;
    }
    input {
        cursor: pointer;
    }
    #selected {
        color: black;
        cursor: pointer;
    }
</style>

<p class="contentheader">Collection for Month per Town</p>
<div class="main">
    <br><br>
    <table class="content-table">
        <tr>
        <td width="15%">
                Selected:
            </td>
            <td>
                <select id="selected">
                    <option value="all"> All </option>
                    <option value="area"> Area </option>
                    <option value="town"> Town </option>
                    <option value="route"> Route </option>
                </select>
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        <tr> <td height='50px'> &nbsp; </td> </tr>
        <tr>
            <td>
                Bill Period:
            </td>
            <td>
                <input type="month" id="billPeriod">
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        <tr> <td height='50px'> &nbsp; </td> </tr>
        <tr>
            <td>
                Collected from:
            </td>
            <td>
                <input type="date" id="from" disabled>
            </td>
        </tr>
        <tr> <td height='50px'> &nbsp; </td> </tr>
        <tr>
            <td>
                Collected to:
            </td>
            <td>
                <input type="date" id="to" disabled>
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan='2'> <button id='printButton' disabled> Print </button> </td>
        </tr>
    </table>
</div>

<script>
    var bill = document.querySelector('#billPeriod');

    bill.addEventListener('change', function(){
        if(bill.value !== ""){
            document.querySelector('#from').disabled = false;
        } else {
            document.querySelector('#from').disabled = true;
        }  
    }) 

    var from = document.querySelector('#from');

    from.addEventListener('change', function(){
        if(from.value !== ""){
            document.querySelector('#to').disabled = false;
        } else {
            document.querySelector('#to').disabled = true;
        }
    })

    var to = document.querySelector('#to');

    to.addEventListener('change', function(){
        if(to.value !== ""){
            document.querySelector('#printButton').disabled = false;
        } else {
            document.querySelector('#printButton').disabled = true;
        }
    })

    document.querySelector('#printButton').addEventListener('click', function(){
        const toSend = {
            "selected": document.querySelector('#selected').value,
            "bill_period": bill.value,
            "date_from": from.value,
            "date_to": to.value
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_collection_for_month_per_town")}}';
        window.open($url);
    })
</script>
@endsection
