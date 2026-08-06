@php
    use Carbon\Carbon;
@endphp
<section class="octoberresultchart" style="margin-top: 20px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h3>Extra Games Result</h3>
            </div>
        </div>
    </div>
</section>

    <section class="tablebox1" style="margin: 0px 0px 5px;">
        <div class="container-fluid">
            <div class="row">
                <article style="padding: 0px;">
                    <div class="col-md-12 nopadding" style="margin-bottom: 20px;">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="forblack">
                                <tr>
                                    <th class="col-md-4 text-center"
                                        style="width: 37%; background: rgb(0, 0, 0); color: rgb(255, 255, 255);">सट्टा
                                        का नाम
                                    </th>
                                    <th class="col-md-4 text-center"
                                        style="background: rgb(0, 0, 0); color: rgb(255, 255, 255);">कल आया था
                                    </th>
                                    <th class="col-md-4 text-center"
                                        style="background: rgb(0, 0, 0); color: rgb(255, 255, 255);">आज का रिज़ल्ट
                                    </th>
                                </tr>
                                </thead>
                                
                                @foreach ($extraGames as $game)
                                    <tbody>
                                        <tr>
                                            <td class="foryellow">
                                                <a class="gamenameeach"
                                                   href="{{ route('extra-game.show', $game->id) }}">{{ $game->name }}</a><br>
                                                {{ Carbon::parse($game->result_time)->format('h:i A') }}<br>
                                                <h3 class="game-link" style="margin-top: 10px; font-size: 16px;"><a href="{{ route('extra-game.show', $game->id) }}" style="color: #fff; text-decoration: underline;">Record Chart</a></h3>
                                            </td>
                                            <td class="yesterday-number">
                                                <div class="special-bold"
                                                     style="margin-bottom: 0px; letter-spacing: 2px; font-size: 22px;">
                                                    @if($game->yesterday_result == 100)
                                                        00
                                                    @elseif($game->yesterday_result == null)
                                                    --
                                                    @else
                                                        {{ str_pad($game->yesterday_result, 2, '0', STR_PAD_LEFT) ?? '--' }}
                                                    @endif

                                                </div>
                                            </td>
                                            <td class="today-number">
                                                <div style="margin-bottom: 0px; letter-spacing: 2px; font-size: 22px;">
                                                    @if (Carbon::now('Asia/Kolkata')->format('H:i') < $game->result_time)
                                                        <img style="width: 40px; height: 40px;"
                                                             src="{{asset('assets/img/wait.gif')}}" alt="wait icon">
                                                    @else
                                                        @if($game->today_result == 100)
                                                            00
                                                        @elseif($game->today_result == null)
                                                        --
                                                        @else
                                                            @if (!empty($game->today_result))
                                                                {{ str_pad($game->today_result, 2, '0', STR_PAD_LEFT) }}
                                                            @else
                                                                <img style="width: 40px; height: 40px;"
                                                                     src="{{ asset('assets/img/wait.gif') }}"
                                                                     alt="wait icon">
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
