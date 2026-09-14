@extends('Layout.master')
@section('content')
    <campaign-management
        :campaign="{{ json_encode($campaign, JSON_THROW_ON_ERROR) }}"
        :members="{{ json_encode($members, JSON_THROW_ON_ERROR) }}"
        :invite-url="{{ json_encode($inviteUrl, JSON_THROW_ON_ERROR) }}"
    ></campaign-management>
@endsection
