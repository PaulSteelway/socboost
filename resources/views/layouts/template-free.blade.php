<!DOCTYPE html>
<html dir="ltr" lang="{{ app()->getLocale() }}" prefix="og: http://ogp.me/ns#">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @include('layouts.meta.mobile')
    @include('layouts.meta.title')
    @include('layouts.meta.canonical')
    @include('layouts.meta.icon')
    @include('layouts.meta.description')
    @include('layouts.meta.fonts')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('ldjson')
    @include('layouts.meta.ldjson')

    @include('layouts.prod.gtag_free')
    @include('layouts.prod.yandex_free')
    @include('layouts.prod.unitpay_free')

    <link rel="stylesheet" type="text/css" href="{{ asset(mix('/css/app.css')) }}">
    @include('layouts.meta.addition-top')
    @stack('styles')
  </head>

  <body class="@yield('body_class')">
    <div id="app">

      @yield('innerblock')

    </div>

    <script type="text/javascript" src="{{ asset(mix('js/app.js')) }}"></script>
    <script type="text/javascript" src="{{ asset(mix('js/free.js')) }}"></script>
    <script type="text/javascript" src="{{ asset(mix('js/vue.js')) }}"></script>
    <script type="text/javascript" src="{{ asset('js/freePromotionTasks.js') }}"></script>
    @include('layouts.meta.addition-bottom')

    @stack('scripts')
    @include('layouts.prod.jivo')
  </body>
</html>
