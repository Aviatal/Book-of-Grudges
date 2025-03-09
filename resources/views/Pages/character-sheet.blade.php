@extends('Layout.master')

@section('content')
    @if(!$hero)
        Nie posiadasz przypisanego bohatera!
    @else
        <character-sheet
            :init-hero="{{ json_encode($hero) }}"
            :skills="{{ json_encode($skills) }}"
        ></character-sheet>
    @endif

@endsection
