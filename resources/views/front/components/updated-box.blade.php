<section class="circlebox">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="liveresult">
                    <div id="clockbox"></div>
                    <p class="hintext" style="padding: 0px;">हा भाई यही आती हे सबसे पहले खबर रूको और देखो</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="circlebox2">
    @php
        $gamii = $games->where('result_time', '>=', \Carbon\Carbon::now('Asia/Kolkata')->format('H:i'))->take(2);
        $lastGame = $games->where('result_time', '<', \Carbon\Carbon::now('Asia/Kolkata')->format('H:i'))->sortByDesc('result_time')->first();
        if($lastGame == null){
        $NightGame = $games->sortByDesc('result_time')->first();
      }
        $disawar = $games->where('english_name', 'disawar')->first();
    @endphp
    @foreach($gamii as $game)
    <div>
        <div class="sattaname">
            <p style="margin: 0px;">{{ $game?->name }}</p>
        </div>
        <div class="sattaname">
            <p style="margin: 0px; padding: 0px;">
                @if (\Carbon\Carbon::now('Asia/Kolkata')->format('H:i') < $game?->result_time)
                <img style="width: 60px; height: 60px;" src="{{asset('assets/img/wait.gif')}}" alt="wait icon">
                @else
                {{ $game?->todayResult()?->result }}
               @endif
            </p>
        </div>
    </div>
    @endforeach
    @if ($lastGame)
    <div class="sattaname">
        <p style="margin: 0px;">{{ $lastGame?->name }}</p>
    </div>
    <div class="sattaname">
        @if($lastGame?->todayResult()?->result == null)
        <p style="margin: 0px; padding: 0px;"><img style="width: 60px; height: 60px;" src="{{asset('assets/img/wait.gif')}}" alt="wait icon"></p>
        @elseif($lastGame?->todayResult()?->result == 100)
        <p style="margin: 0px; padding: 0px;"> 00 </p>
        @else
        <p style="margin: 0px; padding: 0px;">
            @if(!empty($lastGame?->todayResult()?->result))
             {{ str_pad($lastGame?->todayResult()?->result, 2, '0', STR_PAD_LEFT) }}</p>
             @else
             <img style="width: 60px; height: 60px;" src="{{asset('assets/img/wait.gif')}}" alt="wait icon">
             @endif
        @endif
    </div>
    @endif
    @if (isset($NightGame))
    <div class="sattaname">
        <p style="margin: 0px;">{{ $NightGame?->name }}</p>
    </div>
    <div class="sattaname">
        @if($NightGame?->yesterdayResult()?->result == null)
        <p style="margin: 0px; padding: 0px;"> <img style="width: 60px; height: 60px;" src="{{asset('assets/img/wait.gif')}}" alt="wait icon"> </p>
        @elseif($NightGame?->yesterdayResult()?->result == 100)
        <p style="margin: 0px; padding: 0px;"> 00 </p>
        @else
        <p style="margin: 0px; padding: 0px;"> {{ str_pad($NightGame?->yesterdayResult()?->result, 2, '0', STR_PAD_LEFT) ?? '--' }}</p>
        @endif
    </div>
    @endif
</section>
<div class="wrapper-yellow">
    <section class="sattadividerr">
        <div class="container">
            <div class="col-md-12 text-center" style="padding-bottom: 15px;">
                <h4 style="font-size: 24px; font-weight: normal; text-transform: uppercase; margin: 0px; padding: 0px;">Disawar</h4>
                <p style="font-size: 18px; font-weight: 400;">{{ \Carbon\Carbon::parse($disawar?->result_time)->format('g:i A') }}</p>
                <strong style="font-size: 20px; letter-spacing: 2px;">
                    @if($disawar?->yesterdayResult()?->result == 100)
                        00
                    @else
                        {{ $disawar?->yesterdayResult()?->result !== null ? str_pad($disawar->yesterdayResult()->result, 2, '0', STR_PAD_LEFT) : '--' }}
                    @endif
                
                    <img src="{{ asset('assets/img/arrow.gif') }}" alt="arrow icon" height="30px" width="30px" style="margin-left: 5px; margin-right: 5px;">
                
                    @if($disawar?->todayResult()?->result == 100)
                        00
                    @elseif($disawar?->todayResult()?->result !== null)
                        {{ str_pad($disawar->todayResult()->result, 2, '0', STR_PAD_LEFT) }}
                    @else
                        <img style="width: 30px; height: 30px;" src="{{ asset('assets/img/wait.gif') }}" alt="wait icon">
                    @endif
                </strong></div>
        </div>
    </section>
</div>

<script>
    function updateClock() {
        var now = new Date();
        var options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true };
        var timeString = now.toLocaleString('en-US', options);
        document.getElementById('clockbox').innerHTML = timeString;
    }
    
    // Update the clock every second
    setInterval(updateClock, 1000);
    
    // Initial call to display the current time right away
    updateClock();
    </script>