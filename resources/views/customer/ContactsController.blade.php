@extends('layouts.customer')

@section('title', __('Our contacts') . ' - ' . __('site.site'))

@section('content')
    <main style="padding-top: 100px;">
        <section class="contacts">
            <div class="container">
                <h2 class="contacts__title">{{__('Our contacts')}}</h2>
                <div class="row contacts__row">
                    <div class="col-lg-6">
                        <div class="contacts__info">
                            <div class="contact-block">
                                <div class="contact-block__name">{{__('Support Schedule')}}</div>
                                <div class="contact-block__info">10.00 - 18.00 ({{__('Mon')}} - {{__('Fri')}})</div>
                            </div>
                            <div class="contact-block">
                                <div class="contact-block__name">{{__('Support service')}}:</div>
                                <div class="contact-block__info">
                                    <a href="http://teleg.run/socialboosterarina" target="_blank">http://teleg.run/socialboosterarina</a>
                                </div>
                                <div class="contact-block__support">({{__('Support service')}})</div>
                            </div>
                            <div class="contact-block">
                                <div class="contact-block__name">{{__('Telegram channel')}}:</div>
                                <div class="contact-block__info">
                                    <a href="https://tg.guru/sclbstr" target="_blank">https://tg.guru/sclbstr</a>
                                </div>
                                <div class="contact-block__support">{{__('(Telegram channel)')}}</div>
                            </div>
                            @if(!Auth::guest() && Auth::user()->is_premium)
                                <div class="contact-block">
                                    <div class="contact-block__name">{{__('Premium Channel')}}</div>
                                    <div class="contact-block__info">
                                        <a href="https://t.me/joinchat/AAAAAFfm3KNVAOGCrE3uzg" target="_blank">https://t.me/joinchat/AAAAAFfm3KNVAOGCrE3uzg</a>
                                    </div>
                                    <div class="contact-block__support">{{__('(Telegram channel)')}}</div>
                                </div>
                            @endif
                            <div class="contact-block">
                                <div class="contact-block__name">E-Mail:</div>
                                <div class="contact-block__info">
                                    <a href="mailto:support@socialbooster.me">support@socialbooster.me</a>
                                </div>
                                <div class="contact-block__support">({{__('Technical support')}})</div>
                            </div>
                            <div class="contact-block">
                                <div class="contact-block__name">E-Mail:</div>
                                <div class="contact-block__info">
                                    <a href="http://teleg.run/socialboosterarina"
                                       target="_blank">admin@socialbooster.me</a>
                                </div>
                                <div class="contact-block__support">({{__('For cooperation and suggestions')}})
                                </div>
                            </div>
                            <div class="contact-block">
                                <div class="contact-block__name">{{__('Phone number')}}</div>
                                <div class="contact-block__info">
                                    <a href="tel:+78005338048">+7 800 533 80 48</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="contacts__img">
                            <img src="images/contact.png" alt="">
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>
@endsection
