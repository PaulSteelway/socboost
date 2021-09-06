@extends('layouts.customer')

@php
  $title = __('site.title');
  $meta_title = __('site.title');
  $meta_desc = __('site.description');

  if (isset($category['meta_keywords']) && $category['meta_keywords']) {
    $meta_keywords = $category['meta_keywords'];
  }
  if (isset($category['title']) && $category['title']) {
    $title = $category['title'] . ' - ' . __('site.site');
  }
  if (isset($category['meta_title']) && $category['meta_title']) {
    $meta_title = $category['meta_title'] . ' - ' . __('site.site');
  }
  if (isset($category['meta_description']) && $category['meta_description']) {
    $meta_desc = $category['meta_description'];
  }

  if(app()->getLocale() == 'ru') {
    if (isset($category['meta_keywords_ru']) && $category['meta_keywords_ru']) {
      $meta_keywords = $category['meta_keywords_ru'];
    }
    if (isset($category['title_ru']) && $category['title_ru']) {
      $title = $category['title_ru'] . ' - ' . __('site.site');
    }
    if (isset($category['meta_title_ru']) && $category['meta_title_ru']) {
      $meta_title = $category['meta_title_ru'] . ' - ' . __('site.site');
    }
    if (isset($category['meta_description_ru']) && $category['meta_description_ru']) {
      $meta_desc = $category['meta_description_ru'];
    }
  }
@endphp

@section('title', $title)
@section('meta_title', $meta_title)
@section('description', $meta_desc)
@section('keywords', $meta_desc)

@section('content')

  @push('styles')
    <style>
    body {
      background-color: #fdfbfb;
    }

    #shortageBlock {
      display: none;
      font-size: 12px;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .current-service__packet-link::placeholder {
      font-size: 10px;
    }

    .inp-w-btn {
      display: inline-block;
      position: relative;
      width: 150px;
    }

    .input-username {
      width: 100%;
    }

    #title1, #title2, #title3, #title4 {
      font-weight: bold;
    }

    @media (max-width: 767px) {
      .inp-w-btn {
        width: 174px;
      }
    }

    .inp-w-btn input {
      text-align: center;
    }

    .inpMinus {
      position: absolute;
      left: 10px;
      top: -2px;
      font-size: 30px;
      font-weight: 600;
      cursor: pointer;
    }

    .inpPlus {
      position: absolute;
      right: 10px;
      font-size: 30px;
      font-weight: 600;
      cursor: pointer;
    }

    .inpMinus:hover, .inpPlus:hover {
      color: #f57656;
    }
  </style>

  @endpush

    <main style="padding-top: 100px;">
       {{-- <section class="service-page__services @if(!\Auth::user())service-page__services--locked @endif"> --}}

        <section class="service-page__services">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="service-page__our-services">
                            <div class="service-page__our-services-heading">
                                <button class="trigger-btn">
                                    <!--on mobile show here img and name of selected category from backend-->
                                    <img src="{{asset('/' . $parent_category["icon_img"])}}" width="24" alt=""
                                         class="trigger-btn__img">
                                    <div class="trigger-btn__name">{!! app()->getLocale() == 'en' ? $category['name_en'] : $category['name_ru'] !!}</div>
                                </button>
                            </div>
                            <div class="panel-group service-page__accordion" id="service-page__accordion">
                                <noindex v-pre>
                                @foreach ($categories as $key => $category_list)
                                    <div class="our-services__panel-container">
                                        <div class="our-services__panel-heading">
                                            <h4 class="our-services__panel-title">
                                                <a class="accordion-toggle our-services__accordion-link @if($category_list['id'] != $parent_category->id) collapsed @endif"
                                                   data-toggle="collapse" data-parent="#service-page__accordion"
                                                   href="#{{ $category_list['icon_class'] }}">
                                                    <div class="our-services__accordion-img">
                                                        <img src="{{ asset('/' . $category_list['icon_img']) }}"
                                                             width="24" alt="">
                                                    </div>
                                                    <span class="our-services__accordion-link-name">
                                                {{ app()->getLocale() == 'en' ? $category_list['name_en'] : $category_list['name_ru'] }}
                                                </span>
                                                </a>
                                            </h4>
                                        </div>
                                        @if(!empty($category_list['subcategories']))
                                            <div id="{{ $category_list['icon_class'] }}"
                                                 class="panel-collapse  @if($category_list['id'] !== $parent_category->id) collapse @else in show @endif our-services__panel">
                                                <div class="panel-body our-services__panel-body">
                                                    <ul class="our-services__panel-list">
                                                        @foreach($category_list['subcategories'] as $cat)
                                                            <li class="our-services__panel-item">
                                                                <div class="our-services__icon-container">
                                                                    <img class="our-services__icon"
                                                                         src="{{ asset('/' . $cat['icon_img']) }}"
                                                                         width="24" alt="">
                                                                    <img class="our-services__icon our-services__icon--hover"
                                                                         src="{{ asset('/' . $cat['icon_img_active']) }}"
                                                                         width="24" alt="">
                                                                </div>
                                                                <a href="{{ route('order.category', $cat['url']) }}"
                                                                   class="our-services__panel-link">
                                                                    {{ app()->getLocale() == 'en' ? $cat['name_en'] : $cat['name_ru'] }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                @endforeach
                                </noindex>
                            </div>
                        </div>
                        <div class="service-page__banners service-page__banners--desc">
                            <div class="service-page__banner">
                                <a href="/c/tgsubscribersauto" class="banner-link">
                                    <img src="{{ app()->getLocale() == 'en' ? '/images/Banner1-en.png' : '/images/Banner1.png' }}" alt="">
                                </a>
                            </div>
                            <div class="service-page__banner">
                                <a href="/affilate-program" class="banner-link">
                                    <img src="{{ app()->getLocale() == 'en' ? '/images/Banner2-en.png' : '/images/Banner2.png' }}" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="current-service">
                            <h1 class="current-service__title">
                                <img src="{{asset('/' . $parent_category["icon_img"])}}" width="24" alt=""
                                     class="current-service__img">
                                @if(app()->getLocale() == 'en')
                                    @if($category['meta_title'])
                                        <span class="current-service__name">{!! $category['meta_title'] !!}</span>
                                    @else
                                        {!! $parent_category['name_en'] !!} - <span class="current-service__name">{!! $category['name_en'] !!}</span>
                                    @endif
                                @else
                                    @if($category['meta_title_ru'])
                                        <span class="current-service__name">{!! $category['meta_title_ru'] !!}</span>
                                    @else
                                        {!! $parent_category['name_ru'] !!} - <span class="current-service__name">{!! $category['name_ru'] !!}</span>
                                    @endif
                                @endif
                            </h1>
                            <noindex v-pre>
                            <div class="current-service__block">
                                
                                @include('customer.order.category_form')

                                <div class="service-page__alert" style="display: none;">
                                    <img class="service-page__alert-icon" src="/svg/icon_info.svg" alt="">
                                    <p class="service-page__alert-text"></p>
                                </div>

                                <div class="row current-service__icons-block" >
                                    <div class="current-service__icons-element">
                                            <img src="/images/icons/start-time.svg" width="30" height="30" alt="">
                                            <h6 id="title1"></h6>
                                            <span id="subtitle1"></span>
                                    </div>
                                    <div class="current-service__icons-element">
                                            <img src="/images/icons/source.svg" width="30" height="30" alt="">
                                        <h6 id="title2"></h6>
                                        <span id="subtitle2"></span>
                                    </div>
                                    <div class="current-service__icons-element">
                                            <img src="/images/icons/demography.svg" width="30" height="30" alt="">
                                        <h6 id="title3"></h6>
                                        <span id="subtitle3"></span>
                                    </div>
                                    <div class="current-service__icons-element">
                                            <img src="/images/icons/speed.svg" width="30" height="30" alt="">
                                        <h6 id="title4"></h6>
                                        <span id="subtitle4"></span>
                                    </div>
                                </div>

{{--                                <div class="service--page__locker">--}}
{{--                                    <div class="service--page__locker-img">--}}
{{--                                        <img src="/img/icon-lock.png" alt="">--}}
{{--                                    </div>--}}
{{--                                    <div class="service--page__locker-txt">--}}
{{--                                        {{__('In order to use the services of the service, you must register -')}}--}}
{{--                                        <button class="service-page__btn-reg" type="button" href="#" data-toggle="modal"--}}
{{--                                                data-target="#regModal" data-dismiss="modal">{{__('Register')}}--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>
                            </noindex>
                        </div>
                        <noindex v-pre>
                        <div class="reviews-container reviews-container--service">
                            <div class="reviews-container__header">
                                <div class="reviews-container__title">{{__('Last reviews')}}</div>
                                <div class="reviews-container__rating">
                                    <div class="reviews-container__rating-name">{{__('Rating')}}</div>
                                    <div class="reviews-container__rating-stars">
                                        <div class="reviews-container__rating-star">
                                            <img src="/svg/star.svg" alt="">
                                        </div>
                                        <div class="reviews-container__rating-star">
                                            <img src="/svg/star.svg" alt="">
                                        </div>
                                        <div class="reviews-container__rating-star">
                                            <img src="/svg/star.svg" alt="">
                                        </div>
                                        <div class="reviews-container__rating-star">
                                            <img src="/svg/star.svg" alt="">
                                        </div>
                                        <div class="reviews-container__rating-star">
                                            <img src="/svg/star.svg" alt="">
                                        </div>
                                    </div>
                                    <div class="reviews-container__rating-amount">({{$reviews_total}})</div>
                                </div>
                            </div>
                            <div class="reviews__list reviews__list--service">
                                @php( $colors = ['#fff2b3', '#b3c0e2', '#b0a84d', '#99dd44', '#6497b1', '#ad5349'])
                                @foreach($reviews as $review)
                                    <div class="review-post__container">
                                        <div class="review-post__avatar review-post__avatar--yellow" style="background-color: {{$colors[rand(0,5)]}}">
                                            {{mb_substr($review->user->name, 0, 1)}}{{mb_substr($review->user->lastname, 0, 1)}}
                                        </div>
                                        <div class="review-post">
                                            <div class="review-post__author">{{$review->user->name}}</div>
                                            <div class="review-post__date">{{$review->created_at->format('d-m-Y')}}</div>
                                            <div class="review-post__text">{{$review->text}}.</div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="show-more__container">
                                    <a href="/reviews" class="show-more">
                                        {{__('Show more')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="service-page__banners service-page__banners--mob">
                            <div class="service-page__banner">
                                <a href="/c/tgviews" class="banner-link">
                                    <img src="{{ app()->getLocale() == 'en' ? '/images/Banner1-en.png' : '/images/Banner1.png' }}" alt="">
                                </a>
                            </div>
                            <div class="service-page__banner">
                                <a href="/affilate-program" class="banner-link">
                                    <img src="{{ app()->getLocale() == 'en' ? '/images/Banner2-en.png' : '/images/Banner2.png' }}" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="buy-with">
                            <div class="buy-with__title">{{__('Buy with it')}}</div>
                            <div class="buy-with__list">
                                @foreach($parent_category->child_categories as $child_category)
                                    @if($child_category->id == $category['id'])
                                        @continue
                                    @endif
                                    <div class="buy-with__item">
                                        <div class="buy-with__item-img">
                                            <img src="{{ asset('/' . $parent_category['icon_img']) }}" alt="">
                                        </div>
                                        <div class="buy-with__item-name"  style="max-height: 80px; overflow: hidden">
                                            {!! app()->getLocale() == 'en'
                                                        ? $child_category->name_en
                                                        : $child_category->name_ru
                                            !!}
                                        </div>
                                        <hr class="buy-with__item-hr">
                                        @if(isset($child_category->packets[0]))
                                        <div class="buy-with__item-price">{{__('250 pcs.') . ' = ' .  number_format(socialboosterPriceByAmount($child_category->packets[0]->price * 250), 2, '.', '')}}{{ app()->getLocale() == 'en' ? '$' : '₽' }}</div>
                                        @endif
                                        <a href="{{ route('order.category', $child_category['url']) }}" class="buy-with-acc__btn">{{__('Buy')}}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        </noindex>

                        @if((app()->getLocale() == 'en' && (!empty($category['details_title_en']) || !empty($category['details_en']))) || (app()->getLocale() == 'ru' && (!empty($category['details_title_ru']) || !empty($category['details_ru']))))
                            <div class="current-service__description">
                                <div class="service-details__title">{!! app()->getLocale() == 'en' ? $category['details_title_en'] : $category['details_title_ru'] !!}</div>
                                <div class="service-details__text">{!! app()->getLocale() == 'en' ? $category['details_en'] : $category['details_ru'] !!}</div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
      @if(\Auth::check() && session()->exists(\Auth::id() . '_' . 'cloudpayments'))
        <script>
          var cloudpayments = {!! json_encode(session()->pull(\Auth::id() . '_' . 'cloudpayments')) !!};
        </script>
      @endif
    @endpush

@endsection
