@extends('layouts.customer-template')


@section('title', \App\Models\Setting::getValue('seo_title_' . app()->getLocale()) . ' - ' . __('site.site'))
@section('keywords', \App\Models\Setting::getValue('seo_keywords_' . app()->getLocale()))
@section('description', \App\Models\Setting::getValue('seo_description_' . app()->getLocale()))

@section('body_class', "homepage")

@section('content')

  <main>
    <section class="our-services" id="our-services">

      <div class="container">
        <div class="row our-services__row">
          <div class="col-12 col-lg-7 our-services__text-container">
            <div class="our-services__text">
              <h2 class="our-services__title">
                {{ __('site.our_service') }}
              </h2>
              <p class="our-services__desc">
                {{__('Nowadays social networks are not only a place for online communication, but an excellent opportunity for self-expression and income generation. However, sometimes in order to get a significant result in the promotion, own forces are not enough. In this case, it is worth appealing to professionals')}}
              </p>
            </div>
          </div>

          <div class="col-12 col-lg-5">
            <div class="our-services__img">
              <img src="{{ asset('svg/promotion.svg') }}" alt="Promotion">
            </div>
          </div>
        </div>
      </div>

      @include('customer.main.our-service')
    </section>

    @include('customer.main.offer')
    @include('customer.main.buy-ready')
    @include('customer.main.special-offers')
    @include('customer.main.subscribe')
    @include('customer.main.tg-bot')
    @include('customer.main.reviews')

    @push('scripts')
      @if(\Auth::check() && session()->exists(\Auth::id() . '_' . 'unitpay'))
        <script>
          var unitpay = {!! json_encode(session()->pull(\Auth::id() . '_' . 'unitpay')) !!};
        </script>
      @endif
    @endpush

  </main>

@endsection
