@extends(isFreePromotionSite() ? 'layouts.free_promotion.layout' : 'layouts.customer')

@section('title', __('Become a premium user right now!') . ' - ' . __('site.site'))

@section('content')

  @push('styles')
    <style>
      .premium_acc_star {
        position: absolute;
        bottom: -10px;
        left: 190px;
      }

      @media (max-width: 1023px) {
        .premium_acc_star {
          position: absolute;
          bottom: 30%;
          left: 30%;
        }
      }

      @media (max-width: 767px) {
        .premium_acc_star {
          position: absolute;
          bottom: 35%;
          left: 35%;
        }
      }
    </style>
  @endpush

    <main style="padding-top: 100px; min-height: 73vh">
        <section class="premium-promotion">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="app__block app__block--ios">
                            <div class="premium-promotion__title">{{__('Become a premium user right now!')}}</div>
                            <div class="premium-promotion__subtitle">{{__('Only for 3$ per month! Try 3 days trial period!')}}</div>
                            <div class="app__img app__img--premium app__img--ios profile-info__photo--img" style="background-image: url('{{ asset(Auth::user()->avatar ?? '/svg/account-img-new.svg') }}')">
                                <img class="premium_acc_star" src="/images/profile/star.svg" width="90" height="90" alt="">
                            </div>
                            <div class="premium-promotion__text">{{__('Get benefits that are not available to other users! Discount up to 15% on each order, premium promotions and promotional codes, priority support and much more!')}}</div>
                            <form method="POST" action="{{ route('profile.premium.purchase') }}">
                                {{ csrf_field() }}
                                <button class="app__btn app__btn--prem-prom">{{__('Become a premium user')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="premium-acc">
            <div class="container">
                <div class="premium-acc__title">{{__('Premium Users\' Benefits')}}</div>
                <div class="premium-acc__subtitle">{{__('Become a privileged user with special status and get special tricks that others don\'t know!')}}</div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="premium-acc__img">
                            <img src="/images/premium.png" alt="">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="premium-acc__advantages">
                            <div class="premium-acc__advantages-name">{{__('Pay less')}}</div>
                            <div class="premium-acc__advantages-text">{{__('10% discount on absolutely all packages on the site')}}</div>
                            <div class="premium-acc__advantages-name">{{__('24/7')}}</div>
                            <div class="premium-acc__advantages-text">{{__('Premium support that responds without queuing 24 hours a day')}}</div>
                            <div class="premium-acc__advantages-name">{{__('Private Channel')}}</div>
                            <div class="premium-acc__advantages-text">{{__('Get access to a private Telegram channel where we distribute vouchers and promotional codes for various amounts that are not available to ordinary users daily!')}}</div>
                            <div class="premium-acc__advantages-name">{{__('VIP Packages')}}</div>
                            <div class="premium-acc__advantages-text">{{__('You\'ll see unique packages that are only available to premium users.')}}</div>
                            <div class="premium-acc__advantages-name">{{__('Just one more bonus')}}</div>
                            <div class="premium-acc__advantages-text">{{__('Get 5% bonus on your balance to any amount from your top up!')}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="best-offer">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="app__block app__block--ios">
                            <div class="best-offer__title">{{__('Best offer for you')}}</div>
                            <div class="best-offer__img">
                                <img src="/images/best-offer.png" alt="">
                            </div>
                            <div class="app__text">{{__('Especially for you, premium status for 3 days trial! After it just 3$ per month to keep all privileges!')}}</div>
                            <form method="POST" action="{{ route('profile.premium.purchase') }}">
                                {{ csrf_field() }}
                                <button class="app__btn app__btn--best-offer">{{__('Become a premium user')}}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')
  @if(\Auth::check() && session()->exists(\Auth::id() . '_' . 'cloudpayments'))
    <script>
      var cloudpayments = {!! json_encode(session()->pull(\Auth::id() . '_' . 'cloudpayments')) !!};
    </script>
  @endif
@endpush
