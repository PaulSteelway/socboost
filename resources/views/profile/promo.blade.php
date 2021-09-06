@extends('layouts.profile')

@section('title', __('Promo') . ' - ' . __('site.site'))

@section('content')
    <div class="card card-outline-secondary">
        <div class="card-header">
            <strong>{{ __('Hi') }}, {{ getUserName() }}</strong>
            <strong style="float:right;">{{ __('Your balance') }}:
                @foreach(getUserBalancesByCurrency(true) as $symbol => $balance)
                    {{ $symbol }} {{ number_format($balance, 2) }}{{ !$loop->last ? ',' : '' }}
                @endforeach
            </strong>
        </div>
        <div class="card-body">
            @include('partials.inform')
            <h3>{{ __('Your referral link') }}</h3>
            <input type="text" value="{{ getUserReferralLink() }}" class="form-control"
                   style="padding:5px 10px 5px 10px; font-weight:bold;" disabled>

            <hr>

            <p>{{ __('here can be banners ...') }}</p>
        </div>
    </div>
@endsection
