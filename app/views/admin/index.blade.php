@extends('layout')

@section('body')
    @if(Session::has('error'))
        <div>
            <span>{{ trans(Session::get('error')) }}</span>
        </div>
    @endif

    <div ng-view></div>

    <!-- Partials -->
@if(View::exists('admin.compiled'))
    @include('admin.compiled')
@endif
@stop
