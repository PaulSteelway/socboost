<div class="modal" id="passModal" tabindex="-1" style="overflow: hidden" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="passModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="passModalLabel">
          {{__('Password recovery')}}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div id="recover-errors"></div>
        <div class="modal-form__container">
          <div class="modal-form__title">
            {{__('A new password will be sent to your inbox')}}
          </div>

          <form id="recover-psw" class="modal-form modal-form--recovery" method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}

            <div class="modal-form__field">
              <label for="email-pass" class="modal-form__label">
                {{__('E-Mail or Phone')}}
              </label>
              <input id="email" type="text" class="modal-form__input form-control" name="email"
              value="{{ $email ?? old('email') }}" required autofocus>
            </div>

            <div class="modal-form__btns">
              <button class="modal-form__btn modal-form__btn--rec-pass">{{__('Send password')}}</button>
              <button class="modal-form__btn modal-form__btn--close" type="button" data-dismiss="modal" aria-label="Close">
                {{__('Close')}}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
