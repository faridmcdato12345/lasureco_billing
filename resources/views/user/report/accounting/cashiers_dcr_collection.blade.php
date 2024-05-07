@extends('layout.master')
@section('title', 'Cashiers DCR')
@section('content')

<style>
    .month {
        cursor: pointer;
    }
    .printBtn {
        float: right;
        margin-right: 2.5%; 
        margin-top: 2%;
        background-color: white;
        color: royalblue;
        height: 40px !important;
    }
</style>

<p class="contentheader">Cashier's DCR</p>
<div class="main">
    <table class="content-table">
        <tr> 
            <br><br>
            <td style="width: 15%;"> 
                Collection Date:
            </td>
            <td> 
                <input type="date" class="month">
            </td>
        </tr>
        <tr>
            <td colspan=4> 
                <button onclick="print()" class="printBtn" disabled> 
                    Print
                </button>
            </td>
        </tr>
    </table>
</div>  

<script>
    var month = document.querySelector(".month");

    month.addEventListener("change", function(){
        if(month.value !== ""){
            document.querySelector(".printBtn").disabled = false;
        } else {
            document.querySelector(".printBtn").disabled = true;
        }
    })

    function print(){
        var month = document.querySelector(".month").value;

        const toSend = {
            "bill_date": month
        }

        var JSONed = JSON.stringify(toSend);

        $url = '{{route("print_cashiers_dcr_collection")}}';

        var xhr = new XMLHttpRequest();
        var token = document.querySelector('meta[name="csrf-token"]').content;
        var cashiersDcr = "{{route('accounting.dcr.cashier')}}";
        xhr.open('POST', cashiersDcr, true);
        xhr.setRequestHeader("Accept", "application/json");
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.send(JSONed);

        xhr.onload = function(){
            if(this.status == 200){
                localStorage.setItem('data', JSON.stringify(toSend));
                window.open($url);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No DCR Found!',
                })
            }
        }
    }
</script>

@endsection
