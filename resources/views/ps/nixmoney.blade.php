<form id="payment" method="POST" action="https://www.nixmoney.com/merchant.jsp" style="display:none;">
  <input type="hidden" name="PAYEE_ACCOUNT" value="{{ $payeeAccount }}">
  <input type="hidden" name="PAYEE_NAME" value="{{ $payeeName }}">
  <input type="hidden" name="PAYMENT_AMOUNT" value="{{ $transaction->amount }}">
  <input type="hidden" name="PAYMENT_URL" value="{{ route('profile.topup.payment_message', ['result' => 'ok']) }}">
  <input type="hidden" name="NOPAYMENT_URL" value="{{ route('profile.topup.payment_message', ['result' => 'error']) }}">
  <input type="hidden" name="PAYMENT_ID" value="{{ $transaction->id }}">
  <input type="hidden" name="STATUS_URL" value="{{ $statusUrl }}">
  <input type="hidden" name="SUGGESTED_MEMO" value="{{ $comment }}">

  <input type="hidden" name="userid" value="{{ $user->id }}">
  <input type="hidden" name="walletid" value="{{ $wallet->id }}">
  <input type="hidden" name="BAGGAGE_FIELDS" value="userid walletid">

  <input type="submit" value="Pay Now!">
</form>

@push('scripts')
  <script>
    document.forms.payment.submit()
  </script>
@endpush
