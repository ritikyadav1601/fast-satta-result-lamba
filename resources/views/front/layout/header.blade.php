<section class="topboxnew">
    @php
        $setting = App\Models\Setting::pluck('value', 'key')->toArray();
    @endphp
    <div class="container-fluid">
        <div class="col-md-16 nopadding">
            <div class="newnav">
                <ul>
                    <li><a class="{{ Route::currentRouteName() == 'front.home' ? 'active' : '' }}" href="{{ route('front.home') }}">Home</a></li>
                    <li><a class="{{ Route::currentRouteName() == 'front.chart' ? 'active' : '' }}" href="{{ route('front.chart') }}">Chart</a></li>
                    <li><a class="{{ Route::currentRouteName() == 'front.contact' ? 'active' : '' }}" href="{{ route('front.contact') }}">Contact</a></li>
                    <li><a class="{{ Route::currentRouteName() == 'front.disclaimer' ? 'active' : '' }}" href="{{ route('admin.login') }}">Login</a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="text_slide">
                <marquee style="color: rgb(255, 255, 255);">
                    @if (Route::currentRouteName() == 'front.home')

                    <p style="font-size: 16px; text-align: center;">{{ $settings['home_page_float_text'] }}</p>
                @else

                    <p style="font-size: 16px; text-align: center;">{{ $setting['secondary_page_float_text'] }}</p>
                    @endif
                </marquee>
            </div>
        </div>
    </div>
</section>
