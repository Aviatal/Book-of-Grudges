@extends('Layout.master')

@section('content')
    <character-sheet
        :init-hero="{{ json_encode($hero) }}"
    ></character-sheet>
@endsection
