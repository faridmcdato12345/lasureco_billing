@extends('layout.master')

@section('stylesheet')
@include('include.style.consumer')
@section('title', 'Modify Account')
@endsection
@section('content')
<div class="container">
  <!-- Content here -->
  
  <p class="contentheader">Modify Consumer Account</p>
  
  <form action="{{route('modify.to.old')}}" method="POST">
    @csrf
    <div class="row">
      
      <div class="col-6 px-5 py-5">
        @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        @if(session('status1'))
        <div class="alert alert-danger">
            {{ session('status1') }}
        </div>
        @endif
        @if(session('status2'))
        <div class="alert alert-danger">
            {{ session('status2') }}
        </div>
        @endif
        @if(session('status3'))
        <div class="alert alert-danger">
            {{ session('status3') }}
        </div>
        @endif
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-default">Current Account Number :</span>
          </div>
          <input type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="currentAcc" required>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-default">New Account Number :&nbsp&nbsp&nbsp&nbsp&nbsp</span>
          </div>
          <input type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="newAcc" required>
        </div>
        <div class="input-group mb-3 w-25 py-10">
          <button type="submit" class="form-control btn-primary" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">Submit</button>
        </div>
      </div>
  
    </div>
  </form>
  
  
</div>


  
<script type="text/javascript" >
  
</script>
@endsection

