@php
  $merchant_id = env('FKASSA_MERCHANT_ID', 111978);
  $secret_word = config('freekassa.secret');
  $order_id = $paymentId;
  $order_amount = $amount;
  $sign = md5($merchant_id.':'.$order_amount.':'.$secret_word.':'.$order_id);

  // <input type='hidden' name='i' value='{{ $i }}'>
@endphp

<form method='get' action='http://www.free-kassa.ru/merchant/cash.php' id="payment" style="display:none;">
  <input type='hidden' name='m' value='<?=$merchant_id?>'>
  <input type='hidden' name='oa' value='<?=$order_amount?>'>
  <input type='hidden' name='o' value='<?=$order_id?>'>
  <input type='hidden' name='s' value='<?=$sign?>'>
  <input type='hidden' name='lang' value='ru'>
  <input type='hidden' name='us_login' value='<?=$user->login?>'>
  <input type='submit' name='pay' value='Оплатить'>
</form>

@push('scripts')
  <script>
    document.forms.payment.submit()
  </script>
@endpush
