@extends('Layout.master')
@section('content')
    <token-create
        :heroes="{{ json_encode($heroes, JSON_THROW_ON_ERROR) }}"
    ></token-create>
@endsection
