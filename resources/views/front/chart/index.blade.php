@extends('front.layout.master')

@section('title', 'Satta King Chart 2026 | Satta Result Chart 2026 & Old Charts')
@section('meta_title', 'Satta King Chart 2026 | Satta Result Chart 2026 & Old Charts')
@section('meta_description', 'Check Satta King Chart 2026 with Satta Result Charts for 2025, 2024, 2023, 2022, 2021, 2020 and older years. View Gali, Desawar, Ghaziabad and Faridabad charts.')
@section('meta_keywords', 'Satta King Chart, Satta King Old Chart, Satta Result Chart, Satta King History, Satta History, Gali Chart, Gali Satta Chart, Desawar History, Desawar Chart, Satta King Old Record, Satta King 2026 Record, Satta King 2025 Record, Satta King 2024 Record, Satta King 2023 Record, Satta King Daily Result List, Satta King Monthly Chart, Satta King Yearly Chart')

@section('canonical')
<link rel="canonical" href="https://www.fast-satta-result.com/chart" />
<meta name="robots" content="index, follow" />
@endsection

@section('content')

@include('front.components.fade-logo',['title' => $settings['website_name']])

<section class="circlebox">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="liveresult">
                    <h1 class="hintext2">Satta King Chart 2026 – Satta Result Chart & Old Charts</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="octoberresultchart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>Gali Chart, Desawar History</h2>
            </div>
        </div>
    </div>
</section>

<section class="octoberresultchart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>SATTA KING CHART</h2>
            </div>
        </div>
    </div>
</section>

<section class="octoberresultchart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h3>2026 Record, Daily Result List</h3>
            </div>
        </div>
    </div>
</section>

<section class="newtable">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 nopadding">
                <div class="table-responsive marginBottom">
                    <table class="table table-bordered table-extra">
                        <thead>
                            <tr>
                                <th class="table_chart_section forfirtcolor text-center">Game</th>
                                <th class="table_chart_section forfirtcolor text-center">2026</th>
                                <th class="table_chart_section forfirtcolor text-center">2025</th>
                                <th class="table_chart_section forfirtcolor text-center">2024</th>
                                <th class="table_chart_section forfirtcolor text-center">2023</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($games as $game)
                            <tr>
                                <td class="table_chart_section forfirtcolor text-center">
                                    {{ strtoupper($game->name) }}
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('game.show', $game->id) }}?year=2026">2026</a>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('game.show', $game->id) }}?year=2025">2025</a>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('game.show', $game->id) }}?year=2024">2024</a>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('game.show', $game->id) }}?year=2023">2023</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@include('front.components.chart-links')

@endsection