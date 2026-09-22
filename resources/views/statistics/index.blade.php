@extends('Layout.master')

@section('content')
    <statistics-page
        :global-statistics="{{ json_encode($globalStatistics, JSON_THROW_ON_ERROR) }}"
        :player-statistics="{{ json_encode($playerStatistics, JSON_THROW_ON_ERROR) }}"
        :player-hero-name="{{ json_encode($playerHeroName, JSON_THROW_ON_ERROR) }}"
        :show-hero-breakdown="{{ json_encode($showHeroBreakdown, JSON_THROW_ON_ERROR) }}"
        :roll-statistics="{{ json_encode($rollStatistics, JSON_THROW_ON_ERROR) }}"
    ></statistics-page>
@endsection
