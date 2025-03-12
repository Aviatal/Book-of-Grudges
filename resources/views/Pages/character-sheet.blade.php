@extends('Layout.master')

@section('content')
    <character-sheet
        :user-id="{{ json_encode($id) }}"
    ></character-sheet>
@endsection
