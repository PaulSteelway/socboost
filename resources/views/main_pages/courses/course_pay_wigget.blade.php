@push('scripts')
  {{-- <script src="https://widget.unitpay.money/unitpay.js"></script> --}}
  <script type="text/javascript" defer>
  this.pay = function() {
    var payment = new UnitPay();
    payment.createWidget({
      publicKey: "{{ config('money.unitpay_public_key') }}",
      sum: {{ $price }},
      account: "course",
      domainName: "unitpay.money",
      signature: "{{ $signature }}",
      desc: "Покупка курса {{ $course }}",
      locale: "ru",
    });
    payment.success(function (params) {
      $.ajax({
        url: `/process_course_payment`,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: JSON.stringify(params),
        success: function (response) {
          if(response.url){
            window.open(response.url,'_blank');
          }
        },
        error: function (error) {
          console.log(error);
        }
      });
    });
    payment.error(function (message, params) {
      console.log(message);
    });
    return false;
  };
</script>

@endpush
