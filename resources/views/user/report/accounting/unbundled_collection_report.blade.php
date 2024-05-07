@extends('layout.master')
@section('title', 'Unbundled Collection Report')
@section('content')

<style>
    .printBtn {
        float: right;
        height: 40px !important;
        color: royalblue;
        background-color: white;
        margin-right: 2.7%;
        margin-top: 4%; 
    }
    input {
        cursor: pointer;
    }
</style>

<p class="contentheader">Unbundled Collection Report</p>
<div class="main">
    <br><br>
    <table class="content-table">
        <tr>
            <td style="width: 12%;"> 
                Date From:
            </td>
            <td> 
                <input type="date" id="monthFrom">
            </td>
        </tr>
        <tr><td height=50px></td></tr>
        <tr>
            <td>
                Date To:
            </td>
            <td> 
                <input type="date" id="monthTo" disabled>
            </td>
        </tr>
        <tr> 
            <td colspan=2> 
                <button class='printBtn' onclick='print()' disabled> 
                    Print 
                </button>
            </td>
        </tr>
    </table>
</div>

<script>
    var monthFrom = document.querySelector('#monthFrom');
    monthFrom.addEventListener("change", function(){
        if(monthFrom.value !== ""){
            document.querySelector('#monthTo').disabled = false;
        } else {
            document.querySelector('#monthTo').disabled = true;
        }
    })

    var monthTo = document.querySelector('#monthTo');
    monthTo.addEventListener("change", function(){
        if(monthTo.value !== ""){
            document.querySelector('.printBtn').disabled = false;
        } else {
            document.querySelector('.printBtn').disabled = true;
        }
    })

    function print(){
        var date_from = document.querySelector('#monthFrom').value;
        var date_to = document.querySelector('#monthTo').value;

        const toSend = {
            'date_from': date_from,
            'date_to': date_to
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_unbundled_collection_report")}}';
        window.open($url);
    }
</script>

@endsection
