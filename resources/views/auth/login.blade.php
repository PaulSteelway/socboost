@extends(isFreePromotionSite() ? 'layouts.free_promotion.layout' : 'layouts.customer')

@section('title', __('Sign in') . ' - ' . __('site.site'))

@section('content')
<div class="main-screen" style="height: 83vh"></div>
    <div class="modal in show" style="display: block" id="authModal" tabindex="-1" role="dialog" aria-labelledby="authModalLabel" aria-hidden="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel">{{ __("Sign in") }}</h5>
                    <a href="/" type="button" class="close" data-dismiss="modal" aria-label="Close"></a>
                </div>
                <div class="modal-body">
                    @include('partials.inform')
                    <div class="modal-form__container">
                        <div class="modal-form__title">{{__('Login via')}} :</div>
                        @include('auth.socialite')
                        <div class="modal-form__or">{{__('or')}}</div>
                        <form method="POST" action="{{ route('login') }}" name="signin" role="form" class="modal-form">
                            {{ csrf_field() }}
                            <div class="modal-form__field">
                                <label for="login-auth" class="modal-form__label">{{ __("Your e-mail") }}</label>
                                <input type="text" name="login" id="login-auth" class="modal-form__input">
                            </div>
                            <div class="modal-form__field">
                                <label for="pass-auth" class="modal-form__label">{{ __("Password") }}</label>
                                <input type="password" name="password" id="pass-auth" class="modal-form__input">
                            </div>
                            <div class="modal-form__btns" style="text-align: center">
                                <a href="/register" class="modal-form__btn modal-form__btn--register">{{ __("Registration") }}</a>
                                <button class="modal-form__btn modal-form__btn--auth">{{ __("Sign in") }}</button>
                                <a  href="/password/reset"  style="margin-top: 10px"class="modal-form__btn modal-form__btn--pass">
                                    {{ __("Forgot password?") }}
                                </a>
                                <button class="modal-form__btn modal-form__btn--close" type="button" data-dismiss="modal"
                                        aria-label="Close">Закрыть
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
      <script type="text/javascript">
        $(window).on('load', function () {
          $('#authModal').modal('show');
        });
    </script>
  @endpush
@endsection
