@extends('layout')

@section('body')
<form action="{{ url('search') }}" method="post" role="form">

    <a href="#" onclick="modelHelper.delete('asdf', 1)">Delete</a>


    <form role="form">
        <div class="form-group">
            <label>username</label>
            <input type="text" class="form-control" name="username" />
        </div>
    </form>
@stop
