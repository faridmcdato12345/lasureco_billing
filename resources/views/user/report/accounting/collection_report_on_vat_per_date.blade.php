@extends('layout.master')
@section('title', 'Collection Report on VAT - per Date')
@section('content')

<style>
    .buttonPrint{
        float: right;
        color: royalblue;
        background-color: white;
        height: 40px !important;
        margin-top: 5%;
        margin-right: 2.7%;
    }
</style>

<p class="contentheader">Collection Report on VAT - per Date</p>
<div class="main">
    <br><br>
    <table class="content-table">
        <tr> 
            <td width=15%> 
                Collection from:
            </td>
            <td> 
                <input type="date" id="from">
            </td>
        </tr>
        <tr><td height=60px></td></tr>
        <tr>
            <td>
                Collection to:
            </td>
            <td> 
                <input type="date" id="to" disabled>
            </td>
        </tr>
        <tr> 
            <td colspan=4> 
                <button class="buttonPrint" onclick="print()" disabled>  
                    Print
                </button>
            </td>
        </tr>
    </table>
</div>

<script>
    var from = document.querySelector("#from");
    from.addEventListener('change', function(){
        if(from.value !== ""){
            document.querySelector('#to').disabled = false;
        } else {
            document.querySelector('#to').disabled = true;
        }
    })

    var to = document.querySelector("#to");
    to.addEventListener('change', function(){
        if(to.value !== ""){
            document.querySelector('.buttonPrint').disabled = false;
        } else {
            document.querySelector('.buttonPrint').disabled = true;
        }
    })
    
    function print(){
        var from = document.querySelector("#from").value;
        var to = document.querySelector("#to").value;

        const toSend = {
            'date_from': from,
            'date_to': to
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_collection_report_on_vat_per_date")}}'
        window.open($url);
    }
</script>
@endsection
