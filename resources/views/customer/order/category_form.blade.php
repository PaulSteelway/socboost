<form name="checkout" method="post" id="mainOrderForm"
      action="{{ empty($subscription) ? ($category['type'] == 'Subscription' ? route('checkoutSubscription') : route('checkout')) : route('profile.subscriptions.update', $subscription->id)}}">
    {{ csrf_field() }}
    <input type="hidden" id="curLoc" value="{{app()->getLocale()}}">
    @if($order_now !== true)
        <div class="current-service__packet">
            <h4 class="current-service__packet-title">{{__('Choose package')}}: <i class="fa fa-question-circle" aria-hidden="true" data-title="{{getPacketQuestionTooltip()}}" id="topup-question"></i></h4>
            <select name="packet" {{ count($packets) == 1 ? 'readonly="readonly"' : '' }} id="packet"
                    class="current-service__packet-select packet-options" style="-webkit-appearance: auto;">
                @foreach($packets as $key => $packet)
                    @if($packet['only_for_vip'] && (Auth::guest() || !Auth::user()->is_premium))
                        @continue
                    @endif
                    <option value="{{$packet['id']}}" data-price="{{$packet['price']}}"
                            data-lang="{{app()->getLocale()}}"
                            data-min="{{$packet['min']}}" data-max="{{$packet['max']}}"
                            data-link="{{$packet['link']}}"
                            data-item="{{json_encode($packet)}}"
                            {{ (old('packet') && old('packet') == $packet['id']) ? 'selected' : ($key == 0 ? 'selected' : '') }}>
                        {{ app()->getLocale() == 'en' ? $packet['name_en'] : $packet['name_ru'] }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif
    @if($order_now !== true)
        <div class="current-service__price">
            <div class="current-service__block-left--price">
                <div class="current-service__price-name">{{ __("Price / 1:") }}</div>
            </div>
            <div class="current-service__block-right" style="display: flex; margin-left: 0">
                <div class="current-service__price-amount priceOne">
                    <span id="price_base"></span>$</div>
                <div class="current-service__info-price priceOne">
                    <i class="fa fa-info-circle" style="padding-top: 6px"></i>
                    <p class="current-service__info-text">{{__('Price per 1 action')}} <strong><span class="price_info"
                            ></span> $ .</strong> (250 {{__('pcs')}}. =
                        <strong><span class="price_info_sum"></span> $
                        </strong>)</p>
                </div>
            </div>
        </div>
        <div class="current-service__info-price-mobile priceOne">
            <i class="fa fa-info-circle" style="margin-top: 5px"></i>
            <p class="current-service__info-text">{{__('Price per 1 action')}} <strong><span
                            class="price_info"></span> $
                    .</strong> (250 {{__('pcs')}}. =
                <strong><span class="price_info_sum"></span> $</strong>)</p>
        </div>
    @endif

    @if ($category['type'] == 'Subscription')
        <input type="hidden" name="category_url" value="{{ $category['url'] }} ">
        <div class="current-service__packet-amount">
            <div class="current-service__block-half-inline" style="margin-left: 0;">
                <h4 class="current-service__packet-title">{{ __('Payment Method') }}:</h4>
                <div class="input-username">
                    <select name="paymentMethod" class="current-service__packet-select" style="-webkit-appearance: menulist;"
                            {{ empty($subscription) ? '' : 'disabled' }}>
                        @foreach(config('enumerations.subscription_payment_types') as $key => $method)
                            <option value="{{$key}}" {{ (old('paymentMethod') && old('paymentMethod') == $key) ? 'selected' : ($key == 'card' ? 'selected' : '') }}>
                                {{ __($method) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="current-service__block-half-inline">
                <h4 class="current-service__packet-title">{{ __('Username') }}:&nbsp<a
                            href="{{route('faq')}}" target="_blank" style="color: black;"><i
                                class="fa fa-question-circle" aria-hidden="true"></i></a>
                </h4>
                <div class="input-username">
                    <input name="username" type="text" class="current-service__packet-link"
                           value="{{ empty($subscription) ? old('username') : $subscription->username}}" required>
                </div>
            </div>
        </div>
        <div class="current-service__packet-amount">
            <div class="current-service__block-third-inline">
                <h4 class="current-service__packet-title">{{ __('New Posts') }}:</h4>
                <div class="inp-w-btn">
                    <input name="posts" type="number" class="current-service__packet-input" id="orderPosts" required
                           data-step="15"
                           value="{{ empty($subscription) ? (old('posts') ? old('posts') : 15) : $subscription->posts}}">
                </div>
            </div>
            <div class="current-service__block-third-inline">
                <h4 class="current-service__packet-title">{{ __('Quantity Min') }}:</h4>
                <div class="inp-w-btn">
                    <input name="qtyMin" type="number" class="current-service__packet-input" id="orderQtyMin" required
                           data-step="250"
                           value="{{ empty($subscription) ? (old('qtyMin') ? old('qtyMin') : '') : $subscription->qty_min}}">
                </div>
                <div class="current-service__packet-limits">
                    {{__('Limits:')}} <span class="current-service__packet-limits-amount minMaxLimits"></span>
                </div>
            </div>
            <div class="current-service__block-third-inline">
                <h4 class="current-service__packet-title">{{ __('Quantity Max') }}:</h4>
                <div class="inp-w-btn">
                    <input name="qtyMax" type="number" class="current-service__packet-input" id="orderQtyMax" required
                           data-step="250"
                           value="{{ empty($subscription) ? (old('qtyMax') ? old('qtyMax') : '') : $subscription->qty_max}}">
                </div>
                <div class="current-service__packet-limits">
                    {{__('Limits:')}} <span class="current-service__packet-limits-amount minMaxLimits"></span>
                </div>
            </div>
        </div>
    @else
        <input type="hidden" name="category_url" value="{{ $category['url'] }} ">
        <div class="current-service__packet-amount">
            <div class="current-service__block-left">
                <h4 class="current-service__packet-title">{{ __("Quantity") }}:</h4>
                @if($order_now !== true)
                    <div class="inp-w-btn">
                        <input name="count" type="number" required id="orderQty" class="current-service__packet-input"
                               data-step="250" value="{{ old('count') ? old('count') : ''}}">
                    </div>
                @else
                    <input name="count" type="number" required id="orderQty" class="current-service__packet-input"
                           disabled value="100">
                @endif
                <div class="current-service__packet-limits">
                    {{__('Limits:')}} <span id="count_limits" class="current-service__packet-limits-amount"></span>
                </div>
            </div>
            <div class="current-service__block-right">
                <h4 class="current-service__packet-link-title">{{ __("Link") }}:&nbsp<a
                            href="{{route('faq')}}" target="_blank" style="color: black;"><i
                                class="fa fa-question-circle" aria-hidden="true"></i></a>
                </h4>
                <input name="link" type="text" class="current-service__packet-link" value="{{ old('link') }}" required>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-5 col-sm-12 current-service__total-block"
             style="justify-content: space-between; display: flex">
            <div class="current-service__price-name">{{ __('Charge') }}:</div>
            <div class="current-service__total-price">
                @if($order_now !== true)
                    <span class="priceAll">~</span>
                @else
                    <span>49</span>
                @endif
                <span>$</span>
                @auth
                    <div id="discountPrice" style="padding-left: 5px">
                        <em>
                            <span class="priceAll priceWithDiscount">~</span>$
                        </em>
                    </div>
                @endauth
            </div>
        </div>
        <div class="col-md-7" style="text-align: center">
            @if (!empty($subscription))
                <button type="submit" class="current-service__make-order-btn">{{ __('Save') }}</button>
            @else
                {{-- onclick="ym(51742118,'reachGoal','zakaz'); return true;" --}}
                <button type="submit" class="current-service__make-order-btn">{{ __('Submit') }}</button>
            @endif
        </div>
    </div>
    <div id="shortageBlock">
        {{__('Not enough money on the wallet balance.')}}
            @if (!empty($subscription))
                <a href="#" onClick='$("#mainOrderForm").submit();return false;'  id="replenishLink">{{__('Replenish')}}</a>
            @else
                {{-- onclick="ym(51742118,'reachGoal','zakaz'); return true;" --}}

                <br>
                @if(app()->getLocale() == 'en')
                    <a href="#" target="_blank" id="replenishLink" onClick='$("#mainOrderForm").submit();return false;'>Pay for order separately</a>
                @else
                    <a href="#" target="_blank" id="replenishLink" onClick='$("#mainOrderForm").submit();return false;'>Оплатить заказ отдельно</a>
                @endif
            @endif
        <?php /*
        @if(app()->getLocale() == 'en')
            <a href="#" target="_blank" id="replenishLink">{{__('Replenish')}}</a>
        @else
            <br><a href="#" target="_blank" id="replenishLink" onclick="submitMainOrderForm(event)">Оплатить заказ отдельно</a>
        @endif
        */?>
    </div>
</form>

@push('scripts')
<script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
<script src="https://js.stripe.com/v3/"></script>
  @if(!empty(session()->pull('autoRegister')))
    <script>
      $(document).ready(function() {
        if (!isAuth) {
          let formData = $('form[id=mainOrderForm]').serializeArray();
          let objFormData = formData.reduce((acc, element) => {
            if (element.name !== '_token') {
              acc[element.name] = element.value;
            }
            return acc;
          }, {});
          $('#register-order-form').append(`<input type='hidden' name='orderData' value='${JSON.stringify(objFormData)}' />`);
          $('#regOrderModal').modal('show');
        }
      });
    </script>
  @endif

  @if(!empty(session()->pull('orderNew')))
    <script>
      $(document).ready(function() {
        $('#firstOrderModal').modal('show');
      });
    </script>
  @endif

  @if(session()->exists(\Auth::id() . '_autoSubmitOrder'))
    <script>
        $(document).ready(function () {
            const payOrderData = {!! json_encode(session()->pull(\Auth::id() . '_autoSubmitOrder')) !!}
            console.log({!! json_encode(session()->pull(\Auth::id() . '_autoSubmitOrder')) !!});
            // if (payOrderData) {
            //     console.log('new widget logic');
            //     var payment = new UnitPay();
            //     payment.createWidget(payOrderData);
            //     payment.success(function (params) {
                    $('#mainOrderForm').append(`<input type='hidden' name='successPayment' value='1' />`)
                    $('#mainOrderForm').submit();
            //     });
            //     payment.error(function (message, params) {
            //         console.log(message);
            //         console.log(params);
            //     });
            //     return false;
            // }
            // console.log('ALDLADLALLADL');
        });
    </script>
  @endif
  @if(session()->pull(\Auth::id() . '_autoSubmitOrderEn'))
    <script>
    $(document).ready(function () {
        $('#mainOrderForm').append(`<input type='hidden' name='successPayment' value='1' />`)
      $('#mainOrderForm').submit();
    });
    </script>
  @endif
@endpush

