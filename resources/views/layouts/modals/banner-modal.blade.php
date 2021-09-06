@if(!Auth::user()->discount_modal && !isFreePromotionSite())

  <div class="modal" id="bannerModal" tabindex="-1" role="dialog" style="overflow: hidden" data-backdrop="static" data-keyboard="false" aria-labelledby="bannerModalLbl" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body" style="position: relative">
          @if(app()->getLocale() !== 'en')
            <img src="/images/Ru-01.png" alt="">
          @else
            <img src="/images/En-01.png" alt="">
          @endif
          <a href="{{ URL::to('/premium_account') }}" class="discount__link">
            {{__('I want!')}}
          </a>
        </div>

      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      if (window.screen.width >= 768) {
        setTimeout(function () {
          $('#bannedModal').modal({
            show: true
          });

          $.ajax({
            url: '/disableModal',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            dataType: 'JSON',
            success: function (response) {
              location.href = "Modal disabled";
            },
            error: function (response) {
            }
          });
        }, 60000)
      }
    </script>
  @endpush

@endif
