@extends('Layout.master')

@section('content')
    <tabletop
        :user-id="{{ Auth::user()->getAuthIdentifier() }}"
        :hero-id="{{ $heroId }}"
        :has-drawing-permission="{{ $hasDrawingPermission ? 'true' : 'false' }}"
    ></tabletop>
@endsection
