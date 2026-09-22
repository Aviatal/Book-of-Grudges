@extends('Layout.master')

@section('content')
    <tabletop
        :user-id="{{ Auth::user()->getAuthIdentifier() }}"
        :hero-id="{{ $heroId }}"
        :has-drawing-permission="{{ $hasDrawingPermission ? 'true' : 'false' }}"
        :is-gm="{{ $isGm ? 'true' : 'false' }}"
        :campaign-id="{{ $campaignId }}"
    ></tabletop>
@endsection
