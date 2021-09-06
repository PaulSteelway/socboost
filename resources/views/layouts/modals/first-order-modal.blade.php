<div class="modal" id="firstOrderModal" tabindex="-1" style="overflow: hidden" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="firstOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="firstOrderModalLabel">
          <small>
            {{ __('site.first-order') }}
          </small>
        </h5>
      </div>

      <div class="modal-body">
        <div class="modal-form__container">

          <div class="modal-form__title">
            {{ __('site.first-order-status') }}
          </div>

          <div class="modal-form__btns text-center d-block">
            <button class="modal-form__btn modal-form__btn--auth" type="button" data-dismiss="modal" aria-label="Close" onclick="ym(51742118,'reachGoal','spasibo'); return true;">
              {{__('Close')}}
            </button>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
