<section class="main_block">

  <div class="main_block__images">
    <div class="home-background__images--radio"></div>
    <div class="home-background__images">
      <img src="{{ asset('/images/free_promotion/fill-1.png') }}" alt="Бесплатная раскрутка">
    </div>
    <div class="icons_background"></div>
  </div>

  <div class="main_block-content">
    <div class="main_block-content__title">
      <h1>{{__('Free following building')}}</h1>
    </div>

    <div class="main_block-content__img-bg">
      <img src="{{ asset('/images/free_promotion/fill-1-mobile.png') }}" alt="Выполняй задания и зарабатывай баллы">
    </div>

    <div class="main_block-content__container">
      <div class="main_block-content__text">
        <p>{{__('At our service you can get free likes, followers, comments in Instagram, Tik Tok, YouTube, Vkontakte and many other social networks.')}}</p>
      </div>
      <div class="main_block-content__actions">
        @guest()
          <a href="#" data-toggle="modal" data-target="#regModalFree" class="content__actions--button">
            {{__('Become popular now')}}
          </a>
        @else
          <a href="{{route('freePromotion.task.tasklist')}}" class="content__actions--button">
            {{__('Become popular now')}}
          </a>
        @endguest
      </div>
    </div>
  </div>
</section>
