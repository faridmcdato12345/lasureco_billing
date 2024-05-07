@extends('layout.master')
@section('title', 'Monthly Cashiers DCR - SO')
@section('content')


<style>
    .printBtn {
        float: right; 
        color: royalblue;
        background-color: white; 
        height: 40px !important;
        margin-top: 5%;
        margin-right: 2.7%;
    }
    input {
        cursor: pointer;
    }
</style>

<p class="contentheader">Monthly Cashiers DCR - SO</p>
<div class="main">
    <table class="content-table">
        <tr style="height: 40px;"> 
            <td> </td>
        </tr>
        <tr> 
            <td style="width: 15%;"> 
                Collection Month:
            </td>
            <td> 
                <input type="month" id="month">
            </td>
        </tr>
        <tr> 
            <td colspan=4> 
                <button class="printBtn" onclick="print()" disabled> 
                    Print
                </button>
            </td>
        </tr>
    </table>
</div>
<script>
    var month = document.querySelector("#month");
    month.addEventListener("change", function(){
        if(month.value !== ""){
            document.querySelector(".printBtn").disabled = false;
        } else {
            document.querySelector(".printBtn").disabled = true;
        }
    })

    function print(){
        var month = document.querySelector("#month").value;

        const toSend = {
            "date": month
        }

        localStorage.setItem('data', JSON.stringify(toSend));

        $url = '{{route("print_monthly_cashiers_dcr_so")}}'
        window.open($url);
    }
</script>
@endsection
