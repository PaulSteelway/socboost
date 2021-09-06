<form id="payment" method="POST" action="https://enpay.ru/payment/{{ session('lang') == 'ru' ? '' : session('lang') }}" style="display:none;">
  <input type="hidden" name="merchant" value="{{ $merchantId }}">
  <input type="hidden" name="description" value="{{ $comment }}">
  <input type="hidden" name="amount" value="{{ $transaction->amount }}">
  <input type="hidden" name="currency" value="{{ $currency->code == 'RUR' ? 'RUB' : $transaction->code }}">
  <input type="hidden" name="order_id" value="{{ $transaction->result }}">
  <button type="submit">Pay</button>
</form>

@push('scripts')
  <script>
    document.forms.payment.submit()
  </script>
@endpush
