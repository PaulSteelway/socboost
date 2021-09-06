<div class="modal fade" id="testOrderBtn" tabindex="-1" style="overflow: hidden" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="testOrderBtnLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="testOrderBtnLabel">
          {{__('Purchase package')}}
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body" style="margin-bottom: 30px">
        <div id="register-errors"></div>

        <form id="test_package_purchase" name="checkout" method="post" action="{{ route('profile.topup')}}">
          {{ csrf_field() }}
          <input hidden name="package_id" value="">
          <div class="current-service__packet-amount">
            <div class="current-service__block-left">
              <h4 class="current-service__packet-title">
                {{ __("Quantity") }}:
              </h4>
              <input disabled name="count" type="number" class="current-service__packet-input" value="">
            </div>
            <div class="current-service__block-right">
              <h4 class="current-service__packet-link-title">
                {{ __("Link") }}:
              </h4>
              <input name="link" type="text" class="current-service__packet-link" value="">
            </div>
          </div>

          <div class="current-service__packet-total">
            <div class="current-service__block-left">
              <div class="current-service__packet-total-name">
                {{ __("Charge") }}:
              </div>
            </div>

            <div class="current-service__block-right">
              <span id="packagePricePlan">0</span>
              {{ app()->getLocale() == 'en' ? '$' : '₽' }}
            </div>
          </div>

          <div class="">
            <button type="submit" class="current-service__make-order-btn">
              {{ __("Submit") }}
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
