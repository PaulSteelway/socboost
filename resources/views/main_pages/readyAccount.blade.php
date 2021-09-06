@extends('layouts.customer')

@section('title', __('Ready account with followers') . ' - ' . __('site.site'))

@section('content')
    <main style="padding-top: 100px; min-height: 73vh">
        {{--ready acc--}}
        <section class="ready-aсс">
            <div class="container">
                <div class="row ready-acc__row">
                    <div class="col-12 col-lg-7 ready-acc__text-container">
                        <div class="ready-acc__text">
                            <h2 class="ready-acc__title">{{__('Ready account with followers')}}</h2>
                            <p class="ready-acc__desc">{{__('You don\'t have time to promote our account and waiting for your order to be completed? Get a ready account with any amount of followers and use it for any purpose.')}}</p>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5">
                        <div class="ready-acc__img">
                            <img src="/images/ready-account.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="category-links">
                <div class="container">
                    <div class="row">
                        @foreach($categories as $cat)
                            <div class="col-12 col-lg-4">
                                <div class="category-link__container">
                                    <a class="category-link"
                                       href="/readyaccount/{{$cat['url']}}">
                                        <div class="category-link__img">
                                            <img src="{{ asset('/' . $cat['icon_img']) }}" width="24"  alt="">
                                        </div>
                                        <span class="category-link__name"> {!! app()->getLocale() == 'en'
                                                        ? $cat->name_en
                                                        : $cat->name_ru
                                            !!}</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </section>
        <section class="reviews reviews--acc">
            <h2 class="reviews--acc__title">{{__('Customer reviews')}}</h2>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="reviews__list reviews__list--video">
                            @foreach($reviews as $review)
                                <div class="review-post__container">
                                    <div class="review-post__video">
                                        <iframe class="embed-responsive-item" width="330" height="199"
                                                src="{{$review->video}}"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                        </iframe>
                                    </div>
                                    <div class="review-post">
                                        <div class="review-post__author">{{$review->user->name}}</div>
                                        <div class="review-post__date">{{$review->created_at->format('d-m-Y')}}</div>
                                        <div class="review-post__text">{{$review->text}}.</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
