<header class="header header--main-page free_promotion">
  <div class="header__container">

    <div class="row header__row">
      <div class="col-12 col-md-2 header__logo-container">
        <div class="header__logo">
          <a href="/"><img src="/images/free_promotion/logo-socialbooster-free.png" alt=""></a>
        </div>
      </div>

      <div class="col-8 header__menu-container">
        <div class="header__menu">
          <nav class="main-menu">
            <ul class="main-menu__list">

              @guest
                <li class="main-menu__item">
                  @guest()
                    <a href="#" data-toggle="modal" data-target="#regModalFree" class="main-menu__link ">{{ __('Накрутка лайков') }}</a>
                  @else
                    <a href="#" class="main-menu__link ">{{ __('Накрутка лайков') }}</a>
                  @endguest
                </li>

                <li class="main-menu__item">
                  @guest()
                    <a href="#" data-toggle="modal" data-target="#regModalFree" class="main-menu__link">
                      {{__('Накрутка подписчиков')}}
                    </a>
                  @else
                    <a href="#" class="main-menu__link">
                      {{__('Накрутка подписчиков')}}
                    </a>
                  @endguest
                </li>

                <li class="main-menu__item">
                  @guest()
                    <a href="#" data-toggle="modal" data-target="#regModalFree"  class="main-menu__link">
                      {{__('Накрутка просмотров')}}
                    </a>
                  @else
                    <a href="#" class="main-menu__link">
                      {{__('Накрутка просмотров')}}
                    </a>
                  @endguest
                </li>

              @endguest

              @auth()
                <li class="main-menu__item">
                  <a href="{{ route('freePromotion.task.tasklist', ['type' => 'exchange']) }}" class="main-menu__link my-tasks">
                    {{__('Мои задания')}}
                  </a>
                </li>
                <li class="main-menu__item">
                  <a href="{{ route('freePromotion.task.tasklist') }}" class="main-menu__link job-exchange">
                    {{__('Биржа заданий')}}
                  </a>
                </li>

                {{-- Только для Биржи заданий --}}
                @if (Request::route()->getName() == 'freePromotion.task.tasklist' ||
                  Request::route()->getName() == 'freePromotion.task.create' ||
                  Request::route()->getName() == 'freePromotion.black_list')
                  <li class="main-menu__item">
                    <soc-select :profiles="{{ $profiles }}" :types="{{ $types }}" />
                  </li>
                @endif

              @endauth

            </ul>
          </nav>
          <div class="header__menu-hamburger">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </div>

      @if(!\Auth::user())
        <div class="col-2 header__login-container">
          <div class="header__login">
            <a href="#" class="header__login-link" data-toggle="modal"
            data-target="#authModalFree">{{__('Login')}}</a>
          </div>
        </div>
      @else
        <div class="col-2 header__login-container">
          <div class="header__login header__login--logined">
            <ul class="account-menu">
              <li class="header__bonus">
                <a>
                  <img src="{{asset('images/currency.png')}}" class="header__bonus-icon">
                  <span class="header__bonus-amount">
                    {{ empty($wallet) ? 0.00 : number_format(socialboosterPriceByAmount($wallet->balance), 2, '.', '') }} {{ app()->getLocale() == 'en' ? 'Points' : getPointsName($wallet->balance) }}
                  </span>
                </a>
                @auth
                  <input id="dsc" type="hidden" value="{{isset($wallet) ? $wallet->getDiscount() : 0 }}">
                @endauth
              </li>
              <li class="account-item">

                <img class="account-item__img" src="{{ asset(Auth::user()->avatar ?? '/svg/account-img-new.svg') }}" alt="">
                <span>{{__('My account')}}</span>
                <ul class="account-submenu">

                  <li class="account-submenu__item">
                    <a href="/topup" class="account-submenu__link">{{__("Add funds")}}</a>
                  </li>

                  <li class="account-submenu__item">
                    <form id="logout-form" class="account-submenu__link" action="{{ route('logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                    </form>
                    <a class="account-submenu__link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __("Log out") }}</a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      @endif

    </div>
  </div>
  <span id="locale" hidden>{{app()->getLocale()}}</span>
</header>
