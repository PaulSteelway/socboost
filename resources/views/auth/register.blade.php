@extends(isFreePromotionSite() ? 'layouts.free_promotion.layout' : 'layouts.customer')

@section('title', __('Register') . ' - ' . __('site.site'))

@section('content')
    <div class="main-screen" style="height: 83vh"></div>

    <div class="modal in show"  id="regModal" tabindex="-1" style="display: block" role="dialog" aria-labelledby="regModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="regModalLabel">{{__('Sign Up')}}</h5>
                    <a href="/" type="button" class="close" data-dismiss="modal" aria-label="Close"></a>
                </div>
                <div class="modal-body">
                    @include('partials.inform')
                    <div class="modal-form__container">
                        <div class="modal-form__title">{{__('Login with')}}:</div>
                        @include('auth.socialite')
                        <div class="modal-form__or">{{__('or')}}</div>
                        <form method="POST" action="{{ route('register') }}" name="signup" role="form" class="modal-form">
                            {{ csrf_field() }}
                            {{--                        <div class="modal-form__field">--}}
                            {{--                            <label for="login-reg" class="modal-form__label">{{__('Your login')}}</label>--}}
                            {{--                            <input type="text" id="login-reg" class="modal-form__input">--}}
                            {{--                        </div>--}}
                            <div class="modal-form__field">
                                <label for="email-reg" class="modal-form__label">E-mail</label>
                                <input type="text" name="email" id="email-reg" class="modal-form__input">
                            </div>
                            <div class="modal-form__field">
                                <label for="name-reg" class="modal-form__label">{{__('Name')}}</label>
                                <input name="name" type="text" id="name-reg" class="modal-form__input">
                            </div>
                            <div class="modal-form__field">
                                <label for="pass-reg" class="modal-form__label">{{__('Your password')}}</label>
                                <input type="password" name="password" id="pass-reg" class="modal-form__input">
                            </div>
                            <div class="modal-form__field">
                                <label for="lastname-reg" class="modal-form__label">{{__('Lastname')}}</label>
                                <input name="lastname" type="text" id="lastname-reg" class="modal-form__input">
                            </div>
                            <div class="modal-form__field">
                                <label for="repeat-pass-reg" class="modal-form__label">{{__('Confirm password')}}</label>
                                <input type="password" name="password_confirmation" id="repeat-pass-reg"
                                       class="modal-form__input">
                            </div>

                            <div class="modal-form__btns">
                                <button class="modal-form__btn modal-form__btn--reg-now">{{__('Registrate')}}</button>
                                <button class="modal-form__btn modal-form__btn--close" type="button" data-dismiss="modal"
                                        aria-label="Close">{{__('Close')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
