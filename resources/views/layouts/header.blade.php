<header class="header header--main-page">
  <div class="container header__container">
    <div class="row header__row">

      <div class="col-12 col-md-2 header__logo-container">
        <div class="header__logo">
          <a href="{{ URL::to('/') }}">
            <img src="{{ asset('/svg/logo-dark.svg') }}" alt="SocialBooster">
          </a>
        </div>
      </div>

      <div class="col-6 header__menu-container">
        <div class="header__menu">
          <nav class="main-menu">
            <ul class="main-menu__list">

              <li class="main-menu__item has-submenu">
                <a href="#" class="main-menu__link">
                  {{__('Services')}}
                </a>

                <ul class="submenu">
                  @foreach ($categories as $key => $category)
                    <li class="submenu__item">

                      <div class="submenu__link">
                        <span class="submenu__link-img">
{{--                          <div class="d-none d-md-block sm-{{ $category['icon_class'] }}"></div>--}}
{{--                          <img class="d-block d-md-none mob-img" src="" data-src="{{ asset('/' . $category['icon_img']) }}" width="24" alt="">--}}
                          <img src="{{ asset('/' . $category['icon_img']) }}" data-src="{{ asset('/' . $category['icon_img']) }}" width="24" alt="">
                        </span>
                        <span class="submenu__link-name">
                          {{ $category['name_' . app()->getLocale()] }}
                        </span>
                      </div>

                      @if(!empty($category['subcategories']))
                        <ul class="submenu submenu--level2">
                          @foreach($category['subcategories'] as $cat)
                            <li class="submenu__item">
                              <a href="{{ route('order.category', $cat['url']) }}" class="submenu__link submenu__link--level2">
                                <img class="d-block d-md-none mob-img mob-img submenu__icon" src="" data-src="{{ asset('/' . $cat['icon_img']) }}" width="24">
                                <img class="d-block d-md-none mob-img mob-img submenu__icon submenu__icon--hover" src="" data-src="{{ asset('/' . $cat['icon_img_active']) }}" width="24">
                                <span class="submenu__link--text">
                                  {{ $cat['name_' . app()->getLocale()] }}
                                </span>
                              </a>
                            </li>
                          @endforeach
                        </ul>
                      @endif

                    </li>
                  @endforeach

                  <li class="submenu__item">
                    <a href="/readyaccount" class="submenu__link">
                      <span class="submenu__link-img">
{{--                        <div class="sm-readyAcc d-none d-md-block"></div>--}}
{{--                        <img class="d-block d-md-none mob-img" src="" data-src="{{ asset('/images/icons/readyAcc.png' ) }}" width="24">--}}
                        <img src="{{ asset('/images/icons/readyAcc.png' ) }}" data-src="{{ asset('/images/icons/readyAcc.png' ) }}" width="24">
                      </span>
                      <span class="submenu__link-name">
                        {{__('Ready accounts')}}
                      </span>
                    </a>
                  </li>

                  @if(app()->getLocale() == 'ru')
                    <li class="submenu__item">
                      <a href="/courses/tiktok" class="submenu__link">
                        <span class="submenu__link-img">
{{--                          <div class="sm-course_tiktok d-none d-md-block"></div>--}}
{{--                          <img class="d-block d-md-none mob-img" src="" data-src="{{ asset('/images/icons/course-tiktok.png' ) }}" width="24" alt="Tik-Tok">--}}
                          <img src="{{ asset('/images/icons/course-tiktok.png' ) }}" data-src="{{ asset('/images/icons/course-tiktok.png' ) }}" width="24" alt="Tik-Tok">
                        </span>
                        <span class="submenu__link-name">
                          {{__('Курс TikTok')}}
                        </span>
                      </a>
                    </li>
                  @endif

                </ul>
              </li>

              <li class="main-menu__item">
                <a href="{{ route('faq') }}" class="main-menu__link">
                  {{__('FAQ')}}
                </a>
              </li>

              <li class="main-menu__item">
                <a href="{{ route('contacts') }}" class="main-menu__link">
                  {{__('Contacts')}}
                </a>
              </li>

              <li class="main-menu__item">
                <a href="{{ route('discount') }}" class="main-menu__link">
                  {{__('Discounts')}}
                </a>
              </li>

            </ul>
          </nav>

          <div class="header__menu-hamburger">
            <span></span>
            <span></span>
            <span></span>
          </div>

        </div>
      </div>

      @include('layouts.header.usermenu')
          {{--            @include('partials.inform')--}}
        </div>
      </div>
      <span id="locale" hidden>{{app()->getLocale()}}</span>
    </header>
