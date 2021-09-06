@extends('layouts.profile')

@section('title', __('Settings') . ' - ' . __('site.site'))

@section('content')

  @push('styles')
    <style>
      .referral-block {
        margin-top: 50px;
      }
      @media(max-width: 767px){
        .referral-block {
          padding: 0;
        }
      }

      #usr-ref-link {
        cursor: pointer;
      }

      .generate-link__btn {
        min-width: 150px;
        border-radius: 25px;
        background-color: #54b5f5;
        border: none;
        color: #ffffff !important;
        font-size: 16px !important;
        font-weight: 500 !important;
        height: 40px;
        margin-right: 31px;
        -webkit-transition: .3s ease-in;
        transition: .3s ease-in;
      }

      .generate-link__btn:hover {
        background-color: #3d9ad8;
      }

      .email-form__btn--promo {
        color: #f57656;
        text-decoration: underline;
        background: transparent;
        border: none;
        height: 43px;
        margin-top: auto;
        padding: 0;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        margin-right: -51px;
      }

      @media (max-width: 767px) {
        .generate-link__btn {
          width: 100%;
        }

        .email-form__btn--promo {
          display: inline-block;
          margin-top: 10px;
          font-size: 14px;
          margin-left: 5px;
        }
      }

      .profile-form__field .iti--allow-dropdown {
        width: 100%;
      }

      .error {
        border: solid 2px red;
      }

      #error-msg {
        color: red;
        font-weight: bolder;
        font-size: 10px;
      }
    </style>
  @endpush

    <main style="padding-top: 100px; min-height: 75vh">
        <section class="my-profile">
            <div class="container">
                <div class="row">
                    <div class="col">
                        @include('partials.inform')

                        <h2 class="profile-form__title">{{__('My Profile')}}</h2>
                        {{--<div class="row my-profile__row">--}}
                            {{--<div class="col-12 col-md-3">--}}
                                {{--<div class="my-profile__ava-container">--}}
                                    {{--<div class="my-profile__ava">--}}
                                        {{--<img src="svg/account-img-new.svg" class="my-profile__ava-img" alt="">--}}
                                        {{--<div class="my-profile__upload-ava-container">--}}
                                            {{--<label for="upload-ava" class="upload-ava-label">{{__('Upload photo')}}</label>--}}
                                            {{--<input type="file" name="upload-ava" id="upload-ava" class="my-profile__upload-ava">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-12 col-md-9">--}}
                                {{--<div class="row my-profile__info-row">--}}
                                    {{--<div class="col-12 col-md-4">--}}
                                        {{--<div class="my-profile__info">--}}
                                            {{--<div class="my-profile__info-name">--}}
                                                {{--{{__('My Profile')}}--}}
                                            {{--</div>--}}
                                            {{--<div class="my-profile__info-value">--}}
                                                {{--<img src="svg/star.svg" class="profile__info-star" alt=""> {{__('Premium')}}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-12 col-md-4">--}}
                                        {{--<div class="my-profile__info">--}}
                                            {{--<div class="my-profile__info-name">--}}
                                                {{--{{__('How many days are you with us:')}}--}}
                                            {{--</div>--}}
                                            {{--<div class="my-profile__info-value">--}}
                                                {{--{{__('45 days')}}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="col-12 col-md-4">--}}
                                        {{--<div class="my-profile__info">--}}
                                            {{--<div class="my-profile__info-name">--}}
                                                {{--{{__('45 days')}}--}}
                                            {{--</div>--}}
                                            {{--<div class="my-profile__info-value">--}}
                                                {{--{{__('50 000 rub')}}--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        <div class="profile-form__cols">
                                <div class="profile-form__col">
                                    <form id="profile-form" style="display: none" method="POST" name="profile_update" role="form"
                                          action="{{ route('profile.settings') }}">
                                        {{ csrf_field() }}
                                    </form>
                                    <div class="profile-form__field">
                                        <label for="profile-login"
                                               class="profile-form__label">{{__('Username')}}</label>
                                        <input form="profile-form" name="login" type="text" id="profile-login" class="profile-form__input"
                                               value="{{\Auth::user()->login}}">
                                    </div>
                                    <div class="profile-form__field">
                                        <label for="profile-name"
                                               class="profile-form__label">{{__('First Name')}}</label>
                                        <input  form="profile-form"  name="name" type="text" id="profile-name" class="profile-form__input"
                                               value="{{\Auth::user()->name}}"
                                               placeholder="{{app()->getLocale() == 'en' ? 'Tommy' : 'Томми'}}">
                                    </div>
                                    <div class="profile-form__field">
                                        <label for="profile-name"
                                               class="profile-form__label">{{__('Last Name')}}</label>
                                        <input form="profile-form"  name="lastname" type="text" id="profile-name" class="profile-form__input"
                                               value="{{\Auth::user()->lastname}}"
                                               placeholder="{{app()->getLocale() == 'en' ? 'Cash' : 'Кэш'}}">
                                    </div>
                                    <div class="profile-form__field">
                                        <label for="country-reg" class="profile-form__label">{{__('Country')}}</label>
                                        <select  form="profile-form"  name="country" id="country-reg" class="profile-form__input">
                                            @foreach(getCodeCountryList() as $country)
                                                <option value="{{strtoupper($country['alpha2'])}}"
                                                        {{(strcasecmp(\Auth::user()->country, $country['alpha2']) == 0) ? 'selected' : ''}}>{{ $country['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="profile-form__btns">
                                        <button onclick="submitProfileForm()" class="profile-form__btn profile-form__btn--save">{{__('Save')}}</button>
                                    </div>
                                </div>
                                <div class="profile-form__col">
                                    <div class="profile-form__field">
                                        @if (!empty(\Auth::user()->phone) && empty(\Auth::user()->phone_verified_at))
                                            <label for="profile-phone"
                                                   class="profile-form__label">{{__('Phone number')}}
                                                <span class="badge badge-success" type="button" data-toggle="modal"
                                                      data-target="#phoneCodeModal">{{__('Enter code verification')}}</span></label>
                                            <input form="profile-form" name="phone" type="text" id="profile-phone"
                                                   class="profile-form__input" value="{{\Auth::user()->phone}}">
                                            <span id="error-msg"></span>
                                            <div><a href="{{route('verification.phone.send')}}"
                                               class="email-form__btn--promo">{{__('Send me confirmation')}}</a></div>
                                        @else
                                            <label for="profile-phone"
                                                   class="profile-form__label">{{__('Phone number')}}</label>
                                            <input form="profile-form" name="phone" type="text" id="profile-phone"
                                                   class="profile-form__input" value="{{\Auth::user()->phone}}">
                                            <span id="error-msg"></span>
                                        @endif
                                    </div>

                                    <div class="profile-form__field">
                                        <label for="profile-phone"
                                               class="profile-form__label">{{__('Telegram')}}</label>
                                        <input form="profile-form" name="telegram" type="text" class="profile-form__input"
                                               value="{{\Auth::user()->telegram}}" placeholder="@tommycash">
                                    </div>

                                    <form id="recover-psw-form" class="" method="POST"
                                          action="{{ route('password.email') }}">
                                        {{ csrf_field() }}

                                        <div class="profile-form__field">
                                            <label for="profile-email" class="profile-form__label">E-mail</label>
                                            <input name="email" type="text" id="profile-email"
                                                   class="profile-form__input" value="{{\Auth::user()->email}}">
                                            @if (!empty(\Auth::user()->email) && empty(getUserEmailVerifiedAt()))
                                                <a href="{{route('verification.resend')}}"
                                                   class="email-form__btn--promo">{{__('Send me confirmation')}}</a>
                                            @endif
                                        </div>
                                    </form>
                                    @if(isCanChangePassword())
                                        <button onclick="submitRecoverPswForm()" class="modal-form__btn modal-form__btn--rec-pass">{{__('Change password')}}</button>
                                    @endif
                                </div>

                        </div>
                        <div class="container referral-block">
                            <div class="row contacts__row">
                                <div class="col">
                                    <div class="contacts__info">
                                        <div class="contact-block">
                                            <div class="contact-block__name">{{__('Referral link')}}:</div>
                                            <div class="contact-block__info">
                                                @if(empty($userRefLink))
                                                    <button onclick="location.href='{{route('profile.generate')}}';"
                                                            class="generate-link__btn">{{__('Generate')}}</button>
                                                @else
                                                    <a id="usr-ref-link" title="Click to copy">{{ $userRefLink }}</a>
                                                @endif
                                            </div>
                                            <div class="contact-block__support">
                                                @if (app()->getLocale() == 'en')
                                                    Invite your friends and get 10% from their top ups to your balance
                                                    immediately!
                                                @else
                                                    Приглашай друзей и получай 10% от их пополнений на свой баланс
                                                    моментально!
                                                @endif
                                            </div>
                                        </div>

                                        @if (!empty($userRefLink))
                                            <div class="contact-block">
                                                <div class="contact-block__name">{{__('Invited users')}}</div>
                                                <div class="contact-block__info">{{ $countReferrals }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
  <script>
    function submitProfileForm() {
      if (!$('#error-msg').text()) {
        document.getElementById("profile-form").submit();
      }
    }
    function submitRecoverPswForm() {
      document.getElementById("recover-psw-form").submit();
    }
  </script>
@endpush
