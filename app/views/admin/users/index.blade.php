@extends('layout')

@section('body')
    @if(Session::has('error'))
    <div>
        <span>{{ trans(Session::get('error')) }}</span>
    </div>
    @endif

    <div ng-view>kf</div>

    <div>
        @foreach($models as $user)
            <div>
                {{ $user }} &raquo; <a href="{{ url('admin/users/' . $user->id . '/edit') }}">edit</a> <a href="#" onclick="modelHelper.delete('{{ url("admin/users") }}', {{ $user->id }})">delete</a>
            </div>
        @endforeach
    </div>
@stop
