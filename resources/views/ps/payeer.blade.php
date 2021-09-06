<form id="payment" method="POST" action="https://payeer.com/merchant/"  style="display:none;">
  <input type="hidden" name="m_shop" value="{{ $merchantId }}">
  <input type="hidden" name="m_orderid" value="{{ $paymentId }}">
  <input type="hidden" name="m_amount" value="{{ $amount }}">
  <input type="hidden" name="m_curr" value="{{ $currency }}">
  <input type="hidden" name="m_desc" value="{{ $comment }}">

  <input type="hidden" name="m_sign" value="{{ $signature }}">
  <input type="submit" name="m_process" value="send"/>
</form>

@push('scripts')
  <script>
    document.forms.payment.submit()
  </script>
@endpush
