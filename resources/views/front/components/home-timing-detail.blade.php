<div class="column-ad">
    <div class="card-body" style="box-sizing: border-box; flex: 1 1 auto; min-height: 1px; padding: 1rem 0.5rem; border: dashed red; background: linear-gradient(rgb(255, 216, 0), rgb(255, 255, 255)); border-radius: 20px; font-weight: bold; margin-top: 5px; margin-bottom: 5px;">
        <p><strong>--सीधे सट्टा कंपनी का No 1 खाईवाल--</strong></p>
        <p><strong>♕♕&nbsp;{{ strtoupper($settings['khaiwal_name']) }}
            &nbsp;BHAI&nbsp;KHAIWAL ♕♕</strong></p>
        @foreach ($games->where('other_chart_id',null) as $game )
            @if($game->english_name != 'disawar')
        <p><strong>⏰ {{ $game->name }} ------------ {{ \Carbon\Carbon::parse($game->time)->format('g:i A') }}</strong></p>
        @endif
        @endforeach
        @php
            $disawar = $games->where('english_name', 'disawar')->first();
        @endphp
        <p><strong>⏰ {{ $disawar->name }} ------------ {{ \Carbon\Carbon::parse($disawar->time)->format('g:i A') }}</strong></p>

        <p><strong>💸 Payment Option 💸</strong><br>PAYTM//BANK TRANSFER//PHONE PAY//GOOGLE PAY =&gt;<strong> ⏺️xxxxxxxxxx⏺️</strong><br>=====================================<br>=====================================</p>
        <p><strong>🤑 Rate list 💸</strong><br><strong>जोड़ी रेट 10-------960</strong><br><strong>हरूफ रेट 100-----960</strong></p>
        <p>♕♕ &nbsp;<strong>{{ strtoupper($settings['khaiwal_name']) }}  BHAI KHAIWAL &nbsp;</strong>♕♕</p>
        <p><a href="{{'https://wa.me/' . $settings['whatsapp_number']}}"><strong>Game play करने के लिये नीचे लिंक पर क्लिक करे</strong></a></p>
        <p><a href="{{'https://wa.me/' . $settings['whatsapp_number']}}"><img loading="lazy" src="{{asset('assets/img/whatsapp.png')}}" width="200px" height="69px" alt="Whatsapp to show game on this website"></a></p>
    </div>
</div>

@foreach ($otherChart as $other)
    <div class="column-ad">
        <div class="card-body" style="font-size: 20px;box-sizing: border-box; flex: 1 1 auto; min-height: 1px; padding: 1rem 0.5rem; border: dashed red; background: linear-gradient(rgb(255, 216, 0), rgb(255, 255, 255)); border-radius: 20px; font-weight: bold; margin-top: 5px; margin-bottom: 5px;">
            <div class="mt-4">
                {!! $other->chart_content !!}
            </div>
            <p><a href="{{'https://wa.me/' . $other->whatsapp_numbers}}"><strong>Game play करने के लिये नीचे लिंक पर क्लिक करे</strong></a></p>
            <p><a href="{{'https://wa.me/' . $other->whatsapp_numbers}}"><img loading="lazy" src="{{asset('assets/img/whatsapp.png')}}" width="200px" height="69px" alt="Whatsapp to show game on this website"></a></p>

        </div>
    </div>
@endforeach
