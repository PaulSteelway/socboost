<footer class="footer">

  @push('styles')
    <style>
      .change-lang {
        display: block;
        color: #fff;
        width: 35px;
        height: 35px;
        text-align: center;
        line-height: 60px;
        background: {{ app()->getLocale() == 'ru' ? 'url(/flags/US.png) no-repeat' : 'url(/flags/RU.png) no-repeat' }};
        font-size: 24px;
        animation: pulse_big 3s infinite;
      }

      .register-info {
        font-family: 'Montserrat', sans-serif;
        font-size: 11px;
        font-weight: 500;
        color: #ffffff;
        margin-top: 5px;
      }

      @media (max-width: 767px) {
        .register-info {
          text-align: center;
          margin-bottom: 20px;
        }
      }
    </style>
  @endpush


  <div class="footer-bottom">
    <div class="container">
      <div class="row footer-bottom__row">
        <div class="footer-row-element col-12 col-md-2 footer-logo__container">
          <div class="footer-logo">
            <img src="{{asset('svg/logo-light.svg')}}" alt="">
          </div>

        </div>

        <div class="footer-row-element col-12 col-md-9">
          <div class="footer-menu row footer-menu-free">
            <a href="{{ route('discount') }}" class="footer-menu__link">
              {{__('Discount system')}}
            </a>
            <a href="{{ route('customer.affilate') }}" class="footer-menu__link">
              {{__('Affiliate program')}}
            </a>
            <a href="{{ route('profile.tickets.index') }}" class="footer-menu__link">
              {{__('Tickets')}}
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>
</footer>
