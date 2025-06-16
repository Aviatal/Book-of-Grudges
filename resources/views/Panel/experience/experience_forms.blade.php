@extends('Layout.master')
@section('content')
    <experience-Management
        :active-users="{{ json_encode($activeUsers, JSON_THROW_ON_ERROR) }}"
    ></experience-Management>
@endsection
