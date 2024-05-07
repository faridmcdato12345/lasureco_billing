@extends('layout.master')
@section('title', 'Set server ip address')
@section('stylesheet')
@endsection
@section('content')
<p class="contentheader">Export Sales Data</p>
<div class="container">
    <div class="row">
        <form action="{{route('export.sales')}}" method="post">
            @csrf
            <div class="form-group">
                <label for="">From date:</label>
                <input type="date" name="from_date" id="from_date" class="form-control">
            </div>
            <div class="form-group">
                <label for="">To date:</label>
                <input type="date" name="to_date" id="to_date" class="form-control">
            </div>
            <div class="form-group">
                <label for="teller">Teller</label>
                <select name="user_id" id="user_id" class="form-control">
                    @forelse ($users as $user)
                        <option value="{{$user->user_id}}">{{$user->user_full_name}}</option>
                    @empty
                    <option value="">No data</option>    
                    @endforelse
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Export" class="btn btn-success form-control">
            </div>
        </form>
    </div>
</div>
@endsection
