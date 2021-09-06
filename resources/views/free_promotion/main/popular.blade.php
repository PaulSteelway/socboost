<section class="free_promotion_block" id="publics">
  <img class="free_promotion_block__image" src="{{ asset('/images/free_promotion/freePromotionBackground.png') }}" alt="SocialBooster Free">
  <img class="free_promotion_block__image--mobile"
  src="{{ asset('/images/free_promotion/freePromotionBackground--mobile.png') }}" alt="SocialBooster Free">

  <div class="free_promotion_block__title">
    {{__('You can promote all social networks with SocialBooster Free')}}
  </div>

  <div class="free_promotion_block__action">
    @guest()
      <a href="#" data-toggle="modal"
      data-target="#regModalFree" class="">{{__('Become popular now')}}</a>
    @else
      <a href="{{route('freePromotion.task.tasklist')}}" class="">{{__('Become popular now')}}</a>
    @endguest
  </div>
</section>
