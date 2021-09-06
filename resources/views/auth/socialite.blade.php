<div class="modal-form__links">

  @if(config('services.facebook.client_id') && config('services.facebook.client_secret'))
    <a href="{{route('auth.socialite', ['provider' => 'facebook'])}}" class="modal-form__link modal-form__link--fb">
      <img src="{{asset('images/facebook.png')}}" align="center" width="50px">
    </a>
  @endif

  {{-- @if(config('services.instagram.client_id') && config('services.instagram.client_secret'))
    <a href="{{route('auth.socialite', ['provider' => 'instagram'])}}"
      class="modal-form__link modal-form__link--inst">
      <img src="{{asset('img/socialite/instagram.jpeg')}}" align="center" width="50px">
    </a>
  @endif --}}

  @if(config('services.linkedin.client_id') && config('services.linkedin.client_secret'))
    <a href="{{route('auth.socialite', ['provider' => 'linkedin'])}}">
      <img src="{{asset('img/socialite/linkedin.jpeg')}}" align="center" width="50px">
    </a>
  @endif

  @if(config('services.vkontakte.client_id') && config('services.vkontakte.client_secret'))
    <a href="{{route('auth.socialite', ['provider' => 'vkontakte'])}}"
      class="modal-form__link modal-form__link--vk">
      <img src="{{asset('images/vk.png')}}" align="center" width="50px">
    </a>
  @endif

  @if(config('services.odnoklassniki.client_id') && config('services.odnoklassniki.client_secret') && config('services.odnoklassniki.client_public'))
    <a href="{{route('auth.socialite', ['provider' => 'odnoklassniki'])}}"
      class="modal-form__link modal-form__link--ok">
      <img src="{{asset('images/ok.png')}}" align="center" width="50px">
    </a>
  @endif

</div>
