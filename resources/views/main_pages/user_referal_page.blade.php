@extends('layouts.customer')

@section('title', 'Public offer' . ' - ' . __('site.site'))

  @push('styles')
    <style>
    header{
      display: none !important;
    }
    </style>
  @endpush
  
@section('content')
    <main style=" min-height: 73vh">
        {{--REFERAL--}}
        <section class="referal-page">
            <div class="referal-block-container">
                <div class="referal-block__logo">
                    <img src="/svg/logo-dark.svg" alt="">
                </div>
                <div class="referal-block">
                    <div class="referal-block__img">
                        <img src="/{{$referral_user->avatart ?? 'svg/account-img-new.svg'}}" alt="">
                    </div>
                    <div class="referal-block__text">
                        {{ $referral_user->name . $referral_user->lastname }} {{__('Invited you and gives')}} {{number_format(socialboosterPriceByAmount(100), 2, '.', '')}} {{ app()->getLocale() == 'en' ? '$' : '₽' }}
                        . {{__('In order to receive them, you must register on our website.')}}
                    </div>
                    <a href="#" class="referal-block__btn" data-toggle="modal"
                       data-target="#regModal"> {{__('Sign Up')}}</a>
                </div>
            </div>
        </section>
    </main>
@endsection
