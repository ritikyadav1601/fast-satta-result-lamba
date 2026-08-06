@php
    $setting = App\Models\Setting::pluck('value', 'key')->toArray();
@endphp

<section class="somelinks" style="overflow: hidden;"><a class="yellow-link mx-4" href="{{ route('front.privacy') }}">Privacy Policy</a><a class="yellow-link" href="/terms-and-conditions">Terms &amp; Conditions</a> <a class="yellow-link mx-4" href="{{ route('front.blogs') }}">Blogs</a><br></section>
<section class="somelinks2">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center"><strong>©️ 2024 {{ $setting['website_name'] }} All Rights Reserved</strong></div>
        </div>
    </div>
</section>
<section class="somelinks">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <ul>
                    <li style="color: rgb(255, 216, 0); padding: 0px; font-weight: 700;">!! DISCLAIMER - {{ $setting['disclaimer'] ?? '' }}</li>
                </ul>
            </div>
        </div>
    </div>
</section>