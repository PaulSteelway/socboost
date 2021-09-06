<meta name="robots" content="index,follow">

@hasSection ('description')
  @php
    $description = $__env->yieldContent('description');
    // Desc пустой
    if (iconv_strlen($description) < 2) {
      $description = __('site.description');
    }
  @endphp
@else
  @php
    $description = __('site.description');
  @endphp
@endif

<meta name="description" content="{{ $description }}">
<meta property="og:description" content="{{ $description }}">
<meta property="twitter:description" content="{{ $description }}">
<meta itemprop="description" content="{{ $description }}" />
<meta property="vk:description" content="{{ $description }}">

<meta property="twitter:card" content="summary_large_image">

@hasSection ('og_image')
  <meta name="image" content="@yield('og_image')"/>
  <meta property="og:image" content="@yield('og_image')"/>
  <meta name="twitter:image:src" content="@yield('og_image')">
@else
  <meta property="og:image:height" content="1257">
  <meta property="og:image:width" content="2400">
  <meta property="og:image" content="{{ URL::to(asset('/assets/og-image.jpg')) }}">
  <meta name="twitter:image:src" content="{{ URL::to(asset('/assets/og-image.jpg')) }}">
  <meta property="og:image:alt" content="{{ __('site.title') }}">
@endif
