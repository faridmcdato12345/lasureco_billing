@extends('layout.master')
@section('title', 'Actual VAT Collection')
@section('content')

<p class="contentheader">Actual VAT Collection</p>
<div class="main">
    <form onSubmit="sendACTVAT(event);" method="post">
        <table class="content-table">
            <tr> 
                <td> 
                    Billing Period:
                </td>
                <td> 
                    <input type="month" id = "bperiod" name="billingPeriod" required>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <button type="submit" style="float:right;width:70px;margin-top:30px;height:40px;" class="btn btn-primary" >Print</button>
                </td>
            </tr>
        </table>
    </form>
</div>  
<script>
    function sendACTVAT(event){
        // report.actual.vat
        event.preventDefault();
        Swal.fire({
        title: 'Do you want to continue?',
        showDenyButton: true,
        confirmButtonText: 'Yes',
        }).then((result) => {
        if (result.isConfirmed) {
                var bp = document.querySelector('#bperiod').value;
                var route = "{{route('report.actual.vat', ['bp'=>':id'])}}";
                var route1 = route.replace(':id', bp);
                var xhr = new XMLHttpRequest();
                xhr.open('GET', route1, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        var data = JSON.parse(this.responseText);
                        localStorage.setItem('data',JSON.stringify(data))
                        localStorage.setItem('info',bp);
                        var url = "{{route('PAV')}}";
                        window.open(url);
                    }       
                }
                xhr.send();
            }
        })
    }
</script>
@endsection
