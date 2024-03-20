@guest
  <div class="col-4 header__login-container">
    <div class="header__login">
      <a href="#" class="header__login-link" data-toggle="modal" data-target="#authModal">
        {{__('Login')}}
      </a>
      <a href="#" class="header__reg-link" data-toggle="modal" data-target="#regModal">
        {{__('Registration')}}
      </a>
    </div>
  </div>
@else
  <div class="col-4 header__login-container">
    <div class="header__login header__login--logined">
      <ul class="account-menu">
        <li class="header__bonus">

          @if (isset($wallet->balance))
            @if (isFreePromotionSite())
              <a>
                <span class="header__bonus-amount">
                  {{ empty($wallet) ? 0.00 : number_format(socialboosterPriceByAmount($wallet->balance), 2, '.', '') }} {{ app()->getLocale() == 'en' ? 'Points' : getPointsName($wallet->balance) }}
                </span>
              </a>
            @else
              <a href="{{ route('profile.topup') }}">
                <img src="{{asset('images/currency.png')}}" class="header__bonus-icon">
                <span class="header__bonus-amount">
                  {{ null !== $wallet ? number_format(socialboosterPriceByAmount($wallet->balance), 2, '.', '') : 0.00 }} $
                </span>
                /
                <span class="header__bonus-percent">
                  {{__('Discount')}} {{ isset($wallet) ? $wallet->getDiscount() : 0 }} %
                </span>
              </a>
            @endif
          @endif
          <input id="dsc" type="hidden" value="{{ isset($wallet) ? $wallet->getDiscount() : 0 }}">
        </li>

        <li class="account-item">
          <img class="account-item__img" src="{{ asset(Auth::user()->avatar ?? '/svg/account-img-new.svg') }}" alt="">
          <span>{{__('My account')}}</span>
          <ul class="account-submenu">
            <li class="account-submenu__item">
              <a href="{{ route('profile.settings') }}" class="account-submenu__link">
                {{__('My Profile')}}
              </a>
            </li>

            <li class="account-submenu__item">
              <a href="{{ route('profile.topup') }}" class="account-submenu__link">
                {{__("Add funds")}}
              </a>
            </li>

            @if(app()->getLocale() == 'en')
              <li class="account-submenu__item">
                <a href="{{ route('set.lang', ['locale' => 'ru']) }}" class="account-submenu__link">
                  По русски <img class="flag-icon" src="{{ asset('/images/russia-flag.png') }}"/>
                </a>
              </li>
            @else
              <li class="account-submenu__item">
                <a href="{{ route('set.lang', ['locale' => 'en']) }}" class="account-submenu__link">
                  English <img class="flag-icon" src="{{ asset('/images/us-flag.png') }}">
                </a>
              </li>
            @endif

            <li class="account-submenu__item">
              <a href="{{ route('profile.operations.index') }}" class="account-submenu__link">
                {{ __("Orders history") }}
              </a>
            </li>
            <li class="account-submenu__item">
              <a href="{{ route('profile.subscriptions.index') }}" class="account-submenu__link">
                {{ __('Subscriptions history') }}
              </a>
            </li>

            <li class="account-submenu__item">
              <a href="{{ route('profile.tickets.index') }}" class="account-submenu__link">
                {{ __('Tickets') }}
              </a>
            </li>

            <li class="account-submenu__item">
              <form id="logout-form" class="account-submenu__link" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
              <a class="account-submenu__link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __("Log out") }}
              </a>
            </li>

          </ul>
        </li>
      </ul>
    </div>
  </div>
@endguest
