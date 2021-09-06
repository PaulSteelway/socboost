@extends('layouts.profile')

@section('title', __('Affiliate program') . ' - ' . __('site.site'))

@section('content')

  @push('scrips')
      <script>(function (e, t, n) {
          var r = e.querySelectorAll("html")[0];
          r.className = r.className.replace(/(^|\s)no-js(\s|$)/, "$1js$2")
      })(document, window, 0);</script>
  @endpush

    <div class="premium_account">
        <main style="padding-top: 100px; min-height: 75vh">
            <section class="affiliate-program ">
                <div class="container">
                    <div class="row">

                        <div class="col-12 col-md-7 affiliate-program__col">
                            <h2 class=" affiliate-program__title affiliate-program__title--earn">
                                <a class="affiliate-program__title profile-link " href="/settings">  {{__('My Profile')}}</a>
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

                        </div>
                        <div class="col-12 col-md-5 affiliate-program__img">
                            <div class="affiliate-program__img">
                                <img src="images/partner.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="premium-profile__discounts">
                <div class="row premium-profile__discounts-row">
                    <div class="premium-profile__discount">
                        <div class="profile__discount__title">Партнер 1 уровня</div>
                        <div class="profile__discount__img"><img src="/images/profile/15balance.png" alt=""></div>
                        <div class="profile__discount__text">Партнер 1 уровня получает 15% от суммы внесенной рефералом
                        </div>
                    </div>
                    <div class="premium-profile__discount">
                        <div class="profile__discount__title">Партнер 2 уровня</div>
                        <div class="profile__discount__img"><img src="/images/profile/7balance.png" alt=""></div>
                        <div class="profile__discount__text">Партнер 2 уровня получает 7% от суммы внесенной рефералом
                        </div>
                    </div>
                </div>
                <div class="row premium-profile__discounts-row">
                    <div class="premium-profile__discount">
                        <div class="profile__discount__title">Партнер 3 уровня</div>
                        <div class="profile__discount__img"><img src="/images/profile/4balance.png" alt=""></div>
                        <div class="profile__discount__text">Партнер 3 уровня получает 4% от суммы внесенной рефералом
                        </div>
                    </div>
                    <div class="premium-profile__discount">
                        <div class="profile__discount__title">Партнер 4 уровня</div>
                        <div class="profile__discount__img"><img src="/images/profile/3balance.png" alt=""></div>
                        <div class="profile__discount__text">Партнер 4 уровня получает 3% от суммы внесенной рефералом
                        </div>
                    </div>
                    <div class="premium-profile__discount">
                        <div class="profile__discount__title">Партнер 5 уровня</div>
                        <div class="profile__discount__img"><img src="/images/profile/2balance.png" alt=""></div>
                        <div class="profile__discount__text">Партнер 5 уровня получает 2% от суммы внесенной рефералом
                        </div>
                    </div>
                </div>
            </section>
            <section>
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
                                            <div class="col-md-6 referral_field__block" onclick="copyField()"> <input type="text" id="ref_field" class="referral_field" value="{{ $userRefLink }}"></div>
                                            {{--                                        <a id="usr-ref-link" title="Click to copy">{{ $userRefLink }}</a>--}}
                                        @endif
                                    </div>
                                    <div class="row">

                                        <div class="col-md-7 feedback__block">
                                            @if(!empty($userRefLink))

                                            <div class="feedbacks__title">{{__('Share your own link:')}}</div>
                                            <div class="feedback__body">
                                                <div><a target="_blank"
                                                        href="https://www.facebook.com/sharer/sharer.php?u={{ $userRefLink }}"
                                                        class="fb-xfbml-parse-ignore"><img
                                                                src="/images/icons/socials/facebook.png"/></a></div>
                                                <div><a target="_blank"
                                                        href="http://vk.com/share.php?url={{ $userRefLink }}"><img
                                                                src="/images/icons/socials/vk.png"/></a></div>
                                                <div><a target="_blank"
                                                        href="http://connect.ok.ru/dk?st.shareUrl={{ $userRefLink }}"><img
                                                                src="/images/icons/socials/ok.png"/></a></div>
                                                <div><a target="_blank"
                                                        href="http://twitter.com/share?url={{ $userRefLink }}"><img
                                                                src="/images/icons/socials/twitter.png"/></a></div>
                                            </div>
                                            @endif

                                        </div>

                                        <div class="col-md-5 profile_statistic">
                                            <h1 class="profile_statistic__title">{{__('Partner Statistics')}}</h1>
{{--                                            <div class="feedback_block">--}}
{{--                                                <div class="feedback_block__text"> <img src="svg/arrow-go.svg" alt="">--}}
{{--                                                    {{__('Переходов по ссылке')}}</div>--}}
{{--                                                <div class="feedback_block__res">{{ $countReferrals }}</div>--}}
{{--                                            </div>--}}
                                            <div class="feedback_block">
                                                <div class="feedback_block__text"> <img src="svg/arrow-go.svg" alt="">
                                                    {{__('Attracted')}}</div>
                                                <div class="feedback_block__res">{{ $countReferrals }} {{__('referral(s)')}}</div>
                                            </div>
                                            <div class="feedback_block">
                                                <div class="feedback_block__text"> <img src="svg/arrow-go.svg" alt="">
                                                    {{__('Money earned')}}</div>
                                                <div class="feedback_block__res">{{ number_format(Auth::user()->getReferralWallet() ? Auth::user()->getReferralWallet()->balance : 0, 2, '.', '') }} ₽</div>
                                            </div>
                                            @auth()
                                                <button class="header__reg-link referral_payout--btn  " data-toggle="modal" data-target="#referralPayouts">{{__('Withdrawal')}}</button>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <div class="modal" id="referralPayouts" tabindex="-1" role="dialog" aria-labelledby="referralPayoutModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="phoneCodeModalLabel" style="margin-left:-20px;margin-right:-20px;">
                        Вывод денег
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                @auth()
                    <div class="modal-body">
                        <div class="modal-form__container">
                            <div id="referral-errors"></div>
                            <form id="referral_payout-form" method="POST" name="referral_payout" role="form" class="modal-form"
                                  action="{{ route('customer.referral_payout') }}">
                            {{ csrf_field() }}
                            <!-- Change Icon Color -->
                                <div id="my-card" class="card-js"  style="width: 100%" data-capture-name="true" data-icon-colour="#158CBA">
                                    <input class="card-number my-custom-class">
                                </div>
                                <div class="" style="margin-top: 10px">
                                    <h4 class="current-service__packet-title">{{ __("Сумма") }} <span>(Доступно {{ number_format(socialboosterPriceByAmount(Auth::user()->getReferralWallet() ? Auth::user()->getReferralWallet()->balance : 0), 2, '.', '') }} ₽):</span></h4>
                                    <input name="amount" id="ref_amount" type="number" class="current-service__packet-input" value="">
                                </div>

                                <div class="modal-form__btns" style="margin-top: 19px;">

                                    <button class="modal-form__btn modal-form__btn--reg-now"
                                            id="referralPayoutSubmit">{{__('Вывести деньги')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{asset('card/card-js.min.css')}}">
    @endpush

    @push('scripts')
        <script src="{{asset('card/card-js.min.js')}}" defer></script>
        <script>
            $(function () {
                $("#referralPayoutSubmit").click(function(event) {
                    event.preventDefault()
                    var myCard = $('#my-card');
                    $.ajax({
                        url: '/referral_payout',
                        data: {
                            cardNumber: myCard.CardJs('cardNumber'),
                            cardType: myCard.CardJs('cardType'),
                            amount: +$('#ref_amount').val(),
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        dataType: 'JSON',
                        success: function (response) {
                            var res = '<div class="alert alert-success"> <ul>';
                            res += "<li class='success-item'>" + response.message + "</li>";
                            res += '</ul></div>';
                            $('#referral-errors').html(res);
                            setTimeout(function () {
                                location.reload()
                            }, 3000);
                        },
                        error: function (response) {
                            console.log(response)

                            if (response.status === 200 || response.status === 201) {
                                // location.href = "/";
                            } else {
                                var errors = '<div class="alert alert-danger"> <ul>';
                                if (response.responseJSON && response.responseJSON.errors) {
                                    var keys = Object.keys(response.responseJSON.errors);
                                    for (var i = 0; i < keys.length; i++) {
                                        errors += "<li class='error-item'>" + response.responseJSON.errors[keys[i]] + "</li>";
                                    }
                                } else {
                                    errors += "<li class='error-item'>" + response.message + "</li>";
                                }
                                errors += '</ul></div>';
                                $('#referral-errors').html(errors);
                                setTimeout(function () {
                                    $('#referral-errors').html('');
                                }, 5000);
                            }
                        }
                    });

                });
            });
        </script>

        <!--<script src="/js/custom-file-input.js"></script>-->
    @endpush



@endsection

@push('scripts')
  <script>
    function copyField() {
      /* Get the text field */
      var copyText = document.getElementById("ref_field");

      /* Select the text field */
      copyText.select();
      copyText.setSelectionRange(0, 99999); /*For mobile devices*/

      /* Copy the text inside the text field */
      document.execCommand("copy");
    }

    function submitProfileForm() {
      document.getElementById("profile-form").submit();
    }

    function submitRecoverPswForm() {
      document.getElementById("recover-psw-form").submit();
    }
  </script>
@endpush
