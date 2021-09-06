@extends(isFreePromotionSite() ? 'layouts.free_promotion.layout' : 'layouts.customer')

@section('title', __('Reset password') . ' - ' . __('site.site'))

@section('content')
    <div class="main-screen" style="height: 78vh"></div>
    <div class="modal  in show" id="passModal" tabindex="-1" role="dialog"  style="display: block" aria-labelledby="passModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passModalLabel">{{__('Password recovery')}}</h5>
                    <a href="/" type="button" class="close" data-dismiss="modal" aria-label="Close"></a>
                </div>
                <div class="modal-body">
                    @include('partials.inform')
                    <div class="modal-form__container">
                        <div class="modal-form__title">{{__('A new password will be sent to your inbox')}}</div>
                        <form action="" class="modal-form modal-form--recovery">
                            <div class="modal-form__field">
                                <label for="email-pass" class="modal-form__label">E-mail</label>
                                <input type="text" id="email-pass" class="modal-form__input">
                            </div>
                            <div class="modal-form__btns">
                                <button class="modal-form__btn modal-form__btn--rec-pass">{{__('Send password')}}</button>
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
