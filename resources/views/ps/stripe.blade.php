<script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
<script src="https://js.stripe.com/v3/"></script>

<script>
var stripe = Stripe('{{ config('money.stripe_public_key') }}');
stripe.redirectToCheckout({'sessionId': '{{ $stripe_id }}'});
</script>
