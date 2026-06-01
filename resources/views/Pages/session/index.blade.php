@extends('Layout.master')

@section('content')
    <tabletop
        :hero-id="{{ $heroId }}"
        :has-drawing-permission="{{ $hasDrawingPermission }}"
    ></tabletop>
@endsection
