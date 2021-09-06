<section class="offers">
  <div class="container">
    <h2 class="offers__title">
      {{__('Special offers')}}
    </h2>

    <div class="row">

      @foreach($packages as $package)
        @php
          $package_price = number_format(socialboosterPriceByAmount($package->price), 2, '.', '');
        @endphp
        <div class="col-12 col-md-6 col-lg-3">
          <div class="offer-card">
            <div class="offer-card__heading">
              <div class="offer-card__img">
{{--                <div class="bg-{{ $package->category->icon_class }} d-none d-md-block"></div>--}}
{{--                <img class="d-block d-md-none mob-img" src="" data-src="{{ $package->category->icon_img }}" width="24"  alt="">--}}
                <img src="{{ $package->category->icon_img }}" data-src="{{ $package->category->icon_img }}" width="24"  alt="">
              </div>
              <div class="offer-card__name">{{ $package->name }}</div>
            </div>

            <hr>

            <div class="offer-card__subscribers">
              {{ $package->qty }} {{__('subscribers for')}}
            </div>

            <div class="offer-card__price">
              {{ $package_price }}  {{ app()->getLocale() == 'en' ? '$' : '₽' }}
            </div>

            <div class="offer-card__btn-container">
              <button class="offer-card__btn" type="button" data-package-id="{{$package->id}}" data-package-name="{{$package->name}}" data-package-price="{{ $package_price }}" data-package-qty="{{ $package->qty }}" data-toggle="modal" data-target="#testOrderBtn">
                {{__('Order')}}
              </button>
            </div>

          </div>
        </div>
      @endforeach

    </div>
  </div>
</section>
