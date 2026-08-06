@extends('front.layout.master')

@section('content')
@include('front.components.fade-logo',['title' => $settings['website_name']])
<section class="octoberresultchart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-uppercase text-center">
                <h1>{{ $game->name }} YEARLY CHART </h1>
                <div style="margin: 20px 0;">
                    <form action="{{ route('game.show', $game->id) }}" method="GET" class="form-inline" style="display: inline-block;">
                        <label for="year" style="font-size: 18px; margin-right: 10px; color: #fff;">Select Year:</label>
                        <select name="year" id="year" class="form-control" onchange="this.form.submit()" style="display: inline-block; width: auto; font-size: 16px; padding: 5px 10px; border-radius: 5px;">
                            @php
                                $currentYear = date('Y');
                                $selectedYear = request()->year ?? $currentYear;
                            @endphp
                            @for($i = $currentYear; $i >= 2015; $i--)
                                <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>@include('front.components.year-chart',[ 'result' => $gameResult ,'game' => $game])

@endsection