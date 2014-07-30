@extends('layout')

@section('body')
    @if(Session::has('error'))
        <div>
            <span>{{ trans(Session::get('error')) }}</span>
        </div>
    @endif

    <div ng-view></div>
@stop
