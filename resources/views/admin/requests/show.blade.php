@extends('admin.layouts.app')
@section('title')
    {{ __('Withdrawal request details') }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.requests.index')}}">{{ __('Withdrawal requests') }}</a></li>
    <li> {{ __('Withdrawal request details') }}</li>
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <!-- col -->
        <div class="col-md-12">

            <!-- tile -->
            <section class="tile">

                <!-- tile header -->
                <div class="tile-header dvd dvd-btm">
                    <h1 class="custom-font">{{ __('Withdrawal request details') }}</h1>
                    <ul class="controls">
                        <li>
                            <a role="button" class="tile-fullscreen">
                                <i class="fa fa-expand"></i> {{ __('Fullscreen') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /tile header -->

                <!-- tile body -->
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th scope="row">{{ __('Amount') }}</th>
                                <td>{{ $transaction->amount }}{{ $transaction->currency->symbol }}</td>
                                <td>
                                    <a href="{{ route('admin.currencies.edit', $transaction->currency->id) }}"
                                       target="_blank">{{ $transaction->currency->code }}</a></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Wallet') }}</th>
                                <td style="font-weight: bold;">{{ $transaction->wallet->external }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Payment system') }}</th>
                                <td>
                                    <a href="{{ route('admin.payment-systems.edit', $transaction->paymentSystem->id) }}"
                                       target="_blank">{{ $transaction->paymentSystem->name }}</a></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('User') }}</th>
                                <td>
                                    <a href="{{ route('admin.users.show', $transaction->user->id) }}"
                                       target="_blank">{{ $transaction->user->login }}</a></td>
                                <td><a href="mailto:{{ $transaction->user->email }}"
                                       target="_blank">{{ $transaction->user->email }}</a></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('User wallet balance') }}</th>
                                <td>{{ $transaction->wallet->balance }}{{ $transaction->currency->symbol }}
                                    <br>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Order status') }}</th>
                                <td>{{ $transaction->isApproved() ? __('approved') : __('new') }}</td>
                                <td> </td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Order created') }}</th>
                                <td>{{ $transaction->created_at }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Order last update') }}</th>
                                <td>{{ $transaction->updated_at }}</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                        <div>
                            <a type="button" class="btn btn-success sure"
                               href="{{route('admin.requests.approve',['id'=>$transaction->id])}}">{{ __('Approve') }}</a>
                            <a type="button" class="btn btn-danger sure"
                               href="{{route('admin.requests.reject',['id'=>$transaction->id])}}">{{ __('reject') }}</a>
                        </div>
                    </div>
                </div>
                <!-- /tile body -->

            </section>
            <!-- /tile -->

        </div>
        <!-- /col -->
    </div>
    <!-- /row -->

@endsection
