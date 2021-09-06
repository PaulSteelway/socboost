@extends(isFreePromotionSite() ? 'layouts.free_promotion.layout' : 'layouts.profile')

@section('title', __('Cumulative system of discounts') . ' - ' . __('site.site'))

@section('content')
    <main style="padding-top: 100px;">
        <section class="discount">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="discount__block">
                            <h2 class="discount__title">{{__('Cumulative system of discounts')}}</h2>
                            <div class="discount__img">
                                <img src="/images/sales.png" alt="">
                            </div>
                            <h3 class="discount__subtitle">{{__('Discounts for regular customers')}}</h3>
                            <p class="discount__text">{{__('We provide regular customers of our service with favorable rates with discounts of up to 30% for all services of the service, tariff plans are switched automatically when the desired amount is reached! Discounts work at the same time as discounts for a one-time wholesale purchase, buy more on the')}} <a href="#" class="discount__link">{{__('stock - pay less')}}</a>. {{__('To use the discount system of discounts, you must maintain the necessary turnover throughout the year')}}.</p>
                            <h3 class="discount__subtitle">{{__('How to keep affiliate tariff')}}?</h3>
                            <p class="discount__text">{{__('To keep the affiliate tariff with a discount, you need to maintain the turnover on your account for placing new orders according to your tariff. As part of the partner tariffs, a system of automatic monitoring for the implementation of the tariff conditions is in effect. The check is carried out daily, based on its results, your tariff will be saved or changed to a more profitable one based on the conditions of the discount system. The system of discounts is activated when the turnover on the account during the last 1 year or 365 days')}}.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="partner-tariffs">
            <div class="container">
                <h2 class="partner-tariffs__title">{{__('Affiliate rates')}}</h2>
                <div class="row">
                    <div class="col-6 col-lg-3">
                        <div class="partner-tariff">
                            <div class="partner-tariff__discount">{{__('Discount')}}: 1%</div>
                            <div class="partner-tariff__spent">{{__('Spent in service')}}:</div>
                            <div class="partner-tariff__spent-amount">{{__('From')}} {{ number_format(socialboosterPriceByAmount(1000), 2, '.', '') }} {{ app()->getLocale() == 'en' ? '$' : '₽' }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="partner-tariff">
                            <div class="partner-tariff__discount">{{__('Discount')}}: 3%</div>
                            <div class="partner-tariff__spent">{{__('Spent in service')}}:</div>
                            <div class="partner-tariff__spent-amount">{{__('From')}} {{ number_format(socialboosterPriceByAmount(3000), 2, '.', '') }} {{ app()->getLocale() == 'en' ? '$' : '₽' }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="partner-tariff">
                            <div class="partner-tariff__discount">{{__('Discount')}}: 5%</div>
                            <div class="partner-tariff__spent">{{__('Spent in service')}}:</div>
                            <div class="partner-tariff__spent-amount">{{__('From')}} {{ number_format(socialboosterPriceByAmount(5000), 2, '.', '') }} {{ app()->getLocale() == 'en' ? '$' : '₽' }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="partner-tariff">
                            <div class="partner-tariff__discount">{{__('Discount')}}: 7%</div>
                            <div class="partner-tariff__spent">{{__('Spent in service')}}:</div>
                            <div class="partner-tariff__spent-amount">{{__('From')}} {{ number_format(socialboosterPriceByAmount(10000), 2, '.', '') }} {{ app()->getLocale() == 'en' ? '$' : '₽' }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="partner-tariff">
                            <div class="partner-tariff__discount">{{__('Discount')}}: 10%</div>
                            <div class="partner-tariff__spent">{{__('Spent in service')}}:</div>
                            <div class="partner-tariff__spent-amount">{{__('From')}} {{ number_format(socialboosterPriceByAmount(25000), 2, '.', '') }}{{ app()->getLocale() == 'en' ? '$' : '₽' }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="partner-tariff">
                            <div class="partner-tariff__discount">{{__('Discount')}}: 12%</div>
                            <div class="partner-tariff__spent">{{__('Spent in service')}}:</div>
                            <div class="partner-tariff__spent-amount">{{__('From')}} {{ number_format(socialboosterPriceByAmount(50000), 2, '.', '') }}{{ app()->getLocale() == 'en' ? '$' : '₽' }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="partner-tariff">
                            <div class="partner-tariff__discount">{{__('Discount')}}: 15%</div>
                            <div class="partner-tariff__spent">{{__('Spent in service')}}:</div>
                            <div class="partner-tariff__spent-amount">{{__('From')}} {{ number_format(socialboosterPriceByAmount(100000), 2, '.', '') }}{{ app()->getLocale() == 'en' ? '$' : '₽' }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="partner-tariff">
                            <div class="partner-tariff__discount">{{__('Discount')}}: 18%</div>
                            <div class="partner-tariff__spent">{{__('Spent in service')}}:</div>
                            <div class="partner-tariff__spent-amount">{{__('From')}} {{ number_format(socialboosterPriceByAmount(200000), 2, '.', '') }}{{ app()->getLocale() == 'en' ? '$' : '₽' }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="partner-tariff">
                            <div class="partner-tariff__discount">{{__('Discount')}}: 21%</div>
                            <div class="partner-tariff__spent">{{__('Spent in service')}}:</div>
                            <div class="partner-tariff__spent-amount">{{__('From')}} {{ number_format(socialboosterPriceByAmount(350000), 2, '.', '') }}{{ app()->getLocale() == 'en' ? '$' : '₽' }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="partner-tariff">
                            <div class="partner-tariff__discount">{{__('Discount')}}: 24%</div>
                            <div class="partner-tariff__spent">{{__('Spent in service')}}:</div>
                            <div class="partner-tariff__spent-amount">{{__('From')}} {{ number_format(socialboosterPriceByAmount(500000), 2, '.', '') }}{{ app()->getLocale() == 'en' ? '$' : '₽' }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="partner-tariff">
                            <div class="partner-tariff__discount">{{__('Discount')}}: 27%</div>
                            <div class="partner-tariff__spent">{{__('Spent in service')}}:</div>
                            <div class="partner-tariff__spent-amount">{{__('From')}} {{ number_format(socialboosterPriceByAmount(750000), 2, '.', '') }}{{ app()->getLocale() == 'en' ? '$' : '₽' }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="partner-tariff">
                            <div class="partner-tariff__discount">{{__('Discount')}}: 30%</div>
                            <div class="partner-tariff__spent">{{__('Spent in service')}}:</div>
                            <div class="partner-tariff__spent-amount">{{__('From')}} {{ number_format(socialboosterPriceByAmount(150000000), 2, '.', '') }}{{ app()->getLocale() == 'en' ? '$' : '₽' }}</div>
                        </div>
                    </div>
                    <div class="col">
                        <p class="partner-tariffs__text partner-tariffs__text--bold">{{__('When you reach a new discount tariff plan, you will be transferred to the next discount group of discounts automatically')}}!</p>
                        <p class="partner-tariffs__text">*{{__('By a discount is meant an automatic reduction of the indicated amount to pay for all services on the service')}}. <b>{{__('Suppose a certain service you have chosen costs')}} {{ number_format(socialboosterPriceByAmount(5000), 2, '.', '') }} {{ app()->getLocale() == 'en' ? '$' : '₽' }}</b>, {{__('with the “Reseller-10” tariff plan you only need to pay')}} <b> {{ number_format(socialboosterPriceByAmount(4500), 2, '.', '') }} {{ app()->getLocale() == 'en' ? '$' : '₽' }}.</b></p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
