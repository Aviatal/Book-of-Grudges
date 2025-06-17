@extends('layout.master')

@section('content')
    <fortune-points-management
        :active-users="{{ json_encode($activeUsers, JSON_THROW_ON_ERROR) }}"
    ></fortune-points-management>
@endsection
