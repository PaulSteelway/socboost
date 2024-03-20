{{-- nit:Daan need del --}}

@extends(isFreePromotionSite() ? 'layouts.free_promotion.layout' : 'layouts.profile')

@section('title', 'Пополнение баланса')

@section('content')

    <main style="padding-top: 100px;">
        <section class="balance" style="position: relative">
            <div class="container">
                <div class="row">
                    <div class="col">
                        @include('partials.inform')
                        <form method="POST" action="{{ route('profile.topup') }}">
                            {{ csrf_field() }}
                            <h2 class="balance-form__title">{{__('Balance refill')}}</h2>
                            <div class="balance-form__cols" style="max-width: unset">
                                <div class="balance-form__col balance-form__col--first">
                                    <div class="balance-form__field">
                                        <label for="balance-payment-system"
                                               class="balance-form__label">{{__('Payment system')}}</label>
                                        <select name="currency" id="balance-payment-system"
                                                class="balance-form__select">
                                            @foreach(getPaymentSystems() as $paymentSystem)
                                                @foreach($paymentSystem['currencies'] as $currency)
                                                    @if (in_array($paymentSystem['code'], ['paypal', 'robokassa']))
                                                        <option value="{{ $paymentSystem['id'].':'.$currency['id'] }}">
                                                            {{ $paymentSystem['name'] }} {{ app()->getLocale() == 'en' ? 'USD' : (in_array($paymentSystem['code'], ['robokassa']) ? '( Карта, Qiwi, Яндекс, Apple Pay )' : '') }}
                                                        </option>
                                                    @elseif ($paymentSystem['code'] == 'unitpay')
                                                        @if(\Auth::user()->email)
                                                            <option value="{{ $paymentSystem['id'].':'.$currency['id'] }}">
                                                                {{ $paymentSystem['name'] }} {{ app()->getLocale() == 'en' ? 'USD (' . __('ApplePay available') . ')' : '( Карта, Qiwi, Яндекс, Apple Pay )' }}
                                                            </option>
                                                        @endif
                                                    @elseif ($paymentSystem['code'] == 'payop')
                                                        @if(\Auth::user()->email)
                                                            <option value="{{ $paymentSystem['id'].':'.$currency['id'] }}">
                                                                {{ $paymentSystem['name'] }} {{ app()->getLocale() == 'en' ? '( WW Credit Cards, Skrill, WeChat Pay )' : '( Карты со всего мира, Skrill, WeChat Pay )' }}
                                                            </option>
                                                        @endif
                                                    @elseif (!in_array($paymentSystem['code'], ['enpay', 'free-kassa', 'cloudpayments']))
                                                        <option value="{{ $paymentSystem['id'].':'.$currency['id'] }}">{{ $paymentSystem['name'] }} {{ app()->getLocale() == 'en' ? $currency['code'] : '' }} {{ session()->has('lang') && session('lang') != 'ru' && $paymentSystem['code'] == 'free-kassa' ? ' (Only for CIS)' : '' }}</option>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="current_locale" value="{{app()->getLocale()}}">

                                    <div class="balance-form__field">
                                        <label for="balance-sum" class="balance-form__label"> {{ __("Points Amount") }}
                                            <i class="fa fa-question-circle" aria-hidden="true"
                                               data-title="{{getTopupQuestionTooltip()}}" id="topup-question"></i>
                                        </label>
                                        <div style="display: flex;">
                                            <input id="amount" name="amount" type="hidden" value="{{empty($amount) ? (app()->getLocale() == 'en' ? 10 : 200) : $amount}}">
                                            <input id="point_amount" type="number" step="any"
                                                   class="balance-form__input balance-form__input--balance-sum"
                                                   name="point_amount" required
                                                   value="{{empty($amount) ? (app()->getLocale() == 'en' ? 10 : 600) : ceil($amount * 3)}}">
                                            @if(getEnterCommission() > 0)
                                                <span class="help-block">{{__('Commission')}} {{ getEnterCommission() }} %</span>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="help-block-price">{{__('Price in')}} $ <span id="point_price"> - {{empty($amount) ? 200 : $amount}}</span></span>
                                        </div>

                                        <div class="balance-form__balance-actions">
                                            @foreach([1000, 3000, 5000, 15000, 30000] as $sum)
                                                <button type="button" class="balance-form__btn--sum_select mb-1"
                                                        data-sum="{{ $sum }}"><span
                                                            style="opacity: 1">{{ $sum }}{{app()->getLocale() == 'en' ? 'P' : 'Б'}}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="balance-form__field">
                                        <div class="balance-form__promo-container">
                                            <label for="balance-promo"
                                                   class="balance-form__label">{{ __("Promotional Code") }}</label>
                                            <input type="text" id="promocode" name="promocode"
                                                   class="balance-form__input balance-form__input--promo"
                                                   placeholder="">
                                            <span class="balance-form__promo-check"></span>
                                        </div>
                                        <button id="promocode_apply_btn" class="balance-form__btn--promo"
                                                onclick="event.preventDefault(); applyPromocode()" type="submit"
                                                class="balance-form__btn--promo">{{__('Apply') }}</button>
                                        <div id="reward_block" style="font-size: 20px; padding-top: 15px"></div>

                                    </div>

                                    <div class="balance-form__btns">
                                        <button class="balance-form__btn balance-form__btn--replenish"
                                                onclick="ym(51742118,'reachGoal','oplata'); return true;">{{ __("Top Up") }}</button>
                                    </div>
                                </div>
                                <div class="balance-form__col balance-form__col--second payment-image__block">
                                    <div class="payment-image"
                                         style=""><img src="{{asset('../images/topup-imge.png')}}" alt="">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="margin-top: 70px;">
                        <form method="POST" action="{{ route('profile.topup.voucher') }}">
                            {{ csrf_field() }}
                            <h2 class="balance-form__title"
                                style="margin-bottom: 10px;">{{__('Balance refill via voucher')}}</h2>
                            <div class="balance-form__promo">
                                <div class="balance-form__cols" style="margin-bottom: 0;">
                                    <div class="balance-form__col">
                                        <div class="balance-form__field">
                                            <label for="code"
                                                   class="balance-form__label"> {{ __('Voucher code') }}</label>
                                            <input type="text"
                                                   class="balance-form__input balance-form__input--balance-sum"
                                                   id="code" name="code" style="width: 250px;" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="balance-form__btns">
                                    <button class="balance-form__btn balance-form__btn--replenish">{{ __('Apply') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="balance-form__bottom-background">
              <img src="{{asset('../images/balance-form_bottom-back.png')}}" alt="">
            </div>

            @push('scripts')
              <script type="text/javascript">
                  $("#point_amount").keyup(function (){
                      var sum = $(this).val() / 3;
                      $('#point_price').text(sum.toFixed(2))
                      $('#amount').val(sum)
                  })

                  function showPromocodeField() {
                      $('#promocode_input').show();
                      $('#promocode_show_btn').hide();
                  }

                  function applyPromocode() {
                      $('#reward_block').html();
                      $.ajax({
                          url: "/get_promocode_details/" + $('#promocode').val(),
                      }).done(function (data) {
                          $('#reward_block').html(data.data);
                      });


                  }

                  $('.balance-form__btn--sum_select').click(function () {
                      $('#point_amount').val(+$(this).data('sum'));
                      $('#point_price').text((+$(this).data('sum') / 3).toFixed(2));
                      $('#amount').val(+$(this).data('sum') / 3);
                  });
              </script>
              @if(!empty($cloudpayments))
                  <script>
                      var cloudpayments = {!! json_encode($cloudpayments) !!};
                  </script>
              @endif
              @if(session()->exists(\Auth::id() . '_' . 'unitpay'))
                  <script>
                      var unitpay = {!! json_encode(session()->pull(\Auth::id() . '_' . 'unitpay')) !!};
                  </script>
              @endif
            @endpush

        </section>
    </main>


@endsection
