<footer class="footer" >

  @push('styles')
    <style media="screen">
      .change-lang {
        background: {{ app()->getLocale() == 'ru' ? 'url(/flags/US.png) no-repeat' : 'url(/flags/RU.png) no-repeat' }};
      }
    </style>
  @endpush

  <div class="footer-top">
    <div class="container">
      <div class="row footer-top__row">
        <div class="col-12 col-xl-2">
          <span class="footer-top__title">{{__('Payment Methods')}}</span>
        </div>
        <div class="col-12 col-xl-10">

          <div class="footer-payments">
            <div class="footer-payment d-none d-md-flex">
              <div class="pm-qiwi"></div>
              <div class="pm-apple_pay"></div>
              <div class="pm-google_pay"></div>
              <div class="pm-yandex_pay"></div>
              <div class="pm-sberbank"></div>
              <div class="pm-a"></div>
              <div class="pm-bitcoin_logo"></div>
              <div class="pm-mastercard"></div>
              <div class="pm-maestro"></div>
              <div class="pm-visa"></div>
              <div class="pm-paypal_3"></div>
              <div class="pm-american_express"></div>
              <div class="pm-cirrus"></div>
              <div class="pm-western_union"></div>
              <div class="pm-discover"></div>
            </div>
            <div class="footer-payment d-block d-md-none">
              <img class="mob-img" src="" data-src="{{asset('svg/qiwi.svg')}}" alt="">
              <img class="mob-img" src="" data-src="{{asset('svg/apple-pay.svg')}}" alt="">
              <img class="mob-img" src="" data-src="{{asset('svg/google-pay.svg')}}" alt="">
              <img class="mob-img" src="" data-src="{{asset('svg/yandex-pay.svg')}}" alt="">
              <img class="mob-img" src="" data-src="{{asset('svg/sberbank.svg')}}" alt="">
              <img class="mob-img" src="" data-src="{{asset('svg/a.svg')}}" alt="">
              <img class="mob-img" src="" data-src="{{asset('svg/bitcoin-logo.svg')}}" alt="">
              <img class="mob-img" src="" data-src="{{asset('svg/mastercard.svg')}}" alt="">
              <img class="mob-img" src="" data-src="{{asset('svg/maestro.svg')}}" alt="">
              <img class="mob-img" src="" data-src="{{asset('svg/visa.svg')}}" alt="">
              <img class="mob-img" src="" data-src="{{asset('svg/paypal-3.svg')}}" alt="">
              <img class="mob-img" src="" data-src="{{asset('svg/american-express.svg')}}" alt="">
              <img class="mob-img" src="" data-src="{{asset('svg/cirrus.svg')}}" alt="">
              <img class="mob-img" src="" data-src="{{asset('svg/western-union.svg')}}" alt="">
              <img class="mob-img" src="" data-src="{{asset('svg/discover.svg')}}" alt="">
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container">
      <div class="row footer-bottom__row">
        <div class="footer-row-element col-12 col-md-1 col-xl-1 change-lang__container">
          <a href="{{ app()->getLocale() == 'ru' ? route('set.lang', ['locale' => 'en']) : route('set.lang', ['locale' => 'ru']) }}" class="change-lang"></a>
        </div>
        <div class="footer-row-element col-12 col-md-2 footer-logo__container">
          <div class="footer-logo">
            <img src="{{asset('svg/logo-light.svg')}}" alt="">
          </div>
          @if(app()->getLocale() == 'ru')
            <div class="register-info">
              <div>ИП ФОМИН ЕВГЕНИЙ ЕВГЕНЬЕВИЧ</div>
              <div>ИНН: 0105 11242077</div>
            </div>
          @endif
        </div>
        <div class="footer-row-element col-12 col-md-9">
          <div class="footer-menu row">
            <div class="col-6 col-sm-6 col-md-3">
              <nav>
                <span class="footer-menu--title">{{__('Services')}}</span>
                <ul class="footer-menu__list">
                  <li class="footer-menu__item">
                    <a href="{{ route('customer.subscription') }}" class="footer-menu__link">{{__('Service subscription')}}</a>
                  </li>
                  <li class="footer-menu__item">
                    <a href="{{ route('customer.app') }}" class="footer-menu__link">{{__('Application and Bot')}}</a>
                  </li>
                </ul>
              </nav>
            </div>
            <div class="col-6 col-sm-6 col-md-3">
              <nav>
                <span class="footer-menu--title">{{__('For users')}}</span>
                <ul class="footer-menu__list">
                  <li class="footer-menu__item">
                    <a href="{{ route('discount') }}" class="footer-menu__link">{{__('Discount system')}}</a>
                  </li>
                  <li class="footer-menu__item">
                    <a href="{{ route('customer.affilate') }}" class="footer-menu__link">  {{__('Referral program')}}</a>
                  </li>
                  <li class="footer-menu__item">
                    <a href="{{ route('reviews') }}" class="footer-menu__link">{{__('Reviews')}}</a>
                  </li>
                  <li class="footer-menu__item">
                    <a href="{{ route('customer.videoReview') }}" class="footer-menu__link">{{__('Video reviews')}}</a>
                  </li>
                  <li class="footer-menu__item">
                    <a href="{{ route('customer.voucher') }}" class="footer-menu__link">{{__('Vouchers')}}</a>
                  </li>
                  <li class="footer-menu__item">
                    <a href="{{ route('customer.public_offer') }}" class="footer-menu__link">{{__('Public offer')}}</a>
                  </li>
                  <li class="footer-menu__item">
                    <a href="{{ route('customer.public_policy') }}" class="footer-menu__link">{{__('Privacy policy')}}</a>
                  </li>
                  @if(app()->getLocale() == 'en')
                  <li class="footer-menu__item">
                    <a href="{{ route('customer.refund_policy') }}" class="footer-menu__link">Refund Policy</a>
                  </li>
                  @endif
                  @if(app()->getLocale() == 'ru')
                    <li class="footer-menu__item">
                      <a href="{{ route('customer.blog') }}" class="footer-menu__link">{{__('Blog')}}</a>
                    </li>
                  @endif
                </ul>
              </nav>
            </div>
            <div class="col-6 col-sm-6 col-md-3">
              <nav>
                <span class="footer-menu--title">{{__('For Partners')}}</span>
                <ul class="footer-menu__list">
                  <li class="footer-menu__item">
                    <a href="{{ route('customer.affilate') }}" class="footer-menu__link">  {{__('Affiliate program')}}</a>
                  </li>
                  <li class="footer-menu__item">
                    <a href="{{ route('customer.prices-search') }}" class="footer-menu__link">{{__('Prices')}}</a>
                  </li>
                  <li class="footer-menu__item">
                    <a href="{{ route('api.index') }}" class="footer-menu__link">{{__('API')}}</a>
                  </li>
                </ul>
              </nav>
            </div>
            <div class="col-6 col-sm-6 col-md-3">

              <nav>
                <span class="footer-menu--title">{{__('Help')}}</span>
                <ul class="footer-menu__list">
                  <li class="footer-menu__item">
                    <a href="{{ route('profile.tickets.index') }}" class="footer-menu__link">{{__('Tickets')}}</a>
                  </li>
                  <li class="footer-menu__item">
                    <a href="{{ route('contacts') }}" class="footer-menu__link">{{__('Customer support')}}</a>
                  </li>
                  <li class="footer-menu__item">
                    <a href="{{ route('customer.about') }}" class="footer-menu__link">  {{__('About Us')}}</a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="preloaderAjax"></div>
    @push('scripts')
      <script>
        window.onload = function() {
          mobileIcons();
        };

        window.onresize = function(event) {
          mobileIcons();
        };

        function mobileIcons() {
          if(screen.width < 768) {
            let images = document.getElementsByClassName('mob-img');

            for(let image of images) {
              let src = image.getAttribute('data-src');
              image.setAttribute('src', src);
            }
          }
        }
      </script>
    @endpush
</footer>
