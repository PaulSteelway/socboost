<div class="modal" id="authModalFree" tabindex="-1" role="dialog" style="overflow: hidden" data-backdrop="static" data-keyboard="false" aria-labelledby="authModalLabel" aria-hidden="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="authModalLabel">{{ __("Sign in") }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div id="login-errors"></div>
        <div class="modal-form__container">
          <div class="modal-form__title">{{__('Login via')}} :</div>

          @include('auth.socialite')
          <div class="modal-form__or">{{__('or')}}</div>

          <form id="login-form" method="POST" action="{{ route('login') }}" name="signin" role="form" class="modal-form">
            {{ csrf_field() }}
            <div class="modal-form__field">
              <label for="login-auth" class="modal-form__label">{{ __("Your e-mail or phone") }}</label>
              <input type="text" name="login" id="login-auth" class="modal-form__input">
            </div>
            <div class="modal-form__field">
              <label for="pass-auth" class="modal-form__label">{{ __("Password") }}</label>
              <input type="password" name="password" id="pass-auth" class="modal-form__input">
            </div>

            <div class="modal-form__btns">
              <button class="modal-form__btn modal-form__btn--auth">
                {{ __("Sign in") }}
              </button>
              <button class="modal-form__btn modal-form__btn--register" type="button" data-toggle="modal" data-target="#regModalFree" data-dismiss="modal">
                {{ __("Registration") }}
              </button>
              <button class="modal-form__btn modal-form__btn--pass" type="button" data-toggle="modal" data-target="#passModal" data-dismiss="modal">
                {{ __("Forgot password?") }}
              </button>
              <button class="modal-form__btn modal-form__btn--close" type="button" data-dismiss="modal" aria-label="Close">
                {{  __('site.close')}}
              </button>
            </div>
            
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
