@extends('admin.layouts.app')
@section('title')
    {{ __('Deposit details') }} {{ $deposit->name }}
@endsection
@section('breadcrumbs')
    <li><a href="{{route('admin.deposits.index')}}">{{ __('Deposits') }}</a></li>
    <li> {{ __('Deposit details') }}: {{ $deposit->name }}</li>
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
                    <h1 class="custom-font">{{ __('Deposit details') }} </h1>
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
                                <th scope="row">ID</th>
                                <td></td>
                                <td><a href="{{ route('admin.deposits.show', $deposit->id) }}"
                                       target="_top">{{ $deposit->id }}</a></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('User') }}</th>
                                <td>
                                    <a href="{{ route('admin.users.show', $deposit->user->id) }}">{{ $deposit->user->login }}
                                        - {{ $deposit->user->email }}</a></td>
                                <td><a href="mailto:{{ $deposit->user->email }}"
                                       target="_blank">{{ $deposit->user->email }}</a></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Invested') }}</th>
                                <td>{{ $deposit->invested }}{{ $deposit->currency->symbol }}</td>
                                <td><a href="{{ route('admin.currencies.edit', $deposit->currency->id) }}"
                                       target="_blank">{{ $deposit->currency->code }}</a></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Current balance') }}</th>
                                <td>{{ $deposit->balance }}{{ $deposit->currency->symbol }}</td>
                                <td><a href="{{ route('admin.currencies.edit', $deposit->currency->id) }}"
                                       target="_blank">{{ $deposit->currency->code }}</a></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Tariff plan') }}</th>
                                <td><a href="{{ route('admin.rates.show',['id'=>$deposit->rate_id]) }}">
                                        {{ $deposit->rate->name }}</a></td>
                                <td>{{ __('minimum investment') }}
                                    : {{ $deposit->rate->min }}{{ $deposit->currency->symbol }}, {{ __('maximum') }}
                                    : {{ $deposit->rate->max }}{{ $deposit->currency->symbol }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Duration') }}</th>
                                <td>{{ $deposit->duration }} {{ __('days') }}</td>
                                <td>{{ __('Closing') }}
                                    : {{ \Carbon\Carbon::parse($deposit->created_at)->addDays($deposit->duration) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Daily earnings') }}</th>
                                <td>{{ $deposit->daily }}%</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Overall') }}</th>
                                <td>{{ $deposit->overall }}%</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Payout') }}</th>
                                <td>{{ $deposit->payout }}%</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Created') }}</th>
                                <td>{{ $deposit->created_at }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Last update') }}</th>
                                <td>{{ $deposit->updated_at }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Status') }}</th>
                                <td>{{ $deposit->active ? __('Active') : __('Closed') }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Compounding') }}</th>
                                <td>{{ $deposit->reinvest ? $deposit->reinvest.'%' : __('no') }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Auto closing') }}</th>
                                <td>{{ $deposit->autoclose ? __('yes') : __('no') }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Last action') }}</th>
                                <td>{{ __($deposit->condition) }}</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                        <div>
                            <a type="button" class="btn btn-warning sure"
                               href="{{route('admin.deposits.block',['id'=>$deposit->id])}}">{{ __('Block deposit') }}</a>
                            <a type="button" class="btn btn-success sure"
                               href="{{route('admin.deposits.unblock',['id'=>$deposit->id])}}">{{ __('Unblock deposit') }}</a>
                        </div>
                        @if($deposit->transactions()->count() > 0)
                            <h3>{{ __('Transactions') }} ({{ $deposit->transactions->count() }}):</h3>
                            <ul class="list-group">
                                @foreach($deposit->transactions as $transaction)
                                    <li class="list-group-item">
                                        <a href="{{ route('admin.transactions.show', ['id'=>$transaction->id]) }}"
                                           target="_blank">
                                            {{ __('Type') }}:&nbsp;{{ __($transaction->type->name) }}
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            {{ __('Amount') }}
                                            :&nbsp;{{ $transaction->amount }}{{ $transaction->currency->symbol }}</a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        {{ __('Result') }}
                                        :&nbsp;{{ !empty($transaction->result) ? $transaction->result : __('empty') }}
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        {{ __('Status') }}
                                        :&nbsp;{{ $transaction->approved ? __('approved') : __('pending') }}
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        {{ __('Created') }}:&nbsp;{{ $transaction->created_at }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if (count($deposit->failedJobs()) > 0)
                            <h3>@lang('Failed earning jobs'):</h3>
                        <ul class="list-group">
                            @foreach($deposit->failedJobs() as $element)
                                <li class="list-group-item">
                                    {{ $element->id }}
                                    {{ explode("in", $element->exception)[0] }}
                                    {{ $element->failed_at }}
                                </li>
                            @endforeach
                        </ul>
                        <div>
                            <form action="{{ route('admin.failedjobs.retry') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="deposit_id" value={{ $deposit->id }}>
                                <input type="submit" class="btn-danger"
                                       value="{{ __('Restart failed jobs for this deposit') }}">
                            </form>
                        </div>
                        @endif
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
