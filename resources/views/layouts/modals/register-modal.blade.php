<div class="modal" id="regModal" tabindex="-1" style="overflow: hidden" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="regModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="register-form" method="POST" action="{{ route('register') }}" name="signup" role="form" class="modal-form">
          {{ csrf_field() }}

          <div id="registration-steps">
            <h3>Keyboard</h3>
            <section>
              <div class="row">
                <div class="col-md-6 register-step__info register-left__element--first"></div>
                <div class="col-xs-12 col-sm-12 col-md-6 register-step__element d-flex flex-column justify-content-center">
                  <h5 class="modal-title" id="regModalLabel">
                    {{__('Sign Up')}}
                  </h5>
                  <div class="register_field">
                    <label for="email-reg" class="modal-form__label">E-mail <sup class="red">*</sup></label>
                    <input type="text" name="email" id="email-reg" class="modal-form__input">
                  </div>

                  <div class="register_field">
                    <label for="password" class="modal-form__label">
                      {{__('Your password')}} <sup class="red">*</sup>
                    </label>
                    <input type="password" name="password" id="password" class="modal-form__input" required>
                    <span id="password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                    <div class="register-errors" style="padding: 10px 10px 0 0;"></div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
