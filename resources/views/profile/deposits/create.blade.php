@extends('layouts.profile')

@section('title', __('Create deposit') . ' - ' . __('site.site'))

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
            <form action="{{ route('profile.deposits.store') }}" method="POST" target="_top">
                {{ csrf_field() }}

                <div class="form-group">
                    <label class="col-md-4 control-label" for="rate">{{ __('Rate') }}</label>
                    <div class="col-md-4">
                        <select id="rate" name="rate_id" class="form-control">
                            @foreach(getTariffPlans() as $plan)
                                <option value="{{ $plan['id'] }}"{{ isset($rate) && $rate['id'] == $plan['id'] ? ' selected' : '' }}>{{ $plan['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <p class="help-block" style="margin-left:20px;">
                    <strong>{{ __('Selected rate') }}</strong>: {{ $rate['name'] }}. {{ __('Minimum investment') }}
                    : {{ number_format($rate['min'], $rate['currency']['precision']) }}{{ $rate['currency']['symbol'] }}
                    , {{ __('Maximum investment') }}
                    : {{ number_format($rate['max'], $rate['currency']['precision']) }}{{ $rate['currency']['symbol'] }}
                    , {{ __('Daily interest') }}: {{ $rate['daily'] }}%</p>

                <div class="form-group">
                    <label class="col-md-4 control-label" for="wallet">{{ __('Wallet') }}</label>
                    <div class="col-md-4">
                        <select id="wallet" name="wallet_id" class="form-control">
                            @foreach(getUserWallets($rate['currency']['id']) as $wallet)
                                <option value="{{ $wallet['id'] }}">{{ $wallet['payment_system']['name'] }}
                                    - {{ number_format($wallet['balance'], $wallet['currency']['precision']) }}{{ $wallet['currency']['symbol'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="amount" class="col-md-4 control-label">{{ __('Amount') }}</label>
                    <div class="col-md-6">
                        <input id="amount" type="number" step="any" class="form-control" name="amount"
                               value="{{ old('amount') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Create deposit') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /.card -->
@endsection

@push('scripts')
    <script>
        jQuery(document).ready(function(){
            jQuery('#rate').change(function(){
                var val = jQuery(this).val();
                location.assign('/deposits/create?rate_id='+val);
            });
        });
    </script>
@endpush
