<meta http-equiv="content-language" content="{{ config('app.locale') }}">
<meta name="twitter:domain" content="{{ config('app.url') }}">

@hasSection ('canonical')
  <link rel="canonical" href="@yield('canonical')">
  <meta property="og:url" content= "@yield('canonical')" />
  <meta property="twitter:url" content= "@yield('canonical')" />
  <meta property="vk:url" content= "@yield('canonical')" />
@else
  <link rel="canonical" href="{{ URL::current() }}">
  <meta property="og:url" content= "{{ URL::current() }}" />
  <meta property="twitter:url" content= "{{ URL::current() }}" />
  <meta property="vk:url" content= "{{ URL::current() }}" />
@endif

@hasSection ('og_type')
  <meta property="og:type" content="@yield('og_type')">
@else
  <meta property="og:type" content="website">
@endif
<meta property="og:locale" content="{{ __('site.og-locale') }}" />

@if (!isFreePromotionSite())
  <link rel="alternate" hreflang="ru" href="https://socialbooster.me/" />
  <link rel="alternate" hreflang="en" href="https://en.socialbooster.me/"/>
@endif
