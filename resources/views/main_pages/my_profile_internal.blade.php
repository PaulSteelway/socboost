@extends(isFreePromotionSite() ? 'layouts.free_promotion.layout' : 'layouts.profile')

@section('title', __('Settings') . ' - ' . __('site.site'))

@section('content')

    @push('styles')
      <script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>

      <style>
        .profile-form__field .iti--allow-dropdown {
          width: 100%;
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
                        <div class="profile_actions">
                            @if(empty(Auth::user()->is_premium))
                                <a href="{{route('customer.premiumAccount')}}">
                                    <button type="button"
                                            class="app__btn app__btn--prem-prom">{{__('Purchase premium account')}}</button>
                                </a>
                            @else
                                <button type="button" class="app__btn app__btn--prem-prom subs-close-btn"
                                        data-qst="{{ __('Do you really want to cancel your premium account?') }}">{{__('Cancel premium account')}}</button>
                            @endif
                        </div>

                        <div class="profile-info">
                            <div class="profile-info__photo">
                            <div id="avatar_error" class="alert alert-danger" style="width:223px;display:none;"> <ul><li class="error-item">Допустимые форматы: png, jpg</li></ul></div>
                                <div class="profile-info__photo--img" style="background-image: url('{{ asset(Auth::user()->avatar ?? '/svg/account-img-new.svg') }}')">
                                    @if(Auth::user()->is_premium)
                                        <img class="premium_acc_start" src="/images/profile/star.svg"
                                             width="70" height="70" alt="">
                                    @else
                                        <a href="{{route('customer.premiumAccount')}}">
                                            <img class="premium_acc_start" src="/images/profile/star-grey.png"
                                                 width="70" height="70" alt=""></a>
                                    @endif
                                </div>

                                <div class="profile-info__upload ">
                                    {{Form::open(['url' => '/my_profile_internal/process_avatar','method'=>'POST', 'files'=>'true', 'id'=>'avatar_form'])}}
                                    <label for="avatar_input" class="avatar_input--label"><span>{{__('Upload photo')}}</span></label>
                                    <input type="file" class="avatar_input" name="avatar_img" id="avatar_id">
                                    {{Form::close()}}
                                </div>
                            </div>
                            <div class="profile-info__data">
                                <div class="profile-info__data--first-element">
                                    <div class="profile-info__name-block">
                                        <span>{{$user->name . ' ' . $user->lastname}}</span>
                                        <div>
                                            <a class="profile-info__edit-link" href="#editAccount">{{__('Edit')}}</a>
                                        </div>
                                    </div>
                                    <div class="profile-info__wallet">
                                        <div class="profile-info__wallet--icon"></div>
                                        <div class="profile-info__wallet--text">{{ null !== $user->getActiveWallet() ? number_format(socialboosterPriceByAmount($user->getActiveWallet()->balance), 2, '.', '') : 0.00 }}
                                        @if (isFreePromotionSite())
                                            {{ app()->getLocale() == 'en' ? 'Points' : getPointsName($wallet->balance) }}
                                        @else
                                            {{ app()->getLocale() == 'en' ? '$' : '₽' }}
                                        @endif
                                        / {{__('Discount')}} {{ $user->getActiveWallet() ? $user->getActiveWallet()->getDiscount() : 0 }} %</div>
                                    </div>
                                </div>
                                <div class="profile-info__discount">
                                    <div>{{__('Current discount')}}: {{ $user->getActiveWallet() ? $user->getActiveWallet()->getDiscount() : 0 }}%</div>
                                    <div class="profile-info__discount__text">{{__('In order to get a discount')}} {{$user->getActiveWallet() ? $user->getActiveWallet()->getNextDiscountPercent() : 0}}%
                                        {{__('you have to top up your account on')}} {{$user->getActiveWallet() ? number_format(socialboosterPriceByAmount($user->getActiveWallet()->getNextDiscountAmount() - $user->getActiveWallet()->spend_amount), 2, '.', '') : 0}} {{ app()->getLocale() == 'en' ? '$' : '₽' }}
                                    </div>
                                </div>
                                <div class="profile-slider__block">
                                    <div id="slider" class="profile_slider">
                                        <div id="custom-handle-1" class="ui-slider-handle"></div>
                                    </div>
                                </div>


{{--                                <div class="profile-info__discount--line">--}}
{{--                                    <div class="progress">--}}
{{--                                        <div class="progress-track"></div>--}}
{{--                                        <div id="step1" class="progress-step is-complete">--}}
{{--                                            <div class="progress__start-step"></div>--}}
{{--                                        </div>--}}
{{--                                        <div id="step2" class="progress-step ">--}}
{{--                                            <div class="progress__active-step"></div>--}}

{{--                                        </div>--}}
{{--                                        <div id="step3" class="progress-step">--}}
{{--                                            <span class="progress-amount">{{$user->getActiveWallet() ?  number_format(socialboosterPriceByAmount($user->getActiveWallet()->spend_amount), 2, '.', '') : 0}} {{ app()->getLocale() == 'en' ? '$' : '₽' }}</span>--}}
{{--                                            <div class="progress__complete-step"></div>--}}
{{--                                        </div>--}}

{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="profile-info__discount__text--mobile">{{__('In order to get a discount')}} {{$user->getActiveWallet() ? $user->getActiveWallet()->getNextDiscountPercent() : 0}}%
                                    {{__('you have to top up your account on')}} {{$user->getActiveWallet() ? number_format(socialboosterPriceByAmount($user->getActiveWallet()->getNextDiscountAmount() - $user->getActiveWallet()->spend_amount), 2, '.', '') : 0}} {{ app()->getLocale() == 'en' ? '$' : '₽' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="background-color: white ">

                <div class="container" style="padding-top: 77px">
                    <div class="profile-cards">
                        <div class="profile-card">
                            <div class="profile-card__img">
                                <img src="/svg/profile/orders.svg" width="90" height="90" alt="">
                            </div>
                            <div class="profile-card__title">{{__('My orders')}}</div>
                            <div class="profile-card__text">{{__('Active Orders Fulfilling at the moment')}}
                            </div>
                            <div class="profile-card__action">
                                <a class="profile-card__action--link" href="{{ route('profile.operations.index') }}" >{{__('Go to')}}</a>
                            </div>
                        </div>
                        <div class="profile-card">
                            <div class="profile-card__img">
                                <img src="/svg/profile/followers.svg" width="90" height="90" alt="">
                            </div>
                            <div class="profile-card__title">{{__('Subscription Management')}}</div>
                            <div class="profile-card__text">{{__('Your monthly service subscriptions')}}</div>
                            <div class="profile-card__action">
                                <a class="profile-card__action--link" href="{{ route('profile.subscriptions.index') }}">{{__('Go to')}}</a>
                            </div>
                        </div>
                        <div class="profile-card">
                            <div class="profile-card__img">
                                <img src="/svg/profile/balance.svg" width="90" height="90" alt="">
                            </div>
                            <div class="profile-card__title">{{__('Balance refill')}}</div>
                            <div class="profile-card__text">{{__('Choose any convenient way to balance refill, bonuses from a certain amount')}}</div>
                            <div class="profile-card__action">
                                <a class="profile-card__action--link" href="{{ route('profile.topup') }}">{{__('Go to')}}</a>
                            </div>
                        </div>
                        <div class="profile-card">
                            <div class="profile-card__img">
                                <img src="/svg/profile/handshake.svg" width="90" height="90" alt="">
                            </div>
                            <div class="profile-card__title">{{__('Referral program')}}</div>
                            <div class="profile-card__text">{{__('Invite your friends and get bonuses!')}}</div>
                            <div class="profile-card__action">
                                <a class="profile-card__action--link" href="/referral_page">{{__('Go to')}}</a>
                            </div>
                        </div>
                        <div class="profile-card">
                            <div class="profile-card__img">
                                <img src="/svg/profile/service.svg" width="90" height="90" alt="">
                            </div>
                            <div class="profile-card__title">{{__('Support')}}</div>
                            <div class="profile-card__text">{{__('Ask any question that interests you 24/7')}}</div>
                            <div class="profile-card__action">
                                <a class="profile-card__action--link" href="{{route('contacts')}}">{{__('Go to')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="profile-form">
                        <div id="editAccount" class="profile-form--title">{{__('Edit profile')}}</div>
                        <div class="profile-form__cols">
                            <div class="profile-form__col">
                                <form id="profile-form" style="display: none" method="POST" name="profile_update"
                                      role="form"
                                      action="{{ route('profile.settings') }}">
                                    {{ csrf_field() }}
                                </form>
{{--                                <div class="profile-form__field">--}}
{{--                                    <label for="profile-login"--}}
{{--                                           class="profile-form__label">{{__('Username')}}</label>--}}
{{--                                    <input form="profile-form" name="login" type="text" id="profile-login"--}}
{{--                                           class="profile-form__input"--}}
{{--                                           value="{{\Auth::user()->login}}">--}}
{{--                                </div>--}}
                                <div class="profile-form__field">
                                    <label for="profile-name"
                                           class="profile-form__label">{{__('First Name')}}</label>
                                    <input form="profile-form" name="name" type="text" id="profile-name"
                                           class="profile-form__input"
                                           value="{{\Auth::user()->name}}"
                                           placeholder="{{app()->getLocale() == 'en' ? 'Tommy' : 'Томми'}}">
                                </div>
                                <div class="profile-form__field">
                                    <label for="profile-last-name"
                                           class="profile-form__label">{{__('Last Name')}}</label>
                                    <input form="profile-form" name="lastname" type="text" id="profile-last-name"
                                           class="profile-form__input"
                                           value="{{\Auth::user()->lastname}}"
                                           placeholder="{{app()->getLocale() == 'en' ? 'Cash' : 'Кэш'}}">
                                </div>
                                <div class="profile-form__field">
                                    <label for="country-reg" class="profile-form__label">{{__('Country')}}</label>
                                    <select form="profile-form" name="country" id="country-reg"
                                            class="profile-form__input">
                                        @foreach(getCodeCountryList() as $country)
                                            <option value="{{strtoupper($country['alpha2'])}}"
                                                    {{(strcasecmp(\Auth::user()->country, $country['alpha2']) == 0) ? 'selected' : ''}}>{{ $country['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="profile-form__btns">
                                    <button onclick="submitProfileForm()"
                                            class="profile-form__btn profile-form__btn--save">{{__('Save')}}</button>
                                </div>
                            </div>
                            <div class="profile-form__col">
                                <div class="profile-form__field">
                                    <label for="profile-telegram"
                                           class="profile-form__label">{{__('Telegram')}}</label>
                                    <input form="profile-form" name="telegram" type="text" id="profile-telegram"
                                           class="profile-form__input"
                                           value="{{\Auth::user()->telegram}}" placeholder="@tommycash">
                                </div>
                                <form id="recover-psw-form" class="" method="POST"
                                      action="{{ route('password.email') }}">
                                    {{ csrf_field() }}

                                    <div class="profile-form__field">
                                        <label for="profile-email" class="profile-form__label">E-mail</label>
                                        @if (empty(getUserEmailVerifiedAt()))
                                            <div>
                                                <input name="email" type="text" id="profile-email"
                                                       class="profile-form__input width-email"
                                                       value="{{\Auth::user()->email}}">
                                                @if (!empty(\Auth::user()->email) && empty(getUserEmailVerifiedAt()))
                                                    <a href="{{route('verification.resend')}}"
                                                       class="email-form__btn--promo">{{__('Send me confirmation')}}</a>
                                                @endif
                                            </div>
                                        @else
                                            <input name="email" type="text" id="profile-email"
                                                   class="profile-form__input"
                                                   value="{{\Auth::user()->email}}">
                                        @endif
                                    </div>
                                </form>
                                @if(isCanChangePassword())
                                    <button onclick="submitRecoverPswForm()"
                                            class="modal-form__btn modal-form__btn--rec-pass">{{__('Change password')}}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>


    @push('scripts')
      <script>
      $(function () {
        $(".avatar_input--label").click(function () {
          $("#avatar_id").click();
        });
        $("#avatar_id").change(function () {
          $('#avatar_error').hide();
          var files;
          files = this.files;
          var data = new FormData();
          $.each( files, function( key, value ){
            data.append( key, value );
          });

          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/my_profile_internal/process_avatar',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Не обрабатываем файлы (Don't process the files)
            contentType: false, // Так jQuery скажет серверу что это строковой запрос
            success: function( response, textStatus, jqXHR ){
              if(response.status == 'success') {
                $('#user_avatar').attr('src',response.avatar);
                toastr.success("{{ app()->getLocale() == 'en' ? 'Avatar changed' : 'Аватар изменён' }}");
              }
              else {
                $('#avatar_error').show();
              }
            },
            error: function( jqXHR, textStatus, errorThrown ){
              console.log('Error' + textStatus );
            }
          });
        });
      });

      $('.subs-close-btn').click(function () {
        let confirmed = confirm($(this).data('qst'));
        if (confirmed) {
          $.ajax({
            url: '/subscriptions',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'DELETE',
            success: function (response) {
              location.reload();
            },
            error: function (error) {
              console.log(error);
            }
          });
        }
      });
    </script>

    <script>
    setTimeout(function () {
      $(document).ready(function () {
        initItiPhoneField("{{\Auth::user()->country}}")
      });
      let handle1 = $( "#custom-handle-1" );
      var max_val = {{$user->getActiveWallet() ? number_format(socialboosterPriceByAmount($user->getActiveWallet()->getNextDiscountAmount() - $user->getActiveWallet()->spend_amount), 2, '.', '') : 0}}
      var current_val = {{$user->getActiveWallet() ?  number_format(socialboosterPriceByAmount($user->getActiveWallet()->spend_amount), 2, '.', '') : 0}}
      $( "#slider" ).slider({
        range: "max",
        value: current_val,
        min: 0,
        max: max_val,
        step: max_val / 10,
        create: function() {
          handle1.text(current_val);
        },
        slide: function( event, ui )
        {
          $( "#amount" ).val( ui.value);
          handle1.text( ui.value );
        }
      })
      $( "#slider" ).slider( "disable" );

    }, 1000)

    function submitProfileForm() {
      if (itiPhoneField) {
        let valPhone = $('#profile-phone').val();
        if ((valPhone.length > 0 && itiPhoneField.isValidNumber()) || !valPhone) {
          document.getElementById("profile-form").submit();
        }
      } else {
        document.getElementById("profile-form").submit();
      }
    }

    function submitRecoverPswForm() {
      document.getElementById("recover-psw-form").submit();
    }
  </script>
  @endpush
@endsection
