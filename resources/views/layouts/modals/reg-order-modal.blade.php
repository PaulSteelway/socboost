<div class="modal" id="regOrderModal" tabindex="-1" role="dialog" style="overflow: hidden"  data-backdrop="static" data-keyboard="false" aria-labelledby="regOrderModalLbl" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="regOrderModalLbl" style="text-align: center;">
          {{__('Enter your e-mail')}}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div id="register-order-errors"></div>

        <div class="modal-form__container">
          <form id="register-order-form" method="POST" action="{{ route('register.order') }}" name="register-order" role="form" class="modal-form">
            {{ csrf_field() }}

            <div class="reg-order__fields">
              <label for="emailRegOrder" class="modal-form__label">
                E-mail <sup class="red">*</sup>
              </label>
              <input type="text" name="email" id="emailRegOrder" class="modal-form__input">
            </div>

            <div class="modal-form__btns">
              <button class="modal-form__btn modal-form__btn--reg-now" onclick="ym(51742118,'reachGoal','zakaz'); return true;">
                {{__('Continue')}}
              </button>
              <button class="modal-form__btn modal-form__btn--close" type="button" data-dismiss="modal" aria-label="Close">
                {{__('Close')}}
              </button>
            </div>

          </form>

          <div class="text-center">
            <a href="#" data-toggle="modal" data-target="#authModal">
              {{ __('site.already_register') }}
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
