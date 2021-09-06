@extends('layouts.profile')

@section('title', 'Пополнение баланса' . ' - ' . __('site.site'))

@section('content')
    <div class="row" style="padding:30px 0;">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            @include('partials.inform')
            <form method="POST" action="{{ route('profile.topup.voucher') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="code" class="control-label">{{ __('Voucher code') }}</label>
                    <input type="text" class="form-control" name="code" required>
                </div>

                <div class="form-group" style="margin-top: 10px;">
                    <div class="col-md-6 col-md-offset-4 xs-pl-null">
                        <button type="submit" class="btn btn-primary btn-add-funds">
                            {{ __('Apply') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
