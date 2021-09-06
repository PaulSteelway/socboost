<div style="width:600px;margin:50px auto;background-color:#ffffff;display:flex;font-family:'Montserrat',sans-serif;font-weight:500;line-height:1.5;color:#35414d;">
    <div style="width:100%;">
        <div style="text-align:center;">
            @yield('content')
        </div>

        <div style="margin-top:100px;">
            <div style="float:left;font-weight:600;font-size:14px;">{{$lang == 'en' ? 'With love, SocialBooster team' : 'С любовью, команда SocialBooster'}}
                <span style="color: red">❤</span>️
            </div>
{{--            <div style="float:right;color:#35414d;font-weight:600;font-size:24px;display:flex;line-height:0.8;">--}}
{{--                <span>socialb</span>--}}
{{--                <span style="color:#f67555;font-size:40px;display:flex;line-height:0.4;">∞</span>--}}
{{--                <span>ster</span>--}}
{{--            </div>--}}
        </div>
    </div>
</div>