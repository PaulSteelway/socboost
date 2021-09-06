@extends('admin.layouts.app')
@section('title')
    {{ __('Transaction details') }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.transactions.index')}}">{{ __('Transactions') }}</a></li>
    <li> {{ __('Transaction details') }}</li>
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
                    <h1 class="custom-font">{{ __('Transaction details') }}</h1>
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
                                <th scope="row">{{ __('Type') }}</th>
                                <td>{{ __($transaction->type->name) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('User') }}</th>
                                <td>
                                    <a href="{{ route('admin.users.show',$transaction->user->id) }}">{{ $transaction->user->login }}</a>
                                </td>
                                <td><a href="mailto:{{ $transaction->user->email }}">{{ $transaction->user->email }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Amount') }}</th>
                                <td>{{ $transaction->amount }}{{ $transaction->currency->symbol }}</td>
                                <td>
                                    <a href="{{ route('admin.currencies.edit', $transaction->currency_id) }}">{{$transaction->currency->code}}</a>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Payment system') }}</th>
                                <td>
                                    @if (empty($transaction->payment_system_id))
                                        -
                                    @else
                                        <a href="{{ route('admin.payment-systems.edit', $transaction->payment_system_id) }}">{{ $transaction->paymentSystem->name }}</a>
                                    @endif
                                </td>
                                <td></td>
                            </tr>
                            @if($transaction->deposit_id)
                                <tr>
                                    <th scope="row">{{ __('Deposit ID') }}</th>
                                    <td></td>
                                    <td>
                                        <a href="{{ route('admin.deposits.show',['id'=>$transaction->deposit_id]) }}">{{ $transaction->deposit_id }}</a>
                                    </td>
                                </tr>
                            @endif
                            @if(null !== $transaction->rate)
                                <tr>
                                    <th scope="row">{{ __('Tariff plan') }}</th>
                                    <td>
                                        <a href="{{ route('admin.rates.show',['id'=>$transaction->rate_id]) }}">{{ $transaction->rate->name }}</a>
                                    </td>
                                    <td></td>
                                </tr>
                            @endif
                            @if($transaction->batch_id )
                            <tr>
                                <th scope="row">{{ __('Batch ID') }}</th>
                                <td></td>
                                <td>{{ $transaction->batch_id }}</td>
                            </tr>
                            @endif
                            @if(!empty($transaction->result))
                            <tr>
                                <th scope="row">{{ __('Payment system response') }}</th>
                                <td>{{ $transaction->result }}</td>
                                <td></td>
                            </tr>
                            @endif
                            <tr>
                                <th scope="row">{{ __('Created') }}</th>
                                <td>{{ $transaction->created_at }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Last update') }}</th>
                                <td>{{ $transaction->updated_at }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Approved') }}</th>
                                <td>{{ $transaction->approved ? __('yes') : __('no') }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Transaction logs') }}</th>
                                <td colspan="2"><textarea readonly
                                                          style="width:100%; height: 100px;">{{ $transaction->log }}</textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>

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
