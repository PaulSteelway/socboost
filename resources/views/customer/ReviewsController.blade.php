@extends('layouts.customer')

@section('title', __('Customer reviews') . ' - ' . __('site.site'))

@section('content')

  @push('styles')
    <style>
    body {
      background-size: cover;
      background-image: url(../images/bg-history.png);
      background-color: rgba(242, 237, 237, 0.7);
    }

    .reviews__header {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-pack: justify;
      -ms-flex-pack: justify;
      justify-content: space-between;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      margin-bottom: 30px;
    }

    .reviews__title {
      font-size: 18px;
      font-weight: 600;
      color: #35414d;
      margin-bottom: 0;
    }

    .reviews-container {
      background: #ffffff;
      border-radius: 10px;
      -webkit-box-shadow: 0 2px 4px 0 rgba(231, 231, 231, 0.5);
      box-shadow: 0 2px 4px 0 rgba(231, 231, 231, 0.5);
      border: solid 1px #e8e8e8;
      padding: 30px 24px 30px 20px;
      margin-bottom: 10px !important;
    }

    .review-header {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      justify-content: space-between;
      font-size: 14px;
      font-weight: 700;
      color: #35414d;
      margin-bottom: 10px;
    }

    .review-message {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      font-size: 16px;
      font-weight: 500;
      color: #35414d;
    }

    .review-iframe {
      max-width: 300px;
      margin: auto;
    }

    .review-social-link {
      font-family: 'Montserrat', sans-serif;
      font-size: 18px;
      font-weight: 600;
      color: #35414d;
      margin-bottom: 10px;
    }

    .review-social-link a {
      color: #778891;
    }

    .add-review-btn {
      min-width: 270px;
      padding: 0 20px;
      border-radius: 25px;
      background-color: #54b5f5;
      border: none;
      color: #ffffff;
      font-size: 14px;
      font-weight: 500;
      height: 50px;
      -webkit-transition: .3s ease-in;
      transition: .3s ease-in;
      text-align: center;
      line-height: 2.8em;
    }

    .add-review-btn--open-popup {
      margin-bottom: 30px;
    }

    .add-review-btn:hover {
      background-color: #3d9ad8;
      color: #ffffff;
    }

    .review-guest {
      background: #fef4f6;
      color: #f0506e;
      border: none;
      border-radius: 10px;
      font-size: 16px;
    }

    .pagination {
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      font-weight: 500;
      color: #35414d;
    }

    .page-link {
      border: none;
      color: #35414d;
      padding: 3px 6px;
      transition: .3s ease;
      background: transparent;
    }

    .page-item.active .page-link,
    .page-link:hover {
      z-index: 0;
      color: #5ab7f5;
      background: transparent;
    }

    @media (min-width: 768px) {
      .add-review-btn--open-popup {
        display: none;
      }
    }

    @media (max-width: 767px) {
      .pagination {
        margin-bottom: 35px;
      }

      .reviews__header {
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        margin-bottom: 0;
      }

      .reviews__title {
        width: 100%;
        font-size: 18px;
        margin-bottom: 20px;
      }

      .reviews-container {
        background: transparent;
        box-shadow: none;
        border: none;
        padding: 0;
      }

      .add-review-btn {
        max-width: 100%;
        width: 100%;
      }
    }
  </style>
  @endpush
  
    <main style="padding-top: 100px;  min-height: 73vh">
        <section class="reviews">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="reviews__header">
                            <h2 class="reviews__title">{{__('Customer reviews')}} <div class="alert alert-success">{{ session('fail') }}</div></h2>
                        </div>
                        @auth

                            <button type="button" id="open-review" class="add-review-btn add-review-btn--open-popup"
                                    href="#">
                                {{__('Send review')}}
                            </button>
                            <div class="leave-review">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                                <div class="leave-review__mob-title">{{__('Post a review')}}</div>
                                <div class="leave-review__title">{{__('You can leave your feedback about us here.')}}</div>
                                @include('partials.inform')
                                <form method="POST" name="leave-review__form" role="form" id="leave_review_form"
                                      action="{{ route('reviews.add') }}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="type_id" value="1">

                                    <div class="leave-review__field-container">
                                        <label for="review-text"
                                               class="leave-review__label leave-review__label--textarea">{{__('Description')}}:</label>
                                        <textarea type="text" id="review-text" name="text" class="leave-review__field"
                                                  cols="90" rows="7" placeholder="{{__('Enter text')}}"></textarea>
                                    </div>

                                    <div class="add-review-btn__container">
                                        <button class="add-review-btn g-recaptcha"  data-sitekey="<?=Config::get('recaptcha.api_site_key')?>" data-callback="onSubmitReview" data-size="invisible">
                                            {{__('Send review')}}
                                        </button>
                                    </div>
                                </form>
                                <button class="leave-review__close" type="button" data-dismiss="modal"
                                        aria-label="Close">{{__('Close')}}</button>
                            </div>
                        @endauth

                        <div class="reviews-container">
                            @guest()
                                <div class="reviews__auth">{{__('Log in to leave your review')}}</div>
                            @endguest
                            <div class="reviews__list">
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
                                {{ $reviews->onEachSide(1)->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{--<section class="faq">--}}
        {{--<div class="container">--}}
        {{--@guest()--}}
        {{--<div class="alert review-guest">{{__('Log in to leave a review')}}</div>--}}
        {{--@endguest--}}
        {{--<div class="row">--}}
        {{--<div class="col">--}}
        {{--<div class="reviews__header">--}}
        {{--<h2 class="reviews__title">{{__('Reviews')}}</h2>--}}
        {{--<div class="faq__search">--}}
        {{--@auth--}}
        {{--<a type="button" class="add-review-btn" href="{{route('reviews.form')}}">--}}
        {{--{{__('Add review')}}--}}
        {{--</a>--}}
        {{--@endauth--}}
        {{--</div>--}}
        {{--</div>--}}

        {{--<div class="panel-group">--}}
        {{--<div class="review-social-link text-center">--}}
        {{--<a href="https://vk.com/topic-192396214_40367037"--}}
        {{--target="_blank">{{__('Reviews about us VK')}}</a>--}}
        {{--</div>--}}

        {{--@foreach($reviews as $review)--}}
        {{--<div class="panel-group">--}}
        {{--<div class="reviews-container">--}}
        {{--<h4>--}}
        {{--<span class="review-header">--}}
        {{--<span>{{$review->user->name}}</span>--}}
        {{--</span>--}}
        {{--<span class="review-message">{{$review->text}}</span>--}}
        {{--</h4>--}}
        {{--@if(!empty($review->video))--}}
        {{--<div class="review-iframe">--}}
        {{--<div class="embed-responsive embed-responsive-16by9">--}}
        {{--<iframe class="embed-responsive-item" width="300" height="200"--}}
        {{--src="{{$review->video}}"--}}
        {{--frameborder="0"--}}
        {{--allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"--}}
        {{--allowfullscreen>--}}
        {{--</iframe>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--@endif--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--@endforeach--}}

        {{--{{ $reviews->onEachSide(1)->links() }}--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</section>--}}
    </main>

<script src="/js/app.js"></script>
<script>
$(document).on('click', '.add-review-btn', function() {
  event.preventDefault();
  grecaptcha.execute();
});

function onSubmitReview(token) {
  document.getElementById("leave_review_form").submit();
}

</script>
<script src='https://www.google.com/recaptcha/api.js?hl=ru'></script>


@endsection
