@extends('layout.master')
@section('title', 'Set server ip address')
@section('stylesheet')
@endsection
@section('content')
<p class="contentheader">Import Sales Data</p>
<div class="container">
    @if($errors->any())
    <div class="error-handler p-1 mb-1 bg-danger text-white rounded">
        <p class="ml-1">{{$errors->first()}}</p>
    </div>
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <strong>Success!</strong> {{Session::get('success')}}
        </div>
    @endif
    <div class="row">
        <form action="{{route('import.sales')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="">File:</label>
                <input type="file" name="fileToUpload" id="fileToUpload" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" value="Upload File" name="submit" class="btn btn-success form-control">
            </div>
        </form>
    </div>
</div>
@endsection
