@extends('Layout.master')
@section('content')
    <purchases
        :active-heroes="{{ json_encode($heroes, JSON_THROW_ON_ERROR) }}"
        :marketplace-items="{{ json_encode($marketplaceItems, JSON_THROW_ON_ERROR) }}"
    ></purchases>
@endsection

