@extends('layouts.customer')

@section('title', 'Курс по продвижению TikTok' . ' - ' . __('site.site'))

@section('content')
    <main style="min-height: 73vh">
        <div style="background-color: #fcfcfc; padding-top: 100px; ">
            <div class="container">
                <section class="courses">
                    <div class="courses-block__title--mobile">
                        Курс по продвижению TikTok
                    </div>
                    <div class="courses__content-block">
                        <div class="courses-block__title">
                            Курс по продвижению TikTok
                        </div>
                        <div class="courses-block__body">
                            <p>Планируете делегировать работу по продвижению страницы в социальной сети? Предлагаем
                                условия, которые будет максимально выгодным для вас. В работе над проектом проводится
                                анализ деятельности, разрабатывается индивидуальный план, собираются промежуточные
                                результаты. </p>
                        </div>
                        <div class="courses-block__actions">
                            <a href="{{ URL::to('/courses/tiktok') }}" class="app__btn">Узнать больше</a>
                        </div>
                    </div>
                    <div class="courses__image-block">
                        <img src="/images/courses/tiktok.svg" alt="">
                    </div>
                </section>
                <section class="courses">
                    <div class="courses-block__title--mobile">
                        Курс по продвижению YouTube
                    </div>
                    <div class="courses__image-block">
                        <img src="/images/courses/youtube.svg" alt="">
                    </div>
                    <div class="courses__content-block">
                        <div class="courses-block__title">
                            Курс по продвижению YouTube
                        </div>
                        <div class="courses-block__body">
                            <p>Планируете делегировать работу по продвижению страницы в социальной сети? Предлагаем
                                условия, которые будет максимально выгодным для вас. В работе над проектом проводится
                                анализ деятельности, разрабатывается индивидуальный план, собираются промежуточные
                                результаты. </p>
                        </div>
                        <div class="courses-block__actions">
                            <a href="{{ URL::to('/courses/youtube') }}" class="app__btn">Узнать больше</a>
                        </div>
                    </div>
                </section>
                <section class="courses">
                    <div class="courses-block__title--mobile">
                        Курс по продвижению Instagram
                    </div>
                    <div class="courses__content-block">
                        <div class="courses-block__title">
                            Курс по продвижению Instagram
                        </div>
                        <div class="courses-block__body">
                            <p>Планируете делегировать работу по продвижению страницы в социальной сети? Предлагаем
                                условия, которые будет максимально выгодным для вас. В работе над проектом проводится
                                анализ деятельности, разрабатывается индивидуальный план, собираются промежуточные
                                результаты. </p>
                        </div>
                        <div class="courses-block__actions">
                            <a href="{{ URL::to('/courses/instagram') }}" class="app__btn">Узнать больше</a>
                        </div>
                    </div>
                    <div class="courses__image-block">
                        <img src="/images/courses/instagram.svg" alt="">
                    </div>
                </section>
                <section class="courses">
                    <div class="courses-block__title--mobile">
                        Курс по продвижению Telegram
                    </div>
                    <div class="courses__image-block">
                        <img src="/images/courses/telegram.svg" alt="">
                    </div>
                    <div class="courses__content-block">
                        <div class="courses-block__title">
                            Курс по продвижению Telegram
                        </div>
                        <div class="courses-block__body">
                            <p>Планируете делегировать работу по продвижению страницы в социальной сети? Предлагаем
                                условия, которые будет максимально выгодным для вас. В работе над проектом проводится
                                анализ деятельности, разрабатывается индивидуальный план, собираются промежуточные
                                результаты. </p>
                        </div>
                        <div class="courses-block__actions">
                            <a href="{{ URL::to('/courses/telegram') }}" class="app__btn">Узнать больше</a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
@endsection
