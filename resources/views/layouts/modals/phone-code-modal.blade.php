<div class="modal" id="phoneCodeModal" tabindex="-1" role="dialog" style="overflow: hidden" data-backdrop="static" data-keyboard="false" aria-labelledby="phoneCodeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="phoneCodeModalLabel" style="margin-left:-20px;margin-right:-20px;">
          {{ \Auth::check() && empty(\Auth::user()->phone_verified_at) ? __('Enter code verification') : __('Enter Code From SMS')}}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">

        <div class="modal-form__container">
          <form id="phone-code-form" method="POST" name="phoneCode" role="form" class="modal-form" action="{{ route('verification.phone') }}">
            {{ csrf_field() }}
            <input type="text" name="code" class="modal-form__input" required style="text-align: center; margin-bottom: 19px;">
            <div class="modal-form__btns">
              <button class="modal-form__btn modal-form__btn--reg-now">
                {{__('Verify')}}
              </button>
            </div>
          </form>

          @if (session()->get('phoneCodeModal'))
            <div class="row" style="text-align: center;">
              <div class="col-md-6">
                <div class="modal-add-link" data-dismiss="modal">
                  {{ __('Verify later') }}
                </div>
              </div>
              <div class="col-md-6">
                <div class="modal-add-link" onclick="openChangePhoneModal()">
                  {{ __('Change phone') }}
                </div>
              </div>
            </div>
          @endif
        </div>

      </div>
    </div>
  </div>
</div>

@push('scripts')
  <script>
    function openChangePhoneModal() {
      $('#phoneCodeModal').modal('hide');
      $('#phoneChangeModal').modal('show');
    }
</script>
@endpush
