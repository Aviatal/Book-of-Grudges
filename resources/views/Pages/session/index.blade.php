@extends('Layout.master')

@section('content')
    <tabletop
        :hero-id="{{ $heroId }}"
    ></tabletop>
@endsection
