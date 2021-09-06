<div class="modal" id="phoneChangeModal" tabindex="-1" role="dialog" style="overflow: hidden" data-backdrop="static" data-keyboard="false" aria-labelledby="phoneChangeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="phoneChangeModalLabel" style="margin-left:-20px;margin-right:-20px;">
          {{ __('Enter new phone') }}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="modal-form__container">

          <form id="phone-change-form" method="POST" name="phoneChange" role="form" class="modal-form" action="{{ route('verification.phone.change') }}">
            {{ csrf_field() }}
            <input type="tel" name="phone" class="modal-form__input" id="modal-phone" required>
            <span id="modal-error-msg"></span>
            <div class="modal-form__btns" style="margin-top: 19px;">
              <button class="modal-form__btn modal-form__btn--reg-now"
              id="modalBtnSubmit">{{__('Change and send SMS')}}</button>
            </div>
          </form>

          <div style="text-align: center;">
            <div class="modal-add-link" onclick="openChangeCodeModal()">
              {{ __('Back') }}
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>


  @push('scripts')
    <script src="{{asset('/js/phonescripts/phoneModal.js')}}"></script>
  @endpush
</div>

@push('scripts')
  <script>
    function openChangeCodeModal() {
      $('#phoneChangeModal').modal('hide');
      $('#phoneCodeModal').modal('show');
    }
</script>
@endpush
