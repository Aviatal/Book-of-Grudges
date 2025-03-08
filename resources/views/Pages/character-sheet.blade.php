@extends('Layout.master')

@section('content')
    <character-sheet
        :init-hero="{{ json_encode($hero) }}"
        :skills="{{ json_encode($skills) }}"
    ></character-sheet>
@endsection
