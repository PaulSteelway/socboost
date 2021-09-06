@extends(isFreePromotionSite() ? 'layouts.free_promotion.layout' : 'layouts.profile')

@section('content')

  <main>
    <section id="our-services" class="our-services">
      <div class="container">

        <div class="row">
          <div class="col-sm-12 text-center">

            <br><br><br><br>
            <div class="errors ">
              <h2>
                @yield('error')
              </h2>

              <a href="/" class="redirect">{{ __('site.site') }}</a>
            </div>
            <br><br><br><br>

          </div>
        </div>

      </div>
    </section>
  </main>
@endsection
