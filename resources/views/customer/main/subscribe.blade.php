<section class="subscribe">
  <div class="container">
    <h2 class="subscribe__title">
      {{__('Service subscription')}}
    </h2>

    <div class="subscribe__price">
      {{__('From')}} {{ number_format(socialboosterPriceByAmount(1199), 2, '.', '') }} {{ app()->getLocale() == 'en' ? '$' : '₽' }} / {{__('month')}}
    </div>

    <div class="row subscribe__row">
      <div class="col-12 col-lg-7">
        <h3 class="subscribe__text-title">{{__('Don\'t waste your time')}}!</h3>
        <p class="subscribe__text">{{__('Tired of placing orders manually? Fed up with ordering likes for each new post by yourself? But what if we say that this process can be automated by using subscription? Instagram likes, Telegram post views, and much more')}}.</p>
        <p class="subscribe__text">{{__('How to arrange it? You can do this in few clicks: Tired of placing orders manually? Fed up with ordering likes for each new post by yourself? But what if we say that this process can be automated by using subscription? Instagram likes, Telegram post views, and much more.')}}</p>
      </div>

      <div class="col-12 col-lg-5">
        <div class="subscribe__services">

          <div class="subscribe__service">
            <div class="subscribe__service-left">
              <div class="subscribe__service-img-container">
                <div class="sm-instagram"></div>
                <!-- <img src="{{ asset('svg/instagram.svg') }}" alt="Instagram" class="subscribe__service-img"> -->
              </div>
              <div class="subscribe__service-name">
                Instagram
              </div>
            </div>
            <a href="/c/instagram-likes" class="subscribe__service-link">
              {{__('Order')}}
            </a>
          </div>

          <div class="subscribe__service">
            <div class="subscribe__service-left">
              <div class="subscribe__service-img-container">
                <div class="sm-telegram"></div>
                <!-- <img src="{{ asset('svg/telegram.svg') }}" alt="Telegram" class="subscribe__service-img"> -->
              </div>
              <div class="subscribe__service-name">
                Telegram
              </div>
            </div>
            <a href="/c/tgsubscribers" class="subscribe__service-link">
              {{__('Order')}}
            </a>
          </div>

          <div class="subscribe__service">
            <div class="subscribe__service-left">
              <div class="subscribe__service-img-container">
                <div class="sm-TikTok2"></div>
                <!-- <img src="{{ asset('svg/tik-tok.svg') }}" alt="Tik-Tok" class="subscribe__service-img"> -->
              </div>
              <div class="subscribe__service-name">
                TikTok
              </div>
            </div>
            <a href="/c/ttsub" class="subscribe__service-link">
              {{__('Order')}}
            </a>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>
