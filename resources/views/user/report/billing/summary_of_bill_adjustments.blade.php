@extends('layout.master')
@section('title', 'Summary of Bill Adjustment')
@section('content')

<p class="contentheader">Summary of Bill Adjustment</p>

<div class="m-5 p-5" style="background-color: white; height: 30%">

    <div class="row m-2" style="color:black">
        <div class="col-4 p-1" style='text-align: center'><span class="label label-default">Adjusted Bill Period</span></div>
        <div class="col-4"><input class="form-control" type="month" id="billPeriod"></div>
        <div class="col-4"><input class="form-control" type="button" value="Print" onclick="print()"></div>
    </div>

</div>

<script>
   function print(){
       var url = "{{route('print_summary_of_adjusted_bill')}}";
       var adjBP = document.getElementById('billPeriod').value;
       localStorage.setItem("adjBP", adjBP);
       console.log(adjBP);
       window.open(url);
   }
</script>
@endsection
