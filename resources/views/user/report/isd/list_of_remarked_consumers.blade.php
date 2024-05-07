@extends('layout.master')
@section('title', 'List of Remarked Consumers')
@section('content')

<style>
    #printBtn {
        margin-top: 5%;
        margin-right: 3%;
    }
    #selected {
        cursor: pointer;
    }
    input {
        cursor: pointer;
    }
</style>

<p class="contentheader">List of Remarked Consumers</p>
<div class="main">
    <br>
    <table class="content-table">
        <tr>
            <td> <input type="checkbox" id="selected" value="all"> &nbsp; All </td>
        </tr>
        <tr>
            <td height="20px"> &nbsp; </td>
        </tr>
        <tr>
            <td width="15%">
               Date From:
            </td>
            <td>
                <input type="date" id="dateFrom">
            </td>
        </tr>
        <tr> <td height="57px"> &nbsp; </td> </tr>
        <tr>
            <td>
               Date To:
            </td>
            <td>
            <input type="date" id="dateTo" disabled>
            </td>
        </tr>
        <tr>
            <td colspan="4">
            <button id="printBtn" onclick="print()" disabled>Print</button>
            </td>
        </tr>
    </table>
</div>

<script>
    var dateFrom = document.querySelector("#dateFrom");
    dateFrom.addEventListener("change", function(){
        if(dateFrom.value !== ""){
            document.querySelector("#dateTo").disabled = false;
        } else {
            document.querySelector("#dateTo").disabled = true;
        }
    })

    var dateTo = document.querySelector("#dateTo");
    dateTo.addEventListener("change", function(){
        if(dateTo.value !== "" && dateFrom.value !== ""){
            document.querySelector("#printBtn").disabled = false;
        } else {
            document.querySelector("#printBtn").disabled = true;
        }
    })

    var check = "";

    var selected = document.querySelector("#selected");
    selected.addEventListener("change", function(){
        check = selected.checked;
        
        if(check == true) {
            document.querySelector("#dateFrom").disabled = true;
            document.querySelector("#dateTo").disabled = true;
            document.querySelector("#printBtn").disabled = false;
            document.querySelector("#dateFrom").value = "";
            document.querySelector("#dateTo").value = "";
        } else {
            document.querySelector("#dateFrom").disabled = false;
            document.querySelector("#printBtn").disabled = true;
        }
    })

    function print(){
        if(check == true) {
            const toSend = {
                'selected': selected.value
            }
            localStorage.setItem('data', JSON.stringify(toSend));
        } else {
            var dateFrom = document.querySelector("#dateFrom").value;
            var dateTo = document.querySelector("#dateTo").value;

            const toSend = {
                'date_from': dateFrom,
                'date_to': dateTo
            }
            localStorage.setItem('data', JSON.stringify(toSend));
        }  

        $url = '{{route("print_list_of_remarked_consumers")}}'
        window.open($url);
    }
</script>
@endsection
