@extends('Layout.master')
@section('content')
    <token-edit
        :token-id="{{ $tokenId }}"
        :heroes="{{ json_encode($heroes, JSON_THROW_ON_ERROR) }}"
    ></token-edit>
@endsection
