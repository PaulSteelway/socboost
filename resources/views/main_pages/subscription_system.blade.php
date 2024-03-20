@extends('layouts.customer')

@section('title', __('Increase the statistics and activity! Subscription for everybody') . ' - ' . __('site.site'))

@section('content')
    <main style="padding-top: 100px; min-height: 73vh">
        <section class="statistic-boost">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="statistic-boost__block">
                            <div class="statistic-boost__title">{{__('Increasing the activity and statistics')}}</div>
                            <div class="statistic-boost__subtitle">{{__('Increase the statistics and activity! Subscription for everybody')}}</div>
                            <div class="statistic-boost__img">
                                <img src="images/statistic.png" alt="">
                            </div>
                                <div class="statistic-boost__text">{{__('We offer additional services on our platform with discount and bonus for your first order!')}}.</div>
                            <div class="statistic-boost__price">{{__('From')}} {{ number_format(socialboosterPriceByAmount(499), 2, '.', '')}} $ / {{__('month')}}</div>
                            <div class="statistic-boost__btns">
                                <button class="statistic-boost__btn statistic-boost__btn--orange">{{__('Subscribe')}}</button>
                                <button class="statistic-boost__btn">{{__('Compare benefits')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="subscribe-advantages">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="subscribe-advantage">
                            <div class="subscribe-advantage__img">
                                <img src="images/statistic-icon.png" alt="">
                            </div>
                            <div class="subscribe-advantage__text">
                                {{__('New posts increase statistics immediately!')}}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="subscribe-advantage">
                            <div class="subscribe-advantage__img">
                                <img src="images/settings-icon.png" alt="">
                            </div>
                            <div class="subscribe-advantage__text">
                                {{__('Easy way to subscribe once and change the settings anytime!')}}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="subscribe-advantage">
                            <div class="subscribe-advantage__img">
                                <img src="images/service.png" alt="">
                            </div>
                            <div class="subscribe-advantage__text">
                                {{__('To all subscriptions connected only our best services!')}}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="subscribe-advantage">
                            <div class="subscribe-advantage__img">
                                <img src="images/subscribe.png" alt="">
                            </div>
                            <div class="subscribe-advantage__text">
                                {{__('Subscription works even when you sleep!')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="how-subscribe">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="how-subscribe__title">{{__('How to subscribe?')}}</div>
                        <div class="how-subscribe__text">{{__('Come to the page with service of subscription for chosen social network. Add all settings and place the order.')}}</div>
                        <div class="how-subscribe__img">
                            <img src="images/subscription-system.png" alt="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="how-subscribe__step">
                            <div class="how-subscribe__step-number">01</div>
                            <div class="how-subscribe__step-text">
                                <div class="how-subscribe__step-name">
                                    {{__('Registration')}}
                                </div>
                                <div class="how-subscribe__step-desc">
                                    {{__('You register on our website.')}}
                                </div>
                            </div>
                        </div>
                        <div class="how-subscribe__step">
                            <div class="how-subscribe__step-number">02</div>
                            <div class="how-subscribe__step-text">
                                <div class="how-subscribe__step-name">
                                    {{__('Rules')}}
                                </div>
                                <div class="how-subscribe__step-desc">
                                    {{__('While registration accept terms of the agreement and subscription.')}}
                                </div>
                            </div>
                        </div>
                        <div class="how-subscribe__step">
                            <div class="how-subscribe__step-number">03</div>
                            <div class="how-subscribe__step-text">
                                <div class="how-subscribe__step-name">
                                    {{__('Settings')}}
                                </div>
                                <div class="how-subscribe__step-desc">
                                    {{__('Do all necessary settings less than in 5 minutes.')}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="how-subscribe__step">
                            <div class="how-subscribe__step-number">04</div>
                            <div class="how-subscribe__step-text">
                                <div class="how-subscribe__step-name">
                                    {{__('Payment')}}
                                </div>
                                <div class="how-subscribe__step-desc">
                                    {{__('Place the order to subscription and disburse the amount of subscription.')}}
                                </div>
                            </div>
                        </div>
                        <div class="how-subscribe__step">
                            <div class="how-subscribe__step-number">05</div>
                            <div class="how-subscribe__step-text">
                                <div class="how-subscribe__step-name">
                                    {{__('Statistics')}}
                                </div>
                                <div class="how-subscribe__step-desc">
                                    {{__('See the increasing of parameters you have chosen.')}}
                                </div>
                            </div>
                        </div>
                        <div class="how-subscribe__step">
                            <div class="how-subscribe__step-number">06</div>
                            <div class="how-subscribe__step-text">
                                <div class="how-subscribe__step-name">
                                    {{__('Management')}}
                                </div>
                                <div class="how-subscribe__step-desc">
                                    {{__('Adjust the parameters, settings or pause the subscription whenever you want.')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="subscription-business">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="subscription-business__title">{{__('Subscription is the best choice for your business!')}}</div>
                        <table class="subscription-business__table">
                            <thead class="subscription-business__thead">
                                <tr class="subscription-business__tr">
                                    <th class="subscription-business__th">#</th>
                                    <th class="subscription-business__th">{{__('Advantages')}}</th>
                                    <th class="subscription-business__th">{{__('Subscriptions')}}</th>
                                    <th class="subscription-business__th">{{__('Regular promotion')}}</th>
                                </tr>
                            </thead>
                            <tbody class="subscription-business__tbody">
                                <tr class="subscription-business__tr">
                                    <td class="subscription-business__td">1</td>
                                    <td class="subscription-business__td">{{__('There\'s no need to place the order manually every time')}}</td>
                                    <td class="subscription-business__td"><img src="images/check.png" alt=""></td>
                                    <td class="subscription-business__td"><img src="images/not.png" alt=""></td>
                                </tr>
                                <tr class="subscription-business__tr">
                                    <td class="subscription-business__td">2</td>
                                    <td class="subscription-business__td">{{__('Ability to pay on credit or in installments')}}</td>
                                    <td class="subscription-business__td"><img src="images/check.png" alt=""></td>
                                    <td class="subscription-business__td"><img src="images/not.png" alt=""></td>
                                </tr>
                                <tr class="subscription-business__tr">
                                    <td class="subscription-business__td">3</td>
                                    <td class="subscription-business__td">{{__('Save your time!')}}</td>
                                    <td class="subscription-business__td"><img src="images/check.png" alt=""></td>
                                    <td class="subscription-business__td"><img src="images/not.png" alt=""></td>
                                </tr>
                                <tr class="subscription-business__tr">
                                    <td class="subscription-business__td">4</td>
                                    <td class="subscription-business__td">{{__('Operates steadily and smoothly 24 hours a day, 7 days a week and all year round')}}</td>
                                    <td class="subscription-business__td"><img src="images/check.png" alt=""></td>
                                    <td class="subscription-business__td"><img src="images/check.png" alt=""></td>
                                </tr>
                                <tr class="subscription-business__tr">
                                    <td class="subscription-business__td">5</td>
                                    <td class="subscription-business__td">{{__('Save for orders from new accounts')}}</td>
                                    <td class="subscription-business__td"><img src="images/check.png" alt=""></td>
                                    <td class="subscription-business__td"><img src="images/not.png" alt=""></td>
                                </tr>
                                <tr class="subscription-business__tr">
                                    <td class="subscription-business__td">6</td>
                                    <td class="subscription-business__td">{{__('Help move to trends and recommendations')}}</td>
                                    <td class="subscription-business__td"><img src="images/check.png" alt=""></td>
                                    <td class="subscription-business__td"><img src="images/not.png" alt=""></td>
                                </tr>
                                <tr class="subscription-business__tr">
                                    <td class="subscription-business__td">7</td>
                                    <td class="subscription-business__td">{{__('Availability of a lifetime warranty on every order')}}</td>
                                    <td class="subscription-business__td"><img src="images/check.png" alt=""></td>
                                    <td class="subscription-business__td"><img src="images/check.png" alt=""></td>
                                </tr>
                                <tr class="subscription-business__tr">
                                    <td class="subscription-business__td">8</td>
                                    <td class="subscription-business__td">{{__('Discount on internal discount tariff system')}}</td>
                                    <td class="subscription-business__td"><img src="images/check.png" alt=""></td>
                                    <td class="subscription-business__td"><img src="images/not.png" alt=""></td>
                                </tr>
                                <tr class="subscription-business__tr">
                                    <td class="subscription-business__td">9</td>
                                    <td class="subscription-business__td">{{__('Free activity on the first publication (post or video)')}}</td>
                                    <td class="subscription-business__td"><img src="images/check.png" alt=""></td>
                                    <td class="subscription-business__td"><img src="images/not.png" alt=""></td>
                                </tr>
                                <tr class="subscription-business__tr">
                                    <td class="subscription-business__td">10</td>
                                    <td class="subscription-business__td">{{__('Priority assistance in the technical support service')}}</td>
                                    <td class="subscription-business__td"><img src="images/check.png" alt=""></td>
                                    <td class="subscription-business__td"><img src="images/check.png" alt=""></td>
                                </tr>
                                <tr class="subscription-business__tr">
                                    <td class="subscription-business__td">11</td>
                                    <td class="subscription-business__td">{{__('Major single order discount')}}</td>
                                    <td class="subscription-business__td"><img src="images/check.png" alt=""></td>
                                    <td class="subscription-business__td"><img src="images/not.png" alt=""></td>
                                </tr>
                                <tr class="subscription-business__tr">
                                    <td class="subscription-business__td">12</td>
                                    <td class="subscription-business__td">{{__('Organic activity after publication without delay')}}</td>
                                    <td class="subscription-business__td"><img src="images/check.png" alt=""></td>
                                    <td class="subscription-business__td"><img src="images/not.png" alt=""></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="subscription-business__btn-container">
                            <button type="button" class="subscription-business__btn">{{__('Subscribe')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="settings">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="settings__block">
                            <div class="settings__title">{{__('Multiple settings')}}</div>
                            <p class="settings__text">{{__('You can subscribe with individual settings, SMM promotion has never been such easy.')}}</p>
                            <p class="settings__text">{{__('By purchasing the subscription you will have effective and live promotion in your social networks which will promote you to trends.')}}</p>
                            <div class="settings__img">
                                <img src="images/settings.png" alt="">
                            </div>
                            <ul class="settings__list">
                                <li class="settings__item">
                                    <img src="images/icons/views-video-hover.svg" alt="">
                                    <div class="setting-name">{{__('Views')}}</div>
                                </li>
                                <li class="settings__item">
                                    <img src="images/icons/share-hover.svg" alt="">
                                    <div class="setting-name">{{__('Share')}}</div>
                                </li>
                                <li class="settings__item">
                                    <img src="images/icons/followers-hover.svg" alt="">
                                    <div class="setting-name">{{__('Subscribers')}}</div>
                                </li>
                                <li class="settings__item">
                                    <img src="images/icons/comment-hover.svg" alt="">
                                    <div class="setting-name">{{__('Comments')}}</div>
                                </li>
                                <li class="settings__item">
                                    <img src="images/icons/like.svg" alt="">
                                    <div class="setting-name">{{__('Likes')}}</div>
                                </li>
                                <li class="settings__item">
                                    <img src="images/icons/save-hover.svg" alt="">
                                    <div class="setting-name">{{__('Conservation')}}</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="subscribe-all">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="subscribe-all__title">{{__('Subscription for everyone and everything')}}</div>
                        <ul class="subscribe__list">
                            <li class="subscribe__item">
                                <div class="subscribe__item-img">
                                    <img src="svg/youtube.svg" alt="">
                                </div>
                                <div class="subscribe__item-text">
                                    <div class="subscribe__item-name">
                                        {{__('Youtube')}}
                                    </div>
                                    <div class="subscribe__item-desc">
                                        {{__('Post the video and receive the traffic with activity for trends immediately.')}}
                                    </div>
                                </div>
                            </li>
                            <li class="subscribe__item">
                                <div class="subscribe__item-img subscribe__item-img--inst">
                                    <img src="svg/instagram.svg" alt="">
                                </div>
                                <div class="subscribe__item-text">
                                    <div class="subscribe__item-name">
                                        {{__('Instagram')}}
                                    </div>
                                    <div class="subscribe__item-desc">
                                        {{__('Activity on photos and videos in profile right after the publication.')}}
                                    </div>
                                </div>
                            </li>
                            <li class="subscribe__item">
                                <div class="subscribe__item-img subscribe__item-img--tg">
                                    <img src="svg/telegram.svg" alt="">
                                </div>
                                <div class="subscribe__item-text">
                                    <div class="subscribe__item-name">
                                        {{__('Telegram')}}
                                    </div>
                                    <div class="subscribe__item-desc">
                                        {{__('Raised activity for Telegram. Increasing the statistics of every new post.')}}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <section class="exclusive">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="exclusive__title">{{__('Exclusive services')}}</div>
                        <div class="exclusive__subtitle">{{__('Use special offers from SocialBooster')}}</div>
                        <div class="exclusive__items">
                            <div class="exclusive__item">
                                <div class="exclusive__item-name">{{__('For every social network')}}</div>
                                <div class="exclusive__item-text">{{__('We offer the variety of services on our website, with a discount and a bonus on the first order!')}}</div>
                                <button class="exclusive__btn" type="button">{{__('Show me prices')}}</button>
                            </div>
                            <div class="exclusive__item">
                                <div class="exclusive__item-name">{{__('Instant order')}}</div>
                                <div class="exclusive__item-text">{{__('Fast order for testing the platform. Daily bonuses and discounts for newbies on our website.')}}</div>
                                <button class="exclusive__btn" type="button">{{__('Place the order')}}</button>
                            </div>
                            <div class="exclusive__item">
                                <div class="exclusive__item-name">{{__('Turnkey promotion')}}</div>
                                <div class="exclusive__item-text">{{__('Boosting your profile with premium tariff. Effective promotion with low prices.')}}</div>
                                <button class="exclusive__btn" type="button">{{__('Show me tariffs')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
