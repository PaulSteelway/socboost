@if(session()->has('success'))
    <div id="msg-block" class="msg-inform alert alert-success" role="alert">
        @lang(session()->get('success'))
    </div>
@endif

@if(session()->has('error'))
    <div id="msg-block" class="msg-inform alert alert-danger" role="alert">
        @lang(session()->get('error'))
    </div>
@endif

@if ($errors->any())
    <div id="msg-block" class="msg-inform alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ __($error) }}</li>
            @endforeach
        </ul>
    </div>
@endif


@push('scripts')
  <script type="text/javascript">
    $(window).on('load', function () {
      setTimeout(function () {
        $('#msg-block').fadeOut( "slow", function() {
          $('#msg-block').hide()
        });
      }, 5000)
    });
  </script>
@endpush
