<script>
  var currentLocale = "{{ app()->getLocale() }}";
  var isAuth = {{json_encode(\Auth::check())}};
</script>

@push('scripts')

  @if (session()->get('phoneCodeModal'))
      <script>
          $('#phoneCodeModal').modal('show');
      </script>
  @endif

  <script src="{{asset('/js/phonescripts/phone.js')}}"></script>

  {{-- payment widgets --}}
  <script src="https://widget.cloudpayments.ru/bundles/cloudpayments"></script>
  <script src="https://widget.unitpay.money/unitpay.js"></script>
@endpush


{{-- sendpulse.com --}}
<script charset="UTF-8" src="//web.webpushs.com/js/push/4d75b6fac963b96c9572783e68a12039_1.js" async></script>
