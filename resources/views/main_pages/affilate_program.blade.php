@extends(isFreePromotionSite() ? 'layouts.free_promotion.layout' : 'layouts.customer')

@section('title', __('Affiliate program') . ' - ' . __('site.site'))

@section('content')
    <main style="padding-top: 100px; min-height: 73vh">
        <section class="affiliate-program affiliate-program--bg">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-5">
                        <div class="affiliate-program__img">
                            <img src="images/partner.png" alt="">
                        </div>
                    </div>
                    <div class="col-12 col-md-7 affiliate-program__col">
                        <h2 class="affiliate-program__title affiliate-program__title--earn">
                            {{__('Earn with us!')}}
                        </h2>
                        <h3 class="affiliate-program__subtitle">
                            {{__('Affiliate program')}}
                        </h3>
                        <p class="affiliate-program__text">
                            {{__('Receive up to 30% of all customer transactions you refer to the system. Copy the link, send it to your friends or share on social networks, and get a percentage of their orders. Multi-Level Affiliate Program. Sign up or log in to start earning.')}}
                        </p>
                        <p class="affiliate-program__text">
                            {{__('Want to keep up with the times and collaborate with a company that can offer innovations in services, products, brands, information and more online? We suggest earning together!')}}
                        </p>
                        <div class="affiliate-program__btns">
                            <a href="/settings" class="link-style affiliate-program__btn affiliate-program__btn--orange">{{__('Ways to earn with us')}}</a>
                            <a  href="/settings" class="link-style affiliate-program__btn">{{__('More details')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="affiliate-program affiliate-program--essence">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-7">
                        <h2 class="affiliate-program__title affiliate-program__title--essence">
                            {{__('What is the essence of earning?')}}
                        </h2>
                        <p class="affiliate-program__text">
                            {{__('The highest rates, convenient and detailed statistics on each action, flexible terms of cooperation. If you are registered in our system, you can receive referral link, which you can place on social networks, send to your friends or share it with potential customers who are interested in promotion in social networks')}}.</p>
                        <p class="affiliate-program__text">
                            {{__('Withdraw money to a bank card or QIWI wallet within 5 minutes. We do not take a commission for withdrawing funds and share secrets on how to earn more. The most active partners receive additional bonuses and increased bets from us.')}}
                        </p>
                        <div class="affiliate-program__btns">
                            <a  href="/settings" class="link-style affiliate-program__btn affiliate-program__btn--orange">{{__('How to start earning?')}}</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="affiliate-program__img affiliate-program__img--earnings">
                            <img src="images/earnings.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="advantages">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h2 class="advantages__title">
                            {{__('Advantages')}}
                        </h2>
                        <ul class="advantages__list">
                            <li class="advantages__item">
                                {{__('Instant charges')}}
                            </li>
                            <li class="advantages__item">
                                {{__('No restrictions on withdrawal')}}
                            </li>
                            <li class="advantages__item">
                                {{__('Possibility of withdrawal to QIWI purse')}}
                            </li>
                            <li class="advantages__item">
                                {{__('Transparent income statistics')}}
                            </li>
                            <li class="advantages__item">
                                {{__('Absolute income passivity')}}
                            </li>
                            <li class="advantages__item">
                                {{__('Wide range of advertising materials')}}
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-6">
                        <h2 class="advantages__title">
                            {{__('Who is suitable for')}}
                        </h2>
                        <ul class="advantages__list advantages__list--brackets">
                            <li class="advantages__item">
                                {{__('Owners of websites and blogs in Internet marketing topics')}}
                            </li>
                            <li class="advantages__item">
                                {{__('SMM professionals and online entrepreneurs')}}
                            </li>
                            <li class="advantages__item">
                                {{__('Active members of forums and thematic communities')}}
                            </li>
                        </ul>
                        <p class="advantages__text">{{__("Let's make money together! Whether you have a personal page in social networks or a large thematic portal, we will be useful to each other!")}}</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="how-much">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="how-much__title"> {{__('How much can you earn')}}</h2>
                        <table class="how-much__table">
                            <thead class="how-much__thead">
                                <tr class="how-much__tr">
                                    <th class="how-much__th">{{__('Number of referrals')}}</th>
                                    <th class="how-much__th">{{__('Monthly expense, usd')}}</th>
                                    <th class="how-much__th">{{__('Your income, usd')}}</th>
                                </tr>
                            </thead>
                            <tbody class="how-much__tbody">
                                <tr class="how-much__tr">
                                    <td class="how-much__td" data-content="{{__('Number of referrals')}}">1</td>
                                    <td class="how-much__td" data-content="{{__('Monthly expense, usd')}}">{{ number_format(socialboosterPriceByAmount(20000), 2, '.', '') }}</td>
                                    <td class="how-much__td" data-content="{{__('Your income, usd')}}">{{ number_format(socialboosterPriceByAmount(4000), 2, '.', '') }}</td>
                                </tr>
                                <tr class="how-much__tr">
                                    <td class="how-much__td" data-content="{{__('Number of referrals')}}">2</td>
                                    <td class="how-much__td"  data-content="{{__('Monthly expense, usd')}}">{{ number_format(socialboosterPriceByAmount(40000), 2, '.', '') }}</td>
                                    <td class="how-much__td" data-content="{{__('Your income, usd')}}">{{ number_format(socialboosterPriceByAmount(8000), 2, '.', '') }}</td>
                                </tr>
                                <tr class="how-much__tr">
                                    <td class="how-much__td" data-content="{{__('Number of referrals')}}">5</td>
                                    <td class="how-much__td"  data-content="{{__('Monthly expense, usd')}}">{{ number_format(socialboosterPriceByAmount(100000), 2, '.', '') }}</td>
                                    <td class="how-much__td" data-content="{{__('Your income, usd')}}">{{ number_format(socialboosterPriceByAmount(20000), 2, '.', '') }}</td>
                                </tr>
                                <tr class="how-much__tr">
                                    <td class="how-much__td" data-content="{{__('Number of referrals')}}">10</td>
                                    <td class="how-much__td" data-content="{{__('Monthly expense, usd')}}">{{ number_format(socialboosterPriceByAmount(200000), 2, '.', '') }}</td>
                                    <td class="how-much__td" data-content="{{__('Your income, usd')}}">{{ number_format(socialboosterPriceByAmount(40000), 2, '.', '') }}</td>
                                </tr>
                                <tr class="how-much__tr">
                                    <td class="how-much__td" data-content="{{__('Number of referrals')}}">50</td>
                                    <td class="how-much__td"  data-content="{{__('Monthly expense, usd')}}">{{ number_format(socialboosterPriceByAmount(100000), 2, '.', '') }}</td>
                                    <td class="how-much__td" data-content="{{__('Your income, usd')}}">{{ number_format(socialboosterPriceByAmount(200000), 2, '.', '') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <section class="referal">
            <div class="referal-step">
                <div class="container">
                    <div class="row referal-step__row">
                        <div class="col-12 col-md-5 referal-step__number-container">
                            <div class="referal-step__number">1</div>
                        </div>
                        <div class="col-12 col-md-7">
                            <h2 class="referal-step__title">{{__('Where to start attracting referrals?')}}</h2>
                            <p class="referal-step__text">{{__('Bring active users to the site and get a percentage of the amount of money spent by them for life! Earnings will interest owners of sites, publics and channels related to Internet marketing, as well as active participants in forums and communities. Our system implements a full autopilot promotion in social networks.')}}</p>
                            @auth()
                                <a href="/settings" class="link-style affiliate-program__btn affiliate-program__btn--orange" >{{__('My account')}}</a>
                            @else
                                <a href="#" class="link-style affiliate-program__btn affiliate-program__btn--orange" data-toggle="modal" data-target="#authModal">{{__('Login')}}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="referal-step">
                <div class="container">
                    <div class="row referal-step__row">
                        <div class="col-12 col-md-7">
                            <h2 class="referal-step__title">{{__('Four-level referral system')}}</h2>
                            <p class="referal-step__text">{{__('Share the link to professional tools for owners of pages on social networks, send the link to bloggers, attract customers and get a percentage from their expenses. We have a multi-level referral program: if your partner invites a friend, you will also earn your commission from it. Create a team, spread the affiliate link, earn money all the time. Affiliate program works on all services and functions of the service.')}}</p>
                            @auth()
                                <a href="/settings" class="link-style affiliate-program__btn" >{{__('My account')}}</a>
                            @else
                                <a href="#" class="link-style affiliate-program__btn" data-toggle="modal" data-target="#regModal">{{__('Sign Up')}}</a>
                            @endif

                        </div>
                        <div class="col-12 col-md-5 referal-step__number-container">
                            <div class="referal-step__number referal-step__number--reverse">2</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="referal-step">
                <div class="container">
                    <div class="row referal-step__row">
                        <div class="col-12 col-md-5 referal-step__number-container">
                            <div class="referal-step__number">3</div>
                        </div>
                        <div class="col-12 col-md-7">
                            <h2 class="referal-step__title">{{__('How can I withdraw my earnings?')}}</h2>
                            <p class="referal-step__text">{{__('Withdrawal is possible to a bank card, Yandex.Wallet and QIWI Wallet.')}}</p>
                            <p class="referal-step__text">{{__('We offer the choice of several ways to withdraw money, which are used not only in Russia but also in other CIS countries.')}}</p>
                            <a href="/settings" class="link-style affiliate-program__btn affiliate-program__btn--orange">{{__('Affiliate program')}}</a>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
