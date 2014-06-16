@extends('layout')

@section('body')
    @if(Session::has('error'))
        <div>
            <span>{{ trans(Session::get('error')) }}</span>
        </div>
    @endif

    Index
@stop
