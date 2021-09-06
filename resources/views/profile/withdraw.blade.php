@extends('layouts.profile')

@section('title', __('Withdraw') . ' - ' . __('site.site'))

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
            <form class="form-horizontal" method="POST" action="{{ route('profile.withdraw') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label class="col-md-4 control-label" for="wallet">{{ __('Wallet') }}</label>
                    <div class="col-md-4">
                        <select id="wallet" name="wallet_id" class="form-control">
                            @foreach(getUserWallets() as $wallet)
                                <option value="{{ $wallet['id'] }}">{{ $wallet['payment_system']['name'] }}
                                    - {{ number_format($wallet['balance'], $wallet['currency']['precision']) }}{{ $wallet['currency']['symbol'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="amount" class="col-md-4 control-label">{{ __('Amount') }}</label>
                    <div class="col-md-6">
                        <input id="amount" type="number" step="any" class="form-control"
                               name="amount" required>

                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-4">
                        <?= captcha_img() ?>
                    </div>
                    <label class="col-md-4 control-label" for="captcha">{{ __('Enter captcha code') }}</label>
                    <div class="col-lg-6">
                        <input type="text" name="captcha" id="captcha" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Process withdraw') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /.card -->
@endsection
