@extends('layout.master')
@section('title', 'Summary of Sales Collected - Fit Allowance')
@section('content')

<style>
    .radio {
        cursor: pointer;
    }
    .printBtn {
        float: right;
        color: royalblue;
        background-color: white;
        height: 40px !important;
        margin-top: 3%;
        margin-right: 2.5%;
    }

    input {
        cursor: pointer;
    }
</style>

<p class="contentheader">Summary of Sales Collected - Fit Allowance</p>
<div class="main">
    <table class="content-table">
        <tr colspan=2>
            <td> 
                <table> 
                    <tr> 
                        <td> 
                            <input type="radio" name="summBy" value="sales" class="radio" checked>
                        </td>
                        <td> 
                            Sales 
                        </td>
                        <td width="10%"></td>
                        <td> 
                            <input type="radio" name="summBy" value="collections" class="radio">
                        </td>
                        <td> 
                            Collections
                        </td>
                    </tr>
                </table>
            </td> 
        </tr>
        <tr> <td> &nbsp; </td></tr>
        <tr>
            <td width="12%"> 
                Area Code:
            </td>
            <td>
                <input type="text" id="area" onclick="showArea()" placeholder="Select Area">
                <input type="text" id="areaId" hidden>
            </td>
        </tr>
        <tr><td height="50px"> &nbsp; </td></tr>
        <tr>
            <td> 
                Month Billed:
            </td>
            <td> 
                <input type="month" id="month" disabled>
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

@include('include.modal.areamodal')

<script>
    function setArea(x) {
        var areaName = x.getAttribute('areaName');
        var areaId = x.getAttribute('areaId');

        document.querySelector("#area").value = areaName;
        document.querySelector("#areaId").value = areaId;

        document.querySelector("#month").disabled = false;
        document.querySelector('#areaCodes').style.display = "none";
    }

    var month = document.querySelector("#month");
    month.addEventListener("change", function(){
        if(month.value !== ""){
            document.querySelector(".printBtn").disabled = false;
        } else {
            document.querySelector(".printBtn").disabled = true;
        }
    })

    function print(){
        var areaId = document.querySelector("#areaId").value;
        var month = document.querySelector("#month").value;
        var selected = document.querySelector('input[name="summBy"]:checked').value;

        const toSend = {
            "date_period": month,
            "selected": selected,
            "area_id": areaId
        }

        $url = '{{route("print_summary_of_sales_collected_ar_fit_all")}}';
        localStorage.setItem('data', JSON.stringify(toSend));

        window.open($url);
    }
</script>

@endsection
