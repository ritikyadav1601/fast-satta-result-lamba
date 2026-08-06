@extends('front.layout.master')
@php
    $today = date('j F Y');
@endphp
@section('title', "Fast Satta Result Today $today | Satta Fast Result")
@section('meta_description', "Fast Satta Result Today $today with Satta Fast Result updates, daily result information and previous result details.")
@section('meta_keywords', 'Fast Satta Result, Satta King Result, Satta Result, Satta King, Satta Result Today, Satta King Today Result, Fast Satta King, Today Satta Result, Satta Result Chart, Satta King Chart, Satta Chart, Satta Bazar Result, Gali Satta Result, Desawar Satta Result, Ghaziabad Satta Result, Faridabad Satta Result, Delhi Bazar Result, Delhi Satta Result, Shri Ganesh Result, Gwalior Result, Fast Satta Result Today, Satta King Result Today, Latest Satta Result, Satta King Latest Result, Satta Result Chart 2026, Satta King Chart 2026, Gali Desawar Result, Fast Satta King Result')
@section('canonical')
<link rel="canonical" href="https://www.fast-satta-result.com/" />
<meta name="robots" content="index, follow">
@endsection
@section('content')
@include('front.components.fade-logo' ,['title' => $settings['website_name']])
@include('front.components.updated-box',['games' => $games, 'gamess' => $gamess])
 @include('front.components.chart-visit-link')
@include('front.components.home-timing-detail',['games' => $games,'settings' => $settings,'otherChart' => $otherChart])

@include('front.components.daily-result-table',['games' => $games])
@if($extraGames->count() > 0)
    @include('front.components.extra-daily-result-table',['extraGames' => $extraGames])
@endif
    @include('front.components.record-chart')
    @include('front.components.seo-content')
    @include('front.components.faq',['faq' => $faq , 'qa' => $qa])
@endsection
