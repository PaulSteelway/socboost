<form id="payment" method="POST" action="https://wallet.advcash.com/sci/" style="display:none;">
  <input type="hidden" name="ac_account_email" value="{{ $accountEmail }}">
  <input type="hidden" name="ac_sci_name" value="{{ $sciName }}">
  <input type="hidden" name="ac_amount" value="{{ $amount }}">
  <input type="hidden" name="ac_currency" value="{{ $currency->code }}">
  <input type="hidden" name="ac_order_id" value="{{ $order->id }}">
  <input type="hidden" name="ac_sign" value="{{ $sign }}">
  <input type="hidden" name="ac_comments" value="{{ $comment }}">
  <!-- Merchant custom fields -->

  <input type="hidden" name="login" value="{{ $user->id }}">

  <input type="submit" value="Pay Now!">
</form>

@push('styles')
  <script>
    document.forms.payment.submit()
  </script>
@endpush
