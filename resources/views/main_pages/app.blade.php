@extends('layouts.customer')

@section('title', __('BOOSTER-APP for iPhone') . ' - ' . __('site.site'))

@section('content')
    <main style="padding-top: 100px; min-height: 73vh">
        <section class="app app--smm">
        <div class="container">
        <div class="row">
        <div class="col">
        <div class="app__block">
        <div class="app__title">{{__('BOOSTER-APP for iPhone')}} <br> {{__('BOOSTER-APP for Android')}}<br>{{__('BOOSTER-BOT for Telegram')}}</div>
        <div class="app__img">
        <img src="/images/app.png" alt="">
        </div>
        <div class="app__text">{{__('Install our application with the opportunity of placing order. Keep up to your orders updates and the latest project news.')}}</div>
        </div>
        </div>
        </div>
        </div>
        </section>
        <section class="app app--ios">
        <div class="container">
        <div class="row">
        <div class="col">
        <div class="app__block app__block--ios">
        <div class="app__subtitle">{{__('IOS')}}</div>
        <div class="app__title">{{__('About application')}}</div>
        <div class="app__img app__img--ios">
        <img src="/images/ios.png" alt="">
        </div>
        <div class="app__text">{{__('Especially for your convenience we have developed an application for iOS, where you can order the service you need without registration in a couple of clicks and pay for it with Apple Pay on your iPhone. The application is available on the App Store.')}}</div>
        <button type="button" class="app__btn">{{__('Download iOS App')}}</button>
        </div>
        </div>
        </div>
        </div>
        </section>
        <section class="app app--android">
        <div class="container">
        <div class="row">
        <div class="col">
        <div class="app__block">
        <div class="app__subtitle">{{__('Android')}}</div>
        <div class="app__title">{{__('About application')}}</div>
        <div class="app__img">
        <img src="/images/android.png" alt="">
        </div>
        <div class="app__text">{{__('Especially for your convenience we have developed an application for Android, where you can order the service you need without registration in a couple of clicks and pay for it with Google Pay on your smartphone. The application is available on Google Play.')}}</div>
        <button type="button" class="app__btn">{{__('Download for Android')}}</button>
        </div>
        </div>
        </div>
        </div>
        </section>
        <section class="app app--tg1200">
        <div class="container">
        <div class="row">
        <div class="col">
        <div class="app__block app__block--tg">
        <div class="app__title">{{__('Telegram Bot')}}</div>
        <div class="app__img app__img--tg">
        <img src="/images/image.png" alt="">
        </div>
        <div class="app__text">{{__('For all newbies we offer the discount for the first order from Telegram Bot.')}}</div>
        <a href="https://tg.guru/socboostbot" class="app__btn">{{__('Open Telegram Bot')}}</a>
        </div>
        </div>
        </div>
        </div>
        </section>
    </main>
@endsection
