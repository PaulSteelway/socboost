@extends('layouts.customer')

@section('title', __('Расскажем как набрать 1 миллион подписчиков в Тик Ток за 1 месяц') . ' - ' . __('site.site'))

@section('content')
    <main style="padding-top: 100px; min-height: 73vh">

        <section class="tiktok-course">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="tiktok-course__block">
                            <div class="tiktok-course__title">{{__('Расскажем как набрать 1 миллион подписчиков в Тик Ток за 1 месяц')}}</div>
                            <div class="tiktok-course__subtitle">{{__('Стань популярным в тик ток и начни зарабатывать на этом от 200 000 рублей в месяц уже через 30 дней!')}}</div>
                            <div class="tiktok-course__img">
                                <img src="/images/course.png" alt="">
                            </div>
                            <div class="tiktok-course__text">{{__('Пошаговая инструкция к популярности в тик ток, проверенные фишки и методы, секретные способы продвижения')}}</div>
                            <button type="button" class="tiktok-course__btn" onclick="return pay();">{{__('Приобрести курс')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="course-for">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="app__block">
                            <div class="course-for__title">{{__('Для кого этот курс?')}}</div>
                            <div class="app__img">
                                <img src="/images/for-whom-is-the-course.png" alt="">
                            </div>
                            <div class="app__text">{{__('Если ты хочешь внимания и популярности, иметь достойный доход и зарабатывать из любой точки планеты - то этот курс для тебя! Но если тебе интересно и дальше сидеть на стуле и пропускать тренд мимо - просто закрой это страничку и забудь.')}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="tiktok__course">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="tiktok__learn-block">
                            <div class="tiktok__learn-title">{{__('Что вы узнаете после прохождения курса?')}}</div>
                            <div class="tiktok__img">
                                <img src="/images/about-course.png" alt="">
                            </div>
                            <div class="tiktok__learn">
                                <p class="tiktok__learn-text">{{__('Секретные фишки которыми пользуются блоггеры')}}</p>
                                <p class="tiktok__learn-text">{{__('Методы вывода видео каждого ролика в рекомендации')}}</p>
                                <p class="tiktok__learn-text">{{__('Как гарантированно набирать 100 000 просмотров на каждый ролик')}}</p>
                                <p class="tiktok__learn-text">{{__('Пошаговую стратегию уже популярных блоггеров с аудиторией более 1 000 000 подписчиков
')}}</p>
                                <p class="tiktok__learn-text">{{__('Как монетизировать свой аккаунт и зарабатывать более 200 000 рублей в месяц')}}</p>
                            </div>
                            @include('main_pages.courses.slider')

                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('main_pages.courses.course-review')

        <section class="tiktok__best-offer">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="tiktok__best-offer-block">
                            <div class="tiktok__best-offer-title">
                                <span class="old_price">9750 ₽</span><span class="new_price"> 2490 ₽</span>
                            </div>
                            <div class="tiktok__best-offer-img">
                                <img src="/images/offer-course.png" alt="">
                            </div>
                            <div class="tiktok__best-offer-text">{{__('Осталось 3 места по цене со скидкой!')}}</div>
                            <a href="#" class="tiktok__best-offer-btn" onclick="return pay();">{{__('Приобрести курс')}}</a>
                            <div class="course-info">*Вы будете перенаправлены на страницу скачивания курса сразу после успешной оплаты</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('main_pages.courses.course_pay_wigget')
@endsection
