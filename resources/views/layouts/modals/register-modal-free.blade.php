<div class="modal" id="regModalFree" tabindex="-1" style="overflow: hidden" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="regModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div id="register-email-errors"></div>
        <form id="register-form-by-email" method="POST" action="{{ route('register.order') }}" name="signup" role="form" class="modal-form">
          {{ csrf_field() }}

          <input type="hidden" name="just_register" value="true">

          <div id="registration-steps">
            <div class="row" style="height: 100%">
              <div class="col-md-6 register-step__info register-left__element--first"></div>
              <div class="col-xs-12 col-sm-12 col-md-6 register-step__element">
                <h5 class="modal-title" id="regModalLabel">
                  {{__('Регистрация')}}
                </h5>

                <div class="register_field register_field--email">
                  <label for="email-reg" class="modal-form__label">
                    E-mail <sup class="red">*</sup>
                  </label>
                  <input type="text" name="email" id="email-reg" class="modal-form__input">
                </div>

                <div class="modal-form__btns">
                  <button class="modal-form__btn modal-form__btn--auth">
                    {{ __("Зарегистрироваться") }}
                  </button>
                  <button class="modal-form__btn modal-form__btn--pass" type="button" data-toggle="modal" data-target="#authModalFree" data-dismiss="modal">
                    {{ __("У меня уже есть аккаунт") }}
                  </button>
                </div>
              </div>

            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
