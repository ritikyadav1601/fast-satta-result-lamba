@php
    use Carbon\Carbon;$disawar = $games->where('english_name', 'disawar')->first();
@endphp
<section class="octoberresultchart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h3>Satta Chart, Faridabad Satta, Ghaziabad Result</h3>
            </div>
        </div>
    </div>
</section>
@foreach ($gamess as $games)
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
                                
                                @foreach ($games as $game)
                                    <tbody>
                                    @if($game->english_name != 'disawar')
                                        <tr>
                                            <td class="foryellow">
                                                <a class="gamenameeach"
                                                   href="{{ route('game.show', $game->id) }}">{{ $game->name }}</a><br>
                                                {{ Carbon::parse($game->result_time)->format('h:i A') }}<br>
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
                                    @endif
                                    </tbody>
                                @endforeach

                                <!--<tbody>-->
                                <!--<tr>-->
                                <!--    <td class="foryellow"><a class="gamenameeach"-->
                                <!--                             href="{{ route('game.show', $disawar->id) }}">{{ $disawar->name }}</a><br> {{ Carbon::parse($disawar->result_time)->format('h:i A') }}-->
                                <!--        <br></td>-->
                                <!--    <td class="yesterday-number">-->
                                <!--        <div class="special-bold"-->
                                <!--             style="margin-bottom: 0px; letter-spacing: 2px; font-size: 22px;">-->
                                <!--            @if($disawar->yesterday_result == 100)-->
                                <!--                00-->
                                <!--            @else-->
                                <!--                @if (!empty($disawar->yesterday_result))-->
                                <!--                    {{ str_pad($disawar->yesterday_result, 2, '0', STR_PAD_LEFT) }}-->
                                <!--                @else-->
                                <!--                    <img style="width: 40px; height: 40px;"-->
                                <!--                         src="{{ asset('assets/img/wait.gif') }}" alt="wait icon">-->
                                <!--                @endif-->
                                <!--            @endif-->
                                <!--        </div>-->
                                <!--    </td>-->
                                <!--    <td class="today-number">-->
                                <!--        <div style="margin-bottom: 0px; letter-spacing: 2px; font-size: 22px;">-->
                                <!--            @if($disawar->today_result == 100)-->
                                <!--                00-->
                                <!--            @else-->
                                <!--                @if (!empty($disawar->today_result))-->
                                <!--                    {{ str_pad($disawar->today_result, 2, '0', STR_PAD_LEFT) }}-->
                                <!--                @else-->
                                <!--                    <img style="width: 40px; height: 40px;"-->
                                <!--                         src="{{ asset('assets/img/wait.gif') }}" alt="wait icon">-->
                                <!--                @endif-->
                                <!--            @endif-->
                                <!--        </div>-->
                                <!--    </td>-->
                                <!--</tr>-->
                                <!--</tbody>-->
                            </table>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
@endforeach

<div class="column-ad card-body"
     style="box-sizing: border-box; flex: 1 1 auto; min-height: 1px; padding: 1rem 0.5rem; border: dashed red; background: linear-gradient(rgb(255, 216, 0), rgb(255, 255, 255)); border-radius: 20px; font-weight: bold; margin-top: 5px; margin-bottom: 5px;">
    <p>🙏🏿नमस्कार साथियो 🙏🏿</p>
    <p>किसी भी तरह की कोई शिकायत के लिए कंपनी के मैनेजर से संपर्क करे &nbsp; &nbsp;</p>
    <p>----{{ strtoupper($settings['owner_name']) }} ----</p>
    <p><a href="{{'https://wa.me/' . $settings['owner_number']}}"><img
                src="{{asset('assets/img/whatsapp.png')}}"
                alt="Whatsapp to show game on this website"></a></p>
    <p>NOTE: &nbsp; इस नंबर पर लीक गेम नही मिलता गेम लेने वाले भाई कॉल या मैसेज न करें।</p>
    <p>किसी भी भाई को किसी भी तरह की कोई शिकायत या परेशानी हो तो हमसे telegram पर संपर्क करे</p>
    <p><a href="{{'https://t.me/' . $settings['owner_number']}}"><img
                src="{{asset('assets/img/tel.webp')}}"
                alt="Telegram link to show game"></a></p></div>
                

