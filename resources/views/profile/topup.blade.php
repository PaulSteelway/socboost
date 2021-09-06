@extends(isFreePromotionSite() ? 'layouts.free_promotion.layout' : 'layouts.profile')


@section('title', __('Add funds') . ' - ' . __('site.site'))

@section('content')

  <main style="padding-top: 100px;">
    <section class="balance" style="position: relative">
      <div class="container" >
        <div class="row">
          <div class="col">
            @include('partials.inform')

            <form method="POST" action="{{ route('profile.topup.post') }}">
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
                          @if((app()->getLocale() == 'en' && $currency['code'] == 'USD') || (app()->getLocale() != 'en' && $currency['code'] == 'RUR'))
                          <option value="{{ $paymentSystem['id'].':'.$currency['id'] }}">
                            {{ $paymentSystem['name'] }} {{ app()->getLocale() == 'en' ? 'USD' : (in_array($paymentSystem['code'], ['robokassa']) ? '( Карта, Qiwi, Яндекс, Apple Pay )' : '') }}
                          </option>
                          @endif
                        @elseif (in_array($paymentSystem['code'], ['stripe']))
                          @if((app()->getLocale() == 'en' && $currency['code'] == 'USD') || (app()->getLocale() != 'en' && $currency['code'] == 'RUR'))
                          <option value="{{ $paymentSystem['id'].':'.$currency['id'] }}">
                            {{ $paymentSystem['name'] }} {{ app()->getLocale() == 'en' ? '( Credit or Debit card ) USD' : '( Карты со всего мира )' }}
                          </option>
                          @endif
                        @elseif ($paymentSystem['code'] == 'unitpay')
                          @if(\Auth::user()->email)
                            <option value="{{ $paymentSystem['id'].':'.$currency['id'] }}">
                              {{ $paymentSystem['name'] }} {{ app()->getLocale() == 'en' ? 'USD ( Apple Pay, PayPal )' : '( Карта, Qiwi, Яндекс, Apple Pay, PayPal )' }}
                            </option>
                          @endif
                        @elseif ($paymentSystem['code'] == 'payop')
                          @if(\Auth::user()->email)
                            <option value="{{ $paymentSystem['id'].':'.$currency['id'] }}">
                              {{ $paymentSystem['name'] }} {{ app()->getLocale() == 'en' ? '( WW Credit Cards, Skrill, WeChat Pay )' : '( Карты со всего мира, Skrill, WeChat Pay )' }}
                            </option>
                          @endif
                        @elseif ($paymentSystem['code'] == 'qiwi')
                          @if (app()->getLocale() != 'en')
                            <option value="{{ $paymentSystem['id'].':'.$currency['id'] }}">
                              {{ $paymentSystem['name'] }} {{ app()->getLocale() == 'ru' ? '( Банковская карта, Qiwi кошелёк)' : '' }}
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
                  <label for="balance-sum" class="balance-form__label"> {{ __("USD Amount") }}
                    <i class="fa fa-question-circle" aria-hidden="true"
                    data-title="{{getTopupQuestionTooltip()}}" id="topup-question"></i>
                  </label>
                  <div style="display: flex;">
                    <input id="amount" type="number" step="any"
                    class="balance-form__input balance-form__input--balance-sum"
                    name="amount" required
                    value="{{empty($amount) ? (app()->getLocale() == 'en' ? 50 : 3000) : intval($amount)}}">
                    @if(getEnterCommission() > 0)
                      <span class="help-block">{{__('Commission')}} {{ getEnterCommission() }} %</span>
                    @endif
                  </div>

                  <div class="balance-form__balance-actions">
                    @if(app()->getLocale() == 'en')
                      @foreach([50, 100, 300, 500, 1000] as $sum)
                        <button type="button" class="balance-form__btn--sum_select mb-1"
                        data-sum="{{ $sum }}"><span
                        style="opacity: 1">{{ $sum }}$</span>
                      </button>
                    @endforeach
                  @else
                    @foreach([1000, 3000, 5000, 15000, 30000] as $sum)
                      <button type="button" class="balance-form__btn--sum_select mb-1"
                      data-sum="{{ $sum }}"><span
                      style="opacity: 1">{{ $sum }}₽</span>
                    </button>
                  @endforeach
                @endif
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
              onclick="event.preventDefault(); applyPromocodeBtn()" type="submit"
              class="balance-form__btn--promo">{{__('Apply') }}</button>
              <div id="recaptcha_promo" class="g-recaptcha mb-3" data-sitekey="<?=Config::get('recaptcha.api_site_key')?>" data-callback="applyPromocode" data-size="invisible"></div>
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
  <div class="col" style="margin-top: 70px; margin-bottom: 80px">
    <form id="voucher_form" method="POST" action="{{ route('profile.topup.voucher') }}">
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
          <div id="recaptcha_voucher"></div>
          <button class="balance-form__btn balance-form__btn--replenish g-recaptcha"  data-sitekey="<?=Config::get('recaptcha.api_site_key')?>" data-callback="onVoucherSubmit" id="voucher_btn"  data-size="invisible">{{ __('Apply') }}</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
<div class="balance-form__bottom-background"><img src="{{asset('../images/balance-form_bottom-back.png')}}" alt=""></div>

  @push('scripts')
    <script type="text/javascript">
      var recaptcha_voucher = false;
      $(document).on('ready', function() {
        recaptcha_voucher = grecaptcha.render('recaptcha_voucher', {'sitekey' : '<?=Config::get('recaptcha.api_site_key')?>'});
      }).on('click', '#voucher_btn', function() {
        // event.preventDefault();
        // grecaptcha.execute();
      });

      function showPromocodeField() {
        debugger
        $('#promocode_input').show();
        $('#promocode_show_btn').hide();
      }

      function applyPromocodeBtn() {
        grecaptcha.execute();
      }

      function onVoucherSubmit(token) {
        $('#voucher_form').submit();
      }

      function applyPromocode() {
        $('#reward_block').html();
        $.ajax({
          url: "/get_promocode_details/" + $('#promocode').val(),
          data: {'g-recaptcha-response': grecaptcha.getResponse()}
        }).done(function (data) {
          $('#reward_block').html(data.data);
          grecaptcha.reset();
        });
      }

      $('.balance-form__btn--sum_select').click(function () {
        $('#amount').val($(this).data('sum'));
      });

  </script>
  <script src='https://www.google.com/recaptcha/api.js?hl=ru'></script>


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
