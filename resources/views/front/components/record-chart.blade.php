<section class="octoberresultchart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Fast Satta Result – Live 2025 Updates</h1>
            </div>
        </div>
    </div>
</section>
<section class="octoberresultchart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>Today Satta Result, Gali Satta King, Desawar Satta King , Fast Satta King</h2>
            </div>
        </div>
    </div>
</section>

<section class="octoberresultchart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h3>{{ strtoupper(now()->format('Y F')) }} RESULT CHART</h3>
            </div>
        </div>
    </div>
</section>
@php
$selectedArray = ['फरीदाबाद', 'गाज़ियाबाद', 'गली', 'दिसावर'];
$selectedGames = $games->whereIn('name', $selectedArray);
 
@endphp

<section class="newtable">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 nopadding">
                <div class="table-responsive marginBottom">
                    <table class="table table-bordered table-extra">
                        <thead>
                            <tr>
                                <td class="table_chart_section_01 forfirtcolor date col-md-2 text-center">
                                    <strong class="fon">Date</strong>
                                </td>
                                @foreach($selectedGames as $game)
                                    <td class="table_chart_section forfirtcolor text-center">
                                        {{ strtoupper($game->name) }}
                                    </td>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($dates as $date)
                        @if($date <= now())
    <tr>
        <td class="forfirtcolor text-center">
            <span class="fon">{{ date('d-m', strtotime($date)) }}</span>
        </td>
        @foreach($selectedGames as $game)
            <td>
                <span class="table_chart_section_02">
                    @php
                        $currentDate = \Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d');
                        $currentTime = \Carbon\Carbon::now('Asia/Kolkata')->format('H:i');
                    @endphp

                    @if ($currentDate == $date)
                        {{-- Apply the result_time condition for today's date --}}
                        @if ($currentTime >= $game->result_time)
                            @if (isset($this_year_results[$date][$game->id]) && $this_year_results[$date][$game->id]->first()->result == 100)
                                00
                            @else
                                {{ isset($this_year_results[$date][$game->id]) ? str_pad($this_year_results[$date][$game->id]->first()->result, 2, '0', STR_PAD_LEFT) : '-' }}
                            @endif
                        @else
                            -- {{-- Result time not reached yet --}}
                        @endif
                    @else
                        {{-- Show results without applying result_time for past/future dates --}}
                        @if (isset($this_year_results[$date][$game->id]))
                            @if ($this_year_results[$date][$game->id]->first()->result == 100)
                                00
                            @else
                                {{ str_pad($this_year_results[$date][$game->id]->first()->result, 2, '0', STR_PAD_LEFT) }}
                            @endif
                        @else
                            --
                        @endif
                    @endif
                </span>
            </td>
        @endforeach
    </tr>
    @endif
@endforeach
                        </tbody>
                        
                    </table>
                </div>
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
                                <td class="table_chart_section_01 forfirtcolor date col-md-2 text-center">
                                    <strong class="fon">Date</strong>
                                </td>
                                @foreach($games->whereNotIn('name', $selectedArray) as $game)
                                    <td class="table_chart_section forfirtcolor text-center">
                                        {{ strtoupper($game->name) }}
                                    </td>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($dates as $date)
                        @if($date <= now())
    <tr>
        <td class="forfirtcolor text-center">
            <span class="fon">{{ date('d-m', strtotime($date)) }}</span>
        </td>
        @foreach($games->whereNotIn('name', $selectedArray) as $game)
            <td>
                <span class="table_chart_section_02">
                    @php
                        $currentDate = \Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d');
                        $currentTime = \Carbon\Carbon::now('Asia/Kolkata')->format('H:i');
                    @endphp

                    @if ($currentDate == $date)
                        {{-- Apply the result_time condition for today's date --}}
                        @if ($currentTime >= $game->result_time)
                            @if (isset($this_year_results[$date][$game->id]) && $this_year_results[$date][$game->id]->first()->result == 100)
                                00
                            @else
                                {{ isset($this_year_results[$date][$game->id]) ? str_pad($this_year_results[$date][$game->id]->first()->result, 2, '0', STR_PAD_LEFT) : '-' }}
                            @endif
                        @else
                            -- {{-- Result time not reached yet --}}
                        @endif
                    @else
                        {{-- Show results without applying result_time for past/future dates --}}
                        @if (isset($this_year_results[$date][$game->id]))
                            @if ($this_year_results[$date][$game->id]->first()->result == 100)
                                00
                            @else
                                {{ str_pad($this_year_results[$date][$game->id]->first()->result, 2, '0', STR_PAD_LEFT) }}
                            @endif
                        @else
                            --
                        @endif
                    @endif
                </span>
            </td>
        @endforeach
    </tr>
    @endif
@endforeach
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

