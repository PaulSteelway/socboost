@extends('layouts.profile')

@section('title', __('Add new review') . ' - ' . __('site.site'))

@section('content')

  @push('styles')
    <style>
      .add-review {
        background-image: url('../images/bg-profile.png');
        background-size: cover;
        padding-top: 50px;
        padding-bottom: 55px;
      }

      .review-form {
        max-width: 900px;
      }

      .review-form__title {
        font-size: 24px;
        font-weight: 600;
        color: #f57656;
        margin-bottom: 25px;
      }

      .review-form__textarea {
        width: 100%;
        height: 200px;
        padding: 10px 20px;
        border-radius: 4px;
        border: solid 1px #e3e3e3;
        margin: 0;
        background-color: #ffffff;
        font-size: 14px;
        font-weight: 500;
        color: #35414d;
      }

      .review-form__input {
        width: 100%;
        height: 43px;
        padding-left: 20px;
        border-radius: 4px;
        border: solid 1px #e3e3e3;
        margin: 0;
        background-color: #ffffff;
        font-size: 14px;
        font-weight: 500;
        color: #35414d;
      }

      .review-form__btn--cancel {
        min-width: 250px;
        border-radius: 25px;
        background-color: #D0D0D0;
        border: none;
        color: #ffffff;
        font-size: 16px;
        font-weight: 500;
        height: 50px;
        margin-right: 31px;
        -webkit-transition: .3s ease-in;
        transition: .3s ease-in;
        text-align: center;
        line-height: 3em;
      }

      .review-form__btn--cancel:hover {
        background-color: #C0C0C0;
        color: #ffffff;
      }

      .tip-message {
        color: rgba(53, 65, 77, 0.8);
        font-size: 12px;
        font-weight: 500;
      }

      @media (max-width: 767px) {
        .add-review {
          padding-top: 20px;
          padding-bottom: 30px;
        }

        .review-form {
          -webkit-box-orient: vertical;
          -webkit-box-direction: normal;
          -ms-flex-direction: column;
          flex-direction: column;
        }

        .review-form__title {
          text-align: center;
          margin-bottom: 27px;
        }

        .review-form__btn--cancel {
          width: 100%;
        }
      }
    </style>
  @endpush
  
    <main style="padding-top: 100px;">
        <section class="add-review">
            <div class="container">
                <div class="row">
                    <div class="col">
                        @include('partials.inform')
                        <form method="POST" name="review_create" role="form" action="{{ route('reviews.add') }}">
                            {{ csrf_field() }}

                            <h2 class="review-form__title">{{__('Add new review')}}</h2>

                            <div class="review-form">
                                <div class="profile-form__field">
                                    <label for="review-message" class="profile-form__label">{{__('Review')}}*</label>
                                    <textarea name="text" id="review-message" class="review-form__textarea"></textarea>
                                </div>

                                <div class="profile-form__field">
                                    <label for="video-link" class="profile-form__label">{{__('Video link')}}</label>
                                    <input type="text" name="video" id="video-link" class="review-form__input">
                                    <div class="tip-message">{{__('YouTube link should be in format "https://youtu.be/XXXXXXXXXXX"')}}</div>
                                </div>

                                <div class="profile-form__field">
                                    <div class="row">
                                        <div class="col-lg-3"><?= captcha_img() ?></div>
                                        <div class="col-lg-9">
                                            <input type="text" name="captcha" class="review-form__input"
                                                   placeholder="Captcha*">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="profile-form__btns">
                                <button class="profile-form__btn profile-form__btn--save">{{__('Save')}}</button>
                                <a type="button" class="profile-form__btn review-form__btn--cancel"
                                   href="{{route('reviews')}}">{{__('Cancel')}}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
