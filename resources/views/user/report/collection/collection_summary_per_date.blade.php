@extends('layout.master')
@section('title', 'Collection Summary per Date')
@section('content')

<style>
    input {
        cursor: pointer;
    }
    #printBtn {
        height: 40px; 
        float: right; 
        margin-right: 8px;
        margin-top: 5%;
    }
</style>

<p class="contentheader">Collection Summary per Collection Date</p>
<div class="main">
    <table class="content-table">
        <br> <br>
        <tr>
            <td style="width: 15%;">
                Collected Date:
            </td>
            <td>
                <input type="date" id="dateFrom">
            </td>
            <td>
                &nbsp; To:
            </td>
            <td>
                <input type="date" id="dateTo" disabled>
            </td>
        </tr>
        <tr>
            <td colspan=4>
                <button class="modal-button" id="printBtn" onclick="printReport()" disabled>
                    Print
                </button>
            </td>
        </tr>
    </table>
</div>
<script>
    var dateFrom = document.querySelector("#dateFrom");
    var dateTo = document.querySelector("#dateTo");
    var print = document.querySelector("#printBtn");

    dateFrom.addEventListener("change", function(){
        if(dateFrom.value !== ""){
            document.querySelector("#dateTo").disabled = false;
            document.querySelector("#dateTo").focus();
            if(dateTo.value !== ""){
                document.querySelector("#printBtn").disabled = false;
            }
        } else {
            document.querySelector("#dateTo").disabled = true;
            document.querySelector("#printBtn").disabled = true;
        }
    });
    
    dateTo.addEventListener("change", function(){
        if(dateTo.value !== ""){
            if(dateFrom.value !== "") {
                document.querySelector("#printBtn").disabled = false;
            }
        } else {
            document.querySelector("#printBtn").disabled = true;
        }
    });
    
    print.addEventListener("click", function(){
        var dateFrom = document.querySelector("#dateFrom").value;
        var dateTo = document.querySelector("#dateTo").value;

        const toSend = {
            'date_from': dateFrom,
            'date_to': dateTo
        }


        var xhr = new XMLHttpRequest();

        var token = document.querySelector('meta[name="csrf-token"]').content;

        var collSumPerDate = "{{route('collection.summary.per.date')}}";
        xhr.open('POST', collSumPerDate, true);

        xhr.setRequestHeader("Accept", "application/json");
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        var sendFinal = JSON.stringify(toSend);                                                                                                                                                                  
        xhr.send(sendFinal);

        xhr.onload = function(){
            if(xhr.status == 200) {
                var data = JSON.parse(this.responseText);

                localStorage.setItem('data', JSON.stringify(data));
                localStorage.setItem('dates', JSON.stringify(toSend));

                $url = '{{route("print_collection_summary_per_date")}}';
                window.open($url);
            } else {
                Swal.fire({
                    title: 'Error!',
                    icon: 'error',
                    text: 'No record found!'
                })
            }
        }
    });
</script>
@endsection
