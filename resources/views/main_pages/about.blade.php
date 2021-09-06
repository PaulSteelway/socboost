@extends('layouts.customer')

@section('title', __('About Us') . ' - ' . __('site.site'))

@section('content')
    <main style="padding-top: 100px; min-height: 73vh">

     {{--about--}}
        <section class="about-us">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h2 class="about-us__title">{{__('About Us')}}</h2>
                        <p class="about-us__text">{{__('An important part of the promotion and recognition of a brand or company in our time is Social Media Marketing (social media marketing). Consider the main points and features of promotion in social networks, as well as why advertising in social networks is extremely important and promising for business development.')}}</p>
                        <p class="about-us__text">{{__('Getting started with the site is not at all difficult. First of all, you need to register and replenish your personal account for an amount sufficient to pay for the desired services. The performance of a service begins automatically after you pay for the order. All stages of execution can be tracked online at any convenient time.')}}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="about-us__img">
                            <img src="images/man.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="map">
            <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3Aa672802a2c79c1668904342355789f8ef6e7a5ebed4d005892409f8bad29aced&amp;source=constructor" width="100%" height="700" frameborder="0"></iframe>
        </section>
        <section class="about-us-contacts">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="about-us-contacts__info">
                                <h2 class="about-us-contacts__title">{{__('Our contacts')}}</h2>
                            <div class="about-us-contacts__block">
                                <div class="about-us-contacts__block-name">{{__('Support Schedule')}}</div>
                                <div class="about-us-contacts__block-info">10.00 - 18.00</div>
                            </div>
                            <div class="about-us-contacts__block">
                                <div class="about-us-contacts__block-name">Telegram:</div>
                                <div class="about-us-contacts__block-info">
                                    <a href="http://teleg.run/socialboosterarina" target="_blank">http://teleg.run/socialboosterarina</a>
                                </div>
                                <div class="about-us-contacts__support">({{__('Support service')}})</div>
                            </div>
                            <div class="about-us-contacts__block">
                                <div class="about-us-contacts__block-name">E-Mail:</div>
                                <div class="about-us-contacts__block-info">
                                    <a href="mailto:support@socialbooster.me">support@socialbooster.me</a>
                                </div>
                                <div class="about-us-contacts__support">({{__('For personal questions only to the administration')}})</div>
                            </div>
                            <div class="about-us-contacts__block">
                                <div class="about-us-contacts__block-name">{{__('Phone number')}}</div>
                                <div class="about-us-contacts__block-info">
                                    <a href="tel:+78005338048">+7 800 533 80 48</a>
                                </div>
                            </div>
                            <div class="about-us-contacts__logo-container">
                                <img class="about-us-contacts__logo" src="svg/logo-dark.svg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
